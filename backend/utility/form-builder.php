<?php
/*
 * LETSSSS GOOOOOOOOOOOOOOOOO FORM BUILDER KINGGGGG
 * Filament kalah ni boss :)
 * Nyuri ide dikit dari filament 
 */ 

require_once "utils.php";

function update_dynamic(mysqli $conn, string $table, array $schema, array $post, int|string $id, string $id_name = "id") {
    $set = [];
    $types = '';
    $values = [];

    foreach ($schema as $col) {
		if ($col['COLUMN_KEY'] === 'PRI') continue;

        $name = $col['COLUMN_NAME'];
        if (!isset($post[$name])) continue;

        $set[] = "$name = ?";
        $values[] = $post[$name];

        $types .= match ($col['DATA_TYPE']) {
            'int', 'bigint', 'smallint' => 'i',
            'float', 'double' => 'd',
            default => 's'
        };
    }

    $types .= is_string($id) ? "s" : "i";
    $values[] = $id;

    $sql = "UPDATE $table SET " . implode(',', $set) . " WHERE $id_name = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, $types, ...$values);
    return mysqli_stmt_execute($stmt);
}


function fetch_by_id(mysqli $conn, string $table, int|string $id, string $id_name = "id"): array|null {
	$sql = "SELECT * FROM $table WHERE $id_name = ? LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, is_string($id) ? "s" : "i", $id);
    mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);
	$data = mysqli_fetch_assoc($result);
	return $data;
}


function map_input_type(string $data_type): string {
    return match ($data_type) {
        'int', 'bigint', 'smallint' => 'number',
        'varchar', 'char' => 'text',
        'text', 'longtext' => 'textarea',
        'date' => 'date',
        'datetime', 'timestamp' => 'datetime-local',
        'enum' => 'select',
        'tinyint' => 'checkbox',
        default => 'text'
    };
}

function insert_dynamic(mysqli $conn, string $table, array $schema, array $post) {
    $fields = [];
    $placeholders = [];
    $types = '';
    $values = [];

    foreach ($schema as $col) {
		if ($col['EXTRA'] === 'auto_increment') continue;

        $name = $col['COLUMN_NAME'];
        if (!isset($post[$name])) continue;

        $fields[] = $name;
        $placeholders[] = '?';
        $values[] = $post[$name];

        $types .= match ($col['DATA_TYPE']) {
            'int', 'bigint', 'smallint' => 'i',
            'float', 'double' => 'd',
            default => 's'
        };
    }

    $sql = "INSERT INTO $table (" . implode(',', $fields) . ") VALUES (" . implode(',', $placeholders) . ")";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, $types, ...$values);
    return mysqli_stmt_execute($stmt);
}


function get_table_schema(mysqli $conn, string $table): array {
    $sql = "
        SELECT 
            COLUMN_NAME,
            DATA_TYPE,
            COLUMN_TYPE,
            IS_NULLABLE,
            COLUMN_KEY,
            EXTRA
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = ?
        ORDER BY ORDINAL_POSITION
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $table);
    mysqli_stmt_execute($stmt);

    return mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
}

function parse_enum(string $column_type): array {
    preg_match("/^enum\((.*)\)$/", $column_type, $matches);
    if (!isset($matches[1])) return [];

    return array_map(
        fn($v) => trim($v, "'"),
        explode(',', $matches[1])
    );
}

function get_foreign_keys(mysqli $conn, string $table): array {
	// agak serem
    $sql = "
        SELECT 
            COLUMN_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = ?
        AND REFERENCED_TABLE_NAME IS NOT NULL
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $table);
    mysqli_stmt_execute($stmt);

    $rows = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);

    $fks = [];
    foreach ($rows as $row) {
        $fks[$row['COLUMN_NAME']] = $row;
    }
    return $fks;
}

function fetch_fk_options(mysqli $conn, string $table, string $id_col): array {
    $sql = "SELECT $id_col, $id_col AS label FROM $table";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function validate_from_schema(array $schema, array $input): array {
    $errors = [];

    foreach ($schema as $col) {
        $name = $col['COLUMN_NAME'];

        if ($col['EXTRA'] === 'auto_increment') continue;
		
		if (is_image_column($col)) continue;

		if ($col['IS_NULLABLE'] === 'NO' && empty($input[$name])) {
			print_r($input);
            $errors[$name] = "$name is required";
            continue;
		}

        if (!isset($input[$name])) continue;

        if (in_array($col['DATA_TYPE'], ['int', 'bigint']) && !ctype_digit($input[$name])) {
            $errors[$name] = "$name must be integer";
        }

        if ($col['DATA_TYPE'] === 'enum') {
            $allowed = parse_enum($col['COLUMN_TYPE']);
            if (!in_array($input[$name], $allowed)) {
                $errors[$name] = "$name invalid option";
            }
        }
    }

    return $errors;
}

function handle_image_upload(
    string $field,
    string $upload_dir = '../../../../upload/profile',
	int $quality = 75,
): ?string {

    if (
        !isset($_FILES[$field]) ||
        $_FILES[$field]['error'] !== UPLOAD_ERR_OK
    ) {
        return null;
    }

    $tmp  = $_FILES[$field]['tmp_name'];
    $name = $_FILES[$field]['name'];

    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
        return null;
    }

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $filename = uniqid($field . '_') . '.' . $ext;
    $dest = rtrim($upload_dir, '/') . '/' . $filename;

    compress_image($tmp, $dest, $quality);

    return $filename;
}


function generate_form($conn, array $schema, array $foreign_keys, array $data = [], string $mode = 'create') {

    echo "<table class='info-table'>";

    foreach ($schema as $col) {

        $name = $col['COLUMN_NAME'];

        if ($col['EXTRA'] === 'auto_increment') continue;

        if ($mode === 'edit' && $col['COLUMN_KEY'] === 'PRI') {
            echo "<input type='hidden' name='$name' value='{$data[$name]}'>";
            continue;
        }

        $value    = $data[$name] ?? '';
        $required = $col['IS_NULLABLE'] === 'NO' ? 'required' : '';

        echo "<tr>";
        echo "<th class='info-label'>" . labelize($name) . "</th>";
        echo "<td class='info-value'>";

        if (is_image_column($col)) {

            if ($mode === 'edit' && $value) {
				if ($name === "profile_picture") echo "<img src='../../../../upload/profile/$value' style='max-height:80px; display:block; margin-bottom:6px'>";
				elseif ($name === "banner") echo "<img src='../../../../upload/banner/$value' style='max-height:80px; display:block; margin-bottom:6px'>";
				else echo "<img src='../../../../upload/$value' style='max-height:80px; display:block; margin-bottom:6px'>";
            }

            echo "<input class='info-value info-edit' type='file' name='$name' accept='image/*' required>";

        } elseif ($col['DATA_TYPE'] === 'enum') {

            $options = parse_enum($col['COLUMN_TYPE']);
            echo "<select class='info-value info-edit' name='$name' $required>";
            foreach ($options as $opt) {
                $sel = ($opt == $value) ? 'selected' : '';
                echo "<option value='$opt' $sel>$opt</option>";
            }
            echo "</select>";

        } elseif (isset($foreign_keys[$name])) {

            $fk = $foreign_keys[$name];
            $options = fetch_fk_options(
                $conn,
                $fk['REFERENCED_TABLE_NAME'],
                $fk['REFERENCED_COLUMN_NAME']
            );

            echo "<select class='info-value info-edit' name='$name' $required>";
            foreach ($options as $opt) {
                $sel = ($opt['id'] == $value) ? 'selected' : '';
                echo "<option value='{$opt['id']}' $sel>{$opt['label']}</option>";
            }
            echo "</select>";

        } elseif (in_array($col['DATA_TYPE'], ['text', 'longtext'])) {

            echo "<textarea class='info-value info-edit' name='$name' $required>$value</textarea>";

        } else {

            $type = map_input_type($col['DATA_TYPE']);
            echo "<input class='info-value info-edit' type='$type' name='$name' value='$value' $required>";
        }

        echo "</td>";
        echo "</tr>";
    }

	echo "</table>";
}


function delete_by_id(mysqli $conn, string $table, int $id, string $id_name = "id"): bool {
    $sql = "DELETE FROM $table WHERE $id_name = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    return mysqli_stmt_execute($stmt);
}

function is_image_column(array $col): bool {
    if (!in_array($col['DATA_TYPE'], ['varchar', 'char', 'text'])) {
        return false;
    }

    $name = strtolower($col['COLUMN_NAME']);

    return preg_match('/(image|img|photo|avatar|thumbnail|banner|cover|profile_picture)/', $name);
}


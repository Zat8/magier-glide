<?php

function number_to_word($number) {
    $map = [
        9 => 'first',
        8 => 'second',
        7 => 'third',
        6 => 'fourth',
        5 => 'fifth',
        4 => 'sixth',
        3 => 'seventh',
        2 => 'eighth',
        1 => 'ninth'
    ];

    return $map[$number] ?? null;
}

function compress_image($source, $destination, $quality) {
    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
        imagejpeg($image, $destination, $quality);  // compress JPEG
    }

    elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
        imagepng($image, $destination, 9); // PNG uses level 0-9
    }

    elseif ($info['mime'] == 'image/webp') {
        $image = imagecreatefromwebp($source);
        imagewebp($image, $destination, $quality);
    }

    return $destination;
}

function paginate($conn, $table, $page = 1, $limit = 10) {
	/* TODO: fixed table name
    $allowed_tables = ['users', 'posts', 'comments', 'products'];

    if (!in_array($table, $allowed_tables)) {
        die("Invalid table name.");
	}

	 */

    $sqlCount = "SELECT COUNT(*) FROM $table";
    $offset = ($page - 1) * $limit;

    $stmtCount = mysqli_prepare($conn, $sqlCount);
    mysqli_stmt_execute($stmtCount);
    mysqli_stmt_bind_result($stmtCount, $total_rows);
    mysqli_stmt_fetch($stmtCount);
    mysqli_stmt_close($stmtCount);

    $sqlData = "SELECT * FROM $table LIMIT ? OFFSET ?";
    $stmtData = mysqli_prepare($conn, $sqlData);
    mysqli_stmt_bind_param($stmtData, "ii", $limit, $offset);
    mysqli_stmt_execute($stmtData);
	$result = mysqli_stmt_get_result($stmtData);

	$fields = mysqli_fetch_fields($result);

	$headers = [];
	foreach ($fields as $field) {
		$headers[] = $field->name;
	}

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    mysqli_stmt_close($stmtData);

	$total_pages = ceil($total_rows / $limit);

	return [
		"headers" => $headers,
        "data" => $rows,
        "total_rows" => $total_rows,
        "total_pages" => $total_pages,
        "current_page" => $page
    ];
}



<?php
session_start();
require "../../../../utility/form-builder.php";
require "../../../../config/connect.php";

$table = 'users';
$admin_path = "../../../admin/";
$path_name = $admin_path . "users.php";
$select_id_name = "id";

$mode = "Tambah";
$page_title = "Penyihir";

$schema = get_table_schema($conn, $table);
$fks = get_foreign_keys($conn, $table);

$id = isset($_GET['id']) ? (string)$_GET['id'] : null;
$data = $id ? fetch_by_id($conn, $table, $id, $select_id_name) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validate_from_schema($schema, $_POST);

	if (!$errors) {
 		foreach ($schema as $col) {
            if (is_image_column($col)) {
                $path = handle_image_upload($col['COLUMN_NAME']);
                if ($path) {
                    $_POST[$col['COLUMN_NAME']] = $path;
                    if ($mode === 'edit' && !empty($data[$col['COLUMN_NAME']])) {
                        if (file_exists($data[$col['COLUMN_NAME']])) {
                            unlink($data[$col['COLUMN_NAME']]);
                        }
                    }

                } else {
                    unset($_POST[$col['COLUMN_NAME']]);
                }
            }
        }


        $id ? update_dynamic($conn, $table, $schema, $_POST, $id, $select_id_name) 
			: insert_dynamic($conn, $table, $schema, $_POST);

        header("Location: $path_name");
        exit;
 
	}
}
?>

<html lang="id">
<?php include "../../../../component/head-admin-form.php"; ?>
<body>

	<?php include "../../../../component/navbar-admin-form.php"; ?>

	<main>
		<div class="container">
			<div class="title-section">
				<div class="title-line"></div>
				<div class="title-diamond"></div>
				<h1 class="page-title"><?= $id ? "Edit $page_title" : "Tambah $page_title" ?></h1>
				<div class="title-diamond"></div>
				<div class="title-line right"></div>
			</div>

			<div class="container-form">	
			<form method="POST" enctype="multipart/form-data">
				<?php generate_form($conn, $schema, $fks, $data, $id ? "edit" : "create"); ?>

				<div class="action-buttons">	
					<a class="delete-btn" href="<?= $path_name ?>">Kembali</a>
					<button class="edit-btn" type="submit">
						<?= $id ? 'Update' : 'Tambah' ?>
					</button>
					</a>
				</div>
			</form>
			</div>

		</div>
	</main>

    <footer>
        <h2 class="footer-title">MAGIER GUIDE</h2>
        <p class="footer-copyright">© 2025 Magier Guide. All Rights Reserved.</p>
    </footer>	
</body>
</html>




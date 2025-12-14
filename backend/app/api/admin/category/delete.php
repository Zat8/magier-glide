<?php
session_start();
require "../../../../utility/form-builder.php";
require "../../../../config/connect.php";

$table = 'quest_category';
$admin_path = "../../../admin/";
$path_name = $admin_path . "category.php";
$select_id_name = "category_id";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

if (!isset($_POST['id']) || !ctype_digit($_POST['id'])) {
    http_response_code(400);
    exit('Invalid ID');
}

$id = (int)$_POST['id'];

delete_by_id($conn, $table, $id, $select_id_name);

header("Location: $path_name");
exit;




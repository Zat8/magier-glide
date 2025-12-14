<?php
session_start();
require "../../config/connect.php";
require "../../utility/utils.php";

$email = $_SESSION["user"];
$username = trim($_POST["username"]);
$umur = (int)$_POST["umur"];
$elemen = $_POST["elemen"];
$ras = $_POST["ras"];
$user_title = $_POST["user_title"];

if (!empty($_FILES['image']['name'])) {

    $sql = "SELECT profile_picture FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    $oldImage = $user['profile_picture'];

    $fileExt = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
	$newName = uniqid() . "." . strtolower($fileExt);

    $targetDir  = "../../upload/profile/";
    $targetFile = $targetDir . $newName;

	$tmp = $_FILES["image"]["tmp_name"];

	compress_image($tmp, $targetFile, 70);

    $sql = "UPDATE users SET profile_picture = ? WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $newName, $email);
	mysqli_stmt_execute($stmt);

	if ($oldImage !== 'profil-default.png' && file_exists("../../upload/$oldImage")) {
        unlink("uploads/$oldImage");
    }
}

$sql = "UPDATE users SET username = ?, umur = ?, elemen = ?, ras = ?, user_title = ? WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sissss", $username, $umur, $elemen, $ras, $user_title, $email);
mysqli_stmt_execute($stmt);

header("Location: ../profile.php");
exit;

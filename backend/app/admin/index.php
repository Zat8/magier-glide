<?php
session_start();
if($_SESSION["user_role"] !== 'resepsionis') {
	header("Location: ../index.php");
	exit;
}

header("Location: ./users.php");
exit();
?>

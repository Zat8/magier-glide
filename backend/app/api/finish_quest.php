<?php
header("Content-Type: application/json ");

if($_SERVER["REQUEST_METHOD"] !== "POST") {
	exit;
}

session_start();
require "../../config/connect.php";
require "../../cores/quest-util.php";

$quest_id = $_POST["quest_id"];
$user_id = $_SESSION["user_id"];
$res = finishQuest($conn, $user_id, $quest_id);
echo json_encode($res);

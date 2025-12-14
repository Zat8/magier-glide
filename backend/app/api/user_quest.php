<?php
session_start();
require "../../config/connect.php";
require "../../cores/quest-util.php";

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
	exit;
}

$user_id = $_SESSION["user_id"];
$quest_id = (int)$_POST["quest_id"];

$res = startQuest($conn, $user_id, $quest_id);
return $res["ok"] ? "ok" : "no";

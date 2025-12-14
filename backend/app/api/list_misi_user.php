<?php
header("Content-Type: application/json");

session_start();
require "../../config/connect.php";
require "../../cores/quest-util.php";
require "../../cores/quest-category.php";

$completed = processUserTimers($conn, $_SESSION["user_id"]);

$category = getQuestCategoryAll($conn);
$quest_all = getQuestWithUserId($conn, $_SESSION["user_id"]);
$result = [
	"inProgress" => [],
	"done" => []
];

foreach ($quest_all as $key => $value) {
	$id = (int)$value["category_id"] - 1;
	$cate = $category[$id]["category_name"];

	$value["category_name"] = strtolower($cate);

	if($value["status"] === "in_progress") {
		$result["inProgress"][] = $value;
	} elseif($value["status"] === "completed") {
		$result["done"][] = $value;
	}
}
echo json_encode($result);

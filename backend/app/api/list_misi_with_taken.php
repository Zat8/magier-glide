<?php
header("Content-Type: application/json");

session_start();
require "../../config/connect.php";
require "../../cores/quest-util.php";
require "../../cores/quest-category.php";

$category = getQuestCategoryAll($conn);
$quest_all = getQuestWithTakenByUserId($conn, $_SESSION["user_id"]);
$result = [];

foreach ($category as $key => $val) {
	$filter_value = $val["category_id"];

	$temp = array_values(array_filter($quest_all, function ($quest) use ($filter_value) {
		return (int)$quest["category_id"] === (int)$filter_value; 
	}));

	$key_item = strtolower($val["category_name"]);
	$result[$key_item] = $temp;
}

echo json_encode($result);


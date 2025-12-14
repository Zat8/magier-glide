<?php

function getQuestCategoryAll($conn) {
	$res = mysqli_query($conn, "select * from quest_category");

	$category = [];
	while($c = mysqli_fetch_assoc($res)) {
		$category[] = $c;
	}
	mysqli_free_result($res);

	return $category;
}

function getQuestCategoryById($conn, $id) {
	$stmt = mysqli_prepare($conn, "select * from quest_category where category_id = ?");
	mysqli_stmt_bind_param($stmt, "i", $id);
	mysqli_stmt_execute($stmt);

	$res = mysqli_stmt_get_result($stmt);
	$category = mysqli_fetch_assoc($res);

	return $category;
}

<?php

function addSihir($conn, $user_id, $sihir_id) {
	$q = "insert into users_sihir (user_id, sihir_id) values (?, ?)";
	$stmt = mysqli_prepare($conn, $q);
	mysqli_stmt_bind_param($stmt, "si", $user_id, $sihir_id);
	mysqli_stmt_execute($stmt);
}

function addAchievement($conn, $user_id, $achievement_id) {
	$q = "insert into users_achievement (user_id, achievement_id) values (?, ?)";
	$stmt = mysqli_prepare($conn, $q);
	mysqli_stmt_bind_param($stmt, "si", $user_id, $achievement_id);
	mysqli_stmt_execute($stmt);
}

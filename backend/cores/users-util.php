<?php

function getUser($conn, $email) {
	$query = "select * from users where email = ?";
	$stmt = mysqli_prepare($conn, $query);
	mysqli_stmt_bind_param($stmt, "s", $email);
	mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);
	$user = mysqli_fetch_assoc($result);

	return $user;
}

function getUserAll($conn) {
	$query = "select * from users";
	$stmt = mysqli_prepare($conn, $query);
	mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);
	$user = [];
	while ($u = mysqli_fetch_assoc($result)) {
		$user[] = $u;
	}

	return $user;
}

function getLeaderboard($conn) {
	$query = "select * from users where role = 'penyihir' order by rank_user desc";
	$stmt = mysqli_prepare($conn, $query);
	mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);
	$user = [];
	while ($u = mysqli_fetch_assoc($result)) {
		$user[] = $u;
	}

	return $user;
}

function getUserRole($conn, $email){
	$query = "select role from users where email = ?";
	$stmt = mysqli_prepare($conn, $query);
	mysqli_stmt_bind_param($stmt, "s", $email);
	mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);
	$user = mysqli_fetch_assoc($result);

	return $user;
}

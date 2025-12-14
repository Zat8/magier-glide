<?php

function getRankMasterByLevel($conn, int $rank_level) {
	$stmt = mysqli_prepare($conn, "select * from rank_master where rank_level = ?");
	mysqli_stmt_bind_param($stmt, "i", $rank_level);
	mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);
	$ranked = mysqli_fetch_assoc($result);
	return $ranked;
}

<?php

function getEvents($conn) {
	$stmt = mysqli_prepare($conn, "select * from events");
	mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);
	$peng = [];
	while ($p = mysqli_fetch_assoc($result)) {
		$peng[] = $p;
	}
	return $peng;
}

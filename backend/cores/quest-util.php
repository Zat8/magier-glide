<?php
require "users-util.php";
require "reward-utils.php";

function now_ts(): int {
    return time();
}

function isQuestFinished($finish_time): bool {
	$tz = new DateTimeZone('Asia/Jakarta');
	$now = new DateTime('now', $tz);
	$finishTime = new DateTime($finish_time, $tz);
	return $now >= $finishTime;

	/*
    $finishTs = is_numeric($finish_time)
        ? (int)$finish_time
        : strtotime($finish_time);

    if ($finishTs === false) {
        throw new Exception("Invalid finish_time");
	}

	print_r($finishTs - time());
	print_r("  ");

	return time() >= $finishTs;
	 */
}

function getAllQuest($conn) {
	$stmt = mysqli_prepare($conn, "select * from quest_master");
	mysqli_stmt_execute($stmt);

	$res = mysqli_stmt_get_result($stmt);
	$quest = [];

	while($q = mysqli_fetch_assoc($res)) {
		$quest[] = $q;
	}

	return $quest;
}

function getQuestById($conn, int $quest_id) {
	$stmt = mysqli_prepare($conn, "select * from quest_master where quest_id = ?");
	mysqli_stmt_bind_param($stmt, "i", $quest_id);
	mysqli_stmt_execute($stmt);

	$res = mysqli_stmt_get_result($stmt);
	$quest = [];

	while($q = mysqli_fetch_assoc($res)) {
		$quest[] = $q;
	}

	return $quest;
}

function getQuestByCategoryId($conn, int $category_id) {
	$stmt = mysqli_prepare($conn, "select * from quest_master where category_id = ?");
	mysqli_stmt_bind_param($stmt, "i", $category_id);
	mysqli_stmt_execute($stmt);

	$res = mysqli_stmt_get_result($stmt);
	$quest = [];

	while($q = mysqli_fetch_assoc($res)) {
		$quest[] = $q;
	}

	return $quest;
}

// yang atas crud seadanya
// EL METHODORE biar gak pusing kasih ginian
// bawah bagian ngatur user/quest ts

function takeQuest(string $user_id, int $quest_id, $conn) {
    mysqli_query($conn, "
        INSERT INTO user_quests (user_id, quest_id, status, start_time, finish_time)
        VALUES ($user_id, $quest_id, 'in_progress', NOW())
    ");
}

function requiredExp($conn, $rank) {
    $stmt = mysqli_prepare($conn,
        "SELECT exp_required FROM rank_master WHERE rank_level=?"
    );
    mysqli_stmt_bind_param($stmt, "i", $rank);
    mysqli_stmt_execute($stmt);

    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);

    mysqli_stmt_close($stmt);

    return $row ? (int)$row['exp_required'] : PHP_INT_MAX;
}

function addExp($conn, $user_id, $amount) {
    $user = getUserById($conn, $user_id);
    $experience = $user['experience'] + $amount;

    $rank_user = $user['rank_user'];
    $experience  = $user['experience'] + $amount;

    while ($experience >= requiredExp($conn, $rank_user)) {
        $experience -= requiredExp($conn, $rank_user);
        $rank_user++;
    }

	$stmt = mysqli_prepare($conn, "UPDATE users SET rank_user=?, experience=? WHERE id=?");
	mysqli_stmt_bind_param($stmt, "iis", $rank_user, $experience, $user_id);
	mysqli_stmt_execute($stmt);
}

function startQuest($conn, $user_id, $quest_id) {
	$stmt = mysqli_prepare($conn, "SELECT duration_seconds FROM quest_master WHERE quest_id = ?");
	mysqli_stmt_bind_param($stmt, "i", $quest_id);
	mysqli_stmt_execute($stmt);

	$result  = mysqli_stmt_get_result($stmt);
	$quest = mysqli_fetch_assoc($result);

    if (!$quest) {
        return ["error" => true, "message" => "Quest tidak ditemukan"];
    }

    $start = now_ts();
    $finish = $start + $quest['duration_seconds'];

    $stmt = mysqli_prepare($conn, "
        INSERT INTO users_quests (user_id, quest_id, status, start_time, finish_time)
        VALUES (?, ?, 'in_progress', FROM_UNIXTIME(?), FROM_UNIXTIME(?))
        ON DUPLICATE KEY UPDATE
			status = IF(status = 'finished', 'in_progress', status),
			start_time = IF(status = 'finished', FROM_UNIXTIME(?), start_time),
			finish_time = IF(status = 'finished', FROM_UNIXTIME(?), finish_time)
			");
	mysqli_stmt_bind_param($stmt, "siiiii", $user_id, $quest_id, $start, $finish, $start, $finish);
	mysqli_stmt_execute($stmt);

    return ["ok" => true, "finish_time" => $finish];
}

function processUserTimers($conn, $user_id) {

    $sql = "
        SELECT uq.id, uq.quest_id, uq.finish_time, qm.exp_reward
        FROM users_quests uq
        JOIN quest_master qm ON uq.quest_id = qm.quest_id
        WHERE uq.user_id = ? AND uq.status = 'in_progress'
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $user_id);
	mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);

	$completed = [];

	while ($q = mysqli_fetch_assoc($result)) {
		if (isQuestFinished($q['finish_time'])) {
            $update = mysqli_prepare(
                $conn,
                "UPDATE users_quests SET status='completed' WHERE id=?"
            );
            mysqli_stmt_bind_param($update, "i", $q['id']);
            mysqli_stmt_execute($update);
			mysqli_stmt_close($update);

			$res = giveQuestReward($conn, $user_id);
			
			if($res["type"] === "exp") {
				addExp($conn, $user_id, $res["reward"]["exp"]);	
			}

            addExp($conn, $user_id, $q['exp_reward']);

            $completed[] = $q['quest_id'];
        }
    }

    mysqli_stmt_close($stmt);

    return [
        "completed_quests" => $completed
    ];
}

function finishQuest($conn, $user_id, $quest_id) {

    $sql = "
        SELECT uq.id, uq.quest_id, uq.finish_time, qm.exp_reward
        FROM users_quests uq
        JOIN quest_master qm ON uq.quest_id = qm.quest_id
        WHERE uq.user_id = ? AND uq.quest_id = ? AND uq.status='in_progress'
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $user_id, $quest_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $quest = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if (!$quest) {
        return ["error" => true, "message" => "Quest tidak valid"];
    }

    if (!isQuestFinished($quest['finish_time'])) {
        return ["error" => true, "message" => "Quest belum selesai"];
    }

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE users_quests SET status='completed' WHERE id=?"
    );
    mysqli_stmt_bind_param($stmt, "i", $quest['id']);
    mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);

	$res = giveQuestReward($conn, $user_id);
	if($res["type"] === "exp") {
		addExp($conn, $user_id, $res["reward"]["exp"]);	
	}
	addExp($conn, $user_id, $quest['exp_reward']);

    return ["ok" => true, "quest_id" => $quest_id];
}

function getQuestWithUserId($conn, string $user_id) {
	$stmt = mysqli_prepare($conn, "
		select q.*, uq.status, uq.start_time, uq.finish_time
		from quest_master q
		join users_quests 
		uq on q.quest_id = uq.quest_id
		where uq.user_id = ?
	");
	mysqli_stmt_bind_param($stmt, "s", $user_id);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

	$list_quest = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$list_quest[] = $row;
	}

	return $list_quest;
}

function getQuestWithTakenByUserId($conn, string $user_id) {
	$stmt = mysqli_prepare($conn, "
		select q.*, 
		case 
        	when uq.quest_id is not null then 'taken'
			else 'available'
    	end as status
		from quest_master q
		left join users_quests uq 
			on q.quest_id = uq.quest_id
			and uq.user_id = ?
	");
	mysqli_stmt_bind_param($stmt, "s", $user_id);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

	$list_quest = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$list_quest[] = $row;
	}

	return $list_quest;
}

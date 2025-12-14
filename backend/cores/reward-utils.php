<?php

function rollRewardType() {
    $pool = ['achievement', 'sihir', 'exp'];
    return $pool[array_rand($pool)];
}

function giveRandomAchievement(mysqli $conn, string $userId) {
    $sql = "
        SELECT a.achievement_id, a.achievement_name
        FROM achievement_master a
        WHERE a.achievement_id NOT IN (
            SELECT ua.achievement_id
            FROM users_achievement ua
            WHERE ua.user_id = ?
        )
        ORDER BY RAND()
        LIMIT 1
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $userId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $achievement = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if (!$achievement) {
        return null;
    }

    $insert = mysqli_prepare(
        $conn,
        "INSERT INTO users_achievement (user_id, achievement_id, completed_at)
         VALUES (?, ?, NOW())"
    );
    mysqli_stmt_bind_param(
        $insert,
        "si",
        $userId,
        $achievement['achievement_id']
    );
    mysqli_stmt_execute($insert);
    mysqli_stmt_close($insert);

    return $achievement;
}

function giveRandomSihir(mysqli $conn, string $userId) {
    $sql = "
        SELECT s.sihir_id, s.sihir_name
        FROM sihir_master s
        WHERE s.sihir_id NOT IN (
            SELECT us.sihir_id
            FROM users_sihir us
            WHERE us.user_id = ?
        )
        ORDER BY RAND()
        LIMIT 1
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $userId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $spell = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if (!$spell) {
        return null;
    }

    $insert = mysqli_prepare(
        $conn,
        "INSERT INTO users_sihir (user_id, sihir_id, acquired_at)
         VALUES (?, ?, NOW())"
    );
    mysqli_stmt_bind_param(
        $insert,
        "si",
        $userId,
        $spell['sihir_id']
    );
    mysqli_stmt_execute($insert);
    mysqli_stmt_close($insert);

    return $spell;
}

function giveQuestReward(mysqli $conn, string $userId) {
    $type = rollRewardType();

    switch ($type) {
        case 'achievement':
            $reward = giveRandomAchievement($conn, $userId);
            break;

        case 'sihir':
            $reward = giveRandomSihir($conn, $userId);
            break;

        default:
            $reward = ['exp' => rand(10, 50)];
            break;
    }

    return [
        'type'   => $type,
        'reward' => $reward
    ];
}


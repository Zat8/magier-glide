<?php
session_start();
require "../config/connect.php";
require "../utility/utils.php";
require "../cores/users-util.php";
require "../cores/rank-util.php";
require "../component/head-content.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['user'];
$user = getUser($conn, $email);
$ranked = getRankMasterByLevel($conn, $user["rank_user"]);

// ------------
$stmt = mysqli_prepare($conn, "
	select m.* 
	from sihir_master m
	join users_sihir um on m.sihir_id = um.sihir_id
	where um.user_id = ?
");
mysqli_stmt_bind_param($stmt, "s", $user["id"]);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$list_sihir = [];
while ($row = mysqli_fetch_assoc($result)) {
	$list_sihir[] = $row;
}

$stmt = mysqli_prepare($conn, "
	select a.* 
	from achievement_master a
	join users_achievement ua on a.achievement_id = ua.achievement_id 
	where ua.user_id = ?
");
mysqli_stmt_bind_param($stmt, "s", $user["id"]);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$list_achievement = [];
while ($row = mysqli_fetch_assoc($result)) {
	$list_achievement[] = $row;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
	<?php echo headComponent("Profile - Frieren"); ?>

	<link href="asset/profil.css" rel="stylesheet">
    <script src="asset/profil.js" defer></script>
</head>
<body>

	<?php include "../component/navbar.php"?>

    <main class="main-content">
        <div class="profile-header">
            <div class="profile-info">
                <p class="profile-subtitle"></p>
				<h1 class="profile-name"><?= $user["username"] ?></h1>
                
				<div class="info-table">
                    <span class="info-label">Email</span>
					<span class="info-value"><?= $user['email'] ?></span>

                    <span class="info-label">Title</span>
					<span class="info-value"><?= $user['user_title'] ?></span>
                    
                    <span class="info-label">Ras</span>
					<span class="info-value"><?= ucfirst($user['ras']) ?></span>
                    
                    <span class="info-label">Element</span>
					<span class="info-value"><?= ucfirst($user['elemen']) ?></span>
                    
                    <span class="info-label">Umur</span>
					<span class="info-value"><?= $user["umur"] ?> Tahun</span>

					<span class="info-label">Exp</span>
					<div class="info-value-flex">
						<div class="exp-progress" style="--value: <?= $user["experience"] ?>; --max: <?= $ranked["exp_required"] ?>;">
							<div class="exp-progress-bar"></div>
						</div>
						<p><?= $user["experience"] ?>/<?= $ranked["exp_required"] ?></p>
					</div>
                </div>
            </div>
             
            <div class="profile-avatar">
                <a class="edit-btn" href="profile-edit.php">EDIT</a>
				<img src="../upload/profile/<?= $user["profile_picture"] ?>" alt="Frieren Avatar" class="avatar-image">
				<div class="badge"><?= strtoupper(number_to_word($user['rank_user'])) ?> CLASS</div>
            </div>
        </div>

         
        <div class="content-grid">
            <div class="card">
                <h2 class="card-title">SIHIR</h2>
				<?php if (!empty($list_sihir)) { ?>
					<?php foreach ($list_sihir as $sihir): ?>
						<div class="card-item">
							<div class="item-icon"></div>
							<div class="item-content">
							<h3 class="item-title"><?= $sihir["sihir_name"] ?></h3>
							<p class="item-description"><?= $sihir["descriptions"] ?></p>
							</div>
						</div>
					<?php endforeach; ?> 
				<?php } else { ?>
					<p class="card-simple-msg">Belum ada sihir yang didapatkan</p>
				<?php } ?>
            </div>

             
            <div class="card">
				<h2 class="card-title">ACHIEVEMENT</h2>
				<?php if (!empty($list_achievement)) { ?>
					<?php foreach ($list_achievement as $achievement): ?>
						<div class="card-item">
							<div class="item-icon"></div>
							<div class="item-content">
							<h3 class="item-title"><?= $achievement["achievement_name"] ?></h3>
							<p class="item-description"><?= $achievement["descriptions"] ?></p>
							</div>
						</div>
					<?php endforeach; ?>                 
				<?php } else { ?>
					<p class="card-simple-msg">Anda belum memiliki achievement</p>
				<?php } ?>

            </div>
        </div>
    </main>
	<?php include "../component/footer.php"?>

</body>
</html>


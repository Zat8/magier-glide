<?php
session_start();
require "../config/connect.php";
require "../cores/users-util.php";
require "../cores/rank-util.php";
require "../component/head-content.php";
require "../utility/utils.php";
require "../cores/events-utils.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION["user"];
$user = getUser($conn, $email);
$ranked = getRankMasterByLevel($conn, $user["rank_user"]);
$list_leaderboard = getLeaderboard($conn);
$list_events = getEvents($conn);
?>

<!DOCTYPE html>
<html lang="id">
<head>
	<?php echo headComponent("Frieren RPG - Dashboard"); ?>
	<link rel="stylesheet" href="asset/dashboard.css">

</head>
<body>
     
    <div class="background-blur"></div>
    <div class="background-overlay"></div>

     
	<?php include "../component/navbar.php"; ?> 

     
    <div class="main-container">
         
        <aside class="sidebar-left">
             
            <div class="player-card">
                <div class="player-info">
                    <div class="level-badge">
						<span class="level-number" id="levelNumber"><?= $user["rank_user"] ?></span>
                    </div>
                    <div class="player-details">
						<h2 class="player-name"><?= $user["username"] ?></h2>
                        <div class="progress-bar-container" style="--value: <?= $user["experience"] ?>; --max: <?= $ranked["exp_required"] ?>;">
                            <div class="progress-bar" id="progressBar"></div>
                        </div>
                    </div>
                </div>
            </div>

			<div class="menu-btn-group">
				<button class="menu-btn" id="questBtn">
					<div class="diamond-icon"></div>
					<span class="menu-text">Quest</span>
				</button>

				<button class="menu-btn" id="leaderboardBtn">
					<div class="diamond-icon"></div>
					<span class="menu-text">Leaderboard</span>
				</button>
			</div>

             
			<div class="event-box">
				<div class="event-images">
				<?php if(!empty($list_events)) { ?>
					<?php foreach ($list_events as $value) { ?>
					<img src="../upload/banner/<?= $value["banner"] ?>" alt="<?= $value["title"] ?>" class="event-image">
					<?php } ?>
				<?php } else { ?>	
					<img src="asset/foto/bg_invest.webp" alt="Event Banner" class="event-image">
				<?php } ?>
				</div>
            </div>
        </aside>

         
        <main class="main-content">
            <div class="character-display">
                <img src="asset/foto/bodyfrieren.webp">
            </div>
        </main>

         
        <aside class="sidebar-right">
            <div class="guild-announcements">
                <div class="guild-header">
                    <div class="guild-icon"></div>
                    <h2 class="guild-title">Pengumuman Guild</h2>
                </div>
                <div class="announcements-container" id="announcementsContainer">
                     
                </div>
            </div>
        </aside>
	</div>

 	<div class="modal" id="questModal">
        <div class="modal-content modal-quest">
            <div class="modal-header">
                <div class="modal-title-group">
                    <h2 class="modal-title">Quest</h2>
                </div>
				<button class="btn-close" id="closeQuestModal">
					<img src="asset/foto/4dm.png">
				</button>
            </div>
            <div class="modal-body">
                 
                <div class="quest-section">
                    <div class="section-header">
                        <div class="section-title-group">
                            <div class="section-diamond"></div>
                            <span class="section-title">In Progress</span>
                        </div>
                        <span class="section-count" id="inProgressCount">0</span>
                    </div>
                    <div class="quest-grid" id="questInProgress">
						 
                    </div>
                </div>

                 
                <div class="quest-section">
                    <div class="section-header">
                        <div class="section-title-group">
                            <div class="section-diamond"></div>
                            <span class="section-title">Done</span>
                        </div>
                        <span class="section-count" id="doneCount">0</span>
                    </div>
                    <div class="quest-grid" id="questDone">
                         
                    </div>
                </div>
            </div>
        </div>
	</div>

    <div class="modal" id="leaderboardModal">
        <div class="modal-content modal-leaderboard">
            <div class="modal-header">
                <div class="modal-title-group">
                    <h2 class="modal-title">Leaderboard</h2>
                </div>
				<button class="btn-close" id="closeLeaderboardModal">
					<img src="asset/foto/4dm.png">
				</button>
            </div>
            <div class="modal-body">
                <div class="leaderboard-list" id="leaderboardList">
					<?php
						$idx = 1;
						foreach ($list_leaderboard as $user) { ?>
				 	<div class="leaderboard-item">
						<div class="leaderboard-rank">
							<span class="rank-number"><?= $idx ?></span>
						</div>
						<div class="leaderboard-info">
							<div class="player-avatar"> 
								<img src="../upload/profile/<?= $user["profile_picture"] ?>">
							</div>
							<div class="player-name-group">
								<h3><?= $user["username"] ?></h3>
								<p class="player-email"><?= $user["email"] ?></p>
							</div>
						</div>
						<div class="leaderboard-class">
							<span class="class-badge"><?= strtoupper(number_to_word($user["rank_user"])) ?> CLASS</span>
						</div>
					</div>
					<?php 
						$idx++;	
					} ?>
                </div>
            </div>
        </div>
	</div>

	<div id="reward-overlay" class="reward-overlay hidden">
	  <div class="reward-popup">
		<h2 class="reward-title">Reward Unlocked</h2>

		<div class="reward-content">
		  <div class="reward-icon">Reward</div>
		  <div class="reward-text"></div>
		</div>

		<button class="reward-btn" onclick="closeRewardPopup()">OK</button>
	  </div>
	</div>


	 
    <script type="module" src="asset/dashboard.js"></script>

</body>
</html>


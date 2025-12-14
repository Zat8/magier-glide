<?php
session_start();
require "../component/head-content.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

?>

<html lang="id">
<head>
	<?php echo headComponent("Frieren Quest - Mission Board"); ?>

    <link rel="stylesheet" href="asset/misi.css">

</head>
<body>
    <!-- Background Container -->
    <!-- Ini adalah container untuk background image -->
    <div class="background">
        <img src="asset/foto/bg_eksplor.webp">
        <img src="asset/svg/peksplor.svg">
    </div>

    <!-- Navigation Bar -->
    <!-- NAVBAR -->

	<?php include "../component/navbar.php" ?>

    <!-- Main Content Container -->
    <div class="main-container">
        
        <aside class="sidebar-left">
            <!-- Button akan di-generate oleh JavaScript -->
            <button class="quest-type-btn active" data-type="exploration">
                Exploration Quest
            </button>
            <button class="quest-type-btn" data-type="escort">
                Escort Quest
            </button>
            <button class="quest-type-btn" data-type="investigation">
                Investigation Quest
            </button>
        </aside>

        <!-- Center - Quest List -->
        <!-- Area tengah untuk menampilkan daftar quest -->
		<main class="quest-list-container">
			<p class="quest-list-title">Current Quest</p>
            <div class="quest-list" id="questList">
                <!-- Quest cards akan di-generate oleh JavaScript -->
            </div>
        </main>

        <!-- Right Sidebar - Character Cards -->
        <!-- Panel kanan untuk menampilkan character cards -->
        <aside class="sidebar-right">
            <img class="character-card card-3" src="asset/foto/frieren-3.png">
            <img class="character-card card-2" src="asset/foto/frieren-2.png">
            <img class="character-card card-1" src="asset/foto/frieren-1.png">
        </aside>

    </div>

    <!-- Modal Detail Quest -->
    <!-- Popup yang muncul saat quest card diklik -->
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-content" id="modalContent">
            <!-- Modal Header dengan Icon -->
            <div class="modal-header">
                <div class="modal-icon">
                    <img src="asset/foto/4dm.png" class="fourdm">
                </div>
            </div>

            <h3 class="modal-subtitle" id="modalSubtitle">Exploration Quest</h3>

            <div class="modal-body">
                <h2 class="modal-title" id="modalTitle">Quest Title</h2>
                <div class="modal-objective">
                    <strong>Objective:</strong> <span id="modalObjective">Quest objective</span>
                </div>
                <p class="modal-description" id="modalDescription">Quest details description</p>
            </div>

            <div class="modal-footer">
                <button class="btn-take-quest" id="btnTakeQuest">Ambil Quest</button>
            </div>
        </div>
	</div>

	<div id="notification" class="notif">
		<p class="msg"></p>
	</div>

    <script type="module" src="asset/misi.js"></script>
</body>
</html>


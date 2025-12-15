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
     
     
    <div class="background">
        <img src="asset/foto/bg_eksplor.webp">
        <img src="asset/svg/peksplor.svg">
    </div>

     
     

	<?php include "../component/navbar.php" ?>

     
    <div class="main-container">
        
        <aside class="sidebar-left">
             
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

         
         
		<main class="quest-list-container">
			<p class="quest-list-title">Current Quest</p>
            <div class="quest-list" id="questList">
                 
            </div>
        </main>

         
         
        <aside class="sidebar-right">
            <img class="character-card card-3" src="asset/foto/frieren-3.png">
            <img class="character-card card-2" src="asset/foto/frieren-2.png">
            <img class="character-card card-1" src="asset/foto/frieren-1.png">
        </aside>

    </div>

     
     
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-content" id="modalContent">
             
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


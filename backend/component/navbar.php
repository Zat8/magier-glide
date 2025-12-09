<nav class="navbar" id="navbar">
	<div class="nav-container">
		<div class="logo">
			<img src="asset/foto/logo.png" alt="Frieren Logo">
		</div>
		
		<div class="hamburger" id="hamburger">
			<span></span>
			<span></span>
			<span></span>
		</div>
		
		<ul class="nav-menu" id="navMenu">
			<li><a href="index.php" class="nav-link">HOME</a></li>
			<li><a href="dashboard.php" class="nav-link">DASHBOARD</a></li>
			<li><a href="mission.php" class="nav-link">MISSIONS</a></li>
			<li><a href="profile.php" class="nav-link">PROFILE</a></li>
			<?php 
				if(!isset($_SESSION["user"])) {
					echo '<li><a href="login.php" class="logout-btn">LOGIN</a></li>';
				} else {
					echo '<li><a href="api/logout.php" class="logout-btn">LOGOUT</a></li>';
				}
			?>
		</ul>
	</div>
</nav>

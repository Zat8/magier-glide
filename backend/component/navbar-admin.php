<header>
	<div class="container">
		<div class="header-content">
			<div style="display: flex; align-items: center; gap: 40px;">
				<div class="logo">
					<img src="../asset/foto/logo.png">
				</div>
				<nav>
					<a href="users.php" class="active">PENYIHIR</a>
					<a href="quest.php">MISSIONS</a>
					<a href="events.php">EVENTS</a>
					<a href="pengumuman.php">PENGUMUMAN</a>
					<a href="achievement.php">ACHIEVEMENT</a>
				</nav>
			</div>
			<?php 
				if(!isset($_SESSION["user"])) {
					echo '<li><a href="login.php" class="logout-btn">LOGIN</a></li>';
				} else {
					echo '<li><a href="../api/logout.php" class="logout-btn">LOGOUT</a></li>';
					
				}
			?>
		</div>
	</div>
</header>

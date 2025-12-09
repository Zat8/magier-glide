<?php
session_start();
require "../config/connect.php";
require "../core/utils.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['user'];
$query = "select * from users where email = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

$stmt = mysqli_prepare($conn, "select * from rank_master where rank_level = ?");
mysqli_stmt_bind_param($stmt, "i", $user["rank_user"]);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$ranked = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Frieren</title>
    <link href="asset/profil.css" rel="stylesheet">
    <script src="asset/profil.js" defer></script>
</head>
<body>

    <!-- NAVBAR -->
  
	<?php include "../component/navbar.php"?>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="main-content edit">
        <!-- Profile Header dengan nama dan avatar -->
		<form method="POST" action="api/edit_profile.php" enctype="multipart/form-data">
        <div class="profile-header">
            <!-- Informasi Profile Kiri -->
            <div class="profile-info">
                <p class="profile-subtitle"></p>
				<input class="profile-name-edit" type="text" name="username" value="<?= $user["username"] ?>" required>
                
				<!-- Tabel informasi character -->
				<div class="info-table">
                    <span class="info-label">Email</span>
					<span class="info-value"><?= $user['email'] ?></span>

                    <span class="info-label">Title</span>
					<span class="info-value"><input class="info-edit" name="user_title" value="<?= $user['user_title'] ?>" required></span>
                    
                    <span class="info-label">Ras</span>
					<span class="info-value">
						<select class="info-edit" id="ras" required name="ras">
							<option value="">Ras</option>
							<option <?= $user['ras'] === 'elf' ? 'selected' : '' ?> value="elf">Elf</option>
							<option <?= $user['ras'] === 'manusia' ? 'selected' : '' ?> value="manusia">Manusia</option>
							<option <?= $user['ras'] === 'dwarf' ? 'selected' : '' ?> value="dwarf">dwarf</option>
							<option <?= $user['ras'] === 'half-demon' ? 'selected' : '' ?> value="half-demon">Half Demon</option>
							<option <?= $user['ras'] === 'half-monster' ? 'selected' : '' ?> value="half-monster">Half Monster</option>
							<option <?= $user['ras'] === 'spirits' ? 'selected' : '' ?> value="spirits">Spirits</option>
							<option <?= $user['ras'] === 'herioc-spirits' ? 'selected' : '' ?> value="herioc-spirits">Heroic Spirits</option>
						</select>
					</span>
                    
                    <span class="info-label">Element</span>
					<span class="info-value">
						<select class="info-edit" id="element" required name="elemen">
							<option value="">Element Sihir</option>
							<option value="api" <?= $user['elemen'] === "api" ? 'selected' : '' ?>>Api</option>
							<option value="air" <?= $user['elemen'] === "air" ? 'selected' : '' ?>>Air</option>
							<option value="angin" <?= $user['elemen'] === "angin" ? 'selected' : '' ?>>Angin</option>
							<option value="tanah" <?= $user['elemen'] === "tanah" ? 'selected' : '' ?>>Tanah</option>
							<option value="alam" <?= $user['elemen'] === "alam" ? 'selected' : '' ?>>Alam</option>
						</select>
					</span>
                    
                    <span class="info-label">Umur</span>
					<span class="info-value"><input class="info-edit" type="number" name="umur" value="<?= $user["umur"] ?>" required> Tahun</span>

					<span class="info-label">Exp</span>
					<div class="info-value-flex">
						<div class="exp-progress" style="--value: <?= $user["experience"] ?>; --max: <?= $ranked["exp_required"] ?>;">
							<div class="exp-progress-bar"></div>
						</div>
						<p><?= $user["experience"] ?>/<?= $ranked["exp_required"] ?></p>
					</div>
                </div>
            </div>
            
            <!-- Avatar dan Badge Kanan -->
			<div class="profile-avatar">
				<label for="upload-image" class="edit-btn">Upload Image</label>
				<input id="upload-image" style="display: none;" type="file" name="image" accept="image/*">

				<img src="../upload/profile/<?= $user["profile_picture"] ?>" alt="Frieren Avatar" class="avatar-image">
				<div class="badge"><?= strtoupper(number_to_word($user['rank_user'])) ?> CLASS</div>
            </div>
		</div>
		
		<div>
			<a class="action-btn" href="profile.php">Kembali / Batal</a>
			<button class="action-btn" type="submit">Edit</button>
		</div>
		</form>
		
		<script>
			document.getElementById("upload-image").addEventListener("change", function(event) {
				const file = event.target.files[0];
				if (!file) return;

				const reader = new FileReader();
				reader.onload = function(e) {
					document.querySelector(".avatar-image").src = e.target.result;
				};
				reader.readAsDataURL(file);
			})
		</script>

        <!-- Grid untuk Sihir dan Achievement - 2 Kartu Besar -->
    </main>

    <!-- ===== FOOTER  ===== -->

	<?php include "../component/footer.php"?>

</body>
</html>



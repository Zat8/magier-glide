<?php
session_start();
require "../../config/connect.php";
require "../../utility/utils.php";

if($_SESSION["user_role"] !== 'resepsionis') {
	header("Location: ../index.php");
	exit;
}

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$user_list = paginate($conn, "users", $page, 10);
$keys = $user_list["headers"];
$api_path_name = "users";
$page_title = "Penyihir";
$select_id_name = "id";

?>

<html lang="id">
<head>
    <meta charset="UTF-8">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Magier Guide - Penyihir Admin Panel</title>
	<link href="../asset/admin.css" rel="stylesheet">
</head>
<body>
    
	<?php include "../../component/navbar-admin.php"; ?>

    <main>
        <div class="container">
            <div class="title-section">
                <div class="title-line"></div>
                <div class="title-diamond"></div>
				<h1 class="page-title"><?= $page_title ?></h1>
                <div class="title-diamond"></div>
                <div class="title-line right"></div>
            </div>

            <div class="tabs">
                <a href="users.php" class="tab-btn active" data-tab="penyihir">Penyihir Table</a>
                <a href="rank.php" class="tab-btn" data-tab="rank">Rank Table</a>
                <a href="sihir.php" class="tab-btn" data-tab="sihir">Sihir Table</a>
                <a href="user-sihir.php" class="tab-btn" data-tab="user-sihir">User - Sihir Table</a>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
							<?php foreach($keys as $key) { ?>
							<?php if($key === "password") continue; ?>
								<th><?= $key ?></th>
							<?php } ?>
			
                            <th style="text-align: right;">
								<a class="tambah-btn" href="../api/admin/<?= $api_path_name ?>/add.php" class="edit-btn">Tambah</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
					<?php foreach($user_list["data"] as $user) { ?>
						<tr>
							<?php foreach($user as $key => $member) { ?>
								<?php if($key === "password") continue; ?>
								<td><?= $member ?></td>
							<?php } ?>
							<td>
								<div class="action-buttons">
								<a href="../api/admin/<?= $api_path_name ?>/edit.php?id=<?= $user[$select_id_name] ?>" class="edit-btn">Edit</a>
									<form method="POST" action="../api/admin/<?= $api_path_name ?>/delete.php" style="display:inline">
										<input type="hidden" name="id" value="<?= $user[$select_id_name] ?>">
										<button class="delete-btn" type="submit"
												onclick="return confirm('Delete this record?')">
											Delete
										</button>
									</form>
								</div>
							</td>
						</tr>
					<?php } ?>

                    </tbody>
                </table>
            </div>

            <div class="pagination">
				<?php for ($i = 1; $i <= $user_list["total_pages"]; $i++): ?>
					<a class="page-btn <?php echo $i == $user_list['current_page'] ? 'active' : '' ?>" href="?page=<?php echo $i; ?>">
						<?php echo $i; ?>
					</a>
				<?php endfor; ?>
            </div>
        </div>
    </main>

    <footer>
        <h2 class="footer-title">MAGIER GUIDE</h2>
        <p class="footer-copyright">© 2025 Magier Guide. All Rights Reserved.</p>
    </footer>
</body>
</html>


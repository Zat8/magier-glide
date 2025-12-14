<?php
session_start();
require "../../config/connect.php";
require "../../utility/utils.php";

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$events = paginate($conn, "events", $page, 10);
$keys = $events["headers"];
$api_path_name = "events";
$page_title = "Events";
$select_id_name = "event_id";

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
                <a href="events.php" class="tab-btn active" data-tab="rank">Events</a>
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
					<?php foreach($events["data"] as $user) { ?>
						<tr>
							<?php foreach($user as $key => $member) { ?>
								<?php if($key === "password") continue; ?>
								<td><?= $member ?></td>
							<?php } ?>
							<td>
								<div class="action-buttons">
									<a href="../api/admin/<?= $api_path_name ?>/edit.php?id=<?= $user[$select_id_name] ?>" class="edit-btn">Edit</a>
									<a href="../api/admin/<?= $api_path_name ?>/delete.php?id=<?= $user[$select_id_name] ?>" class="delete-btn">Delete</a>
								</div>
							</td>
						</tr>
					<?php } ?>

                    </tbody>
                </table>
            </div>

            <div class="pagination">
				<?php for ($i = 1; $i <= $events["total_pages"]; $i++): ?>
					<a class="page-btn <?php echo $i == $events['current_page'] ? 'active' : '' ?>" href="?page=<?php echo $i; ?>">
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







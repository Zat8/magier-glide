<?php
header("Content-Type: application/json");

session_start();
require "../../config/connect.php";
require "../../cores/pengumuman-util.php";
$peng = getPengumumanAll($conn);

echo json_encode($peng);

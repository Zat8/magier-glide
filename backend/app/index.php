<?php
session_start();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Magier Gilde - Era After Hero</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="./asset/style.css" rel="stylesheet">
    <script src="./asset/script.js" defer></script>
</head>
<body>

    <!-- NAVBAR -->
	<?php include "../component/navbar.php"?>

    <!-- BANNER -->
    <section class="banner">
        <div class="banner-bg"></div>
        <div class="banner-overlay"></div>
        
        <div class="banner-content">
            <h1 class="main-title">MAGIER GLIDE</h1>
            <div class="subtitle">
                <img src="asset/svg/diamond.svg">
                <span>Era After Hero</span>
                <img src="asset/svg/diamond.svg">
            </div>
        </div>

        <!-- Curved Divider -->
        <div class="curve-divider">
            <img src="asset/svg/transisi.svg">
        </div>
    </section>

    <!-- CONTENT SECTION  -->
    <section class="content-section">
        <div class="about">
            <img src="asset/svg/diamond.svg">
            <img src="asset/svg/about.svg">
            <h2 class="teks-about">Magier Gilde Adalah Pusat Pelatihan Bagi Para Penyihir Muda Untuk Mengasah Sihir,
                Menjalankan Misi Berbahaya, Dan Menapaki Jalan Menuju Peringkat Tertinggi Di Era Pasca Hero Party.</h2>
				<a href="<?= isset($_SESSION["user"]) ? 'dashboard.php' : 'login.php' ?>" class="login-btn">JOIN NOW</a>

            <div class="slider">
                <div class="slider-container">
                    <div class="slides" id="slides">
                        <div class="slide">
                            <img draggable="false" ondragstart="return false;" class="frame" src="asset/svg/frame_slider.svg" alt="frame">
                            <img draggable="false" ondragstart="return false;" class="content" src="asset/foto/slide-1.png" alt="gambar1">
                        </div>

                        <div class="slide active">
                            <img draggable="false" ondragstart="return false;" class="frame" src="asset/svg/frame_slider.svg" alt="frame">
                            <img draggable="false" ondragstart="return false;" class="content" src="asset/foto/slide-2.png" alt="gambar2">
                        </div>

                        <div class="slide">
                            <img draggable="false" ondragstart="return false;" class="frame" src="asset/svg/frame_slider.svg" alt="frame">
                            <img draggable="false" ondragstart="return false;" class="content" src="asset/foto/slide-3.png" alt="gambar3">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="feature">
            <img src="asset/svg/tfeature.svg">
            <div class="fitur">
                <img src="asset/svg/rank.svg">
                <img src="asset/svg/misi.svg">
                <img src="asset/svg/party.svg">
            </div>
        </div>
    </section>
	
	<?php include "../component/footer.php"?>

</body>
</html>


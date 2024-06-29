<?php
// Mulai sesi
session_start();

// Konfigurasi database
include("../db.php");

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aftercom Laptop Service</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<?php include("header.php"); ?>
    <main>
        <section class="hero">
            <div class="hero-content">
                <h1>Selamat datang di Layanan Servis Aftercom</h1>
                <p>Teknisi berpengalaman kami siap membantu perbaikan laptop Anda tanpa perlu repot-repot keluar rumah. Percayakan kepada kami untuk layanan servis terpercaya dan solusi perbaikan yang handal.</p>
                <button onclick="location.href='service.php'">Mulai</button>
            </div>
        </section>
        <section class="why-aftercom">
            <h2>Kenapa Harus AFTERCOM?</h2>
            <div class="features">
                <div class="feature">
                    <img src="images/icon-experience.png" alt="Teknisi Berpengalaman">
                    <p>Teknisi berpengalaman lebih dari 8 tahun</p>
                </div>
                <div class="feature">
                    <img src="images/icon-original.png" alt="Produk Asli">
                    <p>Produk yang digunakan 100% asli dan bergaransi</p>
                </div>
                <div class="feature">
                    <img src="images/icon-secure.png" alt="Keamanan Data">
                    <p>Menjamin keamanan dan kerahasiaan data customer</p>
                </div>
            </div>
        </section>
        <section class="service-steps">
            <h2>Tata Cara Layanan AFTERCOM</h2>
            <div class="steps">
                <div class="step">
                    <img src="images/step1.png" alt="Langkah 1">
                    <p>Konsultasikan permasalahan atau kerusakan yang dialami</p>
                </div>
                <div class="step">
                    <img src="images/step2.png" alt="Langkah 2">
                    <p>Driver akan menjemput laptop anda</p>
                </div>
                <div class="step">
                    <img src="images/step3.png" alt="Langkah 3">
                    <p>Lakukan pembayaran sesuai dengan estimasi perbaikan</p>
                </div>
                <div class="step">
                    <img src="images/step4.png" alt="Langkah 4">
                    <p>Cek status perbaikan laptop secara realtime</p>
                </div>
                <div class="step">
                    <img src="images/step5.png" alt="Langkah 5">
                    <p>Driver mengantarkan unit Anda sesuai alamat tujuan</p>
                </div>
            </div>
        </section>
        <section class="products-services">
            <h2>Produk dan Jasa AFTERCOM</h2>
            <div class="services">
                <div class="service">
                    <img src="images/upgrade.png" alt="Upgrade RAM & SSD">
                    <p>Upgrade RAM & SSD</p>
                </div>
                <div class="service">
                    <img src="images/sparepart.png" alt="Ganti Sparepart">
                    <p>Ganti Sparepart</p>
                </div>
                <div class="service">
                    <img src="images/install.png" alt="Install Ulang">
                    <p>Install Ulang Windows/Linux/iOS</p>
                </div>
                <div class="service">
                    <img src="images/maintenance.png" alt="Maintenance Laptop">
                    <p>Maintenance Laptop</p>
                </div>
                <div class="service">
                    <img src="images/antivirus.png" alt="Antivirus">
                    <p>Antivirus</p>
                </div>
                <div class="service">
                    <img src="images/data-recovery.png" alt="Servis Hardisk">
                    <p>Servis Hardisk dan Recovery Data</p>
                </div>
                <div class="service">
                    <img src="images/custom-app.png" alt="Aplikasi Custom">
                    <p>Aplikasi Custom</p>
                </div>
            </div>
        </section>
        <section class="brands">
            <h2>AFTERCOM Menerima Servis</h2>
            <div class="brand-logos">
                <img src="images/asus.png" alt="Asus">
                <img src="images/acer.png" alt="Acer">
                <img src="images/lenovo.png" alt="Lenovo">
                <img src="images/hp.png" alt="HP">
                <img src="images/samsung.png" alt="Samsung">
                <img src="images/apple.png" alt="Apple">
                <img src="images/dell.png" alt="Dell">
                <img src="images/xiaomi.png" alt="Xiaomi">
                <img src="images/microsoft.png" alt="Microsoft">
                <img src="images/msi.png" alt="MSI">
            </div>
        </section>
    </main>
<?php include("../footer.php"); ?>
</body>
</html>

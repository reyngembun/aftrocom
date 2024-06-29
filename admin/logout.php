<?php
// Mulai sesi
session_start();

// Menghapus semua variabel sesi
$_SESSION = [];

// Menghapus sesi
session_destroy();

// Mengarahkan kembali ke halaman login
header("Location: ../login.php");
exit();
?>

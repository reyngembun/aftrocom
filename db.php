<?php
// Konfigurasi database
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "aftrocom";

// Membuat koneksi
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
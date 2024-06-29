<?php
// Konfigurasi database
include("db.php");

// Mendapatkan data dari formulir
$name = $_POST['name']; // Tambahkan ini
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$phone_number = $_POST['phone_number'];
$alamat = $_POST['alamat'];
$role = $_POST['role'];

// Memeriksa konfirmasi password
if ($password !== $confirm_password) {
    die("Password dan konfirmasi password tidak cocok.");
}

// Mengenkripsi password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Menyimpan data ke database menggunakan prepared statements
$stmt = $conn->prepare("INSERT INTO users (name, username, password, email, phone_number, Alamat, role) VALUES (?, ?, ?, ?, ?, ?, ?)"); // Tambahkan kolom name
$stmt->bind_param("sssssss", $name, $username, $hashed_password, $email, $phone_number, $alamat, $role);

if ($stmt->execute() === TRUE) {
    // Redirect ke halaman login dengan parameter register=success
    header("Location: login.php?register=success");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

// Menutup koneksi
$stmt->close();
$conn->close();

?>
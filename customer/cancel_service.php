<?php
session_start();
include '../db.php';

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in";
    exit;
}

$user_id = $_SESSION['user_id'];
$laptop_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($laptop_id === 0) {
    echo "Invalid laptop ID";
    exit;
}

// Periksa apakah laptop milik pengguna yang sedang login
$sql_laptop_check = "SELECT id FROM laptops WHERE id = ? AND owner_id = ?";
$stmt_laptop_check = $conn->prepare($sql_laptop_check);
$stmt_laptop_check->bind_param("ii", $laptop_id, $user_id);
$stmt_laptop_check->execute();
$result_laptop_check = $stmt_laptop_check->get_result();

if ($result_laptop_check->num_rows === 0) {
    echo "Laptop not found or does not belong to this user.";
    exit;
}
$stmt_laptop_check->close();

// Masukkan pembatalan service ke tabel service_requests
$description = "laptop dibatalkan";
$status = "on going";
$issue_date = date('Y-m-d');

$sql_cancel = "INSERT INTO service_requests (laptop_id, description, issue_date, status) VALUES (?, ?, ?, ?)";
$stmt_cancel = $conn->prepare($sql_cancel);
$stmt_cancel->bind_param("isss", $laptop_id, $description, $issue_date, $status);

if ($stmt_cancel->execute()) {
    echo "Service request has been cancelled. The courier will soon deliver your laptop.";
} else {
    echo "Failed to cancel the service request.";
}

$stmt_cancel->close();
$conn->close();

// Redirect back to the laptop detail page
header("Location: laptop_detail.php?id=" . $laptop_id);
exit;
?>

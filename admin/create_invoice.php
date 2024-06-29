<?php
session_start();
include '../db.php';

// Check if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

// Get form data
$laptop_id = isset($_POST['laptop_id']) ? intval($_POST['laptop_id']) : 0;
$type = isset($_POST['type']) ? trim($_POST['type']) : '';
$description = isset($_POST['description']) ? trim($_POST['description']) : '';
$price = isset($_POST['price']) ? floatval($_POST['price']) : 0.0;

// Validate input
if ($laptop_id == 0 || empty($type) || empty($description) || $price <= 0) {
    echo "Invalid input.";
    exit;
}

// Insert the invoice into the database
$sql = "INSERT INTO invoice (laptop_id, type, description, price) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo "Error preparing the statement: " . $conn->error;
    exit;
}

$stmt->bind_param('issd', $laptop_id, $type, $description, $price);

if ($stmt->execute()) {
    echo "Invoice created successfully.";
    header("Location: laptop_details.php?id=" . $laptop_id);
    exit;
} else {
    echo "Error creating invoice: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

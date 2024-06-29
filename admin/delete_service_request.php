<?php
session_start();
include '../db.php';

// Check if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

// Get service request ID from URL
$request_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($request_id == 0) {
    echo "Invalid request ID.";
    exit;
}

// Fetch the laptop ID associated with this request
$sql = "SELECT laptop_id FROM service_requests WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $request_id);
$stmt->execute();
$stmt->bind_result($laptop_id);
$stmt->fetch();
$stmt->close();

if ($laptop_id == 0) {
    echo "Laptop ID not found.";
    exit;
}

// Delete the service request
$sql = "DELETE FROM service_requests WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $request_id);

if ($stmt->execute()) {
    header("Location: laptop_details.php?id=" . $laptop_id);
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

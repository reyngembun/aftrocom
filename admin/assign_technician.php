<?php
session_start();
include '../db.php';

// Check if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

$service_request_id = isset($_POST['service_request_id']) ? intval($_POST['service_request_id']) : 0;
$technician_id = isset($_POST['technician_id']) ? intval($_POST['technician_id']) : 0;

if ($service_request_id == 0 || ($technician_id != 17 && $technician_id != 18)) {
    echo "Invalid input.";
    exit;
}

// Assign technician to the service request
$sql = "UPDATE service_requests SET technician_id = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $technician_id, $service_request_id);

if ($stmt->execute()) {
    echo "Technician assigned successfully.";
} else {
    echo "Error assigning technician: " . $stmt->error;
}

$stmt->close();
$conn->close();

$laptop_id = $_POST['laptop_id'];
header("Location: laptop_details.php?id=" . $laptop_id);
exit;
?>

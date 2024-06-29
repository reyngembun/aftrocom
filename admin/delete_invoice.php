<?php
session_start();
include '../db.php';

// Check if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

// Get invoice ID from URL
$invoice_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($invoice_id == 0) {
    echo "Invalid invoice ID.";
    exit;
}

// Fetch invoice details
$sql = "SELECT * FROM invoice WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $invoice_id);
$stmt->execute();
$result = $stmt->get_result();
$invoice = $result->fetch_assoc();

if (!$invoice) {
    echo "Invoice not found.";
    exit;
}

$stmt->close();

// Delete the invoice
$sql = "DELETE FROM invoice WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $invoice_id);

if ($stmt->execute()) {
    echo "Invoice deleted successfully.";
} else {
    echo "Error deleting invoice: " . $conn->error;
}

$stmt->close();
$conn->close();

// Redirect back to the laptop details page
header("Location: laptop_details.php?id=" . $invoice['laptop_id']);
exit;
?>

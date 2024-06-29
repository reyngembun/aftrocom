<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $laptop_id = $_POST['laptop_id'];
    $status = $_POST['status'];

    // Update the laptop status
    $sql = "UPDATE laptops SET status = ?, status_timestamp = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $status, $laptop_id);

    if ($stmt->execute()) {
        header('Location: admin_laptops.php');
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>

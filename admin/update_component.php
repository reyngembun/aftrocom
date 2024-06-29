<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $component_id = $_POST['component_id'];
    $status = $_POST['status'];
    $remark = $_POST['remark'];

    // Update the component
    $sql = "UPDATE components SET status = ?, remark = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $status, $remark, $component_id);

    if ($stmt->execute()) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>

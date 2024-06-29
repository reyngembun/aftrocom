<?php
include '../db.php';

$laptop_id = $_GET['laptop_id'];

$sql = "SELECT component_name, status, remark FROM components WHERE laptop_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $laptop_id);
$stmt->execute();
$result = $stmt->get_result();

$components = [];
while ($row = $result->fetch_assoc()) {
    $components[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($components);
?>

<?php
include '../db.php';

$laptop_id = 1; // Example laptop ID, replace with actual ID

$components = [
    ['component_name' => 'CPU', 'status' => 'Aman', 'remark' => ''],
    ['component_name' => 'Battery', 'status' => 'Wajib diganti', 'remark' => 'Estimasi Biaya Rp. 500.000'],
    ['component_name' => 'RAM', 'status' => 'Aman', 'remark' => ''],
    ['component_name' => 'SSD', 'status' => 'Aman', 'remark' => ''],
    ['component_name' => 'Screen', 'status' => 'Aman', 'remark' => '']
];

$sql = "INSERT INTO components (laptop_id, component_name, status, remark) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

foreach ($components as $component) {
    $stmt->bind_param("isss", $laptop_id, $component['component_name'], $component['status'], $component['remark']);
    $stmt->execute();
}

$stmt->close();
$conn->close();

echo "Components table populated successfully.";
?>

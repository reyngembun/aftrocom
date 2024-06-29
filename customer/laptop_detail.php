<?php
session_start();
include '../db.php';

// Get the laptop ID from the URL parameter
$laptop_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch laptop details
$sql_laptop = "SELECT brand, model, serial_number, description FROM laptops WHERE id = ?";
$stmt_laptop = $conn->prepare($sql_laptop);
$stmt_laptop->bind_param("i", $laptop_id);
$stmt_laptop->execute();
$result_laptop = $stmt_laptop->get_result();

if ($result_laptop->num_rows === 0) {
    echo "Laptop not found.";
    exit;
}

$laptop = $result_laptop->fetch_assoc();
$stmt_laptop->close();

// Fetch service requests for the laptop
$sql_service_requests = "SELECT description, issue_date, status FROM service_requests WHERE laptop_id = ?";
$stmt_service_requests = $conn->prepare($sql_service_requests);
$stmt_service_requests->bind_param("i", $laptop_id);
$stmt_service_requests->execute();
$result_service_requests = $stmt_service_requests->get_result();

$service_requests = [];
while ($row = $result_service_requests->fetch_assoc()) {
    $service_requests[] = $row;
}
$stmt_service_requests->close();

// Fetch components for the laptop
$sql_components = "SELECT component_name, status FROM component WHERE laptop_id = ?";
$stmt_components = $conn->prepare($sql_components);
$stmt_components->bind_param("i", $laptop_id);
$stmt_components->execute();
$result_components = $stmt_components->get_result();

$components = [];
while ($row = $result_components->fetch_assoc()) {
    $components[] = $row;
}
$stmt_components->close();

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($laptop['brand']); ?> - Laptop Detail</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fbd9da;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .content {
            padding: 20px;
            background-color: #fbd9da;
            text-align: center;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2bcbc;
        }
        .invoice-button, .cancel-button {
            margin-top: 20px;
            padding: 10px 20px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .invoice-button {
            background-color: #4CAF50;
        }
        .invoice-button:hover {
            background-color: #45a049;
        }
        .cancel-button {
            background-color: #f44336;
        }
        .cancel-button:hover {
            background-color: #e53935;
        }
    </style>
    <script>
        function confirmCancel() {
            if (confirm('Apakah anda akan membatalkan service? Jika anda klik OK maka service akan dibatalkan dan kurir akan segera mengantarkan laptop anda!')) {
                window.location.href = 'cancel_service.php?id=<?php echo $laptop_id; ?>';
            }
        }
    </script>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <div class="content">
        <h2><?php echo htmlspecialchars($laptop['brand']); ?></h2>
        
        <!-- Table for Service Requests -->
        <h3>Status Service Laptop Anda</h3>
        <table>
            <tr>
                <th>Description</th>
                <th>Issue Date</th>
                <th>Status</th>
            </tr>
            <?php foreach ($service_requests as $request): ?>
                <tr>
                    <td><?php echo htmlspecialchars($request['description']); ?></td>
                    <td><?php echo htmlspecialchars($request['issue_date']); ?></td>
                    <td><?php echo htmlspecialchars($request['status']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        
        <!-- Table for Components -->
        <h3>Components</h3>
        <table>
            <tr>
                <th>Component Name</th>
                <th>Status</th>
            </tr>
            <?php foreach ($components as $component): ?>
                <tr>
                    <td><?php echo htmlspecialchars($component['component_name']); ?></td>
                    <td><?php echo htmlspecialchars($component['status']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        
        <!-- Button to view invoices -->
        <button class="invoice-button" onclick="window.location.href='tagihan.php?id=<?php echo $laptop_id; ?>'">Lihat Tagihan</button>
        <button class="cancel-button" onclick="confirmCancel()">Batal Service</button>
    </div>
</div>

</body>
</html>

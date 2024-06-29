<?php
session_start();
include '../db.php';
// Fetch service request counts
$sql_service_request_count = "SELECT COUNT(*) as count FROM laptops WHERE status IS NULL";
$result_service_request_count = $conn->query($sql_service_request_count);
$service_request_count = $result_service_request_count->fetch_assoc()['count'];

$sql_in_progress_count = "SELECT COUNT(*) as count FROM laptops WHERE status = 'in_progress'";
$result_in_progress_count = $conn->query($sql_in_progress_count);
$in_progress_count = $result_in_progress_count->fetch_assoc()['count'];

$sql_completed_count = "SELECT COUNT(*) as count FROM laptops WHERE status = 'completed'";
$result_completed_count = $conn->query($sql_completed_count);
$completed_count = $result_completed_count->fetch_assoc()['count'];

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .dashboard {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-gap: 20px;
            margin-top: 20px;
        }
        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 20px;
        }
        .card .icon {
            font-size: 50px;
            color: #f2bcbc;
        }
        .card h3 {
            margin: 10px 0;
        }
        .card p {
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?>

<div class="container">
    <h1>Technisian Dashboard</h1>
    <div class="dashboard">
        <div class="card">
            <div class="icon">&#128187;</div>
            <h3>Permintaan Service</h3>
            <p><?php echo $service_request_count; ?></p>
        </div>
        <div class="card">
            <div class="icon">&#128187;</div>
            <h3>On Progress</h3>
            <p><?php echo $in_progress_count; ?></p>
        </div>
        <div class="card">
            <div class="icon">&#128187;</div>
            <h3>Completed</h3>
            <p><?php echo $completed_count; ?></p>
        </div>
    </div>
</div>

</body>
</html>

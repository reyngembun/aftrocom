<?php
session_start();
include '../db.php';

// Check if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

// Fetch all laptops
$sql = "SELECT l.*, u.name AS owner_name FROM laptops l LEFT JOIN users u ON l.owner_id = u.id";
$result = $conn->query($sql);
$laptops = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $laptops[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Service Requests</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2bcbc;
        }
        .btn {
            padding: 10px;
            background-color: #f2bcbc;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            font-size: 16px;
            text-decoration: none;
            color: #fff;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container">

    <h2>Service Requests</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Brand</th>
                <th>Model</th>
                <th>Serial Number</th>
                <th>Description</th>
                <th>Status</th>
                <th>Owner</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($laptops as $laptop): ?>
                <tr>
                    <td><?php echo htmlspecialchars($laptop['id']); ?></td>
                    <td><?php echo htmlspecialchars($laptop['brand']); ?></td>
                    <td><?php echo htmlspecialchars($laptop['model']); ?></td>
                    <td><?php echo htmlspecialchars($laptop['serial_number']); ?></td>
                    <td><?php echo htmlspecialchars($laptop['description']); ?></td>
                    <td><?php echo htmlspecialchars($laptop['status']); ?></td>
                    <td><?php echo htmlspecialchars($laptop['owner_name']); ?></td>
                    <td><a href="create_service_request.php?id=<?php echo $laptop['id']; ?>" class="btn">Create Service Request</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>

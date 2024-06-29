<?php
session_start();
include '../db.php';

// Check if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

// Fetch all service requests
$sql = "SELECT sr.*, l.brand, l.model, u.name AS technician_name
        FROM service_requests sr
        LEFT JOIN laptops l ON sr.laptop_id = l.id
        LEFT JOIN users u ON sr.technician_id = u.id
        INNER JOIN (
            SELECT laptop_id, MAX(issue_date) as max_issue_date
            FROM service_requests
            GROUP BY laptop_id
        ) sr_max ON sr.laptop_id = sr_max.laptop_id AND sr.issue_date = sr_max.max_issue_date
        ORDER BY sr.issue_date DESC";
$result = $conn->query($sql);
$service_requests = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $service_requests[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Service Requests Status</title>
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
    <h2>Service Requests Status</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Status</th>
                <th>Description</th>
                <th>Issue Date</th>
                <th>Laptop Brand</th>
                <th>Laptop Model</th>
                <th>Technician</th>
                <th>View Details</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($service_requests) > 0): ?>
                <?php foreach ($service_requests as $request): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($request['id']); ?></td>
                        <td><?php echo htmlspecialchars($request['status']); ?></td>
                        <td><?php echo htmlspecialchars($request['description']); ?></td>
                        <td><?php echo htmlspecialchars($request['issue_date']); ?></td>
                        <td><?php echo htmlspecialchars($request['brand']); ?></td>
                        <td><?php echo htmlspecialchars($request['model']); ?></td>
                        <td><?php echo htmlspecialchars($request['technician_name']); ?></td>
                        <td><a href="laptop_details.php?id=<?php echo $request['laptop_id']; ?>" class="btn">View Details</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No service requests found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php
session_start();
include '../db.php';

// Check if the connection is established
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all laptops
$sql = "SELECT id, brand, status, status_timestamp FROM laptops";
$result = $conn->query($sql);

$laptops = [];
while ($row = $result->fetch_assoc()) {
    $laptops[] = $row;
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Manage Laptops</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #fbd9da;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .container {
            background-color: #fff;
            width: 80%;
            max-width: 900px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 20px;
        }
        .container h2 {
            text-align: center;
            color: #002060;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .table th {
            background-color: #002060;
            color: white;
            text-align: left;
        }
        .btn-update, .btn-manage {
            background-color: #002060;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }
        .overlay-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .form-container input {
            display: block;
            margin: 10px auto;
            padding: 10px;
            width: 80%;
        }
        .form-container button {
            background-color: #002060;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Manage Laptops</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Brand</th>
                <th>Status</th>
                <th>Status Timestamp</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($laptops as $laptop): ?>
                <tr>
                    <td><?php echo htmlspecialchars($laptop['brand']); ?></td>
                    <td><?php echo htmlspecialchars($laptop['status']); ?></td>
                    <td><?php echo htmlspecialchars($laptop['status_timestamp']); ?></td>
                    <td>
                        <button class="btn-update" onclick="showUpdateForm(<?php echo $laptop['id']; ?>)">Update Status</button>
                        <button class="btn-manage" onclick="window.location.href='manage_components.php?laptop_id=<?php echo $laptop['id']; ?>'">Manage Components</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Update Status Overlay -->
<div class="overlay" id="updateStatusForm">
    <div class="overlay-content">
        <h2>Update Laptop Status</h2>
        <div class="form-container">
            <form action="update_laptop_status.php" method="post">
                <input type="hidden" name="laptop_id" id="laptop_id">
                <input type="text" name="status" placeholder="New Status" required>
                <button type="submit">Update</button>
            </form>
        </div>
        <button onclick="closeOverlay('updateStatusForm')">Close</button>
    </div>
</div>

<script>
    function showUpdateForm(laptopId) {
        document.getElementById('laptop_id').value = laptopId;
        document.getElementById('updateStatusForm').style.display = 'flex';
    }

    function closeOverlay(id) {
        document.getElementById(id).style.display = 'none';
    }
</script>
</body>
</html>

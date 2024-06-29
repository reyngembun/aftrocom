<?php
include '../db.php';

$laptop_id = $_GET['laptop_id'];

// Fetch all components for the given laptop
$sql = "SELECT * FROM components WHERE laptop_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $laptop_id);
$stmt->execute();
$result = $stmt->get_result();

$components = [];
while ($row = $result->fetch_assoc()) {
    $components[] = $row;
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Components</title>
    <link rel="stylesheet" href="styles.css">
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
        .btn-update {
            background-color: #002060;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Manage Components for Laptop ID: <?php echo htmlspecialchars($laptop_id); ?></h2>
    <table class="table">
        <thead>
            <tr>
                <th>Component Name</th>
                <th>Status</th>
                <th>Remark</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($components as $component): ?>
                <tr>
                    <td><?php echo htmlspecialchars($component['component_name']); ?></td>
                    <td><?php echo htmlspecialchars($component['status']); ?></td>
                    <td><?php echo htmlspecialchars($component['remark']); ?></td>
                    <td>
                        <button class="btn-update" onclick="showUpdateForm(<?php echo $component['id']; ?>)">Update</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Update Component Overlay -->
<div class="overlay" id="updateComponentForm">
    <div class="overlay-content">
        <h2>Update Component</h2>
        <div class="form-container">
            <form action="update_component.php" method="post">
                <input type="hidden" name="component_id" id="component_id">
                <input type="text" name="status" placeholder="New Status" required>
                <input type="text" name="remark" placeholder="Remark">
                <button type="submit">Update</button>
            </form>
        </div>
        <button onclick="closeOverlay('updateComponentForm')">Close</button>
    </div>
</div>

<script>
    function showUpdateForm(componentId) {
        document.getElementById('component_id').value = componentId;
        document.getElementById('updateComponentForm').style.display = 'flex';
    }

    function closeOverlay(id) {
        document.getElementById(id).style.display = 'none';
    }
</script>
</body>
</html>

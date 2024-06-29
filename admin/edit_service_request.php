<?php
session_start();
include '../db.php';

// Check if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

// Get service request ID from URL
$request_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($request_id == 0) {
    echo "Invalid request ID.";
    exit;
}

// Fetch service request details
$sql = "SELECT * FROM service_requests WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $request_id);
$stmt->execute();
$result = $stmt->get_result();
$request = $result->fetch_assoc();

if (!$request) {
    echo "Service request not found.";
    exit;
}

$laptop_id = $request['laptop_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];
    $description = $_POST['description'];
    $technician_id = isset($_POST['technician_id']) ? intval($_POST['technician_id']) : NULL;

    $sql = "UPDATE service_requests SET status = ?, description = ?, technician_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssii', $status, $description, $technician_id, $request_id);

    if ($stmt->execute()) {
        header("Location: laptop_details.php?id=" . $laptop_id);
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Service Request</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 10px;
        }
        input, textarea, select {
            margin-bottom: 20px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
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
    <h2>Edit Service Request</h2>
    <form method="POST">
        <label for="status">Status</label>
        <select name="status" id="status" required>
            <option value="pending" <?php if ($request['status'] == 'pending') echo 'selected'; ?>>Pending</option>
            <option value="ongoing" <?php if ($request['status'] == 'ongoing') echo 'selected'; ?>>Ongoing</option>
            <option value="completed" <?php if ($request['status'] == 'completed') echo 'selected'; ?>>Completed</option>
        </select>

        <label for="description">Description</label>
        <textarea name="description" id="description" rows="4" required><?php echo htmlspecialchars($request['description']); ?></textarea>

        
        <button type="submit" class="btn">Update</button>
    </form>
</div>
</body>
</html>

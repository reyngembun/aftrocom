<?php
session_start();
include '../db.php';

// Check if laptop_id is provided
if (!isset($_GET['id'])) {
    echo "Laptop ID not provided.";
    exit;
}

$laptop_id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];
    $description = $_POST['description'];
    $technician_id = $_POST['technician_id'] ?? null;

    // Insert new service request into the database
    $stmt = $conn->prepare("INSERT INTO service_requests (status, description, laptop_id, technician_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $status, $description, $laptop_id, $technician_id);
    
    if ($stmt->execute()) {
        // If the service request is successfully created, redirect back to laptop_details.php
        header("Location: laptop_details.php?id=" . $laptop_id);
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
    exit;
}

// Fetch technician options for the dropdown
$technicians = [];
$tech_result = $conn->query("SELECT id, name FROM users WHERE role = 'technician'");
if ($tech_result->num_rows > 0) {
    while ($tech_row = $tech_result->fetch_assoc()) {
        $technicians[] = $tech_row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Service Request</title>
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
        label, input, select, textarea {
            margin-bottom: 10px;
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
        <h2>Create Service Request</h2>
        <form method="POST" action="create_service_request.php?id=<?php echo $laptop_id; ?>">
            <label for="status">Status</label>
            <select name="status" id="status" required>
                <option value="pending">Pending</option>
                <option value="ongoing">Ongoing</option>
                <option value="completed">Completed</option>
            </select>

            <label for="description">Description</label>
            <textarea name="description" id="description" rows="4" required></textarea>

            <button type="submit" class="btn">Create</button>
        </form>
    </div>
</body>
</html>

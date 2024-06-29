<?php
session_start();
include '../db.php';

// Check if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

// Get laptop ID from the query string
$laptop_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($laptop_id == 0) {
    echo "Invalid laptop ID.";
    exit;
}

// Fetch laptop details
$sql = "SELECT * FROM laptops WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $laptop_id);
$stmt->execute();
$result = $stmt->get_result();
$laptop = $result->fetch_assoc();

if (!$laptop) {
    echo "Laptop not found.";
    exit;
}

// Fetch all users for owner selection
$sql = "SELECT id, name FROM users";
$result = $conn->query($sql);
$users = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $serial_number = $_POST['serial_number'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $owner_id = $_POST['owner_id'];

    $sql = "
    UPDATE laptops 
    SET brand = ?, model = ?, serial_number = ?, description = ?, status = ?, owner_id = ?
    WHERE id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $brand, $model, $serial_number, $description, $status, $owner_id, $laptop_id);
    if ($stmt->execute()) {
        header('Location: service_request.php');
        exit;
    } else {
        echo "Error updating laptop details: " . $conn->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Laptop</title>
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
            margin: 10px 0 5px;
        }
        input, select, textarea {
            padding: 8px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            padding: 10px;
            background-color: #f2bcbc;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Laptop</h2>
    <form method="post">
        <label for="brand">Brand</label>
        <input type="text" id="brand" name="brand" value="<?php echo htmlspecialchars($laptop['brand']); ?>" required>

        <label for="model">Model</label>
        <input type="text" id="model" name="model" value="<?php echo htmlspecialchars($laptop['model']); ?>" required>

        <label for="serial_number">Serial Number</label>
        <input type="text" id="serial_number" name="serial_number" value="<?php echo htmlspecialchars($laptop['serial_number']); ?>" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($laptop['description']); ?></textarea>

        <label for="status">Status</label>
        <select id="status" name="status" required>
            <option value="in_progress" <?php echo $laptop['status'] === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
            <option value="completed" <?php echo $laptop['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
            <!-- Add more status options as needed -->
        </select>

        <label for="owner_id">Owner</label>
        <select id="owner_id" name="owner_id" required>
            <?php foreach ($users as $user): ?>
                <option value="<?php echo $user['id']; ?>" <?php echo $laptop['owner_id'] == $user['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($user['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Update Laptop</button>
    </form>
</div>

</body>
</html>

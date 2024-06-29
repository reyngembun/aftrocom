<?php
include ('../db.php');

// Get status filter from GET request
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Build the SQL query based on the selected status filter
$where_clause = "";
if ($status_filter != 'all') {
  if ($status_filter == 'null') {
    $where_clause = "WHERE laptops.status IS NULL";
  } else {
    $where_clause = "WHERE laptops.status = '$status_filter'";
  }
}

// Fetch the laptops data with the specified filter
$sql = "SELECT laptops.*, users.name AS owner_name, users.phone_number 
        FROM laptops 
        JOIN users ON laptops.owner_id = users.id 
        $where_clause
        ORDER BY 
            CASE 
                WHEN laptops.status IS NULL THEN 1 
                WHEN laptops.status = 'in_progress' THEN 2 
                WHEN laptops.status = 'completed' THEN 3 
            END";
$result = $conn->query($sql);

$laptops = [];
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $laptops[] = $row;
  }
}

// Fetch the latest status description from service_requests
$service_requests = [];
$sql = "SELECT laptop_id, description 
        FROM service_requests 
        ORDER BY issue_date DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    if (!isset($service_requests[$row['laptop_id']])) {
      $service_requests[$row['laptop_id']] = $row['description'];
    }
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Service</title>
    <link rel="stylesheet" href="../styles.css"> <!-- Add your CSS file here -->
    <style>
        .container {
            margin: 2em auto;
            width: 90%;
            text-align: center;
        }
        .filter-form {
            margin-bottom: 1em;
        }
        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: inline-block;
            margin: 1em;
            padding: 1em;
            width: 28%;
        }
        .process-btn, .edit-btn {
            background-color: #cc0000;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
            padding: 10px 20px;
            text-align: center;
            margin-top: 10px;
        }
        .edit-btn {
            background-color: #007bff;
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?>
    <div class="container">
        <h1>Permintaan Service</h1>
        <form method="GET" class="filter-form">
            <label for="status">Filter by Status:</label>
            <select name="status" id="status" onchange="this.form.submit()">
                <option value="all" <?php echo $status_filter == 'all' ? 'selected' : ''; ?>>All</option>
                <option value="null" <?php echo $status_filter == 'null' ? 'selected' : ''; ?>>Null</option>
                <option value="in_progress" <?php echo $status_filter == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                <option value="completed" <?php echo $status_filter == 'completed' ? 'selected' : ''; ?>>Completed</option>
            </select>
        </form>
        <?php if (count($laptops) > 0): ?>
            <?php foreach ($laptops as $laptop): ?>
                <div class="card">
                    <img src="images/<?php echo strtolower($laptop['brand']); ?>.png" alt="<?php echo $laptop['brand']; ?>" style="width: 100px;">
                    <p><strong>Status:</strong> <?php echo $laptop['status'] ? $laptop['status'] : 'Null'; ?></p>
                    <p><strong>Brand:</strong> <?php echo $laptop['brand']; ?></p>
                    <p><strong>Model:</strong> <?php echo $laptop['model']; ?></p>
                    <p><strong>Description:</strong> <?php echo $laptop['description']; ?></p>
                    <p><strong>Owner:</strong> <?php echo $laptop['owner_name']; ?></p>
                    <p><strong>Alamat:</strong> <?php echo $laptop['alamat']; ?></p>
                    <p><strong>Phone Number:</strong> <?php echo $laptop['phone_number']; ?></p>
                    <p><strong>Status Terakhir:</strong> <?php echo isset($service_requests[$laptop['id']]) ? $service_requests[$laptop['id']] : 'Tidak ada'; ?></p>
                    <button class="process-btn" onclick="window.location.href='laptop_details.php?id=<?php echo $laptop['id']; ?>'">Process</button>
                    <button class="edit-btn" onclick="window.location.href='edit_laptop.php?id=<?php echo $laptop['id']; ?>'">Edit</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No laptops found for the selected status.</p>
        <?php endif; ?>
    </div>
</body>
</html>
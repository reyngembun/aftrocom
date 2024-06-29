<?php
session_start();
include '../db.php';

// Check if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

// Get laptop ID from URL
$laptop_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($laptop_id == 0) {
    echo "Invalid laptop ID.";
    exit;
}

// Fetch laptop details
$sql = "SELECT l.*, u.name AS owner_name, u.phone_number 
        FROM laptops l 
        LEFT JOIN users u ON l.owner_id = u.id 
        WHERE l.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $laptop_id);
$stmt->execute();
$result = $stmt->get_result();
$laptop = $result->fetch_assoc();

if (!$laptop) {
    echo "Laptop not found.";
    exit;
}

$stmt->close();

// Fetch service requests for this laptop
$sql = "SELECT * FROM service_requests WHERE laptop_id = ? ORDER BY issue_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $laptop_id);
$stmt->execute();
$result = $stmt->get_result();
$service_requests = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $service_requests[] = $row;
    }
}

$stmt->close();

// Fetch invoices for this laptop
$sql = "SELECT * FROM invoice WHERE laptop_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $laptop_id);
$stmt->execute();
$result = $stmt->get_result();
$invoices = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $invoices[] = $row;
    }
}
$sql = "SELECT * FROM component WHERE laptop_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $laptop_id);
$stmt->execute();
$result = $stmt->get_result();
$components = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $components[] = $row;
    }
}

$stmt->close();

$conn->close();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Laptop Details</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        .details {
            margin-bottom: 20px;
        }
        .details div {
            margin-bottom: 10px;
        }
        .details .label {
            font-weight: bold;
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
        .form-container {
            margin-top: 20px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container">
    <h2>Laptop Details</h2>
    <div class="details">
        <div><span class="label">Brand:</span> <?php echo htmlspecialchars($laptop['brand']); ?></div>
        <div><span class="label">Model:</span> <?php echo htmlspecialchars($laptop['model']); ?></div>
        <div><span class="label">Description:</span> <?php echo htmlspecialchars($laptop['description']); ?></div>
        <div><span class="label">Alamat:</span> <?php echo htmlspecialchars($laptop['alamat']); ?></div>
        <div><span class="label">Owner:</span> <?php echo htmlspecialchars($laptop['owner_name']); ?></div>
        <div><span class="label">Phone Number:</span> <?php echo htmlspecialchars($laptop['phone_number']); ?></div>
    </div>
    
    <h2>Status</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Status</th>
                <th>Description</th>
                <th>Issue Date</th>
                <th>Technician</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($service_requests) > 0): ?>
                <?php foreach
                 ($service_requests as $request): ?>
                 <tr>
                     <td><?php echo htmlspecialchars($request['id']); ?></td>
                     <td><?php echo htmlspecialchars($request['status']); ?></td>
                     <td><?php echo htmlspecialchars($request['description']); ?></td>
                     <td><?php echo htmlspecialchars($request['issue_date']); ?></td>
                     <td><?php echo htmlspecialchars($request['technician_id']); ?></td>
                     <td>
                         <a href="edit_service_request.php?id=<?php echo $request['id']; ?>" class="btn">Edit</a>
                         <a href="delete_service_request.php?id=<?php echo $request['id']; ?>" class="btn" onclick="return confirm('Are you sure you want to delete this request?');">Delete</a>
                         <form action="assign_technician.php" method="post" style="display:inline;">
                             <input type="hidden" name="service_request_id" value="<?php echo $request['id']; ?>">
                             <select name="technician_id">
                                 <option value="17">Technician 1</option>
                                 <option value="18">Technician 2</option>
                             </select>
                             <button type="submit" class="btn">Assign</button>
                         </form>
                     </td>
                 </tr>
             <?php endforeach; ?>
         <?php else: ?>
             <tr>
                 <td colspan="6">No service requests found.</td>
             </tr>
         <?php endif; ?>
     </tbody>
 </table>
 <a href="create_service_request.php?id=<?php echo $laptop['id']; ?>" class="btn">Add New Service Request</a>

 <div class="form-container">
     <h2>Create Invoice</h2>
     <form action="create_invoice.php" method="post">
         <input type="hidden" name="laptop_id" value="<?php echo $laptop_id; ?>">
         <div>
             <label for="type">Invoice Type:</label>
             <input type="text" id="type" name="type" required>
         </div>
         <div>
             <label for="description">Description:</label>
             <textarea id="description" name="description" required></textarea>
         </div>
         <div>
             <label for="price">Price:</label>
             <input type="text" id="price" name="price" required>
         </div>
         <button type="submit" class="btn">Create Invoice</button>
     </form>
 </div>

 <h2>Invoices</h2>
 <table>
     <thead>
         <tr>
             <th>ID</th>
             <th>Type</th>
             <th>Description</th>
             <th>Price</th>
             <th>Actions</th>
         </tr>
     </thead>
     <tbody>
         <?php if (count($invoices) > 0): ?>
             <?php foreach ($invoices as $invoice): ?>
                 <tr>
                     <td><?php echo htmlspecialchars($invoice['id']); ?></td>
                     <td><?php echo htmlspecialchars($invoice['type']); ?></td>
                     <td><?php echo htmlspecialchars($invoice['description']); ?></td>
                     <td>Rp <?php echo number_format($invoice['price'], 0, ',', '.'); ?></td>
                     <td>
                     <a href="<?php echo htmlspecialchars($invoice['bukti']); ?>" target="_blank" class="btn">View Bukti</a>
                     <a href="delete_invoice.php?id=<?php echo $invoice['id']; ?>" class="btn" onclick="return confirm('Are you sure you want to delete this invoice?');">Delete</a>
                     </td>
                 </tr>
             <?php endforeach; ?>
         <?php else: ?>
             <tr>
                 <td colspan="5">No invoices found.</td>
             </tr>
         <?php endif; ?>
     </tbody>
 </table>
 <h2>Components</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Component Name</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($components as $component): ?>
            <tr>
                <td><?php echo htmlspecialchars($component['id']); ?></td>
                <td><?php echo htmlspecialchars($component['component_name']); ?></td>
                <td><?php echo htmlspecialchars($component['status']); ?></td>
                <td>
                    <a href="edit_component.php?id=<?php echo $component['id']; ?>" class="btn">Edit</a>
                    <a href="delete_component.php?id=<?php echo $component['id']; ?>" class="btn" onclick="return confirm('Are you sure you want to delete this component?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="form-container">
    <h2>Add New Component</h2>
    <form action="create_component.php" method="post">
        <input type="hidden" name="laptop_id" value="<?php echo $laptop_id; ?>">
        <div>
            <label for="component_name">Component Name:</label>
            <input type="text" id="component_name" name="component_name" required>
        </div>
        <div>
            <label for="status">Status:</label>
            <input type="text" id="status" name="status" required>
        </div>
        <button type="submit" class="btn">Add Component</button>
    </form>
</div>

</div>
</body>
</html>


<?php
session_start();
include '../db.php';

// Get the laptop ID from the URL parameter
$laptop_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch invoice details for the laptop
$sql_invoice = "SELECT * FROM invoice WHERE laptop_id = ?";
$stmt_invoice = $conn->prepare($sql_invoice);
$stmt_invoice->bind_param("i", $laptop_id);
$stmt_invoice->execute();
$result_invoice = $stmt_invoice->get_result();

if ($result_invoice->num_rows === 0) {
    echo "Invoice not found.";
    exit;
}

$invoices = [];
while ($row = $result_invoice->fetch_assoc()) {
    $invoices[] = $row;
}
$stmt_invoice->close();
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Invoice Detail</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fbd9da;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .content {
            padding: 20px;
            background-color: #fbd9da;
            text-align: center;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2bcbc;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <div class="content">
        <h2>Detail Tagihan</h2>
        <table>
            <tr>
                <th>Produk</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
            <?php
            $total = 0;
            foreach ($invoices as $invoice): 
                $total += $invoice['price'];
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($invoice['type']); ?></td>
                    <td><?php echo htmlspecialchars($invoice['description']); ?></td>
                    <td>Rp <?php echo number_format($invoice['price'], 0, ',', '.'); ?></td>
                    <td>Rp <?php echo number_format($invoice['price'], 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <p>Total: Rp <?php echo number_format($total, 0, ',', '.'); ?></p>
    </div>
</div>

</body>
</html>

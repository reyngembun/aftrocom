<?php
// Mulai sesi
session_start();

// Konfigurasi database
include("../db.php");

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in";
    exit;
}

$user_id = $_SESSION['user_id'];

// Mendapatkan laptop_id dari URL parameter
$laptop_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($laptop_id === 0) {
    echo "Invalid laptop ID";
    exit;
}

// Retrieve the laptop to ensure it belongs to the logged-in user
$sql_laptop_check = "SELECT id FROM laptops WHERE id = ? AND owner_id = ?";
$stmt_laptop_check = $conn->prepare($sql_laptop_check);
$stmt_laptop_check->bind_param("ii", $laptop_id, $user_id);
$stmt_laptop_check->execute();
$result_laptop_check = $stmt_laptop_check->get_result();

if ($result_laptop_check->num_rows === 0) {
    echo "Laptop not found or does not belong to this user.";
    exit;
}

// Retrieve the invoice details for the laptop_id
$sql_invoice = "SELECT * FROM invoice WHERE laptop_id = ?";
$stmt_invoice = $conn->prepare($sql_invoice);
$stmt_invoice->bind_param("i", $laptop_id);
$stmt_invoice->execute();
$result_invoice = $stmt_invoice->get_result();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Tagihan</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .invoice-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin-top: 20px;
            margin: auto;
        }
        .invoice-header, .invoice-body, .invoice-footer {
            margin-bottom: 20px;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .invoice-header div {
            text-align: right;
        }
        .invoice-footer {
            font-size: 12px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .upload-form {
            margin-top: 20px;
        }
        .back-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #f2bcbc;
            color: black;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            width: 100px;
            border: 1px solid #ddd;
        }
        .back-button:hover {
            background-color: #f99;
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?>
    <div class="invoice-container" style="margin-top: 80px;">
        <div class="invoice-header">
            <div>
                <h2>Aftrocom</h2>
                <p>Penerima</p>
                <p>PT. Aladin</p>
                <p>No. Telpon: 08123789234</p>
                <p>Alamat: Jalan Laksda Adi Sucipto</p>
            </div>
            <div>
                <p>No. Rekening</p>
                <p>23781-34234-3424</p>
                <p>Atas Nama: AFTROCOM</p>
            </div>
        </div>
        <div class="invoice-body">
            <?php if ($result_invoice->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Produk</th>
                        <th>Deskripsi</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                    <?php
                    $total = 0;
                    while ($invoice = $result_invoice->fetch_assoc()) {
                        $total += $invoice['price'];
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($invoice['type']) . "</td>";
                        echo "<td>" . htmlspecialchars($invoice['description']) . "</td>";
                        echo "<td>Rp " . number_format($invoice['price'], 0, ',', '.') . "</td>";
                        echo "<td>Rp " . number_format($invoice['price'], 0, ',', '.') . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
                <p>Total: Rp <?php echo number_format($total, 0, ',', '.'); ?></p>
            <?php else: ?>
                <p>Belum ada tagihan.</p>
            <?php endif; ?>
        </div>
        <div class="invoice-footer">
            <p>Jika Anda mengklik "Confirm" berarti Anda telah menyetujui estimasi biaya yang telah ditentukan. Setelah Pembayaran Terverifikasi teknisi kami segera mengerjakan unit anda</p>
        </div>
        <div class="upload-form">
            <form action="upload_bukti.php" method="post" enctype="multipart/form-data">
                <label for="bukti">Upload Bukti Pembayaran:</label>
                <input type="file" name="bukti" id="bukti" required>
                <input type="hidden" name="laptop_id" value="<?php echo $laptop_id; ?>">
                <button type="submit">Upload</button>
            </form>
        </div>
        <a href="javascript:history.back()" class="back-button">Back</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>

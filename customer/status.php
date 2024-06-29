<?php
session_start();
include '../db.php';

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: ../login.php");
    exit();
}

// Mendapatkan ID pengguna dari sesi
$current_user_id = $_SESSION['user_id'];

// Mengambil detail laptop untuk pengguna saat ini
$sql = "SELECT id, brand, model, serial_number, description FROM laptops WHERE owner_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();

$laptops = [];
while ($row = $result->fetch_assoc()) {
    $laptops[] = $row;
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Status</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .container {
            max-width: 800px; /* Meningkatkan lebar maksimal */
            margin: 50px auto;
            padding: 20px;
            background-color: #fbd9da;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center; /* Memusatkan kontainer */
        }
        .content {
            padding: 20px;
            background-color: #fbd9da;
            text-align: center;
        }
        .laptop-list {
            list-style-type: none;
            padding: 0;
            margin: 0 auto;
            max-width: 500px; /* Memusatkan daftar dan membatasi lebarnya */
        }
        .laptop-item {
            background-color: white;
            padding: 10px 15px; /* Menyesuaikan padding untuk spasi yang lebih baik */
            margin-bottom: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%; /* Membuat item mengambil seluruh lebar parent */
        }
        .detail-button {
            background-color: #ffffff;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <div class="content">
        <h2>Laptop Anda</h2>
        <ul class="laptop-list">
            <?php foreach ($laptops as $laptop): ?>
                <li class="laptop-item">
                    <span><?php echo htmlspecialchars($laptop['brand']); ?> - <?php echo htmlspecialchars($laptop['model']); ?></span>
                    <button class="detail-button" onclick="showDetails(<?php echo $laptop['id']; ?>)">Detail</button>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<script>
    function showDetails(laptopId) {
        window.location.href = 'laptop_detail.php?id=' + laptopId;
    }
</script>

</body>
</html>

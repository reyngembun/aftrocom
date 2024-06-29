<?php
// Mulai sesi
session_start();

// Konfigurasi database
include("../db.php");

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: ../login.php");
    exit();
}

$success = false;
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['brand'] === 'Lainnya') {
        $brand = $_POST['other_brand'];
    } else {
        $brand = $_POST['brand'];
    }
    $model = $_POST['model'];
    $serial_number = $_POST['serial_number'];
    $description = $_POST['description'];
    $alamat = $_POST['alamat'];
    $owner_id = $_SESSION['user_id']; // Mendapatkan ID pengguna dari sesi

    // Menyimpan data ke database menggunakan prepared statements
    $stmt = $conn->prepare("INSERT INTO laptops (brand, model, serial_number, description, owner_id, alamat) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $brand, $model, $serial_number, $description, $owner_id, $alamat);

    if ($stmt->execute() === TRUE) {
        $success = true;
    } else {
        $error = "Error: " . $stmt->error;
    }

    // Menutup koneksi
    $stmt->close();
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service - AFTERCOM</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2d7d5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            text-align: center;
            color: #c9302c;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        label {
            font-weight: bold;
            color: #333;
        }
        input, textarea, select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .brand-logos {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }
        .brand-logos img {
            height: 50px;
            cursor: pointer;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: transform 0.2s ease-in-out, border-color 0.2s ease-in-out;
        }
        .brand-logos img.selected {
            transform: scale(1.1);
            border-color: #c9302c;
        }
        .submit-btn {
            padding: 15px;
            background-color: #c9302c;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-align: center;
        }
        .other-brand-input {
            display: none;
        }
    </style>
    <script>
        function selectBrand(brand) {
            const brandImages = document.querySelectorAll('.brand-logos img');
            brandImages.forEach(img => {
                img.classList.remove('selected');
            });

            document.getElementById(brand).checked = true;
            document.querySelector(`img[alt="${brand}"]`).classList.add('selected');

            const otherBrandInput = document.querySelector('.other-brand-input');
            if (brand === 'Lainnya') {
                otherBrandInput.style.display = 'block';
            } else {
                otherBrandInput.style.display = 'none';
            }
        }

        <?php if ($success): ?>
        window.onload = function() {
            alert("Laptop telah ditambahkan");
            window.location.href = "status.php";
        }
        <?php endif; ?>
    </script>

</head>
<body>
<?php include ("header.php"); ?>
    <div class="container">
        <h2>Pilih Merek Laptop Anda</h2>
        <?php if ($error): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="service.php" method="post">
            <div class="brand-logos">
                <label>
                    <input type="radio" id="Asus" name="brand" value="Asus" required hidden>
                    <img src="images/asus.png" alt="Asus" onclick="selectBrand('Asus')">
                </label>
                <label>
                    <input type="radio" id="Acer" name="brand" value="Acer" required hidden>
                    <img src="images/acer.png" alt="Acer" onclick="selectBrand('Acer')">
                </label>
                <label>
                    <input type="radio" id="Lenovo" name="brand" value="Lenovo" required hidden>
                    <img src="images/lenovo.png" alt="Lenovo" onclick="selectBrand('Lenovo')">
                </label>
                <label>
                    <input type="radio" id="HP" name="brand" value="HP" required hidden>
                    <img src="images/hp.png" alt="HP" onclick="selectBrand('HP')">
                </label>
                <label>
                    <input type="radio" id="Samsung" name="brand" value="Samsung" required hidden>
                    <img src="images/samsung.png" alt="Samsung" onclick="selectBrand('Samsung')">
                </label>
                <label>
                    <input type="radio" id="Apple" name="brand" value="Apple" required hidden>
                    <img src="images/apple.png" alt="Apple" onclick="selectBrand('Apple')">
                </label>
                <label>
                    <input type="radio" id="Dell" name="brand" value="Dell" required hidden>
                    <img src="images/dell.png" alt="Dell" onclick="selectBrand('Dell')">
                </label>
                <label>
                    <input type="radio" id="Xiaomi" name="brand" value="Xiaomi" required hidden>
                    <img src="images/xiaomi.png" alt="Xiaomi" onclick="selectBrand('Xiaomi')">
                </label>
                <label>
                    <input type="radio" id="Microsoft" name="brand" value="Microsoft" required hidden>
                    <img src="images/microsoft.png" alt="Microsoft" onclick="selectBrand('Microsoft')">
                </label>
                <label>
                    <input type="radio" id="Lainnya" name="brand" value="Lainnya" required hidden>
                    <img src="images/lainya.png" alt="Lainnya" onclick="selectBrand('Lainnya')">
                </label>
            </div>
            <div class="other-brand-input">
                   <input type="text" name="other_brand" placeholder="Masukkan merek lainnya">
            </div>
  
            <input type="text" name="model" placeholder="Masukkan model laptop" required>
            <input type="text" name="serial_number" placeholder="Masukkan nomor seri laptop" required>
            <h2>Jenis Kerusakan</h2>
            <textarea name="description" placeholder="Deskripsikan kerusakan laptop Anda" required></textarea>
            <h2>Alamat Penjemputan</h2>
            <textarea name="alamat" placeholder="Masukkan alamat lengkap atau link Google Maps" required></textarea>
            <button type="submit" class="submit-btn">Service</button>
        </form>
    </div>
<?php include ("../footer.php"); ?>
</body>
</html>

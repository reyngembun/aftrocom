<?php
session_start();
include("db.php");

// Memproses form login jika data dikirim melalui POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form login
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query ke database untuk memeriksa kecocokan username
    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Jika data ditemukan
        $row = $result->fetch_assoc();
        
        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            // Menyimpan data ke sesi
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            // Redirect sesuai role
            switch ($row['role']) {
                case 'admin':
                    header("Location: admin/admin_dashboard.php");
                    break;
                case 'technician':
                    header("Location: technician/dashboard.php");
                    break;
                case 'customer':
                    header("Location: customer/dashboard.php");
                    break;
                default:
                    echo "Role tidak valid";
            }
            exit();
        } else {
            $login_error = "Password salah. Silakan coba lagi.";
        }
    } else {
        $login_error = "Username tidak ditemukan. Silakan coba lagi.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AFTERCOM</title>
    <link rel="stylesheet" href="styles-login.css">
</head>
<body>
    <div class="container">
        <div class="welcome-section">
            <h2>Selamat Datang!</h2>
            <p>Segera daftarkan diri Anda dan bergabung dengan layanan kami sekarang!</p>
            <button onclick="location.href='register.html'">Register</button>
        </div>
        <div class="login-section">
            <h2>Login</h2>
            <?php
            if (isset($login_error)) {
                echo "<p style='color:red;'>$login_error</p>";
            }
            ?>
            <form action="login.php" method="post">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
                
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit">Login</button>
                
                <p>atau masuk melalui</p>
                <div class="social-login">
                    <img src="google.png" alt="Google Login">
                    <img src="facebook.png" alt="Facebook Login">
                </div>
            </form>
        </div>
    </div>
</body>
</html>

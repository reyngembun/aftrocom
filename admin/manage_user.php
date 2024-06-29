<?php
session_start();
include '../db.php';

// Handle add user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $alamat = $_POST['alamat'];
    $role = $_POST['role'];

    $sql_add_user = "INSERT INTO users (username, password, name, email, phone_number, alamat, role) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_add_user = $conn->prepare($sql_add_user);
    $stmt_add_user->bind_param("sssssss", $username, $password, $name, $email, $phone_number, $alamat, $role);
    if ($stmt_add_user->execute()) {
        echo "User added successfully!";
    } else {
        echo "Error adding user: " . $conn->error;
    }
    $stmt_add_user->close();
}

// Handle delete user
if (isset($_GET['delete'])) {
    $id_to_delete = intval($_GET['delete']);
    $sql_delete_user = "DELETE FROM users WHERE id = ?";
    $stmt_delete_user = $conn->prepare($sql_delete_user);
    $stmt_delete_user->bind_param("i", $id_to_delete);
    if ($stmt_delete_user->execute()) {
        echo "User deleted successfully!";
    } else {
        echo "Error deleting user: " . $conn->error;
    }
    $stmt_delete_user->close();
}

// Fetch users
$sql_users = "SELECT id, username, name, email, phone_number, alamat, role FROM users";
$result_users = $conn->query($sql_users);
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2bcbc;
        }
        form {
            margin-top: 20px;
        }
        input[type="text"], input[type="email"], input[type="password"], select {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #f2bcbc;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 10px 0;
            cursor: pointer;
            border-radius: 4px;
        }
    </style>
</head>
<body>
<?php include('header.php'); ?>
<div class="container">
    <h1>Manage Users</h1>

    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Alamat</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php while ($user = $result_users->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['id']); ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['name']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['phone_number']); ?></td>
                <td><?php echo htmlspecialchars($user['alamat']); ?></td>
                <td><?php echo htmlspecialchars($user['role']); ?></td>
                <td>
                    <a href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a> |
                    <a href="manage_user.php?delete=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h2>Add New User</h2>
    <form method="post" action="manage_user.php">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="phone_number">Phone Number:</label>
        <input type="text" name="phone_number" id="phone_number" required>

        <label for="alamat">Alamat:</label>
        <input type="text" name="alamat" id="alamat" required>

        <label for="role">Role:</label>
        <select name="role" id="role" required>
            <option value="admin">Admin</option>
            <option value="technician">Technician</option>
            <option value="customer">Customer</option>
        </select>

        <input type="submit" name="add_user" value="Add User">
    </form>
</div>

</body>
</html>

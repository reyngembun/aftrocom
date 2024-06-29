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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Memeriksa apakah laptop_id ada di POST
    if (!isset($_POST['laptop_id']) || empty($_POST['laptop_id'])) {
        echo "Invalid laptop ID";
        exit;
    }

    $laptop_id = intval($_POST['laptop_id']);

    // Debugging: Memeriksa nilai laptop_id
    echo "Laptop ID from POST: " . $laptop_id . "<br>";

    $target_dir = "../admin/uploads/";

    // Ensure the directory exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Append timestamp to the file name to ensure uniqueness
    $timestamp = time();
    $original_filename = basename($_FILES["bukti"]["name"]);
    $target_file = $target_dir . $timestamp . "_" . $original_filename;

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["bukti"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["bukti"]["size"] > 5000000) { // 5MB limit
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["bukti"]["tmp_name"], $target_file)) {
            // Save file path to the bukti column in the invoice table
            $sql = "UPDATE invoice SET bukti = ? WHERE laptop_id = ?";
            $stmt = $conn->prepare($sql);

            // Check if the statement was prepared successfully
            if ($stmt === false) {
                die("Error preparing the statement: " . $conn->error);
            }

            $stmt->bind_param("si", $target_file, $laptop_id);
            if ($stmt->execute()) {
                // Redirect to tagihan.php after successful upload
                header("Location: tagihan.php?id=" . $laptop_id);
            } else {
                echo "Sorry, there was an error saving your file: " . $stmt->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

$conn->close();
?>

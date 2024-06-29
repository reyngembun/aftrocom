<?php
// Include database connection
include '../db.php';

// Get POST data
$nama = $_POST['nama'];
$telepon = $_POST['telepon'];
$jam_penjemputan = $_POST['jam_penjemputan'];

// Handle pickup form submission
// Implement the logic to save these details into the database

header('Location: laptop_detail.php');
?>

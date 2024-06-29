<?php
// Lakukan koneksi ke basis data
include('../db.php');

// Pastikan ada parameter ID yang diterima
if(isset($_GET['id'])) {
    // Ambil ID gambar dari parameter
    $id = $_GET['id'];
    
    // Query untuk mengambil data gambar berdasarkan ID
    $sql = "SELECT bukti FROM invoice WHERE id=$id";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 1) {
        // Ambil data gambar dari hasil query
        $row = $result->fetch_assoc();
        $imageData = $row["bukti"];
        
        // Set header untuk menentukan jenis konten sebagai gambar
        header("Content-type: image/jpeg");
        
        // Beri nama file untuk unduhan
        header("Content-Disposition: attachment; filename=invoice_image.jpg");
        
        // Keluarkan data gambar
        echo $imageData;
    } else {
        echo "Image not found.";
    }
} else {
    echo "Invalid request.";
}
$conn->close();
?>

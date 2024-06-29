<?php
session_start();
include '../db.php';

// Check if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data
    $errors = [];

    // Validate laptop ID
    $laptop_id = isset($_POST['laptop_id']) ? intval($_POST['laptop_id']) : 0;
    if ($laptop_id == 0) {
        $errors[] = "Invalid laptop ID.";
    }

    // Validate component name
    $component_name = trim($_POST['component_name']);
    if (empty($component_name)) {
        $errors[] = "Component name is required.";
    }

    // Validate component status
    $status = trim($_POST['status']);
    if (empty($status)) {
        $errors[] = "Component status is required.";
    }

    // If no errors, insert component into database
    if (empty($errors)) {
        $sql = "INSERT INTO component (laptop_id, component_name, status) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $laptop_id, $component_name, $status);
        if ($stmt->execute()) {
            // Redirect to laptop details page with success message
            header("Location: laptop_details.php?id=$laptop_id&success=Component created successfully.");
            exit;
        } else {
            // Redirect to laptop details page with error message
            header("Location: laptop_details.php?id=$laptop_id&error=Failed to create component.");
            exit;
        }
    } else {
        // Redirect to laptop details page with error messages
        $error_message = implode("<br>", $errors);
        header("Location: laptop_details.php?id=$laptop_id&error=$error_message");
        exit;
    }
} else {
    // If accessed without submitting form, redirect to homepage or appropriate page
    header("Location: index.php");
    exit;
}
?>

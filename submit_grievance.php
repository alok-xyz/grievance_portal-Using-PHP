<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user ID and complaint
$user_id = $_SESSION['user_id'];
$complaint = $_POST['complaint'];

// Use a prepared statement to safely insert data into the database
$stmt = $conn->prepare("INSERT INTO grievances (user_id, complaint, status, created_at) VALUES (?, ?, 'Pending', NOW())");
$stmt->bind_param("is", $user_id, $complaint);

if ($stmt->execute()) {
    header("Location: dashboard.php?message=Complaint submitted successfully");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

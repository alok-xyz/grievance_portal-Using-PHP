<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Get form data
$grievance_id = $_POST['grievance_id'];
$status = $_POST['status'];
$remark = $_POST['remark'];

// Update the grievance in the database
$sql = "UPDATE grievances SET status = ?, remark = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $status, $remark, $grievance_id);
$stmt->execute();

$stmt->close();
$conn->close();

// Redirect back to the admin dashboard
header("Location: admin_dashboard.php");
exit();
?>

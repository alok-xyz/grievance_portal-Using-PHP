<?php
// Database credentials
$servername = "localhost";
$username = "root";  // Change to your database username
$password = "";       // Change to your database password
$dbname = "grievance_portal";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

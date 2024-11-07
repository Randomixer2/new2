<?php
// Database connection details
$servername = "localhost";  // Database server (usually localhost for local setups)
$username = "root";         // Default MySQL username for XAMPP
$password = "";             // Default password is empty for XAMPP
$dbname = "project_webapp"; // The name of your database

// Create a new MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    // Optionally set the character set to UTF-8
    $conn->set_charset("utf8");
}
?>

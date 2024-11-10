<?php
session_start();
require 'db_connect.php'; // Include database connection

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get updated form data
    $user_id = $_SESSION['user_id'];
    $fullname = $_POST['full_name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $location = $_POST['location'];

    // Validate the input (optional, add any necessary validation)

    // Update the user information in the database
    $stmt = $conn->prepare("UPDATE user SET fullname = ?, email = ?, contact = ?, location = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $fullname, $email, $contact, $location, $user_id);

    if ($stmt->execute()) {
        // If the update was successful, update session variables
        $_SESSION['name'] = $fullname;
        $_SESSION['email'] = $email;
        $_SESSION['contact'] = $contact;
        $_SESSION['address'] = $location;

        // Redirect to profile page or show success message
        echo "<script>alert('Profile updated successfully!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating profile.');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

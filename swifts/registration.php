<?php
session_start(); // Start the session

// Include the database connection
require 'db_connect.php';  // Ensure this path is correct

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $location = $_POST['location'];
    $contact = $_POST['contact'];
    $password = $_POST['password'];

    // Validate the input (ensure no fields are empty)
    if (empty($fullname) || empty($email) || empty($location) || empty($contact) || empty($password)) {
        echo "<script>alert('All fields are required.');</script>";
    } else {
        // Check if the email already exists in the database
        $stmt = $conn->prepare("SELECT id FROM user WHERE email = ?");
        if ($stmt === false) {
            die("Error preparing email check query: " . $conn->error);
        }
        
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script>alert('This email is already registered.');</script>";
        } else {
            // Hash the password before saving to the database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare the SQL statement to insert the new user into the database
            $insert_stmt = $conn->prepare("INSERT INTO user (fullname, email, location, contact, password) VALUES (?, ?, ?, ?, ?)");
            if ($insert_stmt === false) {
                die("Error preparing insert query: " . $conn->error);
            }

            // Bind parameters and execute the statement
            $insert_stmt->bind_param("sssss", $fullname, $email, $location, $contact, $hashed_password);

            if ($insert_stmt->execute()) {
                // Registration successful, trigger success modal
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('successModal').style.display = 'block';
                    });
                </script>";
            } else {
                echo "<script>alert('Error executing insert query: {$insert_stmt->error}');</script>";
            }

            $insert_stmt->close();
        }
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swift Aid - Registration</title>
    <style>
        /* Background styling for the body */
        body {
            background-image: url('emergency.png'); /* Update this path to where your image is stored */
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Modal styling */
        #successModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            max-width: 400px;
            width: 80%;
            color: #333;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .modal-content h2 {
            margin-bottom: 15px;
            color: #333;
        }

        .modal-content button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        .modal-content button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <!-- Success Modal -->
    <div id="successModal">
        <div class="modal-content">
            <h2>Registration Successful!</h2>
            <p>You can now log in.</p>
            <button onclick="redirectToLogin()">Log In Now</button>
        </div>
    </div>

    <script>
        // Redirect to login page when "Log In Now" button is clicked
        function redirectToLogin() {
            window.location.href = "login.php";
        }

        // Show the modal (example usage; add your condition to display it when registration is complete)
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('successModal').style.display = 'flex';
        });
    </script>
</body>
</html>

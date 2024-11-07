<?php
session_start(); // Start the session

// Check if the user is already logged in (to prevent redirect loop)
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");  // Redirect to dashboard if already logged in
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    require 'db_connect.php';

    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare query to check if email exists
    $stmt = $conn->prepare("SELECT id, email, password FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $stored_email, $stored_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $stored_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['email'] = $stored_email;
            header("Location: dashboard.php");
            exit();
        } else {
            $error_message = "Incorrect password.";
        }
    } else {
        $error_message = "No user found with this email.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swift Aid</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <div class="form-box">
            <h1>Swift Aid</h1>

            <!-- Sign In Form -->
            <form id="signin-form" action="login.php" method="POST">
                <div class="input-group">
                    <div class="input-field">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="input-field">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="signin-password" name="password" placeholder="Password" required>
                        <i class="fa-solid fa-eye" id="toggle-signin-password" style="cursor: pointer;"></i>
                    </div>
                    <div class="checkbox-field">
                        <input type="checkbox" id="show-signin-password">
                        <label for="show-signin-password">Show Password</label>
                    </div>
                </div>
                <div class="btn-field">
                    <button type="submit">Sign In</button>
                </div>
                <p class="toggle-link">Don't have an account? <a href="#" id="toggle-to-signup">Sign Up</a></p>
            </form>

            <!-- Error Message Display -->
            <?php if (isset($error_message)) { ?>
                <p style="color: red;"><?php echo $error_message; ?></p>
            <?php } ?>

            <!-- Sign Up Form (Initially Hidden) -->
            <form id="signup-form" action="registration.php" method="POST" style="display: none;">
                <h2>Create Account</h2>
                <div class="input-group">
                    <div class="input-field">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" name="fullname" placeholder="Full Name" required>
                    </div>
                    <div class="input-field">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="input-field">
                        <i class="fa-solid fa-map-marker-alt"></i>
                        <input type="text" name="location" placeholder="Location" required>
                    </div>
                    <div class="input-field">
                        <i class="fa-solid fa-phone"></i>
                        <input type="tel" name="contact" placeholder="Contact Number" required>
                    </div>
                    <div class="input-field">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="signup-password" name="password" placeholder="Password" required>
                        <i class="fa-solid fa-eye" id="toggle-signup-password" style="cursor: pointer;"></i>
                    </div>
                    <div class="checkbox-field">
                        <input type="checkbox" id="show-signup-password">
                        <label for="show-signup-password">Show Password</label>
                    </div>
                </div>
                <div class="btn-field">
                    <button type="submit">Sign Up</button>
                </div>
                <p class="toggle-link">Already have an account? <a href="#" id="toggle-to-signin">Sign In</a></p>
            </form>
        </div>
    </div>

    <script>
        const signinForm = document.getElementById('signin-form');
        const signupForm = document.getElementById('signup-form');
        const toggleToSignup = document.getElementById('toggle-to-signup');
        const toggleToSignin = document.getElementById('toggle-to-signin');

        toggleToSignup.addEventListener('click', (e) => {
            e.preventDefault();
            signinForm.style.display = 'none';
            signupForm.style.display = 'block';
        });

        toggleToSignin.addEventListener('click', (e) => {
            e.preventDefault();
            signupForm.style.display = 'none';
            signinForm.style.display = 'block';
        });

        // Toggle password visibility for Sign In
        const signinPassword = document.getElementById('signin-password');
        const showSigninPassword = document.getElementById('show-signin-password');
        showSigninPassword.addEventListener('change', () => {
            signinPassword.type = showSigninPassword.checked ? 'text' : 'password';
        });

        // Toggle password visibility for Sign Up
        const signupPassword = document.getElementById('signup-password');
        const showSignupPassword = document.getElementById('show-signup-password');
        showSignupPassword.addEventListener('change', () => {
            signupPassword.type = showSignupPassword.checked ? 'text' : 'password';
        });
    </script>
</body>

</html>

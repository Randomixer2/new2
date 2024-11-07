<?php
session_start();  // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Redirect to login if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swift Aid - Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Linking to the external CSS file -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        /* General Dashboard Styling */
        
        body {
        background-image: url(emergency.png);
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: cover; 
        } 
        .dashboard-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
        }
        
        .form-box {
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .icon-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

        .icon {
            cursor: pointer;
            text-align: center;
            width: 100px;
            margin: 10px;
        }

        .icon img {
            width: 60px;
            height: 60px;
            border-radius: 8px;
        }

        .icon p {
            font-size: 14px;
            margin-top: 5px;
        }

        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
            text-align: center;
            position: relative;
        }

        .modal-content h2 {
            margin-bottom: 15px;
        }

        .modal-content button {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #007bff;
            color: #fff;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            cursor: pointer;
            font-size: 20px;
            color: #555;
        }

        /* Video Modal Styling */
        #logout-video-container {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
        }

        #logout-video {
            width: 80%;
            max-width: 600px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container" id="dashboard">
        <div class="form-box">
            <h1>Welcome, <?php echo $_SESSION['email']; ?>!</h1>
            <p>Welcome to your Emergency Hotline Dashboard.</p>
            <div class="icon-container">
                <!-- Emergency Hotline icons -->
                <?php
                $icons = [
                    ["src" => "cross.png", "alt" => "Ambulance", "title" => "Ambulance"],
                    ["src" => "fire.jpg", "alt" => "Fire Department", "title" => "Fire Department"],
                    ["src" => "police.jpg", "alt" => "Police", "title" => "Police"],
                    ["src" => "disaster.png", "alt" => "Disaster Response", "title" => "Disaster Response"],
                    ["src" => "posion.png", "alt" => "Poison Control", "title" => "Poison Control"],
                    ["src" => "coast.png", "alt" => "Coast Guard", "title" => "Coast Guard"],
                    ["src" => "electric.jpg", "alt" => "Electricity Emergency", "title" => "Electricity Emergency"],
                    ["src" => "gas.jpg", "alt" => "Gas Leak", "title" => "Gas Leak"],
                    ["src" => "water.png", "alt" => "Water Supply", "title" => "Water Supply"],
                    ["src" => "search.jpg", "alt" => "Search & Rescue", "title" => "Search & Rescue"]
                ];
                foreach ($icons as $icon) {
                    echo "
                    <div class='icon' onclick='openModal(\"{$icon['title']}\")'>
                        <img src='{$icon['src']}' alt='{$icon['alt']}'>
                        <p>{$icon['title']}</p>
                    </div>";
                }
                ?>
            </div>
            <button onclick="playLogoutVideo()" class="logout-btn">Logout</button>
        </div>
    </div>

    <!-- Logout Video Container -->
    <div id="logout-video-container">
        <video id="logout-video" src="Swift Aid.mp4" type="video/mp4" autoplay></video>
    </div>

    <!-- Modal Structure -->
    <div class="modal" id="info-modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2 id="modal-title"></h2>
            <p>Choose an action below:</p>
            <button onclick="alert('Calling Now...')">Call Now</button>
            <button onclick="alert('Viewing Info...')">View Info</button>
            <button onclick="alert('Send Emergency Message')">Send Emergency Message</button>
        </div>
    </div>

    <script>
        function openModal(title) {
            document.getElementById("modal-title").innerText = title;
            document.getElementById("info-modal").style.display = "flex";
        }

        function closeModal() {
            document.getElementById("info-modal").style.display = "none";
        }

        // Close modal if clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById("info-modal");
            if (event.target === modal) {
                modal.style.display = "none";
            }
        }
        function playLogoutVideo() {
    document.getElementById("dashboard").style.display = "none";
    document.getElementById("logout-video-container").style.display = "flex";
    const video = document.getElementById("logout-video");

    video.play();

    // Redirect to logout.php after the video ends
    video.onended = function() {
        window.location.href = "logout.php"; // Redirect to logout script
    };
}

    </script>
</body>
</html>

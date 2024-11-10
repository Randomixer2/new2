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
                    ["src" => "cross.png", "alt" => "Ambulance", "title" => "Ambulance", "info" => "Provides emergency medical assistance and transport to healthcare facilities.", "number" => "+63 910 339 1561"],
                    ["src" => "fire.jpg", "alt" => "Fire Department", "title" => "Fire Department", "info" => "Handles fire outbreaks, rescue operations, and related emergencies.", "number" => "+63 910 767 5366"],
                    ["src" => "police.jpg", "alt" => "Police", "title" => "Police", "info" => "Maintains law and order, and responds to crime-related incidents.", "number" => "+63 967 835 1942"],
                    ["src" => "disaster.png", "alt" => "Disaster Response", "title" => "Disaster Response", "info" => "Manages response and relief in case of natural or man-made disasters.", "number" => "+63 907 089 0949"],
                    ["src" => "posion.png", "alt" => "Poison Control", "title" => "Poison Control", "info" => "Provides guidance for poison-related emergencies and prevention advice.", "number" => "+63 909 413 5480"],
                    ["src" => "coast.png", "alt" => "Coast Guard", "title" => "Coast Guard", "info" => "Responsible for maritime safety, security, and search and rescue at sea.", "number" => "+63 994 226 0905"],
                    ["src" => "electric.jpg", "alt" => "Electricity Emergency", "title" => "Electricity Emergency", "info" => "Responds to electrical hazards, power outages, and related issues.", "number" => "16211"],
                    ["src" => "gas.jpg", "alt" => "Gas Leak", "title" => "Gas Leak", "info" => "Handles emergency situations involving gas leaks or related dangers.", "number" => "527-0711"],
                    ["src" => "water.png", "alt" => "Water Supply", "title" => "Water Supply", "info" => "Manages water supply issues, including leaks, shortages, and contamination.", "number" => "1627"],
                    ["src" => "search.jpg", "alt" => "Search & Rescue", "title" => "Search & Rescue", "info" => "Conducts search and rescue operations in various emergencies.", "number" => "911"]
                ];
                foreach ($icons as $icon) {
                    echo "
                    <div class='icon' onclick='openModal(\"{$icon['title']}\", \"{$icon['info']}\", \"{$icon['number']}\")'>
                        <img src='{$icon['src']}' alt='{$icon['alt']}'>
                        <p>{$icon['title']}</p>
                    </div>";
                }
                ?>
            </div>
            <button onclick="openProfileSettings()" class="profile-settings-btn">Profile Settings</button>
            <button onclick="playLogoutVideo()" class="logout-btn">Logout</button>
        </div>
    </div>

    <!-- Profile Settings Modal -->
    <div class="modal" id="profile-settings-modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeProfileSettings()">&times;</span>
        <h2>Profile Settings</h2>
        <form action="update_profile.php" method="POST">
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo isset($_SESSION['name']) ? $_SESSION['name'] : ''; ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" required>

            <label for="contact">Contact:</label>
            <input type="tel" id="contact" name="contact" value="<?php echo isset($_SESSION['contact']) ? $_SESSION['contact'] : ''; ?>" required>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" value="<?php echo isset($_SESSION['address']) ? $_SESSION['address'] : ''; ?>" required>

            <button type="submit">Save Changes</button>
        </form>
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
            <p id="modal-info"></p>
            <p><strong>Hotline:</strong> <span id="modal-number"></span></p>
            <button onclick="alert('Calling Now...')">Call Now</button>
            <button onclick="closeModal()">Close</button>
        </div>
    </div>

    <script>
        function openModal(title, info, number) {
            document.getElementById("modal-title").innerText = title;
            document.getElementById("modal-info").innerText = info;
            document.getElementById("modal-number").innerText = number;
            document.getElementById("info-modal").style.display = "flex";
        }

        function closeModal() {
            document.getElementById("info-modal").style.display = "none";
        }

        function openProfileSettings() {
            document.getElementById("profile-settings-modal").style.display = "flex";
        }

        function closeProfileSettings() {
            document.getElementById("profile-settings-modal").style.display = "none";
        }

        // Close modals if clicking outside of them
        window.onclick = function(event) {
            const infoModal = document.getElementById("info-modal");
            const profileModal = document.getElementById("profile-settings-modal");
            if (event.target === infoModal) {
                infoModal.style.display = "none";
            } else if (event.target === profileModal) {
                profileModal.style.display = "none";
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

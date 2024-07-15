<?php
session_start();
session_destroy();
header("Location: login.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout | Task Manager</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Link to external CSS file -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #00c6ff, #0072ff); /* Background gradient */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .logout-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            padding: 40px;
            text-align: center;
            animation: fadeIn 1s ease-in-out; /* Fade-in animation */
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            margin-bottom: 20px;
        }

        .login-link a {
            color: #0072ff;
            text-decoration: none;
            transition: color 0.3s;
        }

        .login-link a:hover {
            color: #005bb5;
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <h2>You have been logged out</h2>
        <div class="login-link">
            <p><a href="login.php">Log in again</a></p>
        </div>
    </div>
    <script src="js/script.js"></script> <!-- Link to external JavaScript file -->
</body>
</html>

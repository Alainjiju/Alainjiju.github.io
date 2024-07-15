<?php
session_start();
require 'database.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check for user in the database
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    // Verify password
    if ($user && password_verify($password, $user['password'])) {
        // Store user information in session and redirect
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: tasks.php");
        exit;
    } else {
        $error_message = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Task Manager</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Link to external CSS file -->
    <style>
        /* General body styling */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #00c6ff, #0072ff); /* Background gradient */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        /* Login container styling */
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            padding: 40px;
            animation: fadeIn 1s ease-in-out; /* Fade-in animation */
        }

        /* Keyframes for fade-in animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-25px); }
            to { opacity: 1; transform: translateY(10px); }
        }

        /* Form heading styling */
        .login-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Input group styling */
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        /* Input field styling */
        .input-group input {
            width: 100%;
            padding: 10px;
            background: #f1f1f1;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        /* Label styling */
        .input-group label {
            position: absolute;
            top: 10px;
            left: 10px;
            color: #999;
            transition: 0.2s;
            pointer-events: none; /* Prevent label from receiving pointer events */
        }

        /* Label transition for focused or filled input */
        .input-group input:focus ~ label,
        .input-group input:not(:placeholder-shown) ~ label {
            top: -10px;
            left: 5px;
            font-size: 12px;
            color: #0072ff;
        }

        /* Input field focus styling */
        .input-group input:focus {
            border-color: #0072ff;
            outline: none; /* Remove default focus outline */
            box-shadow: 0 0 5px rgba(0, 114, 255, 0.5); /* Add custom focus effect */
        }

        /* Error message styling */
        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        /* Remember me checkbox styling */
        .remember {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .remember label {
            margin-left: 5px;
        }

        /* Button styling */
        button {
            width: 100%;
            padding: 10px;
            background: #0072ff;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        /* Button hover effect */
        button:hover {
            background: #005bb5;
        }

        /* Signup link styling */
        .signup-link {
            text-align: center;
            margin-top: 20px;
        }

        .signup-link a {
            color: #0072ff;
            text-decoration: none;
            transition: color 0.3s;
        }

        /* Signup link hover effect */
        .signup-link a:hover {
            color: #005bb5;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h2>Login</h2>
            <!-- Display error message if any -->
            <?php if ($error_message) : ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <!-- Login form -->
            <form action="login.php" method="POST">
                <div class="input-group">
                    <input type="text" name="username" required>
                    <label>Username</label>
                </div>
                <div class="input-group">
                    <input type="password" name="password" required>
                    <label>Password</label>
                </div>
                <button type="submit">Login</button>
                <div class="signup-link">
                    <p>Don't have an account? <a href="register.php">Sign Up</a></p>
                </div>
            </form>
        </div>
    </div>
    <script src="js/script.js"></script> <!-- Link to external JavaScript file -->
</body>
</html>

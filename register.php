<?php
require 'database.php';

// Initialize variables for form input and error messages
$firstname = $lastname = $username = $password = '';
$username_err = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize user input to prevent SQL injection
    $firstname = sanitizeInput($conn, $_POST['firstname']);
    $lastname = sanitizeInput($conn, $_POST['lastname']);
    $username = sanitizeInput($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Validate and insert into database
    $sql_check = "SELECT id FROM users WHERE username=?";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "s", $username);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        $username_err = "Username already exists. Please choose a different username.";
    } else {
        // Prepare and bind parameters to prevent SQL injection
        $sql_insert = "INSERT INTO users (firstname, lastname, username, password) VALUES (?, ?, ?, ?)";
        $stmt_insert = mysqli_prepare($conn, $sql_insert);

        if ($stmt_insert) {
            mysqli_stmt_bind_param($stmt_insert, "ssss", $firstname, $lastname, $username, $password);

            if (mysqli_stmt_execute($stmt_insert)) {
                header("Location: login.php?message=register_complete");
                exit();
            } else {
                echo "Error: " . mysqli_stmt_error($stmt_insert);
            }

            mysqli_stmt_close($stmt_insert);
        } else {
            echo "Error preparing statement: " . mysqli_error($conn);
        }
    }
}

// Function to sanitize user input
function sanitizeInput($conn, $input) {
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($input)));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Task Manager</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .register-form {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 400px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .register-form input[type="text"],
        .register-form input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
        }

        .register-form button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .register-form button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="register-form">
        <h2>Register</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="firstname" placeholder="First Name" value="<?php echo htmlspecialchars($firstname); ?>" required>
            <input type="text" name="lastname" placeholder="Last Name" value="<?php echo htmlspecialchars($lastname); ?>" required>
            <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
        <?php if (!empty($username_err)) : ?>
            <p class="error-message"><?php echo $username_err; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>

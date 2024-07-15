<?php
session_start();
require 'database.php'; // Ensure this file connects to your database

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Assuming form data is POSTed from a form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskName = $_POST['task_name'];
    $taskDateTime = $_POST['task_datetime'];
    $completed = isset($_POST['completed']) ? 1 : 0;
    $userId = $_SESSION['user_id'];

    // Insert query
    $sql = "INSERT INTO tasks (task_name, task_datetime, completed, user_id) 
            VALUES ('$taskName', '$taskDateTime', '$completed', '$userId')";

    if (mysqli_query($conn, $sql)) {
        header("Location: tasks.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
<!-- HTML form for adding tasks -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Task</title>
</head>
<body>
    <form action="add_task.php" method="POST">
        <label>Task Name:</label>
        <input type="text" name="task_name" required><br><br>
        
        <label>Date and Time:</label>
        <input type="datetime-local" name="task_datetime" required><br><br>
        
        <label>Completed:</label>
        <input type="checkbox" name="completed"><br><br>
        
        <button type="submit">Add Task</button>
    </form>
</body>
</html>

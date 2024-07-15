<?php
session_start();
require 'database.php'; // Ensure this file connects to your database

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = $_POST['task_id'];
    $taskName = $_POST['task_name'];
    $taskDateTime = $_POST['task_datetime'];
    $completed = isset($_POST['completed']) ? 1 : 0;
    $userId = $_SESSION['user_id'];

    // Update query
    $sql = "UPDATE tasks 
            SET task_name='$taskName', task_datetime='$taskDateTime', completed='$completed'
            WHERE id='$taskId' AND user_id='$userId'";

    if (mysqli_query($conn, $sql)) {
        header("Location: tasks.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    // Retrieve task information for editing
    if (isset($_GET['id'])) {
        $taskId = $_GET['id'];
        $userId = $_SESSION['user_id'];

        // Fetch task details
        $sql = "SELECT * FROM tasks WHERE id='$taskId' AND user_id='$userId'";
        $result = mysqli_query($conn, $sql);
        $task = mysqli_fetch_assoc($result);
    }
}
?>
<!-- HTML form for editing tasks -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
</head>
<body>
    <form action="edit_task.php" method="POST">
        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
        
        <label>Task Name:</label>
        <input type="text" name="task_name" value="<?php echo $task['task_name']; ?>" required><br><br>
        
        <label>Date and Time:</label>
        <input type="datetime-local" name="task_datetime" value="<?php echo $task['task_datetime']; ?>" required><br><br>
        
        <label>Completed:</label>
        <input type="checkbox" name="completed" <?php if ($task['completed'] == 1) echo "checked"; ?>><br><br>
        
        <button type="submit">Save Changes</button>
    </form>
</body>
</html>

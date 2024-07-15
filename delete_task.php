<?php
session_start();
require 'database.php'; // Ensure this file connects to your database

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'])) {
    $taskId = $_POST['task_id'];
    $userId = $_SESSION['user_id'];

    // Delete query
    $sql = "DELETE FROM tasks WHERE id='$taskId' AND user_id='$userId'";

    if (mysqli_query($conn, $sql)) {
        header("Location: tasks.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
} elseif (isset($_GET['id'])) {
    // Display confirmation form for deleting task
    $taskId = $_GET['id'];
    $userId = $_SESSION['user_id'];

    // Fetch task details for confirmation
    $sql = "SELECT * FROM tasks WHERE id='$taskId' AND user_id='$userId'";
    $result = mysqli_query($conn, $sql);
    $task = mysqli_fetch_assoc($result);
}
?>
<!-- HTML form for confirming task deletion -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Task</title>
</head>
<body>
    <h2>Delete Task</h2>
    <p>Are you sure you want to delete the task "<?php echo $task['task_name']; ?>"?</p>
    <form action="delete_task.php" method="POST">
        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
        <button type="submit">Yes, Delete Task</button>
        <a href="tasks.php">Cancel</a>
    </form>
</body>
</html>

<?php
session_start();
require 'database.php';

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Function to sanitize user input
function sanitizeInput($conn, $input) {
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($input)));
}

// Insert new task into database
if (isset($_POST['add_task'])) {
    $task_name = sanitizeInput($conn, $_POST['task_name']);
    $task_datetime = sanitizeInput($conn, $_POST['task_datetime']);

    $user_id = $_SESSION['user_id'];
    $sql_insert = "INSERT INTO tasks (user_id, task_name, task_datetime, completed) VALUES ('$user_id', '$task_name', '$task_datetime', 0)";

    if (mysqli_query($conn, $sql_insert)) {
        header("Location: tasks.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Update task completion status
if (isset($_POST['update_completion'])) {
    $task_id = $_POST['task_id'];
    $completed = isset($_POST['completed']) ? 1 : 0;

    $sql_update = "UPDATE tasks SET completed='$completed' WHERE id='$task_id'";
    if (mysqli_query($conn, $sql_update)) {
        header("Location: tasks.php");
        exit();
    } else {
        echo "Error updating task: " . mysqli_error($conn);
    }
}

// Fetch tasks for the logged-in user
$user_id = $_SESSION['user_id'];
$sql_fetch = "SELECT * FROM tasks WHERE user_id='$user_id'";
$result = mysqli_query($conn, $sql_fetch);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
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

        .task-manager {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 600px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .task-form {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .task-form input[type="text"],
        .task-form input[type="datetime-local"] {
            padding: 10px;
            width: 60%;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
        }

        .task-form button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .task-form button:hover {
            background-color: #0056b3;
        }

        .task-list {
            list-style-type: none;
            padding: 0;
        }

        .task-item {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.3s;
        }

        .task-item:hover {
            background-color: #f0f0f0;
        }

        .task-item.completed {
            background-color: #e0e0e0;
            text-decoration: line-through;
            opacity: 0.8;
        }

        .task-item .task-details {
            flex-grow: 1;
        }

        .task-item .task-actions {
            display: flex;
            gap: 10px;
        }

        .task-item .task-actions button {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .task-item .task-actions button:hover {
            background-color: #0056b3;
        }

        .task-item .task-actions input[type="checkbox"] {
            transform: scale(1.5); /* Adjust checkbox size */
        }
    </style>
</head>
<body>
    <div class="task-manager">
        <h2>Task Manager</h2>
        <form class="task-form" method="POST" action="tasks.php">
            <input type="text" name="task_name" placeholder="Enter task name" required>
            <input type="datetime-local" name="task_datetime" required>
            <button type="submit" name="add_task">Add Task</button>
        </form>
        <ul class="task-list">
            <?php while ($task = mysqli_fetch_assoc($result)) : ?>
                <li class="task-item <?php echo isset($task['completed']) && $task['completed'] == 1 ? 'completed' : ''; ?>">
                    <div class="task-details">
                        <span><?php echo isset($task['task_name']) ? htmlspecialchars($task['task_name']) : ''; ?></span>
                        <span><?php echo isset($task['task_datetime']) ? date('M d, Y h:i A', strtotime($task['task_datetime'])) : 'Jan 01, 1970 01:00 AM'; ?></span>
                    </div>
                    <div class="task-actions">
                        <form method="POST" action="tasks.php">
                            <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                            <input type="checkbox" name="completed" onchange="this.form.submit()" <?php echo isset($task['completed']) && $task['completed'] == 1 ? 'checked' : ''; ?>>
                            <input type="hidden" name="update_completion" value="1">
                        </form>
                        <form method="POST" action="delete_task.php">
                            <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                            <button type="submit" name="delete_task">Delete</button>
                        </form>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
        <div class="logout-link">
            <p><a href="logout.php">Logout</a></p>
        </div>
    </div>
</body>
</html>

<?php
include("dbconn.php");

// Check if deleteid is set in the URL
if (isset($_GET['deleteid'])) {
    $deletedTaskId = $_GET['deleteid'];

    // Sanitize the input (optional, but recommended)
    $deletedTaskId = mysqli_real_escape_string($conn, $deletedTaskId);

    // Fetch the project_id associated with the task (assuming your tasksdetails table has a project_id column)
    $sql = "SELECT project_id FROM tasksdetails WHERE task_id = $deletedTaskId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $projectId = $row['project_id'];

        // Delete the task from the database
        $deleteQuery = "DELETE FROM tasksdetails WHERE task_id = $deletedTaskId";
        $deleteResult = mysqli_query($conn, $deleteQuery);

        if ($deleteResult) {
            // Redirect to the task list page after successful deletion
            header("Location: tempaddtask.php?project_id=$projectId");
            exit();
        } else {
            // Handle the error (you might want to customize this part based on your needs)
            echo "Error deleting task: " . mysqli_error($conn);
        }
    } else {
        // Handle the case where the task_id is not found in the database
        echo "Task not found.";
    }
} else {
    // Handle the case where deleteid is not set in the URL
    echo "Invalid request.";
}
?>

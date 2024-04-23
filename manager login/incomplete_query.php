<?php
require_once 'dbconn.php';
if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];
    
    // Update the status of the task to "Incomplete"
    $conn->query("UPDATE `todo_manager` SET `status` = 'pending' WHERE `task_id` = '$task_id'");
    header('location:mytask.php');
}
?>

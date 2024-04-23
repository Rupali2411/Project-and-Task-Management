<?php
require_once 'dbconn.php';
if (isset($_POST['add'])) {
    if ($_POST['task'] != "") {
        $task = $_POST['task'];
        
        // Remove the task_id from the column list and let the database handle it
        $conn->query("INSERT INTO `todo` (`task`, `status`) VALUES ('$task', '')");
        header('location:mytask.php');
    }
}
?>

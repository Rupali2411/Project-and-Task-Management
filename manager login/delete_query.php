<?php
	require_once 'dbconn.php';
	
	if($_GET['task_id']){
		$task_id = $_GET['task_id'];
		
		$conn->query("DELETE FROM `todo_manager` WHERE `task_id` = $task_id") or die(mysqli_errno($conn));
		header("location: mytask.php");
	}	
?>
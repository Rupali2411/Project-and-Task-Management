<?php
session_start();
include("dbconn.php");

$taskId = $_GET['task_id'];

// Fetch messages for the given task
$query = "SELECT message_content, sender_id, timestamp FROM chat_messages WHERE task_id = '$taskId' ORDER BY timestamp";
$result = mysqli_query($conn, $query);
$messages = [];
while ($row = mysqli_fetch_assoc($result)) {
    $messages[] = $row;
}

// Return messages as JSON
header('Content-Type: application/json');
echo json_encode($messages);
?>



<?php
// Include your database connection code here

include('dbconn.php');
// Fetch new messages from the database
$query = "SELECT chat_messages.task_id, chat_messages.message_content, tasks.project_name, tasks.task_title, tasks.assignee_name FROM chat_messages
JOIN tasks ON chat_messages.task_id = tasks.task_id
WHERE chat_messages.receiver_id = '{$_SESSION['emp_id']}' AND chat_messages.timestamp > (SELECT last_checked_timestamp FROM employee_chat_status WHERE employee_id = '{$_SESSION['emp_id']}')";

$result = mysqli_query($conn, $query);

$newMessages = array();

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Add each new message to the array
        $message = $row['project_name'] . ' - ' . $row['task_title'] . ': ' . $row['message_content'];
        $task_id = $row['task_id'];
        $assignee = $row['assignee_name'];
        $newMessages[] = array('message' => $message, 'task_id' => $task_id, 'assignee' => $assignee);
    }

    // Update last_checked_timestamp for the employee
    $updateTimestampQuery = "UPDATE employee_chat_status SET last_checked_timestamp = NOW() WHERE employee_id = '{$_SESSION['emp_id']}'";
    mysqli_query($conn, $updateTimestampQuery);
}

// Return the new messages as JSON
echo json_encode($newMessages);

// Close the database connection
mysqli_close($conn);
?>

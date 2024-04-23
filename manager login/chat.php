<?php
include('dbconn.php');
// Check if the request is for sending a message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'], $_POST['message'])) {
    $taskId = $_POST['task_id'];
    $message = $_POST['message'];
    // Store the message in the database
    // Prepare your SQL query to insert the message into the messages table
    $senderId = $_SESSION['manager_id']; // Example: Manager's ID
    $timestamp = date('Y-m-d H:i:s'); // Current timestamp
    $query = "INSERT INTO messages (task_id, sender_id, message, timestamp) VALUES ($taskId, $senderId, '$message', '$timestamp')";
    $success = mysqli_query($conn, $query);

    // Return success or error response
    echo json_encode(array('success' => $success));
    exit; // Stop further execution
}

// If no valid request is received, return an error response
echo json_encode(array('error' => 'Invalid request'));


// Check if the task ID is provided in the request
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['task_id'])) {
    $taskId = $_GET['task_id'];
    // Fetch messages from the database for the given task ID
    // Execute your SQL query to retrieve messages for the task from the messages table
    // Store the fetched messages in an array
    $messages = array();

    // Example query to fetch messages for the given task ID
    $query = "SELECT sender_id, message, timestamp FROM messages WHERE task_id = $taskId ORDER BY timestamp ASC";
    $result = mysqli_query($conn, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $sender = ($row['sender_id'] == $_SESSION['manager_id']) ? 'Manager' : 'Employee';
            $messages[] = array('timestamp' => $row['timestamp'], 'sender' => $sender, 'message' => $row['message']);
        }
        mysqli_free_result($result);
    }

    // Send the messages as JSON
    echo json_encode($messages);
    exit; // Stop further execution
}


?>

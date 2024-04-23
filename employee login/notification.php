<?php
// Establish database connection (assuming you have already connected)
define('HOST','localhost');
define('USERNAME','root');
define('PWD','root'); // Specify the correct password here
define('DB','studentinfo');

$connection = mysqli_connect(HOST, USERNAME, PWD, DB);

if($connection){
    // Connection successful
    // echo "Connected successfully!";
} else {
    // Connection failed
    echo "Connect problem: " . mysqli_connect_error();
}

// Query to retrieve the most recent message along with task title and sender for the logged employee
$query = "SELECT cm.message_content, t.task_title, cm.sender_id
          FROM chat_messages cm
          JOIN tasksdetails t ON cm.task_id = t.task_id
          WHERE cm.receiver_id = '$_SESSION[emp_id]'
          ORDER BY cm.timestamp DESC
          LIMIT 1"; // Fetch only the most recent message with task title and sender

$result = mysqli_query($connection, $query);

if ($result && mysqli_num_rows($result) > 0) {
    // Display notification message
    $row = mysqli_fetch_assoc($result);
    $message_content = $row['message_content'];
    $task_title = $row['task_title'];
    $sender_id = $row['sender_id'];
    
    echo "You have a new message: $message_content (Task: $task_title, Sender: $sender_id)";
} else {
    echo "No new messages.";
}
?>

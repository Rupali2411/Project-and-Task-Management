<?php
// Include your database connection file
include('dbconn.php');

// Check if the required parameters are present
if (
    isset($_POST['task_id']) &&
    isset($_POST['title']) &&
    isset($_POST['details']) &&
    isset($_POST['assignee']) &&
    isset($_POST['dueDate']) &&
    isset($_POST['priority'])
) {
    // Sanitize and validate input data (you may need to enhance validation)
    $taskId = mysqli_real_escape_string($conn, $_POST['task_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $details = mysqli_real_escape_string($conn, $_POST['details']);
    $assignee = mysqli_real_escape_string($conn, $_POST['assignee']);
    $dueDate = mysqli_real_escape_string($conn, $_POST['dueDate']);
    $priority = mysqli_real_escape_string($conn, $_POST['priority']);

    // Update the task in the database
    $query = "UPDATE tasksdetails
              SET task_title = '$title',
                  task_details = '$details',
                  assignee = '$assignee',
                  due_date = '$dueDate',
                  priority = '$priority'
              WHERE task_id = $taskId";

    if (mysqli_query($conn, $query)) {
        // The update was successful
        $response = array('status' => 'success');
        echo json_encode($response);
    } else {
        // The update failed
        $error_message = mysqli_error($conn);
        $response = array('status' => 'error', 'message' => 'Update failed: ' . ($error_message ? $error_message : 'No error message available') . ' Query: ' . $query);
        echo json_encode($response);
    }
} else {
    // Invalid parameters
    $response = array('status' => 'error', 'message' => 'Invalid parameters');
    echo json_encode($response);
}
// Close the database connection
mysqli_close($conn);
?>
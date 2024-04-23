<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Notification</title>
    <!-- Include Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<body>

<?php
// Manually specify the manager ID
$managerId = "TYBCA002"; // Replace with the actual manager ID

// Manually specify the project ID
$projectId = "67"; // Replace with the actual project ID

// Include your database connection file
include 'dbconn.php'; // Adjust the path as needed

// Query to count overdue tasks for the manager's project
$overdueQuery = "SELECT COUNT(*) AS overdueCount 
                 FROM tasksdetails 
                 WHERE project_id = '$projectId' 
                 AND due_date < CURDATE() 
                 AND status != 'completed'";

// Execute the query
$result = mysqli_query($conn, $overdueQuery);

// Check if the query executed successfully
if ($result) {
    // Fetch the result row
    $row = mysqli_fetch_assoc($result);
    
    // Get the count of overdue tasks
    $overdueCount = $row['overdueCount'];
    
    // Define the threshold for displaying the notification (e.g., 5)
    $threshold = 5;
    
    // Determine whether to show the notification based on the overdue count
    $showNotification = ($overdueCount > $threshold) ? true : false;

    // Send toast notification if there are overdue tasks
    if ($showNotification) {
        echo "<script>
                toastr.error('There are overdue tasks for this project!');
              </script>";
    }
} else {
    // Handle database error
    echo json_encode(array("error" => "Database error: " . mysqli_error($conn)));
}

// Close the database connection
mysqli_close($conn);
?>

<!-- Include jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Include Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

</body>
</html>

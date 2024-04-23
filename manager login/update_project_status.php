<?php include('dbconn.php')?>
<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the status and projectId are set in the POST data
    if (isset($_POST['status']) && isset($_POST['projectId'])) {
        // Get the status and projectId from the POST data
        $status = $_POST['status'];
        $projectId = $_POST['projectId'];

        // Perform any necessary validation on the status and projectId

        // Perform the database update
        $query = "UPDATE projectsnew SET status = '$status' WHERE project_id = '$projectId'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Update successful
            echo "Status updated successfully.";
            // Redirect to tempaddtask.php with project_id query parameter
            header('Location: tempaddtask.php?project_id=' . urlencode($projectId));
            exit;
        } else {
            // Error occurred during update
            echo "Error updating status: " . mysqli_error($conn);
        }
    } else {
        // Missing parameters
        echo "Error: Missing parameters.";
    }
} else {
    // Form not submitted via POST
    echo "Error: Form not submitted.";
}
?>

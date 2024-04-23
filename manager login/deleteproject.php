<?php
include("dbconn.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['deleteproid'])) {
    $projectId = $_GET['deleteproid'];

    // Delete the project from the database
    $deleteQuery = "DELETE FROM projectsnew WHERE project_id = $projectId";
    $deleteResult = mysqli_query($conn, $deleteQuery);

    if ($deleteResult) {
        // Redirect to the project list page after successful deletion
        header("Location: projectlist.php");
        exit();
    } else {
        // Handle the error (you might want to customize this part based on your needs)
        echo "Error deleting project: " . mysqli_error($conn);
    }
} else {
    // Handle the case where project_id is not set in the URL
    echo "Invalid request.";
}
?>

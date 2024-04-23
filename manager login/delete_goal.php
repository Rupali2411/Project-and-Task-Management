<?php
// Include the database connection file
include("dbconn.php");

// Check if goal_id is provided
if(isset($_GET['goal_id'])) {
    // Sanitize the goal_id to prevent SQL injection
    $goalId = mysqli_real_escape_string($conn, $_GET['goal_id']);

    // Construct the DELETE query for additional_sections table
    $deleteAdditionalSectionsQuery = "DELETE FROM additional_sections WHERE goal_id = '$goalId'";

    // Execute the DELETE query for additional_sections table
    if(mysqli_query($conn, $deleteAdditionalSectionsQuery)) {
        // Now that associated additional sections are deleted, proceed to delete the goal from goals table
        // Construct the DELETE query for goals table
        $deleteGoalQuery = "DELETE FROM goals WHERE goal_id = '$goalId'";
        
        // Execute the DELETE query for goals table
        if(mysqli_query($conn, $deleteGoalQuery)) {
            // Goal and associated additional sections deleted successfully
            echo "Goal and associated additional sections deleted successfully.";
            // Redirect to goal.php
            header("Location: goal.php");
            exit(); // Ensure no further code execution after redirection
        } else {
            // Error occurred while deleting the goal
            echo "Error: Unable to delete the goal. Please try again later.";
        }
    } else {
        // Error occurred while deleting associated additional sections
        echo "Error: Unable to delete associated additional sections. Please try again later.";
    }
} else {
    // Goal ID not provided
    echo "Error: Goal ID not provided.";
}
?>

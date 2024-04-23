<?php
// Include the database connection file
include("dbconn.php");

// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the necessary data is present
    if (isset($_POST['selected_team']) && isset($_POST['goaltitle']) && isset($_POST['summary']) && isset($_POST['accomplishment']) && isset($_POST['nextSteps']) && isset($_POST['additional_sections'])) {
        // Get form data
        $teamId = $_POST['selected_team'];
        $goalTitle = $_POST['goaltitle'];
        $summary = $_POST['summary'];
        $accomplishment = $_POST['accomplishment'];
        $nextSteps = $_POST['nextSteps'];

        // Decode additional_sections JSON string
        $additionalSections = json_decode($_POST['additional_sections'], true);

        // Get the manager_id from the session
        if (isset($_SESSION['manager_id'])) {
            $managerId = $_SESSION['manager_id'];

            // Insert data into the goals table
            $insertGoalQuery = "INSERT INTO goals (team_id, goal_title, summary, accomplishment, next_steps, manager_id) 
                                VALUES ('$teamId', '$goalTitle', '$summary', '$accomplishment', '$nextSteps', '$managerId')";
            $result = mysqli_query($conn, $insertGoalQuery);

            // Check if the goal insertion was successful
            if ($result) {
                // Retrieve the auto-generated goal_id
                $goalId = mysqli_insert_id($conn);

                // Insert data into the additional_sections table for each section
                foreach ($additionalSections as $section) {
                    $sectionTitle = $section['title'];
                    $sectionContent = $section['content'];

                    $insertSectionQuery = "INSERT INTO additional_sections (goal_id, section_title, section_content) 
                                           VALUES ('$goalId', '$sectionTitle', '$sectionContent')";
                    $sectionResult = mysqli_query($conn, $insertSectionQuery);

                    // Check if the section insertion was successful
                    if (!$sectionResult) {
                        echo "Error inserting additional section: " . mysqli_error($conn);
                        // Optionally, you can roll back the goal insertion here
                        exit;
                    }
                }

                // Goal insertion and additional section insertion successful
                echo "Goal added for a team successfully!";
            } else {
                echo "Error inserting goal: " . mysqli_error($conn);
            }
        } else {
            echo "Manager ID not found in session!";
        }
    } else {
        echo "Missing form data!";
    }
}
?>

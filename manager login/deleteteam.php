<?php
include("dbconn.php");

$teamId = isset($_GET['deleteid']) ? $_GET['deleteid'] : null;

// Check if $teamId is set and not empty before proceeding
if ($teamId !== null && !empty($teamId)) {
    // Check if there are related records in projectsnew
    $checkQuery = "SELECT COUNT(*) as count FROM projectsnew WHERE team_id = $teamId";
    $result = $conn->query($checkQuery);

    if ($result) {
        $row = $result->fetch_assoc();
        $rowCount = $row['count'];

        // If there are related records, update the team_id in projectsnew
        if ($rowCount > 0) {
            echo "Cannot delete team. Related records found in projectsnew table.<br>";

            // Display or process the related records
            $relatedRecordsQuery = "SELECT * FROM projectsnew WHERE team_id = $teamId";
            $relatedRecordsResult = $conn->query($relatedRecordsQuery);

            if ($relatedRecordsResult) {
                echo "Related records in projectsnew table:<br>";

                while ($relatedRow = $relatedRecordsResult->fetch_assoc()) {
                    echo "Project ID: " . $relatedRow['project_id'] . "<br>";

                    // Update the team_id to NULL (assuming your foreign key allows NULL)
                    $updateRelatedQuery = "UPDATE projectsnew SET team_id = NULL WHERE project_id = '" . $relatedRow['project_id'] . "'";

                    if ($conn->query($updateRelatedQuery) === TRUE) {
                        echo "Related record updated successfully.<br>";
                    } else {
                        echo "Error updating related record: " . $conn->error . "<br>";
                    }
                }
            } else {
                echo "Error fetching related records: " . $conn->error;
            }
        }

        // Check for related records in employees_login
        $checkEmployeesQuery = "SELECT emp_ID FROM employees_login WHERE team_id = $teamId";
        $resultEmployees = $conn->query($checkEmployeesQuery);

        if ($resultEmployees) {
            echo "Related records found in employees_login table. Updating team_id to NULL for related records.<br>";

            // Display or process the related records in employees_login
            while ($row = $resultEmployees->fetch_assoc()) {
                echo "Employee ID: " . $row['emp_ID'] . "<br>";

                // Update the team_id to NULL
                $updateEmployeeQuery = "UPDATE employees_login SET team_id = NULL WHERE emp_ID = '" . $row['emp_ID'] . "'";

                if ($conn->query($updateEmployeeQuery) === TRUE) {
                    echo "Related record in employees_login updated successfully.<br>";
                } else {
                    echo "Error updating related record in employees_login: " . $conn->error . "<br>";
                }
            }
        }
        // Check for related records in employee_teams
$checkEmployeeTeamsQuery = "SELECT emp_ID FROM employee_teams WHERE team_id = $teamId";
$resultEmployeeTeams = $conn->query($checkEmployeeTeamsQuery);

if ($resultEmployeeTeams) {
    echo "Related records found in employee_teams table. Deleting related records.<br>";

    // Display or process the related records in employee_teams
    while ($row = $resultEmployeeTeams->fetch_assoc()) {
        echo "Employee ID: " . $row['emp_ID'] . "<br>";

        // Delete the related record from employee_teams
        $deleteEmployeeTeamQuery = "DELETE FROM employee_teams WHERE emp_ID = '" . $row['emp_ID'] . "' AND team_id = $teamId";

        if ($conn->query($deleteEmployeeTeamQuery) === TRUE) {
            echo "Related record in employee_teams deleted successfully.<br>";
        } else {
            echo "Error deleting related record in employee_teams: " . $conn->error . "<br>";
        }
    }
}
 
        // Check for related records in goals
        $checkGoalsQuery = "SELECT COUNT(*) as count FROM goals WHERE team_id = $teamId";
        $resultGoals = $conn->query($checkGoalsQuery);

        if ($resultGoals) {
            $row = $resultGoals->fetch_assoc();
            $rowCount = $row['count'];

            // If there are related records in goals, delete them
            if ($rowCount > 0) {
                echo "Related records found in goals table. Deleting related goals.<br>";

                // Delete related goals
                $deleteGoalsQuery = "DELETE FROM goals WHERE team_id = $teamId";

                if ($conn->query($deleteGoalsQuery) === TRUE) {
                    echo "Related goals deleted successfully.<br>";
                } else {
                    echo "Error deleting related goals: " . $conn->error . "<br>";
                }
            }
        }

        // After updating related records and deleting related goals, attempt to delete the team from teams
        $deleteQuery = "DELETE FROM teams WHERE team_id = $teamId";
        if ($conn->query($deleteQuery) === TRUE) {
            echo "Team deleted successfully from teams table";

            header("Location: team.php");
        } else {
            echo "Error deleting team from teams table: " . $conn->error;
        }
    } else {
        echo "Error checking for related records: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    echo "Error: Missing or invalid team ID.";
}
?>
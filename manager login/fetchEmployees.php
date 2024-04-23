<?php
// getemployeename.php

include("dbconn.php");

if (isset($_GET['emp_id'])) {
    $empId = $_GET['emp_id'];

    // Function to get employee name by ID
    function getEmployeeNameById($emp_id, $conn) {
        $sql = "SELECT emp_name FROM employees_login WHERE emp_id = ?";
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bind_param("s", $emp_id);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the row
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['emp_name'];
        } else {
            return "Unknown Assignee";
        }

        // Close the statement
        $stmt->close();
    }

    // Fetch and return the employee name
    $employeeName = getEmployeeNameById($empId, $conn);
    echo $employeeName;
} else {
    echo "Employee ID not provided.";
}
?>

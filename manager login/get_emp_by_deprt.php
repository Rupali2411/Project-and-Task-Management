<?php
// Include your database connection file
include("dbconn.php");

// Get the selected department from the AJAX request
$selectedDepartment = isset($_POST['department']) ? $_POST['department'] : '';

// Fetch employees based on the selected department
$employeeQuery = "SELECT emp_id, emp_name FROM employees_login WHERE department = '$selectedDepartment'";
$employeeResult = mysqli_query($conn, $employeeQuery);

// Generate HTML content with checkboxes for the employees
$htmlContent = '';

while ($employeeRow = mysqli_fetch_assoc($employeeResult)) {
    $htmlContent .= '<div class="form-check">';
    $htmlContent .= '<input class="form-check-input" type="checkbox" name="selected_employees[]" value="' . $employeeRow['emp_id'] . '" id="employee_' . $employeeRow['emp_id'] . '">';
    $htmlContent .= '<label class="form-check-label" for="employee_' . $employeeRow['emp_id'] . '">' . $employeeRow['emp_name'] . '</label>';
    $htmlContent .= '</div>';
}

// Return the generated HTML content
echo $htmlContent;

// Close the database connection
mysqli_close($conn);
?>

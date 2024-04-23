<?php
include("dbconn.php");

// Start the session
session_start();

if (isset($_POST['submit'])) {
    $empId = mysqli_real_escape_string($conn, $_POST['emp_id']);

    // Query to check if the Emp ID already exists
    $query = "SELECT COUNT(*) as count FROM employees_login WHERE emp_id = '$empId'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $count = $row['count'];

        if ($count > 0) {
            // Employee with the same Emp ID already exists
            // Handle the error or redirect back to the form with an error message
            echo "<script>alert('Employee already exists. Please check Emp ID.');</script>";
            echo "<script>window.location.href = 'new_register_form.php';</script>";
            exit();
        }
    } else {
        // Handle database query error
        echo "Error: " . mysqli_error($conn);
        exit();
    }
    
    // Now check for empty fields
    $empName = mysqli_real_escape_string($conn, $_POST['emp_name']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $emailId = mysqli_real_escape_string($conn, $_POST['email_id']);
    $department = isset($_POST['department']) ? mysqli_real_escape_string($conn, $_POST['department']) : null;
    $empPassword = password_hash($_POST['emp_password'], PASSWORD_DEFAULT);

    // Check for empty fields
    if (empty($empId) || empty($empName) || empty($contact) || empty($emailId) || empty($department) || empty($_POST['emp_password'])) {
        echo "<script>alert('Please fill in all the required details.');</script>";
        echo '<script>window.location.href = "new_register_form.php";</script>';
        exit();
    }

    // Proceed with the insertion into the database
    $query = "INSERT INTO employees_login (emp_id, emp_name, contact, email_id, department, emp_password)
              VALUES ('$empId', '$empName', '$contact', '$emailId', '$department', '$empPassword')";

    if (mysqli_query($conn, $query)) {
        // Registration successful, set session variable
        $_SESSION['emp_id'] = $empId;

        // Redirect to dash.php
        echo "<script>alert('Congratulations! Registered successfully.');</script>";
        echo '<script>window.location.href = "login.php";</script>';
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid parameters!";
}
?>

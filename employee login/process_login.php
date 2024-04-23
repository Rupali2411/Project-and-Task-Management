<?php
session_start();
include("dbconn.php");

if (isset($_POST['login'])) {
    $empId = mysqli_real_escape_string($conn, $_POST['emp_id']);
    $empPassword = mysqli_real_escape_string($conn, $_POST['emp_password']);

    $query = "SELECT * FROM employees_login WHERE emp_id = '$empId'";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($empPassword, $row['emp_password'])) {
            // Password is correct, set session and redirect
            $_SESSION['emp_id'] = $empId;
            header("Location: empdash.php");
            exit;
        } else {
            // Incorrect password, show a pop-up
            echo '<script>alert("Invalid password. Please try again.");</script>';
            
        }
    } else {
        // Employee ID not found, show a pop-up
        echo '<script>alert("Employee ID not found. Please check your ID or register if you have not.");</script>';
    }
}
?>




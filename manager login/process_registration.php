<?php
include("dbconn.php");

if (isset($_POST['submit'])) {
    $managerId = mysqli_real_escape_string($conn, $_POST['manager_id']);

    // Check if the Manager ID already exists
    $query = "SELECT COUNT(*) as count FROM managers_login WHERE manager_id = '$managerId'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $count = $row['count'];

        if ($count > 0) {
            echo "<script>alert('Manager already exists with this ID. Please check your ID.');</script>";
            echo "<script>window.location.href = 'manager_register_form.php';</script>";
            exit();
        }
    } else {
        echo "Error: " . mysqli_error($conn);
        exit();
    }
    // Now check for empty fields
    $managerName = mysqli_real_escape_string($conn, $_POST['manager_name']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $emailId = mysqli_real_escape_string($conn, $_POST['email_id']);
    $empPassword = password_hash($_POST['manager_password'], PASSWORD_DEFAULT);

    if (empty($managerId) || empty($managerName) || empty($contact) || empty($emailId) || empty($_POST['manager_password'])) {
        echo "<script>alert('Please fill in all the required details.');</script>";
        echo '<script>window.location.href = "manager_register_form.php";</script>';
        exit();
    }

    // Proceed with the insertion into the database
    $query = "INSERT INTO managers_login (manager_id, manager_name, contact, email_id, manager_password)
              VALUES ('$managerId', '$managerName', '$contact', '$emailId', '$empPassword')";

    if (mysqli_query($conn, $query)) {
        // Registration successful, redirect to dash.php
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid parameters!";
}
?>
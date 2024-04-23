<?php
    $servername="localhost";
    $username="root";
    $password="root";
    $dbname="studentinfo";

    $conn= mysqli_connect($servername, $username, $password, $dbname);
    if($conn)
    {
        //echo "Connection successful";
    }
    else
    {
        echo "Connection not successful".mysqli_connect_error();
    }
     // Close the database connection
    //mysqli_close($conn);
?>



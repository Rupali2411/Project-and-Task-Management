<?php include("dbconn.php"); ?>
<?php
// Start the session
session_start();
include("dbconn.php");

// Assume emp_id is passed through a session variable after login
if (isset($_SESSION['emp_id'])) {
    $empId = $_SESSION['emp_id'];

    // Query to fetch employee details
    $query = "SELECT * FROM employees_login WHERE emp_id = '$empId'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $employee = mysqli_fetch_assoc($result);
    } else {
        echo "Employee not found";
        exit;
    }
} else {
    echo "Session not started or emp_id not set.";
    exit;
}
?>   
           

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/boxicons/2.0.7/css/boxicons.min.css">
    <!-- My CSS -->
    <link rel="stylesheet" href="dashstyle.css">

    <title>Project Management</title>
    <style>
          body, h1, h2, p, ul, li, table {
    margin: 0;
    padding: 0;
    color:#fff;
}
h2{
    color: black;

}
.label {
padding: 12px;
}
body{
    color: white;
    background-color: white;
    font-family: Verdana, Geneva, Tahoma, sans-serif;/**/ 
    font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;/**/
    font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;   /* */
    font-family: "Poppins", sans-serif;
    font-size: 17px;
}

 /* SIDEBAR STYLES */
 #sidebar {
  width: 220px;
  background: #fff;
  height: 100%;
  position: fixed;
  overflow-y: auto;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  z-index: 1;
}

#sidebar {
  background: rgba(113,99,186,255);
}
#sidebar .brand {
  padding: 15px;
  margin-bottom: 40px; /* Add margin-bottom to create space */

}

#sidebar .user-box {
  padding: 15px;
  text-align: center;
}

#sidebar ul.side-menu {
  padding-top: 15px;
  list-style: none;
}
/* Sidebar menu hover effect */
#sidebar ul.side-menu li:hover {
  background: #C0C6FD; /* Change the background color on hover */
}

#sidebar ul.side-menu li a:hover {
  color: black /* Change the text color on hover */
}

#sidebar ul.side-menu li {
  padding: 10px 15px;
}

#sidebar ul.side-menu li a {
  text-decoration: none;
  color: #fff;
  display: flex;
  align-items: center;
}

#sidebar ul.side-menu li a i {
  margin-right: 10px;
}


#content {
  margin-left: 250px;
  padding: 50px;
}

.logout {
  color: white;
  text-decoration: none;
  display: flex;
  align-items: center;
}

.logout i {
  margin-right: 5px;
}
.user-box {
          position: fixed;
          top: 10px;
          right: 10px; /* Adjusted to move it to the right */
          margin: 0;
          color: #000;
          z-index: 1000; /* Adjust the z-index as needed */
}
.container1 {
            background-color:white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 0px;
            width: 50%px; 
            margin-left: 250px;
            margin-right: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding-left: 30px;
            padding-top: 20px;
            margin-top: 40px;
            margin-bottom: 40px;
          

}
        
td {
        max-width: 150px; /* Set your desired maximum width */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
}
.container1 {
    position: sticky;
    top: 0;
    background-color: white;
    z-index: 1;
    
}

.table-container {
    max-height: 400px; /* Adjust the max-height as needed */
    overflow-y: auto;

}

.table {
    width: 100%;
    border-collapse: collapse;
    background-color:white;

}    

/* Custom CSS for the dropdown */
.btn-group .dropdown-menu {
    border: 1px solid #ccc; /* Add border to the dropdown */
    border-radius: 0; /* Remove border-radius */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Add a subtle box shadow */
  }

  .btn-group .dropdown-menu a {
    color: #333; /* Change link color */
  }

  .btn-group .dropdown-menu a:hover {
    background-color: #f8f9fa; /* Change background color on hover */
  }
  .goal-details-container *{
        color: black;
    }
.table{
    margin-top:50px;
}

</style>
</head>

<body>
    <!-- SIDEBAR MENU-->
    <section id="sidebar">
                <div class="brand">
                    <span id="text">Employee:</span>
                        <p style="color: white;"><?php echo $employee['emp_name']; ?> </p>
                        <p style="color: white;"> Deprt: <?php echo $employee['department']; ?> </p>
                </div>
                <div class="user-box">
                    <div class="user-info">
                    <span class="user-emailid" style="color:#fff";><?php echo $employee['email_id']; ?></span>                       
                    </div>
                    <a href="login.php" class="logout">
                                <i class='bx bx-log-out'></i>
                        <span class="text">Logout</span>
                    </a>
                </div>

            <ul class="side-menu top">               
                <li>
                    <a href="profile.php">
                        <i class="bx bx-bell"></i>
                        <span class="text">Profile</span>
                    </a>
                </li>
                <li class="">
                    <a href="empdash.php">
                        <i class='bx bxs-dashboard'></i>
                        <span class="text">Dashboard</span>
                    </a>
                </li>

                <li id='inbox-notification'>
                    <a href='gmail.php'>
                        <i class='bx bx-bell'></i>
                        <span class='text'>Inbox</span>
                    </a>
                </li>
               
                <li>
                    <a href="empprolist.php">
                    <i class='bx bxs-briefcase'></i>
                        <span class="text">Projects</span>
                    </a>
                </li>
                <li>
                    <a href="assignedtask.php">
                    <i class='bx bx-task'></i>
                        <span class="text">Assigned Tasks</span>
                    </a>
                </li>
                <li>
                    <a href="goal.php">
                        <i class='bx bx-target-lock'></i>
                        <span class="text">Goal</span>
                    </a>
                </li>
                <li>
                    <a href="portfolio.php">
                        <i class='bx bx-folder'></i>
                        <span class="text">Project Report</span>
                    </a>            
                </li>
                <li>
                    <a href="emp_progress.php">
                    <i class='bx bxs-group'></i>
                        <span class="text">Chat</span>
                    </a>
                </li> 
                
                <li>
                    <a href="mytask.php">
                    <i class='bx bx-list-check'></i>
                        <span class="text">To Do</span>
                    </a>
                </li>
                <li>
                    <a href="team.php">
                    <i class='bx bxs-group'></i>
                        <span class="text">+Team</span>
                    </a>
                </li>               
            </ul>
    </section>
    <!--SIDEMENU-->

    <?php
    // Assume emp_id is passed through a session variable after login
    if (isset($_SESSION['emp_id'])) {
        $empId = $_SESSION['emp_id'];

        // Query to fetch employee details
        $query = "SELECT * FROM employees_login WHERE emp_id = '$empId'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $employee = mysqli_fetch_assoc($result);
    ?>
            <!-- Render employee information -->
            <!-- <div class="container">
                Employee details
                <h3>Employee Details</h3>
                <p>Name: <?php echo $employee['emp_name']; ?></p>
                <p>Email: <?php echo $employee['email_id']; ?></p>
                Additional employee details can be added here -->
           
            <?php

            // Fetch team ID from the employee details
            $teamId = $employee['team_id'];
            // Query to fetch goals associated with the team
            $goalsQuery = "SELECT * FROM goals WHERE team_id = '$teamId'";
            $goalsResult = mysqli_query($conn, $goalsQuery);

            if ($goalsResult && mysqli_num_rows($goalsResult) > 0) {
            ?>
          <div class="container">
                        <h2>Goals</h2>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Goal Title</th>
                                    <th>Summary</th>
                                    <th>Accomplishment</th>
                                    <th>Next Steps</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                    // Loop through each goal
                    while ($goal = mysqli_fetch_assoc($goalsResult)) {
                        echo "<tr>";
                        echo "<td>" . $goal['goal_title'] . "</td>";
                        echo "<td >" . $goal['summary'] . "</td>";
                        echo "<td>" . $goal['accomplishment'] . "</td>";
                        echo "<td>" . $goal['next_steps'] . "</td>";
                        echo "<td>";
                        echo "<button class='btn btn-primary' onclick='toggleGoalDetails(" . $goal['goal_id'] . ")'>View</button>";
                        echo "</td>";
                        echo "</tr>";
                        // Hidden row for displaying goal details
                        echo "<tr id='goalDetails" . $goal['goal_id'] . "' style='display: none;'>";
                        echo "<td colspan='5' >"; // Span the details across all columns
                        echo "<strong>Goal Title:</strong> " . $goal['goal_title'] . "<br>";
                        echo "<strong>Summary:</strong> " . $goal['summary'] . "<br>";
                        echo "<strong>Accomplishment:</strong> " . $goal['accomplishment'] . "<br>";
                        echo "<strong>Next Steps:</strong> " . $goal['next_steps'] . "<br>";

                        // Fetch additional sections for this goal
                        $goalId = $goal['goal_id'];
                        $additionalSectionsQuery = "SELECT * FROM additional_sections WHERE goal_id = $goalId";
                        $additionalSectionsResult = mysqli_query($conn, $additionalSectionsQuery);

                        // Output additional section details
                        while ($section = mysqli_fetch_assoc($additionalSectionsResult)) {
                            echo "<strong>" . $section['section_title'] . ":</strong> " . $section['section_content'] . "<br>";
                        }

                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                            </tbody>
                        </table>
                    </div>
                    </div>
                    <?php
                    } else {
                        // No goals found message
                        ?>
                    <div class="container">
                        <p style="color: #000; padding-top:20px;">No goals assigned for your team.</p>
                    </div>
                    <?php
                    }
                            }
                        }
                    ?>

<script>
    function toggleGoalDetails(goalId) {
        var goalDetailsRow = document.getElementById('goalDetails' + goalId);
        if (goalDetailsRow.style.display === 'none') {
            goalDetailsRow.style.display = 'table-row';
        } else {
            goalDetailsRow.style.display = 'none';
        }
    }
</script>

</body>
</html>

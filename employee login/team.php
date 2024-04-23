<?php 
// Start the session
session_start();
// Include database connection
include("dbconn.php");

// Check if emp_id is set in the session
if (isset($_SESSION['emp_id'])) {
    $empId = $_SESSION['emp_id'];

    // Query to fetch employee details
    $query = "SELECT * FROM employees_login WHERE emp_id = '$empId'";
    $result = mysqli_query($conn, $query);

    // Check if the query executed successfully and returned results
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

// Fetch team details where the current employee is added
$teamQuery = "SELECT t.team_id, t.team_name 
              FROM teams t 
              INNER JOIN employee_teams et ON t.team_id = et.team_id 
              WHERE et.emp_id = '$empId'";
$teamResult = mysqli_query($conn, $teamQuery);

// Array to store team details
$teamDetails = array();

// Check if the query executed successfully and returned results
if ($teamResult && mysqli_num_rows($teamResult) > 0) {
    while ($teamRow = mysqli_fetch_assoc($teamResult)) {
        $teamId = $teamRow['team_id'];
        $teamName = $teamRow['team_name'];
        
        // Fetch project names associated with the team
        $projectQuery = "SELECT project_name FROM projectsnew WHERE team_id = '$teamId'";
        $projectResult = mysqli_query($conn, $projectQuery);
        $projectNames = array();
        while ($projectRow = mysqli_fetch_assoc($projectResult)) {
            $projectNames[] = $projectRow['project_name'];
        }
        
        // Store team details
        $teamDetails[] = array(
            'team_id' => $teamId,
            'team_name' => $teamName,
            'project_names' => $projectNames
        );
    }
} else {
    echo "No team details found for the employee";
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

    <link rel="stylesheet" href="dashstyle.css">

    <title>Project Management</title>
    <style>
          body, h1, h2, p, ul, li, table {
    margin: 0;
    padding: 0;
    color:#fff;
}
h2,h4{
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
 /* Table styles */
 table {
        width: 60%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-left: 350px;
        margin-bottom: 25px;

    }

    th, td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
    }

    /* Responsive table */
    @media screen and (max-width: 600px) {
        table {
            border: 0;
        }
        table thead {
            display: none;
        }
        table tr {
            border-bottom: 2px solid #ddd;
            display: block;
            margin-bottom: 20px;
        }
        table td {
            border-bottom: none;
            display: block;
            text-align: right;
        }
        table td::before {
            content: attr(data-label);
            float: left;
            font-weight: bold;
            text-transform: uppercase;
        }
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
                    <span class="user-emailid" style="color:black";><?php echo $employee['email_id']; ?></span>                       
                    </div>
                    <a href="login.php" class="logout">
                                <i class='bx bx-log-out' style="color:black";></i>
                        <span class="text" style="color:black"; >Logout</span>
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
    
    <div> </div><br><br><div>

            <?php foreach ($teamDetails as $team): ?>
        <h4 style="margin-left: 350px; margin-bottom:30px;">Team <?php echo $team['team_name']; ?> Members</h4>
        <table style="color: #000;">
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
                    <th>Department</th>
                    <th>Project Name</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query to retrieve team members and their associated project names
                $query = "SELECT employees_login.emp_id, employees_login.emp_name, employees_login.department, projectsnew.project_name
                        FROM employees_login
                        INNER JOIN employee_teams ON employees_login.emp_id = employee_teams.emp_id
                        INNER JOIN teams ON employee_teams.team_id = teams.team_id
                        INNER JOIN projectsnew ON teams.team_id = projectsnew.team_id
                        WHERE teams.team_id = " . $team['team_id'];

                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['emp_id'] . "</td>";
                        echo "<td>" . $row['emp_name'] . "</td>";
                        echo "<td>" . $row['department'] . "</td>";
                        echo "<td>" . $row['project_name'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No team members found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    <?php endforeach; ?>

</body>
</html>

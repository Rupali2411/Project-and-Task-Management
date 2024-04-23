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
        <title>Project Management</title>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

 <style>

body, h1, h2, h3, p, ul, li, table {
  margin: 0;
  padding: 0;
  color:#fff;
}

body{
    color: white;
    background-color: #ebe9eb;
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
  color: #333;
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
.user-emailid{
  color: black;
}
main{
    margin-top:20px;
}
.container{
  margin-top: 58px;
  
}

  .excel-box {
    margin-bottom: 30px;
    background-color: rgb(9, 9, 9);
    color: white;
    padding: 20px; /* Adjust the padding for better aesthetics */
    border-radius: 8px;
    border: #4c0bce;

  }

  .excel-box h2 {
    color: #a5a7e6;
  }

  .excel-box table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 0px;
    background-color: black;
    
  }
 
  .excel-box table th,
  .excel-box table td {
    padding: 10px;
    text-align: center;
    color: #fff;

  }

  .excel-box table td{
    border: 0.1px solid #555a64; /* Add a thinner border */
    
  }

  .excel-box table th {
    background-color: #5468ff;
    font-weight: bold;
    color: whitesmoke;
    padding-bottom: 10px;

  }
  .excel-box table th:first-child {
    border-top-left-radius: 11px; /* Adjust this line for rounded corners on the top-left corner */
}

.excel-box table th:last-child {
    border-top-right-radius: 11px; /* Adjust this line for rounded corners on the top-right corner */
}

  .excel-box table tbody tr:nth-child(even) {
    background-color: black;
    
  }

  .excel-box table tbody tr:hover {
    background-color: rgba(71, 58, 58, 0.482);

  }
 
  .excel-box table td:first-child,
  .excel-box table td.task-actions:last-child {
    border-left: none;
  }

  .excel-box table td:last-child {
    border-right: none;
  }
/*remove border from first and last*/
  .excel-box table td:nth-child(2),
  .excel-box table td:nth-child(6),
  .excel-box table th:nth-child(2),
  .excel-box table th:nth-child(6) {
    border-right: none;
    
  }

  .excel-box table th:first-child,
  .excel-box table td:first-child {
    border-left: none;
  }

  .edit-button{
    margin-right: 10px;

  }
  .edit-button,
  .delete-button {
    border: 1.5px solid #0d6efd;
    color: white;
    background-color: #4c0bce;
    padding: 5px 12px;
    cursor: pointer;
    border-radius: 7px;

  }

  .edit-button:hover,
  .delete-button:hover {
    background-color: #9999D0;
    color: #fff;
  }


  .btn-custom {
    background-color: #513c90; 
    border-color:  #513c90;
    display: block;
    margin: 0 auto;
    padding-top: 10px;
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

  /* CSS code for styling the project list table */
.project-list {
    margin-top: 30px;
}

.table-responsive {
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid #ddd;
}

.table th,
.table td {
    padding: 10px;
    text-align: left;
}

.table th {
    background-color: #f2f2f2;
}

.table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

.table tbody tr:hover {
    background-color: #f2f2f2;
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

    // Assume manager_id is passed through a session variable after login
if (isset($_SESSION['emp_id'])) {
    $employeeId = $_SESSION['emp_id'];

    // Query to fetch manager details
    $query = "SELECT * FROM employees_login WHERE emp_id = '$employeeId'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $manager = mysqli_fetch_assoc($result);
    } else {
        echo "Manager ID not found";
        exit;
    }
} else {
    echo "Session not started or manager_id not set.";
    exit;
}

// Define the calculateProgress function
function calculateProgress($projectId, $conn) {
    // Query to count completed tasks for the project
    $completedTasksQuery = "SELECT COUNT(*) as completed_tasks FROM tasksdetails WHERE project_id = '$projectId' AND status = 'completed'";
    $completedTasksResult = mysqli_query($conn, $completedTasksQuery);

    if ($completedTasksResult && mysqli_num_rows($completedTasksResult) > 0) {
        $completedTasks = mysqli_fetch_assoc($completedTasksResult)['completed_tasks'];
    } else {
        $completedTasks = 0;
    }

    // Query to count total tasks for the project
    $totalTasksQuery = "SELECT COUNT(*) as total_tasks FROM tasksdetails WHERE project_id = '$projectId'";
    $totalTasksResult = mysqli_query($conn, $totalTasksQuery);

    if ($totalTasksResult && mysqli_num_rows($totalTasksResult) > 0) {
        $totalTasks = mysqli_fetch_assoc($totalTasksResult)['total_tasks'];
    } else {
        $totalTasks = 0;
    }

    // Calculate progress percentage
    if ($totalTasks > 0) {
        $progress = ($completedTasks / $totalTasks) * 100;
    } else {
        $progress = 0;
    }

    return round($progress); // Round progress to nearest integer
}
?>

<!-- HTML code for displaying project list in table format -->
<section id="content">
    <div class="col-md-12 text-left">
        <!-- Project list table -->
        <div class="project-list">
            <h2 style="color: #4c0bce;">Project List</h2>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Team</th>
                            <th>Due Date</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Progress</th>
                            <th>Report</th> 
                        </tr>
                    </thead>
                    <tbody>
                    <?php
      // Check if the employee ID is set in the session
      if (isset($_SESSION['emp_id'])) {
          $employeeId = $_SESSION['emp_id'];

          // Query to fetch projects associated with the logged-in employee
          $employeeProjectsQuery = "SELECT DISTINCT p.*, t.team_name FROM projectsnew p
              INNER JOIN tasksdetails td ON p.project_id = td.project_id
              INNER JOIN teams t ON p.team_id = t.team_id
              WHERE td.assignee = '$employeeId'";
          $employeeProjectsResult = mysqli_query($conn, $employeeProjectsQuery);

          // Array to keep track of displayed project names
          $displayedProjects = array();

          if ($employeeProjectsResult && mysqli_num_rows($employeeProjectsResult) > 0) {
              // Loop through each project and display its details
              while ($project = mysqli_fetch_assoc($employeeProjectsResult)) {
                  // Calculate progress
                  $progress = calculateProgress($project['project_id'], $conn);

                  // Check if the project name has already been displayed
                  if (!in_array($project['project_name'], $displayedProjects)) {
                      echo "<tr>";
                      echo "<td>{$project['project_name']}</td>";
                      echo "<td>{$project['team_name']}</td>";
                      echo "<td>{$project['due_date']}</td>";
                      echo "<td>{$project['priority']}</td>";
                      echo "<td>";
                      // Display status with appropriate symbols
                      $status = $project['status'];
                      switch ($status) {
                          case 'on_track':
                              echo "&#10004; On track";
                              break;
                          case 'off_track':
                              echo "&#10006; Off track";
                              break;
                          case 'on_hold':
                              echo "&#10074; On hold";
                              break;
                          case 'at_risk':
                              echo "&#9888; At risk";
                              break;
                          case 'completed':
                              echo "&#10004; Completed";
                              break;
                          default:
                              echo $status; // Default case
                      }
                      echo "</td>";
                      echo "<td>{$progress}%</td>"; // Display progress
                      echo "<td><a href='progress_report.php?project_id={$project['project_id']}' class='btn btn-primary'>View Report</a></td>";
                      echo "</tr>";

                      // Add the project name to the displayed projects array
                      $displayedProjects[] = $project['project_name'];
                  }
              }
          } else {
              echo "<tr><td colspan='7'>No projects found for this employee.</td></tr>";
          }
      } else {
          echo "<tr><td colspan='7'>Session not started or emp_id not set.</td></tr>";
      }
      ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
    </body>
    </html>
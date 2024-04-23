<?php
session_start();
include("dbconn.php");
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task_id']) && isset($_POST['status'])) {
  $taskId = $_POST['task_id'];
  $newStatus = $_POST['status'];

  // Update status in the database
  $updateQuery = "UPDATE tasksdetails SET status = '$newStatus', status_updated_at = NOW() WHERE task_id = '$taskId'";
  $updateResult = mysqli_query($conn, $updateQuery);

  if ($updateResult) {

      // Redirect to the same page to prevent form resubmission
      header('Location: assignedtask.php');
      exit();
  } else {
      echo "<script>alert('Error updating status!');</script>";
  }
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css'>

<!-- Add these links to include Bootstrap CSS and JS for Add task form pop up-->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js" integrity="sha384-zMP7rVo3A/CBC6IWhc7QK1Li6Es09mCMAVqnF3Dbt4HRsi6s2hBOJqLBob0ZuofC" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+WyW8JnEMqJL2R8uPvZTt0U9Y7S6Kv1N" crossorigin="anonymous"></script>    

    <title>Project Management</title>

    <style>
      body, h1, h2, h3, p, ul, li, table {
    margin: 0;
    padding: 0;
    color: black;
}
body{
    color: black;
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
  padding: 10px;
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
    width: 1530px;
    margin-left: 250px;
    /* margin-bottom: 40px; */
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

.sticky-heading th {
    position: sticky;
    top: 0;
    background-color: #f2f2f2;
    z-index: 1;
}

  body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }

        .container {
            max-width: 1200px;
            padding: 0 20px;
        }

        
        h2, h3, h4 {
            color: #008cba;
        }
        p{
          color: white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;

        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;

        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            border-radius: 10px;

        }

        select {
            padding: 8px 12px;
            border-radius: 5px;
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            font-size: 14px;
            color: #333;
            outline: none;
        }

        select:hover, select:focus {
            border-color: #777;
        }

        .status-completed {
            background-color: #5cb85c;
            color: #fff;
        }

        .status-pending {
            background-color: #f0ad4e;
            color: #fff;
        }

        .status-hold {
            background-color: #d9534f;
            color: #fff;
        }

        .status-at-risk {
            background-color: #428bca;
            color: #fff;
        }

        /* Responsive table */
        @media only screen and (max-width: 600px) {
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            td, th {
                display: inline-block;
                min-width: 150px;
            }
        }    
       
        .task-details-container{
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
.task-details-container * {
        color: black;
    }
</style>

  <!-- SIDEBAR MENU-->
  <section id="sidebar">
                <div class="brand">
                    <span id="text" style="color: white;">Employee:</span>
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

<?php
// Assume emp_id is passed through a session variable after login
if (isset($_SESSION['emp_id'])) {
    $empId = $_SESSION['emp_id'];

    // Query to fetch employee details
    $query = "SELECT * FROM employees_login WHERE emp_id = '$empId'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $employee = mysqli_fetch_assoc($result);

        // Fetch project details for the employee's team
        $projectQuery = "SELECT projectsnew.project_id, projectsnew.project_name, projectsnew.due_date, projectsnew.priority, projectsnew.content, managers_login.manager_name
        FROM employee_teams
        LEFT JOIN teams ON employee_teams.team_id = teams.team_id
        LEFT JOIN projectsnew ON teams.team_id = projectsnew.team_id
        LEFT JOIN managers_login ON projectsnew.manager_id = managers_login.manager_id
        WHERE employee_teams.emp_id = '$empId'";
        $projectResult = mysqli_query($conn, $projectQuery);

        if ($projectResult && mysqli_num_rows($projectResult) > 0) {
            echo "<section id='content'>
                    
                                <div class='col-md-12 text-left'>";

            while ($projectDetails = mysqli_fetch_assoc($projectResult)) {
                // Display project information
                echo "<div class='project-info'>
                
            </div>
        </section>";
               // Fetch assigned tasksdetails for the project
                $projectId = $projectDetails['project_id'];
                $tasksQuery = "SELECT * FROM tasksdetails WHERE project_id = '$projectId' AND assignee = '$empId'";
                $tasksResult = mysqli_query($conn, $tasksQuery);

                echo "<h4 style='margin-left: 250px; margin-bottom: 0; margin-top:40px;'>Assigned Tasks</h4> <br>";
                echo "<div style='margin-left: 250px; display: flex; align-items: center;'>"; // Opening container for project name and priority
                echo "<h5 style='margin-right: 20px;'>{$projectDetails['project_name']}</h5>";
                echo "</div>"; // Closing container

                if ($tasksResult && mysqli_num_rows($tasksResult) > 0) {
                    echo "
                  <div class='container1 mt-5'>
                      <div class='table-container'>
                          <table class='table' id='taskTable'>
                              <thead class='sticky-heading'>
                                  <tr>
                                      <th>Task Title</th>
                                      <th>Title Details</th>
                                      <th>Assigned Date</th>
                                      <th>Due Date</th>
                                      <th>Priority</th>
                                      <th>View</th>
                                      <th>Status</th>
                                  </tr>
                              </thead>
                              <tbody>";

                    while ($task = mysqli_fetch_assoc($tasksResult)) {
                        echo "<tr>
                                        <td>{$task['task_title']}</td>
                                        <td>{$task['task_details']}</td>
                                        <td>{$task['created_at']}</td>
                                        <td>{$task['due_date']}</td>
                                        <td>{$task['priority']}</td>
                                        <td><button class='btn btn-primary view-task-btn' onclick='viewTask(this)' data-task-id='{$task['task_id']}'>View</button></td>
                                        <td>
                                            <form class='status-form' method='POST' action=''>
                                                <input type='hidden' name='task_id' value='{$task['task_id']}'>
                                                <select name='status' onchange='this.form.submit()'>
                                                    <option value='pending' " . ($task['status'] == 'pending' ? 'selected' : '') . ">Pending</option>
                                                    <option value='completed' " . ($task['status'] == 'completed' ? 'selected' : '') . ">Completed</option>
                                                    <option value='hold' " . ($task['status'] == 'hold' ? 'selected' : '') . ">Hold</option>
                                                </select>
                                            </form>
                                        </td>
                                      </tr>";
                    }

                    echo "</tbody></table>
                  </div>
                  </div>";
                } else {
                    echo "No tasks assigned for this project.";
                }
            }

            echo "</div></div></div></section>";
        } else {
            echo "No projects found for your team.";
        }
    } else {
        echo "Employee not found";
        exit;
    }
} else {
    echo "Session not started or emp_id not set.";
    exit;
}
?>

<!-- Container to display task details -->
<div id="task-details-container" class="container" style="display: none;">
    <h3 class="mt-4 mb-3">Task Details</h3>
    <div id="taskDetails" class="card p-4">
        <div class="row mb-3">
            <div class="col-sm-3"><strong>Task Title:</strong></div>
            <div class="col-sm-9" id="taskTitle"></div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-3"><strong>Assigned Date:</strong></div>
            <div class="col-sm-9" id="assignee"></div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-3"><strong>Due Date:</strong></div>
            <div class="col-sm-9" id="dueDate"></div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-3"><strong>Priority:</strong></div>
            <div class="col-sm-9" id="priority"></div>
        </div>
        <div class="row">
            <div class="col-sm-3"><strong>Task Details:</strong></div>
            <div class="col-sm-9" id="taskDescription"></div>
        </div>
    </div>
</div>

<!-- JavaScript to handle the view task functionality -->
<script>
function viewTask(button) {
    // Get the selected task row
    var taskRow = button.closest('tr');

    // Check if the task row exists
    if (!taskRow) {
        console.error('Task row not found.');
        return;
    }

    // Extract task details from the row
    var taskTitle = taskRow.querySelector('td:nth-child(1)').textContent;
    var taskDetails = taskRow.querySelector('td:nth-child(2)').textContent;
    var assignedDate = taskRow.querySelector('td:nth-child(3)').textContent;
    var dueDate = taskRow.querySelector('td:nth-child(4)').textContent;
    var priority = taskRow.querySelector('td:nth-child(5)').textContent;

    // Update the task details container with the extracted details
    document.getElementById('taskTitle').textContent = taskTitle;
    document.getElementById('assignee').textContent = assignedDate; // Here you can place the assignee data, as it's not clear from the provided code where the assignee information is.
    document.getElementById('dueDate').textContent = dueDate;
    document.getElementById('priority').textContent = priority;
    document.getElementById('taskDescription').textContent = taskDetails;

    // Show the task details container
    document.getElementById('task-details-container').style.display = 'block';
}
</script>

</body>
</html>
<?php include("dbconn.php"); 
// Start the session
session_start();
include("dbconn.php");

// Assume manager_id is passed through a session variable after login
if (isset($_SESSION['manager_id'])) {
    $empId = $_SESSION['manager_id'];

    // Query to fetch employee details
    $query = "SELECT * FROM managers_login WHERE manager_id = '$empId'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $employee = mysqli_fetch_assoc($result);
    } else {
        echo "Manager ID not found";
        exit;
    }
} else {
    echo "Session not started or manager_id not set.";
    exit;
}

// Function to get project details
function getProjectDetails($projectId, $conn)
{
    // Sanitize the input (optional, but recommended)
    $projectId = mysqli_real_escape_string($conn, $projectId);

    // Check if $projectId is not empty
    if (!empty($projectId)) {
        $sql = "SELECT * FROM projectsnew WHERE project_id = $projectId";
        $result = $conn->query($sql);

        return ($result->num_rows > 0) ? $result->fetch_assoc() : null;
    } else {
        // Handle the case where $projectId is empty
        return null;
    }
}

// Function to get tasks for a project
function getTasksForProject($projectId, $conn)
{
    $projectId = mysqli_real_escape_string($conn, $projectId);

    $sql = "SELECT * FROM tasksdetails WHERE project_id = $projectId";
    $result = $conn->query($sql);

    return ($result->num_rows > 0) ? $result : null;
}
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
  // Function to get team members for a project
        function getTeamMembersForProject($projectId, $conn) {
            $teamMembers = array();
        
            $query = "SELECT et.emp_ID, el.emp_name
                      FROM employee_teams et
                      JOIN employees_login el ON et.emp_ID = el.emp_ID
                      WHERE et.team_id = (SELECT team_id FROM projectsnew WHERE project_id = $projectId)";
            $result = $conn->query($query);
        
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $teamMembers[] = $row;
                }
            }
        
            return $teamMembers;
        } 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $details = $_POST["details"];
    $assignee = $_POST["assignee"];
    $dueDate = isset($_POST["dueDate"]) ? $_POST["dueDate"] : '';
    $priority = $_POST["priority"];
    $projectId = isset($_POST["project_id"]) ? $_POST["project_id"] : null;

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO tasksdetails (task_title, task_details, assignee, due_date, priority, created_at, project_id) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP, ?)");

    // Bind parameters
    $stmt->bind_param("sssssi", $title, $details, $assignee, $dueDate, $priority, $projectId);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Task added successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

  // Close the statement
    $stmt->close();

    // Fetch the employee name
$assigneeName = getEmployeeNameById($assignee, $conn);

// Return the employee name as JSON response
echo json_encode(array('assigneeName' => $assigneeName));
}

    // Display project details
    if (isset($_GET['project_id'])) {
        $projectId = $_GET['project_id'];

        // Get project details
        $projectDetails = getProjectDetails($projectId, $conn);

        if ($projectDetails) {
            // Get tasks for the project
            $tasksForProject = getTasksForProject($projectId, $conn);
        } else {
            // echo "Project not found.";
            exit();
        }
    } else {
        echo "Project ID not specified.";
        exit();
    }

// Fetch project details from the database based on the project ID
$query = "SELECT p.*, t.team_id, et.emp_id, et.team_id AS emp_team_id, emp.emp_name, t.team_name
          FROM projectsnew p
          LEFT JOIN teams t ON p.team_id = t.team_id
          LEFT JOIN employee_teams et ON t.team_id = et.team_id
          LEFT JOIN employees_login emp ON et.emp_id = emp.emp_id
          WHERE p.project_id = $projectId";

$result = mysqli_query($conn, $query);

// Check if the query was successful
if (!$result) {
    echo "Error fetching project details: " . mysqli_error($conn);
    exit;
}

// Fetch project details as an associative array
$projectDetails = array();
while ($row = mysqli_fetch_assoc($result)) {
    $projectDetails['project'] = array(
        'project_id' => $row['project_id'],
        'project_name' => $row['project_name'],
        'proj_manager' => $row['proj_manager'],
        'start_date' => $row['start_date'],
        'due_date' => $row['due_date'],
        'content' => $row['content'],
        'priority' => $row['priority'],
        'status' => $row['status'],
        'attachment_path' => $row['attachment_path'],  // Include attachment path
        'team_id' => $row['team_id'],
        'team_name' => $row['team_name'],  
    );

    $projectDetails['team'][] = array(
        'team_id' => $row['team_id'],
        'emp_id' => $row['emp_id'],
        'emp_name' => $row['emp_name'],
        'emp_team_id' => $row['emp_team_id']
    );
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/boxicons/2.0.7/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css'>
    <style>
      body, h1, h2, h3, p, ul, li, table {
    margin: 0;
    padding: 0;
    color: #fff;
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

/* /LOGOUT/ */     
          .container {
            background-color: black;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 40px;
            

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
         
          .back-btn:hover {
            background-color: #2e2272;
            border: #2e2272;
            color: #fff;
          }
             
          .btn-custom {
            background-color: #6610f2; 
            border-color:  #4D1DDE;
            display: block;
            margin: 0 auto;
            padding-top: 10px;

        }
        .btn-custum{
            margin-bottom: 25px;
        }
        
        /* background color of the submit button on hover */
        .btn-custom:hover {
                 background-color:#3223619e;
                 border-color:  #eceaf3;
        
        }

  /* STYLE FOR PROJECT DETAILS*/
 
  .project-details-container {
            background-color: transparent;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border: 2px solid #9972bf; /* Neon color border */
        }

        .project-details-container h2 {
            color: #007bff;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .dropdown-menu {
            border: 2px solid #17a2b8; /* Neon color border for the dropdown */
        }

        /* Style for the placeholder */
        .form-group p::before {
            content: attr(placeholder);
            color: #555;
        }

        /* Style for the values */
        .form-group p {
            background-color: transparent;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #6f42c1;
        }

        /* Style for the button */       
#inputStatus {
    background-color: #6610f2; 
    color: #fff; 
    border: none;
    width:  122px;
    height: 45px;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer; 
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}

#inputStatus option {
    background-color: black; 
    color: white; /*  text color for options */
}

#inputStatus option:hover {
    background-color: #f2f2f2;
}
.form-group-col-md-2{
    padding: 10px;
}
 
.form-group1 div ul {
                list-style-type: disc; 
                padding-left: 20px; 
                margin: 0; 
}

.card{
    color: #000;
}

.text-left1
{
    margin-left: 80%;
    margin-bottom: 10px;


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

.sticky-heading th {
    position: sticky;
    top: 0;
    background-color: #f2f2f2;
    z-index: 1;
}

.btn-primary a {
        text-decoration: none;
        color: inherit;
    }
#taskDetailsContainer{
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
#taskDetailsContainer * {
        color: black;
    }
strong{
    color: black;
}
.col-sm-9{
    color: #000;
}

  </style>
</head>
<body>
    <!--SIDEMENU-->
    <section id="sidebar">
                <div class="brand">
                    <span id="text">Manager Details</span><br>
                        <p1>Name: <?php echo $employee['manager_name']; ?> </p1><br>
                        <p1>Manager ID: <?php echo $employee['manager_id']; ?> </p1>
               </div>
                <div class="user-box">
                    <div class="user-info">
                    <span class="user-emailid"><?php echo $employee['email_id']; ?></span>    
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
                
                <li class="active">
                    <a href="dash.php">
                        <i class='bx bxs-dashboard'></i>
                        <span class="text">Dashboard</span>
                    </a>
                </li>
                <li class="active">

                    <a href="inbox.php">
                        <i class="bx bx-bell"></i>
                        <span class="text">Inbox</span>
                    </a>
                </li>
                <li>
                    <a href="addpro.php">
                        <i class='bx bxs-shopping-bag-alt'></i>
                        <span class="text">+Create Project</span>
                    </a>
                </li>
                <li>
                    <a href="team.php">
                     <i class='bx bxs-group'></i>
                     <span class="text">+Create Team</span>
                    </a>
                </li>
                
                <li>
                    <a href="projectlist.php">
                    <i class='bx bxs-briefcase'></i>
                        <span class="text">My Project</span>
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
                    <a href="progress.php">
                        <i class='bx bxs-doughnut-chart'></i>
                        <span class="text">Progress</span>
                    </a>
                </li>
                <li>
                    <a href="mytask.php">
                        <i class='bx bxs-message-dots'></i>
                        <span class="text">+To Do</span>
                    </a>
                </li>
            </ul>
    </section>
    <!--SIDEMENU-->
    
    <!-- <h2>Project Details: <?php echo $projectDetails['project_name']; ?></h2>
    
    <h3>Project Information:</h3>
    <ul>
        <?php foreach ($projectDetails as $key => $value): ?>
            <li><strong><?php echo $key; ?>:</strong> <?php echo $value; ?></li>
        <?php endforeach; ?>
    </ul> -->
    
<section id="content">
        <div class="container">
                    <div class="col-md-2 text-left1">                    
                            <a href="team.php" class="btn btn-primary btn-custom">Create Team</a>
                    </div>  
                    <div class="project-details-container">  
                     <div class="row">
                        <div class="col-md-3">
                            <h2>Project Details</h2>
                            <!-- Add project details here -->
                        </div>
                        <?php

                        // Fetch the project ID from the GET parameter
$projectId = isset($_GET['project_id']) ? $_GET['project_id'] : null;

// Initialize current status variable
$currentStatus = '';

if ($projectId !== null) {
    // Sanitize the input (optional, but recommended)
    $projectId = mysqli_real_escape_string($conn, $projectId);

    // Fetch the current status of the project from the database
    $query1 = "SELECT status FROM projectsnew WHERE project_id = '$projectId'";
    $result1 = mysqli_query($conn, $query1);

    if ($result1 && mysqli_num_rows($result1) > 0) {
        $row = mysqli_fetch_assoc($result1);
        $currentStatus = $row['status'];
    } else {
        $currentStatus = ''; // Default value if no status found
    }
}

?>

    <div class="col-md-4">
        <form id="statusForm" action="update_project_status.php" method="post" class="form-inline">
        <input type="hidden" name="projectId" value="<?php echo $projectId; ?>">

            <div class="form-group">
                <select class="form-control update-status-dropdown" id="inputStatus" name="status" required>
                    <option value="" disabled>Select Status</option>
                    <option value="on_track" style="color: #28a745;" <?php if ($currentStatus === 'on_track') echo 'selected'; ?>>&#10004; On track</option>
                    <option value="off_track" style="color: #dc3545;" <?php if ($currentStatus === 'off_track') echo 'selected'; ?>>&#10006; Off track</option>
                    <option value="on_hold" style="color: #ffc107;" <?php if ($currentStatus === 'on_hold') echo 'selected'; ?>>&#10074; On hold</option>
                    <option value="at_risk" style="color: #ff6347;" <?php if ($currentStatus === 'at_risk') echo 'selected'; ?>>&#9888; At risk</option>
                    <option value="completed" style="color: #28a745;" <?php if ($currentStatus === 'completed') echo 'selected'; ?>>&#10004; Completed</option>
                </select>
            </div>
            <input type="hidden" name="projectId" value="<?php echo $projectId; ?>">
        </form>
    </div>

                       <!--TOTAL TASK REPORT-->
<?php
// Function to get total task count for a project
function getTotalTaskCount($projectId, $conn) {
    $sql = "SELECT COUNT(*) as total FROM tasksdetails WHERE project_id = $projectId";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total'];
    } else {
        return 0;
    }
}

// Function to get completed task count for a project
function getCompletedTaskCount($projectId, $conn) {
    $sql = "SELECT COUNT(*) as completed FROM tasksdetails WHERE project_id = $projectId AND status = 'completed'";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['completed'];
    } else {
        return 0;
    }
}

// Example usage
if (isset($_GET['project_id'])) {
    $projectId = $_GET['project_id'];
    $totalTaskCount = getTotalTaskCount($projectId, $conn);
    $completedTaskCount = getCompletedTaskCount($projectId, $conn);

    // Calculate percentage of completed tasks
    $percentageCompleted = ($totalTaskCount > 0) ? ($completedTaskCount / $totalTaskCount) * 100 : 0;
}
?>

<div class="col-md-4">
    <div class="card" style="background-color: #4D1DDE; color:white;">
        <div class="card-body">
            <h3 style="color:white">Total Tasks</h3>
            <hr>
            <p class="card-text"><?php echo $completedTaskCount . " / " . $totalTaskCount . " completed"; ?></p>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: <?php echo $percentageCompleted; ?>%;" aria-valuenow="<?php echo $percentageCompleted; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo round($percentageCompleted, 2) . "%"; ?></div>
            </div>
        </div>
    </div>
</div>
     
        <div class="row" style="padding-top:30px;">
                <div class="col-md-3" >
                    <div class="form-group">
                        <label>Project Name:</label>
                        <p><?php echo $projectDetails['project']['project_name']; ?></p>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label> Priority:</label>
                        <p><?php echo $projectDetails['project']['priority']; ?></p>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label>Team:</label>
                        <?php 
                            $teamName = $projectDetails['project']['team_name'];
                            echo $teamName ? '<p>' . $teamName . '</p>' : '<p>Team not created</p>';
                        ?>
                    </div>
                </div> 
        </div>   
   
    <div class="row" style="padding-top:30px;">
            <div class="col-md-2">
                <div class="form-group">
                    <label> Due date:</label>
                    <p><?php echo $projectDetails['project']['due_date']; ?></p>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label>Attachment:</label>
                    
                    <?php 
                    if ($projectDetails['project']['attachment_path']) {
                        $attachmentPath = $projectDetails['project']['attachment_path'];
                        $filename = basename($attachmentPath);

                        echo '<a href="' . $attachmentPath . '" download="' . $filename . '">' . $filename . '</a>';
                    } else {
                        echo 'No attachment';
                    }
                ?>
               </div>
        </div>
    </div>
</div>

<div class="form-group1">
        <label> Description:</label>
        <div style="background: black; max-height: 200px; padding: 10px; color: white; overflow: auto;">
        <?php echo $projectDetails['project']['content']; ?>
    </div>
    
</section>
</head>
<body>

<div class="container1 mt-5 ">

    <button type="button" class="btn btn-primary btn-custum" onclick="addTask()">Add Task</button>
    <?php
    $projectId = isset($_GET['project_id']) ? $_GET['project_id'] : null;

// Sanitize the input (optional, but recommended)
$projectId = mysqli_real_escape_string($conn, $projectId);

// Fetch tasks from the database for the selected project
$sql = "SELECT * FROM tasksdetails WHERE project_id = '$projectId'";
$result = $conn->query($sql);

$tasks = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
}
?>
       
<!-- Table to display tasks -->
<div class="table-container">
    <table class="table" id="taskTable">
        <thead class="sticky-heading">
            <tr>
                <th>Task Title</th>
                <th>Task Details</th>
                <th>Assignee</th>
                <th>Due Date</th>
                <th>Assigned Date</th>
                <th>Priority</th>
                <th style="text-align: center;">Action</th>
                <th>View</th> 

            </tr>
        </thead>
        <tbody id="taskTableBody">
            <?php
            // Display tasks in the table
            foreach ($tasks as $row) {
                echo "<tr>";
                echo "<input type='hidden' name='task_id' value='{$row['task_id']}'>";
                echo "<td class='task-title-cell'>{$row['task_title']}</td>";
                echo "<td class='task-details-cell'>{$row['task_details']}</td>";

                $assigneeName = getEmployeeNameById($row['assignee'], $conn);

                // Fetch team members for the project
                $teamMembers = getTeamMembersForProject($row['project_id'], $conn);

                echo "<td class='assgnee-cell'>";
                echo "<select class='form-control' name='assigneeDropdown[]'>";

                // Display team members in the dropdown
                foreach ($teamMembers as $member) {
                    $selected = ($row['assignee'] == $member['emp_ID']) ? 'selected' : '';
                    echo "<option value='{$member['emp_ID']}' $selected>{$member['emp_name']}</option>";
                }

                echo "</select>";
                echo "</td>";
                echo "<td class='date-cell'>{$row['due_date']}</td>";
                echo "<td class='date-cell'>{$row['created_at']}</td>";
                echo "<td class='priority-cell'>{$row['priority']}</td>";
                echo "<td>  
                <button class='btn btn-primary' onclick='editTask(this.closest(\"tr\"))'>Update</button>
                <button class='btn btn-primary' style='margin-left: 15px;'><a href='deleteTask.php?deleteid={$row['task_id']}' class='text-light'>Delete</a></button>

                    </td>";

                    echo "<td>  
                    <button class='btn btn-primary'  onclick='viewTask(this)'>View</button>

                    </td>";

                echo "</tr>";
            }
           
            ?>
        </tbody>
    </table>
    </div>
</div>
    <!-- Bootstrap dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<!-- Container to display task details -->
<div id="taskDetailsContainer" class="container" style="display: none;">
    <h3 class="mt-4 mb-3">Task Details</h3>
    <div id="taskDetails" class="card p-4">
        <div class="row mb-3">
            <div class="col-sm-3"><strong>Task Title:</strong></div>
            <div class="col-sm-9" id="taskTitle"></div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-3"><strong>Assignee:</strong></div>
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

    // Extract task details from the row
    var taskTitle = taskRow.querySelector('.task-title-cell').textContent;
    var assignee = taskRow.querySelector('.assignee-cell select').value;
    var dueDate = taskRow.querySelector('.date-cell').textContent;
    var priority = taskRow.querySelector('.priority-cell').textContent;
    var taskDetails = taskRow.querySelector('.task-details-cell').textContent;

    // Update the task details container with the extracted details
    document.getElementById('taskTitle').textContent = taskTitle;
    document.getElementById('assignee').textContent = assignee;
    document.getElementById('dueDate').textContent = dueDate;
    document.getElementById('priority').textContent = priority;
    document.getElementById('taskDescription').textContent = taskDetails;

    // Show the task details container
    document.getElementById('taskDetailsContainer').style.display = 'block';
}
</script>

    <script>

function viewTask(button) {
    // Get the selected task row
    var taskRow = button.closest('tr');

    // Check if the task row and the select element exist
    if (!taskRow) {
        console.error('Task row not found.');
        return;
    }

    var assigneeSelect = taskRow.querySelector('.assgnee-cell select');
    if (!assigneeSelect) {
        console.error('Assignee select element not found in the task row.');
        return;
    }

    // Extract task details from the row
    var taskTitle = taskRow.querySelector('.task-title-cell').textContent;
    var taskDetails = taskRow.querySelector('.task-details-cell').textContent;
    var assignee = assigneeSelect.value;
    var dueDate = taskRow.querySelector('.date-cell').textContent;
    var priority = taskRow.querySelector('.priority-cell').textContent;

    // Construct HTML for task details
    var taskDetailsHTML = `
        <p><strong>Task Title:</strong> ${taskTitle}</p>
        <p><strong>Task Details:</strong> ${taskDetails}</p>
        <p><strong>Assignee:</strong> ${assignee}</p>
        <p><strong>Due Date:</strong> ${dueDate}</p>
        <p><strong>Priority:</strong> ${priority}</p>
    `;

    // Update the task details container with the HTML
    var taskDetailsContainer = document.getElementById('taskDetails');
    if (taskDetailsContainer) {
        taskDetailsContainer.innerHTML = taskDetailsHTML;

        // Show the task details container
        var taskDetailsContainerParent = document.getElementById('taskDetailsContainer');
        if (taskDetailsContainerParent) {
            taskDetailsContainerParent.style.display = 'block';
        } else {
            console.error('Task details container parent not found.');
        }
    } else {
        console.error('Task details container not found.');
    }
}


// Define cellTypeMap globally
const cellTypeMap = {
        'date-cell': 'date',
        'assignee-cell': 'select',
        'select-cell': 'select',
        'priority-cell': 'select',
        'task-title-cell': 'text',
        'task-details-cell': 'text',
        // Add more cell types as needed
    };

    function editTask(row) {
    for (let i = 0; i < row.cells.length - 1; i++) {
        const cell = row.cells[i];
        const content = cell.textContent.trim();
        const cellClass = Array.from(cell.classList).find(className => cellTypeMap[className]);

        if (cellClass) {
            const inputElement = createInputElement(cellTypeMap[cellClass], content, cellClass);
            cell.innerHTML = '';
            cell.appendChild(inputElement);
        }
    }

    const updateButton = row.querySelector('.btn-primary');
    const saveButton = document.createElement('button');
    saveButton.innerHTML = 'Save';
    saveButton.className = 'btn btn-success';
    saveButton.onclick = function () {
        updateTask(row);
    };

    updateButton.parentNode.replaceChild(saveButton, updateButton);
}

function createInputElement(type, content, cellClass) {
    const inputElement = document.createElement(type === 'select' ? 'select' : 'input');
    inputElement.className = 'form-control';

    if (type === 'select') {
        if (cellClass === 'assignee-cell') {
            // Populate options for assignee dropdown
            const teamMembers = getTeamMembersForProject($projectID, $conn);
            teamMembers.forEach(member => {
                const option = document.createElement('option');
                option.value = member.emp_ID;
                option.text = member.emp_name;
                option.selected = (content === member.emp_name);
                inputElement.appendChild(option);
            });
        } else if (cellClass === 'priority-cell') {
            // Populate options for priority dropdown
            const priorities = ['High', 'Medium', 'Low'];
            priorities.forEach(priority => {
                const option = document.createElement('option');
                option.value = priority;
                option.text = priority;
                option.selected = (content === priority);
                inputElement.appendChild(option);
            });
        }
    } else if (type === 'date') {
        inputElement.type = 'date';
        inputElement.value = content; // Set the existing date value
    } else if (type === 'text') {
        inputElement.type = 'text';
        inputElement.value = content;
    }

    return inputElement;
}

function updateTask(row) {
    // Set cell values in the current row
    var taskId = row.querySelector('input[name="task_id"]').value;
    var title = row.cells[0].querySelector('input, select').value.trim();
    var details = row.cells[1].querySelector('input, select').value.trim();
    var assignee = row.cells[2].querySelector('select').value.trim();
    var dueDate = row.cells[3].querySelector('input').value.trim();
    var priority = row.cells[4].querySelector('select').value.trim();

    // Check if any field is empty
    
    // Create a FormData object to send data via AJAX
    var formData = new FormData();
    formData.append('task_id', taskId);
    formData.append('title', title);
    formData.append('details', details);
    formData.append('assignee', assignee);
    formData.append('dueDate', dueDate);
    formData.append('priority', priority);

    // Send the data using AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'updateTask.php', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            // Handle the response from the server if needed
            var response = JSON.parse(xhr.responseText);

            if (response.status === 'success') {
                // Update cell values in the current row

                var assigneeName = response.assigneeName;

                row.cells[0].textContent = title;
                row.cells[1].textContent = details;
                row.cells[2].textContent = assignee;
                row.cells[3].textContent = dueDate;
                row.cells[4].textContent = priority;

                // You can add other logic or UI changes here if needed
            } else {
                alert('Task update failed. Please try again.');
            }
        } else {
            // Handle errors
            console.error('Error updating task.');
        }
    };

    // Send the data using AJAX
    xhr.send(formData);
}

  // Function to stop event propagation
    function stopPropagation(event) {
        event.stopPropagation();
    }
    // Global variable to store the current row
    var currentRow;

    function addTask() {
        // Create a new row and append it to the table
        var tableBody = document.getElementById('taskTableBody');
        var newRow = tableBody.insertRow(tableBody.rows.length);

        // Add cells to the new row
        var cellTitle = newRow.insertCell(0);
        var cellDetails = newRow.insertCell(1);
        var cellAssignee = newRow.insertCell(2);
        var cellDueDate = newRow.insertCell(3);
        var cellPriority = newRow.insertCell(4);
        var cellAction = newRow.insertCell(5); // New cell for the Assign Task button

        // Set cell values
        cellTitle.innerHTML = '<div contenteditable="true" onclick="stopPropagation(event)"></div>';
        cellDetails.innerHTML = '<div contenteditable="true" onclick="stopPropagation(event)"></div>';
        cellAssignee.innerHTML = '<select class="form-control" contenteditable="true" onclick="stopPropagation(event)"></select>';
        cellDueDate.innerHTML = '<input type="date" class="form-control" contenteditable="true" onclick="stopPropagation(event)" required min="<?php echo date('Y-m-d'); ?>">';
        cellPriority.innerHTML = '<select contenteditable="true" onclick="stopPropagation(event)"><option value="High">High</option><option value="Medium">Medium</option><option value="Low">Low</option></select>';
      
// Populate Assignee dropdown with JavaScript
var assigneeDropdown = cellAssignee.querySelector('select');

<?php
// Sample PHP code to fetch employees directly in JavaScript (not recommended)
$conn = new mysqli("localhost", "root", "root", "studentinfo");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($projectDetails) {
    // Fetch team_id associated with the project
    $teamIdQuery = "SELECT team_id FROM projectsnew WHERE project_id = $projectId";
    $teamIdResult = $conn->query($teamIdQuery);

    if (!$teamIdResult) {
        echo "Error fetching team ID: " . $conn->error;
        exit();
    }

    $teamIdRow = $teamIdResult->fetch_assoc();
    $teamId = $teamIdRow['team_id'];

    // Fetch team members for the selected project
    $sql = "SELECT et.emp_ID, el.emp_name
            FROM employee_teams et
            JOIN employees_login el ON et.emp_ID = el.emp_ID
            WHERE et.team_id = $teamId";

    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        echo "var option = document.createElement('option');";
        echo "option.value = '" . $row['emp_ID'] . "';";
        echo "option.text = '" . $row['emp_name'] . "';";
        echo "assigneeDropdown.add(option);";
    }
}
?>
      // Add Assign Task button
        var assignTaskButton = document.createElement("button");
        assignTaskButton.innerHTML = "Assign Task";
        assignTaskButton.className = "btn btn-primary"; // Assign the custom class
        assignTaskButton.onclick = function () {
            // Store the current row
            currentRow = this.closest('tr');

            // Check if currentRow is valid before calling submitForm
            if (currentRow) {
                submitForm();
            }
        };
        cellAction.appendChild(assignTaskButton);
    }

    function submitForm() {
    // Set cell values in the current row
    var title = currentRow.cells[0].querySelector('div').textContent.trim();
    var details = currentRow.cells[1].querySelector('div').textContent.trim();
    var assignee = currentRow.cells[2].querySelector('select').value.trim();
    var dueDate = currentRow.cells[3].querySelector('input').value.trim();
    var priority = currentRow.cells[4].querySelector('select').value.trim();

    // Check if any field is empty
    if (title === '' || details === '' || assignee === '' || dueDate === '' || priority === '') {
        alert('Please fill in all the details before assigning the task.');
        return;
    }

    // Create a FormData object to send data via AJAX
    var formData = new FormData();
    formData.append('title', title);
    formData.append('details', details);
    formData.append('assignee', assignee);
    formData.append('dueDate', dueDate);
    formData.append('priority', priority);
    formData.append('project_id', <?php echo $projectId; ?>); // Assuming $projectId is available in your JavaScript

    // Send the data using AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'addtask.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status === 200) {
            // Parse the JSON response to get the employee name
            var response = JSON.parse(xhr.responseText);
            var assigneeName = response.assigneeName;

            // Update cell values in the current row
            currentRow.cells[0].textContent = title;
            currentRow.cells[1].textContent = details;
            currentRow.cells[2].textContent = assigneeName; // Update with the employee name
            currentRow.cells[3].textContent = dueDate;
            currentRow.cells[4].textContent = priority;

            // Clear the input fields in the currentRow
            currentRow.cells[0].querySelector('div').textContent = '';
            currentRow.cells[1].querySelector('div').textContent = '';
            currentRow.cells[2].querySelector('select').textContent = '';
            currentRow.cells[3].querySelector('input').value = '';
            currentRow.cells[4].querySelector('select').textContent = '';

            // Remove the "Assign Task" button
            removeAssignTaskButton(currentRow);

            // Add a label indicating task assignment
            var assignmentLabel = document.createElement("span");
            assignmentLabel.textContent = "Task Assigned";
            currentRow.cells[5].appendChild(assignmentLabel);

            // You can add other logic or UI changes here if needed
        } else {
            console.log('Task addition failed');
            console.log(xhr.responseText);

        }
    };

    // Convert FormData to a query string
    var formDataString = Array.from(formData).map(entry => `${entry[0]}=${entry[1]}`).join('&');

    // Send the data using AJAX
    xhr.send(formDataString);
}

function addTaskRow(title, details, assignee, dueDate, priority) {
    // Create a new row and append it to the table
    var tableBody = document.getElementById('taskTableBody');
    var newRow = tableBody.insertRow(tableBody.rows.length);

    // Add cells to the new row
    var cellTitle = newRow.insertCell(0);
    var cellDetails = newRow.insertCell(1);
    var cellAssignee = newRow.insertCell(2);
    var cellDueDate = newRow.insertCell(3);
    var cellPriority = newRow.insertCell(4);
    var cellAction = newRow.insertCell(5); // New cell for the Assign Task button

    // Set cell values in the new row
    cellTitle.textContent = title;
    cellDetails.textContent = details;
    cellAssignee.textContent = assignee;
    cellDueDate.textContent = dueDate;
    cellPriority.textContent = priority;

    // Add Assign Task button to the new row
    var assignTaskButton = document.createElement("button");
    assignTaskButton.innerHTML = "Assign Task";
    assignTaskButton.onclick = function () {
        // Store the current row
        currentRow = this.closest('tr');

        // Check if currentRow is valid before calling submitForm
        if (currentRow) {
            submitForm();
        }
    };
    cellAction.appendChild(assignTaskButton);
}

// Add event listener to the dropdown menu
document.getElementById('inputStatus').addEventListener('change', function() {
    // Submit the form when a selection is made
    document.getElementById('statusForm').submit();
});

</script>
</body>
</html>
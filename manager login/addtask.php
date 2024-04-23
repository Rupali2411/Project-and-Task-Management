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
    $projectId = mysqli_real_escape_string($conn, $projectId);

    $sql = "SELECT * FROM projectsnew WHERE project_id = $projectId";
    $result = $conn->query($sql);

    return ($result->num_rows > 0) ? $result->fetch_assoc() : null;
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
}



$projectId = isset($_GET['project_id']) ? $_GET['project_id'] : null;

// Fetch tasks from the database for the selected project
$sql = "SELECT * FROM tasksdetails WHERE project_id = $projectId";
$result = $conn->query($sql);

$tasks = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
}

// $conn->close();




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
            width: 50%px; 
            margin-left: 250px;
            margin-right: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 40px;
            margin-bottom: 50px;
          

          }
        
          
          .back-btn {
            border: 1.5px solid #5e63e9;
            color: #5e63e9;
            background-color: transparent;
            padding: 5px 12px;
            cursor: pointer;
            border-radius: 7px;
        
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
    background-color: #6610f2; /* Set your desired background color */
    color: #fff; /* Set text color to make it readable */
    border: none;
    width:  122px;
    height: 45px;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer; /* Add cursor style for better user interaction */
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}

/* Style for dropdown options */
#inputStatus option {
    background-color: black; /* Set background color for options */
    color: white; /* Set text color for options */
}

/* On hover styles for dropdown options */
#inputStatus option:hover {
    background-color: #f2f2f2;
}
.form-group-col-md-2{
    padding: 10px;
}
 
.form-group1 div ul {
                list-style-type: disc; /* or your preferred list style */
                padding-left: 20px; /* or adjust as needed */
                margin: 0; /* remove default margin on ul */
}


.card{
    color: #000;
}

.text-left1
{
    margin-left: 80%;
    margin-bottom: 10px;


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
                <!--LOGIN DETAILS Of LOGGED ONE-->
                <div class="user-box">
                    <div class="user-info">
                    <span class="user-emailid"><?php echo $employee['email_id']; ?></span>

                        
                    </div>
                    <a href="login.php" class="logout">
                                <i class='bx bx-log-out'></i>
                        <span class="text">Logout</span>
                    </a>
                </div>

                <!--SIDEMENU-->

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
                <!-- <li>
                    <a href="allteam.php">
                    <i class='bx bxs-group'></i>
                        <span class="text">+Team</span>
                    </a>
                </li> -->
                
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
                    
                     <div class="col-md-3 text-left">
                        <h2>Project Details</h2> 
                        
                    </div>
                    
             
                            <!--UPDATE STATUS-->
                            <div class="form-group col-md-2" >
                                <select class="form-control update-status-dropdown" id="inputStatus" name="status" required>
                                    <option value="" disabled selected>Update Status</option>
                                    <option value="on_track" style="color: #28a745;">&#10004; On track</option>
                                    <option value="off_track" style="color: #dc3545;">&#10006; Off track</option>
                                    <option value="on_hold" style="color: #ffc107;">&#10074; On hold</option>
                                    <option value="at_risk" style="color: #ff6347;">&#9888; At risk</option>
                                    <option value="completed" style="color: #28a745;">&#10004; Completed</option>
                                </select>
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

                    // Example usage
                    if (isset($_GET['project_id'])) {
                        $projectId = $_GET['project_id'];
                        $totalTaskCount = getTotalTaskCount($projectId, $conn);
                    }

                    ?>
       
                

                    
       <div class="col-md-4 text-left">
            <div class="card" style="background-color: #4D1DDE; color:white;">
                <div class="card-body" >Total Tasks</h2>
                    <hr>
                    <p2 class="card-text">
                        <?php echo $totalTaskCount; ?>
                    </p2>
                </div>
               
        </div>    
         

</div>

      <!--TOTAL TASK REPORT-->
      
        
       
             <!--SIDEMENU-->

    
 

           
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

<div class="container1 mt-5">
        <button type="button" class="btn btn-primary" onclick="addTask()">Add Task</button>

        <!-- Table to display tasks -->
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Task Title</th>
                    <th>Task Details</th>
                    <th>Assignee</th>
                    <th>Due Date</th>
                    <th>Priority</th>
                    <th>Assign</th> 
                    <th>View</th> <!-- New column for the View Task button -->
                </tr>
            </thead>
            <tbody id="taskTableBody">
                <?php
                // Display tasks in the table
                foreach ($tasks as $row) {
                    echo "<tr>";
                    echo "<td>{$row['task_title']}</td>";
                    echo "<td>{$row['task_details']}</td>";
                    $assigneeName = getEmployeeNameById($row['assignee'], $conn);
                    echo "<td>{$assigneeName}</td>";

                    echo "<td>{$row['due_date']}</td>";
                    echo "<td>{$row['priority']}</td>";
                    echo "<td><button class='btn btn-primary' onclick='assignTask(this)'>Assign Task</button></td>";

                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <!-- jQuery 3.6.4 -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-LCpCDbU6j5XoT4O5Uz3AMg5Qt/P2wX+XXvJHrOGWU2k=" crossorigin="anonymous"></script> -->

    <!-- Bootstrap dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script>
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
        echo "option.value = '" . $row['emp_ID'] . "';"; // Use emp_name as the option value
        echo "option.text = '" . $row['emp_name'] . "';";
        echo "assigneeDropdown.add(option);";
    }   
    
}
?>

        // Add Assign Task button
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

    function submitForm() {
    // Set cell values in the current row
    var title = currentRow.cells[0].querySelector('div').textContent;
    var details = currentRow.cells[1].querySelector('div').textContent;
    var assigneeId = currentRow.cells[2].querySelector('select').value;
    var dueDate = currentRow.cells[3].querySelector('input').value;
    var priority = currentRow.cells[4].querySelector('select').value;

    // Check if any of the fields is empty
    if (title === '' || details === '' || assigneeId === '' || dueDate === '' || priority === '') {
        alert("Please fill in all details before assigning the task.");
        return;
    }

    // Create a FormData object to send data via AJAX
    var formData = new FormData();
    formData.append('title', title);
    formData.append('details', details);
    formData.append('assignee', assigneeId);
    formData.append('dueDate', dueDate);
    formData.append('priority', priority);
    formData.append('project_id', <?php echo $projectId; ?>);

    // Send the data using AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'addtask.php', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log(xhr.responseText); // Log the response from the server
            if (xhr.responseText.trim() === "Task added successfully") {
                // Fetch and update the employee name immediately
                var assigneeCell = currentRow.cells[2];
                fetchEmployeeName(assigneeId, assigneeCell);

                // Update button text to indicate task assignment
                var assignTaskButton = currentRow.cells[5].querySelector('button');
                assignTaskButton.innerHTML = "Task Assigned";
                assignTaskButton.disabled = true; // Optionally disable the button
            }
        }
    };
    xhr.send(formData);

    // Clear the input fields in the currentRow
    currentRow.cells[0].querySelector('div').textContent = '';
    currentRow.cells[1].querySelector('div').textContent = '';
    currentRow.cells[2].querySelector('select').textContent = '';
    currentRow.cells[3].querySelector('input').value = '';
    currentRow.cells[4].querySelector('select').textContent = '';
}

// Function to fetch and update employee name in the assignee cell
function fetchEmployeeName(empId, assigneeCell) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetchEmployees.php?emp_id=' + empId, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            assigneeCell.textContent = xhr.responseText;
        }
    };
    xhr.send();
}


</script>

</body>
</html>
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

// Check if project_id is provided in the URL
if(isset($_GET['project_id'])) {
    $selectedProjectId = $_GET['project_id'];
    
    // Query to fetch project details based on project_id
   $projectQuery = "SELECT p.*, t.team_name 
                    FROM projectsnew p
                    INNER JOIN teams t ON p.team_id = t.team_id
                    WHERE p.project_id = '$selectedProjectId'";
                    $projectResult = mysqli_query($conn, $projectQuery);
                        
    
                    if($projectResult && mysqli_num_rows($projectResult) > 0) {
                        $project = mysqli_fetch_assoc($projectResult);
                        $selectedProjectName = $project['project_name'];
                        $selectedTeamName = $project['team_name'];
        
        // Completed tasks count
        $completedTaskCountQuery = "SELECT COUNT(*) AS completed_count 
                                    FROM tasksdetails 
                                    WHERE project_id = '$selectedProjectId' 
                                    AND status = 'completed'";
        $completedTaskCountResult = mysqli_query($conn, $completedTaskCountQuery);
        $completedTaskCountRow = mysqli_fetch_assoc($completedTaskCountResult);
        $completedTaskCount = $completedTaskCountRow['completed_count'];

        // Pending tasks count
        $pendingTaskCountQuery = "SELECT COUNT(*) AS pending_count 
                                  FROM tasksdetails 
                                  WHERE project_id = '$selectedProjectId' 
                                  AND status = 'pending' 
                                  AND due_date >= NOW()";
        $pendingTaskCountResult = mysqli_query($conn, $pendingTaskCountQuery);
        $pendingTaskCountRow = mysqli_fetch_assoc($pendingTaskCountResult);
        $pendingTaskCount = $pendingTaskCountRow['pending_count'];

        // Hold tasks count
        $holdTaskCountQuery = "SELECT COUNT(*) AS hold_count 
                               FROM tasksdetails 
                               WHERE project_id = '$selectedProjectId' 
                               AND status = 'hold' 
                               AND due_date >= NOW()";
        $holdTaskCountResult = mysqli_query($conn, $holdTaskCountQuery);
        $holdTaskCountRow = mysqli_fetch_assoc($holdTaskCountResult);
        $holdTaskCount = $holdTaskCountRow['hold_count'];

        // At risk tasks count
        $atRiskTaskCountQuery = "SELECT COUNT(*) AS risk_count 
        FROM tasksdetails 
        WHERE project_id = '$selectedProjectId' 
        AND status = 'risk' 
        AND due_date >= NOW()";

        $atRiskTaskCountResult = mysqli_query($conn, $atRiskTaskCountQuery);
        $atRiskTaskCountRow = mysqli_fetch_assoc($atRiskTaskCountResult);
        $atRiskTaskCount = $atRiskTaskCountRow['risk_count'];

      // Overdue tasks count
        $overdueTaskCountQuery = "SELECT COUNT(*) AS overdue_count 
        FROM tasksdetails 
        WHERE project_id = '$selectedProjectId' 
        AND status NOT IN ('completed') 
        AND due_date < NOW()";

        $overdueTaskCountResult = mysqli_query($conn, $overdueTaskCountQuery);
        $overdueTaskCountRow = mysqli_fetch_assoc($overdueTaskCountResult);
        $overdueTaskCount = $overdueTaskCountRow['overdue_count'];
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

    <link rel="stylesheet" href="dashstyle.css">

    <title>Project Management</title>
    <style>
         body, h1, h2, h3, p, ul, li, table {
    margin: 0;
    padding: 0;
    color: #fff;
}
body{
    color: white;
    background-color: #4C4966;
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

/* background color of list items */
#content main ul.box-info li {
    background-color: rgba(0, 0, 0, 0.3); /* Transparent black color with 30% opacity */
}
.box-info li a{
        margin-bottom: 220px;
    }
    /* space between icon and text */
.box-info li .bx {
    margin-right: 10px; /* Add space between icon and text */
}

.box-info li a {
    text-decoration: none;
    padding: 10px;
    color: black;
    display: flex;
    align-items: center;
    transition: transform 0.3s; /* Add a smooth transition for the zoom effect */
    
}

.box-info li a:hover {
    text-decoration: none;
    color: #fff;
    transform: scale(1.1); /* Add a zoom-in effect on hover */
}

.box-info li h3 {
    font-size: 18px;
    margin-bottom: 5px;
}

.box-info li p {
    font-size: 14px;
    color: #fff; /* Font color set to white */

}
#content main .box-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        grid-gap: 15px;
        margin-top: 26px;

    }

        #content main .box-info li {
            padding: 15px;
            background: var(--light);
            border-radius: 15px;
            display: flex;
            align-items: center;
            grid-gap: 14px;
        }

            #content main .box-info li .bx {
                width:60px;
                height: 60px;
                border-radius: 20px;
                font-size: 36px;
                display: flex;
                justify-content: center;
                align-items: center;
                
            }

            #content main .box-info li:nth-child(1) .bx {
                background: #b595c1;
                box-shadow: 5px 4px 9px  rgba(0, 0, 0, 0.636);            
                color: black;
            }

            #content main .box-info li:nth-child(2) .bx {
                background: #b595c1;
                box-shadow: 5px 4px 9px  rgba(0, 0, 0, 0.636);            
                color: black;
            }

            #content main .box-info li:nth-child(3) .bx {
                background: #b595c1;
                box-shadow: 5px 4px 9px  rgba(0, 0, 0, 0.636);            
                color: black;
            }
            #content main .box-info li:nth-child(4) .bx {
                background: #b595c1;
                box-shadow: 5px 4px 9px  rgba(0, 0, 0, 0.636);            
                color: black;
            }

            #content main .box-info li .text h3 {
                font-size: 20px;
                font-weight: 300;
                color: #B5B6D0;
            }

            #content main .box-info li .text p {
                color: var(--dark);
            }           

.child {
  margin-left: 70px;
  
  /* Apply negative top and left margins to truly center the element */
}

.box-info1 {
    display: flex;
    justify-content: space-around;
    list-style-type: none;
    padding: 0;
}

.box-info1 li {
    flex: 1;
    text-align: center;
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 20px;
    margin: 0 10px;
    height: 170px;
    width: 250px;
    background-color: rgba(0, 0, 0, 0.3); /* Transparent black color with 30% opacity */
}

.count {
    font-size: 46px;
    color: #b595c1;
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

<section id="content">
        <div>
            <a class="projectname">
                <i class='bx-log-out' style="color: white;"></i>
                <span class="text" style="color: white; font-size:32px;"><?php echo isset($selectedProjectName) ? $selectedProjectName : ''; ?></span>
            </a>
            <?php if(isset($selectedTeamName)): ?>
            <p>Team: <?php echo $selectedTeamName; ?></p>
        <?php endif; ?>

            <?php if(mysqli_num_rows($projectResult) > 0): ?>
                <main>
                    <!-- TASK REPORT DASHBOARD -->
                    <ul class="box-info1">
                            <!-- Completed Task count -->
                            <li class="completed-task">
                                <span class="text1">
                                    <h3>Completed Task</h3>
                                    <div class="count"><?php echo isset($completedTaskCount) ? $completedTaskCount : 0; ?></div>
                                </span>
                            </li>
                            <!-- Pending Task count -->
                            <li class="incompleted-task">
                                <span class="text1">
                                    <h3>Pending Task</h3>
                                    <div class="count"><?php echo isset($pendingTaskCount) ? $pendingTaskCount : 0; ?></div>
                                </span>
                            </li>
                            <!-- Overdue Task count -->
                            <li class="overdue-task">
                                <span class="text1">
                                    <h3>Overdue Task</h3>
                                    <div class="count"><?php echo isset($overdueTaskCount) ? $overdueTaskCount : 0; ?></div>
                                </span>
                            </li>
                            <!-- Tasks on Hold count -->
                            <li class="tasks-on-hold">
                                <span class="text1">
                                    <h3>Tasks on Hold</h3>
                                    <div class="count"><?php echo isset($holdTaskCount) ? $holdTaskCount : 0; ?></div>
                                </span>
                            </li>
                    </ul>

                    <?php
                    // Fetch the team ID associated with the project ID
                    $teamIdQuery = "SELECT team_id FROM projectsnew WHERE project_id = '$selectedProjectId'";
                    $teamIdResult = mysqli_query($conn, $teamIdQuery);
                    $teamIdRow = mysqli_fetch_assoc($teamIdResult);
                    $teamId = $teamIdRow['team_id'];

                    // Fetch the employee names belonging to the project's team
                    $employeeQuery = "SELECT DISTINCT e.emp_name AS assignee_name, td.assignee 
                                    FROM tasksdetails td
                                    INNER JOIN projectsnew pn ON td.project_id = pn.project_id
                                    INNER JOIN employees_login e ON td.assignee = e.emp_id
                                    WHERE pn.team_id = '$teamId'";
                    $employeeResult = mysqli_query($conn, $employeeQuery);
                    $employeeNames = [];
                    $incompleteTaskCounts = [];

                    // Iterate over each employee to get their incomplete task count
                    while ($row = mysqli_fetch_assoc($employeeResult)) {
                        $employeeName = $row['assignee_name'];
                        $employeeNames[] = $employeeName;
                        
                        // Query to get the count of incomplete tasks for the current employee
                        $assigneeId = $row['assignee'];
                        $incompleteTaskCountQuery = "SELECT COUNT(*) AS incomplete_count 
                                                    FROM tasksdetails 
                                                    WHERE project_id IN (
                                                        SELECT project_id FROM projectsnew WHERE team_id = '$teamId'
                                                    ) 
                                                    AND assignee = '$assigneeId' 
                                                    AND status IN ('pending', 'hold', 'risk') 
                                                    AND due_date < NOW()";
                        $incompleteTaskCountResult = mysqli_query($conn, $incompleteTaskCountQuery);
                        $incompleteTaskCountRow = mysqli_fetch_assoc($incompleteTaskCountResult);
                        $incompleteTaskCount = $incompleteTaskCountRow['incomplete_count'];
                        
                        // Store the incomplete task count for the current employee
                        $incompleteTaskCounts[] = $incompleteTaskCount;
                    }
                    ?>

            <!-- Additional charts or information -->
            <ul class="box-info">
                <!-- Bar chart for incomplete tasks by project -->
                <li>
                    <div id="barchart">
                        <p>Incomplete tasks by employee</p>
                        <canvas id="chartId" aria-label="chart" height="350" width="250"></canvas>
                    </div>
                </li>
                <!-- Pie chart for tasks by completion status -->
                <li>
                    <div id="piechart" style="margin-left:50px;">
                        <p>Tasks by completion status</p>
                        <canvas id="completedTaskChart" width="500" height="350"></canvas>
                    </div>
                </li>
            </ul>
        </main>
    <?php else: ?>
        <!-- If no tasks assigned for the selected project -->
        <h4 style="margin-left:350px; color:#fff;">No tasks assigned for the selected project.</h4>
    <?php endif; ?>
</div>

    </main>
</section>

<script src="scr.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data for Completed Task Pie Chart
    var completedTaskChartData = {
        labels: ['Completed', 'Incomplete'],
        datasets: [{
            backgroundColor: ['#5356FF', '#E9A8F2'],
            data: [<?php echo isset($completedTaskCount) ? $completedTaskCount : 0; ?>, <?php echo isset($incompleteTaskCount) ? $incompleteTaskCount : 0; ?>],
            borderWidth: 0 // Remove the border
        }]
    };

    // Configure and render Completed Task chart
    var completedTaskChart = new Chart(document.getElementById('completedTaskChart').getContext('2d'), {
        type: 'doughnut',
        data: completedTaskChartData,
        options: {
            responsive: false,
            maintainAspectRatio: false,
            cutoutPercentage: 90, // Adjust the cutout percentage to create a gap
            legend: {
                display: false
            },
            title: {
                display: false
            }
        }
    });

    // Retrieve incomplete task counts and employee names from PHP
    var incompleteTaskCounts = <?php echo json_encode($incompleteTaskCounts); ?>;
    var employeeNames = <?php echo json_encode($employeeNames); ?>;

    // Get the canvas context
    var ctx = document.getElementById('chartId').getContext('2d');

    // Configure the bar chart
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: employeeNames, // Employee names as labels
            datasets: [{
                label: 'Incomplete Tasks',
                data: incompleteTaskCounts, // Incomplete task counts for each employee
                backgroundColor: '#FF78F0', // Background color for bars
                borderColor: '#b595c1', // Border color for bars  
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true 
                    }
                }]
            }
        }
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.1.1/chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.1.1/helpers.esm.min.js"></script>

</body>
</html>
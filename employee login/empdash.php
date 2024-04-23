
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

// Function to calculate task percentage and count for the logged-in employee
function calculateEmployeeTaskPercentageAndCount($status, $employeeId, $conn) {
    $query = "SELECT COUNT(*) AS count 
              FROM tasksdetails 
              WHERE status = '$status' 
              AND assignee = '$employeeId'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $count = $row['count'];

        // Calculate total count for the logged-in employee
        $totalCountQuery = "SELECT COUNT(*) AS total_count 
                            FROM tasksdetails 
                            WHERE assignee = '$employeeId'";
        $totalCountResult = mysqli_query($conn, $totalCountQuery);
        $totalCountRow = mysqli_fetch_assoc($totalCountResult);
        $totalCount = $totalCountRow['total_count'];

        // Calculate percentage
        $percentage = ($totalCount > 0) ? (($count / $totalCount) * 100) : 0;

       // Count completed tasks
            $completedCountQuery = "SELECT COUNT(*) AS completed_count 
            FROM tasksdetails 
            WHERE status = 'completed' 
            AND assignee = '$employeeId'";
        $completedCountResult = mysqli_query($conn, $completedCountQuery);
        $completedCountRow = mysqli_fetch_assoc($completedCountResult);
        $completedCount = $completedCountRow['completed_count'];

        // Count pending tasks (excluding those with a due date passed)
        $pendingCountQuery = "SELECT COUNT(*) AS pending_count 
        FROM tasksdetails 
        WHERE status = 'pending' 
        AND assignee = '$employeeId'
        AND (due_date >= CURDATE() OR due_date IS NULL)";
        $pendingCountResult = mysqli_query($conn, $pendingCountQuery);
        $pendingCountRow = mysqli_fetch_assoc($pendingCountResult);
        $pendingCount = $pendingCountRow['pending_count'];

        // Count hold tasks (excluding those with a due date passed)
        $holdCountQuery = "SELECT COUNT(*) AS hold_count 
        FROM tasksdetails 
        WHERE status = 'hold' 
        AND assignee = '$employeeId'
        AND (due_date >= CURDATE() OR due_date IS NULL)";
        $holdCountResult = mysqli_query($conn, $holdCountQuery);
        $holdCountRow = mysqli_fetch_assoc($holdCountResult);
        $holdCount = $holdCountRow['hold_count'];

        // Count overdue tasks (due date passed)
        $overdueCountQuery = "SELECT COUNT(*) AS overdue_count 
        FROM tasksdetails 
        WHERE (status = 'pending' OR status = 'hold' OR status = 'risk') 
        AND due_date < CURDATE() 
        AND assignee = '$employeeId'";
        $overdueCountResult = mysqli_query($conn, $overdueCountQuery);
        $overdueCountRow = mysqli_fetch_assoc($overdueCountResult);
        $overdueCount = $overdueCountRow['overdue_count'];

        // Calculate total count for the logged-in employee
        $totalCount = $completedCount + $pendingCount + $holdCount + $overdueCount;

        // Calculate percentage based on the respective count
        $percentage = 0; // Initialize percentage
        switch ($status) {
        case 'completed':
        $percentage = ($totalCount > 0) ? (($completedCount / $totalCount) * 100) : 0;
        break;
        case 'pending':
        $percentage = ($totalCount > 0) ? (($pendingCount / $totalCount) * 100) : 0;
        break;
        case 'hold':
        $percentage = ($totalCount > 0) ? (($holdCount / $totalCount) * 100) : 0;
        break;
        case 'overdue':
        $percentage = ($totalCount > 0) ? (($overdueCount / $totalCount) * 100) : 0;
        break;
        default:
        break;
        }

        return [
        'percentage' => $percentage,
        'completed_count' => $completedCount,
        'pending_count' => $pendingCount,
        'hold_count' => $holdCount,
        'overdue_count' => $overdueCount
        ];
        }
    
}

// Usage example for the logged-in employee
if (isset($_SESSION['emp_id'])) {
    $employeeId = $_SESSION['emp_id'];
    // Completed tasks
    $completedTaskData = calculateEmployeeTaskPercentageAndCount('completed', $employeeId, $conn);
    // $completedCount = $completedTaskData['count'];
    $completedPercentage = $completedTaskData['percentage'];

    // Overdue tasks
    $overdueTaskData = calculateEmployeeTaskPercentageAndCount('overdue', $employeeId, $conn);
    // $overdueCount = $overdueTaskData['count'];
    $overduePercentage = $overdueTaskData['percentage']; // Ensure 'percentage' is correctly retrieved
    // Pending tasks
    $pendingTaskData = calculateEmployeeTaskPercentageAndCount('pending', $employeeId, $conn);
    // $pendingCount = $pendingTaskData['count'];
    $pendingPercentage = $pendingTaskData['percentage'];

    // Hold tasks
    $holdTaskData = calculateEmployeeTaskPercentageAndCount('hold', $employeeId, $conn);
    // $holdCount = $holdTaskData['count'];
    $holdPercentage = $holdTaskData['percentage'];
} else {
    echo "Session not started or employee_id not set.";
    exit;
}

// Usage example for the logged-in employee
if (isset($_SESSION['emp_id'])) {
    $employeeId = $_SESSION['emp_id'];
    $taskData = calculateEmployeeTaskPercentageAndCount('completed', $employeeId, $conn);

    $completedCount = $taskData['completed_count'];
    $pendingCount = $taskData['pending_count'];
    $holdCount = $taskData['hold_count'];
    $overdueCount = $taskData['overdue_count'];
    $percentage = $taskData['percentage']; // Get the percentage value

    // echo "Percentage of completed tasks: $percentage%";
} else {
    echo "Session not started or employee_id not set.";
    exit;
}

// Check if the employee ID is set in the session
if (isset($_SESSION['emp_id'])) {
    $empId = $_SESSION['emp_id'];
 // Query to count notifications
 $notificationQuery = "SELECT COUNT(*) AS notificationCount FROM tasksdetails WHERE assignee = '$empId' AND status IN ('pending', 'hold', 'risk') AND due_date < CURDATE()";
 $notificationResult = mysqli_query($conn, $notificationQuery);

 if ($notificationResult) {
     // Fetch the notification count
     $row = mysqli_fetch_assoc($notificationResult);
     $notificationCount = $row['notificationCount'];

     // Set session variable if there are unseen notifications
     if ($notificationCount > 0) {
         $_SESSION['unseen_notifications'] = true;
     }
 } else {
     // Display an error message if fetching the notification count fails
     echo "<li>Error fetching notification count</li>";
 }
} else {
 // Display an error message if the session is not started or emp_id is not set
 echo "<li>Session not started or emp_id not set</li>";
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
h3{
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
.user-emailid{
  color: white;
}

/* BOX INFO STYLES */
.box-info {
    list-style: none;
    display: flex;
    justify-content: space-between;
}

.box-info li {
    flex: 1;
    padding: 15px;
    background: rgb(163, 119, 163);
    color: white;
    border-radius: 5px;
    margin-right: 10px;
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
    padding: 20px;
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
        <main>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                <ul class="box-info">
                    <!-- Completed Task Pie Chart -->
                    <li style="position: relative;">
                        <canvas id="completedTaskChart" width="80" height="80" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></canvas>
                            <a href="#">
                                <i class='bx bxs-file'></i>
                                <span class="text1">
                                    <h3>Completed Task</h3>
                                    <p><?php echo $completedCount; ?></p>
                                </span>
                            </a>
                    </li>


                    <!-- Overdue Task Pie Chart -->
                    <li style="position: relative;">
                        <canvas id="overdueTaskChart" width="100" height="100" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></canvas>
                        <a href="#">
                            <i class='bx bxs-group'></i>
                            <span class="text1">
                                <h3>Overdue Task</h3>
                                <p><?php echo $overdueCount; ?></p>
                            </span>
                        </a>
                    </li>
                </ul>

                <ul class="box-info">
                    <!-- Pending Task Pie Chart -->
                    <li style="position: relative;">
                        <canvas id="pendingTaskChart" width="100" height="100" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></canvas>
                        <a href="#">
                            <i class='bx bx-task'></i>
                            <span class="text1">
                                <h3>Pending task</h3>
                                <p><?php echo $pendingCount; ?></p>
                            </span>
                        </a>
                    </li>

                    <!-- Tasks on Hold Pie Chart -->
                    <li style="position: relative;">
                        <canvas id="holdTaskChart" width="100" height="100" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></canvas>
                        <a href="#">
                            <i class='bx bxs-time'></i>
                            <span class="text1">
                                <h3>Tasks on hold</h3>
                                <p><?php echo $holdCount; ?></p>
                            </span>
                        </a>
                    </li>
                </ul>
<script>
    // Function to redirect to task_progress.php with the corresponding type parameter
    function redirectToTaskProgress(taskType) {
        var typeParam = '';
        switch (taskType) {
            case 'completed':
                typeParam = 'completed';
                break;
            case 'overdue':
                typeParam = 'overdue';
                break;
            case 'pending':
                typeParam = 'pending';
                break;
            case 'hold':
                typeParam = 'hold';
                break;
        }
        // Log the typeParam to check if it's correct
        console.log("Redirecting with taskType:", taskType);
        window.location.href = 'emp_task_progress.php?type=' + typeParam;
    }

 // Data for Completed Task Pie Chart
var completedTaskChartData = {
    labels: ['Completed', 'Remaining'],
    datasets: [{
        backgroundColor: ['#5356FF', '#DDDDDD'],
        data: [<?php echo $percentage; ?>, 100 - <?php echo $percentage; ?>],
        borderWidth: 0 // Remove the border
    }]
};

// Render Completed Task chart
var completedTaskChart = new Chart(document.getElementById('completedTaskChart').getContext('2d'), {
    type: 'doughnut',
    data: completedTaskChartData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutoutPercentage: 0, // Decrease the cutout percentage to create a smaller hole
        legend: {
            display: false
        },
        title: {
            display: false
        },
        onClick: function(event, elements) {
            redirectToTaskProgress('completed');
        }
    }
});

// Data for Overdue Task Pie Chart
var overdueTaskChartData = {
    labels: ['Overdue', 'Remaining'],
    datasets: [{
        backgroundColor: ['#D6589F', '#DDDDDD'],
        data: [<?php echo $overduePercentage; ?>, 100 - <?php echo $overduePercentage; ?>],
        borderWidth: 0 // Remove the border
    }]
};

// Render Overdue Task chart
var overdueTaskChart = new Chart(document.getElementById('overdueTaskChart').getContext('2d'), {
    type: 'doughnut',
    data: overdueTaskChartData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutoutPercentage: 30, // Adjust the cutout percentage to create a smaller hole
        legend: {
            display: false
        },
        title: {
            display: false
        },
        onClick: function(event, elements) {
            redirectToTaskProgress('overdue');
        }
    }
});


// Data for Pending Task Pie Chart
var pendingTaskChartData = {
    labels: ['Pending', 'Remaining'],
    datasets: [{
        backgroundColor: ['#FFA500', '#DDDDDD'],
        data: [<?php echo $pendingPercentage; ?>, 100 - <?php echo $pendingPercentage; ?>],
        borderWidth: 0 // Remove the border
    }]
};

// Render Pending Task chart
var pendingTaskChart = new Chart(document.getElementById('pendingTaskChart').getContext('2d'), {
    type: 'doughnut',
    data: pendingTaskChartData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutoutPercentage: 30, // Adjust the cutout percentage to create a smaller hole
        legend: {
            display: false
        },
        title: {
            display: false
        },
        onClick: function(event, elements) {
            redirectToTaskProgress('pending');
        }
    }
});

// Data for Tasks on Hold Pie Chart
var holdTaskChartData = {
    labels: ['Hold', 'Remaining'],
    datasets: [{
        backgroundColor: ['#FF4500', '#DDDDDD'],
        data: [<?php echo $holdPercentage; ?>, 100 - <?php echo $holdPercentage; ?>],
        borderWidth: 0 // Remove the border
    }]
};

// Render Tasks on Hold chart
var holdTaskChart = new Chart(document.getElementById('holdTaskChart').getContext('2d'), {
    type: 'doughnut',
    data: holdTaskChartData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutoutPercentage: 30, // Adjust the cutout percentage to create a smaller hole
        legend: {
            display: false
        },
        title: {
            display: false
        },
        onClick: function(event, elements) {
            redirectToTaskProgress('hold');
        }
    }
});

</script>
    </main>
  </section>
</body>
</html>

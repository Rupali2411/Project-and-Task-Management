<?php include("dbconn.php"); 


// Start the session
session_start();

include("dbconn.php");

// Assume emp_id is passed through a session variable after login
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





// Check if manager_id is set in the session
if (isset($_SESSION['manager_id'])) {
    $managerId = $_SESSION['manager_id'];

    // Function to calculate task percentage for the logged-in manager
    function calculateManagerTaskPercentage($status, $managerId, $conn) {
        $query = "SELECT COUNT(*) AS count 
                  FROM tasksdetails 
                  WHERE status = '$status' 
                  AND project_id IN (SELECT project_id FROM projectsnew WHERE manager_id = '$managerId')";
        $result = mysqli_query($conn, $query);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $count = $row['count'];

            $totalCountQuery = "SELECT COUNT(*) AS total_count 
                                FROM tasksdetails 
                                WHERE project_id IN (SELECT project_id FROM projectsnew WHERE manager_id = '$managerId')";
            $totalCountResult = mysqli_query($conn, $totalCountQuery);
            $totalCountRow = mysqli_fetch_assoc($totalCountResult);
            $totalCount = $totalCountRow['total_count'];

            return ($totalCount > 0) ? (($count / $totalCount) * 100) : 0;
        } else {
            return 0;
        }
    }
// Function to calculate task percentage and count for the logged-in manager
function calculateManagerTaskPercentageAndCount($status, $managerId, $conn) {
    $query = "SELECT COUNT(*) AS count 
              FROM tasksdetails 
              WHERE status = '$status' 
              AND project_id IN (SELECT project_id FROM projectsnew WHERE manager_id = '$managerId')";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $count = $row['count'];

        // Calculate total count for the logged-in manager
        $totalCountQuery = "SELECT COUNT(*) AS total_count 
                            FROM tasksdetails 
                            WHERE project_id IN (SELECT project_id FROM projectsnew WHERE manager_id = '$managerId')";
        $totalCountResult = mysqli_query($conn, $totalCountQuery);
        $totalCountRow = mysqli_fetch_assoc($totalCountResult);
        $totalCount = $totalCountRow['total_count'];

        return [
            'percentage' => ($totalCount > 0) ? (($count / $totalCount) * 100) : 0,
            'count' => $count,
            'total_count' => $totalCount
        ];
    } else {
        return [
            'percentage' => 0,
            'count' => 0,
            'total_count' => 0
        ];
    }
}

// Calculate counts and percentages for each task status for the logged-in manager
$completedTaskData = calculateManagerTaskPercentageAndCount('completed', $managerId, $conn);
$completedTaskPercentage = $completedTaskData['percentage'];
$completedCount = $completedTaskData['count'];
$completedTotalCount = $completedTaskData['total_count'];

$pendingTaskData = calculateManagerTaskPercentageAndCount('pending', $managerId, $conn);
$pendingTaskPercentage = $pendingTaskData['percentage'];
$pendingCount = $pendingTaskData['count'];
$pendingTotalCount = $pendingTaskData['total_count'];

$overdueTaskData = calculateManagerTaskPercentageAndCount('overdue', $managerId, $conn);
$overdueTaskPercentage = $overdueTaskData['percentage'];
$overdueCount = $overdueTaskData['count'];
$overdueTotalCount = $overdueTaskData['total_count'];

$holdTaskData = calculateManagerTaskPercentageAndCount('hold', $managerId, $conn);
$holdTaskPercentage = $holdTaskData['percentage'];
$holdCount = $holdTaskData['count'];
$holdTotalCount = $holdTaskData['total_count'];


    // Calculate percentages for each task status for the logged-in manager
    $completedTaskPercentage = calculateManagerTaskPercentage('completed', $managerId, $conn);
    $pendingTaskPercentage = calculateManagerTaskPercentage('pending', $managerId, $conn);
    $overdueTaskPercentage = calculateManagerTaskPercentage('overdue', $managerId, $conn);
    $holdTaskPercentage = calculateManagerTaskPercentage('hold', $managerId, $conn);

    // Function to calculate the count of overdue tasks for the logged-in manager
    function calculateManagerOverdueCount($managerId, $conn) {
        $today = date("Y-m-d"); // Get today's date
        $query = "SELECT COUNT(*) AS overdue_count 
                  FROM tasksdetails 
                  WHERE project_id IN (SELECT project_id FROM projectsnew WHERE manager_id = '$managerId') 
                  AND due_date < '$today' 
                  AND status != 'completed'";
        $result = mysqli_query($conn, $query);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['overdue_count'];
        } else {
            // Handle errors
            return 0; // Set default value if query fails
        }
    }

    // Calculate the count of overdue tasks for the logged-in manager
    $overdueCount = calculateManagerOverdueCount($managerId, $conn);

    mysqli_close($conn);

    // Now you can use $completedTaskPercentage, $pendingTaskPercentage, $overdueTaskPercentage, $holdTaskPercentage, and $overdueCount to display the progress in pie charts.
} else {
    echo "Session not started or manager_id not set.";
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

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


#contentToConvert {
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
#contentToConvert main ul.box-info li {
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
#contentToConvert main .box-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        grid-gap: 15px;
        margin-top: 26px;

    }

        #contentToConvert main .box-info li {
            padding: 15px;
            background: var(--light);
            border-radius: 15px;
            display: flex;
            align-items: center;
            grid-gap: 14px;
        }

            #contentToConvert main .box-info li .bx {
                width:60px;
                height: 60px;
                border-radius: 20px;
                font-size: 36px;
                display: flex;
                justify-content: center;
                align-items: center;
                
            }

            #contentToConvert main .box-info li:nth-child(1) .bx {
                background: #b595c1;
                box-shadow: 5px 4px 9px  rgba(0, 0, 0, 0.636);            
                color: black;
            }

            #contentToConvert main .box-info li:nth-child(2) .bx {
                background: #b595c1;
                box-shadow: 5px 4px 9px  rgba(0, 0, 0, 0.636);            
                color: black;
            }

            #contentToConvert main .box-info li:nth-child(3) .bx {
                background: #b595c1;
                box-shadow: 5px 4px 9px  rgba(0, 0, 0, 0.636);            
                color: black;
            }
            #contentToConvert main .box-info li:nth-child(4) .bx {
                background: #b595c1;
                box-shadow: 5px 4px 9px  rgba(0, 0, 0, 0.636);            
                color: black;
            }

            #contentToConvert main .box-info li .text h3 {
                font-size: 20px;
                font-weight: 300;
                color: #B5B6D0;
            }

            #contentToConvert main .box-info li .text p {
                color: var(--dark);
            }
            

</style>

</head>

<body>

     <!--SIDEMENU-->    
     
 <!-- SIDEBAR MENU-->
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
                
                <li class="">
                    <a href="dash.php">
                        <i class='bx bxs-dashboard'></i>
                        <span class="text">Dashboard</span>
                    </a>
                </li>
                <li>
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
                        <span class="text">+To do</span>
                    </a>
                </li>
                
                
            </ul>

    </section>
    <!--SIDEMENU-->


    <div>
        <button id="downloadPdf" style="margin-left: 250px; margin-top:50px;" class="btn btn-primary">Download PDF</button>
    </div>
    <!-- CONTENT -->
    <section  id="contentToConvert">
        <main>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <ul class="box-info">
                <!-- Completed Task Pie Chart -->
                <li style="position: relative;">
                    <canvas id="completedTaskChart" width="100" height="100" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></canvas>
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
                    <!-- JavaScript for rendering pie charts -->
                    <script>
                        // Function to redirect to task_progress.php with the corresponding type parameter
                    function redirectToTaskProgress(taskType) {
                        var typeParam = '';
                        switch (taskType) {
                            case 'Completed':
                                typeParam = 'completed';
                                break;
                            case 'Overdue':
                                typeParam = 'overdue';
                                break;
                            case 'Pending':
                                typeParam = 'pending';
                                break;
                            case 'Hold':
                                typeParam = 'hold';
                                break;
                        }
                        // Log the typeParam to check if it's correct
                        console.log("Redirecting with taskType:", taskType);
                        window.location.href = 'task_progress.php?type=' + taskType;
                    }

                        // Data for Completed Task Pie Chart
                        var completedTaskChartData = {
                            labels: ['Completed', 'Remaining'],
                            datasets: [{
                                backgroundColor: ['#5356FF', '#DDDDDD'],
                                data: [<?php echo $completedTaskPercentage; ?>, 100 - <?php echo $completedTaskPercentage; ?>],
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
                            cutoutPercentage: 80, // Adjust the cutout percentage to create a gap
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
                                data: [<?php echo $overdueCount; ?>, <?php echo $overdueTotalCount - $overdueCount; ?>],
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
                            cutoutPercentage: 80, // Adjust the cutout percentage to create a gap
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
                                backgroundColor: ['#b595c1', '#DDDDDD'],
                                data: [<?php echo $pendingTaskPercentage; ?>, 100 - <?php echo $pendingTaskPercentage; ?>],
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
                            cutoutPercentage: 80, // Adjust the cutout percentage to create a gap
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

                        // Data for Hold Task Pie Chart
                        var holdTaskChartData = {
                            labels: ['Hold', 'Remaining'],
                            datasets: [{
                                backgroundColor: ['#7469B6', '#DDDDDD'],
                                data: [<?php echo $holdTaskPercentage; ?>, 100 - <?php echo $holdTaskPercentage; ?>],
                                borderWidth: 0 // Remove the border
                            }]
                        };

                        // Render Hold Task chart
                    var holdTaskChart = new Chart(document.getElementById('holdTaskChart').getContext('2d'), {
                        type: 'doughnut',
                        data: holdTaskChartData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutoutPercentage: 80, // Adjust the cutout percentage to create a gap
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


    <script>
    // Function to download the specific section as PDF
    function downloadPdf() {
        // Specify the element to be converted to PDF
        const element = document.getElementById("contentToConvert");

        // Configure the PDF options (optional)
        const options = {
            margin: 0.5, // Set margins (in inches)
            filename: 'report.pdf', // Set the PDF filename
            image: { type: 'jpg', quality: 0.98 }, // Specify image settings
            html2canvas: { scale: 2 }, // Scale HTML elements to improve quality
            jsPDF: { unit: 'in', format: 'a3', orientation: 'landscape' }
        };

        // Generate the PDF
        html2pdf()
            .from(element)
            .set(options)
            .save(); // Save the PDF
    }

    // Add click event listener to the download button
    document.getElementById("downloadPdf").addEventListener("click", downloadPdf);
</script>




</body>

</html>

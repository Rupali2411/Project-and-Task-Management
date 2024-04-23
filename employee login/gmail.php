
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

// Query to fetch overdue tasks for the employee TYBCA231
$overdueQuery = "SELECT * FROM tasksdetails WHERE assignee = 'TYBCA231' AND status = 'pending' AND due_date < CURDATE()";
$overdueResult = mysqli_query($conn, $overdueQuery);

$overdueTasks = [];
if ($overdueResult && mysqli_num_rows($overdueResult) > 0) {
    // Fetch overdue tasks
    $overdueTasks = mysqli_fetch_all($overdueResult, MYSQLI_ASSOC);
}

// Function to send notification (replace this with your actual notification sending mechanism)
function send_notification($recipient, $message) {
    // Replace this with your actual notification sending mechanism
    // echo "Sending notification to $recipient: $message";
}
// Iterate over the overdue tasks and send notifications
foreach ($overdueTasks as $task) {
    $recipient = $task['assignee'];
    $taskTitle = $task['task_title'];
    $dueDate = $task['due_date'];
    $message = "Your task '$taskTitle' with due date $dueDate is overdue. Please complete it as soon as possible.";
    
    // Call the send_notification function
    send_notification($recipient, $message);
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
        :root {
    --Red: hsl(1, 90%, 64%);
    --Blue: hsl(219, 85%, 26%);
    --White: hsl(0, 0%, 100%);
    --Very_light_grayish_blue: hsl(210, 60%, 98%);
    --Light_grayish_blue1: hsl(211, 68%, 94%);
    --Light_grayish_blue2: hsl(205, 33%, 90%);
    --Grayish_blue: hsl(219, 14%, 63%);
    --Dark_grayish_blue: hsl(219, 12%, 42%);
    --Very_dark_blue: hsl(224, 21%, 14%);
}

* {
    box-sizing: border-box;
}


         body, h1, h2, ul, li, table {
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

main{
    gap: 10px;
    background-color: var(--White);
    width: 60%;
    padding: 1rem 3rem;
    display: flex;
    border-radius: 10px;
    flex-direction: column;
    margin: 5%;
}

header{
    display: flex;
    justify-content: space-between;
}
    
img{margin: 1rem; width: 10%; float: left;}

header p:first-child{font-size: 20px ; font-weight: 800;}

.alert{
    padding: 0 10px; color: var(--White);
    background-color: var(--Blue);
    border-radius: 5px;
}

main div{border-radius: 5px;}


small{color:  var(--Grayish_blue);}


main div div{line-height: 1rem;}

fieldset{border-radius:  5px; margin: 1rem; padding: 1rem 2rem;}

fieldset:hover{background-color: var(--Light_grayish_blue1);}

.club{color: var(--Blue);}
.event{color:  var(--Dark_grayish_blue);}
.chess{float: right;}

.kim{
    display: flex;
    justify-content: space-between;
}

.kim div img{width: 20%;}

.kim p{width: 160%;}


.jacob,
.angela,
.mark{background-color: var(--Light_grayish_blue1);}

.span:hover:not(.alert){color: var(--Blue);}

.dot{
    margin-left: 10px;
    height: 10px;
    width: 10px;
    background-color: var(--Red);
    border-radius: 50%;
    display: inline-block;
}

main div{
    cursor: pointer;
}

.toogle:hover{cursor: pointer; color: var(--Blue);}

/* Style for the blue dot */
.blue-dot {
    display: inline-block;
    width: 10px;
    height: 10px;
    background-color: blue;
    border-radius: 50%;
    margin-left: 865px; /* Adjust as needed */
    margin-bottom: 20px;
}
.custom-popup {
    display: none;
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #f0f0f0;
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    z-index: 9999;
    width: 80%;
    max-width: 750px; /* Adjust the maximum width as needed */
    height: 58px;
}

.popup-content {
    text-align: center;
    color: #333; /* Text color */
}

.close-btn {
    position: absolute;
    top: 5px;
    right: 10px;
    cursor: pointer;
    font-size: 20px;
    color: #777; /* Close button color */
}

.close-btn:hover {
    color: #333; /* Close button hover color */
}

.notify {
    margin-top: 10px;
    color: #333; /* Text color */
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
                <!--LOGIN DETAILS Of LOGGED ONE-->
                <div class="user-box">
                    <div class="user-info">
                    <span class="user-emailid" style="color:#fff";><?php echo $employee['email_id']; ?></span>

                        
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
                    <a href="empdash.php">
                        <i class='bx bxs-dashboard'></i>
                        <span class="text">Dashboard</span>
                    </a>
                </li>
                <script>
// Function to mark all notifications as read
function markAllAsRead() {
    function markAllAsRead() {
    var xhr = new XMLHttpRequest(); // Create a new XMLHttpRequest object
    xhr.open("GET", "mark_notification_As_read.php", true); // Open a connection to the PHP script
    xhr.send(); // Send the request
    // Handle the response if needed
}
    <?php
    // Update session variable to mark all notifications as read
    $_SESSION['read_notifications'] = array();
    ?>

    // Remove the count of notifications from the inbox
    var inboxNotification = document.getElementById('inbox-notification');
    if (inboxNotification) {
        inboxNotification.innerHTML = `
            <a href='gmail.php'>
                <i class='bx bx-bell'></i>
                <span class='text'>Inbox</span>
            </a>
        `;
    }
}
</script>

<?php
// Check if the "Mark all as read" button has been clicked
if (isset($_GET['mark_all_read'])) {
    $_SESSION['mark_as_read_clicked'] = true;
}
?>

<li id='inbox-notification'>
    <a href='gmail.php'>
        <i class='bx bx-bell'></i>
        <span class='text'>Inbox</span>
        <?php
        // Display the inbox count if the "Mark all as read" button has not been clicked
        if (!isset($_SESSION['mark_as_read_clicked'])) {
            // Query to count notifications
            $notificationQuery = "SELECT COUNT(*) AS notificationCount FROM tasksdetails WHERE assignee = '$empId' AND status IN ('pending', 'hold', 'risk') AND due_date < CURDATE()";
            $notificationResult = mysqli_query($conn, $notificationQuery);

            if ($notificationResult) {
                // Fetch the notification count
                $row = mysqli_fetch_assoc($notificationResult);
                $notificationCount = $row['notificationCount'];

                // Display the notification count badge if there are any notifications
                if ($notificationCount > 0) {
                    echo "<span class='badge'>$notificationCount</span>";
                }
            } else {
                // Display an error message if fetching the notification count fails
                echo "Error fetching notification count";
            }
        }
        ?>
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
    <main class="container">
    <div class="custom-popup" id="popup">
        <div class="popup-content">
            <span class="close-btn" onclick="closePopup()">&times;</span>
            <p class="notify">You have tasks with approaching or overdue due dates. Please check your task list for details.</p>
        </div>
    </div>

    <header class="d-flex justify-content-between align-items-center mb-3">
        <h2 style="color: black;">Notifications <span class="alert badge bg-secondary" id="notificationCount"></span></h2>
        <button type="button" class="btn btn-outline-primary toggle" onclick="markAllAsRead()">Mark all as read</button>
    </header>

    <?php
    // Your database connection code here

    // Check if the employee ID is set in the session
    if (isset($_SESSION['emp_id'])) {
        $empId = $_SESSION['emp_id'];

        // Query to fetch tasks with approaching or overdue due dates for the current employee
        $query = "SELECT * FROM tasksdetails WHERE assignee = '$empId' AND due_date <= DATE_ADD(CURDATE(), INTERVAL 1 DAY)";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            // Output JavaScript code to show notification pop-up after 5 seconds
            echo "<script>";
            echo "setTimeout(function() {";
            echo "showPopup();";
            echo "}, 5000);"; // 5000 milliseconds = 5 seconds
            echo "</script>";

            // Loop through each task and display notification
            while ($task = mysqli_fetch_assoc($result)) {
                echo "<div class='notification card mb-3'>";
                echo "<div class='card-body'>";
                echo "<p>";
                echo "<span class='fw-bold'>Task Title: </span>{$task['task_title']} - Due Date: {$task['due_date']}";
                echo "</p>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<div class='notification card mb-3'>";
            echo "<div class='card-body'>";
            echo "<p>Nothing to see over here!</p>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "Session not started or emp_id not set.";
    }
    ?>
</main>

<script>
    // Function to show the pop-up notification
    function showPopup() {
        // Display the pop-up
        document.getElementById("popup").style.display = "block";
    }

    // Function to close the pop-up notification
    function closePopup() {
        // Hide the pop-up
        document.getElementById("popup").style.display = "none";
    }
</script>

</section>


</body>

</html>

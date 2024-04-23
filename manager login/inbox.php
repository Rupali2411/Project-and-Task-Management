
<?php
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


.toogle:hover{cursor: pointer; color: var(--Blue);}
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
                    <span class="user-emailid" style="color: #fff;"><?php echo $employee['email_id']; ?></span>                  
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
                        <span class="text">+To Do</span>
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
        <p class="notify">You have projects with approaching due dates. Please check your project list for details.</p>
    </div>
</div>

    <button type="button" class="btn btn-outline-primary toggle">Mark all as read</button>
</header>

<?php
// Your database connection code here

// Check if the manager ID is available in the session
if(isset($_SESSION['manager_id'])) {
    $managerId = $_SESSION['manager_id'];

    // Query to retrieve projects with due dates approaching for the current manager
    $query = "SELECT * FROM projectsnew 
              WHERE manager_id = '$managerId' 
              AND due_date <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Loop through each project and display notification
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='notification card mb-3' id='notification{$row['project_id']}'>";
            echo "<div class='card-body'>";
            echo "<p>Project <span class='project-name'>{$row['project_name']}</span> is approaching its due date (<span class='due-date'>{$row['due_date']}</span>).</p>";
            echo "<small class='text-muted'>Due in " . calculateDaysRemaining($row['due_date']) . " days</small>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<div class='notification card mb-3'>No projects with approaching due dates for the current manager.</div>";
    }
} else {
    echo "<div class='notification card mb-3'>Manager ID not found in session data.</div>";
}

// Function to calculate days remaining until the due date
function calculateDaysRemaining($dueDate) {
    $dueDateTime = strtotime($dueDate);
    $currentDateTime = time();
    $secondsRemaining = $dueDateTime - $currentDateTime;
    $daysRemaining = floor($secondsRemaining / (60 * 60 * 24));
    return $daysRemaining;
}
?>

<?php
// Your database connection code here

// Check if the manager ID is available in the session
if(isset($_SESSION['manager_id'])) {
    $managerId = $_SESSION['manager_id'];

    // Query to retrieve projects with due dates approaching for the current manager
    $query = "SELECT * FROM projectsnew 
              WHERE manager_id = '$managerId' 
              AND due_date <= DATE_ADD(CURDATE(), INTERVAL 1 DAY)";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Output JavaScript code to show notification pop-up after 5 seconds
        echo "<script>";
        echo "setTimeout(function() {";
        // echo "alert('You have projects with approaching due dates. Please check your project list for details.');";
        echo "}, 5000);"; // 5000 milliseconds = 5 seconds
        echo "</script>";
    }
}
?>

<script>
function showPopup() {
    var popup = document.getElementById("popup");
    popup.style.display = "block";
}

function closePopup() {
    var popup = document.getElementById("popup");
    popup.style.display = "none";
}

// Show the pop-up after 5 seconds
setTimeout(showPopup, 5000);

</script>


    </main>

</section>
</body>
</html>
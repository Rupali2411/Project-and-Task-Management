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

if (isset($_SESSION['manager_id'])) {
    $managerId = $_SESSION['manager_id'];

    // Query to fetch manager details
    $query = "SELECT * FROM managers_login WHERE manager_id = '$managerId'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Check if form is submitted to add a new task
        if (isset($_POST['add'])) {
            $task = $_POST['task'];
            // Insert the task associated with the manager
            $insertQuery = "INSERT INTO todo_manager (task, status, manager_id) VALUES ('$task', '', '$managerId')";
            $insertResult = mysqli_query($conn, $insertQuery);
            if ($insertResult) {
                // Redirect to prevent form resubmission
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            } else {
                echo "Error adding task: " . mysqli_error($conn);
            }
        }

        // Fetch tasks associated with this manager
        $tasksQuery = "SELECT * FROM todo_manager WHERE manager_id = '$managerId' ORDER BY task_id ASC";
        $tasksResult = mysqli_query($conn, $tasksQuery);
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
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>

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
.col-md-6{
    margin-top: 50px;
}

.btn-primary{
    margin-left: 20px;

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

<div class="col-md-3"></div>
    <div class="col-md-6 well"> 
        <h3 class="text-primary">To Do List App</h3>
            <hr style="border-top:1px dotted #ccc;"/>
                <div class="col-md-2"></div>
                    <div class="col-md-8">
        <center>
            <form method="POST" class="form-inline">
                <input type="text" class="form-control" name="task" required/>
                <button class="btn btn-primary form-control" name="add">Add To Do</button>
            </form>
        </center>
    </div>
    <br /><br /><br />
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Task</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if (isset($tasksResult) && $tasksResult && mysqli_num_rows($tasksResult) > 0) {
                    $count = 1;
                    while($fetch = mysqli_fetch_assoc($tasksResult)){
            ?>
            <tr>
                <td><?php echo $count++?></td>
                <td><?php echo $fetch['task']?></td>
                <td><?php echo $fetch['status']?></td>
                <td colspan="2">
                    <center>
                        <?php
                            if($fetch['status'] != "Done"){
                                echo '<a href="update_task.php?task_id='.$fetch['task_id'].'" class="btn btn-success"><span class="glyphicon glyphicon-check"></span></a> |';
                            }
                        ?>
                        <a href="incomplete_query.php?task_id=<?php echo $fetch['task_id']?>" class="btn btn-warning"><span class="glyphicon glyphicon-remove"></span> pending</a>
                         <a href="delete_query.php?task_id=<?php echo $fetch['task_id']?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></a>
                    </center>
                </td>
            </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='4'>No tasks found for this manager.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
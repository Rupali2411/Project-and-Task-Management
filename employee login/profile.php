<?php
// Start the session
session_start();

include("dbconn.php");

// Assume emp_id is passed through a session variable after login
if (isset($_SESSION['emp_id'])) {
    $empId = $_SESSION['emp_id'];

    // Query to fetch employee details
    // Query to fetch employee details
        $query = "SELECT * FROM employees_login WHERE emp_id = '$empId'";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            echo "Error: " . mysqli_error($conn);
            exit;
        }

        if (mysqli_num_rows($result) > 0) {
            $employee = mysqli_fetch_assoc($result);
        } else {
            echo "Employee not found for ID: $empId";
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/boxicons/2.0.7/css/boxicons.min.css">
    
    <!-- My CSS -->
    <link rel="stylesheet" href="dashstyle.css">
    <title>Employee Profile</title>
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

.container {
            text-align: left; 
            max-width: 600px; 
            height: 550px; 
            width: 765px;          
            margin-top: 50px;
            background-color: #C6CCEC;
            padding: 50px;
            border-radius: 10px;
            align-items: center;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        }
        h2,p {
            color: black;
        }
        .form-label {
            font-weight: bold;
            color: black;
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
.brand p, .brand {
    color: #fff;
}

.btn-custom {
            background-color: white; 
            border-color:  #6f42c1;
            display: block;
            margin: 0 auto;
            height: 45px;
            width: 95px;
            
        }
        
        /* background color of the submit button on hover */
        .btn-custom:hover {
                 background-color:#3223619e;
                 border-color:  #3223619e;
        
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
            <div class="container">
            <h2 class="text-center mb-4"><i class="bi bi-person-fill"></i> <span id="text">Employee</span></h2>
                <div class="row  mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Employee ID</label>
                        <p><?php echo $employee['emp_id']; ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Employee Name</label>
                        <p><?php echo $employee['emp_name']; ?></p>
                    </div>
                </div>
                <div class="row justify-content-center mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Contact</label>
                        <p><?php echo $employee['contact']; ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" >Email Address</label>
                        <p ><?php echo $employee['email_id']; ?></p>
                    </div>
                </div>
                <div class="row justify-content-center mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Department</label>
                        <p><?php echo $employee['department']; ?></p>
                    </div>
                    
                </div>
                <div class="row">
                            <div class="col-12 mx-auto text-center" style="padding-top: 30px;">
                            <a href="editemp.php" button type="submit" class="btn btn-custom" name="login">Edit</button></a>
                </div>
            </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-rbsW6jo5C1N/PFOkiAa0QOOg/uBqVyI75SPNWb5HRxrd1HzS9WE5I1C0aQtiS1M" crossorigin="anonymous"></script>
</body>
</html>

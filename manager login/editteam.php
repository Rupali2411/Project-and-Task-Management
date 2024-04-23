<?php
include("dbconn.php");
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




  if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['editid'])) {
      $teamId = $_GET['editid'];

      // Fetch team details
      $teamQuery = "SELECT * FROM teams WHERE team_id = $teamId";
      $teamResult = mysqli_query($conn, $teamQuery);
      $team = mysqli_fetch_assoc($teamResult);

      // Fetch employees in the team
      $teamEmployeesQuery = "SELECT emp_id FROM employee_teams WHERE team_id = $teamId";
      $teamEmployeesResult = mysqli_query($conn, $teamEmployeesQuery);
      $selectedEmployees = [];
      while ($row = mysqli_fetch_assoc($teamEmployeesResult)) {
          $selectedEmployees[] = $row['emp_id'];
      }
        // Fetch project details for the selected team
        $projectQuery = "SELECT project_id, project_name FROM projectsnew WHERE team_id = $teamId";
        $projectResult = mysqli_query($conn, $projectQuery);
        $project = mysqli_fetch_assoc($projectResult);
        
    //     // Fetch the selected department for the team from the employees table
    //   $departmentQuery = "SELECT DISTINCT department FROM employees_login WHERE team_id = $teamId";
    //   $departmentResult = mysqli_query($conn, $departmentQuery);
  
    //   if ($departmentResult) {
    //       $departmentRow = mysqli_fetch_assoc($departmentResult);
  
    //       if ($departmentRow) {
    //           $selectedDepartment = $departmentRow['department'];
    //       } else {
    //           $selectedDepartment = ""; // Set a default value or handle accordingly
    //       }
    //   } else {
    //       $selectedDepartment = ""; // Set a default value or handle accordingly
    //   }
  }
  
      

  
  ?>
<?php
// Function to get all employee IDs
        function getAllEmployeeIds() {
            global $conn;

            $employeeIds = [];
            $employeeIdsQuery = "SELECT emp_id FROM employees_login";
            $employeeIdsResult = mysqli_query($conn, $employeeIdsQuery);

            while ($row = mysqli_fetch_assoc($employeeIdsResult)) {
                $employeeIds[] = $row['emp_id'];
            }

            return $employeeIds;
        }
// <!-----update employeee----->

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['teamId']) && isset($_POST['teamName']) && isset($_POST['selected_employees'])) {
        $teamId = mysqli_real_escape_string($conn, $_POST['teamId']);
        $teamName = mysqli_real_escape_string($conn, $_POST['teamName']);
        $selectedEmployees = $_POST['selected_employees'];

        $selectedProject = mysqli_real_escape_string($conn, $_POST['selected_project']);
        // $selectedDepartment = mysqli_real_escape_string($conn, $_POST['selected_department']);


        // Update team name
        $updateTeamQuery = "UPDATE teams SET team_name = '$teamName' WHERE team_id = $teamId";
        mysqli_query($conn, $updateTeamQuery);
        if (mysqli_error($conn)) {
            echo "MySQL Error: " . mysqli_error($conn);
        }
        

         // Get the current employees in the team
         $currentEmployeesQuery = "SELECT emp_id FROM employee_teams WHERE team_id = $teamId";
         $currentEmployeesResult = mysqli_query($conn, $currentEmployeesQuery);
         $currentEmployees = [];
         while ($row = mysqli_fetch_assoc($currentEmployeesResult)) {
             $currentEmployees[] = $row['emp_id'];
         }

        $deleteRelationshipsQuery = "DELETE FROM employee_teams WHERE team_id = $teamId";
        mysqli_query($conn, $deleteRelationshipsQuery);

        // Set team_id to NULL for removed employees in employees_login(not working will use relationship of employee_teams table)
        foreach ($numericEmployeeIds as $empId) {
            $updateEmployeeQuery = "UPDATE employees_login SET team_id = NULL WHERE emp_id = $empId";
            mysqli_query($conn, $updateEmployeeQuery);
        }
        
         // Insert new team-employee relationships
         foreach ($selectedEmployees as $employeeId) {
             $insertEmployeeTeamQuery = "INSERT INTO employee_teams (emp_id, team_id) VALUES ('$employeeId', '$teamId')";
             mysqli_query($conn, $insertEmployeeTeamQuery);
         }
 
         // Redirect to the team page or wherever you want
         header("Location: team.php?teamName=$teamName");
         exit();
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
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">

        <!-- My CSS -->
        <link rel="stylesheet" href="css/addemp.css">


        <title>Project Management</title>

        <style>

/* CSS for the participants list */
.participants-list {
    margin-top: 20px;
}

.participant-list-container {
    list-style: none;
    padding: 0;
    display: flex;
    flex-wrap: wrap;
}

.participant-item {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    margin: 0 15px 15px 0;
    padding: 15px;
    flex: 0 0 calc(33.333% - 15px); /* Three participants in a row */
    box-sizing: border-box;
    transition: background-color 0.3s ease;
}

.participant-item:hover {
    background-color: #f5f5f5;
}

.participant-details {
    text-align: center;
}

.participant-id,
.participant-department {
    color: #666;
    margin: 5px 0;
}

.participant-name {
    margin: 10px 0;
}
 /*VALIDATION*/
 .is-valid {
            border-color: #28a745 !important;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }
        body, h1, h2, h3, p, ul, li, table {
  margin: 0;
  padding: 0;
  color:#fff;
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
 .box
 {
  width:100%;
  max-width:600px;
  background-color:#f9f9f9;
  border:1px solid #ccc;
  border-radius:5px;
  padding:16px;
  margin:0 auto;
 }
 .ck-editor__editable[role="textbox"] {
                /* editing area */
                min-height: 250px;
                
            }
 .error
{
  color:  red;
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
       
        
          .container {
            margin-left: 30px;
            background-color: #0f0f0fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
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
        
          input.form-control,
          textarea.form-control,
          select.form-control {
            background-color: black;
            color: blueviolet;
            border: 1px solid #896bdb;
          }
        
          input.form-control:focus,
          textarea.form-control:focus,
          select.form-control:focus {
            background-color: black;
            color: white;
            border: 1px solid #9f5ee9;
          }
          input[type="date"].form-control:focus {
            background-color: white;
            color: black;
            border: 1px solid #9f5ee9;
          }
        
          .btn-custom {
            background-color: #4D1DDE; 
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


        .styled-form {
        padding: 30px; /* Adjust as needed */
    }

    .styled-form .form-group {
        margin-bottom: 30px; /* Adjust as needed */
    }

    .styled-form label {
        margin-bottom: 5px; /* Adjust as needed */
    }

      /*VALIDATION*/
      .is-valid {
            border-color: #28a745 !important;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

 /* Custom styles for black background select menu */
 .black-background-select {
        background-color: #000000; /* Set to your preferred black color */
        color: #ffffff; /* Set to your preferred text color */
        border: 1px solid #ffffff; /* Set to your preferred border color */
    }

     
/*TBLE TEAM*/


.table {
            width: 100%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
            color:black;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-left: 50px;
            margin-right: 50px;

        }

        th, td {
            height:100%;
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #6f42c1;
            color: #fff;
        }

        tbody tr:hover {
            background-color: grey;
        }

        .sub-table {
            width: 50%;
            height: 90%;
            margin-top: 10px;
            border-collapse: collapse;
            border: 1px solid black;
            background-color: #fff;
        }

        .sub-table th, .sub-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .sub-table th {
            color: #fff;
        }
        
        tbody tr {
            border: 1px solid #ddd;
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
                        <span class="text">+To Do</span>
                    </a>
                </li>
               
            </ul>

    </section>
    <!--SIDEMENU-->
    

 <!-- TASK REPORT DASHBOARD -->
 
        <!-- CONTENT -->
<section id="content">
     <!-- MAIN -->
    
    <main>
    <!-- TASK REPORT DASHBOARD -->
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                <!-- Add Task Form -->
                    <div class="add-task-form">
                        <!--FORM-->
                        <h2 class="mb-4">Edit Your Team</h2>


                     <form class="styled-form" method="POST" action="editteam.php">
                        <!-- Existing team details -->
                        <input type="hidden" name="teamId" value="<?php echo $teamId; ?>">

                        <div class="form-row">
                            <div class="form-group col-md-5">
                                <label for="projectSelect">Select Project</label>
                                    <select class="form-control"class=selectedproject id="projectSelect" name="selected_project" value="<?php echo $project['project_name']; ?>" readonly >
                                        <?php
                                        // Fetch projects created by the manager from the database
                                        $manager_id = $_SESSION['manager_id'];
                                        $projectQuery = "SELECT project_id, project_name FROM projectsnew WHERE manager_id = '$manager_id'";
                                        $projectResult = mysqli_query($conn, $projectQuery);

                                        while ($projectRow = mysqli_fetch_assoc($projectResult)) {
                                            $isSelected = ($projectRow['project_id'] == $project['project_id']) ? 'selected' : '';
                                            echo "<option value='" . $projectRow['project_id'] . "' $isSelected>" . $projectRow['project_name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                        <div class="valid-feedback">
                                            Looks good!
                                        </div>
                                        <div class="invalid-feedback">
                                            Please select a project.
                                        </div>
                                </div>                    
                        </div>
                       

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="teamName">Team Name</label>
                                <input type="text" class="form-control" id="teamNameInput" name="teamName" value="<?php echo $team['team_name']; ?>" required>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter a team name accordingly.
                                    </div>
                            </div>
                                    
                        </div>
                       


                        <div class="form-row">
                            <div class="form-group col-md-9">
                                <label for="inputStatus">Add Employees:</label>
                                    <?php
                                    // Fetch employees with their department from the database
                                    $employeeQuery = "SELECT emp_id, emp_name, department FROM employees_login ORDER BY department";
                                    $employeeResult = mysqli_query($conn, $employeeQuery);

                                    // Fetch selected employees for the current project and team
                                    $selectedEmployeesQuery = "SELECT emp_id FROM employee_teams WHERE team_id = $teamId";
                                    $selectedEmployeesResult = mysqli_query($conn, $selectedEmployeesQuery);
                                    $selectedEmployeeIds = [];
                                    while ($selectedRow = mysqli_fetch_assoc($selectedEmployeesResult)) {
                                        $selectedEmployeeIds[] = $selectedRow['emp_id'];
                                    }

                                    // Initialize a variable to keep track of the current department
                                    $currentDepartment = null;
                                    ?>

                                    <!-- Checkboxes for selecting multiple employees -->
                                    <?php while ($row = mysqli_fetch_assoc($employeeResult)) { ?>
                                        <?php
                                        // Check if the department has changed
                                        if ($currentDepartment !== $row['department']) {
                                            echo '<div class="department-heading" onclick="selectDepartment(\'' . $row['department'] . '\')"> &bull; ' . $row['department'] . '</div>';
                                            $currentDepartment = $row['department'];
                                        }
                                        ?>
                                    <div class="form-check">
                                        <?php
                                        // Check if the current employee is selected for the project
                                        $isChecked = in_array($row['emp_id'], $selectedEmployeeIds) ? 'checked' : '';
                                        ?>
                                        <input class="form-check-input" type="checkbox" name="selected_employees[]" value="<?php echo $row['emp_id']; ?>" id="employee_<?php echo $row['emp_id']; ?>" <?php echo $isChecked; ?>>
                                        <label class="form-check-label" for="employee_<?php echo $row['emp_id']; ?>">
                                            <?php echo $row['emp_name']; ?>
                                        </label>
                                    </div>
                                        <?php } ?>

                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please select at least one employee.
                                    </div>
                            </div>
                        </div>



        <!-- Submit button -->
        <button type="submit" class="btn btn-primary btn-custom" id="updateTeam">Update Team</button>
    </form>

    
    <script>
// Function to load employees based on the selected department
function loadEmployees() {
    var selectedDepartment = document.getElementById("departmentSelect").value;

    //  AJAX request to fetch employees based on the selected department
    $.ajax({
        url: 'get_emp_by_deprt.php', //  server-side script
        type: 'POST',
        data: { department: selectedDepartment },
        success: function(response) {
            $('#employeeCheckboxList').html(response);
        }
    });
}
</script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


</body>
</html>

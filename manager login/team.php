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
// Fetching teams from the teams table
$query = "SELECT * FROM teams";
$result = mysqli_query($conn, $query);

// Process the form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['teamName']) && isset($_POST['selected_employees'])) {
        $teamName = mysqli_real_escape_string($conn, $_POST['teamName']);
        $selectedEmployees = $_POST['selected_employees'];
        // Insert team into teams table
        $insertTeamQuery = "INSERT INTO teams (team_name) VALUES ('$teamName')";
        mysqli_query($conn, $insertTeamQuery);

        // Get the team_id of the inserted team
        $teamId = mysqli_insert_id($conn);

        // Update team_id in projectsnew table based on the selected project
        $selectedProjectId = mysqli_real_escape_string($conn, $_POST['selected_project']);
        $updateProjectsQuery = "UPDATE projectsnew SET team_id = $teamId WHERE project_id = '$selectedProjectId'";
        mysqli_query($conn, $updateProjectsQuery);

        foreach ($selectedEmployees as $employeeId) {
            $insertParticipantQuery = "UPDATE employees_login SET team_id = $teamId WHERE emp_id = '$employeeId'";
            mysqli_query($conn, $insertParticipantQuery);
        
            // Insert record into employee_teams table
            $insertEmployeeTeamQuery = "INSERT INTO employee_teams (emp_id, team_id) VALUES ('$employeeId', $teamId)";
            mysqli_query($conn, $insertEmployeeTeamQuery);
        }
        
        echo "Team and participants added successfully!";

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
            color: white;
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
            height: 350px;  
            width: 650px;         
            margin-left: 450px;
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
       
#teamsTable{
    margin-left: 320px;
    margin-right: 250px;
    width: 75%;         

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
                        <span class="text">+To Do</span>
                    </a>
                </li>
                
            </ul>
    </section>
    <!--SIDEMENU-->
<section id="content">    
    <main>
    <!-- TASK REPORT DASHBOARD -->
    <div class="container">
            <div class="row">
                <div class="col-md-9">
                <!-- Add Task Form -->
                    <div class="add-task-form">
                        <h3 class="mb-3">Add Employees And Create Team</h3>
       
                        <form class="styled-form needs-validation" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" id="createTeamForm" onsubmit="updateEmployeeList(); return true;" novalidate>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                    <label for="projectSelect">Select Project</label>
                                        <select class="form-control" id="projectSelect" name="selected_project" required>
                                            <?php
                                            // Assuming you have the manager_id available
                                            $manager_id = $_SESSION['manager_id'];

                                            // Fetch projects created by the manager from the database
                                            $projectQuery = "SELECT project_id, project_name FROM projectsnew WHERE manager_id = '$manager_id'";
                                            $projectResult = mysqli_query($conn, $projectQuery);

                                            // Check if there are any projects
                                            if (mysqli_num_rows($projectResult) > 0) {
                                                while ($projectRow = mysqli_fetch_assoc($projectResult)) {
                                                    echo "<option value='" . $projectRow['project_id'] . "'>" . $projectRow['project_name'] . "</option>";
                                                }
                                            } else {
                                                // Display "Project not created" if no projects are available
                                                echo "<option value='' disabled selected>Project not created</option>";
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

                                <div class="form-group col-md-7" style="padding-left:20px;">
                                    <div class="form-row">
                                        <div class="form-group col-md-5">
                                            <label for="teamName">Team Name</label>
                                            <input type="text" class="form-control" id="teamNameInput" name="teamName" placeholder="Enter a team name" required>
                                                <div class="valid-feedback">
                                                    Looks good!
                                                </div>
                                                <div class="invalid-feedback">
                                                    Please enter a team name accordingly.
                                                </div>
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
                                                <input class="form-check-input" type="checkbox" name="selected_employees[]" value="<?php echo $row['emp_id']; ?>" id="employee_<?php echo $row['emp_id']; ?>">
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
                    </div>
                                    <button type="submit" class="btn btn-primary btn-custom" id="teamName">Create Team</button>
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
        <div class="row mb-3">
            <div class="col-md-9">
                <label for="searchInput" style="padding-top: 70px;">Search:</label>
                <input type="text" id="searchInput" onkeyup="searchTable()" class="form-control" placeholder="Type to search">
            </div>
        </div>
        </div>
    </div>
    </section>
    <table id="teamsTable" class="table" >
  <thead>
    <tr>
      <th scope="col">Emp name</th>
      <th scope="col">Deprt</th>
      <th scope="col">Teams</th>
      <th scope="col" >Add/Delete employee</th>
      <th scope="col" >Delete</th>
    </tr>
  </thead>
  <tbody>
  <?php
    // Fetch teams added by the logged-in manager to a project
            $managerTeamsQuery = "SELECT teams.team_id, projectsnew.project_name
            FROM teams
            LEFT JOIN projectsnew ON teams.team_id = projectsnew.team_id
            WHERE projectsnew.manager_id = '$empId'";
            $managerTeamsResult = mysqli_query($conn, $managerTeamsQuery);

            // Check if the managerTeams query was successful
            if ($managerTeamsResult) {
            // Loop through each team added by the manager
            while ($managerTeamRow = mysqli_fetch_assoc($managerTeamsResult)) {
            $teamId = $managerTeamRow['team_id'];

            // Display header for each team
            echo "<tr>";
            echo "<td colspan='3'><strong>Employees in {$managerTeamRow['project_name']}:</strong></td>";
            echo "<td><button class='btn btn-custom'><a href='editteam.php?editid=$teamId' class='text-light'>Add Employee</a></button></td>";
            echo "<td><button class='btn btn-custom'><a href='deleteteam.php?deleteid=$teamId' class='text-light'>Delete Team</a></button></td>";
            echo "</tr>";               

            // Fetch employees for the current team using JOIN
            $employeesQuery = "SELECT emp_name, department, team_name
                FROM employees_login
                LEFT JOIN employee_teams ON employees_login.emp_id = employee_teams.emp_id
                LEFT JOIN teams ON employee_teams.team_id = teams.team_id
                WHERE employee_teams.team_id = $teamId";

            $employeesResult = mysqli_query($conn, $employeesQuery);

            // Ensure $employeesResult is set and not empty
            if ($employeesResult) {
            // Loop through each employee in the team
            while ($empRow = mysqli_fetch_assoc($employeesResult)) {
            echo "<tr>";
            echo "<td>{$empRow['emp_name']}</td>";
            echo "<td>{$empRow['department']}</td>";
            echo "<td>{$empRow['team_name']}</td>";
            echo "<td></td>"; // Placeholder for Add Employee button
            echo "<td></td>"; // Placeholder for Delete button
            echo "</tr>";
            }
            } else {
            echo "<tr><td colspan='5'>Error: " . mysqli_error($conn) . "</td></tr>";
            }
            }
            } else {
            echo "<tr><td colspan='5'>Error: " . mysqli_error($conn) . "</td></tr>";
            }
            ?>
    </tbody>
    </table>
             </div>
            </div>
        </div>
    </div>
</div>
</main>

</section>    
    <!--FOR VALIDATION-->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

  <script>
    // JavaScript to add dynamic classes based on validation state
    (function () {
        'use strict';
        // Fetch the form to apply custom Bootstrap validation styles to
        var form = document.querySelector('.needs-validation');

        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');

            Array.from(form.elements).forEach(function (element) {
                if (element.checkValidity()) {
                    element.classList.remove('is-invalid');
                    element.classList.add('is-valid');
                } else {
                    element.classList.remove('is-valid');
                    element.classList.add('is-invalid');
                }
            });
        }, false);
    })();
</script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    function updateEmployeeList() {
        var teamName = document.getElementById('teamName').value;

        // Use AJAX to submit the form data without refreshing the page
        $.ajax({
            type: 'POST',
            url: 'updatemplist.php',
            data: { teamName: teamName },
            success: function (response) {
                // Update the iframe source with the response
                document.getElementById('employeeListFrame').srcdoc = response;
            },
            error: function (error) {
                console.log(error);
            }
        });
    }
</script>

<!-- JavaScript to handle Edit Team button click -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.edit-team-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            var teamId = this.getAttribute('data-teamid');
            openEditTeamForm(teamId);
        });
    });

    function openEditTeamForm(teamId) {
        // Implement the logic to open the edit team form/modal
        console.log('Open form for editing Team ID: ' + teamId);
        // You may use a modal or another mechanism to open the edit form
    }
});
</script>

<script>
    function searchTable() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("teamsTable");
        tr = table.getElementsByTagName("tr");

        for (i = 1; i < tr.length; i++) { // Start from index 1 to skip the header row
            var empNameColumn = tr[i].getElementsByTagName("td")[0]; // Index 0 corresponds to the "Emp name" column
            var teamsColumn = tr[i].getElementsByTagName("td")[2]; // Index 2 corresponds to the "Teams" column

            if (empNameColumn || teamsColumn) {
                var empNameValue = empNameColumn.textContent || empNameColumn.innerText;
                var teamsValue = teamsColumn.textContent || teamsColumn.innerText;

                // Search both "Emp name" and "Teams" columns
                if (empNameValue.toUpperCase().indexOf(filter) > -1 || teamsValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>

</body>
</html>
</body>
</html>
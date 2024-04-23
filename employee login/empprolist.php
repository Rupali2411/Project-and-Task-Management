<?php
// Start the session
session_start();
include("dbconn.php");

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css'>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js" integrity="sha384-zMP7rVo3A/CBC6IWhc7QK1Li6Es09mCMAVqnF3Dbt4HRsi6s2hBOJqLBob0ZuofC" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+WyW8JnEMqJL2R8uPvZTt0U9Y7S6Kv1N" crossorigin="anonymous"></script>
  <!-- My CSS -->
    <link rel="stylesheet" href="css/dashstyle.css">
    
    <title>Project Management</title>

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
     
         .container {
            background-color: #0f0f0fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 40px;
            

          }
          .container1 {
            background-color: #0f0f0fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            padding-left: 40px;
            margin-top: 0px;
            height: 750px;
            width: 950px;
          
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
          input[type="date"]:focus {
            background-color: white;
            color: #000;
        }
          .btn-custom {
            background-color: #6610f2; 
            border-color:  #4D1DDE;
            display: block;
            color: white;
            margin: 0 auto;
            padding-top: 10px;
        }
               /* background color of the submit button on hover */
        .btn-custom:hover {
                 background-color:#4D1DDE;
                 border-color:  #eceaf3;
                 color: white;
        
        }

        .excel-box {
            margin-top: 30px;
    margin-bottom: 30px;
    background-color: rgb(9, 9, 9);
    color: white;
    padding: 20px; /* Adjust the padding for better aesthetics */
    border-radius: 8px;
  }

  .excel-box h2 {
    color: #a5a7e6;
  }

  .excel-box table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 0px;
    background-color: black;
    
  }
 
  .excel-box table th,
  .excel-box table td {
    padding: 10px;
    text-align: center;
    color: #75658B;
  }

  .excel-box table th {
    background-color: #6f42c1;
    font-weight: bold;
    color: whitesmoke;
  }
  .excel-box table th:first-child {
    border-top-left-radius: 11px; /* Adjust this line for rounded corners on the top-left corner */
}

.excel-box table th:last-child {
    border-top-right-radius: 11px; /* Adjust this line for rounded corners on the top-right corner */
}

  .excel-box table tbody tr:nth-child(even) {
    background-color: black;
    
  }

  .excel-box table tbody tr:hover {
    background-color: rgba(71, 58, 58, 0.482);

  }
 
  .excel-box table td:first-child,
  .excel-box table td.task-actions:last-child {
    border-left: none;
  }

  .excel-box table td:last-child {
    border-right: none;
  }
/*remove border from first and last*/
  .excel-box table td:nth-child(2),
  .excel-box table td:nth-child(6),
  .excel-box table th:nth-child(2),
  .excel-box table th:nth-child(6) {
    border-right: none;
    
  }

  .excel-box table th:first-child,
  .excel-box table td:first-child {
    border-left: none;
  }
 
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
</style>
</head>
<body>
  
        <?php
        // Assume emp_id is passed through a session variable after login
        if (isset($_SESSION['emp_id'])) {
            $empId = $_SESSION['emp_id'];

            // Query to fetch employee details
            $query = "SELECT * FROM employees_login WHERE emp_id = '$empId'";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $employee = mysqli_fetch_assoc($result);

                
            }
        }

        ?>
  
 <!-- SIDEBAR MENU-->
 <section id="sidebar">
                <div class="brand">
                    <span id="text">Employee:</span>
                        <p style="color: white;"><?php echo $employee['emp_name']; ?> </p>
                        <p style="color: white;"> Deprt: <?php echo $employee['department']; ?> </p>
                </div>
                <div class="user-box">
                    <div class="user-info">
                    <span class="user-emailid" style="color:black";><?php echo $employee['email_id']; ?></span>                       
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

        <section id="content">
                 <main>
              <div class="excel-box">
                    <div class="table-controls" style="padding-bottom: 50px;">
                        <h2 style="padding-bottom: 30px;">Project List</h2>
                    <div class="row">
                    <div class="col-md-4">
                        <label for="searchInput">Search:</label>
                        <input type="text" id="searchInput" oninput="searchTable()" class="form-control" placeholder="Type to search">
                    </div> 
                    <div id="searchMessage" style="display: none; margin-top: 10px;">No project found.</div>

                </div>
            </div>
            <?php
include("dbconn.php");

if (isset($_SESSION['emp_id'])) {
    $empId = $_SESSION['emp_id'];
    // Query to fetch employee details
    $query = "SELECT * FROM employees_login WHERE emp_id = '$empId'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $employee = mysqli_fetch_assoc($result);

        // Fetch project details for the employee's team
        $projectQuery = "SELECT projectsnew.project_id, projectsnew.project_name, projectsnew.start_date, projectsnew.due_date, projectsnew.priority, projectsnew.content, managers_login.manager_name
                        FROM employee_teams
                        LEFT JOIN teams ON employee_teams.team_id = teams.team_id
                        LEFT JOIN projectsnew ON teams.team_id = projectsnew.team_id
                        LEFT JOIN managers_login ON projectsnew.manager_id = managers_login.manager_id
                        WHERE employee_teams.emp_id = '$empId' AND projectsnew.project_id IS NOT NULL";
        $projectResult = mysqli_query($conn, $projectQuery);

        if ($projectResult && mysqli_num_rows($projectResult) > 0) {
            ?>
            <!-- Table fields-->
            <table id="projectTable">                            
                <thead>
                    <tr class="table-5">
                        <th>Project Name</th>
                        <th>Project Manager</th>
                        <th>Start date <br> </th>
                        <th>Due date</th>
                        <th>Priority</th>
                        <th colspan=1>Action</th>
                    </tr>
                </thead>
                <tbody>
            <?php
            while ($projectDetails = mysqli_fetch_assoc($projectResult)) {
                echo "<tr>
                        <td>" . ($projectDetails['project_name'] ?? 'N/A') . "</td>
                        <td>" . ($projectDetails['manager_name'] ?? 'N/A') . "</td>
                        <td>" . ($projectDetails['start_date'] ?? 'N/A') . "</td>
                        <td>" . ($projectDetails['due_date'] ?? 'N/A') . "</td>
                        <td>" . ($projectDetails['priority'] ?? 'N/A') . "</td>
                        <td>
                            <a href='viewproj.php?project_id=" . $projectDetails['project_id'] . "' class='btn btn-custom'>View Project</a>
                        </td>
                    </tr>";
            }
            ?>
                </tbody>
            </table>
            <?php
        } else {
            echo "No projects found for your team.";
        }
    } else {
        echo "Employee not found";
        exit;
    }
} else {
    echo "Session not started or emp_id not set.";
    exit;
}
?>

<script>
function searchTable() {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue, hasMatch;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("projectTable"); // Assuming the project table has the id "projectTable"
    tr = table.getElementsByTagName("tr");
    hasMatch = false;

    // Loop through all table rows, and hide those that don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0]; // Assuming the project name is in the first column
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
                hasMatch = true;
            } else {
                tr[i].style.display = "none";
            }
        }
    }

    // Show or hide the message based on search result
    var searchMessage = document.getElementById("searchMessage");
    if (hasMatch) {
        searchMessage.style.display = "none";
    } else {
        searchMessage.style.display = "block";
    }
}
</script>
</section>
   </body>
</html>
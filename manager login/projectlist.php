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
  $manager_id = $_SESSION['manager_id'];

if (isset($_POST['add_project'])) {
  $projectName = $_POST['project_name'];
  

    $projectName = isset($_POST['project_name']) ? mysqli_real_escape_string($conn, $_POST['project_name']) : '';
    $projectmanager = isset($_POST['project_manager']) ? mysqli_real_escape_string($conn, $_POST['project_manager']) : '';
    $scope = isset($_POST['content']) ? $_POST['content'] : '';
    $escapedScope = mysqli_real_escape_string($conn, $scope);      
    $dueDate = isset($_POST['due_Date']) ? mysqli_real_escape_string($conn, $_POST['due_Date']) : '';
    $priority = isset($_POST['priority']) ? mysqli_real_escape_string($conn, $_POST['priority']) : '';
    $status = isset($_POST['status']) ? mysqli_real_escape_string($conn, $_POST['status']) : '';
    $instruction = isset($_POST['instruction']) ? mysqli_real_escape_string($conn, $_POST['instruction']) : '';
    $attachmentPath = isset($_POST['attachment']) ? mysqli_real_escape_string($conn, $_POST['attachment_path']) : '';

 //  upload directory if it doesn't exist
 if (!empty($_FILES["attachment"]["name"])) {
  // Handle file upload
  $uploadDir = "uploads/"; // Specify the directory where you want to store uploaded files

  // Create the upload directory if it doesn't exist
  if (!file_exists($uploadDir)) {
      mkdir($uploadDir, 0777, true);
  }

  $attachmentName = $_FILES["attachment"]["name"];
  $attachmentTmpName = $_FILES["attachment"]["tmp_name"];
  $attachmentPath = $uploadDir . $attachmentName;

  // Move the uploaded file to the specified directory
  if (move_uploaded_file($attachmentTmpName, $attachmentPath)) {
      echo "File uploaded successfully.";
  } else {
      echo "File upload failed.";
      // Handle the error accordingly
  }
} else {
  // No file attached
  $attachmentPath = ''; // Set an empty path or null in your database
  echo "No file attached.";
}

// Insert data into the database (excluding the "id" column)
$query = "INSERT INTO projectsnew (manager_id, project_name, proj_manager, content, due_date, priority, status, instruction, attachment_path) VALUES ('$manager_id','$projectName', '$projectmanager', '$scope', '$dueDate', '$priority', '$status', '$instruction','$attachmentPath')";


if (mysqli_query($conn, $query)) {
  echo "Project added successfully";

  // Redirect to another page to avoid form resubmission on refresh
  header("Location: projectlist.php");
  exit();
}
} else {
  // echo "Error: " . mysqli_error($conn);
}
}


$query = "SELECT * FROM projectsnew";
$result = mysqli_query($conn, $query);

?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
      <!-- Boxicons -->
        <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
        <!-- My CSS -->
        <title>Project Management</title>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

 <style>

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
.container{
  margin-top: 58px;
  
}

  .excel-box {
    margin-bottom: 30px;
    background-color: rgb(9, 9, 9);
    color: white;
    padding: 20px; /* Adjust the padding for better aesthetics */
    border-radius: 8px;
    border: #4c0bce;

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
    color: #fff;

  }
 
  .excel-box table td{
    border: 0.1px solid #555a64; /* Add a thinner border */
    
  }

  .excel-box table th {
    background-color: #5468ff;
    font-weight: bold;
    color: whitesmoke;
    padding-bottom: 10px;

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

  .edit-button{
    margin-right: 10px;

  }
  .edit-button,
  .delete-button {
    border: 1.5px solid #0d6efd;
    color: white;
    background-color: #4c0bce;
    padding: 5px 12px;
    cursor: pointer;
    border-radius: 7px;

  }

  .edit-button:hover,
  .delete-button:hover {
    background-color: #9999D0;
    color: #fff;
  }

  .btn-custom {
    background-color: #513c90; 
    border-color:  #513c90;
    display: block;
    margin: 0 auto;
    padding-top: 10px;
}
 /* Custom CSS for the dropdown */
  .btn-group .dropdown-menu {
    border: 1px solid #ccc; /* Add border to the dropdown */
    border-radius: 0; /* Remove border-radius */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Add a subtle box shadow */
  }

  .btn-group .dropdown-menu a {
    color: #333; /* Change link color */
  }

  .btn-group .dropdown-menu a:hover {
    background-color: #f8f9fa; /* Change background color on hover */
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
               <!--SIDEMENU-->

            <ul class="side-menu top">
              <li>
                    <a href="profile.php">
                        <i class="bx bx-bell"></i>
                        <span class="text">Profile</span>
                    </a>
                </li>
                
                
                <li class="active">
                    <a href="dash.php">
                        <i class='bx bxs-dashboard'></i>
                        <span class="text">Dashboard</span>
                    </a>
                </li>
                <li class="active">

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
            <div class="excel-box">
                <div class="table-controls" style="padding-bottom: 50px;">
                    <h2 style="padding-bottom: 30px;">Project List</h2>
                <div class="row">
              <div class="col-md-4">
                  <label for="searchInput">Search:</label>
                  <input type="text" id="searchInput" oninput="searchTable()" class="form-control" placeholder="Type to search">
              </div> 
       
            </div>
      </div>
<div id="noRecordsMessage" style="display: none;">No records found.</div>       

                        <!-- Table fields-->
                        <table  id="projectTable">                           
                            <thead>
                                <tr class="table-5" >
                                    <th>Project name</th>                                    
                                    <th>Start date <br></th>
                                    <th>Due date</th>
                                    <th> Proirity</th>
                                    <th> Team</th>
                                    <th> Status</th>
                                    <th colspan=1>Action</th>
                                </tr>
                            </thead>
                            <?php
                $query = "SELECT p.project_id, p.project_name, p.proj_manager, p.status, p.start_date, p.due_date, p.priority, t.team_name
                FROM projectsnew p
                LEFT JOIN teams t ON p.team_id = t.team_id
                WHERE p.manager_id = '$manager_id'";
                     
                 $result = mysqli_query($conn, $query);
                 
                 while ($row = mysqli_fetch_assoc($result)) {
                     echo "<tr>";
                     // Display project details
                    //  echo "<td>{$row['project_id']}</td>";
                     echo "<td>{$row['project_name']}</td>";
                     
                     echo "<td>{$row['start_date']}</td>";
                     echo "<td>{$row['due_date']}</td>";
                     echo "<td>{$row['priority']}</td>";
                     echo "<td>{$row['team_name']}</td>"; 
                     echo "<td>";
                     // Display status with appropriate symbols
                     $status = $row['status'];
                     switch ($status) {
                         case 'on_track':
                             echo "&#10004; On track";
                             break;
                         case 'off_track':
                             echo "&#10006; Off track";
                             break;
                         case 'on_hold':
                             echo "&#10074; On hold";
                             break;
                         case 'at_risk':
                             echo "&#9888; At risk";
                             break;
                         case 'completed':
                             echo "&#10004; Completed";
                             break;
                         default:
                             echo $status; // Default case
                     }
                     echo "</td>";
                    echo "<td>";
                    echo "<div class='btn-group'>";
                    echo "<button type='button' class='btn btn-group   >
                            Actions
                          </button>";
                    echo "<div class='dropdown-menu'>";
                    echo "<select class='custom-select' onchange='redirectOption(this.value)'>";
                    echo "<option selected disabled>Actions</option>";
                    echo "<option value='editproject.php?editproid={$row['project_id']}'>Edit</option>";
                    echo "<option value='deleteproject.php?deleteproid={$row['project_id']}'>Delete</option>";
                    echo "<option value='tempaddtask.php?project_id={$row['project_id']}'>View Project</option>";
                    echo "</select>";
                    echo "</div>";
                    echo "</div>";
                    echo "</td>";

                    echo "</tr>";
                }
 ?>
    <script>
        function redirectOption(value) {
            if (value !== "") {
                window.location.href = value;
            }
        }
    </script>             
  </table>
                  </div>    
                </div>
          </main>
        </section>

        <script>
     function searchTable() {
        // Get the value and convert to lowercase for case-insensitive search
        var value = document.getElementById('searchInput').value.toLowerCase();

        // Get all rows in the table body
        var rows = document.getElementById('projectTable').getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        var found = false; // Flag to check if any record is found

        // Loop through the rows
        for (var i = 0; i < rows.length; i++) {
            var rowText = rows[i].textContent.toLowerCase();

            // Check if any cell in the row contains the input value
            if (rowText.includes(value)) {
                rows[i].style.display = '';
                found = true;
            } else {
                rows[i].style.display = 'none';
            }
        }
        // Display a message if no records are found
        var noRecordsMessage = document.getElementById('noRecordsMessage');
        if (found) {
            noRecordsMessage.style.display = 'none';
        } else {
            noRecordsMessage.style.display = 'block';
        }
    }

    function filterTable(selectId, columnIndex) {
        var select, filter, table, tr, td, i, txtValue;
        select = document.getElementById(selectId);
        filter = select.value.toUpperCase();
        table = document.getElementById("projectTable");
        tr = table.getElementsByTagName("tr");

        for (i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[columnIndex];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (filter === '' || txtValue.toUpperCase().indexOf(filter) > -1) {
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
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
    

    <link rel="stylesheet" href="css/dashstyle.css">

</head>
<style>
    
    h1, h2, ul, li, table {
  margin: 0;
  padding: 0;
  color:black;
}
p ,label{
  color:#000;
}


body{
    color: black;
    background-color: #ebe9eb;
    font-family: Verdana, Geneva, Tahoma, sans-serif;/**/ 
    font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;/**/
    font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;   /* */
    font-family: "Poppins", sans-serif;
    font-size: 17px;
}
          .container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.5); 
            padding-top: 30px;
            
          }
          
        
          input.form-control,
          textarea.form-control,
          select.form-control {
            background-color: white;
            color: black;
            border: 1px solid #896bdb;
            font-size: 17px;
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
        select.form-control2{
          background-color: white;
            color: black;
            border: 1px solid #896bdb;
            font-size: 17px;
            height: 35px;
            border-radius: 6px;
            width: 200px;
            /* padding: 9px; */

        }
        select.form-control2:focus{
          background-color: white;
            color: black;
            border: 1px solid #9f5ee9;

        }
        
          .btn-custom {
            background-color: #6f42c1; 
            border-color:  #6f42c1;
            font-size: large;
            color: #f8f8f8;
            display: block;
            margin: 0 auto;
            padding-top: 10px;
        }
        
        /* background color of the submit button on hover */
        .btn-custom:hover {
                 background-color:#4e2a77;
                 border-color:  #4e2a77;
                 color: white;
        
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
  color: white;
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

.fontt, #text{
color:black;
text-align: center;
font-size: larger;
font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;   /* */
}
#text{
  color:white;
}


#content5{
    padding-top: 50px;
    padding-left: 90px;
    padding-bottom: 50px;
}
</style> 
  <!-- <?php 
  include('dbconn.php');
  if(isset($_REQUEST['submit']))
  {
    $content = $_REQUEST['content'];
   
    $insert_query = mysqli_query($connection, "insert into tbl_ckeditor set content='$content'");
    if($insert_query)
    {
      $msg = "Data Inserted";
    }
    else
    {
      $msg = "Error";
    }
  }
  ?> -->
<body>

 <!--SIDEMENU-->    
 <!-- SIDEBAR MENU-->
 <section id="sidebar">
                <div class="brand">
                    <span id="text1">Manager Details</span><br>
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
                        <span class="text">+My task</span>
                    </a>
                </li>
                
                
            </ul>

    </section>
    <!--SIDEMENU-->



 
    <section id="content5">
    
<main>
    <div class="container">
      <div class="form-container">
        <h2 class="fontt">Create a Project</h2>
        <p class="fontt" style="font-size: larger; padding-top:15px ;"> Fill in every details to describe your project</p>

        
        
    <form class="styled-form needs-validation" method="post" action="projectlist.php" enctype="multipart/form-data" novalidate>
        <div class="form-row">
        <!-- <div class="form-group col-md-6">
            <input type="text" class="form-control" id="inputProjectManager" name="project_manager" placeholder="Project Manager" pattern="[A-Za-z\s]+" title="Only characters allowed" required>
            <div class="valid-feedback">
                Looks good!
            </div>
            <div class="invalid-feedback">
                Please enter  Project Manager name (only characters allowed).
            </div>
        </div> -->
        <div class="form-group col-md-3" >
        <label>Project Name:</label>
            <input type="text" class="form-control" id="inputprojectname" name="project_name" placeholder="Project Name" pattern="[A-Za-z0-9\s]+" title="Only characters and numbers allowed" required>
            <div class="valid-feedback">
                Looks good!
            </div>
            <div class="invalid-feedback">
                Please enter a Project Name .
            </div>
        </div>
    </div>

      <div class="form-group">
        <label>Description:</label>
        <textarea id="content" name="content" class="form-control"></textarea>
        </div>
      <div class="form-group">
    </div>

    <div class="form-row">
    <div class="form-group col-md-2" style="padding-left: 20;">
        <label>Select Due date:</label>
        <input type="date" class="form-control" id="inputduedate" name="due_Date" required min="<?php echo date('Y-m-d'); ?>" oninput="validateDueDate()">
        <div class="valid-feedback">
            Looks good!
        </div>
        <div class="invalid-feedback">
            Please enter a valid Due Date.
        </div>
        <div id="weekError" style="color: red;"></div>
    </div>
</div>



  <div class="form-row">
          <div class="form-group col-md-4" >
                <label>Select Priority:</label><br>
                <select class="form-control2" id="inputPriority" name="priority" required >
                    <option value="" disabled selected>Select Priority</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
                <div class="valid-feedback">
                    Looks good!
                </div>
                <div class="invalid-feedback">
                    Please select a Priority.
                </div>
          </div>
  </div>
        

        <div class="form-group">
            <label for="inputAttachment" class="text">Attachment:</label>
            <input type="file"  class="form-control-file" id="inputAttachment" name="attachment" >
           
        </div>

    <button type="submit" class="btn  btn-custom" name="add_project">Create Project</button>

  </form>
  


   <!-- Bootstrap JS and dependencies -->
   <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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

<!-- FOR DATE VALIDATON WITHIN ONE WEEK-->
<script>
    function validateDueDate() {
        // Get the selected due date
        var selectedDueDate = new Date(document.getElementById('inputduedate').value);

        // Get today's date
        var today = new Date();

        // Calculate the date one week from today
        var oneWeekFromToday = new Date(today);
        oneWeekFromToday.setDate(oneWeekFromToday.getDate() + 4);

        // Check if the selected due date is within one week from today
        if (selectedDueDate <= oneWeekFromToday) {
            document.getElementById('weekError').innerHTML = 'Due date should be more than one week from today.';
            document.getElementById('inputduedate').setCustomValidity('Due date should be more than one week from today.');
        } else {
            document.getElementById('weekError').innerHTML = '';
            document.getElementById('inputduedate').setCustomValidity('');
        }
    }
</script>
<!-- FOR DATE VALIDATON WITHIN ONE WEEK-->

 </body>  
</html>  
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#content' ))
        .catch( error => {
            console.error( error );
        });
</script>
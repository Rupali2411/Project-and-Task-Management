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
// Fetching teams associated with projects managed by the logged-in manager
$query = "SELECT t.team_id, t.team_name 
          FROM teams t 
          INNER JOIN projectsnew p ON t.team_id = p.team_id 
          WHERE p.manager_id = '$empId'";
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/boxicons/2.0.7/css/boxicons.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
   <!-- My CSS -->
    <link rel="stylesheet" href="css/dashstyle.css">

    <title>Project Management</title>

    <style>
          body, h1, h2, p, ul, li, table {
    margin: 0;
    padding: 0;
    color:#fff;
}
h2{
    color: black;

}
.label {
padding: 12px;
}
body{
    color: white;
    background-color: white;
    font-family: Verdana, Geneva, Tahoma, sans-serif;/**/ 
    font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;/**/
    font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;   /* */
    font-family: "Poppins", sans-serif;
    font-size: 17px;
}
.container{
    background-color: #C0C6FD;
    padding: 30px;
    color: black;
    width: 960px;
}
.form-container{
    padding: 30px;
    color: black;
    background-color: white;
    border-radius: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.395);

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
  color: black;
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

input[type="text"], textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }
    .btn-custom {
        padding: 10px 20px;
        background-color: blue;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    button:hover {
        background-color: #0056b3;
    }
    .additional-sections {
        margin-top: 20px;
    }
    .additional-section {
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
    }
    .additional-section-title,
    .additional-section-content {
        margin-bottom: 10px;
    }
    .additional-section button {
        background-color: #4D1DDE;
    }
    .additional-section button:hover {
        background-color: #C0C6FD;
    }
    .add-section-button {
        background-color: #f0f2f5;
        border: 1px dashed #ccc;
        border-radius: 5px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
    }
    .add-section-button:hover {
        background-color: #e1e4e8;
    }
    .add-section-button i {
        font-size: 24px;
}
.btn-primary{
    padding: 10px;
    width: 150px;
    border-radius: 10px;
}
.btn-primary1{
    border-color: #007bff; 
    color: inherit; 
    background-color: transparent; 
    border-width: 2px;
}
.container1 {
            background-color:white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 0px;
            width: 50%px; 
            margin-left: 250px;
            margin-right: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding-left: 30px;
            padding-top: 20px;
            margin-top: 40px;
            margin-bottom: 40px;
          

}

td {
        max-width: 150px; /* Set your desired maximum width */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
}
.container1 {
    position: sticky;
    top: 0;
    background-color: white;
    z-index: 1;
    
}

.table-container {
    max-height: 400px; /* Adjust the max-height as needed */
    overflow-y: auto;

}

.table {
    width: 100%;
    border-collapse: collapse;
    background-color:white;

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
  .goal-details-container *{
        color: black;
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
            </div>
    </section>
    <!--SIDEMENU-->

    <section id="content">    
        <main>
            <button id="addGoalButton" class="btn1 btn-primary ">Add Goal</button>
                <div id="goalFormContainer" style="display: none;">
                    <div class="container">
                        <div class="form-container">
                            <h2 class="fontt">Add goal</h2><br />
                        <label class="label">What is a goal or key result you want to accomplish?</label>
                <form id="goalForm" class="styled-form needs-validation" enctype="multipart/form-data">
                            <div class="form-group col-md-4">
                                    <label for="projectSelect">Select Team:</label>
                                    <select class="form-control" id="teamSelect" name="selected_team" required>
                                            <?php
                                            // Check if teams query was successful
                                            if ($result && mysqli_num_rows($result) > 0) {
                                                // Loop through each team and populate the dropdown list
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<option value='{$row['team_id']}'>{$row['team_name']}</option>";
                                                }
                                            } else {
                                                echo "<option value='' disabled>No teams available</option>";
                                            }
                                            ?>
                                        </select> 
                                    
                            </div>
        
                            <div class="form-group col-md-7" >
                                 <label>Goal title</label>
                                <input type="text" class="form-control" id="inputgoaltitle" name="goaltitle" placeholder="Title of the Goal" pattern="[A-Za-z\s]+" title="Only characters allowed" required>
                            </div>
 
                        <div class="form-group col-md-7">
                                <label> + Summary</label>
                                <textarea class="form-control" id="inputSummary" name="summary" placeholder="How this goal Progressing ?" rows="1" required pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed"></textarea>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please provide a valid summary with only letters and spaces.
                                    </div>
                        </div>

                        <div class="form-group col-md-7">
                                <label> + What you have accomplishment</label>
                                <textarea class="form-control" id="inputAccomplishment" name="accomplishment" placeholder="what you have achieved" rows="1"  pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed"></textarea>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please provide a valid accomplishment.
                                    </div>
                        </div>

                        <div class="form-group col-md-7">
                                    <label> + Next steps</label>
                                    <textarea class="form-control" id="inputNextSteps" name="nextSteps" placeholder="Tell the team what's next'" rows="1"  pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed"></textarea>
                                        <div class="valid-feedback">
                                            Looks good!
                                        </div>
                                        <div class="invalid-feedback">
                                            Please provide valid next steps with only letters and spaces.
                                        </div>
                        </div>

                        <div class="additional-sections">
                            <div class="additional-section">
                                <input type="text" class="additional-section-title" name="section_title" placeholder="Section Title">
                                <textarea class="additional-section-content" name="section_content" placeholder="Section Content" rows="4"></textarea>
                                <button type="button" class="btn btn-custom" onclick="removeSection(this)">Remove</button>
                            </div>
                        </div>

                        <div class="add-section-button" onclick="addSection()">
                            <i class="fas fa-plus"></i>
                        </div><br>                       
    <!-- Hidden input fields for additional sections data -->
    <div id="additional-sections-data"></div>

    <button type="submit" class="btn btn-primary" id="submitBtn">Add goal</button>
</form>
   
<script>
    function addSection() {
        const additionalSections = document.querySelector('.additional-sections');
        const newSection = document.createElement('div');
        newSection.classList.add('additional-section');
        newSection.innerHTML = `
            <input type="text" class="additional-section-title" name="section_title" placeholder="Section Title">
            <textarea class="additional-section-content" name="section_content" placeholder="Section Content" rows="4"></textarea>
            <button type="button" class="btn btn-custom"  onclick="removeSection(this)">Remove</button>
        `;
        additionalSections.appendChild(newSection);
    }

    function removeSection(button) {
        const additionalSections = document.querySelector('.additional-sections');
        const sectionToRemove = button.parentNode;
        additionalSections.removeChild(sectionToRemove);
    }

    // Function to collect additional sections data before form submission
    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting normally
        
        // Collect additional sections data
        const additionalSectionsData = [];
        const additionalSections = document.querySelectorAll('.additional-section');
        additionalSections.forEach(section => {
            const title = section.querySelector('.additional-section-title').value;
            const content = section.querySelector('.additional-section-content').value;
            additionalSectionsData.push({ title, content });
        });

        // Set the additional sections data in the hidden input field
        document.getElementById('additional-sections-data').innerHTML = `<input type="hidden" name="additional_sections" value='${JSON.stringify(additionalSectionsData)}'>`;

        // Submit the form
        this.submit();
    });
</script>

<script>
    // Function to handle form submission via AJAX
    document.getElementById('goalForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting normally
        
        // Collect form data
        const formData = new FormData(this);

        // Additional sections data
        const additionalSectionsData = [];
        const additionalSections = document.querySelectorAll('.additional-section');
        additionalSections.forEach(section => {
            const title = section.querySelector('.additional-section-title').value;
            const content = section.querySelector('.additional-section-content').value;
            additionalSectionsData.push({ title, content });
        });
        // Append additional sections data to form data
        formData.append('additional_sections', JSON.stringify(additionalSectionsData));

        // AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'goal1.php', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Successful response
                alert(xhr.responseText); // Display response message
                // Optionally, reset the form
                document.getElementById('goalForm').reset();
            } else {
                // Error handling
                alert('Error: ' + xhr.statusText);
            }
        };
        xhr.onerror = function() {
            // Network error
            alert('Network Error');
        };
        xhr.send(formData);
    });
</script>

<script>
    // Function to toggle the visibility of the form container
    function toggleGoalForm() {
        var goalFormContainer = document.getElementById("goalFormContainer");
        if (goalFormContainer.style.display === "none") {
            goalFormContainer.style.display = "block";
        } else {
            goalFormContainer.style.display = "none";
        }
    }

    // Add an event listener to the button to toggle the form visibility
    var addGoalButton = document.getElementById("addGoalButton");
    addGoalButton.addEventListener("click", toggleGoalForm);
</script>

</div>
</div>

    </main>
</section>

<div class="container1 mt-5">
    <?php
    // Include the database connection file
    include("dbconn.php");

    // Fetch goals with additional sections for the logged-in manager
    $managerId = $_SESSION['manager_id']; // Assuming you have stored manager_id in session
    $selectGoalsQuery = "SELECT goals.*, additional_sections.section_title, additional_sections.section_content
                         FROM goals
                         LEFT JOIN additional_sections ON goals.goal_id = additional_sections.goal_id
                         WHERE goals.manager_id = '$managerId'";
    $goalsResult = mysqli_query($conn, $selectGoalsQuery);

    // Check if goals are found
    if (mysqli_num_rows($goalsResult) > 0) {
    ?>

        <!-- Table to display goals -->
        <div class="table-container">
            <table class="table" id="goalTable">
                <thead class="sticky-heading">
                    <tr>
                        <th>Team Name</th>
                        <th>Goal Title</th>
                        <th>Summary</th>
                        <?php
                        // Fetch distinct additional section titles from the database
                        $sectionTitlesQuery = "SELECT DISTINCT section_title FROM additional_sections";
                        $sectionTitlesResult = mysqli_query($conn, $sectionTitlesQuery);
                        while ($sectionTitleRow = mysqli_fetch_assoc($sectionTitlesResult)) {
                            echo "<th>" . $sectionTitleRow['section_title'] . "</th>";
                        }
                        ?>
                        <th style="padding-left: 80px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through each goal
                    while ($goal = mysqli_fetch_assoc($goalsResult)) {
                        // Fetch team name from the database based on team_id
                        $teamId = $goal['team_id'];
                        $teamQuery = "SELECT team_name FROM teams WHERE team_id = $teamId";
                        $teamResult = mysqli_query($conn, $teamQuery);
                        $teamRow = mysqli_fetch_assoc($teamResult);
                        $teamName = $teamRow['team_name'];
                    ?>
                        <tr>
                            <td><?php echo $teamName; ?></td>
                            <td><?php echo $goal['goal_title']; ?></td>
                            <td><?php echo $goal['summary']; ?></td>
                            <?php
                            // Fetch additional section content for each title
                            $additionalSectionsQuery = "SELECT section_title, section_content FROM additional_sections WHERE goal_id = " . $goal['goal_id'];
                            $additionalSectionsResult = mysqli_query($conn, $additionalSectionsQuery);
                            $additionalSections = [];
                            while ($additionalSectionRow = mysqli_fetch_assoc($additionalSectionsResult)) {
                                $additionalSections[$additionalSectionRow['section_title']] = $additionalSectionRow['section_content'];
                            }
                            // Display additional section content in corresponding columns
                            $sectionTitlesResult = mysqli_query($conn, $sectionTitlesQuery);
                            while ($sectionTitleRow = mysqli_fetch_assoc($sectionTitlesResult)) {
                                $sectionTitle = $sectionTitleRow['section_title'];
                                echo "<td>";
                                if (isset($additionalSections[$sectionTitle])) {
                                    echo $additionalSections[$sectionTitle];
                                } else {
                                    echo "-";
                                }
                                echo "</td>";
                            }
                            ?>
                            <td>
                                <button class='btn btn-primary1' onclick="deleteGoal(<?php echo $goal['goal_id']; ?>)">Delete</button>
                                <button class='btn btn-primary1' onclick="toggleGoalDetails(<?php echo $goal['goal_id']; ?>)">View</button>
                                </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            
            <div class="goal-details-container">
    <?php
    // Fetch and display goal details when the "View" button is clicked
    mysqli_data_seek($goalsResult, 0); // Reset the pointer to the beginning of the result set
    while ($goal = mysqli_fetch_assoc($goalsResult)) {
        echo "<div id='goalDetails" . $goal['goal_id'] . "' style='display: none;'>";

        // Start of PHP echoed content with CSS class applied
        echo "<div class='goal-details-content'>"; 

        echo "<h4>Goal Details</h4>";

        // Fetch team name from the 'teams' table using team_id
        $teamId = $goal['team_id'];
        $teamQuery = "SELECT team_name FROM teams WHERE team_id = $teamId";
        $teamResult = mysqli_query($conn, $teamQuery);
        $teamRow = mysqli_fetch_assoc($teamResult);
        $teamName = $teamRow['team_name'];

        // Display team name instead of team ID
        echo "<p style='padding-bottom: 25px;'><strong>Team Name:</strong> <span>" . $teamName . "</span></p> ";

        echo "<p style='padding-bottom: 25px;'><strong>Goal Title:</strong> <span>" . $goal['goal_title'] . "</span></p>";
        echo "<p style='padding-bottom: 25px;'><strong >Summary:</strong> <span>" . $goal['summary'] . "</span></p>";
        echo "<p style='padding-bottom: 25px;'><strong>Accomplishment:</strong> <span>" . $goal['accomplishment'] . "</span></p>";
        echo "<p style='padding-bottom: 25px;'><strong>Next Steps:</strong> <span>" . $goal['next_steps'] . "</span></p>";

        // Fetch and display additional sections
        $additionalSectionsQuery = "SELECT * FROM additional_sections WHERE goal_id = " . $goal['goal_id'];
        $additionalSectionsResult = mysqli_query($conn, $additionalSectionsQuery);
        while ($additionalSectionRow = mysqli_fetch_assoc($additionalSectionsResult)) {
            echo "<strong>" . $additionalSectionRow['section_title'] .":". "</strong>";
            echo "<p>" . $additionalSectionRow['section_content'] . "</p>";
        }

        // End of PHP echoed content with CSS class applied
        echo "</div>"; 

        echo "</div>";
    }
    ?>
</div>


    <?php
    } else {
        echo "<h3 style='color: black;'>No goals found</h3>";
        echo "<p style='color: black;'>You haven't created any goals yet. Start by creating a new goal!</p>";
    }
    ?>
</div>
        </div>
      
<script>
    function toggleGoalDetails(goalId) {
        var goalDetails = document.getElementById('goalDetails' + goalId);
        if (goalDetails.style.display === 'none') {
            goalDetails.style.display = 'block';
        } else {
            goalDetails.style.display = 'none';
        }
    }
</script>

<script>
    function deleteGoal(goalId) {
        if (confirm("Are you sure you want to delete this goal?")) {
            // Redirect to the PHP script with the goal ID as a parameter
            window.location.href = "delete_goal.php?goal_id=" + goalId;
        }
    }
</script>
 
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
   
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

</body>
</html>
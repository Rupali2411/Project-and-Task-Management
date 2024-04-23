<?php include("dbconn.php"); 
// Start the session
session_start();
include("dbconn.php");
// Assume manager_id is passed through a session variable after login
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

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $managerId = $_SESSION['manager_id'];
    $taskId = $_POST['task_id'];
    $messageContent = $_POST['message_content'];
    $assigneeId = $_POST['assignee']; // Assuming you have a way to get the assignee ID

    // Insert message into the database
    $query = "INSERT INTO chat_messages (task_id, sender_id, receiver_id, message_content)
              VALUES ('$taskId', '$managerId', '$assigneeId', '$messageContent')";

if(mysqli_query($conn, $query)) {
    // Redirect to task_progress.php after successful insertion
    
        // $redirectUrl = "task_progress.php?type=" . $_GET['type'] . "&task_id=" . $task_id;
    } else {
        $redirectUrl = "progress.php?task_id=" . $task_id;
    }
    header('Location: ' . $redirectUrl); // Redirect to the constructed URL
    exit;
} else {
    // Handle the case where the query fails
    // echo "Error: " . $query . "<br>" . mysqli_error($conn);
}


// $managerId = $_SESSION['manager_id'];


// Query to fetch all tasks from the task details table
$sql = "SELECT t.*, p.project_name, e.emp_name AS assignee_name
        FROM tasksdetails t
        LEFT JOIN projectsnew p ON t.project_id = p.project_id
        LEFT JOIN employees_login e ON t.assignee = e.emp_id";

$result = $conn->query($sql);



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
.user-emailid{
  color: white;
}     

    .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #333;
            padding-top: 20px;
            color: white;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #333;
            color: white;
        }
 
        .openChatBtn {
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .openChatBtn:hover {
            background-color: #0056b3;
        }

    /* Chat Box styles */
    #chatBox {
        border: 1px solid #ccc;
        border-radius: 5px;
        overflow: hidden;
        width: 1250px;
        margin-left: 20px;
        margin-bottom: 20px;
        font-family: Arial, sans-serif;
    }

    /* Message Container styles */
    #messageContainer {
        max-height: 400px;
        overflow-y: auto;
        padding: 10px;
    }

    /* Individual Message styles */
    .message {
        margin-bottom: 10px;
        overflow: hidden;
    }

    /* Sender Name styles */
    .sender-name {
        font-weight: bold;
        color: #333;
        margin-bottom: 5px;
    }

    /* Message Bubble styles */
    .message-bubble {
        max-width: 70%;
        padding: 8px 12px;
        border-radius: 20px;
        clear: both;
        word-wrap: break-word;
    }

    /* Sender Message Bubble styles */
    .sender-bubble {
        background-color: #dcf8c6;
        float: right;
    }

    /* Receiver Message Bubble styles */
    .receiver-bubble {
        background-color: #e5e5ea;
        float: left;
    }

    /* Time styles */
    .message-time {
        font-size: 12px;
        color: #888;
        margin-top: 5px;
    }

    /* Form styles */
    #messageForm {
        padding: 10px;
        border-top: 1px solid #ccc;
        background-color: #f2f2f2;
    }

    /* Textarea styles */
    #messageInput {
        width: calc(100% - 70px);
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        resize: none;
    }

    /* Send button styles */
    #sendMessageBtn {
        width: 60px;
        padding: 8px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    /* Send button hover styles */
    #sendMessageBtn:hover {
        background-color: #0056b3;
    }
    .message {
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 5px;
}

.sent {
    background-color: #DCF8C6;
    align-self: flex-end;
}

.received {
    background-color: #EAEAEA;
    align-self: flex-start;
}
.chat-box {
        width: 100%;
        max-width: 600px;
        margin: 20px auto;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .message {
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 10px;
        max-width: 80%;
    }

    .sent {
        background-color: #DCF8C6; /* Light green background for sender's messages */
        float: left; /* Align sender's messages to the left */
    }

    .received {
        background-color: #E5E5EA; /* Light gray background for receiver's messages */
        float: right; /* Align receiver's messages to the right */
    }

    .timestamp {
        font-size: 0.8em;
        color: #777;
    }
/* Style for the message container */
.message {
    margin-bottom: 10px;
}

/* Style for the message container */
.message-container {
    max-width: 600px; /* Adjust as needed */
    margin: 0 auto;
    padding: 10px;
}

/* Style for individual message */
.message {
    margin-bottom: 10px;
}

/* Style for sender's message */
.sender-bubble {
    background-color: #dcf8c6;
    padding: 10px;
    border-radius: 10px;
    float: right;
    clear: both;
    color: #000;
}

/* Style for receiver's message */
.receiver-bubble {
    background-color: #ffffff;
    padding: 10px;
    border-radius: 10px;
    float: left;
    clear: both;
    color: #000;

}
        .filter-section {
            margin-bottom: 20px;
            margin-left: 260px;
            padding-top: 50px;

        }

        .filter-section select {
            padding: 10px;
            font-size: 16px;
            margin-left: 15px;

            
        }

        .filter-section input[type="text"] {
            padding: 10px;
            font-size: 16px;

        }

        .filter-btn {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 25px;
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
    <div class="filter-section">
    <input type="text" id="search-filter" placeholder="Search by Employee or Task Title">
    <select id="status-filter">
        <option value="all">All Statuses</option>
        <option value="completed">Completed</option>
        <option value="pending">Pending</option>
        <option value="hold">On Hold</option>
        <option value="overdue">Overdue</option>
    </select>
    <button class="filter-btn" onclick="applyFilters()">Apply Filters</button>
</div>



    <div class="content">
    

    <table>    
         <thead>
        <tr>
            <th>Project</th>
            <th>Task Title</th>
            <th>Assignee</th>
            <th>Status</th>
            <th>Assigned Date</th>
            <th>Due Date</th>
            <th>Last Updated</th>
            <th>Chat</th>

        </tr>
    </thead>
    <tbody>
        <?php
        // Loop through each task and display it in the table
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["project_name"] . "</td>";
                echo "<td>" . $row["task_title"] . "</td>";
                echo "<td>" . $row["assignee_name"] . "</td>";
                echo "<td>" . $row["status"] . "</td>";
                echo "<td>" . $row["created_at"] . "</td>";
                echo "<td>" . $row["due_date"] . "</td>";
                echo "<td>";
                // Check if status_updated_at is NULL
                if ($row['status_updated_at'] != NULL) {
                    echo $row['status_updated_at'];
                } else {
                    echo "Status Not Updated";
                }
                echo "</td>";             
                
                $assigneeName = isset($row['assignee_name']) ? $row['assignee_name'] : ''; 
                echo"<td><button class='openChatBtn' data-task-id='" . $row['task_id'] . "' data-assignee-id='" . $row['assignee_name'] . "'>Open Chat</button></td>";

                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No tasks found</td></tr>";
        }
        ?>
    </tbody>
</table>

<!-- Notification area to display task updates -->
<div class="notification" id="notification">
        <!-- Content will be dynamically added here -->
    </div>

<!-- Chat Box -->
<div id="chatBox" style="display: none;">
<div id="messageContainer" class="message-container">
        <!-- Messages will be displayed here -->
    </div>
    <form id="messageForm" action="progress.php?" method="post">
        <input type="hidden" id="taskIdInput" name="task_id">
        <input type="hidden" id="assigneeInput" name="assignee">
        <textarea id="messageInput" name="message_content" placeholder="Type your message..."></textarea>
        <button type="submit" id="sendMessageBtn">Send</button>
    </form>
</div>

<!-- Include jQuery library -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

$(document).ready(function() {
    // Function to open chat box when the button is clicked
    $(document).on('click', '.openChatBtn', function() {
        var taskId = $(this).data('task-id');
        var assigneeId = $(this).data('assignee-id');
        var taskTitle = $(this).closest('tr').find('td:eq(1)').text(); // Get the task title from the closest row
        $('#chatBox').show();
        loadChatHistory(taskId);
        $('#taskIdInput').val(taskId);
        $('#assigneeInput').val(assigneeId);
        // Show notification in the notification container
        $('#notification').html('Chat opened for task: ' + taskTitle);
    });

    // Function to load chat history for a specific task
    function loadChatHistory(taskId) {
        $.getJSON('get_messages.php?task_id=' + taskId, function(messages) {
            // Sort messages based on timestamp in descending order
            messages.sort(function(a, b) {
                return new Date(b.timestamp) - new Date(a.timestamp);
            });

            // Display messages in message container
            var messageContainer = $('#messageContainer');
            messageContainer.empty(); // Clear previous messages
            messages.forEach(function(message) {
                var bubbleClass = (message.sender_id === '<?php echo $_SESSION["manager_id"]; ?>') ? 'sender-bubble' : 'receiver-bubble';
                var messageHtml = "<div class='message " + bubbleClass + "'>" +
                                    "<div class='message-content'>" + message.message_content + "</div>" +
                                    "<div class='message-timestamp'>" + message.timestamp + "</div>" +
                                "</div>";
                messageContainer.prepend(messageHtml); // Prepend each message
            });
        });
    }

    // Submit message form via AJAX
    $('#messageForm').submit(function(event) {
        event.preventDefault(); // Prevent default form submission
        var messageContent = $('#messageInput').val().trim(); // Get message content and trim leading/trailing spaces
        if (messageContent !== '') { // Check if message content is not blank
            var formData = $(this).serialize(); // Serialize form data
            $.post('task_progress.php', formData, function(response) {
                // Reload chat history after sending message
                var taskId = $('#taskIdInput').val();
                loadChatHistory(taskId);
            });
            // Clear message input field after sending message
            $('#messageInput').val('');
        } else {
            // Display an alert or message indicating that the message cannot be blank
            alert('Message cannot be blank.');
        }
    });
});

</script>   

<script>
    // Function to apply filters
    function applyFilters() {
        var statusFilter = $('#status-filter').val().toLowerCase(); // Get selected status filter value
        var searchValue = $('#search-filter').val().toLowerCase(); // Get search value
        
        // Loop through each row in the table
        $('table tbody tr').each(function() {
            var status = $(this).find('td:eq(3)').text().toLowerCase(); // Get status value from the fourth column (index 3)
            var taskTitle = $(this).find('td:eq(1)').text().toLowerCase(); // Get task title value from the second column (index 1)
            var assignee = $(this).find('td:eq(2)').text().toLowerCase(); // Get assignee value from the third column (index 2)

            // Check if the row matches the selected status filter and search value
            var statusMatch = (statusFilter === 'all' || status === statusFilter);
            var searchMatch = (taskTitle.includes(searchValue) || assignee.includes(searchValue));

            // Show or hide the row based on the selected status filter and search value
            if (statusMatch && searchMatch) {
                $(this).show(); // Show the row
            } else {
                $(this).hide(); // Hide the row
            }
        });
    }
</script>


</body>

</html>

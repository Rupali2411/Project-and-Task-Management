<?php
include("dbconn.php");

if (isset($_POST['update'])) {
    $empId = mysqli_real_escape_string($conn, $_POST['emp_id']);
    $empName = mysqli_real_escape_string($conn, $_POST['emp_name']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $emailId = mysqli_real_escape_string($conn, $_POST['email_id']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
  // Check if a new password is provided
  if (!empty($_POST['emp_password'])) {
    $empPassword = password_hash($_POST['emp_password'], PASSWORD_DEFAULT); // Hash the new password
    $query = "UPDATE employees_login SET 
              emp_name='$empName', 
              contact='$contact', 
              email_id='$emailId', 
              department='$department', 
              emp_password='$empPassword' 
              WHERE emp_id='$empId'";
} else {
    // If no new password is provided, keep the existing hashed password
    $query = "UPDATE employees_login SET 
              emp_name='$empName', 
              contact='$contact', 
              email_id='$emailId', 
              department='$department' 
              WHERE emp_id='$empId'";
}

if (mysqli_query($conn, $query)) {
    // Update successful, redirect to empdash.php
    header("Location: empdash.php");
    exit();
} else {
    echo "Error updating record: " . mysqli_error($conn);
}
} else {
    // echo "Invalid parameters!";
}
// Start the session
session_start();

// Check if the employee is logged in
if (!isset($_SESSION['emp_id'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit();
}

// Retrieve emp_id from the session
$empId = $_SESSION['emp_id'];

// Fetch existing details from the database
$query = "SELECT * FROM employees_login WHERE emp_id = '$empId'";
$result = mysqli_query($conn, $query);

if ($result) {
    // Fetch the employee details
    $employeeDetails = mysqli_fetch_assoc($result);

    // You can use $employeeDetails to pre-fill the form fields
} else {
    echo "Error: " . mysqli_error($conn);
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <!-- My CSS -->
    <link rel="stylesheet" href="css/dashstyle.css">
   <title>Project Management</title>

    <style>
body {
        background-image: url('../bgimage1/sign-page-illustration-design-template_559664-157.avif');
        height: 800px;
        width: 600;
        background-size: auto; 
        
            font-family: 'Arial', sans-serif;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #6f42c1;
        }
        .form-control {
            border-radius: 5px;
        }

        .my-login-page .reg{
	width: 90px;
	height: 90px;
	overflow: hidden;
	border-radius: 50%;
	margin: 40px auto;
	position: relative;
	z-index: 1;
}
.my-login-page  {
	height: px;
}
.my-login-page .card-wrapper {
	width: 400px;
}
.my-login-page .card {
	border-color: transparent;
	box-shadow: 0 4px 8px rgba(0,0,0,.05);
}
.my-login-page .card.fat {
	padding: 10px;
    margin-top: 100px;
}
.my-login-page .card {
	margin-bottom: 30px;
}
.my-login-page .form-control {
	border-width: 2.3px;
    padding-right: 60px;
}
.my-login-page .form-group label {
	width: 100%;
}
.my-login-page .btn.btn-block {
    height: 45px;
    width: 250px;
    font-size: 20px;
    align-items: center;
    margin-left: 150px;
    background-color: #3745bdf1;
    border-color: #3745bdf1;
     
}
.btn {
        transition: background-color 0.3s, transform 0.3s;
    }

    .btn:hover {
        background-color: #4e2a77; /* Change the color to your desired hover color */
        transform: scale(1.1); /* Adjust the scale factor for zoom-in effect */
    }
.card-title  {
	margin: 40px 0;
	color: #aa91f3f7;
	text-align: center;
    font-size: 33px;
}
.card-body{
    height: 560px; 
    width: 650px; 
    padding-left: 40px;
    padding-top: 0px;
    font-size: large;
	padding-left: 40px;
    padding-right: 40px;
    background-color: rgba(16, 16, 17, 0.834);
    box-shadow: 0 0 8px rgba(11, 11, 11, 0.562);

}
.card.fat{
    height: 500px;
    width: 650px;
	margin-top: 180px;
    padding-left: 40px;
    background-image: url('../bgimage1/completed-steps-concept-illustration_114360-5521.avif');
    color: #f5f1f1;
    font-size: 15px;
   
}
.valid-feedback{
    color: rgb(10, 230, 10);
    font-size: 15px;
    /* font-weight: bold; */
}
.invalid-feedback{
    color: rgb(251, 6, 6);
    font-size: 16px;
}
.message {
    margin-top: 10px;
}
.success {
    color: #4CAF50;
}
.danger {
    color: #FF0000;
}

</style>
</head>

<body>
<script
      src="https://code.jquery.com/jquery-3.6.0.min.js"
      integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js"
      referrerpolicy="origin"
    ></script>
    <script src="https://cdn.jsdelivr.net/npm/@tinymce/tinymce-jquery@2/dist/tinymce-jquery.min.js"></script>

    <script>
    var empIdInput = document.getElementById('empid').value;
    var empNameInput = document.getElementById('empname').value;
    var contactInput = document.getElementById('contact').value;
    var emailInput = document.getElementById('email').value;
    var skillsInput = document.getElementById('skill').value;
    var passwordInput = document.getElementById('emp_password').value;

function validateEmpId() {
      var empIdInput = document.getElementById('empid').value;
      var empIdPattern = /^[A-Za-z0-9]+$/;

      if (empIdPattern.test(empIdInput)) {
        document.getElementById('empid').classList.remove('is-invalid');
        document.getElementById('empid').classList.add('is-valid');
        return true;
      } else {
        document.getElementById('empid').classList.remove('is-valid');
        document.getElementById('empid').classList.add('is-invalid');
        return false;
      }
    }

    function validateEmpName() {
      var empNameInput = document.getElementById('empname').value;
      var empNamePattern = /^[A-Za-z ]+$/;

      if (empNamePattern.test(empNameInput)) {
        document.getElementById('empname').classList.remove('is-invalid');
        document.getElementById('empname').classList.add('is-valid');
        return true;
      } else {
        document.getElementById('empname').classList.remove('is-valid');
        document.getElementById('empname').classList.add('is-invalid');
        return false;
      }
    }

function validateContact() {
      var contactInput = document.getElementById('contact').value;
      var contactPattern = /^\d{10}$/;  // Enforces exactly 10 digits

      if (contactPattern.test(contactInput)) {
        document.getElementById('contact').classList.remove('is-invalid');
        document.getElementById('contact').classList.add('is-valid');
        return true;
      } else {
        document.getElementById('contact').classList.remove('is-valid');
        document.getElementById('contact').classList.add('is-invalid');
        return false;
      }
    }

    function validateEmail() {
      var emailInput = document.getElementById('email').value;
      var emailPattern = /^[a-zA-Z0-9._-]+@[a-z.-]+\.[a-zA-Z]{2,4}$/;

      if (emailPattern.test(emailInput)) {
        document.getElementById('email').classList.remove('is-invalid');
        document.getElementById('email').classList.add('is-valid');
        return true;
      } else {
        document.getElementById('email').classList.remove('is-valid');
        document.getElementById('email').classList.add('is-invalid');
        return false;
      }
    }   

    function validateSkills() {
      var skillsInput = document.getElementById('skill').value;
      var skillsPattern = /^[A-Za-z, ]+$/;

      if (skillsPattern.test(skillsInput)) {
        document.getElementById('skill').classList.remove('is-invalid');
        document.getElementById('skill').classList.add('is-valid');
        return true;
      } else {
        document.getElementById('skill').classList.remove('is-valid');
        document.getElementById('skill').classList.add('is-invalid');
        return false;
      }
    }

    function validatePassword() {
    var passwordInput = document.getElementById('mangr_password').value;
    var passwordPattern = /^[^\s]+$/;

    if (passwordPattern.test(passwordInput)) {
        document.getElementById('mangr_password').classList.remove('is-invalid');
        document.getElementById('mangr_password').classList.add('is-valid');
        return true;
    } else {
        document.getElementById('mangr_password').classList.remove('is-valid');
        document.getElementById('mangr_password').classList.add('is-invalid');
        return false;
    }
}

  </script>

</head>
    
<body class="my-login-page">
<section class="h-100">
        <div class="container h-100">
            <div class="row justify-content-md-center h-100">               
                    <div class="card fat">
                        <div class="card-body">
							
                            <h4 class="card-title">Register here!</h4>

                      <div class="message">
                          <div id="successMsg" class="success" style="display: none;">
                              Details added successfully!
                          </div>
                      </div>

                    <div class="message">
                          <div id="errorMsg" class="danger" style="display: none;">
                              Fill all the details!
                          </div>
                     </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
     
<form class="styled-form needs-validation" method="post" action="editemp.php" enctype="multipart/form-data" novalidate>

<div class="form-row">
        <div class="form-group col-md-6">
            <input type="text"  class="form-control" id="empid" name="emp_id" value="<?php echo $employeeDetails['emp_id']; ?>" readonly>
            <div class="valid-feedback">
                Looks good!
            </div>
            <div class="invalid-feedback">
                Enter Emp ID.
            </div>
        </div>

        <div class="form-group col-md-6" style="padding-left:20px;">
            <input type="text" class="form-control" id="empname" name="emp_name" value="<?php echo $employeeDetails['emp_name']; ?>" required>
            <div class="valid-feedback">
                Looks good!
            </div>
            <div class="invalid-feedback">
                Please enter your name (only characters allowed).
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
              <input type="text" class="form-control" id="contact" name="contact" value="<?php echo $employeeDetails['contact']; ?>" required>
                <div class="valid-feedback">
                    Looks good!
                </div>
                <div class="invalid-feedback">
                    Please enter a valid contact number (only numbers allowed).
                </div>
        </div>

        <div class="form-group col-md-6" style="padding-left:20px;">
              <input type="text" class="form-control" id="email" name="email_id" value="<?php echo $employeeDetails['email_id']; ?>" required>
                <div class="valid-feedback">
                    Looks good!
                </div>
                <div class="invalid-feedback">
                    Please enter a valid email address.
                </div>
        </div>
    </div>
    <script>
                            function validateDepartment() {
                                var departmentInput = document.getElementById("department");
                                var departmentList = document.getElementById("departmentOptions");
                                var validDepartment = false;

                                for (var i = 0; i < departmentList.options.length; i++) {
                                    if (departmentInput.value.toLowerCase() === departmentList.options[i].value.toLowerCase()) {
                                        validDepartment = true;
                                        break;
                                    }
                                }

                                if (validDepartment) {
                                    departmentInput.setCustomValidity("");
                                    departmentInput.classList.add("is-valid");
                                    departmentInput.classList.remove("is-invalid");
                                } else {
                                    departmentInput.setCustomValidity("Please enter a valid department.");
                                    departmentInput.classList.add("is-invalid");
                                    departmentInput.classList.remove("is-valid");
                                }
                            }
     </script>

<div class="form-row">
        <div class="form-group col-md-6">
            <select class="form-control" id="inputdepartment" name="department" required>
                <option value="Human Resources" <?php echo ($employeeDetails['department'] == 'Human Resources') ? 'selected' : ''; ?>>Human Resources</option>
                <option value="Finance" <?php echo ($employeeDetails['department'] == 'Finance') ? 'selected' : ''; ?>>Finance</option>
                                <option value="Accounting" <?php echo ($employeeDetails['department'] == 'Accounting') ? 'selected' : ''; ?>>Accounting</option>
                                <option value="IT" <?php echo ($employeeDetails['department'] == 'IT') ? 'selected' : ''; ?>>IT</option>
                                <option value="Marketing" <?php echo ($employeeDetails['department'] == 'Marketing') ? 'selected' : ''; ?>>Marketing</option>
                                <option value="Sales"<?php echo ($employeeDetails['department'] == 'Sales') ? 'selected' : ''; ?>>Sales</option>
                                <option value="Customer Service/Support" <?php echo ($employeeDetails['department'] == 'Customer Service/Support') ? 'selected' : ''; ?>>Customer Service/Support</option>
                                <option value="Operations/Operations Management"<?php echo ($employeeDetails['department'] == 'Operations/Operations Management') ? 'selected' : ''; ?>>Operations/Operations Management</option>
                                <option value="Research and Development (R&D)"<?php echo ($employeeDetails['department'] == 'Research and Development (R&D)') ? 'selected' : ''; ?>>Research and Development (R&D)</option>
                                <option value="Quality Assurance/Quality Control"<?php echo ($employeeDetails['department'] == 'Quality Assurance/Quality Control') ? 'selected' : ''; ?>>Quality Assurance/Quality Control</option>
                                <option value="Legal/Compliance"<?php echo ($employeeDetails['department'] == 'Legal/Compliance') ? 'selected' : ''; ?>>Legal/Compliance</option>
                                <option value="Supply Chain/Logistics" <?php echo ($employeeDetails['department'] == 'Supply Chain/Logistics') ? 'selected' : ''; ?>>Supply Chain/Logistics</option>
                                <option value="Public Relations (PR)" <?php echo ($employeeDetails['department'] == 'Public Relations (PR)') ? 'selected' : ''; ?>>Public Relations (PR)</option>
                                <option value="Administration" <?php echo ($employeeDetails['department'] == 'Administration') ? 'selected' : ''; ?>>Administration</option>
                                <option value="Project Management"<?php echo ($employeeDetails['department'] == 'Project Management') ? 'selected' : ''; ?>>Project Management</option>
                                <option value="Health and Safety"<?php echo ($employeeDetails['department'] == 'Health and Safety') ? 'selected' : ''; ?>>Health and Safety</option>
                                <option value="Training and Development"<?php echo ($employeeDetails['department'] == 'Training and Development') ? 'selected' : ''; ?>>Training and Development</option>
                                <option value="Procurement/Purchasing" <?php echo ($employeeDetails['department'] == 'Procurement/Purchasing') ? 'selected' : ''; ?>>Procurement/Purchasing</option>
                                <option value="Internal Audit"<?php echo ($employeeDetails['department'] == 'Internal Audit') ? 'selected' : ''; ?>>Internal Audit</option>
                                <option value="Public Affairs/Government Relations"<?php echo ($employeeDetails['department'] == 'Public Affairs/Government Relations') ? 'selected' : ''; ?>>Public Affairs/Government Relations</option>
                                <option value="Environmental, Social, and Governance (ESG)" <?php echo ($employeeDetails['department'] == 'Environmental, Social, and Governance (ESG)') ? 'selected' : ''; ?>>Environmental, Social, and Governance (ESG)</option>
                            </select>
                                        <!-- Add more department names as needed -->
                                </datalist>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                                <div class="invalid-feedback">
                                    Please enter a valid department.
                                </div>
                            </div>
                   
                <div class="form-group col-md-6" style="padding-left:20px;">
                  <input type="password" class="form-control" id="emp_password" onkeyup="validatePassword()" name="emp_password" placeholder="New Password">
                  <div class="valid-feedback">
                      Looks good!
                  </div>
                  <div class="invalid-feedback">
                      Please enter skills accordingly (only characters allowed).
                  </div>
              </div>
    </div>

    <button type="submit" class="btn btn-primary btn-block" name="update" onclick="message(false)">Update</button>

<p id="errorMessage" class="error"></p>
</form>

<script>
   function message(duplicateEntry) {
    var empIdInput = document.getElementById('empid');
    var empNameInput = document.getElementById('empname');
    var contactInput = document.getElementById('contact');
    var emailInput = document.getElementById('email');
    var deprtInput = document.getElementById('department');
    var skillsInput = document.getElementById('skill');
    var passwordInput = document.getElementById('emp_password');


    if (empIdInput.value === '' || empNameInput.value === '' || contactInput.value === '' || emailInput.value === '' || deprtInput.value === '' || passwordInput.value === '') {
        showError('Fill all the details!');
    } else if (duplicateEntry) {
        showduplicateError();
    } else {
        showSuccess();
    }
}

function showSuccess() {
    Swal.fire({
        icon: 'success',
        title: 'Details added successfully!',
        showConfirmButton: true,
        timer: 90000000,

    });
}

function showError(danger) {
    Swal.fire({
        icon: 'error',
        title: danger,
        showConfirmButton: true,
        timer: 30000
    });
}
</script>
 
  </div>
</div>
</div>
    </main>
</section>
</body>
</html>

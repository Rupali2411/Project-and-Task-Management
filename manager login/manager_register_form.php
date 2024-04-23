
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
    margin-top: 15px;
     
}

.btn {
        transition: background-color 0.3s, transform 0.3s;
    }

    .btn:hover {
        background-color: #4e2a77; /* Change the color to your desired hover color */
        transform: scale(1.1); /* Adjust the scale factor for zoom-in effect */
    }

.card-title  {
	margin: 30px 0;
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

    <script>
    function validateForm() {
        var isValid = true;

        // Call individual validation functions and update isValid accordingly
        if (!validateEmpId()) {
            isValid = false;
        }

        if (!validateEmpName()) {
            isValid = false;
        }

        if (!validateContact()) {
            isValid = false;
        }

        if (!validateEmail()) {
            isValid = false;
        }

        if (!validatePassword()) {
            isValid = false;
        }

        return isValid;
    }

    function validateEmpId() {
        var empIdInput = document.getElementById('managerid').value;
        var empIdPattern = /^[A-Za-z0-9]+$/;

        if (empIdPattern.test(empIdInput)) {
            document.getElementById('managerid').classList.remove('is-invalid');
            document.getElementById('managerid').classList.add('is-valid');
            return true;
        } else {
            document.getElementById('managerid').classList.remove('is-valid');
            document.getElementById('managerid').classList.add('is-invalid');
            return false;
        }
    }

    function validateEmpName() {
        var empNameInput = document.getElementById('name').value;
        var empNamePattern = /^[A-Za-z ]+$/;

        if (empNamePattern.test(empNameInput) && empNameInput.trim() !== "") {
            document.getElementById('name').classList.remove('is-invalid');
            document.getElementById('name').classList.add('is-valid');
            return true;
        } else {
            document.getElementById('name').classList.remove('is-valid');
            document.getElementById('name').classList.add('is-invalid');
            return false;
        }
    }

    function validateContact() {
        var contactInput = document.getElementById('contact').value;
        var contactPattern = /^\d{10}$/;

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
        var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

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

    function validatePassword() {
    var passwordInput = document.getElementById('mangr_password').value;
    var passwordPattern = /^[\s\S]{4,}$/;

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

<form class="styled-form needs-validation" method="post" action="process_registration.php" enctype="multipart/form-data" novalidate onsubmit="return validateForm()">

        <div class="form-row">
            <div class="form-group col-md-6">
                <input type="text" class="form-control" id="managerid" onkeyup="validateEmpId()" name="manager_id" placeholder="Emp ID" pattern="[A-Za-z0-9]+" title="Only characters and numbers allowed" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                    <div class="invalid-feedback">
                        Enter Emp ID.
                    </div>
            </div>

            <div class="form-group col-md-6" >
                <input type="text" class="form-control" id="name"  onkeyup="validateEmpName()" name="manager_name" placeholder="Manager Name" pattern="[A-Za-z ]+" title="Only characters allowed" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                    <div class="invalid-feedback">
                        Please enter your name.
                    </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <input type="text" class="form-control" id="contact" onkeyup="validateContact()" name="contact" placeholder="Contact" pattern="[0-9]{10}" title="Only 10 digits numbers allowed" required>
                    <div class="valid-feedback">
                    Looks good!
                    </div>
                    <div class="invalid-feedback">
                    Please enter a valid contact number.
                    </div>
            </div>

            <div class="form-group col-md-6">
                <input type="text" class="form-control" id="email" onkeyup="validateEmail()" name="email_id" placeholder="Email Address" required>
                    <div class="valid-feedback">
                    Looks good!
                    </div>
                    <div class="invalid-feedback">
                    Please enter a valid email address.
                    </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6" >
                <input type="password" class="form-control" id="mangr_password" onkeyup="validatePassword()" name="manager_password" placeholder="Password" required>
                    <div class="valid-feedback">
                    Looks good!
                    </div>
                    <div class="invalid-feedback">
                        Enter a password with at least 4 characters.
                    </div>
            </div>
        </div>  

                <button type="submit" class="btn btn-primary btn-block" name="submit" onclick="message(false)">Submit</button>
                <div class="mt-3 text-center">
                        Already have an account? <a href="login.php">Login</a>
                </div>
</form>
                <p id="errorMessage" class="error"></p>
                    </div>
                </div>
            </div>
        </div>

        <script>
    function message(duplicateEntry) {
        var empIdInput = document.getElementById('managerid');
        var empNameInput = document.getElementById('name');
        var contactInput = document.getElementById('contact');
        var emailInput = document.getElementById('email');
        var passwordInput = document.getElementById('mangr_password');

        if (empIdInput.value === '' || empNameInput.value === '' || contactInput.value === '' || emailInput.value === '' ||  passwordInput.value === '') {
            showError('Fill all the details!');
        } else if (duplicateEntry) {
            showDuplicateError();
        } else {
            submitForm();
        }
    }

    function submitForm() {
    $.ajax({
        url: 'process_registration.php',
        method: 'POST',
        data: $('form').serialize(),
        success: function (response) {
            // Check the response to determine success
            if (response.trim() === 'success') {
                showSuccess();
            } else {
                showError('Error submitting the form.');
            }
        },
        error: function (error) {
            showError('Error submitting the form.');
        }
    });

    // Prevent default form submission
    return false;
}

function showSuccess() {
    Swal.fire({
        icon: 'success',
        title: 'Registered successfully!',
        showConfirmButton: true,
        onClose: () => {
            window.location.href = 'login.html';
        }
    });
}

    function showError(errorMessage) {
        Swal.fire({
            icon: 'error',
            title: errorMessage,
            showConfirmButton: true,
            timer: 5000 // Adjust the timer as needed
        });
    }

    function showDuplicateError() {
        showError('Employee already exists. Please check Emp ID.');
    }
</script>
</section>
</body>
</html>
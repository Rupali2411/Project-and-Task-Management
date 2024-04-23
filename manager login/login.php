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
    <title>User Login</title>
    <style>
     
     html,body {
	height: 100%;
    color: white;
    font-size: 19px;
    
     }

body.my-login-page {
    
    background-image: url('loginbackgroundimage.avif'); 
    background-size: contain; 
    font-size: 14px;

}

.my-login-page .reg{
	width: 90px;
	height: 90px;
	overflow: hidden;
	border-radius: 50%;
	margin: 40px auto;
	box-shadow: 0 4px 8px rgba(0,0,0,.05);
	position: relative;
	z-index: 1;
}


.my-login-page  {
	height: 50px;
}

.my-login-page .card-wrapper {
	width: 400px;
}

.my-login-page .card {
	border-color: transparent;
	box-shadow: 0 4px 8px rgba(0, 0, 0, 0.395);
}

.my-login-page .card.fat {
	padding: 10px;
}

.my-login-page .card {
	margin-bottom: 30px;
}

.my-login-page .form-control {
	border-width: 2.3px;
}

.my-login-page .form-group label {
	width: 100%;
}

.my-login-page .btn.btn-block {
	padding: 9px 10px;
    background-color: #3745bdf1;
    border-bottom: #3745bdf1;
    border-color: #3745bdf1;
}

.btn {
        transition: background-color 0.3s, transform 0.3s;
    }

    .btn:hover {
        background-color: #4e2a77; 
        transform: scale(1.1); 
    }

.my-login-page .footer {
	margin: 40px 0;
	color: #888;
	text-align: center;
}

.text-center1{
    margin-top: 50px;
    color: rgb(24, 19, 91)   ;
    width: 455px;
    text-align: center;
    align-items: center;
    padding-right: 41px;
    margin-top:  120px;
    
}
.form-group{
    padding-right: 30px;
    padding-left: 30px;
    font-size: large;
}


    
.card.fat 
{
   
 background-color: #372d518a;
   
    }

.card-title{
        text-align: center;
    }
.text-center{
    color: rgb(248, 245, 245);
    font-size: 18px;
    
    
}

.valid-feedback{
    color: rgba(92, 249, 92, 0.981);
    font-size: 15px;
    font-weight: bold;
}
 
 
.invalid-feedback{
    color: rgb(245, 244, 244);
    font-size: 18px;
    /* font-weight: bold; */

}
</style>
</head>
<body class="my-login-page">

<script>

var empIdInput = document.getElementById('managerid').value;   
var passwordInput = document.getElementById('manager_password').value;


function validateEmpId() {
  var empIdInput = document.getElementById('managerid').value;
  console.log("Emp ID Input:", empIdInput);  // Add this line for debugging

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

    function validatePassword() {
      var passwordInput = document.getElementById('mangr_password').value;
      var passwordPattern = /^(?=.*[A-Za-z\d!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]).{8,}$/;

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

function togglePasswordVisibility() {
        var passwordInput = document.getElementById('mangr_password');
        var showPassIcon = document.getElementById('show-pass');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            showPassIcon.classList.remove('fa-eye-slash');
            showPassIcon.classList.add('fa-eye');
        } else {
            passwordInput.type = 'password';
            showPassIcon.classList.remove('fa-eye');
            showPassIcon.classList.add('fa-eye-slash');
        }
    }

</script>

    <section class="h-100">
		<div class="container h-100">
			<div class="row justify-content-md-center h-100">
				<div class="card-wrapper">
					<div class="form-container">
                        <h4 class="text-center1 mb-4">Now Login to start your work!</h4>
                        </div>

					<div id="card-form" class="card fat">
						<div class="card-body">
							<h4 class="card-title">Login</h4>

                            <form class="styled-form needs-validation" method="post" action="process_login.php" enctype="multipart/form-data" novalidate onsubmit="return validateEmpId()">
								<div class="form-group">
									<label for="email">Manager ID</label>
                                    <!-- <input type="text" class="form-control" id="emp_id" onkeyup="validateEmpId()" name="emp_id" title="Only chracters and numbers"> -->
                                    <input type="text" class="form-control" id="managerid" onkeyup="validateEmpId()" name="manager_id" placeholder="Manager ID" pattern="[A-Za-z0-9]+" title="Only characters and numbers allowed" required>

									<div class="valid-feedback">
                                            Looks good!
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter a valid Emp ID.
                                        </div>
                                    </div>

								<div class="form-group" >
                                        <label for="manager_password" class="form-label">Password
                                        </label>
                                    <div class="input-group">
                                        <!-- <input type="password" class="form-control" id="password" onkeyup="validatePassword()" name="emp_password" required> -->
                                        <input type="password" class="form-control" id="mangr_password" onkeyup="validatePassword()" name="manager_password" placeholder="Password" required>

                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fa fa-eye-slash" id="show-pass" onclick="togglePasswordVisibility()"></i>
                                                </span>
                                            </div>
                                    
                                        <div class="valid-feedback">
                                            Looks good!
                                        </div>
                                            <div class="invalid-feedback">
                                                Please enter a password with at least 8 characters.
                                            </div>
                                        </div>
                                    </div>
                              <br>
								<div class="form-group m-0">
									<button type="submit" class="btn btn-primary btn-block" name="login">
										Login
									</button>
								</div>
								<div class="mt-4 text-center" >
                                    Don't have an account? <a href="manager_register_form.php" style="color: aqua;">Create One</a>
                                </div>
							</form>
						</div>
					</div>
					
				</div>
			</div>
		</div>
</section>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="js/my-login.js"></script>
</body>
</html>
<?php 
include 'user_server.php'; 
if (isset($_SESSION['username'])){
  if ($_SESSION['username']=="admin"){
   header('location: dashboard_admin.php');
 }
 else {
   header('location: dashboard.php');
 }
}
$lastname="";
$firstname="";
$middlename="";
$contact="";
$lot_number="";
$block="";
$street_number="";
$street_name="";
$subdivision="";
$barangay="";
$city="";
$sex="";
$db = mysqli_connect('localhost', 'root', '', 'ppdo_iams');
?>

<!DOCTYPE html>
<html>
<head>
  <title>Electronic Inventory System</title>
  <meta charset="utf-8">
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <!-- jQuery library -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <!-- Popper JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <!-- Latest compiled JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>    

  <!-- CSS stylesheet -->
  <link rel="stylesheet" type="text/css" href="css/login_webpage.css">

  <!-- Icons -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <script type="text/javascript">
    function checkPasswordMatch() {
      var password = $("#txtNewPassword").val();
      var confirmPassword = $("#txtConfirmPassword").val();

      if (password != confirmPassword){
        $("#spanCheckPasswordMatch").html("Passwords do not match!");
      }
      else { 

        $("#spanCheckPasswordMatch").html("Passwords match.");

      }

      if (!password.length && !confirmPassword.length) {
        $("#spanCheckPasswordMatch").html("");
      }
    }
    function checkEmailMatch() {
      var email = $("#txtNewEmail").val();
      var confirmEmail = $("#txtConfirmEmail").val();

      if (email != confirmEmail){
        $("#spanCheckEmailMatch").html("Emails do not match!");
      }
      else { 

        $("#spanCheckEmailMatch").html("Emails match.");

      }

      if (!email.length && !confirmEmail.length) {
        $("#spanCheckEmailMatch").html("");
      }
    }
  </script>

</head>
<body class="text-center">
  <div class="container">
   <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#sign_in">Sign_in</a></li>
    <li><a data-toggle="tab" href="#sign_up">Register</a></li>
  </ul>
  <div class="tab-content">
    <div id="sign_in" class="tab-pane fade in active">
      <br />
      <img class="mb-4" src="css/img/inv/logo.png" alt="" width="72" height="72">
      <h1 class="system_title">PPDO-INVENTORY ARCHIVE MAINTENANCE SYSTEM</h1>
      <form method="post" class="form-signin" action="user_login.php">
        <?php include('include/user_errors.php'); ?>
        <h1 class="h3 mb-3 font-weight-normal">Sign in here</h1>
        <label for="loginUname" class="sr-only">Username</label>
        <div class="input-group">
          <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
          <input type="text" name="username" id="inputUname" class="form-control" placeholder="Enter Username" required autofocus>
        </div>
        <label for="inputPassword" class="sr-only">Password</label>
        <div class="input-group">
         <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
         <input type="password" id="inputPassword" class="form-control" placeholder="Enter Password" name="password" required></div>
         <div class="checkbox mb-3">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="login_user" value="LOGIN">Sign in</button>
        <p class="mt-5 mb-3 text-muted">&copy; 2017-2018</p>
      </form>
    </div>

    <div id="sign_up" class="tab-pane fade">
      <form method="post" action="user_register.php" class="form-signup">
       <?php include('include/user_errors.php'); ?>
       <img class="mb-4" src="css/img/inv/logo.png" alt="" width="72" height="72">
       <h1>Registration</h1>
       <h1 class="h3 mb-3 font-weight-normal">Basic Information</h1>
       <label for="inputFname" class="sr-only">Firstname:</label>
       <div class="input-group">
        <input type="text" name="firstname" id="inputFname" class="form-control" placeholder="Firstname" required autofocus>
      </div>
      <label for="inputLname" class="sr-only">Lastname:</label>
      <div class="input-group">
        <input type="text" name="lastname" id="inputLname" class="form-control" placeholder="Lastname" required autofocus>
      </div>

      <label for="inputUname" class="sr-only">Username</label>
      <div class="input-group">
       <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
       <input type="text" name="username" id="inputUname" class="form-control" placeholder="Enter Username" required autofocus>
     </div>

     <label for="inputEmail" class="sr-only">Email:</label>
     <div class="input-group">
       <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
       <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email" required autofocus>
     </div>
     <label for="inputPassword" class="sr-only">Password</label>
     <div class="input-group">
      <input type="password" id="inputPassword" class="form-control" placeholder="Enter Password" name="password" required></div>
      <div class="input-group">
       <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
       <input type="password" id="inputCnfPassword" class="form-control" placeholder="Confirm Password" name="password" required></div>
       <br />
       <div class="input-group">
        <input type="radio" id="male" name="sex" value="Male" checked="checked" >
        <label for="male">Male</label>
        <input type="radio" id="female" name="sex" value="Female">
        <label for="female">Female</label>
      </div>
      <div class="checkbox mb-3">
        <label>
          <input type="checkbox" value="remember-me"> I agree with the Terms and Conditions
        </label>
      </div>
      <button class="btn btn-lg btn-primary btn-block" type="submit" name="login_user" value="LOGIN">Sign up</button>                                                                    
      <p class="mt-5 mb-3 text-muted">&copy; 2017-2018</p>

    </form>


  </div>
</div>
</div>

</body>
</html>


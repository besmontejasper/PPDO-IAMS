<?php 
	include('user_server.php');
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
	<title>Registration</title>
	<link rel="stylesheet" type="text/css" href="css/user_style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
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
<body>
	
	<header>SHERWIN'S CATERING</header>
	
	<form method="post" action="user_register.php" class="form">

		<?php include('include/user_errors.php'); ?>
			<h3 class="basic">Basic Information</h3>
			<p class="sign-in"><i>Fields marked with asterisk (*) are required.</i></p>
			<input type="text" name="firstname" placeholder="First Name*: " pattern="[A-Za-z ]{1,}" value="<?php echo "$firstname";?>" >
			<input type="text" name="middlename" placeholder="Middle Name: " pattern="[A-Za-z ]{1,}" value="<?php echo "$middlename";?>">			
			<input type="text" name="lastname" placeholder="Last Name*: " pattern="[A-Za-z ]{1,}" value="<?php echo "$lastname";?>" >
			<div class="radios">
			<label>Sex: </label>
    		<input type="radio" id="male" name="sex" value="Male" checked="checked" >
    		<label for="male">Male</label>
   			<input type="radio" id="female" name="sex" value="Female">
    		<label for="female">Female</label>
    		</div>
  			<input type="number" name="contact" pattern="[09]+\d{9}|\d{7}|[\+]+[6]+[3]+[9]\d{9}" placeholder="Contact No.*: " required>
  			<input type="email" name="email1" id="txtNewEmail" placeholder="Email*: " pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" value="<?php echo "$email1";?>" required>
			
			<input type="email"  style="border-bottom: 1px solid white;" name="email2" id="txtConfirmEmail" placeholder="Confirm Email*: " pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" value="<?php echo "$email2";?>" onkeyup="checkEmailMatch();" required>
			<span class="registrationFormAlert" id="spanCheckEmailMatch"></span>

  			<h3 class="address">Address</h3>
  			<input type="number" name="lot_number" min="1" placeholder="Lot Number: " value="<?php echo "$lot_number";?>">
			<input type="text" name="block" placeholder="Block: " value="<?php echo "$block";?>">			
			<input type="number" name="street_number" min="1" placeholder="Street Number: " value="<?php echo "$street_number";?>">
			<input type="text" name="street_name" pattern="[A-Za-z]{1,}" placeholder="Street Name*: " value="<?php echo "$street_name";?>" required>
			<input type="text" name="subdivision" placeholder="Subdivision: " value="<?php echo "$subdivision";?>">
			<input type="text" name="barangay" placeholder="Barangay*: " value="<?php echo "$barangay";?>" required>
			<input type="text" style="border-bottom: 1px solid white;" name="city" pattern="[A-Za-z]{1,}" placeholder="City*: " value="<?php echo "$city";?>" required>
					
			<h3 class="login">Log-in Information</h3>
			<input type="text" name="username" pattern=".{2,}" placeholder="Username*:" value="<?php echo "$username";?>" required>
			<input type="password" name="password_1" pattern=".{6,}" title="Password must have at least 6 characters" placeholder="Password*:" id="txtNewPassword" required>
			<input type="password" name="password_2" min=".{6,}" title="Password must have at least 6 characters" placeholder="Confirm Password*:" id="txtConfirmPassword" onkeyup="checkPasswordMatch();" required>
			<span class="registrationFormAlert" id="spanCheckPasswordMatch"></span>
			<input type="submit" class="btn" name="reg_user" value="REGISTER">


			<p class="sign-in">
				Already a member?<a href="user_login.php" class="signin">Sign in</a>
			</p>
	</form>
	<br>
	<br>
	<center><p>Return to <a href="index.php">Home</a></p></center>
</body>
</html>
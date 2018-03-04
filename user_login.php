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
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="css/user_style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

	<header>SHERWIN'S CATERING</header>
	
	<form method="post" action="user_login.php" class="form">

		<?php include('include/user_errors.php'); ?>

		<input type="text" name="username" placeholder="USERNAME" >
			<input type="password" style="border-bottom: 1px solid white;" name="password" placeholder="PASSWORD">
			<input type="submit" class="btn" name="login_user" value="LOGIN">
			<p class="sign-up">
				Not yet a member? <a href="user_register.php" class="signup">Sign up</a>
			</p>
	</form>
	<br>
	<br>
	<center><p>Return to <a href="index.php">Home</a></p></center>
</body>
</html>
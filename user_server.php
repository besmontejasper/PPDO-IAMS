<?php 

	// variable declaration
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    $id   = "";
	$username = "";
	$email1 = "";
	$email2 = "";
	$password = "";
	$type = "";
	$errors = array(); 
	$_SESSION['success'] = "";
	$i="";
	$j="";

	// connect to database
	$db = mysqli_connect('localhost', 'root', '', 'ppdo_iams');

	// REGISTER USER
	if (isset($_POST['reg_user'])) {
		// receive all input values from the form

		$i=0;
		$results = mysqli_query($db, "SELECT * FROM users");
		while ($row=mysqli_fetch_array($results)){
			$unique_email[$i] = $row['email'];
			$unique_username[$i] = $row['username'];
			$i++;
		}
		
		$lastname = mysqli_real_escape_string($db, $_POST['lastname']);
		$firstname = mysqli_real_escape_string($db, $_POST['firstname']);
		$middlename = mysqli_real_escape_string($db, $_POST['middlename']);
		$sex = $_POST['sex'];
		$contact = mysqli_real_escape_string($db, $_POST['contact']);
		$lot_number = mysqli_real_escape_string($db, $_POST['lot_number']);
		$block = mysqli_real_escape_string($db, $_POST['block']);
		$street_number = mysqli_real_escape_string($db, $_POST['street_number']);
		$street_name = mysqli_real_escape_string($db, $_POST['street_name']);
		$subdivision = mysqli_real_escape_string($db, $_POST['subdivision']);
		$barangay = mysqli_real_escape_string($db, $_POST['barangay']);
		$city = mysqli_real_escape_string($db, $_POST['city']);


		$username = mysqli_real_escape_string($db, $_POST['username']);
		$email1 = mysqli_real_escape_string($db, $_POST['email1']);
		$email2 = mysqli_real_escape_string($db, $_POST['email2']);
		$password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
		$password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

		//Enter characters you want to check for
		$characters = "/(!?=.*[A-Za-z])(?=.*[0-9])(?=.*[^a-zA-Z0-9])/";
		//Split into array to check each character
		$chars = str_split($characters);

		//Set wrong password by default and check for correct characters
		$correctPassword = false;

		//Check each character
		foreach($chars as $char){
		    //If characters have been found, set password as correct
		    if (strpos($password_1, $char))
		    {
		        $correctPassword = true;
		    }
		}

		//Also check for length, equal or greater then 6, smaller or equal to 20
		if (!(($correctPassword) && ( strlen($password_1) >= 6 )))
		{
		    array_push($errors, "Minimum password length is 6 characters"); 
		}
		



		$j=0;
		while ($j!=$i){
			if ($username==$unique_username[$j]) { 
				array_push($errors, "Username is already in use!"); 
			}
			if ($email1==$unique_email[$j]) { 
				array_push($errors, "Email is already in use!"); 
			}
			$j++;
		}

		if (!filter_var($email1, FILTER_VALIDATE_EMAIL)) {
		   	array_push($errors, "Please enter a valid email!");
		}
		if (!filter_var($email2, FILTER_VALIDATE_EMAIL)) {
		   	array_push($errors, "Please enter a valid email!");
		}
		if (empty($password_1)) { 
			array_push($errors, "Password is required!"); 
		}

		if ($email1 != $email2) {
			array_push($errors, "The email you provided do not match!");
		}

		if ($password_1 != $password_2) {
			array_push($errors, "The two passwords do not match!");
		}

		// register user if there are no errors in the form
		if (count($errors) == 0) {
			$hash = md5( rand(0,1000) );
			$password = md5($password_1);//encrypt the password before saving in the database
			$query = "INSERT INTO users (lastname, firstname, middlename, sex, contact, lot_number, block, street_number, street_name, subdivision, barangay, city, username, email, password, hash) 
					  VALUES('$lastname', '$firstname', '$middlename', '$sex', '$contact', '$lot_number', '$block', '$street_number', '$street_name', '$subdivision', '$barangay', '$city', '$username', '$email1', '$password', '$hash')";
			mysqli_query($db, $query);

			include 'mail.php';
		}



	}

	// LOGIN USER
	if (isset($_POST['login_user'])) {
		$username = mysqli_real_escape_string($db, $_POST['username']);
		$password = mysqli_real_escape_string($db, $_POST['password']);

		if (empty($username)) {
			array_push($errors, "Username is required");
		}
		if (empty($password)) {
			array_push($errors, "Password is required");
		}


		$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
		$results = mysqli_query($db, $query);

		if (count($errors) == 0) {
			
			
			$password = md5($password);
			$query = ("SELECT * FROM users WHERE username='$username' AND password='$password'");
			$results = mysqli_query($db, $query);
			$data = mysqli_fetch_array($results);
			include 'multisession_check.php';
			session_start();
			$id = $data["id"];  
			$user = $data["username"];
			$pass = $data["password"];
			$type = $data["type"];
			$firstname = $data["firstname"];
			$middlename = $data["middlename"];
			$lastname = $data["lastname"];
			$contact = $data["contact"];
			$email = $data["email"];
			$verified = $data["verified"];
			$time=time();

			$session_id = md5($time);
			mysqli_query($db, "UPDATE users SET session_id='$session_id' WHERE username='$username' AND password='$password'");
			
			$results2 = mysqli_query($db, "SELECT * FROM users WHERE session_id='$session_id' ");
			$data2 = mysqli_fetch_array($results2);
			$session_id = $data2["session_id"];

			
		}

		if (mysqli_num_rows($results)==1 AND $type=="Administrator") {
			if (!isset($_SESSION)) {
				session_start();
			}
			$_SESSION['username'] = $username;
			$_SESSION['id'] = $id;
			$_SESSION['type'] = $type;
			$_SESSION['verified'] = "1";
			$_SESSION['session_id'] = $session_id;
			$_SESSION['success'] = "You are now logged in";
			header('location: dashboard_admin.php');
		}
		elseif (mysqli_num_rows($results) == 1 AND $type=="Member") {
			if ($verified==0){
				array_push($errors, "In order for you to use the system, please verify your email first.");
			}
			else {	
				if (!isset($_SESSION)) {
					session_start();
				}
				$_SESSION['session_id'] = $session_id;
				$_SESSION['username'] = $username;
				$_SESSION['id'] = $id;
				$_SESSION['type'] = $type;
				$_SESSION['firstname'] = $firstname;
				$_SESSION['lastname'] = $lastname;
				$_SESSION['middlename'] = $middlename;
				$_SESSION['contact'] = $contact;
				$_SESSION['email'] = $email;
				$_SESSION['verified'] = $verified;
				$_SESSION['success'] = "You are now logged in";
				header('location: dashboard.php');
			}
		}
		else {

			if (count($errors) == 0) {
				array_push($errors, "Wrong username/password combination");
			}

		}	
	}


	// Edit user profile

	if (isset($_POST['edit_save'])) {
		// receive all input values from the form
		
		$id = $_POST['id'];
		$error=0;
		$lastname = mysqli_real_escape_string($db, $_POST['ch_lastname']);
		$firstname = mysqli_real_escape_string($db, $_POST['ch_firstname']);
		$middlename = mysqli_real_escape_string($db, $_POST['ch_middlename']);
		$contact = mysqli_real_escape_string($db, $_POST['ch_contact']);
		$email = mysqli_real_escape_string($db, $_POST['ch_email']);	

		$results = mysqli_query($db, "SELECT * FROM users"); 
	  	while ($row = mysqli_fetch_array($results)) { 
	  		$reg_fn=$row['firstname'];
	  		$reg_mn=$row['middlename'];
	  		$reg_ln=$row['lastname'];
	  		if ($firstname==$reg_fn && $middlename==$reg_mn && $lastname=$reg_ln){
	  			$error=1;
	  		}
		}
		if ($error!=1){
			$query = "UPDATE users SET lastname='$lastname', firstname='$firstname', middlename='$middlename', contact='$contact' WHERE id=$id";
			mysqli_query($db, $query);
			header('location: user_edit.php');	
		}
		else {
			header('location: user_edit.php?duplicate');
		}
		
		
	}

	// Edit Password

	if (isset($_POST['edit_pass'])) {
		// receive all input values from the form
		
		$id = $_POST['id'];
		$result = mysqli_query($db, "SELECT * FROM users WHERE id=$id");
		while ($row = mysqli_fetch_array($result)){
			$old_pass1 = $row['password'];
		}

		$ch_pass0 = mysqli_real_escape_string($db, $_POST['ch_pass0']);
		$ch_pass1 = mysqli_real_escape_string($db, $_POST['ch_pass1']);
		$ch_pass2 = mysqli_real_escape_string($db, $_POST['ch_pass2']);
		
		$old_pass2 = md5($ch_pass0);
		$new_pass1 = md5($ch_pass1);
		$new_pass2 = md5($ch_pass2);
		

		if (empty($ch_pass0) || empty($ch_pass1) || empty($ch_pass2)){
			header('location: user_edit.php?error_empty');
		}
		elseif ($old_pass1 != $old_pass2) {
			header('location: user_edit.php?error_old');
		}
		elseif ($new_pass1 != $new_pass2) {
			header('location: user_edit.php?error_new');
		}
		else{
			$query = "UPDATE users SET password='$new_pass1' WHERE id=$id";
			mysqli_query($db, $query);
		header('location: user_edit.php');	
		}
		
	}

	if (isset($_GET['logout'])) {
		if ($_GET['logout']==1){
			session_unset();
			session_destroy();
			header('location: index.php');
		}
		
	}

?>
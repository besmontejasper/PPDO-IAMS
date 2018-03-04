<?php 
	if (!isset($_SESSION['username'])) {
		$_SESSION['msg'] = "You must log in first";
		header('location: user_login.php');
	}

	$username=$_SESSION['username'];
  	$current_session_id = $_SESSION['session_id'];
	$type = $_SESSION['type'];
	if ($type != "Administrator"){   
	  	$results = mysqli_query($db, "SELECT session_id FROM users WHERE username='$username' ");
	  	while ($row = mysqli_fetch_array($results)){
	    	$saved_session = $row['session_id'];
	    
	  	}
	  	if ($current_session_id != $saved_session) {
	    	header('location: user_login.php');
	  	}
  	}
	if (isset($_GET['logout'])) {
		session_destroy();
		unset($_SESSION['username']);
		header("location: user_login.php");
	}

?>
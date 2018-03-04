<?php 
	$query = ("SELECT * FROM users WHERE username='$username' AND password='$password'");
	$results = mysqli_query($db, $query);
	$data = mysqli_fetch_array($results);
	$prev_session = $data["session_id"];
	error_reporting(0);
	session_id($prev_session);
	session_start();
	session_destroy();
			

?>
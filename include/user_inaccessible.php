<?php
	include 'user_server.php'; 
	if ($_SESSION['type']=="Member") {
		header('location: dashboard.php');
	}
?>
	


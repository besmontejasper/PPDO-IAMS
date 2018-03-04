<?php 
	$db = mysqli_connect('localhost', 'root', '', 'ppdo_iams');


	if (isset($_POST['send'])) {
		$message_subject = $_POST['message_subject'];
		$message_body = $_POST['message_body'];
		$id = $_POST['id'];
		$dt = new DateTime(); 
		$date_sent = $dt->format('Y-m-d H:i:s');

		$retrieve_info = mysqli_query($db, "SELECT username, firstname, middlename, lastname FROM users WHERE id=$id");
		while ($row=mysqli_fetch_array($retrieve_info)) {
			$user_sender = $row['username'];
			$user_name = $row['firstname']." ".$row['middlename']." ".$row['lastname'];
		}
		$message_sent=1;
		$admin_inbox=1;
		$insert = mysqli_query($db, "INSERT INTO messaging (user_sender, user_name, message_subject, message_body, date_sent, message_sent) VALUES ('$user_sender', '$user_name', '$message_subject', '$message_body', '$date_sent', '$message_sent') ");
		$insert = mysqli_query($db, "INSERT INTO messaging_admin (user_sender, user_name, message_subject, message_body, date_sent, admin_inbox) VALUES ('$user_sender', '$user_name', '$message_subject', '$message_body', '$date_sent', '$admin_inbox') ");
		header('location: messaging.php');
	}

	if (isset($_GET['del'])) {
		$id = $_GET['del'];
		$message_inbox=0;
		$message_trash=1;
		mysqli_query($db, "UPDATE messaging SET message_inbox='$message_inbox', message_trash='$message_trash' WHERE id=$id");
		header('location: messaging.php');
	}	

	if (isset($_GET['restore'])) {
		$id = $_GET['restore'];
		$message_inbox=1;
		$message_trash=0;
		mysqli_query($db, "UPDATE messaging SET message_inbox='$message_inbox', message_trash='$message_trash' WHERE id=$id");
		header('location: messaging_trash.php');
	}	

?>


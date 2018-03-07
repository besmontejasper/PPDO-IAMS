<?php 
	$db = mysqli_connect('localhost', 'root', '', 'ppdo_iams');


	if (isset($_POST['send_admin'])) {
		$message_subject = $_POST['message_subject'];
		$message_recipient = $_POST['message_recipient'];
		$message_body = $_POST['message_body'];

		$dt = new DateTime(); 
		$date_sent = $dt->format('Y-m-d H:i:s');

		$user_sender = "admin";
		$user_name = "admin";
		
		$admin_sent=1;
		$message_inbox=1;
		$unread = 1;
		$insert = mysqli_query($db, "INSERT INTO messaging (user_sender, user_name, message_subject, message_body, date_sent, message_inbox, message_recipient, unread) VALUES ('$user_sender', '$user_name', '$message_subject', '$message_body', '$date_sent', '$message_inbox', '$message_recipient', '$unread')");
		$insert = mysqli_query($db, "INSERT INTO messaging_admin (user_sender, user_name, message_subject, message_body, message_recipient, date_sent, admin_sent) VALUES ('$user_sender', '$user_name', '$message_subject', '$message_body', '$message_recipient', '$date_sent', '$admin_sent') ");
		header('location: messaging_admin.php');
	}

	if (isset($_GET['del'])) {
		$id = $_GET['del'];
		$admin_inbox=0;
		$admin_trash=1;
		mysqli_query($db, "UPDATE messaging_admin SET admin_inbox='$admin_inbox', admin_trash='$admin_trash' WHERE id=$id");
		header('location: messaging_admin.php');
	}	

	if (isset($_GET['restore'])) {
		$id = $_GET['restore'];
		$admin_inbox=1;
		$admin_trash=0;
		mysqli_query($db, "UPDATE messaging_admin SET admin_inbox='$admin_inbox', admin_trash='$admin_trash' WHERE id=$id");
		header('location: messaging_trash_admin.php');
	}	

?>


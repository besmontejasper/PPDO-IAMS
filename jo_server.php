<?php 
	$db = mysqli_connect('localhost', 'root', '', 'ppdo_iams');

	// initialize variables
	$work_area = "";
	$description = "";
	$unit = "";
	$quantity = 0;
	$ppdo_staff_name = "";
	$pr_number = 0;
	$revision = 0;
	$status = "";
	$note_type = "";
	$id = 0;

	if (isset($_POST['send'])) {
		$jo_number = $_POST['jo_number'];
		$work_area = $_POST['work_area'];
		$completion_date = $_POST['completion_date'];
		$date_released = $_POST['date_released'];
		$unit = $_POST['unit'];
		$quantity = $_POST['quantity'];
		$pr_number = $_POST['pr_number'];
		$ppdo_staff_name = $_POST['ppdo_staff_name'];
		$description = $_POST['description'];
		$status = $_POST['status'];
		$revision = $_POST['revision'];
		$note_type = $_POST['print'];

		mysqli_query($db, "INSERT INTO job_order (jo_number, work_area, completion_date, date_released, unit, quantity, pr_number, ppdo_staff_name, description, status, revision, note_type) VALUES ('$jo_number', '$work_area', '$completion_date', '$date_released', '$unit', '$quantity', '$pr_number', '$ppdo_staff_name', '$description', '$status', '$revision', '$note_type')"); 	
		

		echo $id;
		echo $jo_number;
		echo $work_area;
		echo $completion_date;
		echo $date_released;
		echo $unit;
		echo $quantity;
		echo $pr_number;
		echo $ppdo_staff_name;
		echo $description;
		echo $status;
		echo $revision;
		echo $note_type;
		
		header('location: job_order.php');
	}

	if (isset($_POST['edit'])) {
		$id = $_POST['edit_id'];
		$jo_number = $_POST['jo_number'];
		$work_area = $_POST['work_area'];
		$completion_date = $_POST['completion_date'];
		$date_released = $_POST['date_released'];
		$unit = $_POST['unit'];
		$quantity = $_POST['quantity'];
		$pr_number = $_POST['pr_number'];
		$ppdo_staff_name = $_POST['ppdo_staff_name'];
		$description = $_POST['description'];
		$status = $_POST['status'];
		$revision = $_POST['revision'];
		$note_type = $_POST['print'];

		mysqli_query($db, "UPDATE job_order SET jo_number='$jo_number', work_area='$work_area', completion_date='$completion_date', date_released='$date_released', unit='$unit', quantity='$quantity', pr_number='$pr_number', ppdo_staff_name='$ppdo_staff_name', description='$description', status='$status', revision='$revision', note_type='$note_type' WHERE id=$id");

		echo $id;
		echo $jo_number;
		echo $work_area;
		echo $completion_date;
		echo $date_released;
		echo $unit;
		echo $quantity;
		echo $pr_number;
		echo $ppdo_staff_name;
		echo $description;
		echo $status;
		echo $revision;
		echo $note_type;
		
		header('location: job_order.php');
	}
?>
<?php 
	$db = mysqli_connect('localhost', 'root', '', 'ppdo_iams');
  	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	// initialize variables
	$item_name = "";
	$item_quantity = "";
	$requested_quantity = "";
	$request_date = "";
	$request_username = "";
	$item_tag = "";
	$id = 0;
	$error=0;
	$update2=0;
	$pending = "pending";
	$echo = "0";
	$update = false;

	if (isset($_POST['rent'])) {
		$item_name = $_POST['item_name'];
		$requested_quantity = $_POST['requested_quantity'];
		$request_username = $_SESSION['username'];
		$requested_by = $_SESSION['firstname']." ".$_SESSION['lastname'];
		$results_inv = mysqli_query($db, "SELECT * FROM item_inventory WHERE item_name='$item_name' ");
		while ($row = mysqli_fetch_array($results_inv)){
			$id = $row['id'];
			$inventory_id = $id;
			if ($requested_quantity>$row['item_quantity']){
				$error=1;
				header('location: request_repair.php?error');
			}
			else {
				if (strtolower($item_name)==strtolower($row['item_name']) && $requested_quantity<=$row['item_quantity']){
					// $new_quantity = $row['item_quantity'] - $requested_quantity;
					$item_tag = $row['item_tag'];
					$dt = new DateTime();
					$d = date_add($dt,date_interval_create_from_date_string("+0 days"));
					$request_date = $d->format('Y-m-d');

					$results = mysqli_query($db, "SELECT * FROM item_request WHERE requested_by='$requested_by'");
					$request_count = mysqli_num_rows($results);
					$update2 = 0;
					if ($request_count!=0 && $error==0){
						while ($row=mysqli_fetch_array($results)) {
							$request_status = $row['request_status'];
							if (strtolower($item_name) == strtolower($row['item_name']) && $request_status == "pending") {
								$new_requested_quantity = ($requested_quantity + $row['requested_quantity']);
								$id = $row['id'];
								$update2=1;
								$echo = "1";
								mysqli_query($db, "UPDATE item_request SET id='$id', item_name='$item_name', item_tag='$item_tag', request_date='$request_date', requested_by='$requested_by', request_status = '$request_status', inventory_id='$inventory_id' WHERE id=$id ");
							}
						}
						if ($update2!=1){
							$dt = new DateTime();
							$d = date_add($dt,date_interval_create_from_date_string("+0 days"));
							$request_date = $d->format('Y-m-d');
							$request_status = "pending";
							$echo = "2";
							mysqli_query($db, "INSERT INTO item_request (item_name, requested_quantity, item_tag, request_date, requested_by, request_status, inventory_id) VALUES ('$item_name', '$requested_quantity', '$item_tag', '$request_date', '$requested_by', '$request_status', '$inventory_id')");
						}			
					}	
					else {
						if ($error==0 && $request_status != "canceled") {
							$dt = new DateTime();
							$d = date_add($dt,date_interval_create_from_date_string("+0 days"));
							$request_date = $d->format('Y-m-d');
							$request_status = "pending";
							$echo = "3";
							mysqli_query($db, "INSERT INTO item_request (id, item_name, requested_quantity, item_tag, request_date, requested_by, request_status, inventory_id) VALUES ('$id', '$item_name', '$requested_quantity', '$item_tag', '$request_date', '$requested_by', '$request_status', '$inventory_id') ");	
						}
						
					}
					header('location: request_repair.php');					
				}
			}	
		}	
	}

	else if (isset($_POST['approve'])) {
		$id = $_POST['id'];
		$inventory_id = $_POST['inventory_id'];
		$requested_quantity = $_POST['requested_quantity'];
		$request_fulfilled = $_POST['request_fulfilled'];
		$request_status = $_POST['request_status'];
		$request_remark = $_POST['message_body'];
		$item_quantity = $_POST['item_quantity'];
		mysqli_query($db, "UPDATE item_request SET request_fulfilled='$request_fulfilled', request_status = '$request_status', request_remark = '$request_remark' WHERE id=$id");
		mysqli_query($db, "UPDATE item_inventory SET item_quantity='$item_quantity'-'$requested_quantity' WHERE id=$inventory_id");
		header('location: repair_records.php');
	}
	else if (isset($_POST['reject'])) {
		$id = $_POST['id'];
		$request_fulfilled = $_POST['request_fulfilled'];
		$request_status = $_POST['request_status'];
		$request_remark = $_POST['message_body'];
		mysqli_query($db, "UPDATE item_request SET request_fulfilled='$request_fulfilled', request_status = '$request_status', request_remark = '$request_remark' WHERE id=$id");
		header('location: repair_records.php');
	}
	else if (isset($_POST['undo'])) {
		$id = $_POST['id'];
		$inventory_id = $_POST['inventory_id'];
		$requested_quantity = $_POST['requested_quantity'];
		$request_fulfilled = $_POST['request_fulfilled'];
		$request_status = $_POST['request_status'];
		$request_remark = $_POST['message_body'];
		$item_quantity = $_POST['item_quantity'];
		mysqli_query($db, "UPDATE item_request SET request_fulfilled='$request_fulfilled', request_status = '$request_status', request_remark = '$request_remark' WHERE id=$id");
		mysqli_query($db, "UPDATE item_inventory SET item_quantity='$item_quantity'+'$requested_quantity' WHERE id=$inventory_id");
		header('location: repair_records.php');
	}
	else if (isset($_POST['cancel'])) {
		$id = $_POST['id'];
		$inventory_id = $_POST['inventory_id'];
		$requested_quantity = $_POST['requested_quantity'];
		$request_fulfilled = $_POST['request_fulfilled'];
		$request_status = $_POST['request_status'];
		$request_remark = $_POST['message_body'];
		$request_inv = mysqli_query($db, "SELECT * FROM item_inventory WHERE id='$inventory_id' ");
		$row = mysqli_fetch_array($request_inv);
		$item_quantity = $row['item_quantity'];
		mysqli_query($db, "UPDATE item_request SET request_fulfilled='$request_fulfilled', request_status = '$request_status', request_remark = '$request_remark' WHERE id=$id");
		mysqli_query($db, "UPDATE item_inventory SET item_quantity='$item_quantity' + '$requested_quantity' WHERE id=$inventory_id");


		header('location: request_repair.php');
	}

	else if (isset($_GET['del'])) {
		$id = $_GET['del'];
		mysqli_query($db, "DELETE FROM item_request WHERE id=$id");
		header('location: repair_records.php');
	}	
?>
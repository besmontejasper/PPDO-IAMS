<?php 
	$db = mysqli_connect('localhost', 'root', '', 'ppdo_iams');
  	if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
	// initialize variables
	$item_name = "";
	$requested_quantity = "";
	$request_date = "";
	$requested_by = "";
	$item_tag = "";
	$id = 0;
	$error=0;
	$update2=0;
	$pending = "pending";
	
	$update = false;

	if (isset($_POST['rent'])) {
		$item_name = $_POST['item_name'];
		$requested_quantity = $_POST['requested_quantity'];
		$requested_by = $_SESSION['username'];
		$results_inv = mysqli_query($db, "SELECT * FROM item_inventory WHERE item_name='$item_name' ");
		while ($row = mysqli_fetch_array($results_inv)){
			if ($rented_quantity>$row['item_quantity']){
				$error=1;
				header('location: request_repair.php?error');
			}
			else {
				if (strtolower($item_name)==strtolower($row['item_name']) && $requested_quantity<=$row['item_quantity']){
					$new_quantity = $row['item_quantity'] - $requested_quantity;
					$item_cost = $row['rental_cost'];
					$item_tag = $row['item_tag'];
					mysqli_query($db, "UPDATE item_inventory SET item_quantity='$new_quantity'  WHERE id=$id");						
				}
			}	
		}
		$dt = new DateTime();
		$d = date_add($dt,date_interval_create_from_date_string("+0 days"));
		$request_date = $d->format('Y-m-d');
		$total_cost = ($item_cost*$requested_quantity);

		$results = mysqli_query($db, "SELECT * FROM item_request WHERE requested_by='$requested_by'");
		$request_count = mysqli_num_rows($results);
		
		if ($request_count!=0 && $error==0){
			while ($row=mysqli_fetch_array($results)) {
				$request_status = $row['request_status'];
				if (strtolower($item_name) == strtolower($row['item_name']) && $request_status == "pending") {
					$new_requested_quantity = ($requested_quantity + $row['requested_quantity']);
					$id = $row['id'];
					$update2=1;
					$total_cost = ($item_cost*$new_requested_quantity);
					mysqli_query($db, "UPDATE item_request SET id='$id', item_name='$item_name', requested_quantity='$new_requested_quantity', item_tag='$item_tag', total_cost='$total_cost', request_date='$request_date', requested_by='$requested_by', request_status = '$request_status' WHERE id=$id ");
				}
			}
			if ($update2!=1){
				$dt = new DateTime();
				$d = date_add($dt,date_interval_create_from_date_string("+0 days"));
				$request_date = $d->format('Y-m-d');
				$request_status = "pending";
				mysqli_query($db, "INSERT INTO item_request (id, item_name, requested_quantity, item_tag, total_cost, request_date, requested_by, request_status) VALUES ('$id', '$item_name', '$requested_quantity', '$item_tag', '$total_cost', '$request_date', '$requested_by', '$request_status')");
			}			
		}	
		else {
			if ($error==0) {
				$dt = new DateTime();
				$d = date_add($dt,date_interval_create_from_date_string("+0 days"));
				$request_date = $d->format('Y-m-d');
				$request_status = "pending";
				mysqli_query($db, "INSERT INTO item_request (id, item_name, requested_quantity, item_tag, total_cost, request_date, requested_by, request_status) VALUES ('$id', '$item_name', '$requested_quantity', '$item_tag', '$total_cost', '$request_date', '$requested_by', '$request_status') ");	
			}
			
		}
		
		
		header('location: request_repair.php');
	}
?>
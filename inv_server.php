<?php 
	$db = mysqli_connect('localhost', 'root', '', 'ppdo_iams');

	// initialize variables
	$item_name = "";
	$item_quantity = "";
	$item_tag = "";
	$item_cost = "";
	$date_modified = "";
	$critical_stock = "";
	$unit = "";
	$id = 0;
	$update = false;

	if (isset($_POST['save'])) {
		if (!is_null($_POST['item_name']) && ($_POST['item_quantity']!=0) && ($_POST['item_tag']!="None") && ($_POST['item_cost']!=0)){
			$item_name = $_POST['item_name'];
			$item_quantity = $_POST['item_quantity'];
			$unit = $_POST['unit'];
			$item_tag = $_POST['item_tag'];
			$item_cost = sprintf("%.2f", $_POST['item_cost']);
			$critical_stock = $_POST['critical_stock'];
			$dt = new DateTime();
			$d = date_add($dt,date_interval_create_from_date_string("+0 days"));
			$date_modified = $d->format('Y-m-d');
			$results = mysqli_query($db, "SELECT * FROM item_inventory");
			while ($row = mysqli_fetch_array($results)){
				if ((strtolower(trim($item_name," ")) == strtolower(trim($row['item_name'], " "))) && $item_tag == $row['item_tag'] && $item_cost == $row['item_cost']){ 
					$item_quantity = $item_quantity + $row['item_quantity'];
					$id = $row['id'];
					mysqli_query($db, "DELETE FROM item_inventory WHERE id=$id");
				}
			}
			mysqli_query($db, "INSERT INTO item_inventory (id, item_name, item_quantity, unit, item_tag, item_cost, date_modified, critical_stock) VALUES ('$id', '$item_name', '$item_quantity', '$unit', '$item_tag', '$item_cost', '$date_modified', '$critical_stock')"); 	
			
			header('location: inventory.php');
		}
		else {
			header('location: inventory.php?error');
		}
	}
	
	if (isset($_POST['update'])) {
		if (!is_null($_POST['item_name']) && ($_POST['item_quantity']!=0) && ($_POST['item_tag']!="None") && ($_POST['item_cost']!=0)){
			$id = $_POST['id'];
			$item_name = $_POST['item_name'];
			$item_quantity = $_POST['item_quantity'];
			$unit = $_POST['unit'];
			$item_tag = $_POST['item_tag'];
			$item_cost = $_POST['item_cost'];
			$critical_stock = $_POST['critical_stock'];
			$dt = new DateTime();
			$d = date_add($dt,date_interval_create_from_date_string("+0 days"));
			$date_modified = $d->format('Y-m-d');
			mysqli_query($db, "UPDATE item_inventory SET item_name='$item_name', item_quantity='$item_quantity', critical_stock='$critical_stock', unit = '$unit', item_tag='$item_tag', item_cost='$item_cost', date_modified='$date_modified'  WHERE id=$id");

			header('location: inventory.php');
		}
		else {
			header('location: inventory.php?error');
		}
	}

	if (isset($_GET['del'])) {
		$id = $_GET['del'];
		mysqli_query($db, "DELETE FROM item_inventory WHERE id=$id");
		$_SESSION['message'] = "Item deleted!"; 
		header('location: inventory.php');
	}	
?>
<?php 
	$db = mysqli_connect('localhost', 'root', '', 'ppdo_iams');

	// initialize variables
	$building_name = "";
	$building_floor = "";
	$room_number = "";
	$appliance_name = "";
	$qty = "";
	

	if (isset($_POST['save'])) {
		$building_name = $_POST['building_name'];
		$building_floor = $_POST['building_floor'];

		$room_number = $_POST['room_number'];
		

		if (empty($_POST["selectArray"]) && empty($_POST['qtyArray'])){
		}
		else {
			$wattArray = $_POST["wattArray"];
			$selectArray = $_POST["selectArray"];
			$qtyArray = $_POST["qtyArray"];

			// debug
			echo $building_name;
			echo $building_floor;
			echo $room_number;

			for($i=0, $count = count($selectArray);$i<$count;$i++) {
				$appliance_name  = $selectArray[$i];
			 	$qty = $qtyArray[$i];
			 	$watt = $wattArray[$i];

			 	// debug
			 	echo $qty;
			 	echo $appliance_name;
			 	echo $watt;
			 	mysqli_query($db, "SELECT * FROM watt_computation");

			 	mysqli_query($db, "INSERT INTO watt_computation (building_name, building_floor, room_number, qty, appliance_name, watt) VALUES ('$building_name', '$building_floor', '$room_number', '$qty', '$appliance_name', '$watt') ");
			}
		}
		if ($building_name == "SNGAH") {
			header('location: wattage_compute.php?building_name=SNGAH');
		}
		else if ($building_name == "OB") {
			header('location: wattage_compute.php?building_name=OB');
		}
		else if ($building_name == "RNDB") {
			header('location: wattage_compute.php?building_name=RNDB');
		}
		else if ($building_name == "MAB") {
			header('location: wattage_compute.php?building_name=MAB');
		}
		else if ($building_name == "ITB") {
			header('location: wattage_compute.php?building_name=ITB');
		}
		else if ($building_name == "ITC") {
			header('location: wattage_compute.php?building_name=ITC');
		}
	}

	if (isset($_POST['edit'])) {
		$id = $_POST['id'];
		$building_name = $_POST['building_name'];
		$building_floor = $_POST['building_floor'];
		$room_number = $_POST['room_number'];
		$qty = $_POST['qty'];
		$appliance_name = $_POST['appliance_name'];
		$watt = $_POST['watt'];
		mysqli_query($db, "UPDATE watt_computation SET building_name='$building_name', building_floor='$building_floor', room_number='$room_number', qty = '$qty', appliance_name='$appliance_name', watt='$watt' WHERE id=$id");
		header('location: wattage_compute.php');
	}

	if (isset($_GET['building_name'])) {
		$building_name = $_GET['building_name'];
		header('location: wattage_compute.php?building_name='.$building_name.'');
	}


?>
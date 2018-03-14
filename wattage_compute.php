<?php  
if (!isset($_SESSION)){
	session_start();
}

$db = mysqli_connect('localhost', 'root', '', 'ppdo_iams');
include('inv_server.php'); 
include('include/user_check.php'); 
include 'include/user_inaccessible.php';
$item_name = "";
$item_quantity = "";
$unit = "";
$item_tag = "";
$item_cost = "";
$critical_stock = "";

if (isset($_GET['edit'])) {
	$id = $_GET['edit'];
	$record = mysqli_query($db, "SELECT * FROM watt_computation WHERE id=$id");

	if (count($record) == 1 ) {
		$n = mysqli_fetch_array($record);
		$building_name = $n['building_name'];
		$building_floor = $n['building_floor'];
		$room_number = $n['room_number'];
		$qty = $n['qty'];
		$appliance_name = $n['appliance_name'];
		$watt = $n['watt'];
	}
	?>
	<body onload = "edit_modal()"> </body>
	<?php
}

if (isset($_GET['delete'])) {
	$id = $_GET['delete'];
	$record = mysqli_query($db, "SELECT * FROM watt_computation WHERE id=$id");
	$row = mysqli_fetch_array($record);
	if ($row['building_name'] == "SNGAH") {
		$record = mysqli_query($db, "DELETE FROM watt_computation WHERE id=$id");
		header('location: wattage_compute.php?building_name=SNGAH');
	}
	else if ($row['building_name'] == "OB") {
		$record = mysqli_query($db, "DELETE FROM watt_computation WHERE id=$id");
		header('location: wattage_compute.php?building_name=OB');
	}
	else if ($row['building_name'] == "RNDB") {
		$record = mysqli_query($db, "DELETE FROM watt_computation WHERE id=$id");
		header('location: wattage_compute.php?building_name=RNDB');
	}
	else if ($row['building_name'] == "MAB") {
		$record = mysqli_query($db, "DELETE FROM watt_computation WHERE id=$id");
		header('location: wattage_compute.php?building_name=MAB');
	}
	else if ($row['building_name'] == "ITB") {
		$record = mysqli_query($db, "DELETE FROM watt_computation WHERE id=$id");
		header('location: wattage_compute.php?building_name=ITB');
	}
	else if ($row['building_name'] == "ITC") {
		$record = mysqli_query($db, "DELETE FROM watt_computation WHERE id=$id");
		header('location: wattage_compute.php?building_name=ITC');
	}  
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Wattage Computation</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/wattage_style.css">
	<link rel="stylesheet" type="text/css" href="css/w3schools.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<script src="https://code.jquery.com/jquery-latest.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<script>
		function errorField()
		{
			alert("Please fill in appropriate values in each field!");
		}
	</script>
</head>
<body>
	<!-- Top container -->
	<div class="w3-bar w3-top w3-indigo w3-large" style="z-index:4">
		<button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i>  Menu</button> <!-- responsive menu -->
		<span class="w3-bar-item w3-margin-left"><img src="css/img/inv/dashboard_logo.png"></span>
		<span class="w3-bar-item w3-right" style="font-weight: bold; color: white; font-size: 30px;">WATTAGE CONSUMPTION</span>
	</div>


	<!-- Sidebar/menu -->
	<nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar"><br>
		<div class="w3-container w3-row">
			<div class="w3-col s8 w3-bar">
				<br>
				<br>
				<!-- logged in user information -->
				<?php  if (isset($_SESSION['username'])) : ?>
					<span><p>Welcome <strong>
						<?php 
						if ($_SESSION['type']=="Member"){
							echo $_SESSION['firstname']." ".$_SESSION['lastname'];
						} 
						elseif ($_SESSION['type']=="Administrator"){
							echo "Administrator";
						}
						?></strong></p></span><br>
						<span><?php include 'user_logout.php'; ?></span><br>
					<?php endif ?>

					<!--       <a href="messaging_admin.php" class="w3-bar-item w3-button"><i class="fa fa-envelope"></i></a> -->
				</div>
			</div>
			<hr>
			<div class="w3-bar-block">
				<a href="dashboard_admin.php" class="w3-bar-item w3-button w3-padding w3-hover-none w3-indigo w3-hover-text-indigo" style="font-size: 20px; transition-duration: 0.3s;">Dashboard</a>
				<a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>
				<div class="w3-bar-item w3-button w3-hover-white" onclick="myWattFnc()">
					<h5 class="w3-hover-text-indigo" style="font-size: 20px;"><i class="fa fa-plug"></i>&nbsp;Wattage Consumptions&nbsp;<i class="fa fa-caret-down"></i></h5></div>
				<div id="watAcc" class="w3-hide w3-white w3-card-4">
				<a href="javascript:void(0)" class="w3-bar-item w3-button w3-black w3-button w3-hover-red w3-left-align" onclick="document.getElementById('modal').style.display='block'" style="transition-duration: 0.3s;">Add Consumption<i class="w3-padding fa fa-plus-square"></i></a>
				<a href="wattage_compute.php?building_name=SNGAH" class="w3-bar-item w3-button w3-hover-indigo"><i class="fa fa-building w3-margin-right"></i>SNGAH<i class="fa fa-caret-right w3-margin-left"></i></a>
				<a href="wattage_compute.php?building_name=OB" class="w3-bar-item w3-button w3-hover-indigo"><i class="fa fa-building w3-margin-right"></i>OB<i class="fa fa-caret-right w3-margin-left"></i></a>
				<a href="wattage_compute.php?building_name=RNDB" class="w3-bar-item w3-button w3-hover-indigo"><i class="fa fa-building w3-margin-right"></i>RNDB<i class="fa fa-caret-right w3-margin-left"></i></a>
				<a href="wattage_compute.php?building_name=MAB" class="w3-bar-item w3-button w3-hover-indigo"><i class="fa fa-building w3-margin-right"></i>MAB<i class="fa fa-caret-right w3-margin-left"></i></a>
				<a href="wattage_compute.php?building_name=ITB" class="w3-bar-item w3-button w3-hover-indigo"><i class="fa fa-building w3-margin-right"></i>ITB<i class="fa fa-caret-right w3-margin-left"></i></a>
				<a href="wattage_compute.php?building_name=ITC" class="w3-bar-item w3-button w3-hover-indigo"><i class="fa fa-building w3-margin-right"></i>ITC<i class="fa fa-caret-right w3-margin-left"></i></a>


				</div>
				<div class="w3-bar-item w3-button w3-hover-indigo" onclick="myAccFunc()"><i class="fa fa-envelope"></i>&nbsp;Messaging <i class="fa fa-caret-down"></i></div>
				<div id="demoAcc" class="w3-hide w3-white w3-card-4">
					<a href="messaging_admin.php" class="w3-bar-item w3-button w3-hover-deep-orange"><i class="fa fa-inbox w3-margin-right"></i>Inbox<i class="fa fa-caret-right w3-margin-left"></i></a>
					<a href="messaging_sent_admin.php" class="w3-bar-item w3-button w3-hover-green"><i class="fa fa-paper-plane w3-margin-right"></i>Sent</a>
					<a href="messaging_trash_admin.php" class="w3-bar-item w3-button w3-hover-red"><i class="fa fa-trash w3-margin-right"></i>Trash</a>
				</div>
				<a href="inventory.php" class="w3-bar-item w3-button w3-padding w3-hover-indigo"><i class="fa fa-list w3-large"></i>&nbsp;Inventory</a>
				<a href="repair_records.php" class="w3-bar-item w3-button w3-padding w3-hover-indigo"><i class="fa fa-wrench fa-fw w3-large"></i> Repair Records</a>
				<a href="job_order.php" class="w3-bar-item w3-button w3-padding w3-hover-indigo"><i class="fa fa-cubes w3-large"></i>&nbsp;Job Orders</a>
			</div>
		</div>
	</nav>

<div class="w3-main" style="margin-left:300px;margin-top:43px;">
<?php 
	if (isset($_GET['error'])) {
		echo '<script> errorField() </script>'; 
	} 
 ?>

 <div class="w3-container">
  <br>
	<?php 
		

 	if (isset($_GET['building_name'])) {
 		if ($_GET['building_name'] == "SNGAH") {
			$building_name = "SNGAH";
 		}
		else if ($_GET['building_name'] == "OB") {
			$building_name = "OB";
		}
		else if ($_GET['building_name'] == "RNDB") {
			$building_name = "RNDB";
		}
		else if ($_GET['building_name'] == "MAB") {
			$building_name = "MAB";
		}
		else if ($_GET['building_name'] == "ITB") {
			$building_name = "ITB";
		}
		else if ($_GET['building_name'] == "ITC") {
			$building_name = "ITC";
		}
 			$Ground_Floor = "G-Floor";
 			$First_Floor = "1st Floor";
 			$Second_Floor = "2nd Floor";
 			$Third_Floor = "3rd Floor";
 			$Fourth_Floor = "4th Floor";
 			$Fifth_Floor = "5th Floor";
 			$row_wattage = 0;
 			$total_wattage_for_building = 0;
 			$results = mysqli_query($db, "SELECT * FROM watt_computation WHERE building_name = '$building_name' "); 
 			$results_gfloor = mysqli_query($db, "SELECT * FROM watt_computation WHERE building_name = '$building_name' AND building_floor='$Ground_Floor' ");
			$results_1floor = mysqli_query($db, "SELECT * FROM watt_computation WHERE building_name = '$building_name' AND building_floor='$First_Floor' ");
			$results_2floor = mysqli_query($db, "SELECT * FROM watt_computation WHERE building_name = '$building_name' AND building_floor='$Second_Floor' ");
			$results_3floor = mysqli_query($db, "SELECT * FROM watt_computation WHERE building_name = '$building_name' AND building_floor='$Third_Floor' ");
			$results_4floor = mysqli_query($db, "SELECT * FROM watt_computation WHERE building_name = '$building_name' AND building_floor='$Fourth_Floor' ");
			$results_5floor = mysqli_query($db, "SELECT * FROM watt_computation WHERE building_name = '$building_name' AND building_floor='$Fifth_Floor' ");
			while ($row = mysqli_fetch_array($results)){
				$row_wattage = $row['qty'] * $row['watt'];
				$total_wattage_for_building = $total_wattage_for_building + $row_wattage;
			}
			($count = mysqli_num_rows($results));
			($count_g = mysqli_num_rows($results_gfloor));
			($count_1 = mysqli_num_rows($results_1floor));
			($count_2 = mysqli_num_rows($results_2floor));
			($count_3 = mysqli_num_rows($results_3floor));
			($count_4 = mysqli_num_rows($results_4floor));
			($count_5 = mysqli_num_rows($results_5floor));
?>
		  <h1><?php echo $building_name; ?></h1>
		<h3>Total Wattage for Building: <?php echo $total_wattage_for_building; ?></h3>
		  <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white w3-margin-bottom">
			      <thead>
			        <div>
			          <tr>
			            <th>Room Number</th>
			            <th>Quantity</th>
			            <th>Appliance Name</th>
			            <th>Wattage</th>
			            <th>Total Wattage Per Device</th>
			            <th>Actions</th>
			          </tr> 
			        </div>
			      </thead>
			         <h3>Ground Floor</h3>
			      <?php 
			      if ($count_g != 0) {
				      while ($row = mysqli_fetch_array($results_gfloor)){
				          ?>
				          <thead>
				            <div>
				              <tr>
				                <td><?php echo $row['room_number']; ?></td>
				                <td><?php echo $row['qty']; ?></td>
				                <td><?php echo $row['appliance_name'];  ?></td>
				                <td><?php echo $row['watt']; ?></td>
				                <td><?php echo $row['qty']*$row['watt']; ?></td>
				                <td>
				                  <a href="wattage_compute.php?building_name=<?php echo $building_name; ?>&edit=<?php echo $row['id']; ?>" class="edit_btn"><i class="fa fa-pencil-square"></i></a>&nbsp;
				                  <a href="wattage_compute.php?delete=<?php echo $row['id']; ?>" class="del_btn"><i class="fa fa-trash"></i></a>&nbsp;
			                	</td>	
				                </tr>   
				              </div>
				            </thead>
				        <?php
				        }			        	
			      }
			      else {
			      		?>
			      	<thead>
			      		<div>
			      			<tr>
			      				<td colspan="6">
			      					<center><?php echo "No data for Ground Floor"; ?></center>
			      				</td>
			      			</tr>
			      		</div>
			      	</thead>
		      	<?php
			      }
			      ?>

			      </table>

			      <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white w3-margin-bottom">
			      <thead>
			        <div>
			          <tr>
			            <th>Room Number</th>
			            <th>Quantity</th>
			            <th>Appliance Name</th>
			            <th>Wattage</th>
			            <th>Total Wattage Per Device</th>
			            <th>Actions</th>
			          </tr> 
			        </div>
			      </thead>
			      <h3>First Floor</h3>
			      <?php 
			      if ($count_1 != 0) {
			      while ($row = mysqli_fetch_array($results_1floor)){
			          ?>
			          <thead>
			            <div>
			              <tr>
			                <td><?php echo $row['room_number']; ?></td>
			                <td><?php echo $row['qty']; ?></td>
			                <td><?php echo $row['appliance_name'];  ?></td>
			                <td><?php echo $row['watt']; ?></td>
			                <td><?php echo $row['qty']*$row['watt']; ?></td>
			                <td>
			                  <a href="wattage_compute.php?building_name=<?php echo $building_name; ?>&edit=<?php echo $row['id']; ?>" class="edit_btn"><i class="fa fa-pencil-square"></i></a>&nbsp;
			                  <a href="wattage_compute.php?delete=<?php echo $row['id']; ?>" class="del_btn"><i class="fa fa-trash"></i></a>&nbsp;
			                </td>
			                </tr>   
			              </div>
			            </thead>
			        <?php
			        }			        	
			      }
			      else {
			      	?>
			      	<thead>
			      		<div>
			      			<tr>
			      				<td colspan="6">
			      					<center><?php echo "No data for First Floor"; ?></center>
			      				</td>
			      			</tr>
			      		</div>
			      	</thead>
		      	<?php
			      }
			      ?>

			      </table>

				<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white w3-margin-bottom">
			      <thead>
			        <div>
			          <tr>
			            <th>Room Number</th>
			            <th>Quantity</th>
			            <th>Appliance Name</th>
			            <th>Wattage</th>
			            <th>Total Wattage Per Device</th>
			            <th>Actions</th>
			          </tr> 
			        </div>
			      </thead>
			      <h3>Second Floor</h3>
			      <?php 
			      if ($count_2 != 0) {
			      while ($row = mysqli_fetch_array($results_2floor)){
			          ?>
			          <thead>
			            <div>
			              <tr>
			                <td><?php echo $row['room_number']; ?></td>
			                <td><?php echo $row['qty']; ?></td>
			                <td><?php echo $row['appliance_name'];  ?></td>
			                <td><?php echo $row['watt']; ?></td>
			                <td><?php echo $row['qty']*$row['watt']; ?></td>
			                <td>
			                  <a href="wattage_compute.php?building_name=<?php echo $building_name; ?>&edit=<?php echo $row['id']; ?>" class="edit_btn"><i class="fa fa-pencil-square"></i></a>&nbsp;
			                  <a href="wattage_compute.php?delete=<?php echo $row['id']; ?>" class="del_btn"><i class="fa fa-trash"></i></a>&nbsp;
			                </td>
			                </tr>   
			              </div>
			            </thead>
			        <?php
			        }			        	
			      }
			      else {
			      	?>
			      	<thead>
			      		<div>
			      			<tr>
			      				<td colspan="6">
			      					<center><?php echo "No data for Second Floor"; ?></center>
			      				</td>
			      			</tr>
			      		</div>
			      	</thead>
		      	<?php
			      }
			      ?>
			      </table>
			      <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white w3-margin-bottom">
			      <thead>
			        <div>
			          <tr>
			            <th>Room Number</th>
			            <th>Quantity</th>
			            <th>Appliance Name</th>
			            <th>Wattage</th>
			            <th>Total Wattage Per Device</th>
			            <th>Actions</th>
			          </tr> 
			        </div>
			      </thead>
			      <h3>Third Floor</h3>
			      <?php 
			      if ($count_3 != 0) {
			      while ($row = mysqli_fetch_array($results_3floor)){
			          ?>
			          <thead>
			            <div>
			              <tr>
			                <td><?php echo $row['room_number']; ?></td>
			                <td><?php echo $row['qty']; ?></td>
			                <td><?php echo $row['appliance_name'];  ?></td>
			                <td><?php echo $row['watt']; ?></td>
			                <td><?php echo $row['qty']*$row['watt']; ?></td>
			                <td>
			                  <a href="wattage_compute.php?building_name=<?php echo $building_name; ?>&edit=<?php echo $row['id']; ?>" class="edit_btn"><i class="fa fa-pencil-square"></i></a>&nbsp;
			                  <a href="wattage_compute.php?delete=<?php echo $row['id']; ?>" class="del_btn"><i class="fa fa-trash"></i></a>&nbsp;
			                </td>
			                </tr>   
			              </div>
			            </thead>
			        <?php
			        }			        	
			      }
			      else {
			      	?>
			      	<thead>
			      		<div>
			      			<tr>
			      				<td colspan="6">
			      					<center><?php echo "No data for Third Floor"; ?></center>
			      				</td>
			      			</tr>
			      		</div>
			      	</thead>
		      	<?php
			      }
			      ?>
		      </table>
		      <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white w3-margin-bottom">
			      <thead>
			        <div>
			          <tr>
			            <th>Room Number</th>
			            <th>Quantity</th>
			            <th>Appliance Name</th>
			            <th>Wattage</th>
			            <th>Total Wattage Per Device</th>
			            <th>Actions</th>
			          </tr> 
			        </div>
			      </thead>
			      <h3>Fourth Floor</h3>
			      <?php 
			      if ($count_4 != 0) {
			      while ($row = mysqli_fetch_array($results_4floor)){
			          ?>
			          <thead>
			            <div>
			              <tr>
			                <td><?php echo $row['room_number']; ?></td>
			                <td><?php echo $row['qty']; ?></td>
			                <td><?php echo $row['appliance_name'];  ?></td>
			                <td><?php echo $row['watt']; ?></td>
			                <td><?php echo $row['qty']*$row['watt']; ?></td>
			                <td>
			                  <a href="wattage_compute.php?building_name=<?php echo $building_name; ?>&edit=<?php echo $row['id']; ?>" class="edit_btn"><i class="fa fa-pencil-square"></i></a>&nbsp;
			                  <a href="wattage_compute.php?delete=<?php echo $row['id']; ?>" class="del_btn"><i class="fa fa-trash"></i></a>&nbsp;
			                </td>
			                </tr>   
			              </div>
			            </thead>
			        <?php
			        }			        	
			      }
			      else {
			      	?>
			      	<thead>
			      		<div>
			      			<tr>
			      				<td colspan="6">
			      					<center><?php echo "No data for Fourth Floor"; ?></center>
			      				</td>
			      			</tr>
			      		</div>
			      	</thead>
		      	<?php
			      }
			      ?>
		      </table>
		      <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white w3-margin-bottom">
			      <thead>
			        <div>
			          <tr>
			            <th>Room Number</th>
			            <th>Quantity</th>
			            <th>Appliance Name</th>
			            <th>Wattage</th>
			            <th>Total Wattage Per Device</th>
			            <th>Actions</th>
			          </tr> 
			        </div>
			      </thead>
			      <h3>Fifth Floor</h3>
			      <?php 
			      if ($count_5 != 0) {
			      while ($row = mysqli_fetch_array($results_5floor)){
			          ?>
			          <thead>
			            <div>
			              <tr>
			                <td><?php echo $row['room_number']; ?></td>
			                <td><?php echo $row['qty']; ?></td>
			                <td><?php echo $row['appliance_name'];  ?></td>
			                <td><?php echo $row['watt']; ?></td>
			                <td><?php echo $row['qty']*$row['watt']; ?></td>
			                <td>
			                  <a href="wattage_compute.php?building_name=<?php echo $building_name; ?>&edit=<?php echo $row['id']; ?>" class="edit_btn"><i class="fa fa-pencil-square"></i></a>&nbsp;
			                  <a href="wattage_compute.php?delete=<?php echo $row['id']; ?>" class="del_btn"><i class="fa fa-trash"></i></a>&nbsp;
			                </td>
			                </tr>   
			              </div>
			            </thead>
			        <?php
			        }			        	
			      }
			      else {
			      	?>
			      	<thead>
			      		<div>
			      			<tr>
			      				<td colspan="6">
			      					<center><?php echo "No data for Fifth Floor"; ?></center>
			      				</td>
			      			</tr>
			      		</div>
			      	</thead>
		      	<?php
			      }
			      ?>
		      </table>
  			<?php
 	}
    ?>	
	</div>
</div>

	<!-- Consumption Form -->
	<div id="modal" class="w3-modal" style="z-index:4;">
		<div class="w3-modal-content w3-animate-zoom" style="width: 650px;">
			<div class="w3-container w3-padding w3-indigo">
				<header class="w3-padding"> 
					<h2 class="w3-center" style="color: white;">Wattage Consumption Form</h2>
				</header>
				<form id="regForm" method="post" action="watt_server.php">
					<input type="hidden" name="id" value="<?php echo $id; ?>">
					<!-- One "tab" for each step in the form: -->
					<div class="tab">Building Info:
						<p><select name="building_name" placeholder="Building Name">
							<?php 
								if (isset($_GET['edit'])) {
							?>
								<option <?php if ($building_name == "None" ) echo 'selected' ; ?> value="None">-Select building-</option>
								<option <?php if ($building_name == "SNGAH" ) echo 'selected' ; ?> value="SNGAH" selected>SNGAH</option>
								<option <?php if ($building_name == "OB" ) echo 'selected' ; ?> value="OB">OB</option>
								<option <?php if ($building_name == "RNDB" ) echo 'selected' ; ?> value="RNDB">RNDB</option>
								<option <?php if ($building_name == "MAB" ) echo 'selected' ; ?> value="MAB">MAB</option>
								<option <?php if ($building_name == "ITB" ) echo 'selected' ; ?> value="ITB">ITB</option>
								<option <?php if ($building_name == "ITC" ) echo 'selected' ; ?> value="ITC">ITC</option>
							<?php
									
								}
								else {
									?>
									<option value="None" selected>-Select building-</option>
									<option value="SNGAH" >SNGAH</option>
									<option value="OB">OB</option>
									<option value="RNDB">RNDB</option>
									<option value="MAB">MAB</option>
									<option value="ITB">ITB</option>
									<option value="ITC">ITC</option>
									<?php
								}
								?>
							</select></p>
							<p><select name="building_floor" placeholder="Building Floor">
								<?php 
								if (isset($_GET['edit'])) {
								?>
									<option <?php if ($building_floor == "None" ) echo 'selected' ; ?> value="None">-Select floor-</option>
									<option <?php if ($building_floor == "G-Floor" ) echo 'selected' ; ?> value="G-Floor" selected>G-Floor</option>
									<option <?php if ($building_floor == "1st Floor" ) echo 'selected' ; ?> value="1st Floor">1st Floor</option>
									<option <?php if ($building_floor == "2nd Floor" ) echo 'selected' ; ?> value="2nd Floor">2nd Floor</option>
									<option <?php if ($building_floor == "3rd Floor" ) echo 'selected' ; ?> value="3rd Floor">3rd Floor</option>
									<option <?php if ($building_floor == "4th Floor" ) echo 'selected' ; ?> value="4th Floor">4th Floor</option>
									<option <?php if ($building_floor == "5th Floor" ) echo 'selected' ; ?> value="5th Floor">5th Floor</option>
								<?php
								}
								else {
									?>
									<option value="None" selected>-Select floor-</option>
									<option value="G-Floor">G-Floor</option>
									<option value="1st Floor">1st Floor</option>
									<option value="2nd Floor">2nd Floor</option>
									<option value="3rd Floor">3rd Floor</option>
									<option value="4th Floor">4th Floor</option>
									<option value="5th Floor">5thFloor</option>
								<?php
								}
								?>
								</select></p>
								<p><input name="room_number" type="number" placeholder="Room Number" value="<?php echo $room_number; ?>" required></p>
						
							<div class="w3-section">
								<table>
									<thead>
										<tr>
										<td style="color: white;">Quantity</td>
										<td style="color: white;">Item</td>
										<td style="color: white;">Wattage</td>
									</tr>
								</thead>
								<tbody id="TextBoxContainer">
								</tbody>
								<tfoot>
									<tr>
										<?php 
											if (isset($_GET['edit'])) {
												?>
												<td><input type="number" name="qty" value="<?php echo $qty; ?>"></td>
												<td><select style="width:300px;" name="appliance_name" class="w3-input">
													<option <?php if ($appliance_name == "None" ) echo 'selected' ; ?> value="none">Choose Appliance</option>
													<option <?php if ($appliance_name == "T5" ) echo 'selected' ; ?> value="T5">T5  (Lightning Loads)</option>
													<option <?php if ($appliance_name == "T8" ) echo 'selected' ; ?> value="T8">T8  (Lightning Loads)</option>
													<option <?php if ($appliance_name == "T8/C" ) echo 'selected' ; ?> value="T8/C">T8/C  (Lightning Loads)</option>
													<option <?php if ($appliance_name == "LED" ) echo 'selected' ; ?> value="LED">LED (Lightning Loads)</option>
													<option <?php if ($appliance_name == "CFL" ) echo 'selected' ; ?> value="CFL">CFL (Lightning Loads)</option>
													<option <?php if ($appliance_name == "CFL/EL" ) echo 'selected' ; ?> value="CFL/EL">CFL/EL (Lightning Loads)</option>
													<option <?php if ($appliance_name == "LED Bulb" ) echo 'selected' ; ?> value="LED Bulb">LED Bulb (Lightning Loads)</option>
													<option <?php if ($appliance_name == "2.5 Window Type" ) echo 'selected' ; ?> value="2.5 Window Type">2.5 Window Type, rating (HP) (ACU)</option>
													<option <?php if ($appliance_name == "2 Window Type" ) echo 'selected' ; ?> value="2 Window Type">2 Window Type, rating (HP) (ACU)</option>
													<option <?php if ($appliance_name == "1.5 Window Type" ) echo 'selected' ; ?> value="1.5 Window Type">1.5 Window Type, rating (HP) (ACU)</option>
													<option <?php if ($appliance_name == "1 Split Type" ) echo 'selected' ; ?> value="1 Split Type">1 Split Type, Rating (Ton) (ACU)</option>
													<option <?php if ($appliance_name == "2 Split Type" ) echo 'selected' ; ?> value="2 Split Type">2 Split Type, Rating (Ton) (ACU)</option>
													<option <?php if ($appliance_name == "4 Split Type" ) echo 'selected' ; ?> value="4 Split Type">4 Split Type, Rating (Ton) (ACU)</option>
													<option <?php if ($appliance_name == "Brand ACU" ) echo 'selected' ; ?> value="Brand ACU">Other Brand (ACU)</option>
													<option <?php if ($appliance_name == "Orbit Fan" ) echo 'selected' ; ?> value="Orbit Fan">Orbit Fan (Ventilation)</option>
													<option <?php if ($appliance_name == "Wall Fan" ) echo 'selected' ; ?> value="Wall Fan">Wall Fan (Ventilation)</option>
													<option <?php if ($appliance_name == "Stand Fan" ) echo 'selected' ; ?> value="Stand Fan">Stand Fan (Ventilation)</option>
													<option <?php if ($appliance_name == "TV SET,LED" ) echo 'selected' ; ?> value="TV SET,LED">TV SET,LED (Multimedia Equipment)</option>
													<option <?php if ($appliance_name == "Desktop" ) echo 'selected' ; ?> value="Desktop">Desktop (Multimedia Equipment)</option>
													<option <?php if ($appliance_name == "Laptop" ) echo 'selected' ; ?> value="Laptop">Laptop (Multimedia Equipment)</option>
													<option <?php if ($appliance_name == "Projector" ) echo 'selected' ; ?> value="Projector">Projector (Multimedia Equipment)</option>
													<option <?php if ($appliance_name == "Brand ME" ) echo 'selected' ; ?> value="Brand ME">Other Brand (Multimedia Equipment)</option>
												</select></td>
												<td><input type="number" name="watt" value="<?php echo $watt; ?>"></td>
												<?php
											}
											else {
												?>
												<th colspan="4">
													<button id="btnAdd" type="button" class="w3-button w3-block w3-green"><i class="fa fa-plus-square"></i>&nbsp; Add&nbsp;</button>
												</th>
												<?php
											}
										?>
										
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<br>
						<div style="overflow:auto;">
							<div style="float:right;">
								<?php 
								if (isset($_GET['edit'])) {
									?>
									<th colspan="4">
										<button class="w3-button w3-hover-blue w3-padding" style="transition-duration: 0.3s;" name="edit" type="submit" id="editBtn"><i class="fa fa-pencil"></i>&nbsp;Edit</button>
									</th>
									<?php
								}
								else {
									?>
									<th colspan="4">
										<button class="w3-button w3-hover-green w3-padding" style="transition-duration: 0.3s;" name="save" type="submit" id="submitBtn"><i class="fa fa-save"></i>&nbsp;Save</button>
									</th>
									<?php
								}
							?>
							</div>
						</div>
							</form>
								<form action = "watt_server.php">
									<input type="hidden" name="building_name" value="<?php echo $building_name; ?>">	
									<button onclick="document.getElementById('modal').style.display='none'" type="submit" class="w3-button w3-block w3-red">Cancel <i class="fa fa-remove"></i></button>
								</form>
						</div>
					</div>
				</div>

				<script>
// Get the Sidebar
var mySidebar = document.getElementById("mySidebar");

// Get the DIV with overlay effect
var overlayBg = document.getElementById("myOverlay");

// Toggle between showing and hiding the sidebar, and add overlay effect
function w3_open() {
	if (mySidebar.style.display === 'block') {
		mySidebar.style.display = 'none';
		overlayBg.style.display = "none";
	} else {
		mySidebar.style.display = 'block';
		overlayBg.style.display = "block";
	}
}

// Close the sidebar with the close button
function w3_close() {
	mySidebar.style.display = "none";
	overlayBg.style.display = "none";
}
</script>

<!-- Script for accordion Tabs-->
<script>
	function myAccFunc() {
		var x = document.getElementById("demoAcc");
		if (x.className.indexOf("w3-show") == -1) {
			x.className += " w3-show";
			x.previousElementSibling.className += " w3-indigo";
		} else { 
			x.className = x.className.replace(" w3-show", "");
			x.previousElementSibling.className = 
			x.previousElementSibling.className.replace(" w3-indigo", "");
		}
	}

	function myDropFunc() {
		var x = document.getElementById("demoDrop");
		if (x.className.indexOf("w3-show") == -1) {
			x.className += " w3-show";
			x.previousElementSibling.className += " w3-indigo";
		} else { 
			x.className = x.className.replace(" w3-show", "");
			x.previousElementSibling.className = 
			x.previousElementSibling.className.replace(" w3-indigo", "");
		}
	}


	function edit_modal() {
		document.getElementById('modal').style.display='block';
	}
</script>

<!-- for wattage consumption viewing -->
<script>
	function myWattFnc() {
		var x = document.getElementById("watAcc");
		if (x.className.indexOf("w3-show") == -1) {
			x.className += " w3-show";
			x.previousElementSibling.className += " w3-text-indigo";
		} else { 
			x.className = x.className.replace(" w3-show", "");
			x.previousElementSibling.className = 
			x.previousElementSibling.className.replace(" w3-text-indigo", "");
		}
	}

	function myDropFunc() {
		var x = document.getElementById("demoDrop");
		if (x.className.indexOf("w3-show") == -1) {
			x.className += " w3-show";
			x.previousElementSibling.className += " w3-text-black";
		} else { 
			x.className = x.className.replace(" w3-show", "");
			x.previousElementSibling.className = 
			x.previousElementSibling.className.replace(" w3-text-indigo", "");
		}
	}


	function edit_modal() {
		document.getElementById('modal').style.display='block';
	}
</script>

<!-- Script for Wattage U.I -->
<script>
var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the crurrent tab

function showTab(n) {
  // This function will display the specified tab of the form...
  var x = document.getElementsByClassName("tab");
  x[n].style.display = "block";
  //... and fix the Previous/Next buttons:
  if (n == 0) {
  	document.getElementById("prevBtn").style.display = "none";
  } else {
  	document.getElementById("prevBtn").style.display = "inline";
  }
  if (n == (x.length - 1)) {
  	document.getElementById("nextBtn").innerHTML = "Save";
  } else {
  	document.getElementById("nextBtn").innerHTML = "Next";
  }
  fixStepIndicator(n)
}

function nextPrev(n) {
  // This function will figure out which tab to display
  var x = document.getElementsByClassName("tab");
  // Exit the function if any field in the current tab is invalid:
  if (n == 1 && !validateForm()) return false;
  // Hide the current tab:
  x[currentTab].style.display = "none";
  // Increase or decrease the current tab by 1:
  currentTab = currentTab + n;
  // if you have reached the end of the form...
  if (currentTab >= x.length) {
    // ... the form gets submitted:

    document.getElementById("nextBtn").type = "submit";
    document.getElementById("nextBtn").name = "save";
    document.getElementById("regForm").submit();
    return false;
}
  // Otherwise, display the correct tab:
  showTab(currentTab);
}

function validateForm() {
  // This function deals with validation of the form fields
  var x, y, i, valid = true;
  x = document.getElementsByClassName("tab");
  y = x[currentTab].getElementsByTagName("input","select");
  // A loop that checks every input field in the current tab:
  for (i = 0; i < y.length; i++) {
    // If a field is empty...
    if (y[i].value == "") {
      // add an "invalid" class to the field:
      y[i].className += " invalid";
      // and set the current valid status to false
      valid = false;
  }
}
  // If the valid status is true, mark the step as finished and valid:
  if (valid) {
  	document.getElementsByClassName("step")[currentTab].className += " finish";
  }
  return valid; // return the valid status
}

function fixStepIndicator(n) {
  // This function removes the "active" class of all steps...
  var i, x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
  	x[i].className = x[i].className.replace(" active", "");
  }
  //... and adds the "active" class on the current step:
  x[n].className += " active";
}
</script>

<!-- For dynamic dropdown -->
<script>
	function showWattage(){
		var selectBox = document.getElementById('slct1');
		var userInput = selectBox.options[selectBox.selectedIndex].value;
		if (userInput == 'T8') {
			document.getElementById('chooseWatts').style.visibility = 'visible';
		}else{
			document.getElementById('chooseWatts').style.visibility = 'hidden';
		}
		return false;
	}
</script>

<script>
	
	$(function () {
		$("#btnAdd").bind("click", function () {
			var div = $("<tr />");
			div.html(GetDynamicTextBox(""));
			$("#TextBoxContainer").append(div);
		});
		$("body").on("click", ".remove", function () {
			$(this).closest("tr").remove();
		});
	});
	function GetDynamicTextBox(value) {
		return '<td><input type="number" name="qtyArray[]" value ="" min="0" class="w3-input" style="width:90px;" required/></td>' + '<td><select style="width:300px;" name="selectArray[]" class="w3-input"><option value="none" selected>Choose Appliance</option><option value="T5">T5  (Lightning Loads)</option><option value="T8">T8  (Lightning Loads)</option><option value="T8/C">T8/C  (Lightning Loads)</option><option value="LED">LED (Lightning Loads)</option><option value="CFL">CFL (Lightning Loads)</option><option value="CFL/EL">CFL/EL (Lightning Loads)</option><option value="LED Bulb">LED Bulb (Lightning Loads)</option><option value="2.5 Window Type">2.5 Window Type, rating (HP) (ACU)</option><option value="2 Window Type">2 Window Type, rating (HP) (ACU)</option><option value="1.5 Window Type">1.5 Window Type, rating (HP) (ACU)</option><option value="1 Split Type">1 Split Type, Rating (Ton) (ACU)</option><option value="2 Split Type">2 Split Type, Rating (Ton) (ACU)</option><option value="4 Split Type">4 Split Type, Rating (Ton) (ACU)</option><option value="Brand ACU">Other Brand (ACU)</option><option value="Orbit Fan">Orbit Fan (Ventilation)</option><option value="Wall Fan">Wall Fan (Ventilation)</option><option value="Stand Fan">Stand Fan (Ventilation)</option><option value="TV SET,LED">TV SET,LED (Multimedia Equipment)</option><option value="Desktop">Desktop (Multimedia Equipment)</option><option value="Laptop">Laptop (Multimedia Equipment)</option><option value="Projector">Projector (Multimedia Equipment)</option><option value="Brand ME">Other Brand (Multimedia Equipment)</option></select></td>' + '<td><input type="number" name="wattArray[]" min="0" class="w3-input" style="width:90px;" value = "" required/></td>' + '<td><button type="button" class="w3-button w3-red remove"><i class="fa fa-remove"></i></button></td>'
	}
</script>

</body>
</html>

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
	$update = true;
	$record = mysqli_query($db, "SELECT * FROM item_inventory WHERE id=$id");

	if (count($record) == 1 ) {
		$n = mysqli_fetch_array($record);
		$item_name = $n['item_name'];
		$item_quantity = $n['item_quantity'];
		$unit = $n['unit'];
		$item_tag = $n['item_tag'];
		$item_cost = $n['item_cost'];
		$critical_stock = $n['critical_stock'];
	}
	?>
	<body onload = "edit_modal()"> </body>
	<?php
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Inventory</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/fab_admin.css">
	<link rel="stylesheet" type="text/css" href="css/w3schools.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
		<span class="w3-bar-item w3-right" style="font-weight: bold; color: white; font-size: 30px;">INVENTORY</span>
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
				<div class="w3-bar-item w3-button w3-hover-white" onclick="myInvFunc()">
					<h5 class="w3-hover-text-indigo" style="font-size: 20px;"><i class="fa fa-list"></i>&nbsp;Inventory&nbsp;<i class="fa fa-caret-down"></i></h5></div>
					<div id="invAcc" class="w3-hide w3-white w3-card-4">
						<a href="javascript:void(0)" class="w3-bar-item w3-button w3-black w3-button w3-hover-red w3-left-align" onclick="document.getElementById('modal').style.display='block'" style="transition-duration: 0.3s;">Add Single Item<i class="w3-padding fa fa-plus-square"></i></a>
						<a href="javascript:void(0)" class="w3-bar-item w3-button w3-black w3-button w3-hover-red w3-left-align" onclick="document.getElementById('bulk_modal').style.display='block'" style="transition-duration: 0.3s;">Add Bulk Items<i class="w3-padding fa fa-plus-square"></i></a></div>
						<div class="w3-bar-item w3-button w3-hover-indigo" onclick="myAccFunc()"><i class="fa fa-envelope"></i>&nbsp;Messaging <i class="fa fa-caret-down"></i></div>
						<div id="demoAcc" class="w3-hide w3-white w3-card-4">
							<a href="messaging_admin.php" class="w3-bar-item w3-button w3-hover-deep-orange"><i class="fa fa-inbox w3-margin-right"></i>Inbox<i class="fa fa-caret-right w3-margin-left"></i></a>
							<a href="messaging_sent_admin.php" class="w3-bar-item w3-button w3-hover-green"><i class="fa fa-paper-plane w3-margin-right"></i>Sent</a>
							<a href="messaging_trash_admin.php" class="w3-bar-item w3-button w3-hover-red"><i class="fa fa-trash w3-margin-right"></i>Trash</a>
						</div>
						<a href="repair_records.php" class="w3-bar-item w3-button w3-padding w3-hover-indigo"><i class="fa fa-wrench fa-fw w3-large"></i> Repair Records</a>
						<a href="job_order.php" class="w3-bar-item w3-button w3-padding w3-hover-indigo"><i class="fa fa-cubes w3-large"></i>&nbsp;Job Orders</a>
						<a href="wattage_compute.php?building_name=SNGAH" class="w3-bar-item w3-button w3-padding w3-hover-indigo"><i class="fa fa-plug w3-large"></i>&nbsp;Wattage Consumption</a>

					</div>
				</div>
			</nav>

			<?php if (isset($_GET['error'])) { ?> 
			<?php echo '<script> errorField() </script>'; ?>	
			<?php } ?>
			<?php $results = mysqli_query($db, "SELECT * FROM item_inventory"); ?>
			<div id="modal" class="w3-modal" style="z-index:4;">
				<div class="w3-modal-content w3-animate-zoom" style="width: 400px;">
					<div class="w3-container w3-padding w3-indigo">
						<header class="w3-padding"> 
							<h2 class="w3-center" style="color: white;">Inventory Form</h2>
						</header>
						<form method="post" action="inv_server.php" >
							<input type="hidden" name="id" value="<?php echo $id; ?>">
							<div class="w3-section">
								<div class="input-group w3-margin-bottom">
									<label>Item Name</label>
									<input class="w3-input" type="text" name="item_name" value="<?php echo $item_name; ?>" required>
								</div>
								<div class="input-group w3-margin-bottom">
									<label>Stock</label>
									<input class="w3-input" type="number" name="item_quantity" min="0" value="<?php echo $item_quantity; ?>" required>
								</div>
								<div class="input-group w3-margin-bottom">
									<label>Unit</label>
									<input class="w3-input" type="text" name="unit" value="<?php echo $unit; ?>" required>
								</div>
								<div class="input-group w3-margin-bottom">
									<label>Critical Level of Stock</label>
									<input class="w3-input" type="text" name="critical_stock" value="<?php echo $critical_stock; ?>" required>
								</div>
								<div class="input-group w3-margin-bottom">
									<label>Item Tag</label>
									<select name="item_tag" class="w3-input">
										<?php 
										if (isset($_GET['edit'])) {
											if ($item_tag=="None") {
												?>
												<option value="None" selected>None</option>
												<option value="Lighting Loads">Lighting Loads</option>
												<option value="Air Conditioning Units">Air Conditioning Units</option>
												<option value="Ventilation">Ventilation</option>
												<option value="Multimedia Equipment">Multimedia Equipment</option>
												<option value="Electrical/Hardware Equipment">Electrical/Hardware Equipment</option>
												<?php
											}
											if ($item_tag=="Lighting Loads") {
												?>
												<option value="None">None</option>
												<option value="Lighting Loads" selected>Lighting Loads</option>
												<option value="Air Conditioning Units">Air Conditioning Units</option>
												<option value="Ventilation">Ventilation</option>
												<option value="Multimedia Equipment">Multimedia Equipment</option>
												<option value="Electrical/Hardware Equipment">Electrical/Hardware Equipment</option>
												<?php
											}
											if ($item_tag=="Air Conditioning Units") {
												?>
												<option value="None">None</option>
												<option value="Lighting Loads">Lighting Loads</option>
												<option value="Air Conditioning Units" selected>Air Conditioning Units</option>
												<option value="Ventilation">Ventilation</option>
												<option value="Multimedia Equipment">Multimedia Equipment</option>
												<option value="Electrical/Hardware Equipment">Electrical/Hardware Equipment</option>
												<?php
											}
											if ($item_tag=="Ventilation") {
												?>
												<option value="None">None</option>
												<option value="Lighting Loads">Lighting Loads</option>
												<option value="Air Conditioning Units">Air Conditioning Units</option>
												<option value="Ventilation" selected>Ventilation</option>
												<option value="Multimedia Equipment">Multimedia Equipment</option>
												<option value="Electrical/Hardware Equipment">Electrical/Hardware Equipment</option>
												<?php
											}
											if ($item_tag=="Multimedia Equipment") {
												?>
												<option value="None">None</option>
												<option value="Lighting Loads">Lighting Loads</option>
												<option value="Air Conditioning Units">Air Conditioning Units</option>
												<option value="Ventilation">Ventilation</option>
												<option value="Multimedia Equipment" selected>Multimedia Equipment</option>
												<option value="Electrical/Hardware Equipment">Electrical/Hardware Equipment</option>
												<?php
											}
											if ($item_tag=="Electrical/Hardware Equipment") {
												?>
												<option value="None">None</option>
												<option value="Lighting Loads">Lighting Loads</option>
												<option value="Air Conditioning Units">Air Conditioning Units</option>
												<option value="Ventilation">Ventilation</option>
												<option value="Multimedia Equipment" selected>Multimedia Equipment</option>
												<option value="Electrical/Hardware Equipment" selected>Electrical/Hardware Equipment</option>
												<?php
											}
										}
										else {
											?>
											<option value="None" selected>None</option>
											<option value="Lighting Loads">Lighting Loads</option>
											<option value="Air Conditioning Units">Air Conditioning Units</option>
											<option value="Ventilation">Ventilation</option>
											<option value="Multimedia Equipment">Multimedia Equipment</option>
											<option value="Electrical/Hardware Equipment">Electrical/Hardware Equipment</option>
											<?php	
										}
										?>

									</select>
								</div>
								<div class="input-group w3-margin-bottom">
									<label>Price Per Unit</label>
									<input class="w3-input" type="number" name="item_cost" min="0" step="0.01" value="<?php echo $item_cost; ?>" required>
								</div>
								<div class="input-group w3-margin-bottom">
									<?php if ($update == true): ?>
										<button class="btn w3-button w3-block w3-blue" type="submit" name="update">Update <i class="fa fa-pencil"></i></button>
									<?php else: ?>
										<button class="btn w3-button w3-block w3-green" type="submit" name="save">Save <i class="fa fa-save"></i></button>
									<?php endif ?>
								</div>
							</div>
						</form>
						<form action = "inventory.php">			
							<button onclick="document.getElementById('modal').style.display='none'" type="submit" class="w3-button w3-block w3-red">Cancel <i class="fa fa-remove"></i></button>
						</form>
					</div>
				</div>
			</div>
			<br>
			<div class="w3-main" style="margin-left:300px;margin-top:43px;">
				<div class="w3-container">
					<div id="bulk_modal" class="w3-modal" style="z-index:4;">
						<div class="w3-modal-content w3-animate-zoom w3-indigo" style="width: 500px;">
							<form method="post" action="inv_server.php" enctype="multipart/form-data">
								<h2 style="color: white;" class="w3-center">Insert Items in Bulk</h2>
								<div class="w3-center">
									<input id="uploadFile" placeholder="Choose File" disabled="disabled" />
									<div class="fileUpload btn w3-button w3-green w3-hover-teal">
										<span><i class="fa fa-upload"></i>&nbsp;&nbsp;Upload</span>
										<input id="uploadBtn" type="file" class="upload" name="csv_file" />
									</div>
									<div class="fileSubmit btn w3-button w3-red w3-hover-pink">
										<span><i class="fa fa-check"></i>&nbsp;&nbsp;Submit</span>
										<input type="submit" name="file_submit" class=" submit">
									</div>
								</div>
							</form>
							<button onclick="document.getElementById('bulk_modal').style.display='none'" type="submit" class="w3-button w3-block w3-red">Cancel <i class="fa fa-remove"></i></button> 
						</div>
					</div>
					<h2 style="color: crimson;">On-Stock Items</h2>
					<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
						<thead>
							<tr>
								<div class="tags">
									<th>Item Name</th>
									<th>Stock</th>
									<th>Unit</th>
									<th>Critical Level</th>
									<th>Tag</th>
									<th>Price Per Unit</th>
									<th>Total Price</th>
									<th>Date Modified</th>
									<th>Actions</th>
								</div>
							</tr>
						</thead>

						<?php while ($row = mysqli_fetch_array($results)) { ?>
						<tr>
							<td><?php echo $row['item_name']; ?></td>
							<td><?php echo $row['item_quantity']; ?></td>
							<td><?php echo $row['unit']; ?></td>
							<td><?php echo $row['critical_stock']; ?></td>
							<td><?php echo $row['item_tag']; ?></td>
							<td><?php echo $row['item_cost']; ?></td>
							<td><?php echo number_format($row['item_quantity']*$row['item_cost'],2); ?> </td>				
							<td><?php echo $row['date_modified']; ?></td>
							<td><a href="inventory.php?edit=<?php echo $row['id']; ?>" class="edit_btn" ><i class="fa fa-pencil-square"></i></a>
								<a href="inv_server.php?del=<?php echo $row['id']; ?>" class="del_btn"><i class="fa fa-trash"></i></a></td>
							</tr>
							<?php } ?>
						</table>
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

<!-- Script for inventory accordion-->
<script>
	function myInvFunc() {
		var x = document.getElementById("invAcc");
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

<script type="text/javascript">
	document.getElementById("uploadBtn").onchange = function () {
		document.getElementById("uploadFile").value = this.value;
	};
</script>

</body>
</html>

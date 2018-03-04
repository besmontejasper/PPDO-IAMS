<?php  
if (!isset($_SESSION)){
	session_start();
}

$db = mysqli_connect('localhost', 'root', '', 'ppdo_iams');
include('inv_server.php'); 
include('include/user_check.php'); 
include 'include/user_inaccessible.php';

if (isset($_GET['edit'])) {
	$id = $_GET['edit'];
	$update = true;
	$record = mysqli_query($db, "SELECT * FROM item_inventory WHERE id=$id");

	if (count($record) == 1 ) {
		$n = mysqli_fetch_array($record);
		$item_name = $n['item_name'];
		$item_quantity = $n['item_quantity'];
		$unrentable_stock = $n['unrentable_stock'];
		$item_tag = $n['item_tag'];
		$item_cost = $n['item_cost'];
		$rental_cost = $n['rental_cost'];
	}
}
?>
<!-- inserted container START -->
<!DOCTYPE html>
<html>
<head>
	<title>Inventory</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/rsrvp_style.css">
	<link rel="stylesheet" type="text/css" href="css/fab_admin.css">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
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
		<span class="w3-bar-item w3-right" style="font-size: 30px;">INVENTORY</span>
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
				<h5 class="w3-text-indigo w3-padding"><i class="fa fa-list"></i>  Inventory</a></h5>
				<a href="javascript:void(0)" class="w3-bar-item w3-button w3-dark-grey w3-button w3-hover-indigo w3-left-align" onclick="document.getElementById('id01').style.display='block'" style="transition-duration: 0.3s;">Add Item<i class="w3-padding fa fa-plus"></i></a>
    <a href="repair_records.php" class="w3-bar-item w3-button w3-padding w3-hover-yellow"><i class="fa fa-wrench fa-fw"></i>  Repair Records</a>

</div>
</nav>
</div>
<!-- END OF INSERTED CONTAINER -->
<br>
<br>
<?php if (isset($_GET['error'])) { ?> 
<?php echo '<script> errorField() </script>'; ?>	
<?php } ?>
<?php $results = mysqli_query($db, "SELECT * FROM item_inventory"); ?>
<div class="w3-main" style="margin-left:300px;margin-top:43px;">
	<div class="w3-container">
		<h2 style="color: crimson;">On-Stock Items</h2>
		<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
			<thead>
				<div>
					<tr>
						<th>Item Name</th>
						<th>Stocks</th>
						<th>Item Tag</th>
						<th>Price</th>
						<th>Rental Cost</th>
						<th>Date Modified</th>
						<th>Actions</th>
					</tr> 
				</div>
			</thead>

			<?php while ($row = mysqli_fetch_array($results)) { ?>
			<thead>
				<div>
					<tr>
						<td><?php echo $row['item_name']; ?></td>
						<td><?php echo $row['item_quantity'] + $row['unrentable_stock']; ?></td>
						<td><?php echo $row['item_tag']; ?></td>
						<td><?php echo $row['item_cost']; ?></td>				
						<td><?php echo $row['rental_cost']; ?></td>
						<td><?php echo $row['date_modified']; ?></td>
						<td><a href="inventory.php?edit=<?php echo $row['id']; ?>" class="view_btn" onclick="document.getElementById('id01').style.display='block'"><i class="fa fa-pencil-square-o"></i></a>
							<a href="inv_server.php?del=<?php echo $row['id']; ?>" class="check_btn"><i class="fa fa-remove"></i></a></td>
						</tr>
						<?php } ?>
					</div>
				</thead>
			</table>
		</div>
	</div>

	<!-- Inventory form -->
	<form method="post" action="inv_server.php" >
		<input type="hidden" name="id" value="<?php echo $id; ?>">
	<div id="id01" class="w3-modal" style="z-index:4">
		<div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px;">
			<header class="w3-container w3-yellow"> 
				<h2 class="w3-center">Inventory Form</h2>
			</header>
			<div class="w3-center">
				<span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-xlarge w3-hover-indigo w3-display-topright" title="Close Modal">&times;</span>
				<img src="css/img/inv/maintenance.png" alt="Avatar" style="width:20%; margin-bottom: 10px;">
			</div>

			<div class="w3-row">
				<div class="w3-col s6 w3-indigo w3-center">
					<form class="w3-container" action="#">
						<div class="w3-section">
							<label><b>Item Name</b></label>
							<input class="w3-input w3-border w3-margin-bottom" type="text" name="item_name" pattern="[a-zA-Z0-9]" value="<?php echo $item_name; ?>" required>
							<label><b>Rentable Stock</b></label>
							<input class="w3-input w3-border" type="number" name="item_quantity" min="0" value="<?php echo $item_quantity; ?>" required>
						</div>
					</form>
				</div>
				<div class="w3-col s6 w3-indigo w3-center">
					<form class="w3-container" action="#">
						<div class="w3-section">
							<label><b>Unrentable Stock</b></label>
							<input class="w3-input w3-border w3-margin-bottom" type="number" name="unrentable_stock" min="0" value="<?php echo $unrentable_stock; ?>" required>
							<label><b>Item Tag</b></label>
							<select name="item_tag">
								<option>None</option>
								<option>Dishware</option>
								<option>Utensil</option>
								<option>Tablecloth</option>
								<option>Glassware</option>
								<option>Tables and Chairs</option>
							</select>
						</div>
					</form>
				</div>
				<div class="w3-col s6 w3-indigo w3-center">
					<form class="w3-container" action="#">
						<div class="w3-section">
							<label><b>Price</b></label>
							<input class="w3-input w3-border w3-margin-bottom" type="number" name="item_cost" min="0" step="0.01" value="<?php echo $item_cost; ?>" required>
							<label><b>Rental Cost</b></label>
							<input class="w3-input w3-border" type="number" name="item_cost" min="0" step="0.01" value="<?php echo $rental_cost; ?>" required>
						</div>
					</form>
				</div>
				<div class="w3-section">
			<a class="w3-button w3-black w3-hover-red" style="transition-duration: 0.3s;" onclick="document.getElementById('id01').style.display='none'">Cancel  <i class="fa fa-remove"></i></a>
			<?php if ($update == true): ?>
          <button class="w3-button w3-light-grey w3-hover-red w3-right" style="transition-duration: 0.3s;" type="submit" name="update">update  <i class="fa fa-paper-pencil"></i></button> 
          <?php else: ?>
          	 <button class="w3-button w3-light-grey w3-hover-red w3-right" style="transition-duration: 0.3s;" type="submit" name="save">Submit <i class="fa fa-paper-plane"></i></button> 
          	<?php endif?>
				</div>
			</div>
		</div>
	</div>
</form>









		<!-- End page content -->
	</div>

	<div id="container-floating">

		<div class="nd1 nds" data-toggle="tooltip" data-placement="left" data-original-title="settings">
			<a href="index.php" class="letter" data-tooltip="Home"><i class="fa fa-home"></i></a>
		</div>

		<div class="nd3 nds" data-toggle="tooltip" data-placement="left" data-original-title="message">
			<a href="messaging_admin.php" class="letter" data-tooltip="Compose"><i class="fa fa-envelope"></i></a>
		</div>

		<div id="floating-button" data-toggle="tooltip" data-placement="left" data-original-title="Create" onclick="newmail()">
			<p class="plus"><i class="fa fa-user"></i></p>
			<img class="edit" src="https://ssl.gstatic.com/bt/C3341AA7A1A076756462EE2E5CD71C11/1x/bt_compose2_1x.png">
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

</body>
</html>

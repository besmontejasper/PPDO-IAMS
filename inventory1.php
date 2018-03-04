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
	<link rel="stylesheet" type="text/css" href="css/inv_style.css">
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
				<!-- <a href="#" class="w3-bar-item w3-button w3-padding w3-hover-blue"><i class="fa fa-eye fa-fw"></i>  Visits</a>-->
   <!--  <a href="staff.php" class="w3-bar-item w3-button w3-padding w3-hover-blue"><i class="fa fa-users fa-fw"></i>  Staff</a>
    <a href="payroll.php" class="w3-bar-item w3-button w3-padding w3-hover-blue"><i class="fa fa-money"></i>  Generate Payroll</a>
    <a href="records_payroll.php" class="w3-bar-item w3-button w3-padding w3-hover-blue"><i class="fa fa-book fa-fw"></i>  Payroll Records</a> -->
    <a href="records_reservation.php" class="w3-bar-item w3-button w3-padding w3-hover-yellow"><i class="fa fa-wrench fa-fw"></i>  Repair Records</a>

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
<form method="post" action="inv_server.php" >
	<input type="hidden" name="id" value="<?php echo $id; ?>">
	<div class="input-group">
		<label>Item Name</label>
		<input type="text" name="item_name" patter="[a-zA-Z0-9]" value="<?php echo $item_name; ?>" required>
	</div>
	<div class="input-group">
		<label>Rentable Stock</label>
		<input type="number" name="item_quantity" min="0" value="<?php echo $item_quantity; ?>" required>
	</div>
	<div class="input-group">
		<label>Unrentable Stock</label>
		<input type="number" name="unrentable_stock" min="0" value="<?php echo $unrentable_stock; ?>" required>
	</div>
	<div class="input-group">
		<label>Item Tag</label>
		<select name="item_tag">
			<option>None</option>
			<option>Dishware</option>
			<option>Utensil</option>
			<option>Tablecloth</option>
			<option>Glassware</option>
			<option>Tables and Chairs</option>
		</select>
	</div>
	<div class="input-group">
		<label>Price</label>
		<input type="number" name="item_cost" min="0" step="0.01" value="<?php echo $item_cost; ?>" required>
	</div>
	<div class="input-group">
		<label>Rental Cost</label>
		<input type="number" name="rental_cost" min="0" step="0.01" value="<?php echo $rental_cost; ?>" required>
	</div>
	<div class="input-group">
		<?php if ($update == true): ?>
			<button class="btn" type="submit" name="update" style="background: #556B2F; margin-top: 2%;" >Update</button>
		<?php else: ?>
			<button class="btn" type="submit" name="save" style="margin-top: 2%;" >Save</button>
		<?php endif ?>
	</div>
	
</form>
<table>
	<thead>
		<tr>
			<div class="tags">
				<th>Item Name</th>
				<th>Rentable Stock</th>
				<th>Unrentable Stock</th>
				<th>Tag</th>
				<th>Price</th>
				<th>Rental Cost</th>
				<th>Date Modified</th>
				<th>Actions</th>
			</div>
		</tr>
	</thead>

	<?php while ($row = mysqli_fetch_array($results)) { ?>
	<tr>
		<td><?php echo $row['item_name']; ?></td>
		<td><?php echo $row['item_quantity']; ?></td>
		<td><?php echo $row['unrentable_stock']; ?></td>
		<td><?php echo $row['item_tag']; ?></td>
		<td><?php echo $row['item_cost']; ?></td>				
		<td><?php echo $row['rental_cost']; ?></td>
		<td><?php echo $row['date_modified']; ?></td>
		<td><a href="inventory.php?edit=<?php echo $row['id']; ?>" class="edit_btn" >Edit</a>
			<a href="inv_server.php?del=<?php echo $row['id']; ?>" class="del_btn">Delete</a></td>
		</tr>
		<?php } ?>
	</table>


	<!--   End page content -->
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

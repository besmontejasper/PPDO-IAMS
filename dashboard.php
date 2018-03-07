<?php 
if (!isset($_SESSION)) {
	session_start();
}
$db = mysqli_connect('localhost', 'root', '', 'ppdo_iams');
$lastname = $_SESSION['lastname'];
$firstname = $_SESSION['firstname'];
$username = $_SESSION['username'];
$id = $_SESSION['id'];

include 'include/user_check.php';

?>
<!DOCTYPE html>
<html>
<title>Sherwin's Catering</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="css/fab.css">
<link rel="stylesheet" type="text/css" href="css/w3schools.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
html,body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
* {box-sizing:border-box;}
ul {list-style-type: none;}
</style>

<body class="w3-light-grey">

	<!-- Top container -->
	<div class="w3-bar w3-top w3-yellow w3-large" style="z-index:4">
		<button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-black" onclick="w3_open();"><i class="fa fa-bars"></i>  Menu</button> <!-- responsive menu -->
		<span class="w3-bar-item w3-margin-left"><img src="css/img/inv/dashboard_logo.png"></span>

		<span class="w3-bar-item w3-right" style="font-weight: bold; font-size: 30px;">DASHBOARD</span>
	</div>

	<!-- Sidebar/menu -->
	<nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar"><br>
		<div class="w3-container w3-row">
			<div class="w3-col s8 w3-bar">

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
				</div>
			</div>
			<hr>
			<div class="w3-container">
				<h5 class="w3-text-amber" style="font-size: 20px;">Dashboard</h5>
			</div>
			<div class="w3-bar-block">
				<a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>
				

				<div class="w3-bar-item w3-button w3-hover-yellow" onclick="myAccFunc()"><i class="fa fa-envelope"></i>
					Messaging <i class="fa fa-caret-down"></i></div>
					<div id="demoAcc" class="w3-hide w3-white w3-card-4">
						<a href="messaging.php" class="w3-bar-item w3-button w3-hover-deep-orange"><i class="fa fa-inbox w3-margin-right"></i>Inbox<i class="fa fa-caret-right w3-margin-left"></i></a>
						<a href="messaging_sent.php" class="w3-bar-item w3-button w3-hover-green"><i class="fa fa-paper-plane w3-margin-right"></i>Sent</a>
						<a href="messaging_trash.php" class="w3-bar-item w3-button w3-hover-red"><i class="fa fa-trash w3-margin-right"></i>Trash</a>
					</div>
					<a href="request_repair.php" class="w3-bar-item w3-button w3-padding w3-hover-yellow"><i class="fa fa-list"></i>  Request Repairs</a>
					
				</div>
			</nav>


			<!-- Overlay effect when opening sidebar on small screens -->
			<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

			<!-- !PAGE CONTENT! -->
			<div class="w3-main" style="margin-left:300px;margin-top:43px;">

				<!-- Header -->
				<header class="w3-container" style="padding-top:22px">
					<h5><b><i class="fa fa-dashboard"></i> Your Dashboard</b></h5>
				</header>

				<div class="w3-row-padding w3-margin-bottom">

					<div class="w3-quarter">
						<div class="w3-container w3-red w3-padding-16">
							<div class="w3-left"><i class="fa fa-envelope w3-xxxlarge"></i></div>
							<div class="w3-right">
								<h3>
									<?php 
									$results = mysqli_query($db, "SELECT * FROM messaging WHERE unread=1");
									$message_count = mysqli_num_rows($results);
									echo $message_count;
									?>
								</h3>
							</div>
							<div class="w3-clear"></div>
							<h4>Unread Messages</h4>
						</div>
					</div>

					<div class="w3-quarter">
						<div class="w3-container w3-indigo w3-padding-16">
							<div class="w3-left"><i class="fa fa-wrench w3-xxxlarge"></i></div>
							<div class="w3-right">
								<h3>
									<?php 
									$requested_by = $_SESSION['firstname']." ".$_SESSION['lastname'];
									$results = mysqli_query($db, "SELECT * FROM item_request WHERE requested_by='$requested_by'");
									$request_count = mysqli_num_rows($results);
									echo $request_count;
									?>
								</h3>
							</div>
							<div class="w3-clear"></div>
							<h4>Repairs</h4>
						</div>
					</div>


					<hr>
					<div class="w3-container">
						<h5><i class="fa fa-wrench"></i> Request Repairs</h5>
						<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
							
							<tr>
								<td>Item Name</td>
								<td>Quantity</td>
								<td>Item Tag</td>
								<td>Request Date</td>
								<td>Status</td>
							</tr>
							<tr>
								<?php
								$results_request = mysqli_query($db, "SELECT * FROM item_request WHERE requested_by='$requested_by' ORDER BY id DESC LIMIT 5"); 
								while ($row_request = (mysqli_fetch_array($results_request))) {
									?>
									<td><?php echo $row_request['item_name'] ?></td>
									<td><?php echo $row_request['requested_quantity'] ?></td>
									<td><?php echo $row_request['item_tag'] ?></td>
									<td><?php echo $row_request['request_date'] ?></td>
									<td><?php echo $row_request['request_status'] ?></td>
								</tr>
								<?php 
							} 
							if ($request_count == 0) { 
								?>
								<tr>
									<td colspan="5">
										<center><p>You haven't made any requests.</p></center>       
									</td>
								</tr>
								<?php
							}
							?>
						</table><br>
						<?php 
						if ($request_count!=0) { ?>

						<a href="request_repair.php"><button class="w3-button w3-yellow w3-padding w3-hover-amber" style="transition-duration: 0.3s;">More   <i class="fa fa-arrow-right"></i></button></a>

						<?php 
					}
					?>
				</div>
				<hr>



				<!-- End page content -->
<!-- </div>

<div id="container-floating">
  <div class="nd3 nds" data-toggle="tooltip" data-placement="left" data-original-title="message">
       <a href="messaging.php" class="letter" data-tooltip="Compose"><i class="fa fa-envelope"></i></a>
  </div>
  <div class="nd1 nds" data-toggle="tooltip" data-placement="left" data-original-title="settings">
      <a href="user_edit.php" class="letter" data-tooltip="Change"><i class="fa fa-cog"></i></a>
  </div>
  <div class="nd4 nds" data-toggle="tooltip" data-placement="left" data-original-title="home">
      <a href="index.php" class="letter" data-tooltip="Home"><i class="fa fa-home"></i></a>
  </div>

  <div id="floating-button" data-toggle="tooltip" data-placement="left" data-original-title="Create" onclick="newmail()">
    <p class="plus"><i class="fa fa-user"></i></p>
    <img class="edit" src="https://ssl.gstatic.com/bt/C3341AA7A1A076756462EE2E5CD71C11/1x/bt_compose2_1x.png">
</div> -->

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
			x.previousElementSibling.className += " w3-yellow";
		} else { 
			x.className = x.className.replace(" w3-show", "");
			x.previousElementSibling.className = 
			x.previousElementSibling.className.replace(" w3-yellow", "");
		}
	}

	function myDropFunc() {
		var x = document.getElementById("demoDrop");
		if (x.className.indexOf("w3-show") == -1) {
			x.className += " w3-show";
			x.previousElementSibling.className += " w3-yellow";
		} else { 
			x.className = x.className.replace(" w3-show", "");
			x.previousElementSibling.className = 
			x.previousElementSibling.className.replace(" w3-yellow", "");
		}
	}
</script>

</body>
</html>

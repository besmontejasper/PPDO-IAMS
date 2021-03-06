<?php 


if (!isset($_SESSION)) {
	session_start();
}

$db = mysqli_connect('localhost', 'root', '', 'ppdo_iams');
include('include/user_check.php');
include('include/user_inaccessible.php');
$fulfill_check=1;
$is_printed_fulfill=0;
$is_printed_empty=0;
$pending = "pending";
$approved = "approved";
$rejected = "rejected";
$canceled = "canceled";

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/w3schools.css">
	<link rel="stylesheet" type="text/css" href="css/repair_log.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<title>Repair Log</title>
</head>
<body>
	<div class="w3-container">

		<?php
		if (isset($_GET['pending'])) {
			$results = mysqli_query($db, "SELECT * FROM item_request WHERE request_status = '$pending' "); 
			($count = mysqli_num_rows($results));
			if ($count==0){
				echo "No data found in the database.";
			} 
			else if ($count!=0){ 
				?>
				<h2 style="color: goldenrod;">Pending Repairs</h2>
				<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
					<thead>
						<div>
							<tr>
								<th>Repair Number</th>
								<th>Item Name</th>
								<th>Requested By</th>
								<th>Quantity</th>
								<th>Item Tag</th>
								<th>Request Date</th>
								<th>Status</th>
							</tr> 
						</div>
					</thead>
					<?php } ?>
					<?php 
					while ($row = mysqli_fetch_array($results)){
						if ($row['request_fulfilled']==0){ 
							?>
							<thead>
								<div>
									<tr>
										<td><?php echo $row['id']; ?></td>
										<td><?php echo $row['item_name']; ?></td>
										<td><?php echo $row['requested_by'];  ?></td>
										<td><?php echo $row['requested_quantity']; ?></td>
										<td><?php echo $row['item_tag']; ?></td>
										<td><?php echo $row['request_date']; ?></td>
										<td><?php echo $row['request_status']; ?></td>
									</tr>   
								</div>
							</thead>

							<?php
						} 
					}
					?>
				</table>
		<?php
		}
		else if (isset($_GET['approved'])) {
			$results = mysqli_query($db, "SELECT * FROM item_request WHERE request_status = '$approved' "); 
			($count = mysqli_num_rows($results));
			if ($count==0){
				echo "No data found in the database.";
			} 
			else if ($count!=0){ 
				?>
				<h2 style="color: green;">Approved Repairs</h2>
				<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
					<thead>
						<div>
							<tr>
								<th>Repair Number</th>
								<th>Item Name</th>
								<th>Requested By</th>
								<th>Quantity</th>
								<th>Item Tag</th>
								<th>Request Date</th>
								<th>Status</th>
							</tr> 
						</div>
					</thead>
					<?php 
					while ($row = mysqli_fetch_array($results)){ 
						?>
						<thead>
							<div>
								<tr>
									<?php if ($row['request_fulfilled']==1) {?>
									<td><?php echo $row['id']; ?></td>
									<td><?php echo $row['item_name']; ?></td>
									<td><?php echo $row['requested_by'];  ?></td>
									<td><?php echo $row['requested_quantity']; ?></td>
									<td><?php echo $row['item_tag']; ?></td>
									<td><?php echo $row['request_date']; ?></td>
									<td><?php echo $row['request_status']; ?></td>
									<?php } ?>
								</tr>   
							</div>
						</thead>
						<?php }
					}
					?>
				</table>  
			<?php
		}
		else if (isset($_GET['rejected'])) {
			$results = mysqli_query($db, "SELECT * FROM item_request WHERE request_status = '$rejected' "); 
			($count = mysqli_num_rows($results));
			if ($count==0){
				echo "No data found in the database.";
			} 
			else if ($count!=0){ 
				?>
				<h2 style="color: crimson;">Rejected Repairs</h2>
				<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
					<thead>
						<div>
							<tr>
								<th>Repair Number</th>
								<th>Item Name</th>
								<th>Requested By</th>
								<th>Quantity</th>
								<th>Item Tag</th>
								<th>Request Date</th>
								<th>Status</th>
							</tr> 
						</div>
					</thead>
					<?php 
					while ($row = mysqli_fetch_array($results)){ 
						?>
						<thead>
							<div>
								<tr>
									<?php if ($row['request_status']=="rejected") {?>
									<td><?php echo $row['id']; ?></td>
									<td><?php echo $row['item_name']; ?></td>
									<td><?php echo $row['requested_by'];  ?></td>
									<td><?php echo $row['requested_quantity']; ?></td>
									<td><?php echo $row['item_tag']; ?></td>
									<td><?php echo $row['request_date']; ?></td>
									<td><?php echo $row['request_status']; ?></td>
									<?php } ?>
								</tr>   
							</div>
						</thead>
						<?php }
					} ?>
				</table>  

			<?php
		}
		else if (isset($_GET['canceled'])) {
			$results = mysqli_query($db, "SELECT * FROM item_request WHERE request_status = '$canceled' "); 
			($count = mysqli_num_rows($results));
			if ($count==0){
				echo "No data found in the database.";
			} 
			else if ($count!=0){ 
				?>
				<h2 style="color: red;">Canceled Repairs</h2>
				<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
					<thead>
						<div>
							<tr>
								<th>Repair Number</th>
								<th>Item Name</th>
								<th>Requested By</th>
								<th>Quantity</th>
								<th>Item Tag</th>
								<th>Request Date</th>
								<th>Status</th>
							</tr> 
						</div>
					</thead>
					<?php 
					while ($row = mysqli_fetch_array($results)){ 
						?>
						<thead>
							<div>
								<tr>
									<?php if ($row['request_status']=="canceled") {?>
									<td><?php echo $row['id']; ?></td>
									<td><?php echo $row['item_name']; ?></td>
									<td><?php echo $row['requested_by'];  ?></td>
									<td><?php echo $row['requested_quantity']; ?></td>
									<td><?php echo $row['item_tag']; ?></td>
									<td><?php echo $row['request_date']; ?></td>
									<td><?php echo $row['request_status']; ?></td>
									<?php } ?>
								</tr>   
							</div>
						</thead>
						<?php }
					}
					?>
				</table>
	<?php
		}
		else if (isset($_GET['all'])) {
			$results = mysqli_query($db, "SELECT * FROM item_request WHERE request_status = '$pending' "); 
			($count = mysqli_num_rows($results));
			if ($count!=0){ 
				?>
				<h2 style="color: goldenrod;">Pending Repairs</h2>
				<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
					<thead>
						<div>
							<tr>
								<th>Repair Number</th>
								<th>Item Name</th>
								<th>Requested By</th>
								<th>Quantity</th>
								<th>Item Tag</th>
								<th>Request Date</th>
								<th>Status</th>
							</tr> 
						</div>
					</thead>
					<?php } ?>
					<?php 
					while ($row = mysqli_fetch_array($results)){
						if ($row['request_fulfilled']==0){ 
							?>
							<thead>
								<div>
									<tr>
										<td><?php echo $row['id']; ?></td>
										<td><?php echo $row['item_name']; ?></td>
										<td><?php echo $row['requested_by'];  ?></td>
										<td><?php echo $row['requested_quantity']; ?></td>
										<td><?php echo $row['item_tag']; ?></td>
										<td><?php echo $row['request_date']; ?></td>
										<td><?php echo $row['request_status']; ?></td>
									</tr>   
								</div>
							</thead>

							<?php
						} 
					}
					?>
				</table>
		<?php
			$results = mysqli_query($db, "SELECT * FROM item_request WHERE request_status = '$approved' "); 
			($count = mysqli_num_rows($results));
			if ($count!=0){ 
				?>
				<h2 style="color: green;">Fulfilled Repairs</h2>
				<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
					<thead>
						<div>
							<tr>
								<th>Repair Number</th>
								<th>Item Name</th>
								<th>Requested By</th>
								<th>Quantity</th>
								<th>Item Tag</th>
								<th>Request Date</th>
								<th>Status</th>
							</tr> 
						</div>
					</thead>
					<?php 
					while ($row = mysqli_fetch_array($results)){ 
						?>
						<thead>
							<div>
								<tr>
									<?php if ($row['request_fulfilled']==1) {?>
									<td><?php echo $row['id']; ?></td>
									<td><?php echo $row['item_name']; ?></td>
									<td><?php echo $row['requested_by'];  ?></td>
									<td><?php echo $row['requested_quantity']; ?></td>
									<td><?php echo $row['item_tag']; ?></td>
									<td><?php echo $row['request_date']; ?></td>
									<td><?php echo $row['request_status']; ?></td>
									<?php } ?>
								</tr>   
							</div>
						</thead>
						<?php }
					}
					?>
				</table>  
		<?php
			$results = mysqli_query($db, "SELECT * FROM item_request WHERE request_status = '$rejected' "); 
			($count = mysqli_num_rows($results));
			if ($count!=0){ 
				?>
				<h2 style="color: crimson;">Rejected Repairs</h2>
				<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
					<thead>
						<div>
							<tr>
								<th>Repair Number</th>
								<th>Item Name</th>
								<th>Requested By</th>
								<th>Quantity</th>
								<th>Item Tag</th>
								<th>Request Date</th>
								<th>Status</th>
							</tr> 
						</div>
					</thead>
					<?php 
					while ($row = mysqli_fetch_array($results)){ 
						?>
						<thead>
							<div>
								<tr>
									<?php if ($row['request_status']=="rejected") {?>
									<td><?php echo $row['id']; ?></td>
									<td><?php echo $row['item_name']; ?></td>
									<td><?php echo $row['requested_by'];  ?></td>
									<td><?php echo $row['requested_quantity']; ?></td>
									<td><?php echo $row['item_tag']; ?></td>
									<td><?php echo $row['request_date']; ?></td>
									<td><?php echo $row['request_status']; ?></td>
									<?php } ?>
								</tr>   
							</div>
						</thead>
						<?php }
					} ?>
				</table>  
		<?php
			$results = mysqli_query($db, "SELECT * FROM item_request WHERE request_status = '$canceled' "); 
			($count = mysqli_num_rows($results));
			if ($count!=0){ 
				?>
				<h2 style="color: red;">Canceled Repairs</h2>
				<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
					<thead>
						<div>
							<tr>
								<th>Repair Number</th>
								<th>Item Name</th>
								<th>Requested By</th>
								<th>Quantity</th>
								<th>Item Tag</th>
								<th>Request Date</th>
								<th>Status</th>
							</tr> 
						</div>
					</thead>
					<?php 
					while ($row = mysqli_fetch_array($results)){ 
						?>
						<thead>
							<div>
								<tr>
									<?php if ($row['request_status']=="canceled") {?>
									<td><?php echo $row['id']; ?></td>
									<td><?php echo $row['item_name']; ?></td>
									<td><?php echo $row['requested_by'];  ?></td>
									<td><?php echo $row['requested_quantity']; ?></td>
									<td><?php echo $row['item_tag']; ?></td>
									<td><?php echo $row['request_date']; ?></td>
									<td><?php echo $row['request_status']; ?></td>
									<?php } ?>
								</tr>   
							</div>
						</thead>
						<?php }
					}
					?>
				</table>
		<?php
		}
		?>
					</div>

					<div id="container-floating">
						<div id="floating-button" data-toggle="tooltip" data-placement="left" data-original-title="Create" onclick="printFunction()" data-tooltip="Print" class="printing">
							<p class="plus"><i class="fa fa-print"></i></p>
						</div>
					</div>  

					<script>
						function printFunction() {
							window.print();
						}
					</script>

					<script type="text/javascript">
						// ==UserScript==  
						GM_addStyle('@media print { a:link:after, a:visited:after { content: none; }}');  
					</script>

				</body>
				</html>

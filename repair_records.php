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
$cancelled = "cancelled";
?>
<!DOCTYPE html>
<html>
<head>
  <title>Repair Records</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="css/w3schools.css">
  <link rel="stylesheet" type="text/css" href="css/fab_admin.css">
  <link rel="stylesheet" href="css/w3schools.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="css/rsrvp_style.css">
</head>
<body>

<?php 
	if (isset($_GET['approve'])) {
	 $id = $_GET['approve'];
	 $modal_type = "approve";
	 ?>
	 <body onload = "remark()"> </body>
	 <?php
	}
	else if (isset($_GET['reject'])) {
	  $id = $_GET['reject'];
	 $modal_type = "reject";
	  ?>
	 <body onload = "remark()"> </body>
	 <?php
	}

	else if (isset($_GET['undo'])) {
	 $id = $_GET['undo'];
	 $modal_type = "undo";
	 ?>
	 <body onload = "remark()"> </body>
	 <?php
	 }
?>

  <!-- Top container -->
  <div class="w3-bar w3-top w3-black w3-large" style="z-index:4">
    <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i>  Menu</button> <!-- responsive menu -->
    <span class="w3-bar-item w3-margin-left">SHERWIN'S CATERING</span>
    <span class="w3-bar-item w3-right">REPAIR RECORDS</span>
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
        <!--    <a href="messaging_admin.php" class="w3-bar-item w3-button"><i class="fa fa-envelope"></i></a> -->
      </div>
    </div>
    <hr>
    <div class="w3-bar-block">
      <a href="dashboard_admin.php" class="w3-bar-item w3-button w3-padding w3-hover-none w3-blue w3-hover-text-blue" style="font-size: 20px; transition-duration: 0.3s;">Dashboard</a>
      <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>
      <h5 class="w3-text-blue w3-padding"><i class="fa fa-diamond fa-fw"></i>  Repair Records</a></h5>
      <a href="inventory.php" class="w3-bar-item w3-button w3-padding w3-hover-blue"><i class="fa fa-list"></i>  Inventory</a>



    </div>
  </nav>
</div>
<!-- END OF INSERTED CONTAINER -->


<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">


 <div class="w3-container">

  <?php
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
            <th>Remarks</th>
            <th>Actions</th>
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
                <td><?php echo $row['request_remark']; ?></td>
                <td>
                  <a href="repair_records.php?approve=<?php echo $row['id']; ?>" class="approve_btn"><i class="fa fa-check"></i></a>&nbsp;
                  <a href="repair_records.php?reject=<?php echo $row['id']; ?>" class="reject_btn"><i class="fa fa-check"></i></a>&nbsp;
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
                <th>Remarks</th>
                <th>Actions</th>
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
 	               <td><?php echo $row['request_remark']; ?></td>
                  <td><a href="repair_records.php?undo=<?php echo $row['id']; ?>" class="undo_btn" ><i class="fa fa-close"></i></a>
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
                    <th>Remarks</th>
                    <th>Actions</th>
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
        	        <td><?php echo $row['request_remark']; ?></td>
                      <td><a href="repair_records.php?undo=<?php echo $row['id']; ?>" class="undo_btn" ><i class="fa fa-close"></i></a>
                        <?php } ?>
                      </tr>   
                    </div>
                  </thead>
                  <?php }
                } ?>
              </table>  

              <?php
              $results = mysqli_query($db, "SELECT * FROM item_request WHERE request_status = '$cancelled' "); 
              ($count = mysqli_num_rows($results));
              if ($count!=0){ 
                ?>
                <h2 style="color: red;">Cancelled Repairs</h2>
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
                        <th>Remarks</th>
                        <th>Actions</th>
                      </tr> 
                    </div>
                  </thead>
                  <?php 
                  while ($row = mysqli_fetch_array($results)){ 
                    ?>
                    <thead>
                      <div>
                        <tr>
                          <?php if ($row['request_status']=="cancelled") {?>
                          <td><?php echo $row['id']; ?></td>
                          <td><?php echo $row['item_name']; ?></td>
                          <td><?php echo $row['requested_by'];  ?></td>
                          <td><?php echo $row['requested_quantity']; ?></td>
                          <td><?php echo $row['item_tag']; ?></td>
                          <td><?php echo $row['request_date']; ?></td>
                          <td><?php echo $row['request_status']; ?></td>
              			  <td><?php echo $row['request_remark']; ?></td>
                          <td></td>
                          <?php } ?>
                        </tr>   
                      </div>
                    </thead>
                    <?php }
                  }
                  ?>
                </table>  


                <div id="modal" class="w3-modal">
                  <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px">

                    <div class="w3-center">
                     <header class="w3-container w3-yellow w3-padding"> 
                      <h2 class="w3-center"><b>Remarks<b></h2>
                    </header>
                  </div>

                  <form method="post" class="w3-container" action="request_server.php">
                    <div class="w3-section">
					<input type="hidden" name="id" value="<?php echo $id ?>">
					<input class="w3-input w3-border w3-margin-bottom" style="height:150px" name="message_body" placeholder="Remarks" required>
					<?php 
						if ($modal_type == "approve") {
							?>
								<input type="hidden" name="request_fulfilled" value="1">
								<input type="hidden" name="request_status" value="approved">
		                      <button class="w3-button w3-block w3-green w3-section w3-padding" type="submit" name="approve">Approve</button>
							<?php
						}
						else if ($modal_type == "reject") {
							?>
								<input type="hidden" name="request_fulfilled" value="0">
								<input type="hidden" name="request_status" value="rejected">
		                      <button class="w3-button w3-block w3-green w3-section w3-padding" type="submit" name="reject">Reject</button>
							<?php
						}
						else if ($modal_type == "undo") {
							?>
								<input type="hidden" name="request_fulfilled" value="0">
								<input type="hidden" name="request_status" value="pending">
		                      <button class="w3-button w3-block w3-green w3-section w3-padding" type="submit" name="undo">Undo</button>
		                    <?php
						}
					?>   
                    </div>
                  </form>
                  <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
                    <form action="repair_records.php">
                      <button onclick="document.getElementById('modal').style.display='none'" type="submit" class="w3-button w3-red">Cancel</button>
                    </form>
                    </div>
                </div>

                





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

function remark() {
	document.getElementById('modal').style.display='block';
}
</script>


</body>
</html>
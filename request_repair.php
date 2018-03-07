<?php if (!isset($_SESSION)) {
    session_start();
}
?>


<?php  

include 'inv_server.php';
include 'include/user_check.php';
$request_username = "";
$canceled = "canceled";
$username=$_SESSION['username'];

if (isset($_GET['rent'])) {
    $id = $_GET['rent'];
    $update = true;       
    $record = mysqli_query($db, "SELECT * FROM item_inventory WHERE id=$id");

    if (count($record) == 1 ) {
        $n = mysqli_fetch_array($record);
        $item_name = $n['item_name'];
        $item_quantity = $n['item_quantity'];
        $item_tag = $n['item_tag'];
        $request_status = "pending";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Request Repairs</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/w3schools.css">
    <link rel="stylesheet" type="text/css" href="css/fab.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script>
        function errorField()
        {
            alert("Requested quantity cannot exceed stock!");
        }
    </script>
</head>
<body>

  <?php 
    if (isset($_GET['cancel'])) {
     $id = $_GET['cancel'];
     $cancel_query = mysqli_query($db, "SELECT * FROM item_request WHERE id='$id'");
     $row = mysqli_fetch_array($cancel_query);
     $requested_quantity = $row['requested_quantity'];
     $inventory_id = $row['inventory_id'];
     ?>
     <body onload = "remark()"> </body>
     <?php
    }
    ?>
    <!-- Top container -->
    <div class="w3-bar w3-top w3-yellow w3-large" style="z-index:4">
      <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i>  Menu</button> <!-- responsive menu -->
      <span class="w3-bar-item w3-margin-left"><img src="css/img/inv/dashboard_logo.png"></span>
      <span class="w3-bar-item w3-right" style="font-weight: bold; font-size: 30px;">REQUEST REPAIRS</span>
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
          </div>
      </div>
      <hr>
 <div class="w3-bar-block">
        <a href="dashboard.php" class="w3-bar-item w3-button w3-padding w3-hover-none w3-amber w3-hover-text-orange" style="font-size: 20px; transition-duration: 0.3s;">Dashboard</a>
        <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>
            <a href="javascript:void(0)" class="w3-bar-item w3-button w3-dark-grey w3-button w3-hover-green w3-left-align" onclick="document.getElementById('id01').style.display='block'" style="transition-duration: 0.3s;">Request<i class="w3-padding fa fa-ticket"></i></a>
        </div>
    </div>
  </nav>
<!-- END OF INSERTED CONTAINER -->
<br>
<br>
<?php if (isset($_GET['error'])) { ?> 
<?php echo '<script> errorField() </script>'; ?>    
<?php } ?>
<div id="id01" class="w3-modal" style="z-index:4;">
    <div class="w3-modal-content w3-animate-zoom" style="width: 400px;">
        <div class="w3-container w3-padding w3-indigo">
            <header class="w3-padding"> 
              <h2 class="w3-center" style="color: white;">Request Form</h2>
              </header>
              <form method="post" action="request_server.php" class="w3-container" >

                <?php 
                $results = mysqli_query($db, "SELECT * FROM item_inventory"); 
                
                ?>
                <div class="w3-section">
                    <div class="input-group w3-margin-bottom">
                        <label>Item Name</label>
                        <select class="w3-input" name="item_name".>
                            <?php 
                            while ($row=mysqli_fetch_array($results)){ 
                                if ($row['item_quantity']!=0) {
                                    ?>

                                    <option>
                                        <?php
                                        echo $row['item_name'];     
                                        ?>    
                                    </option>
                                    <?php  
                                }
                            } 
                            ?>
                        </select>
                    </div>
                    <div class="input-group w3-margin-bottom">
                        <label>Quantity</label>
                        <input type="number" class="w3-input w3-border w3-margin-bottom"  name="requested_quantity" min="0" value="<?php echo $requested_quantity; ?>" required>
                    </div>
                    <div class="input-group w3-margin-bottom">
                        <button class="btn w3-button w3-green" type="submit" name="rent" >Send Request  <i class="fa fa-paper-plane"></i></button>
                        <button onclick="document.getElementById('id01').style.display='none'" type="button" class="w3-button w3-red">Cancel <i class="fa fa-remove"></i></button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>


<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">
 <div class="w3-container">
    <?php $results = mysqli_query($db, "SELECT * FROM item_inventory"); ?>
    <h2 style="color: goldenrod;">Current Stock</h2>
    <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white w3-margin-bottom">
        <thead>
            <tr>
                <div class="tags">
                    <th>Item Name</th>
                    <th>Stock</th>
                </div>
            </tr>
        </thead>

        <?php $requestrecord = mysqli_query($db, "SELECT * FROM item_inventory");
        if (mysqli_num_rows($requestrecord) > 0) {
            while($row = mysqli_fetch_array($requestrecord)) { ?>
            <tr>
                <td><?php echo $row['item_name']; ?></td>
                <td><?php echo $row['item_quantity']; ?></td>
            </tr>
            <?php
        } 
    } 
    ?>
</table>
<?php 
$requested_by = $_SESSION['firstname']." ".$_SESSION['lastname'];
$results = mysqli_query($db, "SELECT * FROM item_request WHERE requested_by='$requested_by'"); 
?>
<h2 style="color: green;">Repair Requests</h2>
<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white w3-margin-bottom">
    <thead>
        <tr>
            <div class="tags">      
                <th>Item Name</th>
                <th>Request Quantity</th>
                <th>Request Date</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Action</th>
            </div>    
        </tr>
    </thead>    
    <?php while ($row = mysqli_fetch_array($results)) { ?>
    <tr>
        <td><?php echo $row['item_name']; ?></td>
        <td><?php echo $row['requested_quantity']; ?></td>
        <td><?php echo $row['request_date']; ?></td>
        <td><?php echo $row['request_status']; ?></td>
        <td><?php echo $row['request_remark']; ?></td>
        <td>
            <?php 
            if ($row['request_status'] == "canceled" || $row['request_status'] == "rejected") {

            }
            else { ?>
            <a href="request_repair.php?cancel=<?php echo $row['id']; ?>" class="cancel_btn" ><i class="fa fa-close"></i></a>
            <?php 
        }
        ?>

    </tr>
    <?php } ?>    
</table>
</div>
</div>
</td>
</tr>
</table>
</div>
</div>

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
	        <input type="hidden" name="request_fulfilled" value="0">
	        <input type="hidden" name="request_status" value="canceled">
	        <input type="hidden" name="requested_quantity" value="<?php echo $requested_quantity; ?>">
	        <input type="hidden" name="inventory_id" value="<?php echo $inventory_id; ?>">
	      <button class="w3-button w3-block w3-green w3-section w3-padding" type="submit" name="cancel">Cancel Request</button>
	    </div>
	  </form>
	  <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
	     <form action="request_repair.php">
	      <button onclick="document.getElementById('modal').style.display='none'" type="submit" class="w3-button w3-red">Cancel</button>
	    </form>
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

function remark() {
    document.getElementById('modal').style.display='block';
}
</script>

</body>
</html>

<?php 

if (!isset($_SESSION)) {
  session_start();
}
$db = mysqli_connect('localhost', 'root', '', 'sherwincatering');
include('include/user_check.php');
include('include/user_inaccessible.php');
$fulfill_check=1;
$is_printed_fulfill=0;
$is_printed_empty=0;

if (isset($_GET['check'])) {
	$order_number = $_GET['check'];
	$rsrv_fulfilled = 1;
	mysqli_query($db, "UPDATE menu_reservation SET rsrv_fulfilled=$rsrv_fulfilled WHERE order_number=$order_number");
	header('location: records_reservation.php');
}
if (isset($_GET['uncheck'])) {
 $order_number = $_GET['uncheck'];
 $rsrv_fulfilled = 0;
 mysqli_query($db, "UPDATE menu_reservation SET rsrv_fulfilled=$rsrv_fulfilled WHERE order_number=$order_number");
 header('location: records_reservation.php');
}

if (isset($_GET['delete'])) {
	$order_number = $_GET['delete'];
  mysqli_query($db, "DELETE FROM menu_reservation WHERE order_number=$order_number");
  mysqli_query($db, "DELETE FROM bf_reservation WHERE order_number=$order_number");
  mysqli_query($db, "DELETE FROM bs_reservation WHERE order_number=$order_number");
  mysqli_query($db, "DELETE FROM ch_reservation WHERE order_number=$order_number");
  mysqli_query($db, "DELETE FROM d_reservation WHERE order_number=$order_number");
  mysqli_query($db, "DELETE FROM f_reservation WHERE order_number=$order_number");
  mysqli_query($db, "DELETE FROM p_reservation WHERE order_number=$order_number");
  mysqli_query($db, "DELETE FROM sn_reservation WHERE order_number=$order_number");
  mysqli_query($db, "DELETE FROM v_reservation WHERE order_number=$order_number");
  header('location: records_reservation.php');
}
?>
<?php  
if (!isset($_SESSION)){
  session_start();
}

$db = mysqli_connect('localhost', 'root', '', 'sherwincatering');
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
<!DOCTYPE html>
<html>
<head>
  <title>Reservation Records</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="css/rsrvp_style.css">
  <link rel="stylesheet" type="text/css" href="css/fab_admin.css">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
        <a href="javascript:void(0)" class="w3-bar-item w3-button w3-dark-grey w3-button w3-hover-indigo w3-left-align" onclick="document.getElementById('id01').style.display='block'" style="transition-duration: 0.3s;">Add Job Order<i class="w3-padding fa fa-plus"></i></a>
        <!-- <a href="#" class="w3-bar-item w3-button w3-padding w3-hover-blue"><i class="fa fa-eye fa-fw"></i>  Visits</a>-->
   <!--  <a href="staff.php" class="w3-bar-item w3-button w3-padding w3-hover-blue"><i class="fa fa-users fa-fw"></i>  Staff</a>
    <a href="payroll.php" class="w3-bar-item w3-button w3-padding w3-hover-blue"><i class="fa fa-money"></i>  Generate Payroll</a>
    <a href="records_payroll.php" class="w3-bar-item w3-button w3-padding w3-hover-blue"><i class="fa fa-book fa-fw"></i>  Payroll Records</a> -->
    <a href="records_reservation.php" class="w3-bar-item w3-button w3-padding w3-hover-yellow"><i class="fa fa-wrench fa-fw"></i>  Repair Records</a>

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
  $results = mysqli_query($db, "SELECT * FROM menu_reservation WHERE is_not_empty = 1"); 
  ($count = mysqli_num_rows($results));
  if ($count!=0){ 
    ?>
    <br/>
    <h2 style="color: crimson;">Pending Reservation</h2>
    <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
      <thead>
        <div>
          <tr>
            <th>Item #</th>
            <th>Item Tag</th>
            <th>Item Name</th>
            <th>Price</th>
            <th>Rental Cost</th>
            <th>Date Modified</th>
            <th>Actions</th>
          </tr> 
        </div>
      </thead>
      <?php } ?>
      <?php 
      while ($row = mysqli_fetch_array($results)){
        if ($row['rsrv_fulfilled']==0){ 
          ?>
          <thead>
            <div>
              <tr>
                <td><?php echo $row['order_number']; ?></td>
                <td><?php echo $row['rsrv_eventname']; ?></td>
                <td><?php echo $row['rsrv_lname'].", ".$row['rsrv_fname']." ".$row['rsrv_mname'];  ?></td>
                <td><?php echo $row['rsrv_date']." ".$row['rsrv_calltime']; ?></td>
                <td></td>
                <td></td>
                <td><a href="invoice.php?view=<?php echo $row['order_number']; ?>" class="view_btn"><i class="fa fa-eye"></i></a>&nbsp;
                  <a href="records_reservation.php?check=<?php echo $row['order_number']; ?>" class="check_btn"><i class="fa fa-check"></i></a></td>
                </tr>   
              </div>
            </thead>

            <?php
          } 
        }
        ?>
      </table>
      <?php 
      $results = mysqli_query($db, "SELECT * FROM menu_reservation WHERE is_not_empty = 1"); 

      while ($row = mysqli_fetch_array($results)){
        if ($row['rsrv_fulfilled']==1){ 

          if ($is_printed_fulfill!=1){

            ?>
            <h2 style="color: goldenrod;">Fulfilled Reservation</h2>
            <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
              <thead>
                <div>
                  <tr>
                    <th>Item #</th>
            <th>Item Tag</th>
            <th>Item Name</th>
            <th>Price</th>
            <th>Rental Cost</th>
            <th>Date Modified</th>
            <th>Actions</th>
                  </tr> 
                </div>
              </thead>
              <?php
              $is_printed_fulfill=1; 
            }
          } 
        }
        ?>
        <?php 
        $results = mysqli_query($db, "SELECT * FROM menu_reservation WHERE is_not_empty = 1"); 
        while ($row = mysqli_fetch_array($results)){ 
          ?>
          <thead>
            <div>
              <tr>
                <?php if ($row['rsrv_fulfilled']==1) {?>
                <td><?php echo $row['order_number']; ?></td>
                <td><?php echo $row['rsrv_eventname']; ?></td>
                <td><?php echo $row['rsrv_lname'].", ".$row['rsrv_fname']." ".$row['rsrv_mname'];  ?></td>
                <td><?php echo $row['rsrv_date']." ".$row['rsrv_calltime']; ?></td>
                <td><a href="records_reservation.php?uncheck=<?php echo $row['order_number']; ?>" class="uncheck_btn" ><i class="fa fa-close"></i></a>
                  <?php } ?>
                </tr>   
              </div>
            </thead>
            <?php } ?>
          </table>  
          <?php 
          $results = mysqli_query($db, "SELECT * FROM menu_reservation WHERE is_not_empty = 0"); 
          while ($row = mysqli_fetch_array($results)){
            if ($row['is_not_empty']==0){ 

              if ($is_printed_empty!=1){

                ?>
                <h2 style="color: midnightblue;">Empty Reservation</h2>
                <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
                  <thead>
                    <div>
                      <tr>
                         <th>Item #</th>
            <th>Item Tag</th>
            <th>Item Name</th>
            <th>Price</th>
            <th>Rental Cost</th>
            <th>Date Modified</th>
            <th>Actions</th>
                      </tr> 
                    </div>
                  </thead>
                  <?php
                  $is_printed_empty=1; 
                }
              } 
            }
            ?>
            <?php 
            $results = mysqli_query($db, "SELECT * FROM menu_reservation WHERE is_not_empty = 0"); 
            while ($row = mysqli_fetch_array($results)){ 
              ?>
              <thead>
                <div>
                  <tr>
                    <td><?php echo $row['order_number']; ?></td>
                    <td><?php echo $row['rsrv_eventname']; ?></td>
                    <td><?php echo $row['rsrv_lname'].", ".$row['rsrv_fname']." ".$row['rsrv_mname'];  ?></td>
                    <td><?php echo $row['rsrv_date']." ".$row['rsrv_calltime']; ?></td>
                    <td></td>
                    <td></td>
                    <td><a href="records_reservation.php?delete=<?php echo $row['order_number']; ?>" class="del" ><i class="fa fa-trash"></i></a>  
                    </tr>   
                  </div>
                </thead>
                <?php } ?>
              </table>

              <?php if (isset($_GET['error'])) { ?> 
              <?php echo '<script> errorField() </script>'; ?>  
              <?php } ?>
              <?php $results = mysqli_query($db, "SELECT * FROM item_inventory"); ?>
              <div id="id01" class="w3-modal" style="z-index:4">
               <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px;">
                <header class="w3-container w3-yellow"> 
                  <h2 class="w3-center">Job Order Entry Form</h2>
                </header>
                <div class="w3-center">
                  <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-xlarge w3-hover-indigo w3-display-topright" title="Close Modal">&times;</span>
                  <img src="css/img/inv/maintenance.png" alt="Avatar" style="width:20%; margin-bottom: 10px;">
                </div>

                <div class="w3-row">
                  <div class="w3-col s6 w3-indigo w3-center">
                   <form class="w3-container" action="#">
                    <div class="w3-section">
                      <label><b>Job Order</b></label>
                      <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Enter Job Order" name="jobOrder">
                      <label><b>Work Area</b></label>
                      <input class="w3-input w3-border" type="text" placeholder="Enter Work Area" name="workArea">
                    </div>
                  </form>
                </div>
                
                <div class="w3-col s6 w3-indigo w3-center">
                 <form class="w3-container" action="#">
                  <div class="w3-section">
                    <label><b>Completion Date</b></label>
                    <input class="w3-input w3-border w3-margin-bottom" type="date" placeholder="Enter Job Order" name="jobOrder">
                    <label><b>Date Released</b></label>
                    <input class="w3-input w3-border" type="date" placeholder="Enter Work Area" name="workArea">
                  </div>
                </form>
              </div>
              
              <div class="w3-col w3-indigo w3-center">
                <form class="w3-container" action="#">
                  <div class="w3-section">
                    <label><b>Description</b></label>
                    <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Input Description" name="Description">
                  </div>
                </form>
              </div>
              
              <div class="w3-col s6 w3-indigo w3-center">
               <form class="w3-container" action="#">
                <div class="w3-section">
                  <label><b>Unit</b></label>
                  <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Enter Job Order" name="jobOrder">
                  <label><b>Quantity</b></label>
                  <input class="w3-input w3-border" type="number" min="0" placeholder="Enter Work Area" name="workArea">
                </div>
              </form>
            </div>
            
            <div class="w3-col s6 w3-indigo w3-center">
             <form class="w3-container" action="#">
              <div class="w3-section">
                <label><b>PPDO staff name</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Enter PPDO Staff Namer" name="ppdoStaff">
                <label><b>Materials PR #</b></label>
                <input class="w3-input w3-border" type="text" placeholder="Enter Material PR#" name="materialPR">
              </div>
            </form>
          </div>

          <div class="w3-col w3-indigo w3-center">
           <form class="w3-container" action="#">
            <div class="w3-section">
              <label><b>Revision</b></label>
              <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Type here" name="revision">
              <label><b>Status</b></label>
              <input class="w3-input w3-border" type="text" placeholder="Type here" name="status">
            </div>
          </form>
        </div>
        <div class="w3-col w3-indigo w3-center">
         <form class="w3-container" action="#">
          <div class="w3-section">
            <label><b>Order Covering Note</b></label>
            <input class="w3-radio w3-margin-bottom" type="radio" name="print" value="orderCoveringNote" checked="checked">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label><b>Material Issue Note</b></label>
            <input class="w3-radio w3-margin-bottom" type="radio" name="print" value="materialIssueNote">
          </div>
        </form>
      </div>
    </div>
    <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
     <a class="w3-button w3-hover-red" style="transition-duration: 0.3s;" onclick="document.getElementById('id01').style.display='none'">Cancel  <i class="fa fa-remove"></i></a>
     <button class="w3-button w3-light-grey w3-hover-yellow w3-right" style="transition-duration: 0.3s;" type="submit" name="send">Send  <i class="fa fa-paper-plane"></i></button> 
     <button class="w3-button w3-light-grey w3-hover-green w3-right" style="transition-duration: 0.3s;" type="reset" name="refresh">Refresh  <i class="fa fa-refresh"></i></button> 
   </div>
 </div>
</div>
































       <!--            <form method="post" action="inv_server.php" >
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
                     <div class="w3-section">
          <a class="w3-button w3-black w3-hover-red" style="transition-duration: 0.3s;" onclick="document.getElementById('id01').style.display='none'">Cancel  <i class="fa fa-remove"></i></a>
          <button class="w3-button w3-light-grey w3-hover-red w3-right" style="transition-duration: 0.3s;" type="submit" name="send">Send  <i class="fa fa-paper-plane"></i></button> 
        </div>    
                  </form>
                </div>
              </div> -->









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
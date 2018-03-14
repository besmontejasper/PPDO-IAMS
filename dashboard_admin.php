<?php 

  if (!isset($_SESSION)) {
   session_start();
  }
  $db = mysqli_connect('localhost', 'root', '', 'ppdo_iams');
  include('include/user_check.php');
  include('include/user_inaccessible.php');
  $critical_count = 0;

  // Data analytics for Messages
  $message_query = mysqli_query($db, "SELECT * FROM messaging_admin WHERE unread=1");
  $message_count = mysqli_num_rows($message_query);

  // Data analytics for Job Orders
  $completed = "completed";
  $jo_query = mysqli_query($db, "SELECT * FROM job_order WHERE status!='$completed'");
  $jo_count = mysqli_num_rows($jo_query);

  // Data analytics for Repairs
  $pending = "pending";
  $repair_query = mysqli_query($db, "SELECT * FROM item_request WHERE request_status = '$pending'");
  $repair_count = mysqli_num_rows($repair_query);

  // Data analytics for Repairs
  $pending = "approved";
  $repair_query = mysqli_query($db, "SELECT * FROM item_request WHERE request_status = '$pending'");
  $repair_count_approved = mysqli_num_rows($repair_query);

  // Data analytics for Repairs
  $pending = "rejected";
  $repair_query = mysqli_query($db, "SELECT * FROM item_request WHERE request_status = '$pending'");
  $repair_count_rejected = mysqli_num_rows($repair_query);

  // Data analytics for Repairs
  $pending = "canceled";
  $repair_query = mysqli_query($db, "SELECT * FROM item_request WHERE request_status = '$pending'");
  $repair_count_canceled = mysqli_num_rows($repair_query);

  // Data analytics for Critical Level of Stock
  $critical_query = mysqli_query($db, "SELECT * FROM item_inventory");
  while ($critical_row = (mysqli_fetch_array($critical_query))) { 
    $item_quantity = $critical_row['item_quantity'];
    $critical_stock = $critical_row['critical_stock'];
    if ($item_quantity <= $critical_stock) {
      $critical_count++;
    }
  }	   
?>
<!DOCTYPE html>
<html>
<title>Electronic Inventory System</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
 <link rel="stylesheet" type="text/css" href="css/w3schools.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" type="text/css" href="css/fab_admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
html,body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
* {box-sizing:border-box;}
ul {list-style-type: none;}
</style>

<body class="w3-light-grey">

  <!-- Top container -->
  <div class="w3-bar w3-top w3-indigo w3-large" style="z-index:4">
    <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i>  Menu</button> <!-- responsive menu -->
    <span class="w3-bar-item w3-margin-left"><img src="css/img/inv/dashboard_logo.png"></span>
    <span class="w3-bar-item w3-right" style="font-weight: bold; color: white; font-size: 30px;">DASHBOARD</span>
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
        <!--   <a href="messaging_admin.php" class="w3-bar-item w3-button"><i class="fa fa-envelope"></i></a> -->
        <!--      <a href="#" class="w3-bar-item w3-button"><i class="fa fa-user"></i></a> -->
        <!--   <a href="user_edit.php" class="w3-bar-item w3-button"><i class="fa fa-cog"></i></a> -->
      </div>
    </div>
    <hr>
    <div class="w3-container">
      <h5 class="w3-text-indigo" style="font-size: 20px;">Dashboard</h5>
    </div>
    <div class="w3-bar-block">
      <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>
      
      <div class="w3-bar-item w3-button w3-hover-indigo" onclick="myAccFunc()"><i class="fa fa-envelope"></i>&nbsp;Messaging <i class="fa fa-caret-down"></i></div>
       <div id="demoAcc" class="w3-hide w3-white w3-card-4">
         <a href="messaging_admin.php" class="w3-bar-item w3-button w3-hover-deep-orange"><i class="fa fa-inbox w3-margin-right"></i>Inbox<i class="fa fa-caret-right w3-margin-left"></i></a>
         <a href="messaging_sent_admin.php" class="w3-bar-item w3-button w3-hover-green"><i class="fa fa-paper-plane w3-margin-right"></i>Sent</a>
         <a href="messaging_trash_admin.php" class="w3-bar-item w3-button w3-hover-red"><i class="fa fa-trash w3-margin-right"></i>Trash</a>
       </div>
       <a href="inventory.php" class="w3-bar-item w3-button w3-padding w3-hover-indigo"><i class="fa fa-list w3-large"></i>&nbsp;Inventory</a>
       <a href="repair_records.php" class="w3-bar-item w3-button w3-padding w3-hover-indigo"><i class="fa fa-wrench fa-fw w3-large"></i> Repair Records</a>
       <a href="job_order.php" class="w3-bar-item w3-button w3-padding w3-hover-indigo"><i class="fa fa-cubes w3-large"></i>&nbsp;Job Orders</a>
       <a href="wattage_compute.php?building_name=SNGAH" class="w3-bar-item w3-button w3-padding w3-hover-indigo"><i class="fa fa-plug w3-large"></i>&nbsp;Wattage Consumption</a>
     </div>
</nav>


<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-columns" style="color: #0f49ac;"></i> Your Dashboard</b></h5>
  </header>


  <div class="w3-row-padding w3-margin-bottom">
    <div class="w3-quarter">
      <div class="w3-container w3-red w3-padding-16">
        <div class="w3-left"><i class="fa fa-comments w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3>
            <?php 
              echo $message_count;
            ?>
          </h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Unread Messages</h4>
      </div>
    </div>


    <div class="w3-quarter">
      <div class="w3-container w3-teal w3-padding-16">
        <div class="w3-left"><i class="fa fa-list w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3>
            <?php
              echo $jo_count;
            ?>
          </h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Pending Job Orders</h4>
      </div>
    </div>
    <div class="w3-quarter">
      <div class="w3-container w3-orange w3-text-white w3-padding-16">
        <div class="w3-left"><i class="fa fa-wrench w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3>
            <?php 
            if ($repair_count != 0) {
              echo $repair_count;  
            }
            else {
              echo "0";
            }
            ?>
          </h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Pending Repairs</h4>
      </div>
    </div>
    <div class="w3-quarter">
      <div class="w3-container w3-blue w3-text-white w3-padding-16">
        <div class="w3-left"><i class="fa fa-shopping-cart w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3>
            <?php 
              echo $critical_count;
            ?>
          </h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Critical Stocks</h4>
      </div>
    </div>
  </div>


    <hr>
    <div class="w3-container">
      <h5><i class="fa fa-list" style="color: #0f49ac"></i>  Inventory</h5>
      <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
        <?php 
        $results_inv = mysqli_query($db, "SELECT * FROM item_inventory LIMIT 5"); 
        ?>
        <thead>
          <tr>
            <th>Item</td>
              <th>Quantity</td>
              </tr>
            </thead>
            <?php
            while ($row_inv = (mysqli_fetch_array($results_inv))) {
              ?>
              <tbody>
                <tr>
                  <td><?php echo $row_inv['item_name'] ?></td>
                  <td><?php echo $row_inv['item_quantity'] ?></td>
                </tr>
              </tbody>
              <?php } ?>
            </table><br>
            <br>
            <a href="inventory.php"><button class="w3-button w3-black w3-hover-indigo" style="transition-duration: 0.3s;">More   <i class="fa fa-arrow-right"></i></button></a>
          </div>
          <hr>

          <div class="w3-container">
            <h5><i class="fa fa-industry fa-fw" style="color: #0f49ac"></i>Data Analytics</h5>
            <div id="piechart"></div>
          </div>
          <hr>


        

      <script type="text/javascript" src="js/analytics.js"></script>

      <script type="text/javascript">
// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

// Draw the chart and set the chart values
function drawChart() {
	var pending_count = <?php echo htmlspecialchars($repair_count);?>;
	var approved_count = <?php echo htmlspecialchars($repair_count_approved);?>;
	var rejected_count = <?php echo htmlspecialchars($repair_count_rejected);?>;
	var canceled_count = <?php echo htmlspecialchars($repair_count_canceled);?>;
    var data = new google.visualization.DataTable();
	data.addColumn('string', 'Request Status');
	data.addColumn('number', 'Number of Request');
    data.addRow(['Pending Requests', pending_count]);
    data.addRow(['Approved Requests', approved_count]);
    data.addRow(['Rejected Requests', rejected_count]);
    data.addRow(['Canceled Requests', canceled_count]);

	
  	

  // Optional; add a title and set the width and height of the chart
  var options = {'title':'Electronic Inventory System Pie Chart', 'width':550, 'height':400};

  // Display the chart inside the <div> element with id="piechart"
  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
  chart.draw(data, options);
}
</script>

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
</script>

 <!-- script for floating notification -->
<script type="text/javascript">
  var n=0;
  function increaseNumber(){
    n=n+1;
      document.getElementById('num1').innerHTML=n;
      zeroNotifs();
  }
  function zeroNotifs(){
    if (document.getElementById('num1').innerHTML==0){
          document.getElementById('box1').style.display="none";
    }
    else{
        document.getElementById('box1').style.display="block";
    }
  }
</script>

</body>
</html>
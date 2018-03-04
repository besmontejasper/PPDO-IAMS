<?php 

  if (!isset($_SESSION)) {
   session_start();
  }
  $db = mysqli_connect('localhost', 'root', '', 'ppdo_iams');
  include('include/user_check.php');
  include('include/user_inaccessible.php');

  // Data analytics for Repairs
  $cancelled = "cancelled";
  $repair_query = mysqli_query($db, "SELECT * FROM item_request WHERE request_status != '$cancelled'");
  $repair_count = mysqli_num_rows($repair_query);
?>
<!DOCTYPE html>
<html>
<title>Electronic Inventory System</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
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

      <a href="inventory.php" class="w3-bar-item w3-button w3-padding w3-hover-yellow"><i class="fa fa-list"></i>  Inventory</a>
    <a href="repair_records.php" class="w3-bar-item w3-button w3-padding w3-hover-yellow"><i class="fa fa-wrench fa-fw"></i>  Repair Records</a>
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
          <h3>52</h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Request Service</h4>
      </div>
    </div>
    <div class="w3-quarter">
      <div class="w3-container w3-teal w3-padding-16">
        <div class="w3-left"><i class="fa fa-list w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3>23</h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Inventory</h4>
      </div>
    </div>
    <div class="w3-quarter">
      <div class="w3-container w3-orange w3-text-white w3-padding-16">
        <div class="w3-left"><i class="fa fa-wrench w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3>
            <?php 
            echo $repair_count;
            ?>
          </h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Repairs</h4>
      </div>
    </div>
    <div class="w3-quarter">
      <div class="w3-container w3-blue w3-text-white w3-padding-16">
        <div class="w3-left"><i class="fa fa-shopping-cart w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3>50</h3>
        </div>
        <div class="w3-clear"></div>
        <h4>No. of Stocks</h4>
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
            <a href="inventory.php"><button class="w3-button w3-black w3-hover-yellow" style="transition-duration: 0.3s;">More   <i class="fa fa-arrow-right"></i></button></a>
          </div>
          <hr>
          <div class="w3-container">
            <h5><i class="fa fa-area-chart fa-fw" style="color: #0f49ac"></i>General Stats</h5>
            <p>Failed Repairs</p>
            <div class="w3-grey">
              <div class="w3-container w3-center w3-padding w3-green" style="width:25%">+25%</div>
            </div>

            <p>New Repairs</p>
            <div class="w3-grey">
              <div class="w3-container w3-center w3-padding w3-orange" style="width:50%">50%</div>
            </div>

            <p>Done Repairs</p>
            <div class="w3-grey">
              <div class="w3-container w3-center w3-padding w3-red" style="width:75%">75%</div>
            </div>
          </div>
          <hr>

          <hr>
          <div class="w3-container">
            <h5><i class="fa fa-industry fa-fw" style="color: #0f49ac"></i>Data Analytics</h5>
            <div id="piechart"></div>
          </div>
          <hr>


        <!-- End page content -->
        </div>

        <div id="container-floating">
          <div class="nd1 nds" data-toggle="tooltip" data-placement="left" data-original-title="settings">
            <a href="index.php" class="letter" data-tooltip="Home"><i class="fa fa-home"></i></a>
          </div>

          <div class="nd3 nds" data-toggle="tooltip" data-placement="left" data-original-title="message">
           <a href="messaging_admin.php" class="letter" data-tooltip="Compose"><i class="fa fa-envelope"></i></a>
           <div id="box1"><p id="num1">0</p></div>
         </div>

         <div id="floating-button" data-toggle="tooltip" data-placement="left" data-original-title="Create" onclick="newmail()">
          <div id="box"><p id="number">!</p></div>
          <p class="plus"><i class="fa fa-user"></i></p>
          <img class="edit" src="https://ssl.gstatic.com/bt/C3341AA7A1A076756462EE2E5CD71C11/1x/bt_compose2_1x.png">
        </div>
      </div>

      <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

      <script type="text/javascript">
// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

// Draw the chart and set the chart values
function drawChart() {
  var data = google.visualization.arrayToDataTable([
    ['Task', 'Hours per Day'],
    ['Repairs', 8],
    ['Inventory', 2],
    ['Maintenance', 4],
    ['Sales', 2],
    ['Losts', 8]
    ]);

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


// script for floating notification
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
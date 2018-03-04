<?php 
  
  if(!isset($_SESSION)) 
    { 
        session_start(); 
    }

  $db = mysqli_connect('localhost', 'root', '', 'ppdo_iams');
  
  $username=$_SESSION['username'];


    
  include('include/user_check.php');
?>
<!DOCTYPE html>
<html>
<title>Sherwin's Catering</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="css/rsrvp_style.css">
<link rel="stylesheet" type="text/css" href="css/fab.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
html,body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
* {box-sizing:border-box;}
ul {list-style-type: none;}
</style>

<body class="w3-light-grey">

<!-- Top container -->
<div class="w3-bar w3-top w3-black w3-large" style="z-index:4">
  <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i>  Menu</button> <!-- responsive menu -->
  <span class="w3-bar-item w3-margin-left">SHERWIN'S CATERING</span>
  <span class="w3-bar-item w3-right">RESERVATION RECORDS</span>
</div>

<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar"><br>
  <div class="w3-container w3-row">
    <div class="w3-col s8 w3-bar">

<!-- logged in user information -->
    <?php  if (isset($_SESSION['username'])) : ?>
     <span><p>Welcome <strong><br>
      <?php 
        if ($_SESSION['type']=="Member"){
          echo $_SESSION['firstname']." ".$_SESSION['lastname'];
        } 
        elseif ($_SESSION['type']=="Administrator"){
          echo "Administrator";
        }
      ?></strong></p></span><br>
    <?php endif ?>
    <span><?php include 'user_logout.php'; ?></span><br>
    </div>
  </div>
  <hr>
  <div class="w3-bar-block">
    <a href="dashboard_admin.php" class="w3-bar-item w3-button w3-padding w3-hover-none w3-blue w3-hover-text-blue" style="font-size: 20px; transition-duration: 0.3s;">Dashboard</a>
    <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>
    
    <h5 class="w3-text-blue w3-padding"><i class="fa fa-list-alt"></i>  Reservation Records</a></h5>
    <a href="rentals.php" class="w3-bar-item w3-button w3-padding w3-hover-blue"><i class="fa fa-list"></i>  Rentals</a>
    <a href="reservation.php" class="w3-bar-item w3-button w3-padding w3-hover-blue"><i class="fa fa-pencil-square-o"></i>  Reservations</a>
    


  </div>
</nav>


<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">


 <div class="w3-container">

    <?php
    $results = mysqli_query($db, "SELECT * FROM menu_reservation WHERE rsrv_username='$username' LIMIT 5"); 
    ($count = mysqli_num_rows($results));
      if ($count!=0){ 
  ?>
  <h2 style="color: goldenrod;">Reservations Made</h2>
  <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
    <thead>
      <div>
        <tr>
        <th>Transaction Number</th>
        <th>Event Name</th>
        <th>Date and Time</th>
        <th>Actions</th>
        </tr> 
      </div>
    </thead>
    <?php } ?>
    <?php 
      while ($row = mysqli_fetch_array($results)){ 
    ?>
    <thead>
      <div>
        <tr>
          <td><?php echo $row['order_number']; ?></td>
          <td><?php echo $row['rsrv_eventname']; ?></td>
          <td><?php echo $row['rsrv_date']." ".$row['rsrv_calltime']; ?></td>
          <td><a href="invoice.php?view=<?php echo $row['order_number']; ?>" class="view_btn"><i class="fa fa-eye"></i></a>&nbsp;
        </tr>   
      </div>
    </thead>

    <?php
       } 
      
    ?>
  </table>

 


  <!-- End page content -->
</div>

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
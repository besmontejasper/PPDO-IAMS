<?php 
  $db = mysqli_connect('localhost', 'root', '', 'ppdo_iams');
  if (!isset($_SESSION)){
    session_start();
  }
  include 'include/user_check.php';

  $update=false;
  $update_pass=0;
  $pass_check=0;
  if (isset($_GET['edit'])) {
    $update=true;
  }
  if (isset($_GET['ch_pass'])) {
    $update_pass=$_GET['update_pass'];
    $pass_check=$_GET['pass_check'];
  }
  
?>

<!DOCTYPE html>
<html>
<head>
<title>Profile</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="css/fab.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script>
  function oldPassError()
  {
    alert("Incorrect password!");
  }

  function newPassError()
  {
    alert("Password did not match!");
  }

  function duplicateFieldError()
  {
  	alert("That name is already registered!");
  }

  </script>
</head>
<style>
html,body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
* {box-sizing:border-box;}
ul {list-style-type: none;}
</style>

<body class="w3-light-grey">

<!-- Top container -->
<div class="w3-bar w3-top w3-black w3-large" style="z-index:4">
  <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i>  Menu</button> <!-- responsive menu -->

  <span class="w3-bar-item w3-right">PROFILE</span>
</div>

<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar"><br>
  <div class="w3-container w3-row">
    <div class="w3-col s8 w3-bar">

<!-- logged in user information -->
    <?php  if (isset($_SESSION['username'])) : ?>
     <span><p>Welcome <strong><?php echo $_SESSION['firstname']." ".$_SESSION['lastname']; ?></strong></p></span><br>
      <span><?php include 'user_logout.php'; ?></span><br>
    <?php endif ?>
    </div>
  </div>
  <hr>
 
  <div class="w3-bar-block">
    <a href="dashboard.php" class="w3-bar-item w3-button w3-padding w3-hover-none w3-blue w3-hover-text-blue" style="font-size: 20px;">Dashboard</a>
    <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>
    <a href="rentals.php" class="w3-bar-item w3-button w3-padding w3-hover-blue"><i class="fa fa-list"></i>  Rentals</a>
    <a href="reservation.php" class="w3-bar-item w3-button w3-padding w3-hover-blue"><i class="fa fa-pencil-square-o"></i>  Reservations</a>
    <a href="view_reservations.php" class="w3-bar-item w3-button w3-padding w3-hover-blue"><i class="fa fa-list-alt"></i>  Reservation Records</a>

  </div>
</nav>


<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

  <?php 
    $current_user = $_SESSION['id'];
	$results = mysqli_query($db, "SELECT * FROM users WHERE id=$current_user"); 
  	while ($row = mysqli_fetch_array($results)) { 
  		$current_fn=$row['firstname'];
  		$current_ln=$row['lastname'];	
	}
    ?>

  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-user w3-xxlarge"></i> <?php echo $current_fn." ".$current_ln; ?>'s Profile</b></h5>
  </header>


<?php if (isset($_GET['error_old'])): ?> 
      <?php echo '<script> oldPassError() </script>'; ?>  
    <?php endif ?>
    <?php if (isset($_GET['error_new'])): ?> 
      <?php echo '<script> newPassError() </script>'; ?>  
    <?php endif ?>
    <?php if (isset($_GET['duplicate'])): ?> 
      <?php echo '<script> duplicateFieldError() </script>'; ?>  
    <?php endif ?>


 <div class="w3-container">
    <h5>General Account Settings</h5>
    
    <?php 
      $current_user = $_SESSION['id'];
      $results = mysqli_query($db, "SELECT * FROM users WHERE id=$current_user"); 
      while ($row = mysqli_fetch_array($results)) { 
    ?>

    <?php if ($update==false): ?>
      
   <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
         <tr>
          <td>First Name</td><td><?php echo $row['firstname'];  ?></td>
        </tr>
        <tr>
          <td>Middle Name</td><td><?php echo $row['middlename'];  ?></td>
        </tr>
        <tr>
          <td>Last Name</td><td><?php echo $row['lastname'];  ?></td>
        </tr>
        <tr>  
          <td>Contact No.</td><td><?php echo $row['contact']; ?></td>
        </tr>
        <tr> 
          <td>Email</td><td><?php echo $row['email']; ?></td>
        </tr>
         <tr>
        <td>Password</td>
        <td>
          <?php if ($update_pass==1 && $pass_check==1): ?>
            <form method="post" action="user_server.php">
            <input type="hidden" name="id" value="<?php echo $current_user; ?>">
            <input type="password" name="ch_pass0" placeholder="Enter Old Password">
            <input type="password" name="ch_pass1" placeholder="Enter New Password">
            <input type="password" name="ch_pass2" placeholder="Confirm Password">
            <button class="w3-button w3-dark-grey w3-hover-red" style="transition-duration: 0.3s;" type="submit" name=edit_pass>Update Password</button>
            </form>
          <?php elseif ($update_pass==0 && $pass_check==0): ?>
            <a href="user_edit.php?ch_pass&update_pass=1&pass_check=1"><button class="w3-button w3-dark-grey w3-hover-red" style="transition-duration: 0.3s;">Change Password</button></a>
          <?php endif ?>
        </td>
      </tr>
    </table>
   
  <?php else: ?>
    <form method="post" action="user_server.php">
    <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
      <tr>
        <input type="hidden" name="id" value="<?php echo $current_user; ?>">
        <td>First Name</td>
        <td><input type="text" name="ch_firstname" pattern="[a-zA-Z ]{1,}" value="<?php echo $row['firstname'];  ?>" required></td>
      </tr>
      <tr>      
       <tr>
        <td>Middle Name</td>
        <td><input type="text" name="ch_middlename" pattern="[a-zA-Z ]{1,}" value="<?php echo $row['middlename'];  ?>"></td>
      </tr>
      <tr>  
       <tr>
        <td>Last Name</td>
        <td><input type="text" name="ch_lastname" pattern="[a-zA-Z ]{1,}" value="<?php echo $row['lastname'];  ?>" required></td>
      </tr>
      <tr>
        <td>Contact No.</td>
        <td><input type="text" name="ch_contact" pattern="[0]+[9]\d{9}|\d{7}|[\+]+[6]+[3]+[9]\d{9}|\d{3}+[\-]+\d{2}+[\-]+\d{2}" value="<?php echo $row['contact'];  ?>" required></td>
      </tr>
      <tr>
        <td>Email</td>
        <td></td>
      </tr>
      <tr>
        <td>Password</td>
        <td></td>
      </tr>
     

      <?php endif ?>
    </table>
    <br>
    <?php if ($update==true):  ?>
      <button class="w3-button w3-dark-grey w3-hover-red" style="transition-duration: 0.3s;" type="submit" name=edit_save>Save</button>
     <?php
          else: 
      ?>
        <a href="user_edit.php?edit=1"><button class="w3-button w3-dark-grey w3-hover-red" style="transition-duration: 0.3s;">Edit</button></a>
      <?php 
          endif
      ?>
  </div>
</form>
 <?php } ?>


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
      <a href="index.php" class="letter" data-tooltip="Visit"><i class="fa fa-home"></i></a>
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

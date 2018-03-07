<?php 

if (!isset($_SESSION)) {
 session_start();
}
$db = mysqli_connect('localhost', 'root', '', 'ppdo_iams');
include('include/user_check.php');
include('include/user_inaccessible.php');
$jo_number = "";
$work_area = "";
$completion_date = "";
$date_released = "";
$description = "";
$quantity = "";
$unit = "";
$ppdo_staff_name = "";
$pr_number = "";
$revision = "";
$status = "";
$note_type = "";


if (isset($_GET['edit'])) {
  $id = $_GET['edit'];
  $update = true;
  $record = mysqli_query($db, "SELECT * FROM job_order WHERE id=$id");

  if (count($record) == 1 ) {
    $n = mysqli_fetch_array($record);
    $jo_number = $n['jo_number'];
    $work_area = $n['work_area'];
    $completion_date = $n['completion_date'];
    $date_released = $n['date_released'];
    $description = $n['description'];
    $quantity = $n['quantity'];
    $unit = $n['unit'];
    $ppdo_staff_name = $n['ppdo_staff_name'];
    $pr_number = $n['pr_number'];
    $revision = $n['revision'];
    $status = $n['status'];
    $note_type = $n['note_type'];
  }
  ?>
  <body onload = "edit_modal()"> </body>
  <?php
}


if (isset($_GET['del'])) {
  $id = $_GET['del'];
  $record = mysqli_query($db, "DELETE FROM job_order WHERE id=$id");
  header('location: job_order.php');
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Job Order</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="css/w3schools.css">
  <link rel="stylesheet" type="text/css" href="css/fab_admin.css">
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
  <!-- Top container -->
  <div class="w3-bar w3-top w3-indigo w3-large" style="z-index:4">
    <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i>  Menu</button> <!-- responsive menu -->
    <span class="w3-bar-item w3-margin-left"><img src="css/img/inv/dashboard_logo.png"></span>
    <span class="w3-bar-item w3-right" style="font-weight: bold; font-size: 30px;">JOB ORDER</span>
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
      <a href="dashboard_admin.php" class="w3-bar-item w3-button w3-padding w3-hover-none w3-indigo w3-hover-text-indigo" style="font-size: 20px; transition-duration: 0.3s;">Dashboard</a>
      <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>
      <a href="javascript:void(0)" class="w3-bar-item w3-button w3-dark-grey w3-button w3-hover-black w3-left-align" onclick="document.getElementById('id01').style.display='block'" style="transition-duration: 0.3s;">Add Job Order <i class="w3-padding fa fa-plus-square"></i></a>
    </div>
  </div>
</nav>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>



<!-- Table for JO -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">
  <div class="w3-container">
<?php $results = mysqli_query($db, "SELECT * FROM job_order"); ?>
    <br>
    <h2 style="color: crimson;">Job Orders</h2>
     <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white w3-margin-bottom w3-responsive">
      <thead>
        <div>
          <tr>
            <th>JO Number</th>
            <th>Work Area</th>
            <th>Completion Date</th>
            <th>Date Released</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Unit</th>
            <th>PPDO Staff</th>
            <th>PR Number</th>
            <th>Revision</th>
            <th>Status</th>
            <th>Actions</th>
          </tr> 
        </div>
      </thead>

      <?php while ($row = mysqli_fetch_array($results)) { ?>
      <thead>
        <div>
          <tr>
            <td><?php echo $row['jo_number']; ?></td>
            <td><?php echo $row['work_area']; ?></td>
            <td>
              <?php 
              if ($row['completion_date'] == "0000-00-00") {
                echo "Not completed";
              }
              else {
                echo $row['completion_date'];  
              }
              ?>
            </td>          
            <td><?php echo $row['date_released']; ?></td>
            <td><?php echo $row['description']; ?></td> 
            <td><?php echo $row['quantity']; ?></td> 
            <td><?php echo $row['unit']; ?></td> 
            <td><?php echo $row['ppdo_staff_name']; ?></td> 
            <td><?php echo $row['pr_number']; ?></td> 
            <td><?php echo $row['revision']; ?></td> 
            <td><?php echo $row['status']; ?></td>
            <td><a href="job_order.php?edit=<?php echo $row['id']; ?>" class="view_btn" onclick="document.getElementById('id01').style.display='block'"><i class="fa fa-pencil-square-o"></i></a>
              <a href="job_order.php?del=<?php echo $row['id']; ?>" class="check_btn"><i class="fa fa-remove"></i></a></td>
            </tr>
            <?php } ?>
          </div>
        </thead>
      </table>
    </div>
  </div>





  <!-- Modal that pops up when you click on "New Message" -->

  <div id="id01" class="w3-modal" style="z-index:4">
    <div class="w3-modal-content w3-animate-zoom">
      <div class="w3-container w3-padding w3-indigo">
       <h2>Create Job Order</h2>
     </div>

     <form method="post" action="jo_server.php" class="w3-container" >
      <div class="w3-panel">
        <div class="w3-twothird w3-margin-left">
          <label>Job Order #</label>
          <input class="w3-input w3-border w3-margin-bottom" type="number" name="jo_number" placeholder="Enter Job Order #" min="0" value="<?php echo $jo_number; ?>">
          <label>Work Area</label>
          <input class="w3-input w3-border w3-margin-bottom" placeholder="Enter Work Area" type="text" name="work_area" value="<?php echo $work_area; ?>"></div>

          <div class="w3-quarter w3-margin-left">
            <label>Completion Date</label>
            <input class="w3-input w3-border w3-margin-bottom" name="completion_date" type="Date"  value="<?php echo $completion_date; ?>">
            <label>Released Date</label>
            <input class="w3-input w3-border w3-margin-bottom" name="date_released" type="date" value='<?php if (isset($_GET['edit'])) {  echo $date_released; } else { echo date('Y-m-d'); }?>'></div>
            <div class="w3-third w3-margin-left ">
              <label>Quantity</label>
              <input class="w3-input w3-border w3-margin-bottom" name="quantity" type="number" placeholder="Enter Quantity"  value="<?php echo $quantity; ?>">
              <label>Unit</label>
              <input class="w3-input w3-border w3-margin-bottom" name="unit" type="text" placeholder="Enter Unit"  value="<?php echo $unit; ?>">         
            </div>

            <div class="w3-quarter w3-margin-left">
              <label>Materials PR #</label>
              <input class="w3-input w3-border w3-margin-bottom" type="number" name="pr_number" placeholder="Enter Materials PR #" min="0" value="<?php echo $pr_number; ?>">
              <label>PPDO Staff Name</label>
              <input class="w3-input w3-border w3-margin-bottom" type="text" name="ppdo_staff_name" placeholder="Enter Staff Name" value="<?php echo $ppdo_staff_name; ?>"></div>  

              <div class="w3-third w3-margin-left">
                <label>Revision</label>
                <input class="w3-input w3-border w3-margin-bottom" name="revision" type="number" placeholder="Enter revision number" min="0"  value="<?php echo $revision; ?>">
                <label>Status</label>
                <select name="status" class="w3-input">
                 <?php 
                 if (isset($_GET['edit'])) {
                  if ($status == "requested") {
                    ?>
                    <option value="requested" selected>requested</option>
                    <option value="ongoing">ongoing</option>
                    <option value="completed">completed</option>
                    <?php
                  }
                   if ($status == "ongoing") {
                    ?>
                    <option value="requested">requested</option>
                    <option value="ongoing" selected>ongoing</option>
                    <option value="completed">completed</option>
                    <?php
                  }
                  else if ($status == "completed") {
                    ?>
                    <option value="requested">requested</option>
                    <option value="ongoing">ongoing</option>
                    <option value="completed" selected>completed</option>
                    <?php
                  }
                }
                else {
                 ?>
                 <option value="requested" selected>requested</option>
                 <option value="ongoing">ongoing</option>
                 <option value="completed">completed</option>
                 <?php
               }
               ?>

             </select>

           </div> 

           <div class="w3-panel">
            <div class="w3-half ">
              <label>Description</label>
              <textarea class="w3-input w3-border" style="height: 125px; resize: none;" name="description" type="text" placeholder="Enter Description"  value="<?php echo $description; ?>" required></textarea>
            </div>   
            <div class="w3-rest w3-margin-left">  
              <div class="w3-container w3-margin-left">
                <label><i class="fa fa-print"></i>Print Options:</label><br><br>
                <?php 
                if (isset($_GET['edit'])) {
                  if ($note_type == "material") {
                    ?>
                    <input type="radio" id="order" name="print" value="order" >
                    <label for="order">Order Covering Note</label><br><br>
                    <input type="radio" id="material" name="print" value="material" checked="checked">
                    <label for="material">Material Issue Note</label>
                    <?php
                  }
                  else {
                    ?> 
                    <input type="radio" id="order" name="print" value="order" checked="checked">
                    <label for="order">Order Covering Note</label><br><br>
                    <input type="radio" id="material" name="print" value="material">
                    <label for="material">Material Issue Note</label>
                    <?php
                  }
                }
                else {
                  ?>
                  <input type="radio" id="order" name="print" value="order" checked="checked">
                  <label for="order">Order Covering Note</label><br><br>
                  <input type="radio" id="material" name="print" value="material">
                  <label for="material">Material Issue Note</label>
                  <?php
                }
                ?>
              </div>
            </div>
          </div>

          <div class="w3-margin-bottom">
           <div class="w3-container">  
            <?php 
            if (isset($_GET['edit'])) {
              ?>
              <input type="hidden" name="edit_id" value="<?php echo $id; ?>">
              <button class="w3-button w3-light-grey w3-hover-blue w3-left" style="transition-duration: 0.3s;" type="submit" name="edit">Edit&nbsp;<i class="fa fa-pencil"></i></button> 
              <?php
            }
            else {
              ?>
              <button class="w3-button w3-light-grey w3-hover-green w3-left" style="transition-duration: 0.3s;" type="submit" name="send">Send&nbsp;<i class="fa fa-paper-plane"></i></button> 
              <?php
            }
            ?>
        </form>
          <form action="job_order.php">
            <button class="w3-button w3-black w3-hover-red w3-left w3-margin-left" style="transition-duration: 0.3s;" onclick="document.getElementById('id01').style.display='none'" type="submit">Cancel&nbsp;<i class="fa fa-remove"></i></button>
          </form>
        </div> 
      </div>
    </div>
  </div>
  <!-- END OF INSERTED CONTAINER -->

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

function edit_modal() {
  document.getElementById('id01').style.display='block';
}
</script>


</body>
</html>
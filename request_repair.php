<?php if (!isset($_SESSION)) {
    session_start();
}
?>


<?php  

    include 'inv_server.php';
    include 'include/user_check.php';
    
    $cancelled = "cancelled";
    $username=$_SESSION['username'];
    
    if (isset($_GET['cancel'])) {
        $id = $_GET['cancel'];
        $request_fulfilled = 0;
        $request_status = "cancelled";
        mysqli_query($db, "UPDATE item_request SET request_fulfilled='$request_fulfilled', request_status = '$request_status' WHERE id=$id");
        header('location: request_repair.php');
    }

    if (isset($_GET['rent'])) {
        $id = $_GET['rent'];
        $update = true;       
        $record = mysqli_query($db, "SELECT * FROM item_inventory WHERE id=$id");

        if (count($record) == 1 ) {
            $n = mysqli_fetch_array($record);
            $item_name = $n['item_name'];
            $item_quantity = $n['item_quantity'];
            $item_tag = $n['item_tag'];
            $item_cost = $n['item_cost'];
            $request_status = "Pending";
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Request Repairs</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/rent_style.css">
    <link rel="stylesheet" type="text/css" href="css/fab.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script>
    function errorField()
    {
        alert("Rented quantity cannot exceed stock!");
    }
    </script>
</head>
<body>
<!-- Top container -->
<div class="w3-bar w3-top w3-black w3-large" style="z-index:4">
  <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i>  Menu</button> <!-- responsive menu -->
  <span class="w3-bar-item w3-margin-left">SHERWIN'S CATERING</span>
  <span class="w3-bar-item w3-right">REQUEST REPAIRS</span>
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
    <a href="dashboard.php" class="w3-bar-item w3-button w3-padding w3-hover-none w3-blue w3-hover-text-blue" style="font-size: 20px; transition-duration: 0.3s;">Dashboard</a>
    <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>
    <h5 class="w3-text-blue w3-padding"><i class="fa fa-list"></i>  Request Repairs</a></h5>
    

  </div>
</nav>
  </div>
<!-- END OF INSERTED CONTAINER -->
<br>
<br>

   
    <?php if (isset($_GET['error'])) { ?> 
        <?php echo '<script> errorField() </script>'; ?>    
    <?php } ?>
    <form method="post" action="request_server.php" >
        
        <?php 
            $results = mysqli_query($db, "SELECT * FROM item_inventory"); 
                
        ?>
        <div class="input-group">
            <label>Item Name</label>
            <select name="item_name".>
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
        <div class="input-group">
            <label>Quantity</label>
            <input type="number" name="requested_quantity" min="0" value="<?php echo $requested_quantity; ?>">
        </div>
        <div class="input-group">
            <button class="btn" type="submit" name="rent" >Rent</button>

        </div>
    </form>

    <?php $results = mysqli_query($db, "SELECT * FROM item_inventory"); ?>
    <table class="newtable1">
        <thead>
            <tr>
            <div class="tags">
                <th>Item Name</th>
                <th>Stock</th>
                <th>Price per Piece</th>
            </div>
            </tr>
        </thead>

        <?php $requestrecord = mysqli_query($db, "SELECT * FROM item_inventory");
            if (mysqli_num_rows($requestrecord) > 0) {
                while($row = mysqli_fetch_array($requestrecord)) { ?>
                    <tr>
                        <td><?php echo $row['item_name']; ?></td>
                        <td><?php echo $row['item_quantity']; ?></td>
                        <td><?php echo $row['rental_cost']; ?></td>
                    </tr>
        <?php
                } 
            } 
        ?>
    </table>
    
    <?php $results = mysqli_query($db, "SELECT * FROM item_request WHERE requested_by='$username'"); ?>
    <table class="newtable3">
        <thead>
            <tr>
            <div class="tags">      
                <th>Item Name</th>
                <th>Rented Quantity</th>
                <th>Total Price</th>
                <th>Rent Date</th>
                <th>Status</th>
                <th>Action</th>
            </div>    
            </tr>
        </thead>    
        <?php while ($row = mysqli_fetch_array($results)) { ?>
            <tr>
                <td><?php echo $row['item_name']; ?></td>
                <td><?php echo $row['requested_quantity']; ?></td>
                <td><?php echo $row['total_cost']; ?></td>
                <td><?php echo $row['request_date']; ?></td>
                <td><?php echo $row['request_status']; ?></td>
                <td>
                    <?php 
                    if ($row['request_status'] == "cancelled" || $row['request_status'] == "rejected") {

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

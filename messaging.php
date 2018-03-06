<?php 
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    $db = mysqli_connect('localhost', 'root', '', 'ppdo_iams');
    include 'include/user_check.php';
    $message_subject="";
    $message_body="";
    $view=0;
    $email= $_SESSION['email'];
    $username=$_SESSION['username'];
    $admin_username="admin";
    if (isset($_GET['view'])) {
      $message_id = $_GET['view'];
      $view=1;
      $unread = 0;
      mysqli_query($db, "UPDATE messaging SET unread='$unread' WHERE id='$message_id'");
    }

?>


<!DOCTYPE html>
<html>
<title>Message Board</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3schools.css">
<link href='https://fonts.googleapis.com/css?family=RobotoDraft' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"><style>
html,body,h1,h2,h3,h4,h5 {font-family: "RobotoDraft", "Roboto", sans-serif}
.w3-bar-block .w3-bar-item {padding: 16px}
</style>
<body>

<!-- Side Navigation -->
!-- Top container -->
<div class="w3-bar w3-top w3-brown w3-large" style="z-index:4"> 
 <span class="w3-bar-item w3-margin-left">SHERWIN'S CATERING</span>
  <span class="w3-bar-item w3-right">MESSAGE BOARD</span>
</div>
<br>
<br>
<nav class="w3-sidebar w3-bar-block w3-collapse w3-white w3-animate-left w3-card-2" style="z-index:3;width:320px;" id="mySidebar">
  <a href="javascript:void(0)" onclick="w3_close()" title="Close Sidemenu" 
  class="w3-bar-item w3-button w3-hide-large w3-large">Close <i class="fa fa-remove"></i></a><!-- responsive menu -->
  <a href="javascript:void(0)" class="w3-bar-item w3-button w3-dark-grey w3-button w3-hover-teal w3-left-align" onclick="document.getElementById('id01').style.display='block'" style="transition-duration: 0.3s;">Message Sherwin <i class="w3-padding fa fa-pencil"></i></a>
  <a href="messaging.php" class="w3-bar-item w3-button w3-hover-indigo"><i class="fa fa-inbox w3-margin-right"></i>Inbox<i class="fa fa-caret-right w3-margin-left"></i></a>

  <a href="messaging_sent.php" class="w3-bar-item w3-button w3-hover-green"><i class="fa fa-paper-plane w3-margin-right"></i>Sent</a>
  <a href="messaging_trash.php" class="w3-bar-item w3-button w3-hover-red"><i class="fa fa-trash w3-margin-right"></i>Trash</a>
  <a href="dashboard.php" class="w3-bar-item w3-button w3-hover-purple"><i class="fa fa-tachometer w3-margin-right"></i>Return to Dashboard</a>
</nav>


<!-- Modal that pops up when you click on "New Message" -->

  <div id="id01" class="w3-modal" style="z-index:4">
    <div class="w3-modal-content w3-animate-zoom">
      <div class="w3-container w3-padding w3-indigo">
         <span onclick="document.getElementById('id01').style.display='none'"
         class="w3-button w3-indigo w3-right w3-xxlarge w3-hover-red" style="transition-duration: 0.3s;"><i class="fa fa-remove"></i></span>
         <form method="post" action="message_server.php">
        <h2>Send Mail</h2>
      </div>

    <form method="post" action="message_server.php" >
      <div class="w3-panel">
        <label>Subject</label>
        <input type="hidden" name="id" value="<?php echo $_SESSION['id']; ?>">
        <input class="w3-input w3-border w3-margin-bottom" type="text" name="message_subject" placeholder="Subject" value="<?php echo $message_subject; ?>">
        <input class="w3-input w3-border w3-margin-bottom" style="height:150px" name="message_body" placeholder="What's on your mind?" value="<?php echo $message_body; ?>">
        <div class="w3-section">
          <a class="w3-button w3-black w3-hover-red" style="transition-duration: 0.3s;" onclick="document.getElementById('id01').style.display='none'">Cancel  <i class="fa fa-remove"></i></a>
          <button class="w3-button w3-light-grey w3-hover-red w3-right" style="transition-duration: 0.3s;" type="submit" name="send_user">Send  <i class="fa fa-paper-plane"></i></button> 
        </div>    
      </div>
      </form>
    </div>
  </div>


<!-- Overlay effect when opening the side navigation on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="Close Sidemenu" id="myOverlay"></div>

<!-- Page content -->
<div class="w3-main" style="margin-left:320px;">
<i class="fa fa-bars w3-button w3-white w3-hide-large w3-xlarge w3-margin-left w3-margin-top" onclick="w3_open()"></i>
<a href="javascript:void(0)" class="w3-hide-large w3-red w3-button w3-right w3-margin-top w3-margin-right" onclick="document.getElementById('id01').style.display='block'"><i class="fa fa-pencil"></i></a>

<?php  
       $retrieve_inbox = mysqli_query($db, "SELECT * FROM messaging WHERE message_recipient='$email' AND user_sender='$admin_username' AND message_inbox=1 AND message_trash=0"); 
    $inbox_count = mysqli_num_rows($retrieve_inbox);
if ($inbox_count!=0){
  if ($view==0) { ?>

 <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">  
      <tr>
        <td style="color: indigo;">Subject</td>
        <td style="color: indigo;">Date and Time Sent</td>
        <td style="color: indigo;">Action</td>
      </tr>
      <tr>
      <?php 
        
        while ($row=mysqli_fetch_array($retrieve_inbox)) {
        if ($row['unread'] == 1) {
          ?>
          <td style="color: red;"><a href="messaging.php?view=<?php echo $row['id']; ?>"><?php echo $row['message_subject']; ?></a></td>
          <td style="color: red;"><a href="messaging.php?view=<?php echo $row['id']; ?>"><?php echo $row['user_sender']; ?></a></td>
          <td style="color: red;"><a href="messaging.php?view=<?php echo $row['id']; ?>"><?php echo $row['date_sent'] ?></a></td>
          <td style="color: red;"><a href="message_server.php?del=<?php echo $row['id']; ?>" class="del_btn"><i class="fa fa-trash-o w3-large w3-hover-text-red" style="transition-duration: 0.3s;"></i></a></td>
        <?php
        }
        else {
          ?>
        <td><a href="messaging.php?view=<?php echo $row['id']; ?>"><?php echo $row['message_subject']; ?></a></td>
        <td><a href="messaging.php?view=<?php echo $row['id']; ?>"><?php echo $row['user_sender']; ?></a></td>
        <td><a href="messaging.php?view=<?php echo $row['id']; ?>"><?php echo $row['date_sent'] ?></a></td>
        <td><a href="message_server.php?del=<?php echo $row['id']; ?>" class="del_btn"><i class="fa fa-trash-o w3-large w3-hover-text-red" style="transition-duration: 0.3s;"></i></a></td>
          <?php
        }
      ?>
      
        

      </tr>
      <?php } ?>
    </th>
  </table>
  <?php 
    }
    else { 
     $retrieve_sent = mysqli_query($db, "SELECT * FROM messaging WHERE message_recipient='$email' AND user_sender='$admin_username' AND message_inbox=1 AND message_trash=0 AND id='$message_id'");
      while ($row=mysqli_fetch_array($retrieve_sent)) {
    ?>
    <br>
    <br>
    <br>
   <h1 class="w3-margin-left w3-large w3-margin-top " style="margin-bottom: 5%;">Subject:<strong style="color: slateblue;"><?php echo $row['message_subject']; ?></strong></h1>
     <p class="w3-margin-left w3-small">Sent on <strong><?php echo $row['date_sent']; ?></strong> by <strong><?php echo $row['user_sender']; ?></strong></p><br><br>
     <p class="w3-margin-left w3-border w3-padding-large w3-black w3-margin-right"><?php echo $row['message_body']; ?></p>
     <a href="javascript:void(0)" class="w3-bar-item w3-button w3-dark-grey w3-button w3-hover-blue w3-margin-left w3-padding-large" onclick="document.getElementById('id01').style.display='block'" style="transition-duration: 0.3s; margin-top: 5%;"><i class="fa fa-reply"></i>&nbsp;&nbsp;Reply</a>
  <?php
    }
  } 
}
else{ ?>
<p>You don't have any messages inside your inbox.</p>
<?php  
} 
?>


     
</div>

<script>
var openInbox = document.getElementById("myBtn");
openInbox.click();

function w3_open() {
    document.getElementById("mySidebar").style.display = "block";
    document.getElementById("myOverlay").style.display = "block";
}
function w3_close() {
    document.getElementById("mySidebar").style.display = "none";
    document.getElementById("myOverlay").style.display = "none";
}

function myFunc(id) {
    var x = document.getElementById(id);
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show"; 
        x.previousElementSibling.className += " w3-red";
    } else { 
        x.className = x.className.replace(" w3-show", "");
        x.previousElementSibling.className = 
        x.previousElementSibling.className.replace(" w3-red", "");
    }
}

openMail("Borge")
function openMail(personName) {
    var i;
    var x = document.getElementsByClassName("person");
    for (i = 0; i < x.length; i++) {
       x[i].style.display = "none";
    }
    x = document.getElementsByClassName("test");
    for (i = 0; i < x.length; i++) {
       x[i].className = x[i].className.replace(" w3-light-grey", "");
    }
    document.getElementById(personName).style.display = "block";
    event.currentTarget.className += " w3-light-grey";
}
</script>

<script>
var openTab = document.getElementById("firstTab");
openTab.click();
</script>

</body>
</html> 

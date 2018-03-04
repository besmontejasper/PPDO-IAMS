<!DOCTYPE html>
<html>
<title>Verification</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<style>
body,h1 {font-family: "Raleway", sans-serif}
body, html {height: 100%}
</style>
<body>
    <!-- start header div --> 
    <div class="bgimg w3-display-container w3-animate-opacity w3-text-red">
    <div class="w3-center">
    <h1 class="w3-jumbo w3-animate-top w3-center">You're almost there!</h1>
    <img src="https://mir-s3-cdn-cf.behance.net/project_modules/disp/6105c734559659.56d59c54f0d05.gif">
    <!-- end header div -->   
     
    <!-- start wrap div -->   
    <div id="wrap">
        <!-- start PHP code -->
        <?php
         
            $db = mysqli_connect('localhost', 'root', '', 'sherwincatering');

                         
            if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
                // Verify data
                $email = mysqli_real_escape_string($db, $_GET['email']); 
                $hash = mysqli_real_escape_string($db, $_GET['hash']);
                
                $search = mysqli_query($db, "SELECT email, hash, verified FROM users WHERE email='$email' AND hash='$hash' AND verified='0'") or die(mysqli_error()); 
                $match  = mysqli_num_rows($search);
                             
                if($match > 0){
                    // We have a match, activate the account
                    mysqli_query($db, "UPDATE users SET verified='1' WHERE email='$email' AND hash='$hash' AND verified='0'") or die(mysqli_error());
                    echo 'Your account is now activated. You can log-in by clicking ';?> <a href="user_login.php">here</a>
                <?php
                }
                else{
                    // No match -> invalid url or account has already been activated.
                    echo 'The URL is either invalid or you already have activated your account.';
                }
                             
            }
            else{
                // Invalid approach
                echo '<div class="statusmsg">Invalid approach! Please use the link that has been sent to your email.</div>';
            }
        ?>
        <!-- stop PHP Code -->
 
         
    </div>
    <!-- end wrap div --> 
    </div>
    </div>
</body>
</html>
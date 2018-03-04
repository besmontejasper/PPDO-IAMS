<?php
		
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;	

			require_once "vendor/autoload.php";

			$mail = new PHPMailer(true);

			// //Enable SMTP debugging. 
			// $mail->SMTPDebug = 3;                               
			//Set PHPMailer to use SMTP.
			$mail->isSMTP();            
			//Set SMTP host name                          
			$mail->Host = "smtp.gmail.com";
			//Set this to true if SMTP host requires authentication to send email
			$mail->SMTPAuth = true;                          
			//Provide username and password     
			$mail->Username = "sherwincatering@gmail.com";                 
			$mail->Password = "Sherw1ncatering";                           
			//If SMTP requires TLS encryption then set it
			$mail->SMTPSecure = "tls";                           
			//Set TCP port to connect to 
			$mail->Port = 587;                                   

			$mail->From = "noreply@sherwincatering.com";
			$mail->FromName = "Sherwin Catering";

			$mail->addAddress("$email1", "$firstname"." "."$lastname");

			$mail->isHTML(true);

			$mail->Subject = "Email Confirmation";
			$mail->Body = ("
			<html>
			<body>
				<p>
				Thanks for signing up! <br>
				Your account has been created with the following credentials, you can login  after you have activated your account by pressing the URL below.<br><br>
				

				------------------------<br>
				First Name: $firstname<br>
				Middle Name: $middlename<br>
				Last Name: $lastname<br>
				Sex: $sex<br>
				Contact No: $contact<br>
				E-mail: $email1<br>
				Lot Number: $lot_number<br>
				Block: $block<br>
				Street Number: $street_number<br>
				Street Name: $street_name<br>
				Subdivision: $subdivision<br>
				Barangay: $barangay<br>
				City: $city<br>
				Username: $username<br>
				------------------------<br><br>

				Please click this link to activate your account:<br><br>

				http://localhost/sherwincatering/verify.php?email=$email1&hash=$hash
				</p>
			</body>
			</html>");


			$mail->SMTPOptions = array(
		    'ssl' => array(
		        'verify_peer' => false,
		        'verify_peer_name' => false,
		        'allow_self_signed' => true
		    )
			);


			if(!$mail->send()) {
			    echo "Mailer Error: " . $mail->ErrorInfo;
			} 
			else {
			    echo "Message has been sent successfully";
			}
			header('location: redirect.php');

?>
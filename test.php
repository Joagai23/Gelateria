<?php
	
	include "sendMail.php";

	$mailer = new Mailer();
	$mailer->sendMail("jaguirregomezcorta@gmail.com", "Mailer test", "the test was a success");

	/*use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;

	require 'PHPMailer-master/src/PHPMailer.php';
	require 'PHPMailer-master/src/SMTP.php';
	require 'PHPMailer-master/src/Exception.php';


			$secretCode = rand(10000,99999);

			
			//Create a new PHPMailer instance
			$mail = new PHPMailer;
			//Tell PHPMailer to use SMTP
			$mail->isSMTP();
			//Disable SMTP debugging
			$mail->SMTPDebug = SMTP::DEBUG_OFF;
			//Set the hostname of the mail server
			$mail->Host = 'smtp.gmail.com';

			//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
			$mail->Port = 587;
			//Set the encryption mechanism to use - STARTTLS or SMTPS
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
			//Whether to use SMTP authentication
			$mail->SMTPAuth = true;
			//Username to use for SMTP authentication - use full email address for gmail
			$mail->Username = 'fakemailpapi@gmail.com';
			//Password to use for SMTP authentication
			$mail->Password = 'fakemailpapi1234';
			//Set who the message is to be sent from
			$mail->setFrom('from@example.com', 'First Last');
			//Set who the message is to be sent to
			$mail->addAddress('jaguirregomezcorta@gmail.com', 'John Doe');
			//Set the subject line
			$mail->Subject = 'PHPMailer GMail SMTP test';
			//convert HTML into a basic plain-text alternative body
			$txtHTML='
				<p>
			    	 Hi , <br>This is a message from the PAI class, your secret code is: '.$secretCode.'.
			    </p>';


			$mail->msgHTML($txtHTML);
			//Replace the plain text body with one created manually
			$mail->AltBody = 'This is a plain-text message body';

			//send the message, check for errors
			if (!$mail->send()) {
			    echo 'Mailer Error: '. $mail->ErrorInfo;
			} else {
			    echo 'Message sent!';
			}

			//$queryInsert= "INSERT INTO users(email,password,confirmed,secretCode) Values ('{$email}','{$pass}','No' ,
					//{$secretCode})";

			//var_dump($queryInsert);
			//$result = $mysqli->query($queryInsert);	


			echo("please check your email and confirm your account");*/
	
?>
<?php
	
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;

	require 'PHPMailer-master/src/PHPMailer.php';
	require 'PHPMailer-master/src/SMTP.php';
	require 'PHPMailer-master/src/Exception.php';

	class Mailer{

		function sendMail($recipientAddress, $subject, $body){
		
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
			$mail->setFrom('from@example.com', 'Gelateria di Giorgio');
			//Set who the message is to be sent to
			$mail->addAddress($recipientAddress, 'John Doe');
			//Set the subject line
			$mail->Subject = $subject;
			//convert HTML into a basic plain-text alternative body
			$txtHTML= $body;

			$mail->msgHTML($txtHTML);

			//send the message, check for errors
			if (!$mail->send()) {
			    echo 'Mailer Error: '. $mail->ErrorInfo;
			}
		}
	}
?>
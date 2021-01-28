<?php
include 'cors_headers.php';
include 'configure_emails.php';

function send_email_campaign()
{
	$recieved_json = json_decode(file_get_contents('php://input'), true);
  	if ($recieved_json == NULL)
		return "Recieved marketing contact json is NULL";
	$email_domain = $recieved_json["email_domain"];
	$email = $recieved_json["email"];
	$email_pass = $recieved_json["email_pass"];
	$email_signature = $recieved_json["email_signature"];
	$email_subject = $recieved_json["email_subject"];
	$email_text = $recieved_json["email_text"];
	$recievers = $recieved_json["recievers"];
	$mail = get_mail_object($email_domain, $email, $email_pass, $email_signature);
	if (is_string($mail)){
		header("Status: 400 Bad Request");
		return $mail;
	}
  
	try {
    	foreach ($recievers as $value){
        	$mail->addBCC($value);
    	}
        $mail->isHTML(true);                                  
        $mail->Subject = $email_subject;
        $mail->Body    = $email_text;
        $mail->AltBody = $email_text;
        $mail->send();
  	} catch (Exception $e) {
		header("Status: 400 Bad Request");
    	echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}
}

echo send_email_campaign();
?>
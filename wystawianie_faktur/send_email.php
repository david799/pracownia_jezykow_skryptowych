<?php
include 'cors_headers.php';
include 'configure_emails.php';

function send_invoice_email($pdf_file, $email_text, $reciever)
{
	$json_path = 'email_config.json';
    if (file_exists(dirname(__FILE__) . $json_path))
        return "Orders JSON does not exist";
    $json = json_decode(file_get_contents($json_path), true);
	$email_domain = $json["email_domain"];
	$email = $json["email"];
	$email_pass = $json["email_pass"];
	$email_signature = $json["email_signature"];
	$email_subject = $json["email_subject"];
	$mail = get_mail_object($email_domain, $email, $email_pass, $email_signature);
	if (is_string($mail)){
		header("Status: 400 Bad Request");
		return $mail;
	}
  
	try {
        $mail->addBCC($reciever);
        $mail->isHTML(true);                                  
        $mail->Subject = $email_subject;
        $mail->Body    = $email_text;
		$mail->AltBody = $email_text;
		$mail->AddAttachment($pdf_file, $name = 'invoice.pdf',  $encoding = 'base64', $type = 'application/pdf');
        $mail->send();
  	} catch (Exception $e) {
		header("Status: 400 Bad Request");
    	echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}
}

?>
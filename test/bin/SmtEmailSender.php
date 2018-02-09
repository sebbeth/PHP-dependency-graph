
<?php

/**
sendEmail
@author Sebastian Brown

A function that uses PHPMailer to send an email from admin@safeministrytraining.com.au
*/
function sendEmail($to, $replyAddress, $replyName, $body, $subject) {

require_once(__DIR__.'/../resources/php/PHPMailer-master/PHPMailerAutoload.php');

	$results_messages = array();

	$mail = new PHPMailer(true);
	$mail->CharSet = 'utf-8';
	ini_set('default_charset', 'UTF-8');

	class phpmailerAppException extends phpmailerException {}

	try {
	if(!PHPMailer::validateAddress($to)) {
	  throw new phpmailerAppException("Email address " . $to . " is invalid -- aborting!");
	}
	$mail->isMail();
	$mail->addReplyTo($replyAddress, $replyName);
	$mail->setFrom("admin@safeministrytraining.com.au", "Safe Ministry Training");
	$mail->addAddress($to, "Recipient");
	$mail->Subject  = $subject;
	$mail->WordWrap = 78;
	$mail->msgHTML($body, dirname(__FILE__), true); //Create message bodies and embed images

	try {
	  $mail->send();
	  $results_messages[] = "Message has been sent using MAIL";
	}
	catch (phpmailerException $e) {
	  throw new phpmailerAppException('Unable to send to: ' . $to. ': '.$e->getMessage());
	}
	}
	catch (phpmailerAppException $e) {
	  $results_messages[] = $e->errorMessage();
	}

	echo "Sent email from admin@safeministrytraining.com.au";
}


?>

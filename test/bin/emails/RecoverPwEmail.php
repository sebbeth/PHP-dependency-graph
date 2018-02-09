<?php 
		$from = "Safe Ministry Training <admin@safeministrytraining.com.au>";
		$subject = "Do you want to update your Safe Ministry Training Password?"; 
		$message = "
<p style='text-align: center;'><img alt='' src='http://online.safeministrytraining.com.au/img/SMTonTrans.png' style='height:165px; margin-bottom:10px; margin-top:10px; width:250px' /></p>

<p>A request has been made to change your www.safeministrytraining.com.au login password.</p>

<p>If you did not make this request, please ingore this email.</p>

<p>If you did request a new loging password, and you want to confirm the change, please click <a href='$NewPwLink'>here</a> (or copy and paste the link below into your browser).<p>

<p>$NewPwLink</p>

<p>If you have any questions about using Safe Ministry Training Online, please let us know,</p>

<p>Cheers,</p>

<p>Dave Moore &amp; the SafeMinistryTraining.com.au Team<br />
info@safeministrytraining.com.au</p>

<p>&nbsp;</p>

";

$headers = 'MIME-Version:1.0' . "\r\n" . 'Content-type: text/html; charset=iso-8859-1' . "\r\n" . "From:" . $from;
    
		
		mail($to,$subject,$message,$headers); 

?>
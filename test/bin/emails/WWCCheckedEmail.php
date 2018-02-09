<?php 

$from = "Safe Ministry Training <admin@safeministrytraining.com.au>";
$subject = "$TraineeName has completed the Safe Ministry Training Online";
$message = "
<p style='text-align:center'><img alt='' src='http://online.safeministrytraining.com.au/img/SMTonTrans.png' style='height:165px; margin-bottom:10px; margin-top:10px; width:250px' /></p>

<p>Dear $AdminName,</p>

<p>This email is to let you know that we have checked the WWCC Status of $TraineeName (DOB: $DOB, WWCC#: $WWCNumber).</p>

<p>The result was: $ResultString</p>

<p>You can view the record of the result page and the WWCC expiry on the <a href='online.safeministrytraining.com.au/login.php'>Safe Ministry Training Admin</a> site.</p>

<p>Thanks for being part of making our churches safe places for everyone.</p>

<p>The Safe Ministry Training Team<br />
<a href='http://www.safeministrytraining.com.au'>www.safeministrytraining.com.au</a></p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>&nbsp;</p>";

$headers = 'MIME-Version:1.0' . "\r\n" . 'Content-type: text/html; charset=iso-8859-1' . "\r\n" . "From:" . $from;

mail($to,$subject,$message,$headers); 

?>







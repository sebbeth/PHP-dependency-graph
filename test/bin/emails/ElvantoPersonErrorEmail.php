<?php 
// $to, $AdminName, $TraineeName, $LastName, $DOB, $Email, $Mobile, $ResultString

$from = "Safe Ministry Training <admin@safeministrytraining.com.au>";
$subject = "There was an error updating the Elvanto record for $TraineeName";
$message = "
<p style='text-align:center'><img alt='' src='http://online.safeministrytraining.com.au/img/SMTonTrans.png' style='height:165px; margin-bottom:10px; margin-top:10px; width:250px' /></p>

<p>Dear $AdminName,</p>

<p>This email is to let you know we could not find an Elvanto record for $TraineeName.</p>

<p>This is the criteria we searched for:<br>
First name: {$Trainee['Firstname']}<br>
Last name: {$Trainee['Lastname']}<br>
Email: {$Trainee['Email']}</p>
<p>We couldn't find an Elvanto record to match those details.<br>
Just to be sure, we also tried:<br>
Last name: {$Trainee['Lastname']}<br>
Birthdate: {$Trainee['DOB']}<br>
Mobile: {$Trainee['Mobile']}</p>

<p>The result Elvanto gave us was: <i>$ResultString<i></p>

<p>We suggest you edit the person's details in Elvanto and in the Safe Ministry Training dashboard so they are the same. (See <a href='https://online.safeministrytraining.com.au/SmtAction-EditTrainee.php?TraineeId=$TraineeId'>$TraineeName</a>)</p>

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


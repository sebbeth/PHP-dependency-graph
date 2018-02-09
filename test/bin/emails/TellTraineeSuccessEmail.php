<?php 
		$from = "Safe Ministry Training <admin@safeministrytraining.com.au>";
		$subject = "Training Completed - Safe Ministry Training Online"; 
		$HtmlExtraReqs = html_entity_decode($ExtraReqs);
		$message = "
<p style='text-align:center'><img alt='' src='http://online.safeministrytraining.com.au/img/SMTonTrans.png' style='height:165px; margin-bottom:10px; margin-top:10px; width:250px' /></p>

<p>Dear $Fname&nbsp;$Lname,</p>

<p>Thankyou for completing the Safe Ministry Training online course. 
This email is to confirm your training and make sure you have a quick access to everything you 
need to report issues and behave appropriately as a leader at&nbsp;$OrgName.</p>

<p>&nbsp;</p>

<p><a href='$CertificateUrl'>Download your Safe Ministry Training Certificate</a></p>

<p>&nbsp;</p>

<h2>Important Quick Reference Details</h2>

<table align='center' border='0' cellpadding='8' cellspacing='0' style='width:100%'>
	<thead>
		<tr>
			<th scope='col' style='background-color:#ffcc33; text-align:left; vertical-align:middle'>The Appointed Safe Ministry Supervisors at&nbsp;$OrgName:</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><em>Please contact these people if you suspect someone has been abused or has made a complaint about a leader. (In an emergency, contact the police immediately).</em></td>
		</tr>
		<tr>
			<td>$SmsList</td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<table align='center' border='0' cellpadding='8' cellspacing='0' style='width:100%'>
	<thead>
		<tr>
			<th scope='col' style='background-color:#ffcc33; text-align:left; vertical-align:middle'>The $OrgName Safe Ministry Policy and Full Training Notes:</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>You can always find the&nbsp;$OrgName Safe Ministry Policy <a href='http://$PolicyUrl'>here</a>:<br />
			<a href='http://$PolicyUrl'>$PolicyUrl</a></td>
		</tr>
		<tr>
			<td>You can always find the&nbsp;$OrgName Child Discipline Policy <a href='http://$DiscPolUrl'>here</a>:<br />
			<a href='http://$DiscPolUrl'>$DiscPolUrl</a></td>
		</tr>
		<tr>
			<td>You can always find a copy of the online training you just did <a href='http://online.safeministrytraining.com.au/fulltrainingnotes.php?tid=$TraineeId&amp;tac=$AuthCode'>here</a>:<br />
			http://online.safeministrytraining.com.au/fulltrainingnotes.php?tid=$TraineeId&amp;tac=$AuthCode</td>
		</tr>
		<tr>
			<td>The additional info for being a leader at $OrgName is:<br />
			$HtmlExtraReqs
			</td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p>&nbsp;</p>

<h2>What happens next?</h2>

<p>There&#39;s a few things that will now happen:</p>

<ol>
	<li>We will email $OrgName to let them know&nbsp;you&#39;ve completed the training.<br />
	&nbsp;</li>
	<li>We&nbsp;will email your two referees ($_1stRefName and $_2ndRefName) to confirm with them that you are suitable to work with vulnerable people.<br />
	&nbsp;</li>
	<li>Your Working With Children Check still needs to clear; $OrgName will let you know when that&#39;s done.<br />
	&nbsp;</li>
	<li>You can print out your <a href='$CertificateUrl'>training certificate</a> to show you&#39;ve completed the training.</li>
</ol>

<p>&nbsp;</p>

<p>Thanks for being part of making our churches safe places for everyone.</p>

<p>The Safe Ministry Training Team<br />
<a href='http://www.safeministrytraining.com.au'>www.safeministrytraining.com.au</a></p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>&nbsp;</p>


";

$headers = 'MIME-Version:1.0' . "\r\n" . 'Content-type: text/html; charset=iso-8859-1' . "\r\n" . "From:" . $from;
    
		
		mail($to,$subject,$message,$headers); 

?>







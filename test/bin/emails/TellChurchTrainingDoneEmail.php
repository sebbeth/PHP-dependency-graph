<?php 
		$from = "Safe Ministry Training <admin@safeministrytraining.com.au>";
		$subject = "$TraineeName has completed the Safe Ministry Training Online"; 
		
		if($Disclose=="No"){
			$DiscloseText = "There were no previous accusations to disclose.";
		}else{
			$DiscloseText = "<span style='background-color:#ffa07a'>There was a previous issue/accusation about $TraineeName</span>";
		}
		
		if($WWC == true){
			if ($WWCCOther != "") { $WWCCOtherText = "(".$WWCCOther.")"; }else{ $WWCCOtherText = ""; }
			$WWCCDetails = "<tr>
			<th scope='row'>State WWCC number</th>
			<td>$WWCCNumber $WWCCOther</td>
			</tr>";
			switch (true){
				case ($OrgState == "NSW"):
				case ($OrgState == "VIC"):
					$NextWWCC = "<li>If you have provided your WWCC Login details, we will automaticaly verify the WWCC Status and Expiry for $TraineeName and let you know when that&#39;s done.<br />&nbsp;</li>";
					break;
				default :
					$NextWWCC = "<li>You will still need to verify that $TraineeName is suitable to work with children under state Working with Children law.<br />&nbsp;</li>";
			}
		}else{$WWCCDetails = ""; }
		
		if($PaymentMethod == "STRIPE"){
			$PayText = "Paid via Credid Card";
		}else{
			$PayText = "A Church Training Credit was used. There are $OrgCredits remaining.";
		}
		
		$message = "
<p style='text-align:center'><img alt='' src='http://online.safeministrytraining.com.au/img/SMTonTrans.png' style='height:165px; margin-bottom:10px; margin-top:10px; width:250px' /></p>

<p>Dear $AdminName,</p>

<p>This email is to let you know that $TraineeName has completed the Safe Ministry Training Course Online.</p>

<table border='0' cellpadding='8' cellspacing='0' style='width:100%'>
	<tbody>
		<tr>
			<th scope='row'>Full Name</th>
			<td>$Fname $Mname $Lname</td>
		</tr>
		<tr>
			<th scope='row'>Email</th>
			<td>$Email</td>
		</tr>
		<tr>
			<th scope='row'>Mobile number</th>
			<td>$Mobile</td>
		</tr>
		<tr>
			<th scope='row'>Date of birth</th>
			<td>$DOB</td>
		</tr>
		<tr>
			<th scope='row'>Will they work with children?</th>
			<td>$WWC</td>
		</tr>
		<tr>
			<th scope='row'>Previous issues to disclose?</th>
			<td>$DiscloseText</td>
		</tr>
		$WWCCDetails
		<tr>
			<th scope='row'>Role (as they described it)</th>
			<td>$Role</td>
		</tr>
		<tr>
			<th scope='row'>Previous experience</th>
			<td>$PrevExp</td>
		</tr>
		<tr>
			<th scope='row'>Referee #1 details</th>
			<td>$_1stRefName (p: $_1stRefMobile, e: $_1stRefEmail)</td>
		</tr>
		<tr>
			<th scope='row'>Referee #1 details</th>
			<td>$_2ndRefName (p: $_2ndRefMobile, e: $_2ndRefEmail)</td>
		</tr>
		<tr>
			<th scope='row'>Training payment method</th>
			<td>$PayText</td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<h2>What happens next?</h2>

<p>There&#39;s a few things that will now happen:</p>

<ol>
	<li>We will email $TraineeName&nbsp;to let them they&#39;ve completed the training.<br />
	&nbsp;</li>
	<li>We&nbsp;will email the two referees ($_1stRefName and $_2ndRefName) to ask them to confirm $TraineeName is suitable to work with vulnerable people.<br />
	If the referees do not respond to the email, you will need to contact them yourself.<br />
	&nbsp;</li>
	$NextWWCC
	<li>You can see the progress of $TraineeName and your other trainees at the Safe Ministry Training Admin site <a href='http://online.safeministrytraining.com.au/SmtAdmin-Main.php'>here</a>.</li>
</ol>

<p>Thanks for being part of making our churches safe places for everyone.</p>

<p>The Safe Ministry Training Team<br />
<a href='http://www.safeministrytraining.com.au'>www.safeministrytraining.com.au</a></p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>&nbsp;</p>


<p>&nbsp;</p>


";

$headers = 'MIME-Version:1.0' . "\r\n" . 'Content-type: text/html; charset=iso-8859-1' . "\r\n" . "From:" . $from;
    
		
		mail($to,$subject,$message,$headers); 

?>







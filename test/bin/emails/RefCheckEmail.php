<?php 
		$from = "Safe Ministry Training <admin@safeministrytraining.com.au>";
		$subject = "Please provide a one-click reference for $TraineeName";
		$message = "<p style='text-align:center;'>
<small>(If you have trouble viewing this email, you can view it online <a href='http://online.safeministrytraining.com.au/RefCheckResponse.php?tid=$TraineeId&tac=$AuthCode&refnum=$RefNumber'>here</a>)</small></p>

<p style='text-align:center'><img title='Safe Ministry Training Online' src='http://online.safeministrytraining.com.au/img/SMTonTrans.png' style='height:165px; margin-bottom:10px; margin-top:10px; width:250px' /></p>

<p>Dear $RefName,</p>
<p>Recently <b>$TraineeName</b> completed the online Safe Ministry Training course at <a href='http://www.safeministrytraining.com.au'>www.safeministrytraining.com.au</a>.
As part of the standard screening process, $TraineeName was asked to provide the name and email of someone who could vouch for their character and 
suitability to do work that may include interacting with vulnerable people.</p>
<p>Can you confirm ALL of the following?</p>
<p style='margin-left: 25px;'>1. I am $RefName</p>
<p style='margin-left: 25px;'>2. I have known $TraineeName for more than 6 months</p>
<p style='margin-left: 25px;'>3. To my knowledge, there have never been any allegations of any sort of wrong-doing or abuse made against $TraineeName.</p>
<p style='margin-left: 25px;'>4. I have no concerns about $TraineeName and believe $TraineeName is of suitable character to work with children and vulnerable adults.</p>
<p style='text-align:center'><a href='http://online.safeministrytraining.com.au/RefCheckResponse.php?tid=$TraineeId&tac=$AuthCode&refnum=$RefNumber&response=Confirmed'>
<img title='Yes. I affirm all the above.' alt='Yes. I affirm all the above.' src='http://online.safeministrytraining.com.au/img/Yes_I_affirm_all_the_above.png'></a><br>
<a href='http://online.safeministrytraining.com.au/RefCheckResponse.php?tid=$TraineeId&tac=$AuthCode&refnum=$RefNumber&response=Confirmed'>Click here to confirm.</a></p>
<p style='text-align:center'><a href='http://online.safeministrytraining.com.au/RefCheckResponse.php?tid=$TraineeId&tac=$AuthCode&refnum=$RefNumber&response=Failed'>
<img title='Not entirely. There are some issues.' alt='Not entirely. There are some issues.' src='http://online.safeministrytraining.com.au/img/Not_entirely_there_are_some_issues.png'></a><br>
<a href='http://online.safeministrytraining.com.au/RefCheckResponse.php?tid=$TraineeId&tac=$AuthCode&refnum=$RefNumber&response=Failed'>Click here if there are some issues.</a></p>
<p>Please select one of the options above. Please be honest. This is an important part of making sure our churches and organisations are safe places for everyone. If you select &quot;Not entirely&quot; a member of their organisation will contact you to elaborate further.</p>
<p>Cheers,</p>
<p>The SafeMinistryTraining.com.au Team<br />
info@safeministrytraining.com.au</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

";

$headers = 'MIME-Version:1.0' . "\r\n" . 'Content-type: text/html; charset=iso-8859-1' . "\r\n" . 'From:' . $from;
		
mail($to,$subject,$message,$headers); 

?>
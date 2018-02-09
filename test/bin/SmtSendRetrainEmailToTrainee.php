<?php




/*
SmtSendRetrainEmailToTrainee.php
@author Sebastian Brown

Module containing functions used to send a retrain link to trainees who's training has expired.

*/


function sendThreeMonthRetrainEmail($to, $Fname, $Lname,$orgName,$orgCode,$traineeID, $usingCredits) {


	// Do nothing

}


function sendTrainingExpiredEmail($to, $replyAddress, $Fname, $Lname,$orgName,$orgCode,$traineeID, $usingCredits) {

	require_once "SmtEmailSender.php";

	$retrainURL = generateRetrainURL($traineeID,$orgCode,$usingCredits);
	echo 'Train '  .$retrainURL;

	$paymentMessage = "";
	if ($usingCredits == 1) {
			$paymentMessage = "Training cost will be covered by $orgName.";
	} else {
			$paymentMessage = "Training will cost $10 please have your credit card details ready";
	}

	$emailBody = "<p>Dear $Fname,</p>";
	$emailBody .= "<p>It's been over three years since you completed your Safe Ministry Training course online, and as such your Safe Ministry Training has now expired. </p>";
	$emailBody .= "<p>Its important that everyone at $orgName does a refresher course every few years to help ensure we keep everyone safe.</p>";
	$emailBody .= "<p>To renew your training, please click on the link below. It will automatically fill in your details and WWCC information. </p>";
	$emailBody .= "<p><a href= $retrainURL>$retrainURL</a><p>";
	$emailBody .= "<p>$paymentMessage</p>";
	$emailBody .= "<p>Regards,</p>";
	$emailBody .= "<p>$orgName<br>$replyAddress</p>";


	// Send the Email

	sendEmail($to, $replyAddress, $orgName, $emailBody, "Time to redo your safe ministry training");





}

function encode($string,$key) {
    /*
     *  A hash encoding function that uses the sha1 hash algorithm
     *  Source: https://gist.github.com/LogIN-/e451ab0e8738138bc60b
     */
    $hash = "";
    $j = 0;
    $key = sha1($key);
    $strLen = strlen($string);
    $keyLen = strlen($key);
    for ($i = 0; $i < $strLen; $i++) {
        $ordStr = ord(substr($string,$i,1));
        if ($j == $keyLen) { $j = 0; }
        $ordKey = ord(substr($key,$j,1));
        $j++;
        $hash .= strrev(base_convert(dechex($ordStr + $ordKey),16,36));
    }
    return $hash;
}



function generateRetrainURL($userToBeRetrainedId, $orgId, $usingChurchCredits ) {

    $address = "http://online.safeministrytraining.com.au/origtraining/Retrain.php";

    $data = $userToBeRetrainedId . "," .$orgId . ",". $usingChurchCredits;
    $encodedData = encode($data,"whysoserious");
    $payload = array("in" => $encodedData);
    return $address . '?' . http_build_query($payload);
}


?>

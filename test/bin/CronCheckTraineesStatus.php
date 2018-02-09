<?php

	/**
	CronCheckTraineesStatus

	@author Sebastian Brown

	A cron job module that was created as a validator for all trainees in database.
	Currently, the only check performed is whether an email asking the trainee to retrain needs to be sent.
	This email is sent once a month until the trainee does their training again.
	*/

	require_once 'dbconfig.php';
	require_once 'SmtSendRetrainEmailToTrainee.php';

	/*
	AddEventLog Function redefined so that SmtHeadInclude does not need to be included.
	 */
	date_default_timezone_set ('Australia/Sydney');
	function AddEventLog ( $id, $EventTxt ) {
		$t = date('Y-m-d G:i:s');
		$EventTxt = mysql_real_escape_string($EventTxt);
		$query = "INSERT INTO EventLog (OrgId, TimeStamp, RecordString) VALUES ('$id','$t','$EventTxt')";
		$data = mysql_query ($query)or die(mysql_error());
	}

	$stopLooking = false;
	// Query database and get all trainees who's orgs are set to automatically send retrain emails
	// ordered by AutomaticRetrainEmailSentAt date, return only the first result.
	$query = "SELECT Trainee.* FROM Trainee LEFT JOIN  Org ON Trainee.OrgID=Org.OrgId
	WHERE Org.AutoRenewTrainingEmail=1
	ORDER BY Trainee.AutomaticRetrainEmailSentAt ASC;";
	$traineesReturnedFromDB = mysql_query($query) or die(mysql_error());


	// Iterate the resuls array looking for a trainee who's training has expired
	while (($trainee = mysql_fetch_assoc($traineesReturnedFromDB)) && ($stopLooking == false)) {

		// If training has expired for trainee
		if(strtotime("now") > strtotime($trainee['DidModule01At']." + 35 months")){


			// If AutomaticRetrainEmailSentAt is greater then one month ago send an email or null
			if( (strtotime("now") > strtotime($trainee['AutomaticRetrainEmailSentAt']." + 1 months")) || (is_null($trainee['AutomaticRetrainEmailSentAt'])) ){


				// Send retrain email

				// Parse PaymentRecord
				$usingCredits = 0;
				if ($trainee['PaymentRecord'] == 'CREDITS') {
					$usingCredits = 1;
				}

				// Grab some data from the Trainee's Org to be included in the email
				$orgID = $trainee['OrgID'];
				$query = "SELECT OrgName, OrgCode, AdminEmail, AutoRenewTrainingEmail FROM Org WHERE OrgID='$orgID' "; // Get the Org data
				$orgIdResults = mysql_query($query) or die(mysql_error());
				$org = mysql_fetch_assoc($orgIdResults);


				// SEND THE EMAIL.
				sendTrainingExpiredEmail($trainee['Email'],
				$org['AdminEmail'],
				$trainee['Firstname'],
				$trainee['Lastname'],
				$org['OrgName'],
				 $org['OrgCode'],
				 $trainee['TraineeID'],
				 $usingCredits);

				// Update AutomaticRetrainEmailSentAt for trainee to now.
				$Now = date("Y-m-d h:i:s");
				$traineeID = $trainee['TraineeID'];
				// Do SQL UPDATE
				// NOTE: In future, this is where Traineestatus and StatusReason should be set.
				$updateQuery = "UPDATE Trainee SET
				AutomaticRetrainEmailSentAt='$Now'
				WHERE TraineeID='$traineeID' ";

				$result = mysql_query($updateQuery) or die(mysql_error()); //Execute the query

				// Log that the retrain email was sent
				AddEventLog($orgID, "Sent trainee " . $trainee['Firstname']. " " . $trainee['Lastname']. " an email asking them to retrain because their training has expired.");
				AddEventLog(1, "Sent trainee " . $trainee['Firstname']. " " . $trainee['Lastname']. " an email asking them to retrain because their training has expired.");



			}


			// We've found our trainee to message so don't email any more this time around. Terminate instead.
			$stopLooking = true;
		}

	}





?>

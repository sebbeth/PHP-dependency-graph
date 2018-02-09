<?php
	/*
	 * Script to check thru the db of trainees
	 * and see if they are approved by their respective state
	 */

	// only run this if a var is set
	if(isset($runthis)!=true) { exit; }else{ echo "Running Trainee Check...\r\n"; }

	// Rather than call the std header functions, just hard code them in
	date_default_timezone_set ('Australia/Sydney');
	require_once __DIR__.'/../dbconfig.php';
	function AddEventLog ( $id, $EventTxt ) {
		$t = date('Y-m-d G:i:s');
		$query = "INSERT INTO EventLog (OrgId, TimeStamp, RecordString) VALUES ('$id','$t','$EventTxt')";
		$data = mysql_query ($query)or die(mysql_error());
	}

	// Get the next Org Id to be checked
	// Find the NSW State Org that was check the longest time ago
	// $OrgId=18;
	// $OrgArray = mysql_query("SELECT * FROM Org ORDER BY LastChecked DESC") or die(mysql_error());
	$CheckCount=0;
	$DidOneCheck = false;
	$TraineeArray = mysql_query("SELECT * FROM `Trainee` WHERE `WWCCStatus` != 'CLEARED' AND `WWCCStatus` != 'FAILED' AND `WWCCStatus` != 'NOTFOUND' ORDER BY `CheckedAtTimestamp`") or die(mysql_error());
	$report = "<br>People found who could be updated:" . mysql_num_rows($TraineeArray) . "<br>"; 
	
	while ( $DidOneCheck == false && $CurrentTrainee = mysql_fetch_assoc($TraineeArray) ){

		$Now = date("Y-m-d h:i:s");

		// Grab the Org Details
		$OrgId = $CurrentTrainee['OrgID'];
		$OrgArray = mysql_query("SELECT * FROM Org WHERE OrgId='$OrgId'") or die(mysql_error());
		$OrgArray = mysql_fetch_assoc($OrgArray);
		$OrgState = $OrgArray['OrgState'];
		$OrgName = $OrgArray['OrgName'];
		$AdminName = $OrgArray['AdminName'];
		$AdminEmail = $OrgArray['AdminEmail'];
		$nswUsername = $OrgArray['WwccUsername'];
		$nswPassword = $OrgArray['WwccPassword'];

		// Grab the Trainee Details
		$TraineeId = $CurrentTrainee['TraineeID'];
		$Fname = $CurrentTrainee['Firstname'];
		$Mname = $CurrentTrainee['Middlename'];
		$Lname = $CurrentTrainee['Lastname'];
		$Prevname = $CurrentTrainee['Prevname'];
		$WorksWithChildren = $CurrentTrainee['WorksWithChildren'];
		$WWCStatus = $CurrentTrainee['WWCCStatus'];
		$WWCNumber = $CurrentTrainee['WWCCNumber'];
		$WWCExpiry = $CurrentTrainee['WWCCExpiry'];
		$LastChecked = $CurrentTrainee['CheckedAtTimestamp'];
		$DOB = $CurrentTrainee['DOB'];
		if ( strtotime("now") < (strtotime($DOB . " +18 years"))){
			$Under18 = true;
		}else{
			$Under18 = false;
		}
		$DOB = date("d/m/Y",strtotime($CurrentTrainee['DOB']));

		// Record the time of the check against the Trainee
		// This stops the same trainee being checked over and over again
		$SqlResult = mysql_query("UPDATE Trainee SET CheckedAtTimestamp='$Now' WHERE OrgID='$OrgId' AND TraineeID='$TraineeId'") or die(mysql_error());
		//$SqlResult = mysql_query("UPDATE Org SET LastChecked='$Now' WHERE OrgId='$OrgId'") or die(mysql_error());
		$CheckCount += 1;
		$report .= "<br>\r\nInitiate check $CheckCount with TraineeId: $TraineeId [$Fname, $Lname, $WWCNumber, $DOB, $OrgName ($OrgState), last checked at: $LastChecked]: ";

		// If they DON'T NEED A CHECK - echo and escape
		switch (true){
			case ( $Under18 ) :
				$report .= "Trainee is under 18 years old.";
				continue 2;
			case ($WorksWithChildren == false) :
				$report .= "Trainee does not work with children.";
				continue 2;
			case ($WWCNumber == "") :
				$report .= "Trainee does not have a WWCC Number.";
				continue 2;
			case ($WWCStatus == "CLEARED") :
				$report .= "Trainee has already been checked and marked CLEARED";
				continue 2;
			case ($WWCStatus == "FAILED") :
				$report .= "Trainee has already been checked and marked FAILED";
				continue 2;
			default:
		}

		// reset vars
		$WwccResultStatus = $WwccResultPage = $WwccResultExpiry = "";
		$NewExpiry = "NULL";

		// Do check depending on STATE
		switch ($OrgState){
			case "" :

				$report .= "No Church State defined.";
				continue 2;

			case "NSW":
				if ($nswUsername == "" or $nswPassword == "") {
					$report .= "Church does not have Username and/or Password.";
					continue 2;
				}

				// Run the NswWwcc Class file
				require_once 'NswWwcc.php';
				$MyTurn = new NswCheckerInterface();
				$WwccResultPage = $MyTurn->NswCheckTrainee($nswUsername, $nswPassword, $Lname, $DOB, $WWCNumber);
				$WwccResultStatus = $MyTurn->NswParseStatus($WwccResultPage);
				$WwccResultExpiry = $MyTurn->NswParseExpiry($WwccResultPage);

				// If the status is NOT "CLEARED" and the trainee has a Previous Name (Maiden name)... try again
				if ($WwccResultStatus != "CLEARED" and $Prevname != "" ) {

					// Run the NswWwcc Class file with the Prevname
					require_once 'NswWwcc.php';
					$MyTurn = new NswCheckerInterface();
					$WwccResultPage = $MyTurn->NswCheckTrainee($nswUsername, $nswPassword, $Prevname, $DOB, $WWCNumber);
					$WwccResultStatus = $MyTurn->NswParseStatus($WwccResultPage);
					$WwccResultExpiry = $MyTurn->NswParseExpiry($WwccResultPage);

					// Add the PrevName to the LastName field for reporting
					$Lname = "$Lname (nee $Prevname)";

				}

				// Edit the html to have absolute link refs
				$WwccResultPage = str_replace('="/Content', '="https://wwccheck.ccyp.nsw.gov.au/Content', $WwccResultPage);

				$DidOneCheck = true;

				break;

			case "VIC":

				require_once 'VicWwcc.php';
				$MyTurn = new VicCheckerInterface();
				$WwccResultPage = $MyTurn->VicCheckTrainee( $Lname, $WWCNumber);
				$WwccResultStatus = $MyTurn->VicParseStatus($WwccResultPage);
				$WwccResultExpiry = $MyTurn->VicParseExpiry($WwccResultPage);

				// If the status is NOT "CLEARED" and the trainee has a Previous Name (Maiden name)... try again
				if ($WwccResultStatus != "CLEARED" and $Prevname != "" ) {


					require_once 'VicWwcc.php';
					$MyTurn = new VicCheckerInterface();
					$WwccResultPage = $MyTurn->VicCheckTrainee( $Prevname, $WWCNumber);
					$WwccResultStatus = $MyTurn->VicParseStatus($WwccResultPage);
					$WwccResultExpiry = $MyTurn->VicParseExpiry($WwccResultPage);
					$Lname = "$Lname (nee $Prevname)"; // Add the PrevName to the LastName field for reporting

				}
				$DidOneCheck = true;

				break;

			case "QLD":

			require_once 'QldWwcc.php';
				$MyTurn = new QldCheckerInterface();
				$WwccResultPage = $MyTurn->QldCheckTrainee($Fname, $Mname, $Lname, $WWCNumber, $WWCExpiry);
				$WwccResultStatus = $MyTurn->QldParseStatus($WwccResultPage);
				// If the status is NOT "CLEARED" and the trainee has a Previous Name (Maiden name)... try again
				if ($WwccResultStatus != "CLEARED" and $Prevname != "" ) {

				 	require_once 'QldWwcc.php';
					$MyTurn = new QldCheckerInterface();
					$WwccResultPage = $MyTurn->QldCheckTrainee($Fname, $Mname, $Lname, $WWCNumber, $WWCExpiry);
					$WwccResultStatus = $MyTurn->QldParseStatus($WwccResultPage);
					$Lname = "$Lname (nee $Prevname)";  // Add the PrevName to the LastName field for reporting
				}

				$WwccResultExpiry = $WWCExpiry;
				$DidOneCheck = true;

				break;
			
			case "TAS":

			require_once 'TasWwcc.php';
				$MyTurn = new TasCheckerInterface();
				$WwccResultPage = $MyTurn->TasCheckTrainee($Lname, $WWCNumber);
				$WwccResultStatus = $MyTurn->TasParseStatus($WwccResultPage);
				$WwccResultExpiry = $MyTurn->TasParseExpiry($WwccResultPage);

				// If the status is NOT "CLEARED" and the trainee has a Previous Name (Maiden name)... try again
				if ($WwccResultStatus != "CLEARED" and $Prevname != "" ) {

					$MyTurn = new TasCheckerInterface();
					$WwccResultPage = $MyTurn->TasCheckTrainee($Lname, $WWCNumber);
					$WwccResultStatus = $MyTurn->TasParseStatus($WwccResultPage);
					$WwccResultExpiry = $MyTurn->TasParseExpiry($WwccResultPage);
					$Lname = "$Lname (nee $Prevname)";  // Add the PrevName to the LastName field for reporting
				}

				$WwccResultExpiry = $WWCExpiry;
				$DidOneCheck = true;

				break;


			case "WA":

				require_once 'WaWwcc.php';
				$MyTurn = new WaCheckerInterface();
				$WwccResultPage = $MyTurn->WaCheckTrainee($Lname, $WWCNumber);
				$WwccResultStatus = $MyTurn->WaParseStatus($WwccResultPage);

				// If the status is NOT "CLEARED" and the trainee has a Previous Name (Maiden name)... try again
				if ($WwccResultStatus != "CLEARED" and $Prevname != "" ) {

				 	require_once 'WaWwcc.php';
					$MyTurn = new WaCheckerInterface();
					$WwccResultPage = $MyTurn->WaCheckTrainee($Prevname, $WWCNumber);
					$WwccResultStatus = $MyTurn->WaParseStatus($WwccResultPage);
					$Lname = "$Lname (nee $Prevname)";  // Add the PrevName to the LastName field for reporting
				}

				$DidOneCheck = true;

				break;

			default:

				$report .= "Other Church. Do Nothing.";
				continue 2;

				// Do nothing
		}

		$NewExpiry = "'$WwccResultExpiry'";
		if ($OrgState != "QLD") {
			if ( $WwccResultStatus == "FAILED" or $WwccResultStatus == "NOTFOUND") { $NewExpiry = "NULL"; }
		}
		// If there was a Satus found in the HTML...
		if ($WwccResultStatus != ""){

			// Get the dateStamp to record in the DB
			$WwccResultPage = str_replace('<body>', '<body>Result Page Saved at:'.$Now, $WwccResultPage);
			$FileContent = $WwccResultPage;

			// Save the HMTL Result
			$filename=__DIR__."/ResultPages/WwccResult-$OrgId-$TraineeId.html";
			file_put_contents( $filename, $FileContent);

			// Insert Results in DB
			$query = 	"UPDATE Trainee SET WWCCStatus='$WwccResultStatus', WWCCExpiry=$NewExpiry, ElvantoUpdated='0000-00-00' WHERE OrgID='$OrgId' AND TraineeID='$TraineeId'";
			$TraineeResult = mysql_query($query) or die(mysql_error());

			AddEventLog( $OrgId, "The WWC Status of $Fname $Lname (id: $TraineeId, $WWCNumber, $DOB) was checked online. The result was $WwccResultStatus.");
			$report .=  " -> Result: $WwccResultStatus (Expires: $NewExpiry)";

			// Send Email Update to Church
			$TraineeName = "$Fname $Lname";
			$to = $AdminEmail;
			$ResultString = "<b>$WwccResultStatus</b>";
			if ($WwccResultStatus == "CLEARED") $ResultString .= " Expires:<b>$WwccResultExpiry</b>";
			require_once __DIR__.'/../emails/WWCCheckedEmail.php';

			// echo $report
			
			// Run the overall check for the status of this trainee
			$CronCheckTraineeOverallStatus=['TraineeId'=>$TraineeId, 'OrgId'=>$OrgId];
			
			echo "<br>".__DIR__."/../SmtCheckTrainees.php ";
			require_once __DIR__."/../SmtCheckTrainees.php";

		}else{

			// No Status was found in the HTML - So there must have been some type of error
			AddEventLog( $OrgId, "The WWC Status of $Fname $Lname (id: $TraineeId, $WWCNumber, $DOB) was checked online. There was an error trying to retrieve the result.");
			$report .=  " -> No result could be found, there could be an issue with the WWCC server.";
			// echo $report;

		}
		if($runthis == true) { echo $report; }

	}


?>

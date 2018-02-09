<?php 
	
	exit; 
	
	date_default_timezone_set ('Australia/Sydney');
	
	function AddEventLog ( $id, $EventTxt ) {
		$t = date('Y-m-d G:i:s');
		$query = "INSERT INTO EventLog (OrgId, TimeStamp, RecordString) VALUES ('$id','$t','$EventTxt')"; 
		$data = mysql_query ($query)or die(mysql_error());
	}
	
	// require_once __DIR__.'/../SmtHeadInclude.php'; 
	require_once __DIR__.'/../dbconfig.php';
	
	// Get the next Org Id to be checked
	// Find the NSW State Org that was check the longest time ago
	//$OrgId=18;
	$OrgArray = mysql_query("SELECT * FROM `Org` WHERE `OrgState` = 'NSW' ORDER BY `LastChecked` DESC") or die(mysql_error());
	$DidOneCheck = false;
	
	while ($DidOneCheck == false && $CurrentOrg = mysql_fetch_assoc($OrgArray)){
		$OrgId = $CurrentOrg['OrgId'];
		$OrgName = $CurrentOrg['OrgName'];
		$nswUsername = $CurrentOrg['WwccUsername'];
		$nswPassword = $CurrentOrg['WwccPassword'];
		echo "\r\n$OrgName (OrgId: $OrgId) ";
		
		if ($nswUsername == "" or $nswPassword == "") {
			echo " does not have Username and Password";
			break;
		}
		// echo "[" . $nswUsername ." (" . $nswPassword . ")]<br>";
		
		$TraineeArray = mysql_query("SELECT * FROM `Trainee` WHERE `OrgID` = '$OrgId' ORDER BY `CheckedAtTimestamp`") or die(mysql_error());
		while ( $DidOneCheck == false && $CurrentTrainee = mysql_fetch_assoc($TraineeArray) ){
			$Now = date("Y-m-d h:i:s");
			$TraineeId = $CurrentTrainee['TraineeID'];
			$Fname = $CurrentTrainee['Firstname'];
			$Lname = $CurrentTrainee['Lastname'];
			$WorksWithChildren = $CurrentTrainee['WorksWithChildren'];
			$WWCStatus = $CurrentTrainee['WWCCStatus'];
			$WWCNumber = $CurrentTrainee['WWCCNumber'];
			$DOB = $CurrentTrainee['DOB'];
			if ( strtotime("now") < (strtotime($DOB . " +18 years"))){
				$Under18 = true;
			}else{
				$Under18 = false;
			}
			$DOB = date("d/m/Y",strtotime($CurrentTrainee['DOB']));
			
			echo "\r\n - $Fname $Lname (TraineeId: $TraineeId) ";
			
			// Record the time of the check against the Trainee
			$SqlResult = mysql_query("UPDATE Trainee SET CheckedAtTimestamp='$Now' WHERE OrgID='$OrgId' AND TraineeID='$TraineeId'") or die(mysql_error());
					
			switch (true){
				case ( $Under18 ) :
					echo "is under 18 years old.";
					break;
				case ($WorksWithChildren == false) :
					echo "does not work with children.";
					break;
				case ($WWCNumber == "") :
					echo "does not have a WWCC Number.";
					break;
				case ($WWCStatus == "CLEARED") :
					echo "Already checked and marked CLEARED";
					break;
				case ($WWCStatus == "FAILED") :
					echo "Already checked and marked FAILED";
					break;
				default:
				
				echo " - Initiate check with [$Lname, $WWCNumber, $DOB]...\r\n";
				
				// Do check
				// Run the NswWwcc Class file  
				require_once 'NswWwcc.php';
				$MyTurn = new CheckerInterface();
				$WwccResultPage = $MyTurn->NswCheckTrainee($nswUsername, $nswPassword, $Lname, $DOB, $WWCNumber);
				$WwccResultStatus = $MyTurn->ParseStatus($WwccResultPage);
				$WwccResultExpiry = $MyTurn->ParseExpiry($WwccResultPage);
				$NewExpiry = "'".date('Y-m-d', strtotime(str_replace('/', '-', $WwccResultExpiry)))."'";
				if ( $WwccResultStatus == "FAILED") { $NewExpiry = "NULL"; }
				
				if ($WwccResultStatus != ""){
					// If there was a Satus found in the HTML...
					
					// Get the dateStamp to record in the DB
					$WwccResultPage = str_replace('<body>', '<body>Result Page Saved at:'.$Now, $WwccResultPage);
					$WwccResultPage = str_replace('="/Content', '="https://wwccheck.ccyp.nsw.gov.au/Content', $WwccResultPage);
					$FileContent = $WwccResultPage;
					
					// Save the HMTL Result
					$filename=__DIR__."/ResultPages/WwccResult-$OrgId-$TraineeId.html";
					file_put_contents( $filename, $FileContent);
					
					// Insert Results in DB
					// Update CheckedAtTimestamp to now
					$query = 	"UPDATE Trainee SET WWCCStatus='$WwccResultStatus', WWCCExpiry=$NewExpiry WHERE OrgID='$OrgId' AND TraineeID='$TraineeId'";
					$TraineeResult = mysql_query($query) or die(mysql_error());
					$query = 	"UPDATE Org SET LastChecked='$Now' WHERE OrgID='$OrgId'";
					$TraineeResult = mysql_query($query) or die(mysql_error());
					
					AddEventLog( $OrgId, "The WWC Status of $Fname $Lname (id: $TraineeId, $WWCNumber, $DOB) was checked online. The result was $WwccResultStatus.");
					echo " -> $Now Result: $WwccResultStatus (Expires: $NewExpiry)";
					
				}else{
					
					// No Status was found in the HTML - So there must have been some type of error
					AddEventLog( $OrgId, "The WWC Status of $Fname $Lname (id: $TraineeId, $WWCNumber, $DOB) was checked online. There was an error trying to retrieve the result.");
					echo " -> $Now No result could be found, there could be an issue with the WWCC server.";
					
				}
				$DidOneCheck = true;
				
				// End SELECT>DEFAULT
				
			}
			
		}
		
	}
	

?>
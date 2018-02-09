<?php 
	// session_start();

	require_once 'dbconfig.php';
	$AddToSearch = "";
	
	// Check that this code is meant to be run...
   switch (true){
      case (isset($_SESSION['SessionOrgId'])):
         // There is a valid session, get the Org Id
         require_once 'SmtHeadInclude.php'; 
         $OrgId = $_SESSION['SessionOrgId'];
         $AddToSearch="";
         // Check if there's only one Trainee to check
         if ( $CheckTrainee != "" ){
            $TraineeId = $CheckTrainee;
            $AddToSearch = " AND TraineeID = '$CheckTrainee'";
            error_log( __FILE__ ." I'm only going to check one trainee. $CheckTrainee ");
         }
         break;
      case (isset($CronCheckTraineeOverallStatus)):
         // There is a cron job running and it has updated a user and needs to re-run the overall check
         echo "This is running out of a crond job... Org:{$CronCheckTraineeOverallStatus['OrgId']}  Trainee:{$CronCheckTraineeOverallStatus['TraineeId']}";
         $OrgId = $CronCheckTraineeOverallStatus['OrgId'];
         $TraineeId = $CronCheckTraineeOverallStatus['TraineeId'];
         $AddToSearch = " AND TraineeID = '$CheckTrainee'";
         break;
      default:
         /// There is no good reason this should be running!!
         header("location:login.php?message=InvalidUser");
         exit;
   }

	$sqlQueryString = "SELECT * FROM Trainee WHERE OrgId='$OrgId'$AddToSearch";
	$EventArray = mysql_query($sqlQueryString) or die(mysql_error());
	
	while ($LineData = mysql_fetch_assoc($EventArray)) {
		
		$NewStatus = "Failed";
		$NewStatusReason = "Reason: ";
		$TraineeId = $LineData['TraineeID'];
		$NeedsToRedo = "";
		$RefsChk="Verified";   // Assume they're ok until something says they're not
		
		// Each trainee needs a verified Disclouser Statement
		// 'No','YesButOk','YesButOk-Unverified','YesNotYet'
		$Disclose = $LineData['Disclose'];
		switch ($Disclose){
			case "YesButOk-Unverified":
				// If they've disclosed something but it hasn'r been checked, they're "pending"
				$RefsChk = "Pending";  
				$NewStatusReason .= "Unverified disclosure of historical incident. ";
				break;
			case "YesNotYet": 
				// If they've been accused and haven't told someone they're "failed"
				$RefsChk = "Failed";  
				$NewStatusReason .= "Disclosure of historical incident. ";
				break;
			case "No":
			case "YesButOk":
			default :
				// $RefsChk = ""; 
				break;  // If they are ok, or their discluser has been checked, leave blank for Reference Checks below
		}
		
		// Every Trainee Needs two Confirmed References (if required)
		if ( $_SESSION['ReqsTraineesRefs'] == true ) {
			
			// Each Trainee needs refs
			$Refs=$LineData['1stRefStatus'].$LineData['2ndRefStatus'];
			switch(true){							// If current, apply the lowest common denominator between RefChk and WwccChk
					
				case preg_match("/failed/i",$Refs):
					$RefsChk = "Failed";
					$NewStatusReason .= "Referee indicated issues. "; 
					break;
					
				case preg_match("/pending/i",$Refs):
					$RefsChk = "Pending";
					$NewStatusReason .= "Referee has not responded. ";
					break;
					
				default:

			}
		}
		
		// Training must be completed within 3 years
		if(strtotime("now") > strtotime($LineData['DidModule01At']." +42 months")){ 
			$TrainingChk = false; 
			$NewStatusReason .= "More than 3.5 years since training completed. ";
			$NeedsToRedo = true;
			
		}else{
			$TrainingChk = true;
			
			// Flag issue if Training will expire within 3 months (35 months since orig training)
			if(strtotime("now") > strtotime($LineData['DidModule01At']." +35 months")){
				$NeedsToRedo = true;
				$NewStatusReason .= "Training will expire soon.. ";
			}
		}
		
		
		
		if ( $_SESSION['ReqsTraineesWWCC'] == true ) {
			
			// WWCC status must not be "failed"
			switch($LineData['WWCCStatus']){
				case "CLEARED": 
					$WwccChk = "Verified"; 
					// BUT... WWCC must not be expired
					// if(strtotime("now") > strtotime($LineData['WWCCExpiry'])){ $WwccChk = "Failed"; }
					break;
				case "PENDING": 
					$WwccChk = "Pending";
					$NewStatusReason .= "WWCC has not been verified yet. ";
					break;
				case "NOTFOUND": 
				case "":
					$WwccChk = "Pending";
					$NewStatusReason .= "WWCC has not been verified yet. ";
					break;
				case "FAILED": 
					$WwccChk = "Failed";
					$NewStatusReason .= "WWCC verification problem. ";
					break;
			}
		}else{
			// The org doesn't require WWCC Checking...
			$WwccChk = "Verified";
		}
		
		// Other Checks
		if ($LineData['WorksWithChildren']=='false') { $WwKids = false; }else{ $WwKids=true; }
		if (strtotime("now") > (strtotime($LineData['DOB']." +18 years"))) { $Over18 = true; }else{ $Over18 = false; }
		
		
		
		if (!$Over18){								// if UNDER 18...
			if($TrainingChk == true){						// Check if training is current...
				$NewStatus = $RefsChk;					// Apply Refs chk
			}else{
				$NewStatus = 'Failed';					// Or they fail
			}
		}
		
		if ($Over18 && !$WwKids){					// if OVER 18 and doesn't work with kids...
			if($TrainingChk == true){								// Check if training is current...
				$NewStatus = $RefsChk;					// Apply Refs chk
			}else{
				$NewStatus = 'Failed';					// Or they fail
			}
		}
		
		if ($Over18 && $WwKids) {					// If OVER 18 and DOES work with kids
			if($TrainingChk == true){									// Check if training is current...
				switch(true){											// If current, apply the lowest common denominator between RefChk and WwccChk
					case preg_match("/failed/i",$RefsChk.$WwccChk):
						$NewStatus = "Failed"; break;
					case preg_match("/pending/i",$RefsChk.$WwccChk):
						$NewStatus = "Pending"; break;
					default :
						$NewStatus = "Verified"; break;
				}
			}else{
				$NewStatus = 'Failed';						// If their training is not current... they fail
			}
		}
		
		// Apply final Training Expire Check
		if ( $NewStatus == "Verified" and $NeedsToRedo == true) {
			$NewStatus = "Pending";
		}
		
		if($NewStatus != $LineData['TraineeStatus'] or $NewStatusReason != $LineData['StatusReason']) {
			$sqlquery = "UPDATE Trainee SET 
							TraineeStatus = '$NewStatus', 
							StatusReason = '$NewStatusReason', 
							ElvantoUpdated='0000-00-00'
						WHERE OrgID='$OrgId' AND TraineeID='$TraineeId'";
			$result = mysql_query($sqlquery) or die(mysql_error());
		}
		
	}
?>
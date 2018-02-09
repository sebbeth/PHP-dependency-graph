<?php 

	// To ensure that all other functions have run BEFORE it tried to update Elvanto...
	sleep(10);

	// Rather than call the std header functions, just hard code them in
	date_default_timezone_set ('Australia/Sydney');
	require_once __DIR__.'/dbconfig.php';
	require_once __DIR__.'/../vendor/Elvanto_API.php';
	function AddEventLog ( $id, $EventTxt ) {
		$t = date('Y-m-d G:i:s');
		$query = "INSERT INTO EventLog (OrgId, TimeStamp, RecordString) VALUES ('$id','$t','$EventTxt')"; 
		$data = mysql_query ($query)or die(mysql_error());
	}
	
	// Should the file create an Echo log
	$EchoFlag = false;
	$UpdateElvantoReport = "Displaying Status updates from " . __FILE__ ; 
	
	// Set the Counter var 
	$AttemptTally = 0;
	$SuccessfulTally = 0;
	

	// Go through all the Trainees in order of the last time they were updated (Org.OrgId)  ( and Trainee.OrgID = 20 )
	// $TraineeArray = mysql_query("SELECT * FROM Trainee WHERE ElvantoUpdated IS NULL or ElvantoUpdated < DATE_SUB( NOW( ) , INTERVAL 5 DAY ) ORDER BY ElvantoUpdated ASC LIMIT 1000") or die(mysql_error());
   $TraineeArray = mysql_query("SELECT DISTINCT TraineeID, Trainee.OrgID, Firstname, Middlename, Lastname, Prevname, Email, Mobile, DOB, TraineeStatus, WWCCNumber, WWCCExpiry, WWCCStatus, CheckedAtTimestamp, DidModule01At, ElvantoUpdated, ElvantoPersonId 
                              FROM Trainee CROSS JOIN Org ON Trainee.OrgID = Org.OrgId 
                              WHERE Org.ElvantoApiKey <> '' AND (Trainee.ElvantoUpdated IS NULL or Trainee.ElvantoUpdated < DATE_SUB( NOW( ) , INTERVAL 31 DAY ))
                              ORDER BY Trainee.ElvantoUpdated ASC")
               or die(mysql_error());
	
	$UpdateElvantoReport .= "<br>People found who could be updated:" . mysql_num_rows($TraineeArray) . "<br>"; 
	if (mysql_num_rows($TraineeArray) > 0){ $EchoFlag = true; }
	
	while ( $Trainee = mysql_fetch_assoc($TraineeArray) AND $SuccessfulTally < 2 ) {
		
		$SendErrorEmail = false;
		$AttemptTally = $AttemptTally + 1;
		$TraineeName = "{$Trainee['Firstname']} {$Trainee['Lastname']}";
		$TraineeId = $Trainee['TraineeID'];
		$ThisOrg = mysql_fetch_assoc(mysql_query("SELECT * FROM Org WHERE OrgID='{$Trainee['OrgID']}'")) or die(mysql_error());
		// Output
		$UpdateElvantoReport .= "<br>(Attempt $AttemptTally Completed: $SuccessfulTally).";
		$UpdateElvantoReport .= "<br>Looking for $TraineeName of {$ThisOrg['OrgName']} - Saved ElvantoId: {$Trainee['TraineeID']}";
		
		// Check if Org doesn't have elvanto things
		if ($ThisOrg['ElvantoSmtStatusId'] . $ThisOrg['ElvantoWWCCStatusId'] . $ThisOrg['ElvantoWWCCId'] . $ThisOrg['ElvantoWWCCExpId'] . $ThisOrg['ElvantoSmtDateId'] == ''){
			$UpdateElvantoReport .= "({$ThisOrg['OrgName']} has no Elvanto details).";
			continue;			// skip to the next person
		}
	   
		// setup the API Connection
		$auth_details = array('api_key' => $ThisOrg['ElvantoApiKey']);
		$elvanto = new Elvanto_API($auth_details);
		
		// Does the Trainee have an Elvanto Person Id
		$TraineeElvantoId =  $Trainee['ElvantoPersonId'];
		if ( $TraineeElvantoId == "" ) {
		   
		   // ** FIND the Trainee In Elvanto ** //
         // Make the Elvanto Search API call  ## try first name, last name and email first ##
         $SearchArray = ['firstname'=>$Trainee['Firstname'], 'lastname'=>$Trainee['Lastname'],'email'=>$Trainee['Email']];
         $results = $elvanto->call('people/search', ['search'=>$SearchArray]);
         
         // Check if a result was returned Failed
         if ( $results->status != 'ok' ) {
         	$UpdateElvantoReport .=  $results->error->message . "<br><br>Hmmm... tricky. We couldn't find someone in Elvanto with these details; {$Trainee['Firstname']}, {$Trainee['Lastname']}, {$Trainee['Email']}.<br>Trying with other details... ";
         	// Try with Lastname, DOB and mobile
         	$SearchArray = ['lastname'=>$Trainee['Lastname'], 'birthday'=>$Trainee['DOB'], 'mobile'=>$Trainee['Mobile']];
         	$results = $elvanto->call('people/search', ['search'=>$SearchArray]);
         	
         	// Check if the result failed again...
         	if ( $results->status != 'ok' ) {
         		$UpdateElvantoReport .=  "<br>".$results->error->message . "<br>...and we couldn't find someone in Elvanto with these details; {$Trainee['Lastname']}, {$Trainee['DOB']}, {$Trainee['Mobile']}. Sorry!<br>You should check that the details for this person match in Elvanto.";
         		
               // send email to CHURCH Admin with error
               // $to, $AdminName, $TraineeName, $LastName, $DOB, $Email, $Mobile, $ResultString
               $to = $ThisOrg['AdminEmail'];
               $AdminName = $ThisOrg['AdminName'];
               $ResultString = $results->error->message;
               // send the Admin an error msg
               require_once __DIR__.'/emails/ElvantoPersonErrorEmail.php';
               
               //echo "Elvanto Error mail sent to $AdminName ({$ThisOrg['OrgName']}) regarding $TraineeName.";
               
               // record the updated Elvanto attempt (so it doesn't try it again)
               $Now = date("Y-m-d h:i:s");  
               $ResultArray = mysql_query("UPDATE Trainee SET ElvantoUpdated='$Now', ElvantoPersonId=NULL WHERE TraineeID='{$Trainee['TraineeID']}'") or die(mysql_error());
               
               // skip to the next person
               continue;		
               
         	}
         }
         
         // If a valid match has been found, grab the persons's Elvanto ID
			$TraineeElvantoId = $results->people->person[0]->id;
			$UpdateElvantoReport .= "<br>$TraineeName found in Elvanto! ElvantoId: $TraineeElvantoId";
			
			// Save Elvanto Person Id to Database
			mysql_query("UPDATE Trainee SET ElvantoPersonId='$TraineeElvantoId' WHERE TraineeID='{$Trainee['TraineeID']}'") or die(mysql_error());
		   
		}
		
		// Get the Elvanto Person Details using the Elvanto Person Id
		// Include the extra Custom Fields in the GetInfo Call
		// (but ONLY if they exist)
		$GetElvantoFields = ['birthday'];
		if ($ThisOrg['ElvantoSmtStatusId'] != '') { array_push( $GetElvantoFields, "custom_".$ThisOrg['ElvantoSmtStatusId'] ); }
		if ($ThisOrg['ElvantoWWCCStatusId'] != '') { array_push( $GetElvantoFields, "custom_".$ThisOrg['ElvantoWWCCStatusId'] ); }
		if ($ThisOrg['ElvantoWWCCId'] != '') { array_push( $GetElvantoFields, "custom_".$ThisOrg['ElvantoWWCCId'] ); }
		if ($ThisOrg['ElvantoWWCCExpId'] != '') { array_push( $GetElvantoFields, "custom_".$ThisOrg['ElvantoWWCCExpId'] ); }
		if ($ThisOrg['ElvantoSmtDateId'] != '') { array_push( $GetElvantoFields, "custom_".$ThisOrg['ElvantoSmtDateId'] ); }
		
		$UpdateElvanto = false;
		$results = $elvanto->call('people/getInfo', ['id'=>$TraineeElvantoId, 'fields'=>$GetElvantoFields]);
		
      if ( $results->status != 'ok' ) {
         
         // Despite everything, the ElvantoId doesn't seem to have returned a result.
         // Chances are Elvanto has changed the person's ElvantoId, so we should delete it
         echo $UpdateElvantoReport;
         echo "<br>The Elvanto result:";
         print_r($results,true);
         echo "<br>$TraineeName could not be found in Elvanto! Clearing Recorded ElvantoId: $TraineeElvantoId <br>";
         // CLEAR Elvanto ID in SMT db
         $Now = date("Y-m-d h:i:s");  
         $ResultArray = mysql_query("UPDATE Trainee SET ElvantoUpdated='$Now', ElvantoPersonId='' WHERE TraineeID='{$Trainee['TraineeID']}'") or die(mysql_error());
         
		}else{
		   
         // Person has been found
         // Compare the Elvanto and SMT fields
         $Eperson = json_decode(json_encode($results->person[0]),true);         // Turns the Elvanto Results into a PHP Array
         $UpdateElvantoReport .= "<br>Elvanto Record:". $results->status . "!<br>";
         
         // Create the Edit Person Array (with the PersonID) and the 'FIELDS' array to use in the call
         $CallArray = ['id'=>$TraineeElvantoId,'fields'=>[]];
         
         // SMT Status - Check if different and add to update array if it needs it
         $CustomId = "custom_".$ThisOrg['ElvantoSmtStatusId'];
         if ( $Eperson[$CustomId] != $Trainee['TraineeStatus'] AND $ThisOrg['ElvantoSmtStatusId'] != '') { 
            $UpdateElvantoReport .= "<br>Smt Status does not match (Elvto:".$Eperson[$CustomId].", SMT:{$Trainee['TraineeStatus']})"; 
            $CallArray['fields'][$CustomId]=$Trainee['TraineeStatus'];
            $UpdateElvanto = true; 
         }
         
         // WWCC Status - Check if different and add to update array if it needs it
         $CustomId = "custom_".$ThisOrg['ElvantoWWCCStatusId'];
         if ( $Eperson[$CustomId]['name'] != $Trainee['WWCCStatus'] AND $ThisOrg['ElvantoWWCCStatusId'] != '') { 
            $UpdateElvantoReport .= "<br>WWCC Status does not match (Elvto:".$Eperson[$CustomId]['name'].", SMT:{$Trainee['WWCCStatus']})"; 
            $CallArray['fields'][$CustomId]=$Trainee['WWCCStatus'];
            $UpdateElvanto = true; 
         }
         
         // SMT WWCCNumber - Check if different and add to update array if it needs it
         $CustomId = "custom_".$ThisOrg['ElvantoWWCCId'];
         if ( $Eperson[$CustomId] != $Trainee['WWCCNumber'] AND $ThisOrg['ElvantoWWCCId'] != '') { 
            $UpdateElvantoReport .= "<br>WWCCNumber does not match (Elvto:".$Eperson[$CustomId].", SMT:{$Trainee['WWCCNumber']})"; 
            $CallArray['fields'][$CustomId]=$Trainee['WWCCNumber'];
            $UpdateElvanto = true; 
         }
         
         // SMT WWCCExpiry - Check if different and add to update array if it needs it
         $CustomId = "custom_".$ThisOrg['ElvantoWWCCExpId'];
         if ( $Eperson[$CustomId] != $Trainee['WWCCExpiry'] AND $ThisOrg['ElvantoWWCCExpId'] != '') { 
            $UpdateElvantoReport .= "<br>WWCCExp does not match (Elvto:".$Eperson[$CustomId].", SMT:{$Trainee['WWCCExpiry']})"; 
            $CallArray['fields'][$CustomId]=$Trainee['WWCCExpiry'];
            $UpdateElvanto = true; 
         }
         
         // SMT DidModule01At - Check if different and add to update array if it needs it
         $CustomId = "custom_".$ThisOrg['ElvantoSmtDateId'];
         if ( $Eperson[$CustomId] != date("Y-m-d",strtotime($Trainee['DidModule01At'])) AND $ThisOrg['ElvantoSmtDateId'] != '') { 
            $UpdateElvantoReport .= "<br>SMT Trainign Completed Date does not match (Elvto:".$Eperson[$CustomId].", SMT:".date("Y-m-d",strtotime($Trainee['DidModule01At'])).")";
            $CallArray['fields'][$CustomId] = date("Y-m-d",strtotime($Trainee['DidModule01At']));
            $UpdateElvanto = true; 
         }
          
		}
         
      // If No fields are different, don't update any fields in Elvanto
      if ($UpdateElvanto == false) { 
         $UpdateElvantoReport .= "<br>All fields match between Elvanto and SMT.";
      }
      
      // If ANY fields are different, run the Elvnato Api Call to update those fields in Elvanto
      if ($UpdateElvanto == true) { 
         
         // ** UPDATE the Trainee In Elvanto ** //
         
         // Run the Edit Person API call
         $results = $elvanto->call('people/edit', $CallArray);
         
         // Echo the result
         if ($results->status == "ok"){ 
            // TODO Add Event Log
            AddEventLog( $Trainee['OrgID'] , "The Elvanto record for {$Trainee['Firstname']} {$Trainee['Lastname']} have been updated. (Overall Status:{$Trainee['TraineeStatus']} WWCC Number:{$Trainee['WWCCNumber']} WWCC Status:{$Trainee['WWCCStatus']} WWCC Expiry:{$Trainee['WWCCExpiry']} Date Completed Training:".date("Y-m-d",strtotime($Trainee['DidModule01At'])).")");
         	$UpdateElvantoReport .= "<br>Elvanto Person updated successfully<br><br>"; 
         	$SuccessfulTally = $SuccessfulTally + 1;
         }else{ 
         	$UpdateElvantoReport .= "Edit Person Error - " . $results->error->message . "<br>CallArray that was used:".print_r($CallArray,true);  
         	echo $UpdateElvantoReport;
         	$SuccessfulTally = $SuccessfulTally + 1;
         }
         
      }
      
      // Record that the Trainee was checked/Updated
      $Now = date("Y-m-d h:i:s");  
      $ResultArray = mysql_query("UPDATE Trainee SET ElvantoUpdated='$Now' WHERE TraineeID='{$Trainee['TraineeID']}'") or die(mysql_error());
      
      $UpdateElvantoReport .= "<br>Elvanto Person Update Complete<br><hr><br>";
      
		continue;
		  
	}
	
	if ($EchoFlag == true) { echo $UpdateElvantoReport; }
	 
?>
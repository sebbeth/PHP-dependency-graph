<?php  
	ob_start();
	date_default_timezone_set ('Australia/Sydney');
	
	function stripinput($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  //$data = mysql_real_escape_string($data);
	  return $data; 
	}
	
	function AddEventLog ( $id, $EventTxt ) {
		$t = date('Y-m-d G:i:s');
		$EventTxt = mysql_real_escape_string($EventTxt);
		$query = "INSERT INTO EventLog (OrgId, TimeStamp, RecordString) VALUES ('$id','$t','$EventTxt')"; 
		$data = mysql_query ($query)or die(mysql_error());
	}
	
	function WorldStandard( $string ) {
		$string = preg_replace("/[\/]/", "-", $string);
		return $string;
	}
	
	function GetIfTrue ( $GetVar ) {
		
		if( isset($_GET[$GetVar]) == true) {
			return $_GET[$GetVar];
		}
		
	}

?>


<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
<script src="http://online.safeministrytraining.com.au/assets/jquery-1.10.2.js"></script> -->


<!-- Style Sheets
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />  <!-- assets/bootstrap.css "http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>



<link href="https://online.safeministrytraining.com.au/assets/custom.css" rel="stylesheet" />
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

<!-- Scripts - I should load these in the footer to reduce load time -->


<script src="https://use.fontawesome.com/71dbcafc07.js"></script>
<script src="https://online.safeministrytraining.com.au/assets/jquery.metisMenu.js"></script>
<script src="https://online.safeministrytraining.com.au/assets/custom.js"></script>



<?php


$nswUsername = 'hbckids';
$nswPassword = '!Godis3';
$Lname = 'Pain';
$DOB = date("d/m/Y",strtotime('1992-11-21'));
$WWCNumber = 'WWC0047995E';

if ($nswUsername == "" or $nswPassword == "") {
	$report .= "Church does not have Username and/or Password.";
	continue 2;
}

// Run the NswWwcc Class file
require_once 'bin/WWCC/NswWwcc.php';
$MyTurn = new NswCheckerInterface();
$WwccResultPage = $MyTurn->NswCheckTrainee($nswUsername, $nswPassword, $Lname, $DOB, $WWCNumber);
$WwccResultStatus = $MyTurn->NswParseStatus($WwccResultPage);
$WwccResultExpiry = $MyTurn->NswParseExpiry($WwccResultPage);

echo "The Status is:". $WwccResultStatus ."<br><br>";
echo $WwccResultPage;

	// WA wwcc ("2567049" " Meyer")


	// QLD data


	/*
	$Fname = "Dorothy"; $Mname = "Louraine"; $Lname = "Shanks";
	$cardnum = "904282/3";
	$expdate = "2019-09-27";
	*/
/*
		$Fname = "Esther"; $Mname = " "; $Lname = "Ng";
	$cardnum = "1305352/2";
	$expdate = "2020-08-02";


		/*
		$Fname = "Dorothy"; $Mname = "Louraine"; $Lname = "Shanks";
		$cardnum = "904282/3";
		$expdate = "2019-09-27";
		*/

			$Fname = "Esther"; $Mname = " "; $Lname = "Ng";
		$cardnum = "1305352/2";
		$expdate = "2020-08-02";




		require_once 'bin/WWCC/QldWwcc.php';
		$MyTurn = new QldCheckerInterface();
		$WwccResultPage = $MyTurn->QldCheckTrainee($Fname, $Mname, $Lname, $cardnum, $expdate);
		$WwccResultStatus = $MyTurn->QldParseStatus($WwccResultPage);
		//$WwccResultExpiry = $MyTurn->QldParseExpiry($WwccResultPage);

		echo "$WwccResultStatus<br>$WwccResultPage";

/*
	//TAS Test
/*
	$cardnum = "641596267"; //641596267
	$Lname = "Niebuur";


	require_once 'bin/WWCC/TasWwcc.php';
	$MyTurn = new TasCheckerInterface();
	$WwccResultPage = $MyTurn->TasCheckTrainee($Lname, $cardnum);
	$WwccResultStatus = $MyTurn->TasParseStatus($WwccResultPage);
	$WwccResultExpiry = $MyTurn->TasParseExpiry($WwccResultPage);

	echo "$WwccResultStatus<br>$WwccResultPage";
*/


*/
	/*
	//include autoloader
	$path = __DIR__.'/vendor/autoload.php';
	if (is_file($path)) {
		require_once $path;
	} else {
		echo $path;
		exit('<br>Please run `composer install` in your app directory.');
	}

	use phpQuery;
	use GuzzleHttp\Client;  // as HttpClient;

	// Use a specific cookie jar
	$client = new Client([
		// Base URI is used with relative requests
		'base_uri' => 'https://www.bluecard.qld.gov.au',
		// You can set any number of default request options.
		'timeout'  => 2.0,
		'cookies' => true
	]);

	$response = $client->get('https://www.bluecard.qld.gov.au/onlinevalidation/validation.aspx');

	if (preg_match('#<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="(.*)" />#', $response->getBody(), $matches) !== 1) {
		// No Code found
		echo "No Code found";
	}else{
		$Code01 = $matches[1];
		echo "<br>Code:" . $Code01;
	}

	if (preg_match('#type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="(.*)" />#', $response->getBody(), $matches) !== 1) {
		// No Code found
		echo "No Code found";
	}else{
		$Code02 = $matches[1];
		echo "<br>Code:" . $Code02;
	}

	$response = $client->post($url, [
				'cookies' => true,
				'body' => [
					'__EVENTTARGET'         => '',
					'__EVENTARGUMENT'       => '',
					'__VIEWSTATE'           => $Code01,    // $this->QldFetchTokenViewState($url),  // $tokens['__VIEWSTATE'],
					'opentx'				=> 'rw',
					'__VIEWSTATEGENERATOR'	=> 'E490CF8E',
					'__EVENTVALIDATION'     => $Code02,  // $tokens['__EVENTVALIDATION'],
					'FullName'              => $namecode,
					'CardNumber'            => $cardnumstart,  // substr($details['cardNum'], 0, 6), //from 123456/7 it takes 123456
					'IssueNumber'           => $cardnumend,    // substr($details['cardNum'], 7, 1), //from 123456/7 it takes 7
					'ExpiryDate$selDay'     => $daycode,   // $details['expiryDate']->format('j'),
					'ExpiryDate$selMonth'   => $monthcode,   // $details['expiryDate']->format('n'),
					'ExpiryDate$selYear'    => $yearcode,   // $details['expiryDate']->format('Y'),
					'ValidateCardBtn'       => 'Validate Card'
				],
			]);

	echo "<br><br>".$response;









	/*
	$client = new GuzzleHttp\Client(	[
											//'base_url' => 'https://www.bluecard.qld.gov.au',
											//'defaults' => ['allow_redirects' => false],
											'cookies' => true
										]
									);
	echo "Created client<br>";

	$response = $client->request('GET', 'http://httpbin.org/cookies');  //'http://www.bluecard.qld.gov.au/onlinevalidation/validation.aspx');

	echo "Body" . $response->getBody();


	exit;

	/*
	$QldClient = 	new client([
						'base_url' => 'https://www.bluecard.qld.gov.au',
						'defaults' => ['allow_redirects' => false]
					]);

	$url = '/onlinevalidation/validation.aspx';

	QldFetchTokenViewState($url){
			//create the HTTP session
			error_log ("before doing token get 01 ($url)");
			try{
				$response = $this->client->get($url, [
					'cookies' => true,
				]);
			}
			catch(Exception $e) {
				echo 'Message: ' .$e->getMessage();
			}

			error_log ("after doing token get 01 ($url)");
			if (preg_match('#<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="(.*)" />#', $response->getBody(), $matches) !== 1) {
				return [];   // throw new Exception(get_class($this), [], 'Unable to fetch CSRF token.');
			}

	// NSW Test

	$DOB = date("d/m/Y",strtotime('1995-12-25'));


	$user ='hbckids';
	$pwd = '!Godis3';

	require_once 'bin/WWCC/NswWwcc.php';
	$MyTurn = new NswCheckerInterface();
	$WwccResultPage = $MyTurn->NswCheckTrainee($user, $pwd,'Brown',$DOB,'WWC0490833V');
	$WwccResultStatus = $MyTurn->NswParseStatus($WwccResultPage);
	$WwccResultExpiry = $MyTurn->NswParseExpiry($WwccResultPage);

	echo "$WwccResultStatus<br>$WwccResultExpiry<br>$WwccResultPage";


?>

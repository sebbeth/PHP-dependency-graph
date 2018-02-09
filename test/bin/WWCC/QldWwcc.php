<?php

	//include autoloader
	$path = __DIR__.'/../../vendor/autoload.php';
	if (is_file($path)) {
		require_once $path;
	} else {
		echo $path;
		exit('Please run `composer install` in your app directory.');
	}

	use \phpQuery;
	use GuzzleHttp\Client;  // as HttpClient;


	class QldCheckerInterface {

		private $client;
		private $authenticated;

		function __construct() {
			//create the HTTP client
			error_log ("before new client");

			$this->client = new client([
				'base_url' => 'https://www.bluecard.qld.gov.au',
				'defaults' => ['allow_redirects' => false]
			]);
		}

		/**
		 * Uses https://www.bluecard.qld.gov.au/onlinevalidation/validation.aspx to lookup the given individual's Blue Card.
		 * Returns the webpage produced.
		 *
		 * @param   string namecode
		 * @param   string cardnum
		 * @param   string expiryDate
		 * @return string
		 */
		function QldCheckTrainee( $Fname, $Mname, $Lname, $cardnum, $expiryDate) {

			// Tease out the parts of the card num
			$cardnumComponents = explode("/",$cardnum);
			$cardnumstart = $cardnumComponents[0];
			$cardnumend = $cardnumComponents[1];
			// Concatenate trainee name

			// Check to see if the middle name is set. If it is included it in the name

			if ((ctype_space($Mname)) || (empty($Mname))){

				$namecode = $Fname . ' ' . $Lname;
			} else {
				$namecode = $Fname . ' ' . $Mname . ' ' . $Lname;
			}

			$daycode = date('j', strtotime($expiryDate));
			$monthcode = date('n', strtotime($expiryDate));
			$yearcode = date('Y', strtotime($expiryDate));

			$url = '/onlinevalidation/validation.aspx';

			 $tokens = $this->QldFetchToken($url);


			$response = $this->client->post($url, [
				'cookies' => true,
				'body' => [
					'__EVENTTARGET'         => '',
					'__EVENTARGUMENT'       => '',
					'__VIEWSTATE'           => $tokens['__VIEWSTATE'],    // $this->QldFetchTokenViewState($url),  // $tokens['__VIEWSTATE'],
					'opentx'				=> 'rw',
					'__VIEWSTATEGENERATOR'	=> 'E490CF8E',
					'__EVENTVALIDATION'     => $tokens['__EVENTVALIDATION'],  // $tokens['__EVENTVALIDATION'],
					'FullName'              => $namecode,
					'CardNumber'            => $cardnumstart,  // substr($details['cardNum'], 0, 6), //from 123456/7 it takes 123456
					'IssueNumber'           => $cardnumend,    // substr($details['cardNum'], 7, 1), //from 123456/7 it takes 7
					'ExpiryDate$selDay'     => $daycode,   // $details['expiryDate']->format('j'),
					'ExpiryDate$selMonth'   => $monthcode,   // $details['expiryDate']->format('n'),
					'ExpiryDate$selYear'    => $yearcode,   // $details['expiryDate']->format('Y'),
					'ValidateCardBtn'       => 'Validate Card'
				],
			]);

			$document = $response->getBody();
			if($response->getStatusCode()!=200) {
				$document='NOTFOUND';
			}

			// Now, fix the links to CSS files
			$document = str_replace('<link href="/c/main.min.css"','<link href="https://www.bluecard.qld.gov.au/c/main.min.css"',$document);

			$document = str_replace('<link href="/c/print.css"','<link href="https://www.bluecard.qld.gov.au/c/print.css"',$document);

			return $document;
		}


		function QldParseStatus($content){

			switch (true) {
				case (preg_match('/\d{6}\/\d is a valid card/', $content) == 1) :
					return "CLEARED";
					break;
				default:
					return "NOTFOUND";
					break;
			}
		}


		/**
		 * Fetches the request verification token used by the server to prevent CSRF
		 * @param   string $url
		 * @returns array
		 * @throws  Exception
		 */
		function QldFetchToken($url) {
			//create the HTTP session
			$response = $this->client->get($url, [
				'cookies' => true
			]);

			//check the response was successful
			if ($response->getStatusCode() != 200) {
				return [];   // throw new Exception(get_class($this), [], 'Unable to fetch CSRF token.');
			}

			$result = [];

			//extract the request verification token
			if (preg_match('#<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="(.*)" />#', $response->getBody(), $matches) !== 1) {
				return [];   // throw new Exception(get_class($this), [], 'Unable to fetch CSRF token.');
			}

			$result['__VIEWSTATE'] = $matches[1];

			if (preg_match('#<input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="(.*)" />#', $response->getBody(), $matches) !== 1) {
				return [];   // throw new Exception(get_class($this), [], 'Unable to fetch CSRF token.');
			}

			$result['__EVENTVALIDATION'] = $matches[1];
			return $result;
		}

		/*

		function QldFetchTokenViewState($url){
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
			error_log ("output " . $matches[1]);
			return $matches[1];
		}

		function QldFetchTokenEventVal($url){
			//create the HTTP session
			error_log ("before doing token get 02 ($url)");
			$response = $this->client->get($url, [
				'cookies' => true,
			]);
			if (preg_match('#<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="(.*)" />#', $response->getBody(), $matches) !== 1) {
				return [];   // throw new Exception(get_class($this), [], 'Unable to fetch CSRF token.');
			}
			return $matches[1];
		}
		*/




	}

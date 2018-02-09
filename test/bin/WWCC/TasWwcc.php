<?php

	//include autoloader
	$path = __DIR__.'/../../vendor/autoload.php';
	if (is_file($path)) {
		require_once $path;
	} else {
		echo $path;
		exit('Please run `composer install` in your app directory.');
	}

	use GuzzleHttp\Client;

	class TasCheckerInterface {

		private $client;

		function __construct() {
			//create the HTTP client
			error_log ("before new client");

			$this->client = new client(["defaults" => array(
	                    "allow_redirects" => false
	        )]);
		}

		/**
		 * A function fills the form found at https://workingwithchildren.wa.gov.au/card-validation with
		 * an individual's last name and WWC card number. The contents of the page are returned as a string
		 *
		 * @param string lastName
		 * @param string cardnum
		 *
		 * @returns string
		 */
		function TasCheckTrainee( $lastName, $cardnum) {

			$url = 'https://wwcforms.justice.tas.gov.au/RegistrationSearch.aspx';

			/* The HTTP client needs to use fake User-Agent data to not be blocked by the
			site's security software. The value of $userAgentData is used to make the server
			think a real person is trying to access the page not a robot. Yew! Hacking!
			*/
			$userAgentData = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36';

			// Get the tokens required for the post request.
			$tokens = $this->TasFetchToken($url);

			$response = $this->client->post($url, [
				'cookies' => true,
				'headers' => ['User-Agent' => $userAgentData],
				'body' => [
					'__EVENTTARGET'         => '',
					'__EVENTARGUMENT'       => '',
					'__VIEWSTATE'           => $tokens['__VIEWSTATE'],

					'opentx'				=> 'rw',
					'__EVENTVALIDATION'     => $tokens['__EVENTVALIDATION'],
					'__LASTFOCUS'           => '',
					'ctl00$ctl00$RefreshTimeout'     => '2390',
					'ctl00$ctl00$RefreshDestination'   => 'Logout.aspx',
					'ctl00$ctl00$IsProgressBarEnabled'    => 'true',
					'ctl00$ctl00$hidFormUniqueSessionKey'    => $tokens['session_key'],
					'ctl00$ctl00$scrollLeft'    => '0',
					'ctl00$ctl00$scrollTop'    => '0',
					'ctl00$ctl00$ctlMainContent$MainContent$txtCardNumber'    => $cardnum,
					'ctl00$ctl00$ctlMainContent$MainContent$txtSurname'    => $lastName,
					'__ASYNCPOST'    => 'true',
					'ctl00$ctl00$ctlMainContent$MainContent$btnSearch'       => 'Search'
				],
			]);

			$document = $response->getBody();
			if($response->getStatusCode()!=200) {
				$document='NOTFOUND';
			}

			// Now, fix the links to CSS files
			$document = '<link href="https://wwcforms.justice.tas.gov.au/css/Styles.css" rel="stylesheet" type="text/css" /><link href="https://wwcforms.justice.tas.gov.au/css/bsol.css" rel="stylesheet" type="text/css" />' . $document;



			return $document;
		}



		function TasParseStatus($content){

			if (preg_match('/Registered To/', $content) == 1) {
				return "CLEARED";

			} else {
				return "NOTFOUND";


			}
		}

		function TasParseExpiry($content){

			//TODO This needs completing

			//preg_match('/\d{2}\/\d{2}\/\d{4}/',$content,$matches,PREG_SET_ORDER, 0);

			//extract the result
			$document   = phpQuery::newDocumentHTML($content)->getDocument();
			$cells      = $document->find('table td');

			//check the cells are found
			if ($cells->count() < 15) {
				return NULL;
			}
			$expiry = $cells->get(15)->textContent;
			return $expiry;
			//$expiry = str_replace('/', '-', $expiry);
			//return date('Y-m-d', strtotime($expiry));
		}

		/**
		 * Fetches the request verification token used by the server to prevent CSRF
		 * @param   string $url
		 * @returns array
		 * @throws  Exception
		 */
		function TasFetchToken($url) {
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

			// Get a session key
			if (preg_match('#<input type="hidden" name="ctl00$ctl00$hidFormUniqueSessionKey" id="ctl00_ctl00_hidFormUniqueSessionKey" value="(.*)" />#', $response->getBody(), $matches) == true) {
				return [];   // throw new Exception(get_class($this), [], 'Unable to fetch CSRF token.');
			}

			$result['session_key'] = $matches[1];


			if (preg_match('#<input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="(.*)" />#', $response->getBody(), $matches) !== 1) {
				return [];   // throw new Exception(get_class($this), [], 'Unable to fetch CSRF token.');
			}

			$result['__EVENTVALIDATION'] = $matches[1];




			return $result;
		}

	}

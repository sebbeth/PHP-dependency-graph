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

	class WaCheckerInterface {

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
		function WaCheckTrainee( $lastName, $cardnum) {

			$url = 'https://workingwithchildren.wa.gov.au/card-validation';

			/* The HTTP client needs to use fake User-Agent data to not be blocked by the
			site's security software. The value of $userAgentData is used to make the server
			think a real person is trying to access the page not a robot. Yew! Hacking!
			*/
			$userAgentData = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36';

			$response = $this->client->post($url,['headers' => ['User-Agent' => $userAgentData],

			'body' => [
				'ctl00_ctl00_ctlMainContent_MainContent_btnSearch' => 'Search'
				]

			]);

			$document = $response->getBody();
			if($response->getStatusCode()!=200) {
				$document='NOTFOUND';
			}

			// Now, fix the links to CSS files
			$document = str_replace('<link href="/Telerik.Web.UI.WebResource.axd?',
			'<link href="https://workingwithchildren.wa.gov.au/Telerik.Web.UI.WebResource.axd?',$document);

			$document = str_replace('<link href="/Sitefinity/WebsiteTemplates/',
			'<link href="https://workingwithchildren.wa.gov.au/Sitefinity/WebsiteTemplates/',$document);


			return $document;
		}



		function WaParseStatus($content){

			switch (true) {
				case (preg_match('/Card number \d+ for/', $content) == 1) :
					return "CLEARED";
					break;
				default:
					return "NOTFOUND";
					break;
			}
		}
	}

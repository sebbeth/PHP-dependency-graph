<?php

	//include autoloader
	$path = __DIR__.'/../../vendor/autoload.php';
	if (is_file($path)) {
		require_once $path;
	} else {
		echo $path;
		exit('Please run `composer install` in your app directory.');
	}
	
	use phpQuery;
	use GuzzleHttp\Client;  // as HttpClient;


	class VicCheckerInterface {

		private $client;
		private $authenticated;

		function __construct() {

			//create the HTTP client
			$this->client = new client([
				'base_url' => 'https://online.justice.vic.gov.au',
				'defaults' => ['allow_redirects' => false]
			]);

		}

		function VicCheckTrainee( $Lastname, $VicWwccNumber ) {

			$response = $this->client->post('/wwccu/checkstatus.doj', [
				'cookies' => true,
				'body' => [
					'viewSequence'  => '1',
					'language'      => 'en',
					'cardnumber'    => substr($VicWwccNumber, 0, 8), //from 12345678-90 it takes 12345678
					'lastname'      => $Lastname,        // $details['lastname'],
					'pageAction'    => 'Submit',
					'Submit'        => 'submit',
					'Cancel'        => 'cancel'
				],
			]);
			
			$document = $response->getBody();
			if($response->getStatusCode()!=200) { $document='NOT FOUND'; }
			return $document;
		}
		
		
		function VicParseStatus($content){
			
			switch (true) {
				case (preg_match('/This person may engage in child related work/', $content) == 1) :
					return "CLEARED";
					break;
				case (preg_match('/card number combination do not match/', $content) == 1):
					return "FAILED";
					break;
				case ($content == 'NOT FOUND'):
					return "NOT FOUND";
					break;
			}
		}
		
		function VicParseExpiry($content){    //, array $details = []) {

			//extract the result
			$getdate = preg_match('#This person may engage in child related work and their card expires on (\d{2} [a-zA-Z]{3} \d{4}).#', $content, $results);
			if($getdate==1){
				return date('Y-m-d', strtotime($results[1]));
			}else{
				return null;
			}
			
			//$document   = phpQuery::newDocumentHTML($content)->getDocument();
			//$cells      = $document->find('This person may engage in child related');
			//check the cells are found
			//if ($cells->count() < 3) {
				//return new Result(Result::STATUS_UNKNOWN, (string) $document);
				//return '';
			//}
			//$expiry = $cells->get(3)->textContent;
			//return $expiry;
		}

	}
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

	// echo "($nswUsername, $nswPassword, $Lname, $DOB, $WWCNumber) ";

	/**	 * NSW WWCC	 */
	class NswCheckerInterface {

		private $username;
		private $password;
		private $client;
		private $authenticated;
		
		function __construct() {
			//create the HTTP client
			$this->client = new client([
				'base_url' => 'https://wwccheck.ccyp.nsw.gov.au',
				'defaults' => ['allow_redirects' => false]
			]);
		}

		function NswCheckTrainee($nswUsername, $nswPassword, $Lname, $DOB, $WWCNumber) {

			
			/*$familyName = 'Moore';
			$dateOfBirth = '29/06/1978';
			//$authorisationOrApplicationNumber = 'WWC0003209E';
			$WWCNumber = 'WWC0003209E';*/

			$this->authenticate($nswUsername, $nswPassword);

			$url = '/Employers/Search';
			$response = $this->client->post($url, [
				'cookies'                           => true,
				'body' => [
					'__RequestVerificationToken'        => $this->fetchToken($url),
					'Criteria[0].FamilyName'            => strtoupper($Lname),
					'Criteria[0].BirthDate'             => $DOB,
					'Criteria[0].AuthorisationNumber'   => $WWCNumber, //substr($authorisationOrApplicationNumber, 0, 3) === 'WWC' ? strtoupper($authorisationOrApplicationNumber) : '', //TODO: figure out which number has been passed
					//'Criteria[0].ApplicationNumber'     => substr($authorisationOrApplicationNumber, 0, 3) !== 'WWC' ? strtoupper($authorisationOrApplicationNumber) : '',
					'ActionCommand'                     => 'Verify',
				],
			]);
			
			/*/check the response was successful
			if ($response->getStatusCode() != 200 || preg_match('#Please fix the following errors#', (string) $response->getBody()) === 1) {
				throw new Exception(get_class($this), $details, 'Check failed.');
			} */

			return $response->getBody();   //$this->parse((string) $response->getBody());
		}
		
		function authenticate($nswUsername, $nswPassword) {
			//check if we're already authenticated
			if ($this->authenticated) { return $this; }

			//authenticate
			$url = '/Employers/Login';
			$response = $this->client->post($url, [
				'cookies'                           => true,
				'body' => [
					'__RequestVerificationToken'    => $this->fetchToken($url),
					'Username'                      => $nswUsername,
					'Password'                      => $nswPassword,
					'Login'                         => 'Login',
				],
			]);

			//check the response was successful
			if ($response->getStatusCode() != 302 || preg_match('#Login#', (string) $response->getBody()) === 1) {
				//throw new Exception(get_class($this), [], 'Authentication failed.');
				$this->authenticated = false;
			}else{
				//remember we're authenticated
				$this->authenticated = true;
			}
			return $this;
			
		}

		function NswParseStatus($content){    //, array $details = []) {

			//extract the result
			$document   = phpQuery::newDocumentHTML($content)->getDocument();
			$cells      = $document->find('.grid tbody td');

			//check the cells are found
			if ($cells->count() < 3) {
				//return new Result(Result::STATUS_UNKNOWN, (string) $document);
				return '';
			}

			$status = $cells->get(2)->textContent;
			// $expiry = $cells->get(3)->textContent;

			switch ($status) {
				case 'CLEARED':
					return 'CLEARED'; //new Result(Result::STATUS_SAFE, (string) $document, \DateTime::CreateFromFormat('d/m/Y', $expiry));
					break;
				case 'APPLICATION IN PROGRESS':
					return 'PENDING';  //return new Result(Result::STATUS_NOTFOUND, (string) $document);
					break;
				case 'NOT FOUND':
					return 'NOTFOUND';  //return new Result(Result::STATUS_NOTFOUND, (string) $document);
					break;
				case 'BARRED':
					return 'FAILED';  //return new Result(Result::STATUS_NOTFOUND, (string) $document);
					break;
				case 'INTERIM BARRED':
					return 'FAILED';  //return new Result(Result::STATUS_NOTFOUND, (string) $document);
					break;
				default:
					return 'No result';  //new Result(Result::STATUS_UNKNOWN, (string) $document);
			}
		}

		function NswParseExpiry($content){    //, array $details = []) {

			//extract the result
			$document   = phpQuery::newDocumentHTML($content)->getDocument();
			$cells      = $document->find('.grid tbody td');

			//check the cells are found
			if ($cells->count() < 3) {
				return NULL;
			}
			$expiry = $cells->get(3)->textContent;
			$expiry = str_replace('/', '-', $expiry);
			return date('Y-m-d', strtotime($expiry));
		}

		function fetchToken($url) {

			//create the HTTP session
			$response = $this->client->get($url, [
				'cookies' => true,
			]);
			
			$matches[1]="s56IwkNHnMjAP19TU5wVGVUd4gzPCrzNxJmG3mJd+K+XKIqcbuv0mCNeTx4thJGiW+kegIuTg7THMG2CTs5d+ofiOB8migdlBvhGKciPGdQaG7xdrUuGC1c0aCBcHLRHrUalKDEkuZlzrZMF2bGqupXnhJA=";
			//check the response was successful and extract the request verification token
			if ($response->getStatusCode() != 200 || preg_match('#<input name="__RequestVerificationToken" type="hidden" value="(.*)" />#', (string) $response->getBody(), $matches) !== 1) {
				//throw new Exception(get_class($this), [], 'Unable to fetch CSRF token.');
			}

			return $matches[1];
			
		}

		
	}
	


?>

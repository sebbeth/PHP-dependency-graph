<?php 

	// This php requires $nswUsername, $nswPassword, $Lname, $DOB, $WWCNumber to be defined before being called
	// It will return $WwccResultPage and $WwccResultStatus and $WwccResultExpiry as Strings
	
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

	echo "($nswUsername, $nswPassword, $Lname, $DOB, $WWCNumber) ";
	
	
	
?>
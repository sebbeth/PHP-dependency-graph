<?php
	
	require_once( __DIR__.'/../vendor/stripe/stripe/init.php');
	
	$stripe = array(
		"secret_key"      => "sk_live_Lz5JkMyd9GUXUaI4A1UwIVL3",    //  "sk_test_BTEqr39h1OYeyUvOrDSrda9A",    //  
		"publishable_key" => "pk_live_6HGSORTeldUb99sValErYlut"     //  "pk_test_OfurQuIpnH652TEmto8Yl6fU"
	);
	
//	$stripe = array(
//		"secret_key"      => "sk_test_BTEqr39h1OYeyUvOrDSrda9A",    //  
//		"publishable_key" => "pk_test_OfurQuIpnH652TEmto8Yl6fU"
//	);
	
	\Stripe\Stripe::setApiKey($stripe['secret_key']);

?>
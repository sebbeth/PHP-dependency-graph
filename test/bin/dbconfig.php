<?php

	$db_host = "localhost";
	$db_name = "safemini_db";
	$db_user = "safemini_dm";
	$db_pass = "hbc653";

	mysql_connect("$db_host", "$db_user", "$db_pass")or die("cannot connect"); 
	mysql_select_db("$db_name")or die("cannot select DB");
  
?>
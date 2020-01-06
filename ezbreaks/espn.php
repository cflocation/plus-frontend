<?php
	ini_set("display_errors",1);

	$username = trim('programming@showseeker.com');
	$password = trim('showseeker1');
	$loginUrl = "https://affiliate.disney.espn.com/j_spring_security_check"; 

	//init curl
	$ch = curl_init();

	//Set the URL to work with
	curl_setopt($ch, CURLOPT_URL, $loginUrl);

	// ENABLE HTTP POST
	curl_setopt($ch, CURLOPT_POST, 1);

	//Set the post parameters
	curl_setopt($ch, CURLOPT_POSTFIELDS, 'j_username='.$username.'&j_password='.$password);

	//Handle cookies for the login
	curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');

	//Setting CURLOPT_RETURNTRANSFER variable to 1 will force cURL
	//not to print out the results of its query.
	//Instead, it will return the results as a string return value
	//from curl_exec() instead of the usual true/false.
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	//execute the request (the login)
	$store = curl_exec($ch);

	//the login is now done and you can continue to get the
	//protected content.

	//set the URL to the protected file
	curl_setopt($ch, CURLOPT_URL, 'https://affiliate.disney.espn.com/home/programming/grid');

	//execute the request
	$content = curl_exec($ch);


	print_r($content);

	//save the data to disk
	//file_put_contents('~/download.zip', $content);



?>


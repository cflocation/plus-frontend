<?php
	ini_set("display_errors",1);

	$secret = "66ed2238cafb47c491ba6f0d9bf4e215";
	$email = "wyoming.harvey@spectrum.com";
	$nonce = time();

	$message = (string)$email.(string)$nonce;
	$signature = strtoupper(hash("sha256",$secret.$message));
	$fields_string = "";
	//$key - email


	$fields = array(
		'key' => urlencode($email),
		'nonce' => urlencode($nonce),
		'signature' => urlencode($signature)
	);


	//$link = "https://plus.showseeker.com/plus/auth/sr/?key=$email&nonce=$nonce&signature=$signature";
	//print_r($link);
	/*
	foreach($fields as $key=>$value) {
		$fields_string .= $key.'='.$value.'&';
	}
	
	rtrim($fields_string, '&');

	$ch = curl_init();

	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

	//execute post
	$result = curl_exec($ch);

	//close connection
	curl_close($ch);
	*/
?>

<h2>GO</h2>

 <form action="index.php" method="get">
  <input type="text" name="key" value="<?php print $email; ?>"><br>
  <input type="text" name="nonce" value="<?php print $nonce; ?>"><br>
  <input type="text" name="signature" value="<?php print $signature; ?>"><br>
  <input type="submit" value="Submit">
</form> 

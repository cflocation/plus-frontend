<?php 
	
	$email 		= $_POST['email'];
	$firstName 	= $_POST['first'];
	$id 		= $_POST['id'];
	$result 	= sentEmailFlag($id,$email,$firstName);
	print($result);
	exit;
	
	function sentEmailFlag($id,$email,$firstName){
		$ch 		= curl_init();
		$timeout 	= 5;		
		$url 		= 'https://admin.showseeker.com/services/user-emailer-Spectrum.php';
		$data 		= "id={$id}&first={$firstName}&email={$email}";  
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded','Content-Length: ' . strlen($data)));
		$data 		= curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
	
?>
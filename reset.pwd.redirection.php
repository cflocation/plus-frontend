<?php
	$userId = $_GET['userId'];
	$token 	= json_decode(getToken($userId));
	$t 		= "reset.password.php?token=".$token->token."&app=plus";
	header("location: $t");
	exit;

	
	function getToken($id){
		$ch 		= curl_init();
		$timeout 	= 5;		
		$url 		= 'https://plusapi.showseeker.com/user/passwordreset/passwordtoken';
		$data 		= array("userId" => "{$id}");  
		$data_string= json_encode($data);

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_string)));
		$data 		= curl_exec($ch);
		curl_close($ch);

		return $data;
	}
	
?>
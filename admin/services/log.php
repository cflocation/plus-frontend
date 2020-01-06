<?php
	include_once('../database.php');
	$userId = $_GET['userId'];
	$apiKey = $_GET['apiKey'];
	print fetchUser($userId,$apiKey);
	exit;
	
	function fetchUser($userId,$apiKey){
		$sql 		= "	SELECT 	email, firstName,lastName
						FROM 	User
						WHERE	id = {$userId}";
		$result  	= mysql_query($sql);
		return json_encode(mysql_fetch_assoc($result));
	}
	
?>
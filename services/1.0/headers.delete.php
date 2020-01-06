<?php
	
	
	ini_set("display_errors",1);

	//set the userid and the token id
	$userid 		= $_POST['userid'];
	$authtokin 	= $_POST['tokenid'];
	$rows 		= $_POST['rows'];

	//if there is anything blank return an error
	/*if(empty($authtokin) || empty($userid)){
		exit('error');
	}*/

	//include the datbase file for the authentication and other services
	include_once('../../config/mysqli.php');

	//Authentication
	require_once('../../classes/Auth.php');
	$auth = new Auth($con);
	$url = $_SERVER['PHP_SELF'];
	$key = $auth->checkAuth($url,$authtokin,$userid);

	/*if(!$key){
		print "Access denied - You are not authorized to access this page.";
		exit;
	}*/

	$rows = json_decode($rows);

	
	foreach ($rows as &$value) {
		$sql = "DELETE FROM headers WHERE id = {$value->id}";
		$result = mysqli_query($con, $sql);
	}

	return;
?>
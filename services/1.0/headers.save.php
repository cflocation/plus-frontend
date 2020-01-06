<?php
	ini_set("display_errors",1);

	$userid 		= $_POST['userid'];
	$authtokin 	= $_POST['tokenid'];
	$header 		= $_POST['header'];
	$headerid 	= $_POST['headerid'];

	//if there is anything blank return an error
	if(empty($authtokin) || empty($userid)){
		exit('error');
	}

	include_once('../../config/mysqli.php');

	//Authentication
	require_once('../../classes/Auth.php');

	$auth 	= new Auth($con);
	$url 	= $_SERVER['PHP_SELF'];
	$key 	= $auth->checkAuth($url,$authtokin,$userid);


	/*if(!$key){
		print "Access denied - You are not authorized to access this page.";
		exit;
	}*/
	
	//set post date
	$d = date('Y-m-d H:i:s');

	if($headerid == 0){
		$sql = "INSERT INTO headers (userid, header, createdat, updatedat)VALUES ({$userid}, '".mysqli_real_escape_string($con,$header)."','{$d}','{$d}')";
	}
	else{
		$sql = "UPDATE headers SET header='".mysqli_real_escape_string($con,$header)."' WHERE id={$headerid}";
	}

	mysqli_query($con, $sql);
?>
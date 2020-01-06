<?php
	
	ini_set("display_errors",1);

	//set the userid and the token id
	$userid 		= $_GET['userid'];
	$authtokin 	= $_GET['tokenid'];

	//if there is anything blank return an error
	if(empty($authtokin) || empty($userid)){
		exit('error');
	}

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
	//set the token id for the user
	$tokenid 	= $key;
	
	$sql 			= "SELECT * FROM headers WHERE userid = {$userid} AND deletedat IS NULL ORDER BY header";
	$result 		= mysqli_query($con, $sql);
	$num_rows 	= mysqli_num_rows($result);

	if($num_rows == 0){
		print '[]';
		return;
	}

	while ($row = mysqli_fetch_assoc($result)) {
    	$search[] = $row;
	}

	print json_encode($search);
?>
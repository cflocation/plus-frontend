<?php
	ini_set("display_errors",1);

	//if there is anything blank return an error
	if(empty( $_GET['userid']) || empty($_GET['tokenid'])){
		exit('error');
	}

	$userid = $_GET['userid'];
	$authtokin = $_GET['tokenid'];

	include_once('../../config/mysqli.php');

	//Authentication
	require_once('../../classes/Auth.php');
	$auth = new Auth($con);
	$url = $_SERVER['PHP_SELF'];
	$key = $auth->checkAuth($url,$authtokin,$userid);


	if(!$key){
		print "Access denied - You are not authorized to access this page.";
		exit;
	}
	//set the token id for the user
	$tokenid = $key;


	$sql = "SELECT id,title FROM show_titles2 ORDER BY title ASC";

	$result = mysqli_query($con, $sql);

	while ($row = $result->fetch_assoc()) {
		$data[] = $row;
	}

	print json_encode($data);
?>
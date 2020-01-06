<?php
	ini_set("display_errors",1);

	$userid = $_POST['userid'];
	$authtokin = $_POST['tokenid'];

	//if there is anything blank return an error
	if(empty($authtokin) || empty($userid)){
		exit('error');
	}

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

	//include the classes and the database
	include_once('../../classes/User.php');
	include_once('../../classes/Proposal.php');

	//posted
	$proposaldata = $_POST['proposal'];
	$weeks = $_POST['weeks'];
	$name = $_POST['name'];
	$calendar = $_POST['calendar'];	

	//call to user class
	$user = new User($con,$userid,$tokenid);
	$userinfo = $user->getuserinfo();


	//call the proposal class file
	$proposal = new Proposal($con, $userid, $tokenid);
	$create = $proposal->createproposals($proposaldata, $weeks, $name, $calendar);

	$array = array("id" => $create);
	$re = json_encode($array);
	print $re;
		
	try {
		//LOG EVENT
		$d = date('Y-m-d H:i:s');
		mysqli_select_db($con,"logs");
		$sql = "INSERT INTO eventlogs (userid,eventslogid,request,result,proposalid,createdat, updatedat)VALUES ({$userid}, 2,'{$name}','{$re}','{$create}','{$d}','{$d}')";
		mysqli_query($con,$sql);
	}
	catch(Exception $e) {}
?>
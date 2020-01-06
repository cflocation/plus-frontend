<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	ini_set('max_execution_time','0');
	ini_set('memory_limit','2048M');
	
	$userid 	= $_POST['userid'];
	$authtokin 	= $_POST['tokenid'];

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


	if(!$key){
		print "Access denied - You are not authorized to access this page.";
		exit;
	}
	
	//set the token id for the user
	$tokenid = $key;

	//include the classes and the database
	include_once('../../config/mysqli.php');
	include_once('../../classes/User.php');
	include_once('../../classes/Proposal.php');

	//incoming data
	$id 							= $_POST['id'];
	$discountpackagetype 			= $_POST['discountpackagetype'];
	$discountpackage 				= $_POST['discountpackage'];
	$discountagency 				= $_POST['discountagency'];
	$proposaldata 					= $_POST['proposal'];

	if(isset($_POST['proposalTotalInfoAgencyDisc'])){
		$proposalTotalInfoAgencyDisc 	= $_POST['proposalTotalInfoAgencyDisc'];
	}
	else{
		$proposalTotalInfoAgencyDisc 	= 0;
	}

	$proposalTotalInfoEndDate 		= $_POST['proposalTotalInfoEndDate'];

	if(isset($_POST['proposalTotalInfoGross'])){
		$proposalTotalInfoGross 		= $_POST['proposalTotalInfoGross'];
	}
	else{
		$proposalTotalInfoGross 		= 0;
	}
	
	$proposalTotalInfoLineCount 	= $_POST['proposalTotalInfoLineCount'];

	if(isset($_POST['proposalTotalInfoNet'])){
		$proposalTotalInfoNet 			= $_POST['proposalTotalInfoNet'];
	}
	else{
		$proposalTotalInfoNet 			= 0;		
	}
	
	if(isset($_POST['proposalTotalInfoPackageDisc'])){
		$proposalTotalInfoPackageDisc 	= $_POST['proposalTotalInfoPackageDisc'];
	}
	else{
		$proposalTotalInfoPackageDisc 	= 0;
	}
	$proposalTotalInfoSpots 		= $_POST['proposalTotalInfoSpots'];
	$proposalTotalInfoStartDate 	= $_POST['proposalTotalInfoStartDate'];	
	$proposalTotalInfoZones 		= $_POST['proposalTotalInfoZones'];
	$weeks 							= $_POST['weeks'];
	$zones 							= '';

	//set the zones as a comma delimited list
	if(is_array($proposalTotalInfoZones))
		$zones = implode(', ', $proposalTotalInfoZones);


	//fix the start and end date
	if($proposalTotalInfoStartDate == 0){
		$proposalTotalInfoStartDate = 'NULL';
	}else{
		$proposalTotalInfoStartDate = "'".$proposalTotalInfoStartDate."'";
	}


	if($proposalTotalInfoEndDate == 0){
		$proposalTotalInfoEndDate = 'NULL';
	}else{
		$proposalTotalInfoEndDate = "'".$proposalTotalInfoEndDate."'";
	}


	//call to user class
	$user 		= new User($con,$userid,$tokenid);
	$userinfo 	= $user->getuserinfo();


	//call the proposal class
	$proposal = new Proposal($con, $userid, $tokenid);
	$save = $proposal->save(
		$id, 
		$proposaldata, 
		$discountpackagetype, 
		$discountpackage, 
		$discountagency, 
		$weeks, 
		$proposalTotalInfoSpots, 
		$proposalTotalInfoGross, 
		$proposalTotalInfoNet, 
		$proposalTotalInfoAgencyDisc,
		$proposalTotalInfoPackageDisc,
		$proposalTotalInfoLineCount,
		$proposalTotalInfoStartDate,
		$proposalTotalInfoEndDate,
		$proposalTotalInfoZones,
		$zones);


	print json_encode($save);
?>



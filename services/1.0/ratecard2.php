<?php
	session_start();
	ini_set("display_errors",1);


	//set the userid and the token id and other varibles that are needed
	$userid = $_GET['userid'];
	$authtokin = $_GET['tokenid'];
	$zoneid = $_GET['zoneid'];
	$cardid = $_GET['cardid'];
	$startdate = $_GET['startdate'];
	$type = $_GET['type'];
	$group = urlencode($_GET['group']);


	//if there is anything blank return an error
	if(empty($authtokin) || empty($userid)){
		exit('error');
	}

	//include the classes and the database
	include_once('../../config/mysqli.php');

	//Authentication
	require_once('../../classes/Auth.php');
	$auth = new Auth($con2);
	$url = $_SERVER['PHP_SELF'];
	$key = $auth->checkAuthGoplus($url,$authtokin,$userid);



	//set the token id for the user
	$tokenid = $key;

	//if there is anything blank return an error
	if(empty($zoneid)){
		exit('error');
	}

	if($type == 0){
		//$url = 'http://ww2.showseeker.com/rc/?zoneid='.$zoneid.'&userid='.$userid.'&tokenid='.$tokenid;
		$url = 'http://www.showseeker.com/ezrates/services/rc2.php?zoneid='.$zoneid.'&userid='.$userid.'&tokenid='.$tokenid.'&startdate='.$startdate.'&cardid='.$cardid.'&group='.$group;		
	}

	if($type == 1){
		//$url = 'http://ratecards.showseeker.com/services/loadZoneRatecard.php?zoneid='.$zoneid.'&userid='.$userid.'&tokenid='.$tokenid.'&startdate='.$startdate.'&cardid='.$cardid.'&group='.$group;
		//$url = 'http://www.showseeker.com/ezrates/services/loadratecardRemote.php?zoneid='.$zoneid.'&userid='.$userid.'&tokenid='.$tokenid.'&startdate='.$startdate.'&cardid='.$cardid.'&group='.$group;
		$url = 'http://www.showseeker.com/ezrates/services/loadratecard2.php?zoneid='.$zoneid.'&userid='.$userid.'&tokenid='.$tokenid.'&startdate='.$startdate.'&cardid='.$cardid.'&group='.$group;
	}

	if($type == 2){
	//	$url = 'http://www.showseeker.com/ezrates/services/load.rc.php?zoneid='.$zoneid.'&userid='.$userid.'&tokenid='.$tokenid.'&startdate='.$startdate.'&cardid='.$cardid.'&group='.$group;
	}


	$page = file_get_contents($url);
	echo $page;
?>
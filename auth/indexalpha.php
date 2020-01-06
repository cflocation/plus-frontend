<?php
	ini_set("display_errors",1);
	session_start();

	if(!isset($_GET['id']) || $_GET['id'] == ''){
		header('location:http://plus.showseeker.com/login.php?logout=true');
		exit;
	}
	
	setcookie ("userid", "", time() - 3600);
	setcookie ("tokenid", "", time() - 3600);
	//session_destroy();


	$id = $_GET['id'];
	$tokenid = $_GET['tokenid'];

	if(!isset($_GET['id']) || !isset($_GET['tokenid'])){
		print '-1';
		return;
	}

	include_once('../config/database.php');
	

	$sql = "SELECT * FROM users WHERE id = $id AND tokenid = '$tokenid' AND deletedat IS NULL LIMIT 1";
	$re 		= mysql_query($sql);
	$num_rows 	= mysql_num_rows($re);
	$row = mysql_fetch_array($re);


	if($num_rows == 1){
	
		$_SESSION['userid']=$row['id'];
		$_SESSION['tokenid']=$row['tokenid'];
		$_SESSION['adminid']=uniqid();
				
		$expire	=time()+60*60*24*30;
		setcookie("userid", $row['id'], $expire);
		setcookie("tokenid", $row['tokenid'], $expire);
		
		$userid = $_SESSION['userid'];
		$dtime 	= date('Y-m-d H:i:s');

		header( 'Location: ../plus/' ) ;
	}else{
		print_r('-1');
		return;
	}
?>
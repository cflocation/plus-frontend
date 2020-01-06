<?php


	ini_set("display_errors",1);
	session_start();

	if(!isset($_GET['userid']) || $_GET['userid'] == ''){
		header('location:http://plus.showseeker.com/login.php?logout=true');
		exit;
	}
	
	if (isset($_SERVER['HTTP_COOKIE'])) {
	    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
	    foreach($cookies as $cookie) {
	        $parts = explode('=', $cookie);
	        $name = trim($parts[0]);
	        setcookie($name, '', time()-1000);
	        setcookie($name, '', time()-1000, '/');
	        setcookie($name, '', time()-1000, '/', '.showseeker.com');
	    }
	}

	//setcookie ("userid", "", time() - 3600);
	//setcookie ("tokenid", "", time() - 3600);
	session_destroy();


	$id = $_GET['userid'];
	$tokenid = $_GET['tokenid'];

	if(!isset($_GET['userid']) || !isset($_GET['tokenid'])){
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
		
		$expire	=time()+60*60*24*30;
		setcookie("userid", $row['id'], $expire, "/");
		setcookie("tokenid", $row['tokenid'], $expire, "/");

		header( 'Location: ../plus' ) ;
	}else{
		print_r('-1');
		return;
	}
?>
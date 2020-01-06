<?php
	//global permission page
	session_start();
	ini_set("display_errors",1);

	if(!$_SESSION['corporationid'] || !$_SESSION['userid']){
		$re = array("data"=>"login");
		print json_encode($re);
		exit;
	}


	include_once('../../config/database.php');

	//session ids
	$corporationid = $_SESSION['corporationid'];
	$userid        = $_SESSION['userid'];
	$roles         = $_SESSION['roles'];
	$apikey        = $_SESSION['apikey'];
	$corpApiKey    = $_SESSION['corpApiKey'];
	$datestamp     = date('Y-m-d H:i:s');

	function isRole($roles,$role){
		foreach ($roles as &$value) {
			if($role == $value['roleid']){
				return true;
			}
		}
		return false;
	}



?>
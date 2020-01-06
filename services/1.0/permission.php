<?php
	include_once('../config/mysqli.php');
	require_once('../classes/User.php');
  	$userid = $_SESSION['userid'];
  	$tokenid = $_SESSION['tokenid'];
  	$user = new User($con,$userid,$tokenid);
  	$valid = $user->inrole($roleId);
  	
  	if($valid == 0){
      header( 'Location: /login.php?logout=true&redirectto='.rawurlencode($_SERVER['REQUEST_URI']));
      //print '<b>No Access</b>';
    	//exit;
  	}
?>
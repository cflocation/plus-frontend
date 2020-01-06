<?php
ini_set("display_errors",1);
  error_reporting(E_ALL);
	include_once('../config/mysqli.php');
	require_once('../classes/User.php');
  	$userid = $_SESSION['userid'];
  	$tokenid = $_SESSION['tokenid'];
  	$user = new User($con,$userid,$tokenid);
  	$result = $user->getrolelist();
    foreach ($result as &$value) {
      print '<li><a href="/'.$value["path"].'">'.$value["alias"].'</a></li>';
    }
?>
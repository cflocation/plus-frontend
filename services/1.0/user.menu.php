<?php
session_start();

include_once('../../config/mysqli.php');
require_once('../../classes/User.php');
  	$userid = $_SESSION['userid'];
  	$tokenid = $_SESSION['tokenid'];
  	$user = new User($con,$userid,$tokenid);
  	$result = $user->getrolelist();
    foreach ($result as &$value) {
      print '<li><a href="/'.$value["path"].'">'.$value["alias"].'</a></li>';
    }
?>
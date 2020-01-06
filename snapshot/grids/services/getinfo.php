<?php

	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(-1);	
	
	$showid = $_GET['showid'];
	$altId = $_GET['altKey'];
	$getjson = true;
	
	include_once('../classes/Showinfo.php');
	
	$info =  new Showinfo();
	print_r($info->getShowinfo($showid,$altId,true));
	
?>
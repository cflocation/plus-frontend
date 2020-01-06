<?php
	/*$dbHost		= 'db4.showseeker.net';
	$dbUserName	= 'vastdbuser';
	$dbPassWord	= 'jK6YK71tJ';
	$dbName		= 'ShowSeeker';*/
	
	$dbHost		= 'db0.showseeker.net';
	$dbUserName	= 'dbadmin';
	$dbPassWord	= 'XddS2fTFr4521';
	$dbName		= 'ShowSeeker';	

	$con = mysqli_connect($dbHost,$dbUserName,$dbPassWord,$dbName);
	
	// Check connection
	if (mysqli_connect_errno()){
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
?> 
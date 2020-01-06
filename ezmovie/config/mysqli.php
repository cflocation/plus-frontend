<?php
	$dbHost		= 'db2.showseeker.net';
	$dbUserName	= 'vastsupport1';
	$dbPassWord	= 'cP7qRiSvaR2M';
	$dbName		= 'Programs';

	$con = mysqli_connect($dbHost,$dbUserName,$dbPassWord,$dbName);
	
	// Check connection
	if (mysqli_connect_errno()){
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
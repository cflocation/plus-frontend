<?php
	//$dbHost		= '61b1ed95616f65903ff311cc9decad51aa4cac3d.rackspaceclouddb.com';
	$dbHost		= 'db4.showseeker.net';
	$dbUserName	= 'devDBAUserSSDBOR';
	$dbPassWord	= 'avcZ5j26yU4EyqB66RmfcjfuPGwDkBLUNnZe8MM2UBuw3k';
	$dbName		= 'ShowSeeker';

	$con = mysqli_connect($dbHost,$dbUserName,$dbPassWord,$dbName);
	
	// Check connection
	if (mysqli_connect_errno()){
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}


	$newdbHost		= 'db0.showseeker.net';
	$newdbUserName	= 'devDBAUserSSDB';
	$newdbPassWord	= 'L8YRtuK7n8xQR8FJ8bPChyvKvXSLZC7waCK37T28BXW';
	$newdbName		= 'ShowSeeker';

	$con2 = mysqli_connect($newdbHost,$newdbUserName,$newdbPassWord,$newdbName);
	
	// Check connection
	if (mysqli_connect_errno()){
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
?> 
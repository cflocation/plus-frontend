<?php
	$dbHost		= '10.5.10.120';
	$dbUserName	= 'devDBAUserSSDBOR';
	$dbPassWord	= 'avcZ5j26yU4EyqB66RmfcjfuPGwDkBLUNnZe8MM2UBuw3k';
	$dbName		= 'ShowSeeker';

	$con = mysqli_connect($dbHost,$dbUserName,$dbPassWord,$dbName);
	
	// Check connection
	if (mysqli_connect_errno()){
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}?> 
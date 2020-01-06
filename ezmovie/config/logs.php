<?php
$logDbHost		= 'db3.prod.showseeker.com';
$logDbUserName	= 'devDBALogs';
$logDbPassWord	= 'kUgEGBgL2t5ej3PMSykVHhPXHSswNX2DQBvtmT9xbaPrfh';
$logDbName		= 'Log';
$logDb          = mysqli_connect($logDbHost,$logDbUserName,$logDbPassWord,$logDbName);
	
// Check connection
if (mysqli_connect_errno()){
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


function ezMovieLogEvent($logDb,$rootId,$userId,$created,$updates,$remarks){
	$now     = date("Y-m-d H:i:s");
	$updates = implode(',',$updates);
	$sql     = "INSERT INTO EzMovieLog(rootId, userId, created, updated, remarks, createdAt) VALUES ($rootId,$userId,$created,'{$updates}','{$remarks}','{$now}')";
	$result = mysqli_query($logDb, $sql);

	return $result;
}

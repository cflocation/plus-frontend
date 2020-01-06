<?php
	session_start();
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);

	require_once('../inc/s3/S3.php');
	$awsAccessKey = 'AKIAJV3JKEBKML5Q7OVA';
	$awsSecretKey = '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';
	S3::setAuth($awsAccessKey, $awsSecretKey);
	$bucketName = "showseeker";



//DISCOVER LAST MONDAY, CONVERT TO DAYS OF YEAR FORMAT

if (date('N', time()) == 1) $dayofyear=date('z');
else $dayofyear=date('z', strtotime('last Monday'));

//ADJUST FOR BUILDER STARTING AT 0
$correctMonday =  $dayofyear +1;


$folderPath1 = "grids/2015/xlsx/ast/".$correctMonday."/";

echo $folderPath1 ;





	//S3::getObject($bucketName, $uploadName, fopen('savefile.txt', 'wb'))
	//$s3->getObject('myBucket','myRemoteFile', array('fileDownload' => 'localFileName'));

	

?>
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
$correctMonday =  $dayofyear ;

$year = date('Y');
$type = $_GET['type']; //xlsx or pdf
$tz = $_GET['tz'];

$path = "grids/".$year."/".$type."/".$tz."/".$correctMonday."/";

print $path;

/*
$folderPath1b = "grids/2015/pdf/ast/".$correctMonday."/";

$folderPath2a = "grids/2015/xlsx/cst/".$correctMonday."/";
$folderPath2b = "grids/2015/pdf/cst/".$correctMonday."/";

$folderPath3a = "grids/2015/xlsx/est/".$correctMonday."/";
$folderPath3b = "grids/2015/pdf/est/".$correctMonday."/";

$folderPath4a = "grids/2015/xlsx/hast/".$correctMonday."/";
$folderPath4b = "grids/2015/pdf/hast/".$correctMonday."/";
*/

$time_start = microtime(true);


$arr1a = S3::getBucket($bucketName,$path);
	foreach ($arr1a as &$value) {
	    $name = $value['name'];
	    $hash = $value['hash'];
	    $size = $value['size'];
	    $localfileArray = explode("/", $name);
	    $localfilename = $localfileArray[5];
		$locafile = '/var/www/html/showseeker.com/ezgrids/download/'.$tz.'/'.$type.'/'.$localfilename;
	    //print $localfilename.'<br>';
	    S3::getObject($bucketName, $name, fopen($locafile, 'wb'));
	}


	print "AST Excel";
?>

<?php
	session_start();
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);


$key = "AKIAJL3LTL2KSQHRLV3A";
$secret = "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";

require_once('sdk.class.php');

$s3 = new AmazonS3($key, $secret);

$objInfo = $s3->get_object_headers('showseeker/test', 'SS_P4.png');
$obj = $s3->get_object('showseeker/test', 'SS_P4.png', array('headers' => array('content-disposition' => $objInfo->header['_info']['content_type'])));

echo $obj->body;


?>
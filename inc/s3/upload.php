<?php
	require_once('S3.php');
	$awsAccessKey = 'AKIAIKHJVBVMNDZHEZOA';
	$awsSecretKey = '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';
	S3::setAuth($awsAccessKey, $awsSecretKey);

	function uploadToS3($filepath,$filename,$type,$userid){
		//set the bucket name
		$bucket = strtolower("showseeker.downloads/".md5($userid)."/$type");

		//upload the file
		S3::putObject(S3::inputFile($filepath, false), $bucket, $filename, S3::ACL_PUBLIC_READ);

		//set the lionk for the download
		$link = "https://s3.amazonaws.com/showseeker.downloads/".md5($userid)."/".$type."/".$filename;

		//return the link
		return $link;
	}

?>
<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);	
	
	$programid = $_GET['id'];
	
	$url = getCoverImage($programid);
	


	if($url != '0'){
		$p = '<center><img class="side-wrapper" width="170" src="'.$url.'" /></center>';
	}else{
		$p = '<center></center>';
	}

	print $p;


	function getCoverImage($id){
		$baseurl = 'https://plusapi.showseeker.com/show/';
		$showimg = $baseurl.$id.'/null';
		$jsondat = file_get_contents($showimg);
		$jdec = json_decode($jsondat);
		if($jdec->result == 1)
			return $jdec->thumb;
		else
			return 0;
	}

?>
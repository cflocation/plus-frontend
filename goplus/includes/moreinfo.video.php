<?php
	$showid = $_GET['id'];

	$pointurl = 'http://api.internetvideoarchive.com/1.0/DataService/GetEntertainmentProgramByPinpointId?ID=\''.$showid.'\'&IdType=11&developerid=dc6e450d-f4ab-477a-a4dd-a07166c3da7e&$format=json';
	$pointcontents = file_get_contents($pointurl);
	$pointid = json_decode($pointcontents);

	if(empty($pointid->d)){
		print 0;
		exit;
	}
	$pointid = $pointid->d[0]->Publishedid;


	$asseturl = 'http://api.internetvideoarchive.com/1.0/DataService/VideoAssets('.$pointid.')/Encodes?developerid=dc6e450d-f4ab-477a-a4dd-a07166c3da7e&$format=json';
	$assetcontent = file_get_contents($asseturl);
	$assets = json_decode($assetcontent);
	if(count($assets->d->results) < 1){
		print 0;
		exit;
	}
	$assets =  $assets->d->results;

	

	foreach ($assets as &$value) {
		$pid = $value->PublishedId;
		$videourl = getHash($value->URL);
		$arr[] = $videourl;
	}


	if($arr[0]){
		print "player/?id=".$showid."&url=".urlencode($arr[0])."&type=cl";
	}else{
		print 0;
	}


	function getHash($path){
		date_default_timezone_set('UTC');

		$secret = "omoylcithoerecmd";

		$date1 = mktime(0,0,0,1,1,1970);
		
		$date2 = strtotime('+30 minutes', time());

		$dateDiff = $date2 - $date1;

		$url = $path."&customerid=223344&e=".$dateDiff."";
		
		$tohash = $secret.$url;
		
		$md5 = md5($tohash);
		
		$xrl = $path."&customerid=223344&e=".$dateDiff."&h=".$md5."";
		
		return $xrl;
	}

?>
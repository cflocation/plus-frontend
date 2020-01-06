<?php
	$showid = $_GET['id'];

	$pointurl = 'http://api.internetvideoarchive.com/1.0/DataService/GetEntertainmentProgramByPinpointId?ID=\''.$showid.'\'&IdType=11&developerid=dc6e450d-f4ab-477a-a4dd-a07166c3da7e&$format=json';
	$pointcontents = file_get_contents($pointurl);
	$pointid = json_decode($pointcontents);
	$pointid = $pointid->d[0]->Publishedid;


	$asseturl = 'http://api.internetvideoarchive.com/1.0/DataService/VideoAssets('.$pointid.')/Encodes?developerid=dc6e450d-f4ab-477a-a4dd-a07166c3da7e&$format=json';
	$assetcontent = file_get_contents($asseturl);
	$assets = json_decode($assetcontent);
	$assets =  $assets->d->results;

	//$videourl = "http://showinfo.prod.showseeker.com/iva.php?path=".urlencode($value->URL)."";

	

	foreach ($assets as &$value) {
		$pid = $value->PublishedId;
		$videourl = "http://showinfo.prod.showseeker.com/iva.php?path=".urlencode($value->URL)."";
		$videourl = file_get_contents($videourl);
		$arr[] = $videourl;
	}




	if($arr[0]){
		$path = 'https://plus.showseeker.com/plus/player/?&type=cl&id='.$showid.'&url='.urlencode($arr[0]);
		header('Location:'.$path);
	}else{
		print 0;
	}
?>

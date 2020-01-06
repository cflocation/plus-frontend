<?php
	$userid  = $_GET['userId'];
	$tokenid = $_GET['tokenId'];
	$url     = $_GET['url'];

	//build the key
	$key = md5($userid.$tokenid.$url);
	$uri = $url."?userid=".$userid."&tokenid=".$key;	
	$re  = array("key"=>$key, "url"=>$uri, "userid"=>$userid, "passedurl" => $url);

	//return the key to the browser to send to the webservice
	print json_encode($re);
?>
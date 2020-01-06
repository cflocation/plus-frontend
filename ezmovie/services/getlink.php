<?php
	ini_set("display_errors",1);

	$title = $_GET['title'];
	$type = $_GET['type'];


	if($type == "tvcom"){
		$title2 = preg_replace("/[^ \w]+/", "", $title);
		$title2 = strtolower(str_replace(" ", "-", $title2));


		$url = "http://www.tv.com/shows/".$title2;
		$headers = get_headers($url);
		
		$error = strpos($headers[9], '404');

		if($error == false){
			$arr = array("url"=>$url);
			print json_encode($arr);
		}else{
			$arr = array("url"=>0);
			print json_encode($arr);
		}
		return;
	}
?>


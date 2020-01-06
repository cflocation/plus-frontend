<?php 
//This file goes out to the showseeker site, and gets the list of youtube video links and then checks them against the youtube API //
set_time_limit(9000);

$download_location = "http://i.showseeker.com/youtube.php?apikey=63d663f0-b52c-11e3-a5e2-0800200c9a66&event=list"; 
$contents = file_get_contents($download_location);  
$g = json_decode($contents);

foreach($g as $value){
	$r = breakURL($value->url);
	$linkout = "<a target=_blank href=".$value->url.">".$value->url."</a><br>";
	if ($r != '1') {
		//print $value->id;
		$youtube = "http://i.showseeker.com/youtube.php?apikey=63d663f0-b52c-11e3-a5e2-0800200c9a66&event=remove&id=".$value->id;
		$youtube_status = file_get_contents($youtube);  
	}

}

function breakURL($url){
	$arr = parse_url($url);
	if(isset($arr['host'])){
		$url = $arr['host'];
		$pos = strpos($url, 'youtube.com');

		if($pos > 0){
			$id = substr($arr['query'], 2, 11);
			$v = validYoutube($id);	
			return $v;
		}		
	}
	if(isset($arr['host'])){
		if ($arr['host']=='youtu.be'){
				$id = substr($arr['path'], 1, 11);
		
			$v = validYoutube($id);	
			return $v;
		}		
	}
}

function validYoutube($id){
    $id = trim($id);
    if (strlen($id) === 11){
        $file = @file_get_contents('http://gdata.youtube.com/feeds/api/videos/'.$id);
        return !!$file;
    }
    return false;
}
?> 
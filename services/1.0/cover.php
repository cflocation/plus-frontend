<?php
	ini_set("display_errors",1);

	//include the classes and the database
	include_once('../../config/mysqli.php');

	$showid = $_GET['showid'];
	$width = $_GET['width'];
	$height = $_GET['height'];
	$baseurl = 'https://showseeker.s3.amazonaws.com/on/';

	$validShowid = validShowid($showid);

	//120
	//180
	$sql = "SELECT * FROM onGalleryImages WHERE TMSId = '$validShowid' AND action != 'delete' AND process = 1 AND width = $width AND height = $height AND category IN ('Banner','Box Art','Poster Art') ORDER BY lastModified LIMIT 1";
	mysqli_select_db($con,"On");
	$result = mysqli_query($con, $sql);

	$row_cnt = $result->num_rows;

	if($row_cnt == 0){
		$re = array("cover"=>0);
		print json_encode($re);
		return;
	}

	$row = $result->fetch_assoc();
	$baseimg = $row['URI'];
	$path = setpath($row['category']);
	$url =  $baseurl.$path.$baseimg;
	$row['fullPath'] = $url;

	$re = array("cover"=>$row['fullPath']);
	print json_encode($re);


	//create valid showid from whatever is passd in
	function validShowid($showid){
		$type = substr($showid, 0, 2);
		
		if($type == "MV" || $type == "SP"){
			if(strlen($showid) == 10){
				$showid = $showid.'0000';
			}
			return $showid;
		}

		if($type == "SH" || $type == "EP"){
			$showid = str_replace("EP","SH",$showid);
			$tmp = substr($showid, 0, 10);
			$showid = $tmp.'0000';
			return $showid;
		}
	}



	//get the priper image path if needed
	function setpath($i){
		switch ($i) {
	    case 'Poster Art':
			return 'photos/movieposters/';
	        break;
	    case 'Box Art':
	       	return 'photos/dvdboxart/';
	       	break;
	    case 'Banner':
	       	return 'photos/tvbanners/';
	    case 'Logo':
	   		return 'db_photos/sportslogos/';
	       	break;
	       	
		}
	}

?>
<?php
// ini_set("display_startup_errors",1);
// ini_set("display_errors",1);
// error_reporting(E_ALL);

if (!defined('TMP_IMG_DIR')) define("TMP_IMG_DIR","/var/www/html/www.showseeker.com/ezshows/tmp/");
if (!defined('TMP_IMG_URL')) define("TMP_IMG_URL","http://ww4.showseeker.com/ezshows/tmp/");


function getImagesFromLinks($tmsId){
	global $con;

	$sql    = "SELECT futon, facebook, twitter, wiki, imdb, instagram, pintrest, youtube, theMovieDB, networkurl, rottentomatoes
				FROM Programs.ProgramRootid 
				INNER JOIN Programs.MovieLink ON MovieLink.rootId = ProgramRootid.rootid 
				WHERE ProgramRootid.connectorId = '$tmsId' LIMIT 1 ";
	$result = mysqli_query($con, $sql);
	$row    = $result->fetch_assoc();

	if(!($row)){
		return array();
	}

	$images = array();


	if($row['wiki'] != ""){
		$image = imageFromWikiPedia($row["wiki"]);
		if($image != '' && file_exists(TMP_IMG_DIR.$image))
			$images[] = $image;
	}

	if($row['imdb'] != ""){
		$image = imageFromIMDb($tmsId,$row["imdb"]);
		if($image != '' && file_exists(TMP_IMG_DIR.$image))
			$images[] = $image;
	}

	
	return $images;
}


function imageFromIMDb($tmsId,$url){
	try{
		$html    = file_get_html($url);
		$div     = $html->find('div.poster',0);
		$imgHref = ($div) ? $div->find('a',0) : false;
		$img     = ($imgHref) ? $imgHref->find('img',0) : false;
		$imgSrc  = ($img) ? $img->src : '';
		$html->clear();

		$ext  = pathinfo(rawurldecode(basename($imgSrc)), PATHINFO_EXTENSION);
		$name = strtolower("{$tmsId}_".uniqid()).".{$ext}";
		$res  = file_put_contents(TMP_IMG_DIR.$name, fopen("$imgSrc", 'r'));

		return $name;
	} catch(Exception $e){
		return "";
	}
}


function imageFromWikiPedia($url){
	try{
		$html    = file_get_html($url);
		$infoTbl = $html->find("table.infobox", 0);
		$imgHref = ($infoTbl) ? $infoTbl->find('a.image',0) : false;
		$imgLink = ($imgHref) ? $imgHref->href : '';
		$imgUrl  = ($imgLink != '') ? 'http://en.wikipedia.org'.$imgLink : '';		
		$html->clear();

		if($imgUrl == '')
			return '';

		$html   = file_get_html($imgUrl);
		$imgSrc = $html->find('img',0)->src;
		$html->clear();

		$name = strtolower(preg_replace('/[^A-Za-z0-9_\-.]/', '_',rawurldecode(basename($imgSrc))));
		$res  = file_put_contents(TMP_IMG_DIR.$name, fopen("http:{$imgSrc}", 'r'));

		return $name;
	} catch(Exception $e){
		return "";
	}

}
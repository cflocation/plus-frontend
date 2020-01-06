<?php
	require_once('simple_html_dom.php');
	require_once('../../config/mysqli.php');
	if( !ini_get('safe_mode') ){ 
		set_time_limit(0); 
		ini_set('max_execution_time', 0);
	}

	ini_set("display_startup_errors", "On");
	ini_set("display_errors", "On");

	error_reporting(E_ALL);

	//get all shows from links table
	$con->select_db("On");

	

	if(isset($_POST['tvdbid'])){
		importShowDetails($con, $_POST['tvdbid']);

		print $_POST['tvdbid'];
	}
	else
	{
		$sql = 'SELECT tvdb
		FROM links WHERE tvdb!=0 AND tvdb NOT IN(SELECT showid FROM `tvdb_shows`)
		limit 30';
		$result = mysqli_query($con, $sql);

		while ($row = $result->fetch_assoc()) {
			//print "<br>".$row['tvdb'];
			$tvdbid = $row['tvdb'];

			importShowDetails($con, $row['tvdb']);
			
			//
			//$ingeninsQry = "INSERT INTO `On`.`tvdb_showsloaded` (`id`, `showid`) VALUES (NULL, '{$tvdbid}')";
			//$inresult = mysqli_query($con, $ingeninsQry);
		}
	}
	

//
	
function importShowDetails($con, $tvdbid)
{
	$con->select_db("On");
	$url = 'http://thetvdb.com/index.php?tab=series&id='.$tvdbid; //79488'; //trim($_GET['x']);
	if($url=='') exit('No Url provided');
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url); 
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($curl);
	curl_close($curl);
	$html = str_get_html($result);

	//$lastTableIndex = count($html->find('body table tr td table',6)->find('table'))-1;
	$html->find("#maincontent", 0);
	
	$infoarray = array();

	$titletag = $html->find("#maincontent #content h1", 0);
	$infoarray['title'] = $title = addslashes($titletag->plaintext);
	
	$descriptionhtml = $html->find("#maincontent #content", 0)->innerText();
	$infoarray['description'] = $description = addslashes(trim(str_replace('</h1>','',substr($descriptionhtml, strpos($descriptionhtml,'</h1>')))));

	$infotable = $html->find("#maincontent #fanart #content table ", 0)->find('table',0);
	for($i=0; $i<count($infotable->find('tr'));$i++)
	{
		//print $infotable->find('tr',$i)->find('td',0)." -> ".$infotable->find('tr',$i)->find('td',1);

		if($infotable->find('tr',$i)->find('td',0)->plaintext == 'First Aired:'){
			$infoarray['firstAired'] = $firstAired = date('Y-m-d', strtotime($infotable->find('tr',$i)->find('td',1)->plaintext));
		}

		if($infotable->find('tr',$i)->find('td',0)->plaintext == 'Air Day:'){
			$infoarray['airDay'] = $airDay = $infotable->find('tr',$i)->find('td',1)->plaintext;
		}

		if($infotable->find('tr',$i)->find('td',0)->plaintext == 'Air Time:'){
			$infoarray['airTime'] = $airTime = $infotable->find('tr',$i)->find('td',1)->plaintext;
		}

		if($infotable->find('tr',$i)->find('td',0)->plaintext == 'Runtime:'){
			$infoarray['runtime'] = $runtime = $infotable->find('tr',$i)->find('td',1)->plaintext;
		}

		if($infotable->find('tr',$i)->find('td',0)->plaintext == 'Network:'){
			$infoarray['network'] = $network = $infotable->find('tr',$i)->find('td',1)->plaintext;
		}

		if($infotable->find('tr',$i)->find('td',0)->plaintext == 'Genre:'){
			//print_r(explode('<br>',trim($infotable->find('tr',$i)->find('td',1)->innerText())));
			$infoarray['genre'] = explode('<br>',trim($infotable->find('tr',$i)->find('td',1)->innerText()));

			//search for old show genre entry
			$showgenQry ="select * from `On`.`tvdb_showgenre` where showid = '{$tvdbid}'";
			$sgresult = mysqli_query($con, $showgenQry);
			$showgenrowcount = mysqli_num_rows($sgresult);

			if($showgenrowcount > 0){
				$delshowgenQry ="delete from `On`.`tvdb_showgenre` where showid = '{$tvdbid}'";
				$sgresult = mysqli_query($con, $delshowgenQry);
			}

			foreach($infoarray['genre'] as $eachgenre){
				//insert or update genre master
				$genQry ="select * from `On`.`tvdb_genre` where name = '{$eachgenre}'";
				$gresult = mysqli_query($con, $genQry);
				$genrowcount = mysqli_num_rows($gresult);

				if($genrowcount > 0){
					$resultarr = $gresult->fetch_assoc();
					$genreid = $resultarr['id'];
				}
				else
				{
					//insert show record
					$con->select_db("On");
					$geninsQry = "INSERT INTO `On`.`tvdb_genre` (`id`, `name`) VALUES (NULL, '{$eachgenre}')";
					$result = mysqli_query($con, $geninsQry);
					$genreid = mysqli_insert_id($con);
				}

				
				//insert show record
				$geninsQry = "INSERT INTO `On`.`tvdb_showgenre` (`id`, `showid`, `genreid`) VALUES (NULL, '{$tvdbid}', '{$genreid}')";
				$result = mysqli_query($con, $geninsQry);
				
			}
		}
	}

	$srcQry ="select * from `On`.`tvdb_shows` where `showid` = '{$tvdbid}'";
	$sresult = mysqli_query($con, $srcQry);
	$rowcount = mysqli_num_rows($sresult);

	if($rowcount > 0){
		//print "<br/>Show already exist - Updating<br/>";
		$showQry = "update `On`.`tvdb_shows` SET `title`='{$title}', `description`='{$description}', `firstaireddate`='{$firstAired}', `airday`='{$airDay}', `airtime`='{$airTime}', `runtime`='{$runtime}', `network`='{$network}' where `showid` = '{$tvdbid}'";
		$result = mysqli_query($con, $showQry);
	}
	else
	{
		//print "<br/>Inserting new show<br/>";
		
		$showQry = "INSERT INTO `On`.`tvdb_shows` (`id`, `showid`, `title`, `description`, `firstaireddate`, `airday`, `airtime`, `runtime`, `network`) VALUES (NULL, {$tvdbid}, '{$title}', '{$description}', '{$firstAired}', '{$airDay}', '{$airTime}', '{$runtime}', '{$network}')";
		$result = mysqli_query($con, $showQry);

	}
$html->clear();

	//get all banners
	//$banners = $html->find("#maincontent #fanart #content", 3);
	//$banners->find('a', count($banners->find('a'))-1)->href
	$infoarray['bannners'] = getAllBanners($con, $tvdbid);

	//get all Fan art
	//$fanart = $html->find("#maincontent #fanart #content", 4);
	//$fanart->find('a', count($fanart->find('a'))-1)->href
	$infoarray['fanart'] = getAllFanart($con, $tvdbid);

	//get all posters
	//$posters = $html->find("#maincontent #fanart #content", 5);
	//$posters->find('a', count($posters->find('a'))-1)->href
	$infoarray['posters'] = getAllPosters($con, $tvdbid);

	//get Actors
	//$actors = $html->find("#maincontent #fanart #content", 6);
	//$actors->find('a', count($actors->find('a'))-1)->href
	$infoarray['actors'] = getAllActors($con, $tvdbid);

	//get all seasons
	//$seasons = $html->find("#maincontent #fanart #content", 2);
	//$seasons->find('a', count($seasons->find('a'))-1)->href
	$infoarray['episods'] = getAllSeasonEpisodes($con, $tvdbid);

	//print "<pre>";
	//print_r($infoarray);

	
return true;
	
}

	//----------------------------------------------------------------------------
	//Function to get all season episods
	function getAllSeasonEpisodes($con, $tvdbid){
		
		$url = 'http://thetvdb.com/?tab=seasonall&id='.$tvdbid;

		if($url=='') exit('No Url provided');
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		curl_close($curl);
		$html = str_get_html($result);

		$listtable = $html->find("#listtable", 0);
		$episodtr = $listtable->find('tr',0);
		$trcount = 0;

		$episodArr = array();

		$genQry ="select * from `On`.`tvdb_seasonepisodes` where showid = '{$tvdbid}'";
		$gresult = mysqli_query($con, $genQry);
		$genrowcount = mysqli_num_rows($gresult);

		if($genrowcount > 0){
			$delshowgenQry ="delete from `On`.`tvdb_seasonepisodes` where showid = '{$tvdbid}'";
			$sgresult = mysqli_query($con, $delshowgenQry);
		}

		foreach($listtable->find('tr') as $tr)
		{
			//print "<br>".$trcount;
			$trcount++;

			if($trcount == 1)
				continue;

			//print "<br>-----".$tdtotcount = count($tr->find('td'));

			$tdcount = 0;
			$tempArr = array();
			foreach ($tr->find('td') as $td) {
				//print "<br/>".$td->innerText();

				$tempArr['link'] = $tr->find(' td a',0)->href;

				$overview = addslashes(getMoredetailsAboutSeasonEpisode($con, $tr->find(' td a',0)->href));

				if($tdcount == 0){
					$tempArr['episodeno'] = $episodeno = $td->plaintext;
				}

				if($tdcount == 1){
					$tempArr['episodename'] = $episodename = $td->plaintext;
				}

				if($tdcount == 2){
					//print "<br/>:".$td->plaintext;
					//$tempArr['airdate'] = $airdate = ($td->plaintext)? date('Y-m-d',strtotime($td->plaintext)) : '0000:00:00';
					$tempArr['airdate'] = $airdate = (trim($td->plaintext) !='' && strtotime($td->plaintext)? date("'Y-m-d'",Strtotime($td->plaintext)):'NULL');
				}

				$tdcount++;
			}

			//$episodArr[] = $tempArr;

			//insert show record
			$geninsQry = "INSERT INTO `On`.`tvdb_seasonepisodes`(`id`, `showid`, `episodeno`,`name`, `overview`, `airdate`) VALUES (NULL, '{$tvdbid}', '{$episodeno}', '{$episodename}', '{$overview}', {$airdate})";
			$result = mysqli_query($con, $geninsQry);
		}

		$html->clear();

		return $episodArr;
	}
	//----------------------------------------------------------------------------
	//----------------------------------------------------------------------------
	//Function to get all banners
	function getAllBanners($con, $tvdbid){
		
		$url = 'http://thetvdb.com/?tab=seriesbanners&id='.$tvdbid;

		if($url=='') exit('No Url provided');
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		curl_close($curl);
		$html = str_get_html($result);

		$banners = $html->find("#maincontent #fanart #content", 1);

		$bannerArr = array();

		$genQry ="select * from `On`.`tvdb_banners` where showid = '{$tvdbid}'";
		$gresult = mysqli_query($con, $genQry);
		$genrowcount = mysqli_num_rows($gresult);

		if($genrowcount > 0){
			$delshowgenQry ="delete from `On`.`tvdb_banners` where showid = '{$tvdbid}'";
			$sgresult = mysqli_query($con, $delshowgenQry);
		}

		//print count($banners->find('table tr td a img[class=banner]'));

		foreach($banners->find('table tr td a img[class=banner]') as $eachbanner)
		{
			$bannerArr[] = $eachbanner->src;
			$imagepath = explode('/',$eachbanner->src);
			$imagename = $imagepath[count($imagepath) - 1];

			downloadImage($eachbanner->src, 'banners', $imagename);

			$geninsQry = "INSERT INTO `On`.`tvdb_banners`(`id`, `showid`, `imagename`) VALUES (NULL, '{$tvdbid}', '{$imagename}')";
			$result = mysqli_query($con, $geninsQry);
		}

		$html->clear();

		return $bannerArr;
	}
	//----------------------------------------------------------------------------
	//----------------------------------------------------------------------------
	//Function to get all fan art images
	function getAllFanart($con, $tvdbid){
		
		$url = 'http://thetvdb.com/?tab=seriesfanart&id='.$tvdbid;

		if($url=='') exit('No Url provided');
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		curl_close($curl);
		$html = str_get_html($result);

		$banners = $html->find("#maincontent #fanart #content", 1);

		$bannerArr = array();

		$genQry ="select * from `On`.`tvdb_fanart` where showid = '{$tvdbid}'";
		$gresult = mysqli_query($con, $genQry);
		$genrowcount = mysqli_num_rows($gresult);

		if($genrowcount > 0){
			$delshowgenQry ="delete from `On`.`tvdb_fanart` where showid = '{$tvdbid}'";
			$sgresult = mysqli_query($con, $delshowgenQry);
		}

		//print count($banners->find('table tr td a img[class=banner]'));
		foreach($banners->find('table tr td a img[class=banner]') as $eachbanner)
		{
			$bannerArr[] = $eachbanner->src;

			$imagepath = explode('/',$eachbanner->src);
			$imagename = $imagepath[count($imagepath) - 1];

			downloadImage($eachbanner->src, 'fanart', $imagename);

			$geninsQry = "INSERT INTO `On`.`tvdb_fanart`(`id`, `showid`, `imagename`) VALUES (NULL, '{$tvdbid}', '{$imagename}')";
			$result = mysqli_query($con, $geninsQry);
		}

		$html->clear();

		return $bannerArr;
		
	}
	//----------------------------------------------------------------------------
	//----------------------------------------------------------------------------
	//Function to get all fan art images
	function getAllPosters($con, $tvdbid){
		
		$url = 'http://thetvdb.com/?tab=seriesposters&id='.$tvdbid;

		if($url=='') exit('No Url provided');
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		curl_close($curl);
		$html = str_get_html($result);

		$banners = $html->find("#maincontent #fanart #content", 1);

		$bannerArr = array();

		$genQry ="select * from `On`.`tvdb_posters` where showid = '{$tvdbid}'";
		$gresult = mysqli_query($con, $genQry);
		$genrowcount = mysqli_num_rows($gresult);

		if($genrowcount > 0){
			$delshowgenQry ="delete from `On`.`tvdb_posters` where showid = '{$tvdbid}'";
			$sgresult = mysqli_query($con, $delshowgenQry);
		}

		//print count($banners->find('table tr td a img[class=banner]'));
		foreach($banners->find('table tr td a img[class=banner]') as $eachbanner)
		{
			$bannerArr[] = $eachbanner->src;

			$imagepath = explode('/',$eachbanner->src);
			$imagename = $imagepath[count($imagepath) - 1];

			downloadImage($eachbanner->src, 'posters', $imagename);

			$geninsQry = "INSERT INTO `On`.`tvdb_posters`(`id`, `showid`, `imagename`) VALUES (NULL, '{$tvdbid}', '{$imagename}')";
			$result = mysqli_query($con, $geninsQry);
		}

		$html->clear();

		return $bannerArr;
		
	}
	//----------------------------------------------------------------------------
	//----------------------------------------------------------------------------
	//Function to get all actors
	function getAllActors($con, $tvdbid){
		
		$url = 'http://thetvdb.com/?tab=actors&id='.$tvdbid;

		if($url=='') exit('No Url provided');
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		curl_close($curl);
		$html = str_get_html($result);

		$genQry ="select * from `On`.`tvdb_actors` where showid = '{$tvdbid}'";
		$gresult = mysqli_query($con, $genQry);
		$genrowcount = mysqli_num_rows($gresult);

		if($genrowcount > 0){
			$delshowgenQry ="delete from `On`.`tvdb_actors` where showid = '{$tvdbid}'";
			$sgresult = mysqli_query($con, $delshowgenQry);
		}

		$actors = $html->find("#maincontent #fanart #content", 1)->find('table',0);
		//print "Count:".count($actors->find('.infotable'));

		$bannerArr = array();
		for($a=0; $a<count($actors->find('.infotable'));$a++)
		{
			$tempArr = array();
			$tempArr['actorimg'] = $actors->find('.infotable img[class=banner]',$a)->src;

			$tempArr['actorlink'] = $actorlink = $actors->find('.infotable h2 a',$a)->href;
			$tempArr['actorName'] = $actorName = $actors->find('.infotable h2 a',$a)->innerText();
			
			$infotable = $actors->find('.infotable', $a);
			//print "<br/>".count($infotable->find('tr td',0));
			for($n=0; $n<count($infotable->find('tr td',0));$n++)
			{
				$actorinfo = $infotable->find('tr td',$n);
				$tempArr['imageby'] = $imageby = $actorinfo->find('a',1)->innerText();
				
				$innertext = $infotable->find('tr td',$n)->innerText();

				$howmanycharacters = strpos($innertext,'Image By:') - strpos($innertext,'</h2>');
				
				$role = str_replace('as ','',trim(substr($innertext, strpos($innertext,'</h2>'), $howmanycharacters)));
				$tempArr['role'] = $role = strip_tags($role);

				//$tempArr['role'] = str_replace('as ','',trim(substr($innertext, strpos($innertext,'</h2>'), $howmanycharacters)));

			}

			$bannerArr[] = $tempArr;

			$imagepath = explode('/',$actors->find('.infotable img[class=banner]',$a)->src);
			$imagename = $imagepath[count($imagepath) - 1];

			downloadImage($actors->find('.infotable img[class=banner]',$a)->src, 'actors', $imagename);

			$geninsQry = "INSERT INTO `On`.`tvdb_actors`(`id`, `showid`, `name`, `actorlink`,`role`, `image`, `imageby`) VALUES (NULL, '{$tvdbid}', '{$actorName}', '{$actorlink}', '{$role}', '{$imagename}', '{$imageby}')";
			$result = mysqli_query($con, $geninsQry);

		}

		$html->clear();

		return $bannerArr;
		
	}
	//----------------------------------------------------------------------------
	//function to download iamges
	function downloadImage($imageurl, $newpath, $imagename){
		$url = "http://thetvdb.com".$imageurl;
		$ch = curl_init($url);
		$fp = fopen('/var/www/html/www.showseeker.com/ezcelebrities/services/'.$newpath.'/'.$imagename, 'wb');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
		uploadFileToCloud($newpath, $imagename);
	}

	function uploadFileToCloud($newpath, $imagename)
	{
		$bucket = strtolower("showseeker/thetvdb/$newpath");
		$filepath = '/var/www/html/www.showseeker.com/ezcelebrities/services/'.$newpath.'/'.$imagename;

		//AMAZON
		require_once('/opt/s3/S3.php');
		//$awsAccessKey = 'AKIAJV3JKEBKML5Q7OVA';
		//$awsSecretKey = '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';
		$awsAccessKey = 'AKIAQBTJZ7I7SNKMVUOY';
		$awsSecretKey = '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';
		S3::setAuth($awsAccessKey, $awsSecretKey);

		/*
		if(filesize($filepath) < 15000){
			print "<br/>File size exceeds!";
		}
		else
		{
		//upload the file to amazon
		*/
		S3::putObject(S3::inputFile($filepath, false), $bucket, $imagename, S3::ACL_PUBLIC_READ);

		//remove the file after upload
		unlink($filepath); 
		//}
	}

	//---------------------------------------------------------------------------------------------

	function getMoredetailsAboutSeasonEpisode($con, $url){
		
		$url = "http://thetvdb.com".$url;
		if($url=='') exit('No Url provided');
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		curl_close($curl);
		$html = str_get_html($result);

		$overview = $html->find("#maincontent #datatable textarea[style!=display: none]", 0)->innerText();;
		$html->clear();

		return $overview;

	}

	
?>

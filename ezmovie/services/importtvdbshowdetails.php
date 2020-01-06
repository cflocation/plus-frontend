<?php
	require_once('../../config/mysqli.php');
	if( !ini_get('safe_mode') ){ 
		set_time_limit(0); 
		ini_set('max_execution_time', 0);
	}

	//ini_set("display_startup_errors", "On");
	//ini_set("display_errors", "On");

	//error_reporting(E_ALL);

	//get all shows from links table
	$con->select_db("On");
	$GLOBALS['tvdb_api'] = 'E9EB6DC2DB63B4D1';

	if(isset($_REQUEST['tvdbid'])){
		importShowDetails($con, $_REQUEST['tvdbid']);

		print $_REQUEST['tvdbid'];
	}
	else
	{
		$sql = 'SELECT tvdb
		FROM links WHERE tvdb!=0 AND tvdb NOT IN(SELECT showid FROM `tvdb_shows`)
		limit 30';
		$result = mysqli_query($con, $sql);

		$cnt = 0;
		while ($row = $result->fetch_assoc()) {
			print "<br>".$row['tvdb'];
			$tvdbid = $row['tvdb'];

			importShowDetails($con, $row['tvdb']);
			
			//
			//$ingeninsQry = "INSERT INTO `On`.`tvdb_showsloaded` (`id`, `showid`) VALUES (NULL, '{$tvdbid}')";
			//$inresult = mysqli_query($con, $ingeninsQry);

			$cnt++;
		}

		print "<br/> Total records processed:".$cnt;
	}
	

/*
0 4 * * 1 curl http://www.showseeker.com/ezshows/services/importtvdbshowdetails.php
TRUNCATE `tvdb_actors`;
TRUNCATE `tvdb_banners`;
TRUNCATE `tvdb_fanart`;
TRUNCATE `tvdb_genre`;
TRUNCATE `tvdb_posters`;
TRUNCATE `tvdb_seasonepisodes`;
TRUNCATE `tvdb_showgenre`;
TRUNCATE `tvdb_shows`;
TRUNCATE `tvdb_showsloaded`;

*/
	
function importShowDetails($con, $tvdbid)
{
	$apiid = $GLOBALS['tvdb_api'];

	$xmlstring = file_get_contents("http://thetvdb.com/api/{$apiid}/series/{$tvdbid}/all");
	$xml = simplexml_load_string($xmlstring);
	$json = json_encode($xml);
	$seriesAllarray = json_decode($json,TRUE);
	
	$series = $seriesAllarray['Series'];

	//print "<pre>";
	//print_r($series);

	$title = (isset($series['SeriesName']) && !is_array($series['SeriesName']))? addslashes($series['SeriesName']):'';
	
	$overview = (isset($series['Overview']) && !is_array($series['Overview']))? addslashes($series['Overview']):'';

	$firstAired = (isset($series['FirstAired']) && !is_array($series['FirstAired']))? date("'Y-m-d'", strtotime($series['FirstAired'])):'NULL';
	
	$airDay = (isset($series['Airs_DayOfWeek']) && !is_array($series['Airs_DayOfWeek']))? $series['Airs_DayOfWeek']:'';
	$airTime = (isset($series['Airs_Time']) && !is_array($series['Airs_Time']))? $series['Airs_Time']:'';
	$runtime = (isset($series['Runtime']) && !is_array($series['Runtime']))? $series['Runtime']:'';
	$network = (isset($series['Network']) && !is_array($series['Network']))? $series['Network']: '';

	$contentrating = (isset($series['ContentRating']) && !is_array($series['ContentRating']))? $series['ContentRating']:'';
	$imdbid = (isset($series['IMDB_ID']) && !is_array($series['IMDB_ID']))? $series['IMDB_ID']:'';
	$rating = (isset($series['Rating']) && !is_array($series['Rating']))? $series['Rating']:'';
	$ratingcount = (isset($series['RatingCount']) && !is_array($series['RatingCount']))? $series['RatingCount']:'';
	$seriesid = (isset($series['SeriesID']) && !is_array($series['SeriesID']))? $series['SeriesID']:'';
	$status = (isset($series['Status']) && !is_array($series['Status']))? $series['Status']:'';
	$zap2itid = (isset($series['zap2it_id']) && !is_array($series['zap2it_id']))? $series['zap2it_id']:'';

	//search for old show genre entry
	$showgenQry ="select * from `On`.`tvdb_showgenre` where showid = '{$tvdbid}'";
	$sgresult = mysqli_query($con, $showgenQry);
	$showgenrowcount = mysqli_num_rows($sgresult);

	if($showgenrowcount > 0){
		$delshowgenQry ="delete from `On`.`tvdb_showgenre` where showid = '{$tvdbid}'";
		$sgresult = mysqli_query($con, $delshowgenQry);
	}

	if(!is_array($series['Genre'])){
		$genre = explode('|',$series['Genre']);
		foreach($genre as $eachgenre){
			//insert or update genre master
			if($eachgenre !=''){
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
		$showQry = "update `On`.`tvdb_shows` SET `title`='{$title}', `description`='{$overview}', `firstaireddate`={$firstAired}, `airday`='{$airDay}', `airtime`='{$airTime}', `runtime`='{$runtime}', `network`='{$network}', `contentrating`='{$contentrating}', `imdbid` = '{$imdbid}', `rating` = '{$rating}', `ratingcount` = '{$ratingcount}', `seriesid` = '{$seriesid}', `status` = '{$status}', `zap2itid` = '{$zap2itid}' where `showid` = '{$tvdbid}'";
		$result = mysqli_query($con, $showQry);
	}
	else
	{
		//print "<br/>Inserting new show<br/>";
	
		$showQry = "INSERT INTO `On`.`tvdb_shows` (`id`, `showid`, `title`, `description`, `firstaireddate`, `airday`, `airtime`, `runtime`, `network`,`contentrating`, `imdbid`, `rating`, `ratingcount`, `seriesid`, `status`, `zap2itid`) VALUES (NULL, {$tvdbid}, '{$title}', '{$overview}', {$firstAired}, '{$airDay}', '{$airTime}', '{$runtime}', '{$network}','{$contentrating}', '{$imdbid}', '{$rating}', '{$ratingcount}', '{$seriesid}', '{$status}', '{$zap2itid}')";
		$result = mysqli_query($con, $showQry);
	}

	//insert all episodes
	if(isset($seriesAllarray['Episode']))
	{
		//print_r($seriesAllarray['Episode']);
		$episodes = $seriesAllarray['Episode'];

		$genQry ="select * from `On`.`tvdb_seasonepisodes` where showid = '{$tvdbid}'";
		$gresult = mysqli_query($con, $genQry);
		$genrowcount = mysqli_num_rows($gresult);

		if($genrowcount > 0){
			$delshowgenQry ="delete from `On`.`tvdb_seasonepisodes` where showid = '{$tvdbid}'";
			$sgresult = mysqli_query($con, $delshowgenQry);
		}

		foreach($episodes as $eachepisode){
			$seasonno = (isset($eachepisode['SeasonNumber']) && !is_array($eachepisode['SeasonNumber']))? $eachepisode['SeasonNumber']:'';
			$episodeno = (isset($eachepisode['EpisodeNumber']) && !is_array($eachepisode['EpisodeNumber']))? $eachepisode['EpisodeNumber']:'';

			$episodename = (isset($eachepisode['EpisodeName']) && !is_array($eachepisode['EpisodeName']))? addslashes($eachepisode['EpisodeName']):'';
			$episodeoverview = (isset($eachepisode['Overview']) && !is_array($eachepisode['Overview']))? addslashes($eachepisode['Overview']):'';
			
			$episoderating = (isset($eachepisode['Rating']) && !is_array($eachepisode['Rating']))? $eachepisode['Rating']:'';
			$episoderatingcount = (isset($eachepisode['RatingCount']) && !is_array($eachepisode['RatingCount']))? $eachepisode['RatingCount']:'';
			$episodewriter = (isset($eachepisode['Writer']) && !is_array($eachepisode['Writer']))? $eachepisode['Writer']:'';
			$episodeimdbid = (isset($eachepisode['IMDB_ID']) && !is_array($eachepisode['IMDB_ID']))? $eachepisode['IMDB_ID']:'';
			$episodedirector = (isset($eachepisode['Director']) && !is_array($eachepisode['Director']))? $eachepisode['Director']:'';
			
			$airdate = "NULL";

			if(isset($eachepisode['FirstAired']) && count($eachepisode['FirstAired']))
			$airdate = ($eachepisode['FirstAired'] !='' && strtotime($eachepisode['FirstAired']))? date("'Y-m-d'",Strtotime($eachepisode['FirstAired'])):'NULL';

			$geninsQry = "INSERT INTO `On`.`tvdb_seasonepisodes`(`id`, `showid`, `seasonno`, `episodeno`,`name`, `overview`, `airdate`, `rating`, `ratingcount`, `writer`, `imdbid`, `director`) VALUES (NULL, '{$tvdbid}', '{$seasonno}', '{$episodeno}', '{$episodename}', '{$episodeoverview}', {$airdate}, '{episoderating}', '{$episoderatingcount}', '{$episodewriter}', '{$episodeimdbid}', '{$episodedirector}')";
			$result = mysqli_query($con, $geninsQry);
		}
	}

	getAllBanners($con, $tvdbid);

	getAllActors($con, $tvdbid);
	
	return true;
	
}

	
	//----------------------------------------------------------------------------
	//----------------------------------------------------------------------------
	//Function to get all banners
	function getAllBanners($con, $tvdbid){
		
		$apiid = $GLOBALS['tvdb_api'];

		$xmlstring = file_get_contents("http://thetvdb.com/api/{$apiid}/series/{$tvdbid}/banners.xml");
		$xml = simplexml_load_string($xmlstring);
		$json = json_encode($xml);
		$allBanners = json_decode($json,TRUE);

		//print "<pre>";
		//print_r($allBanners);

		$bannerCount = 1;
		$posterCount = 1;
		$fanartCount = 1;

		if(isset($allBanners['Banner']))
		{
			foreach($allBanners['Banner'] as $eachbanner)
			{
				//print "<pre>";
			//	print_r($eachbanner);

				$imagetype = "";
				if(isset($eachbanner['BannerType'])){
					if($eachbanner['BannerType'] == "fanart"){
						if(isset($eachbanner['ThumbnailPath']))
						{
							$imagepath = explode('/',$eachbanner['ThumbnailPath']);
							$imagename = $imagepath[count($imagepath) - 1];

							$imagetype = "fanart";

							if($fanartCount == 1){
								$genQry ="select * from `On`.`tvdb_fanart` where showid = '{$tvdbid}'";
								$gresult = mysqli_query($con, $genQry);
								$genrowcount = mysqli_num_rows($gresult);

								if($genrowcount > 0){
									$delshowgenQry ="delete from `On`.`tvdb_fanart` where showid = '{$tvdbid}'";
									$sgresult = mysqli_query($con, $delshowgenQry);
								}
							}

							
							downloadImage($eachbanner['ThumbnailPath'], 'fanart', $imagename);

							$geninsQry = "INSERT INTO `On`.`tvdb_fanart`(`id`, `showid`, `imagename`) VALUES (NULL, '{$tvdbid}', '{$imagename}')";
							$result = mysqli_query($con, $geninsQry);

							$fanartCount++;
						}
					}

					if($eachbanner['BannerType'] == "poster"){
						if(isset($eachbanner['BannerPath']))
						{
							$imagepath = explode('/',$eachbanner['BannerPath']);
							$imagename = $imagepath[count($imagepath) - 1];

							$imagetype = "poster";
							
							if($posterCount == 1){
								$genQry ="select * from `On`.`tvdb_posters` where showid = '{$tvdbid}'";
								$gresult = mysqli_query($con, $genQry);
								$genrowcount = mysqli_num_rows($gresult);

								if($genrowcount > 0){
									$delshowgenQry ="delete from `On`.`tvdb_posters` where showid = '{$tvdbid}'";
									$sgresult = mysqli_query($con, $delshowgenQry);
								}
							}

							downloadImage($eachbanner['BannerPath'], 'posters', $imagename);

							$geninsQry = "INSERT INTO `On`.`tvdb_posters`(`id`, `showid`, `imagename`) VALUES (NULL, '{$tvdbid}', '{$imagename}')";
							$result = mysqli_query($con, $geninsQry);

							$posterCount++;
						}
					}

					if($eachbanner['BannerType'] == "series"){
						if(isset($eachbanner['BannerPath']))
						{
							$imagepath = explode('/',$eachbanner['BannerPath']);
							$imagename = $imagepath[count($imagepath) - 1];

							$imagetype = "series";

							if($bannerCount == 1){
								$genQry ="select * from `On`.`tvdb_banners` where showid = '{$tvdbid}'";
								$gresult = mysqli_query($con, $genQry);
								$genrowcount = mysqli_num_rows($gresult);

								if($genrowcount > 0){
									$delshowgenQry = "delete from `On`.`tvdb_banners` where showid = '{$tvdbid}'";
									$sgresult = mysqli_query($con, $delshowgenQry);
								}
							}

							downloadImage($eachbanner['BannerPath'], 'banners', $imagename);

							$geninsQry = "INSERT INTO `On`.`tvdb_banners`(`id`, `showid`, `imagename`) VALUES (NULL, '{$tvdbid}', '{$imagename}')";
							$result = mysqli_query($con, $geninsQry);

							$bannerCount++;
						}
					}
					
					if($eachbanner['BannerType'] == "season"){
						$imagetype = "season";
					}
				}
				

			}
		}
	

	}
	//----------------------------------------------------------------------------
	//----------------------------------------------------------------------------
	//Function to get all actors
	function getAllActors($con, $tvdbid)
	{
		//print "<br/>TVDBID: ".$tvdbid;

		$apiid = $GLOBALS['tvdb_api'];

		$xmlstring = file_get_contents("http://thetvdb.com/api/{$apiid}/series/{$tvdbid}/actors.xml");
		$xml = simplexml_load_string($xmlstring);
		$json = json_encode($xml);
		$allActors = json_decode($json,TRUE);

		$genQry ="select * from `On`.`tvdb_actors` where showid = '{$tvdbid}'";
		$gresult = mysqli_query($con, $genQry);
		$genrowcount = mysqli_num_rows($gresult);

		if($genrowcount > 0){
			$delshowgenQry ="delete from `On`.`tvdb_actors` where showid = '{$tvdbid}'";
			$sgresult = mysqli_query($con, $delshowgenQry);
		}

		$actorsArr = $allActors;
		if(isset($allActors['Actor'][0])){
			$actorsArr = $allActors['Actor'];
		}

		foreach($actorsArr as $eachactor)
		{
			if(isset($eachactor['Name']))
			{
				if(isset($eachactor['Image']) && count($eachactor['Image'])){
					$imagepath = explode('/',$eachactor['Image']);
					$imagename = $imagepath[count($imagepath) - 1];

					downloadImage($eachactor['Image'], 'actors', $imagename);
				}
				else
					$imagename = "";

				$actorName = ($eachactor['Name'] && !is_array($eachactor['Name']))? addslashes($eachactor['Name']):'';
				$actorlink = '';
				
				$role = ($eachactor['Role'] && !is_array($eachactor['Role']))? addslashes($eachactor['Role']):'';
				
				$imageby = '';

				$geninsQry = "INSERT INTO `On`.`tvdb_actors`(`id`, `showid`, `name`, `actorlink`,`role`, `image`, `imageby`) VALUES (NULL, '{$tvdbid}', '{$actorName}', '{$actorlink}', '{$role}', '{$imagename}', '{$imageby}')";
				$result = mysqli_query($con, $geninsQry);
			}
		}

		
		
	}
	//----------------------------------------------------------------------------
	//function to download iamges
	function downloadImage($imageurl, $newpath, $imagename){

		if(isset($imageurl)){
			$url = "http://thetvdb.com/banners/_cache/".$imageurl;
			$ch = curl_init($url);
			$fp = fopen('/var/www/html/www.showseeker.com/ezshows/services/'.$newpath.'/'.$imagename, 'wb');
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_exec($ch);
			curl_close($ch);
			fclose($fp);
			
			uploadFileToCloud($newpath, $imagename);
		}
	}

	function uploadFileToCloud($newpath, $imagename)
	{
		$bucket = strtolower("showseeker/thetvdb/$newpath");
		$filepath = '/var/www/html/www.showseeker.com/ezshows/services/'.$newpath.'/'.$imagename;

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
		//unlink($filepath); 
		//}
	}

	//---------------------------------------------------------------------------------------------
//following function was made to get extra details by scrapping
/*
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
*/
	
?>

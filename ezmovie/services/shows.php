<?php
	session_start();
	ini_set("display_errors",1);
	error_reporting(E_ALL);

	//session ids
	$corporationid = $_SESSION['corporationid'];
	$userid = $_SESSION['userid'];


	//if the corporation id is not set return
	if(!isset($corporationid)){
		return 0;
	}

	//are we posting or getting the event type
	if(isset($_GET['eventtype'])){
		$event = $_GET['eventtype'];
	}else{
		$event = $_POST['eventtype'];
	}

	//set the global date for inset update delete
	$d = date('Y-m-d H:i:s');


	include_once('../config/logs.php');   //Include logs db and basic functions
	include_once('../config/mysqli.php'); //include database


	if($event == "list"){
		$year = $_GET['year'];
		$re   = array();
		
		if(trim($year)=='nowshowing'){
			
			// $resp = file_get_contents('https://plusapi.showseeker.com/gridlist/networks/EST');
			// $data = json_decode($resp);
			// $nets = array();

			//Get networks.. only Eastern Cable Feed
			$opts    = array('http'=>array('method'=>"GET",'header'=>"Api-Key: 9399290eaf8e214eeebe834ae0c0fe4a\r\n" . "User: 152\r\n"));
			$context = stream_context_create($opts);
			$resp    = file_get_contents('https://plusapi.showseeker.com/zone/load/447', false, $context);
			$data    = json_decode($resp);
			$nets    = "";

			foreach ($data->networks as $n)
				$nets .= "{$n->networkid} ";
			
			$options = array('hostname' => 'solr.prod.showseeker.com','port' => 8983,'path' => 'solr/gracenote',);
			$client  = new SolrClient($options);
			$query 	 = new SolrQuery();
			$query->setQuery('*:*');
			$query->setRows(5000);

			//fields to include
			$query  ->addField('showid')
					->addField('title')
					->addField('genre')
					->addField('genre2')
					->addField('rating')
					->addField('language')
					->addField('year')
					->addField('country');
			//grouping
			$query->set("group", "true");
			$query->set("group.field", "showid");
			$query->set("group.ngroups", "true");

			//Sorting
			$query->addSortField('title',SolrQuery::ORDER_ASC);

			//Filter
			$query->addFilterQuery('projected:0');
			//$query->addFilterQuery('language:English');
			$query->addFilterQuery('showtype: "MV"');
			$query->addFilterQuery("stationnum:({$nets})");

			$qryResp    = $client->query($query);
			$response   = $qryResp->getResponse();
			$moviesList = $response->grouped->showid->groups;
			$uniqueIds  = array();
			$re         = array();

			foreach ($moviesList as &$row) {
				$movie = $row->doclist->docs[0];
				$tmsId = $movie->showid.'0000';				
				$links = getMovieLinks($con,$tmsId);
				
				if($links == false || in_array($links['id'], $uniqueIds)){
					continue;
				}

				$genre = array();

				if(trim($movie->genre) != '')
					$genre[]   = trim($movie->genre);
				
				if(trim($movie->genre2) != '')
					$genre[]   = trim($movie->genre2);
				
				unset($movie->genre2);

				//$movie->id    = $tmsId;
				$movie->tmsId = $tmsId;
				$movie->genre = implode(',', $genre);

				$movie       = array_merge((array)$movie,  $links);
				$re[]        = $movie;
				$uniqueIds[] = $links['id'];
			}
		} else {
			$sql    = "SELECT ProgramRootid.rootid AS id, IFNULL(ProgramTitle.title, Program.title) AS title, ProgramRating.code AS rating, ProgramRootid.connectorId AS tmsId, GROUP_CONCAT(DISTINCT CommonGenre.genre) AS genre,
						IFNULL(MovieLink.futon,'') AS futon,
						IFNULL(MovieLink.facebook,'') AS facebook,
						IFNULL(MovieLink.twitter,'') AS twitter,
						IFNULL(MovieLink.wiki,'') AS wiki,
						IFNULL(MovieLink.imdb,'') AS imdb,
						IFNULL(MovieLink.instagram,'') AS instagram,
						IFNULL(MovieLink.pintrest,'') AS pintrest,
						IFNULL(MovieLink.youtube,'') AS youtube,
						IFNULL(MovieLink.theMovieDB,'') AS theMovieDB,
						IFNULL(MovieLink.networkurl,'') AS networkurl,
						IFNULL(MovieLink.rottentomatoes,'') AS rottentomatoes,
						IFNULL(DATE_FORMAT(MovieLink.createdat,' %Y-%m-%d'),'') AS createdat,
						IFNULL(DATE_FORMAT(MovieLink.updatedat,' %Y-%m-%d'),'') AS updatedat,
						IFNULL(ProgramCountry.country,'') AS country,
						LEFT(dateyear,4) AS year
						FROM ProgramDate
						INNER JOIN Program ON Program.connectorId = ProgramDate.connectorId
						INNER JOIN ProgramRootid ON ProgramRootid.connectorId = ProgramDate.connectorId						
						LEFT JOIN ProgramTitle ON ProgramTitle.connectorId = Program.connectorId AND ProgramTitle.type = 'full' AND ProgramTitle.lang = 'en'
						LEFT JOIN ProgramDescription ON ProgramDescription.connectorId = ProgramDate.connectorId AND ProgramDescription.size = 60 AND ProgramDescription.lang = 'en'
						LEFT JOIN ProgramRating ON ProgramRating.connectorId = ProgramDate.connectorId 
						LEFT OUTER JOIN MovieLink ON MovieLink.rootId = ProgramRootid.rootid
						INNER JOIN ProgramCountry ON ProgramCountry.tmsId = Program.connectorId
						LEFT JOIN ProgramGenre ON ProgramGenre.connectorId = ProgramRootid.connectorId
						LEFT JOIN CommonGenre ON CommonGenre.genreId = ProgramGenre.genreId AND  CommonGenre.lang = 'en'
						WHERE LEFT(dateyear,4) = $year AND ProgramDate.type = 'Year' AND (ProgramCountry.country = 'USA' OR ProgramCountry.country = 'ZAF') AND ProgramRating.code IN ('R','PG','G','PG-13')
						
						GROUP BY ProgramRootid.rootid
						ORDER BY Program.title";
			$result = mysqli_query($con, $sql);

			while ($row = $result->fetch_assoc()) {
					$row['genre'] = strtolower(str_replace(' ', '-',$row['genre']));
					$re[]		  = array_map('utf8_encode', $row);
			}
		}

    	header('Content-Type: application/json');
    	print json_encode($re);
		return;
	}



	if($event == "listshowsfromid"){
		$title = $_GET['title'];
		$title = str_replace(', THE', '', $title);
		$title = $con->real_escape_string($title);


		$con->select_db("On");
		$sql = "SELECT title, showcardId AS id FROM onShowcardTitles WHERE title LIKE '%$title%' GROUP BY id ORDER BY title";
		$result = mysqli_query($con, $sql);

		if($result->num_rows == 0){
			print 0;
			return;
		}

   		while ($row = $result->fetch_assoc()) {
   			$re[] = $row;
    	}

    	print json_encode($re);
		return;
	}



	if($event == "setshowid"){
		$con->select_db("Yoda");
		$showcardid = $con->real_escape_string($_POST['showcardid']);
		$futonid = $con->real_escape_string($_POST['futonid']);

		$sql = "UPDATE futoncritic_showwatchcatalog SET showcardid = $showcardid WHERE showatch_url = '$futonid'";
		$result = mysqli_query($con, $sql);


		print_r($sql);

		return;
	}	

	

	if($event == "updatelinks"){
		$con->select_db("On");
		$rootId   = $_POST['rootId'];
		$updates  = $_POST['updates'];
		$facebook = $con->real_escape_string($_POST['facebook']);
		$twitter  = $con->real_escape_string($_POST['twitter']);
		$wiki     = $con->real_escape_string($_POST['wiki']);
		
		$youtube    = $con->real_escape_string($_POST['youtube']);
		$networkurl = $con->real_escape_string($_POST['networkurl']);
		$imdb       = $con->real_escape_string($_POST['imdb']);
		$instagram  = $con->real_escape_string($_POST['instagram']);
		
		$pintrest       = $con->real_escape_string($_POST['pintrest']);
		$rottentomatoes = $con->real_escape_string($_POST['rottentomatoes']);
		$theMovieDB     = $con->real_escape_string($_POST['theMovieDB']);

		$sql    = "SELECT rootId FROM MovieLink WHERE rootId = '$rootId' LIMIT 1";
		$result = mysqli_query($con, $sql);

		if(!(is_array($updates)) || count($updates) == 0){
			print json_encode(array("result"=>false,"error"=>"Nothing to update"));
			return;
		}

		if($result->num_rows == 0){
			$sql    = "INSERT INTO MovieLink (rootId, theMovieDB, rottentomatoes, pintrest, instagram, imdb, networkurl, facebook, twitter, wiki, youtube, createdat, updatedat) VALUES 
						($rootId,'$theMovieDB','$rottentomatoes','$pintrest','$instagram','$imdb','$networkurl','$facebook','$twitter','$wiki','$youtube','$d','$d')";
			$result = mysqli_query($con, $sql);
			
			$remarks = [];
			foreach($updates as $u)
				$remarks[$u] = $_POST[$u];
			$remarks = json_encode($remarks);
			ezMovieLogEvent($logDb,$rootId,$userid,1,$updates,$remarks);

		} else {
			$sql    = "UPDATE MovieLink SET 
						imdb = '$imdb',
						theMovieDB = '$theMovieDB',
						facebook = '$facebook',
						twitter = '$twitter',
						instagram = '$instagram',
						wiki = '$wiki',
						networkurl = '$networkurl',
						youtube = '$youtube',
						pintrest = '$pintrest',
						rottentomatoes = '$rottentomatoes',
						updatedat = '$d'
					    WHERE rootId = $rootId";
			$result = mysqli_query($con, $sql);

			$remarks = [];
			foreach($updates as $u)
				$remarks[$u] = $_POST[$u];
			$remarks = json_encode($remarks);
			ezMovieLogEvent($logDb,$rootId,$userid,0,$updates,$remarks);
		}
		
		print json_encode(array("result"=>$result));
		return;
	}



function getMovieLinks($con,$tmsid){
	$sql ="SELECT ProgramRootid.rootid AS id, 
	            IFNULL(MovieLink.futon,'') AS futon,
				IFNULL(MovieLink.facebook,'') AS facebook,
				IFNULL(MovieLink.twitter,'') AS twitter,
				IFNULL(MovieLink.wiki,'') AS wiki,
				IFNULL(MovieLink.imdb,'') AS imdb,
				IFNULL(MovieLink.instagram,'') AS instagram,
				IFNULL(MovieLink.pintrest,'') AS pintrest,
				IFNULL(MovieLink.youtube,'') AS youtube,
				IFNULL(MovieLink.theMovieDB,'') AS theMovieDB,
				IFNULL(MovieLink.networkurl,'') AS networkurl,
				IFNULL(MovieLink.rottentomatoes,'') AS rottentomatoes,
				IFNULL(DATE_FORMAT(MovieLink.createdat,' %Y-%m-%d'),'') AS createdat,
				IFNULL(DATE_FORMAT(MovieLink.updatedat,' %Y-%m-%d'),'') AS updatedat
				FROM ProgramRootid
				LEFT OUTER JOIN MovieLink ON MovieLink.rootId = ProgramRootid.rootid
				WHERE ProgramRootid.connectorId='$tmsid'
				GROUP BY ProgramRootid.rootid LIMIT 1";
	$res = mysqli_query($con, $sql);

	if($res->num_rows > 0){
		return $res->fetch_assoc();
	} else {
		return false;
		/*return array('futon'=>'',
					'facebook'=>'',
					'twitter'=>'',
					'wiki'=>'',
					'imdb'=>'',
					'instagram'=>'',
					'pintrest'=>'',
					'youtube'=>'',
					'theMovieDB'=>'',
					'networkurl'=>'',
					'rottentomatoes'=>'',
					'dateadded'=>''
				 );*/
	}
}

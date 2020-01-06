<?php
	session_start();
	ini_set("display_errors",0);

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


	//include database
	include_once('../../config/mysqli.php');


	if($event == "list"){
		$con->select_db("On");
		$sql = 'SELECT 
		onShowcardTitles.showcardId AS id, 
		onShowcardTitles.title,
		IFNULL(links.futon,"") AS futon,
		IFNULL(links.tvdb,"") AS tvdb,
		IFNULL(links.facebook,"") AS facebook,
		IFNULL(links.twitter,"") AS twitter,
		IFNULL(links.wiki,"") AS wiki,
		IFNULL(links.tvcom,"") AS tvcom,
		IFNULL(links.imdb,"") AS imdb,
		IFNULL(links.instagram,"") AS instagram,
		IFNULL(links.pintrest,"") AS pintrest,
		IFNULL(links.networkurl,"") AS networkurl,
		IFNULL(links.rottentomatoes,"") AS rottentomatoes,
		IFNULL(DATE_FORMAT(links.updatedat," %Y-%m-%d"),"") AS dateadded
		FROM onShowcardTitles
		LEFT OUTER JOIN links ON links.showcardId = onShowcardTitles.showcardId
		WHERE onShowcardTitles.type = "full" 
		ORDER BY onShowcardTitles.title';
		$result = mysqli_query($con, $sql);

   		while ($row = $result->fetch_assoc()) {
   			$re[] = $row;
    	}

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
		$showcardId = $_POST['showid'];
		$futon = $_POST['futon'];
		$tvdb = $_POST['tvdb'];
		$facebook = $_POST['facebook'];
		$twitter = $_POST['twitter'];
		$wiki = $_POST['wiki'];
		$networkurl = $_POST['networkurl'];
		$tvcom = $_POST['tvcom'];
		$imdb = $_POST['imdb'];
		$instagram = $_POST['instagram'];
		$pintrest = $_POST['pintrest'];
		$rottentomatoes = $_POST['rottentomatoes'];
		


		$con->select_db("On");
		$sql = "SELECT * FROM links WHERE showcardId = '$showcardId' LIMIT 1";
		$result = mysqli_query($con, $sql);



		if($result->num_rows == 0){
			$sql = "INSERT INTO links (showcardId, rottentomatoes, pintrest, instagram, imdb, tvcom, networkurl, futon, tvdb, facebook, twitter, wiki, createdat, updatedat) VALUES ($showcardId,'$rottentomatoes','$pintrest','$instagram','$imdb','$tvcom','$networkurl','$futon','$tvdb','$facebook','$twitter','$wiki','$d','$d')";
			

			print_r($sql);
			$result = mysqli_query($con, $sql);
			return;
		}


		$sql = "UPDATE links 
				SET futon = '$futon',
				tvcom = '$tvcom',
				tvdb = '$tvdb',
				imdb = '$imdb',
				facebook = '$facebook',
				twitter = '$twitter',
				instagram = '$instagram',
				wiki = '$wiki',
				networkurl = '$networkurl',
				pintrest = '$pintrest',
				rottentomatoes = '$rottentomatoes'
				WHERE showcardId = $showcardId";

		print_r($sql);

		$result = mysqli_query($con, $sql);

		//import the tvdb show details from the tvdb show id
		return;
	}



?>









<?php
	session_start();
	ini_set("display_errors",0);

	//connect to 
	$dbPrograms = mysql_connect("db2.showseeker.net","vastsupport1","cP7qRiSvaR2M");
		if (!$dbPrograms)
  	{
  		die('Could not connect: ' . mysql_error());
  	}

	//select the table
	mysql_select_db("Programs", $dbPrograms);
	mysql_query("SET NAMES 'utf8'", $dbPrograms);
	mysql_query("SET CHARACTER_SET 'utf8'", $dbPrograms);


	//session ids
	$corporationid = $_SESSION['corporationid'];
	$userid = $_SESSION['userid'];


	//if the corporation id is not set return
	if(!isset($corporationid)){
		return 0;
	}

	if(!isset($userid)){
		return 0;
	}

	//are we posting or getting the event type
	if(isset($_GET['eventtype'])){
		$event = $_GET['eventtype'];
	}else{
		$event = $_POST['eventtype'];
	}


	if(isset($_GET['nets'])){
		$nets = $_GET['nets'];
	}else{
		$nets = $_POST['nets'];
	}

	$netsArray = explode(',',$nets);

	if($event == "title"){
		$str 	= addslashes($_GET['str']);
		$kw 	= $_GET['str'];
		$words 	= explode(" ", $kw);
		//$q 		= 'titlesearch:(';

		$numItems = count($words);
		$i = 0;
		
		
		$re = "&fq=";
		foreach($netsArray as &$val){
			$re .= 'stationnum:' . $val . '+';
		};
		
		$q 		= '';
		
		foreach ($words as &$word){
			$q 	.= '&fq='.urlencode('titlesearch:(*'.$word.'* OR '.$word.')');
		}
		
		$resultNew = file_get_contents('http://solr.showseeker.net:8983/solr/gracenote/select?q=*%3A*'.$q.$re.'&fl=title&wt=json&indent=true&group=true&group.field=title&rows=1000');
		$resultNew = json_decode($resultNew);
		

		foreach ($resultNew->grouped->title->groups as &$value) {
			$title = $value->doclist->docs[0]->title;
			$id = md5($title);
			$row = array('id' => $id, 'title' => $title);

			$data[] = $row;
		}

		$tmp = array ();

		foreach ($data as $row) 
		    if (!in_array($row,$tmp)) array_push($tmp,$row);


		function cmp($a, $b){
			return strcmp($a["title"], $b["title"]);
		}

		usort($tmp, "cmp");



		$re = json_encode($tmp);
	    print $re;




	    return;



		$sql = "SELECT DISTINCT Program.title, md5(Program.title) AS id
			FROM Program
			INNER JOIN ProgramRating ON ProgramRating.connectorId = Program.connectorId AND ProgramRating.area = 'United States'
			WHERE ProgramRating.code IN ('R','PG','G','PG-13') AND Program.title LIKE '%$str%'
			UNION DISTINCT
			SELECT DISTINCT Program.title, md5(Program.title) AS id
			FROM Program 
			WHERE LEFT(connectorId,2) = 'SH' AND Program.title LIKE '%$str%'
			ORDER BY title
			LIMIT 170";

		$result = mysql_query($sql);

		$cnt = mysql_num_rows($result);

		if($cnt == 0){
	    	print 0;
			return;
		}

		//loop over and add to list
	    while($row = mysql_fetch_assoc($result)) {
	        $data[] = $row;
	    }

	    $re = json_encode($data);
	    print $re;

		return;
	}

?>
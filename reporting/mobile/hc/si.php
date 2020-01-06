<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);

	$today = date('Y-m-d');
	$con = mysqli_connect("61b1ed95616f65903ff311cc9decad51aa4cac3d.rackspaceclouddb.com","vastdb","VastPlus#01","logs");
	//$sql = "SELECT * FROM `eventlogs` WHERE `eventslogid` = 5 and createdat like '{$today}%' ORDER BY createdat";
	$sql = "SELECT * FROM `eventlogs` WHERE `eventslogid` = 5 ORDER BY createdat DESC LIMIT 0 , 5000";
	$search = mysqli_query($con, $sql);
	$reportdate = date("F j, Y");  

	//CLEAN OUT TRENDS TABLE
	$clean = "TRUNCATE TABLE searches";
	$clear = mysqli_query($con, $clean);

while ($row = mysqli_fetch_assoc($search)) {;

	$info = $row['request'];
	$si = json_decode($info);

	$searchType =  $si->searchType ;
	$searchKeywords = $si->searchKeywordsArray;
	$searchTitle = $si->searchTitlesArray;
	$keywords = count ($searchKeywords);
	$titles = count ($searchTitle);

	
	if (($searchType == 'keyword' ) AND ($keywords != NULL)){
		$k = 0;
		//echo "<hr>KEYWORDS: <br>" ;
			while ( $k < $keywords ) {
				$type1 = 1;
				$data1 = ($searchKeywords[$k]->title);
				$keywords = "INSERT INTO searches (type,data,createdat) VALUES ('{$type1}', '{$data1}', '{$today}' )";
				$keywords_log = mysqli_query($con, $keywords);
			$k ++;
		}
	}

	else if (($searchType == 'title' ) AND ($titles != NULL)) {
		//echo "<hr>TITLES: <br>";
		$t = 0;
			while ( $t < $titles ) {
				$type2 = 2;
				$data2 = ($searchTitle[$t]->title) ;
				$titles = "INSERT INTO searches (type,data,createdat) VALUES ('{$type2}', '{$data2}', '{$today}' )";
				$titles_log = mysqli_query($con, $titles);
			$t ++;
		}
	}
	
	else {
		//echo $searchType ."<br>" ; 
	}

}
header('Location: http://http://managed.showseeker.com/reporting/mobile/hc/trends.php');?>

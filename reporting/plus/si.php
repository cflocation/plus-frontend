<?php
	include 'db.php' ; 

//UPDATE THESE TO DETERMINE THE SEARCH SCRAPE DATE RANGE

	$datefrom = "2016-05-30 01:00:00";
	$dateto = "2016-06-30 23:59:00";

	$today = date('Y-m-d');
	$final_sql = "SELECT * FROM `eventlogs` WHERE `eventslogid` = 5 AND (createdat BETWEEN '$datefrom' AND '$dateto') and USERID NOT IN (147, 148, 149, 220, 151, 152, 153, 154, 155, 160, 742, 1177, 1187, 1188, 1189, 1190, 1191, 2022, 2136, 2261, 2339, 2344, 2345, 2347, 2407, 2435)  ORDER BY createdat DESC";

echo $final_sql;


	$search = mysqli_query($con, $final_sql);
	$reportdate = date("F j, Y");  

	//CLEAN OUT TRENDS TABLE, IF NEEDED
	//$clean = "TRUNCATE TABLE searches";
	//$clear = mysqli_query($con, $clean);

while ($row = mysqli_fetch_assoc($search)) {;

	$info = $row['request'];
	$si = json_decode($info);

	$searchdat = $row['createdat'];
	$userid = $row['userid'];

	$searchType =  $si->searchType ;
	$searchKeywords = $si->searchKeywordsArray ;
	$searchTitle = $si->searchTitlesArray ;
	$searchActors = $si->searchActorsArray ;

	$keywords = count ($searchKeywords);
	$titles = count ($searchTitle);
	$actors =  count ($searchActors);

	
	if (($searchType == 'keyword' ) AND ($keywords != NULL)){
		$k = 0;
		//echo "<hr>KEYWORDS: <br>" ;
			while ( $k < $keywords ) {
				$type1 = 1;
				$data1 = ($searchKeywords[$k]->title);
				$keywords = "INSERT IGNORE INTO searches (type,data,userid,createdat) VALUES ('{$type1}', '{$data1}', '{$userid}', '{$searchdat}' )";
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
				$titles = "INSERT IGNORE INTO searches (type,data,userid,createdat) VALUES ('{$type2}', '{$data2}', '{$userid}', '{$searchdat}' )";
				$titles_log = mysqli_query($con, $titles);
			$t ++;
		}
	}

	else if (($searchType == 'actor' ) AND ($actors != NULL)) {
		//echo "<hr>ACTORS: <br>";
		$a = 0;
			while ( $a < $actors ) {
				$type3 = 3;
				$data3 = ($searchActors[$a]->title) ;
				$actors = "INSERT IGNORE INTO searches (type,data,userid,createdat) VALUES ('{$type3}', '{$data3}', '{$userid}', '{$searchdat}' )";
				$actors_log = mysqli_query($con, $actors);
			$a ++;
		}
	}

}
//header('Location: http://managed.showseeker.com/reporting/plus/');?>

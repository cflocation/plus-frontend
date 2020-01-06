<?php
	session_start();
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);
	$con = mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","ShowSeeker");

	if (isset($_GET['UID'])) { $userid = $_GET['UID'] ; }

	$proposal_sql = "SELECT id, proposal, createdat from proposals where userid = $userid order by createdat desc limit 0, 10"; 
	$user_sql = "SELECT tokenid from users where id = $userid" ; 

	$proposals = mysqli_query($con, $proposal_sql);
	$users = mysqli_query($con, $user_sql);

	$row2 = mysqli_fetch_array($users) ;
	$tokenid = $row2['tokenid'] ; 

	$shows = array();

	while ($row1 = mysqli_fetch_array($proposals)) { ; 
	$proposalid = $row1['id'] ;



	$call = "http://services.showseeker.com/userproposal.php?proposalid={$proposalid}&userid={$userid}&tokenid={$tokenid}";
	$json_data		= file_get_contents($call);
	$resJson = json_decode($json_data);

	foreach($resJson->proposal AS $zone){
		foreach($zone->lines AS $line){
			$sn = $line->title ; 
			$shows[] =  $sn ; 
		}
	}
}

//echo implode("<br>",$shows);

sort($shows);
$final = array_unique($shows) ;
foreach ($final as $key => $val) {
    echo $val . "<br>";
}


?>
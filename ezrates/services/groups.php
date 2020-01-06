<?php
	include_once('../../inc/permissions.php');

	//are we posting or getting the event type
	if(isset($_GET['eventtype'])){
		$event 		= $_GET['eventtype'];
		$marketid 	= $_GET['marketid'];
	}
	else{
		$event 		= $_POST['eventtype'];
		$marketid 	= $_POST['marketid'];
	}


	if($event == "list"){

		$sql = "SELECT name FROM `ratecard_cards` where marketid = {$marketid} and deletedat is null group by name order by name";
		$result = mysql_query($sql);
		
	    //loop over and add to list
	    while($row = mysql_fetch_assoc($result)) {
	    	$data[] = $row;
	    }

	    //build the return
	   	$re = array("data"=>$data);

	   	//print the output
	    print json_encode($re);
		return;
	}


?>









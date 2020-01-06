<?php
	session_start();

	$tmsId = trim($_GET['showid']);

	if($tmsId != ''){
		/*
			$sql    = "SELECT connectorId FROM ProgramRootid WHERE rootid = $rootId LIMIT 1";
			$result = mysqli_query($con, $sql);
			$row    = $result->fetch_assoc();
			$tmsId  = $row['connectorId'];
		*/

		$opts    = array('http'=>array('method'=>"GET", 'header'=>"Api-Key: 9399290eaf8e214eeebe834ae0c0fe4a\r\n" . "User: 152\r\n" . "User-Agent: MyAgent/1.0\r\n"));
		$context = stream_context_create($opts);
		$info    = file_get_contents('https://plusapi.showseeker.com/show/'.substr($tmsId,0, -4).'/nocache', false, $context);
		
		header('Content-Type: application/json');
		print $info;
	}

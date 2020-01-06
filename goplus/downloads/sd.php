<?php

		//get month and year to pass to calendar

		$month =  date("n");
		$year = date("y");

		//Getting variable values

		$proposalid		= urldecode(trim($_GET['proposalid']));
		$userid			= urldecode(trim($_GET['userid']));
		$tokenid		= urldecode(trim($_GET['tokenid']));
		$sort1			= urldecode(trim($_GET['sort1']));
		$sort2			= urldecode(trim($_GET['sort2']));
		$sort3			= urldecode(trim($_GET['sort3']));				

		$hiderate		= urldecode(trim($_GET['hiderates']));
		$includelogos	= urldecode(trim($_GET['logos']));
		$includedesc	= urldecode(trim($_GET['description']));
		$includenew		= urldecode(trim($_GET['includenew']));
		$includetc		= urldecode(trim($_GET['addterms']));
		$onlyfixed		= urldecode(trim($_GET['onlyfixed']));
		$showratecard	= urldecode(trim($_GET['showratecard']));
		
		
		$call = "http://services.showseeker.com/userproposal.php?proposalid={$proposalid}&userid={$userid}&tokenid={$tokenid}&sort1={$sort1}&sort2={$sort2}&sort3={$sort3}";
		$json_data		= file_get_contents($call);
?>
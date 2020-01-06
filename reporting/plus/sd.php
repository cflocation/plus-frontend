<?php

		//Getting variable values

		//$hiderate		= urldecode(trim($_GET['hiderates']));
		//$includelogos	= urldecode(trim($_GET['logos']));
		//$includedesc	= urldecode(trim($_GET['description']));
		//$includenew		= urldecode(trim($_GET['includenew']));
		//$includetc		= urldecode(trim($_GET['addterms']));
		//$onlyfixed		= urldecode(trim($_GET['onlyfixed']));
		$proposalid		= urldecode(trim($_GET['proposalid']));
		//$showratecard	= urldecode(trim($_GET['showratecard']));
		//$sort1			= urldecode(trim($_GET['sort1']));
		//$sort2			= urldecode(trim($_GET['sort2']));
		//$sort3			= urldecode(trim($_GET['sort3']));				
		$tokenid		= urldecode(trim($_GET['tokenid']));
		$userid			= urldecode(trim($_GET['userid']));
		
		$call = "http://services.showseeker.com/userproposal.php?proposalid={$proposalid}&userid={$userid}&tokenid={$tokenid}";
		$json_data		= file_get_contents($call);
	
?>
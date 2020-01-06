<?php
	$u_agent = $_SERVER['HTTP_USER_AGENT'];	
	if(preg_match('/MSIE 7.0/i',$u_agent) && preg_match('/compatible/i',$u_agent)){
		print_r("Bad");
		return;
	}

	print "Good";
?>



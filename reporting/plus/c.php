<?php
	session_start();
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);



	$call = "http://stvplus.com/api/ratings/cable_top/filter/current/type/total";

	$test = $.ajax({url: "http://stvplus.com/api/ratings/cable_top/filter/current/type/total"});
	print_r ($test);
?>


<?php
	//set the errors
	ini_set('display_errors', TRUE);

	//inc the url parser
	include_once('../../inc/geturl.php');

	//set the url to get
	$url = $_GET['url'];

	//call the search event
	$search = getUrl($url);

	//print the results
	print $search;

	//exit page
	return;
?>
<?php
	session_start();
	ini_set("display_errors",1);

	$userid = $_COOKIE['userid'];
	$tokenid = $_COOKIE['tokenid'];

	//if there is anything blank return an error
	if(empty($tokenid) || empty($userid)){
		exit('error');
	}

	//posted
	$url = $_GET["url"];

	//get the file contents
	$data = file_get_contents($url);
	$ids = explode(",", $data);

	$arr = '&fq=';
	
	foreach($ids as $value){
		$arr.='id:'.$value.'+';
	}
	$arr.='ffffff';
	         
	$solr = 'http://solr.prod.showseeker.com:8983/solr/gracenote/select/?q=*%3A*&version=2.2&start=0&indent=on&wt=json&rows=5000&fq=-sort:"Paid Programming"';
	//$solr = 'http://solr.showseeker.net:8983/solr/gracenote/select/?q=*%3A*&version=2.2&start=0&indent=on&wt=json&rows=5000&fq=-sort:"Paid Programming"';
	$solr.= $arr;
		
	print $solr;

?>
<?php
	//connect to the database
	//$con = mysql_connect("plusdb.showseeker.net","devDBAUserSSDB","L8YRtuK7n8xQR8FJ8bPChyvKvXSLZC7waCK37T28BXW");
	$con = mysql_connect("db0.showseeker.net","devDBAUserSSDB","L8YRtuK7n8xQR8FJ8bPChyvKvXSLZC7waCK37T28BXW");	

	if (!$con){
		die('Could not connect: ' . mysql_error());
	}
	
	//select the table
	mysql_select_db("ShowSeeker", $con);
?>
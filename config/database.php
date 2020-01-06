<?php
	//connect to the database
	//$con = mysql_connect("61b1ed95616f65903ff311cc9decad51aa4cac3d.rackspaceclouddb.com","vastdbuser","jK6YK71tJ");
	$con = mysql_connect("db4.showseeker.net","devDBAUserSSDBOR","avcZ5j26yU4EyqB66RmfcjfuPGwDkBLUNnZe8MM2UBuw3k");
	
	if (!$con){
  		die('Could not connect: ' . mysql_error());
  	}

	//select the table
	mysql_select_db("ShowSeeker", $con);

	mysql_query("SET NAMES 'utf8'", $con);
	mysql_query("SET CHARACTER_SET 'utf8'", $con);
?>
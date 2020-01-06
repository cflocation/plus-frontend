<?php
	//connect to the database
	$con = mysql_connect("db2.showseeker.net","devDBAUser01","DZfGzWuyH63WSJerYKVN9zeKwnwGnPU9");
	
	if (!$con){
  		die('Could not connect: ' . mysql_error());
  	}

	//select the table
	mysql_select_db("ShowSeeker", $con);

	mysql_query("SET NAMES 'utf8'", $con);
	mysql_query("SET CHARACTER_SET 'utf8'", $con);
?>
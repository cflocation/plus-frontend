<?php
	//connect to the database
	$con = mysql_connect("61b1ed95616f65903ff311cc9decad51aa4cac3d.rackspaceclouddb.com","vastdbuser","jK6YK71tJ");
		if (!$con)
  	{
  		die('Could not connect: ' . mysql_error());
  	}

	//select the table
	mysql_select_db("ShowSeeker", $con); 
?>
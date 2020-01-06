<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);
	session_start();
?>
<?php

//	$con = mysqli_connect("61b1ed95616f65903ff311cc9decad51aa4cac3d.rackspaceclouddb.com","vastdb","VastPlus#01","Lineups");
	$con = mysqli_connect("db4.showseeker.net","vastdb","VastPlus#01","Lineups");

	if (isset($_GET['z'])) {
		$zipType = $_GET['z'] ;

		switch ($zipType) {
			case 0:
				$sql = "00000 AND 09999" ; break;

			case 1:
				$sql = "10000 AND 19999" ; break;
			
			case 2:
				$sql = "20000 AND 29999" ; break;
			
			case 3:
				$sql = "30000 AND 39999" ; break;
			
			case 4:
				$sql = "40000 AND 49999" ; break;
			
			case 5:
				$sql = "50000 AND 59999" ; break;
			
			case 6:
				$sql = "60000 AND 69999" ; break;
			
			case 7:
				$sql = "70000 AND 79999" ; break;
			 
			case 8:
				$sql = "80000 AND 89999" ; break;
			
			case 9:
				$sql = "90000 AND 99999" ; break;


		}
		$zips_sql = "SELECT zipcode, city, state from zipcodes where zipcode between $sql";
		$zipssql = mysqli_query($con, $zips_sql);
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//cdn.jsdelivr.net/foundation/5.4.3/css/normalize.css">
<link rel="stylesheet" href="//cdn.jsdelivr.net/foundation/5.4.3/css/foundation.css">
<script src="//cdn.jsdelivr.net/foundation/5.4.3/js/vendor/modernizr.js"></script>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<?php include 'top.php'; ?>
<div class="row">
	<div class="small-10 large-centered columns"><center><h3>ShowSeeker ZipCode Tool</h3></center>
		<ul class="inline-list">
		  <li><a href="index.php?z=0">00000-09999</a></li>
		  <li><a href="index.php?z=1">10000-19999</a></li>
		  <li><a href="index.php?z=2">20000-29999</a></li>
		  <li><a href="index.php?z=3">30000-39999</a></li>
		  <li><a href="index.php?z=4">40000-49999</a></li>
		  <li><a href="index.php?z=5">50000-59999</a></li>
		  <li><a href="index.php?z=6">60000-69999</a></li>
		  <li><a href="index.php?z=7">70000-79999</a></li>
		  <li><a href="index.php?z=8">80000-89999</a></li>
		  <li><a href="index.php?z=9">90000-99999</a></li>
		</ul>
	</div>
</div>

<div class="row">
	<div class="small-12 large-centered columns">
		<table>
		  <thead>
			<tr>
			  <th width="200">ZipCode</th>
			  <th width="150">City</th>
			  <th width="50">State</th>
			</tr>
		  </thead>
					<?php
		if (isset($_GET['z'])) {

					while ($row = mysqli_fetch_assoc($zipssql)) {
					echo "<tr><td><a href='providers.php?zc=". $row['zipcode'] ."'>" .  $row['zipcode'] ."</a></td><td>" . $row['city'] ."</td><td>" . $row['state'] . "</td></tr>" ; } 
		} ?>
		</table>
	</div>
</div>
<script src="//cdn.jsdelivr.net/foundation/5.4.3/js/foundation.min.js"></script>
<script>
	$(document).foundation();
</script>
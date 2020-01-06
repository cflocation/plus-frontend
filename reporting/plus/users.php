<?php
	include 'db.php' ; 
	$con1 = mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","ShowSeeker");

	if (isset($_GET['s'])) {
		$userType = $_GET['s'] ;
	}

	$corporations = "SELECT name, id FROM corporations where id NOT IN ('14', '35', '38') ORDER BY name ASC";
	$corps = mysqli_query($con1, $corporations);



if ($userType == 1) {

		$sql_user_sql = "SELECT count( logs.searches.data ) AS user_count, userid, logs.searches.userid, ShowSeeker.users.firstname, ShowSeeker.users.lastname, ShowSeeker.corporations.name, ShowSeeker.corporations.id FROM searches INNER JOIN ShowSeeker.users ON userid = users.id INNER JOIN ShowSeeker.corporations ON ShowSeeker.corporations.id = users.corporationid GROUP BY userid ASC ORDER BY ShowSeeker.corporations.name,  user_count desc, ShowSeeker.users.lastname  ASC";
		$title = "Number of Searches by User";
}

else if ($userType == 2) {

		$sql_user_sql = "SELECT count( result ) AS user_count, userid, ShowSeeker.users.firstname, ShowSeeker.users.lastname, ShowSeeker.corporations.name, ShowSeeker.corporations.id FROM eventlogs INNER JOIN ShowSeeker.users ON userid = users.id INNER JOIN ShowSeeker.corporations ON ShowSeeker.corporations.id = users.corporationid where eventslogid = 1 and USERID NOT IN (147, 148, 149, 220, 151, 152, 153, 154, 155, 160, 742, 1177, 1187, 1188, 1189, 1190, 1191, 2022, 2136, 2261, 2339, 2344, 2345, 2347, 2407, 2435) GROUP BY userid ASC ORDER BY ShowSeeker.corporations.name, user_count desc, ShowSeeker.users.lastname ASC";
		$title = "Number of Logins by User";
}

else if ($userType == 3) {

		$sql_user_sql = "SELECT count( result ) AS user_count, userid, ShowSeeker.users.firstname, ShowSeeker.users.lastname, ShowSeeker.corporations.name, ShowSeeker.corporations.id FROM eventlogs INNER JOIN ShowSeeker.users ON userid = users.id INNER JOIN ShowSeeker.corporations ON ShowSeeker.corporations.id = users.corporationid where eventslogid = 2 and USERID NOT IN (147, 148, 149, 220, 151, 152, 153, 154, 155, 160, 742, 1177, 1187, 1188, 1189, 1190, 1191, 2022, 2136, 2261, 2339, 2344, 2345, 2347, 2407, 2435) GROUP BY userid ASC ORDER BY ShowSeeker.corporations.name, user_count desc, ShowSeeker.users.lastname ASC";
		$title = "Number of Proposals Created by User";
}

else if ($userType == 4) {

		$sql_user_sql = "SELECT count( request ) AS user_count, userid, ShowSeeker.users.firstname, ShowSeeker.users.lastname, ShowSeeker.corporations.name, ShowSeeker.corporations.id FROM eventlogs INNER JOIN ShowSeeker.users ON userid = users.id INNER JOIN ShowSeeker.corporations ON ShowSeeker.corporations.id = users.corporationid where eventslogid = 4 and USERID NOT IN (147, 148, 149, 220, 151, 152, 153, 154, 155, 160, 742, 1177, 1187, 1188, 1189, 1190, 1191, 2022, 2136, 2261, 2339, 2344, 2345, 2347, 2407, 2435) GROUP BY userid ASC ORDER BY ShowSeeker.corporations.name, user_count desc, ShowSeeker.users.lastname ASC";
		$title = "Number of Proposals Shared by User";
}

else if ($userType == 5) {

		$sql_user_sql = "SELECT count( request ) AS user_count, userid, ShowSeeker.users.firstname, ShowSeeker.users.lastname, ShowSeeker.corporations.name, ShowSeeker.corporations.id FROM eventlogs INNER JOIN ShowSeeker.users ON userid = users.id INNER JOIN ShowSeeker.corporations ON ShowSeeker.corporations.id = users.corporationid where eventslogid = 50 and USERID NOT IN (147, 148, 149, 220, 151, 152, 153, 154, 155, 160, 742, 1177, 1187, 1188, 1189, 1190, 1191, 2022, 2136, 2261, 2339, 2344, 2345, 2347, 2407, 2435) GROUP BY userid ASC ORDER BY ShowSeeker.corporations.name, user_count desc, ShowSeeker.users.lastname ASC";
		$title = "Number of Emailed Proposals by User";
}

else if ($userType == 6) {

		$sql_user_sql = "SELECT count( request ) AS user_count, userid, ShowSeeker.users.firstname, ShowSeeker.users.lastname, ShowSeeker.corporations.name, ShowSeeker.corporations.id FROM eventlogs INNER JOIN ShowSeeker.users ON userid = users.id INNER JOIN ShowSeeker.corporations ON ShowSeeker.corporations.id = users.corporationid where eventslogid = 45 and USERID NOT IN (147, 148, 149, 220, 151, 152, 153, 154, 155, 160, 742, 1177, 1187, 1188, 1189, 1190, 1191, 2022, 2136, 2261, 2339, 2344, 2345, 2347, 2407, 2435) GROUP BY userid ASC ORDER BY ShowSeeker.corporations.name, user_count desc, ShowSeeker.users.lastname ASC";
		$title = "Number of Proposals Copied by User";
}

else if ($userType == 7) {

		$sql_user_sql = "SELECT count( request ) AS user_count, userid, ShowSeeker.users.firstname, ShowSeeker.users.lastname, ShowSeeker.corporations.name, ShowSeeker.corporations.id FROM eventlogs INNER JOIN ShowSeeker.users ON userid = users.id INNER JOIN ShowSeeker.corporations ON ShowSeeker.corporations.id = users.corporationid where eventslogid = 46 and USERID NOT IN (147, 148, 149, 220, 151, 152, 153, 154, 155, 160, 742, 1177, 1187, 1188, 1189, 1190, 1191, 2022, 2136, 2261, 2339, 2344, 2345, 2347, 2407, 2435) GROUP BY userid ASC ORDER BY ShowSeeker.corporations.name, user_count desc, ShowSeeker.users.lastname ASC";
		$title = "Number of Proposals Renamed by User";
}

else if ($userType == 8) {

		$sql_user_sql = "SELECT count( request ) AS user_count, userid, ShowSeeker.users.firstname, ShowSeeker.users.lastname, ShowSeeker.corporations.name, ShowSeeker.corporations.id FROM eventlogs INNER JOIN ShowSeeker.users ON userid = users.id INNER JOIN ShowSeeker.corporations ON ShowSeeker.corporations.id = users.corporationid where eventslogid = 47 and USERID NOT IN (147, 148, 149, 220, 151, 152, 153, 154, 155, 160, 742, 1177, 1187, 1188, 1189, 1190, 1191, 2022, 2136, 2261, 2339, 2344, 2345, 2347, 2407, 2435) GROUP BY userid ASC ORDER BY ShowSeeker.corporations.name ASC, user_count desc";
		$title = "Number of Proposals Merged by User";
}


else if ($userType == 11) {

		$sql_user_sql = "SELECT count( request ) AS user_count, userid, ShowSeeker.users.firstname, ShowSeeker.users.lastname, ShowSeeker.corporations.name, ShowSeeker.corporations.id FROM eventlogs INNER JOIN ShowSeeker.users ON userid = users.id INNER JOIN ShowSeeker.corporations ON ShowSeeker.corporations.id = users.corporationid where eventslogid = 56 and USERID NOT IN (147, 148, 149, 220, 151, 152, 153, 154, 155, 160, 742, 1177, 1187, 1188, 1189, 1190, 1191, 2022, 2136, 2261, 2339, 2344, 2345, 2347, 2407, 2435) GROUP BY userid ASC ORDER BY ShowSeeker.corporations.name ASC, user_count desc";
		$title = "Tutorials Watched by User";
}



else if ($userType ==9) {

		$sql_user_sql = "SELECT count( request ) AS user_count, userid, ShowSeeker.users.firstname, ShowSeeker.users.lastname, ShowSeeker.corporations.name, ShowSeeker.corporations.id FROM eventlogs INNER JOIN ShowSeeker.users ON userid = users.id INNER JOIN ShowSeeker.corporations ON ShowSeeker.corporations.id = users.corporationid where eventslogid in (13,12,7,8,14,10,49,11,9,48,15) and USERID NOT IN (147, 148, 149, 220, 151, 152, 153, 154, 155, 160, 742, 1177, 1187, 1188, 1189, 1190, 1191, 2022, 2136, 2261, 2339, 2344, 2345, 2347, 2407, 2435) GROUP BY userid ASC ORDER BY ShowSeeker.corporations.name ASC, user_count desc";
		$title = "Number of Proposals Downloaded by User";
}




$usersql = mysqli_query($con, $sql_user_sql);
?>
<head>
<title>ShowSeeker - Historical Trends</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/normalize.css">
<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/foundation.css">
<script src="http://www.showseeker.com/inc/foundation/js/vendor/modernizr.js"></script>
</head>
<?php include 'top-menu.php'; ?>
<h3 align="center">ShowSeeker Plus - Historical Trends <br /><?php echo $title;?></h3>
<center>
<div class="button-bar">
	<div class="small-11 small-centered columns">
		<ul class="button-group">
			<?php while ($row = mysqli_fetch_assoc($corps)) { ?>
			  <li><button class="tiny" onclick="$('.<?php echo $row['id']; ?>').toggle();"> <?php echo $row['name']; ?> </button></li>
			<?php } ?>	
		</ul>
	</div>
</div>
</center>
<div class="row">
<center>
	<table width="55%">
		<thead>
			<tr>
			<th width="45%">User Name:</th>
			<th width="10%">Count</th>
			<th width="45%"><center>Corporation</center></th>
			</tr>
		  </thead>
		<tbody>
		<?php
		$previous = "AdGorillaTV";
		while ($row1 = mysqli_fetch_assoc($usersql)) {
			$info = $row1['firstname'] ." ". $row1['lastname'] ;
			$count = $row1['user_count'];
			$company = $row1['name'];
			$UID = $row1['userid'];
			$cid = $row1['id'];
			if ($previous <> $company) { echo "<tr class=".$cid." style='display:none;'><td colspan=3><hr></td></tr>"; }	
				echo "<tr class=".$cid." style='display:none;'><td><a href='../dashboard/searchSnooper.php?id=$UID'>" . $info . "</a></td><td>" . $count . "</td><td><center>". $company ."</center></td></tr>";
			$previous = $company;
		} ?>
		</tbody>
	</table>
</center>
<script src="http://www.showseeker.com/inc/foundation/js/foundation.min.js"></script>

<script>
	$(document).foundation();
</script>
<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);
	$con = mysqli_connect("61b1ed95616f65903ff311cc9decad51aa4cac3d.rackspaceclouddb.com","vastdb","VastPlus#01","logs");
	$reportdate = date("F j, Y");  
?>

<title>ShowSeeker Search Trends</title>
<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/normalize.css">
<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/foundation.css">
<script src="http://www.showseeker.com/inc/foundation/js/vendor/modernizr.js"></script>
<?php
	$sql_keywords = "SELECT  data, count(*) as count from searches where type = '1' group by data order by count desc";
	$search_keywords = mysqli_query($con, $sql_keywords);

	$sql_titles = "SELECT  data, count(*) as count from searches where type = '2' group by data order by count desc";
	$search_titles = mysqli_query($con, $sql_titles);

?>
<h3 align="center">ShowSeeker Plus - Search Trends - <br> Last 5000 Keyword & Title Searches</h3>
<h4 align="center"><a href="searches.php">Top 10 - Pie Charts </a></h4>

<div class="row">
  <div class="large-6 columns">
	<table>
		<thead>
			<tr>
			<th>Keyword</th>
			<th>Count</th>
			</tr>
		  </thead>
		<tbody>
		<?php
		while ($row1 = mysqli_fetch_assoc($search_keywords)) {

			$info1 = $row1['data'];
			$count1 = $row1['count'];
			echo "<tr><td>" . $info1 . "</td><td>" . $count1 . "</td></tr>";
		}
		?>
		</tbody>
	</table>
</div>

<div class="large-6 columns">
  	<table>
		<thead>
			<tr>
			<th>Title</th>
			<th>Count</th>
			</tr>
		  </thead>
		<tbody>
		<?php
		while ($row2 = mysqli_fetch_assoc($search_titles)) {

			$info2 = $row2['data'];
			$count2 = $row2['count'];
			echo "<tr><td>" . $info2 . "</td><td>" . $count2 . "</td></tr>";
		}
		?>
		</tbody>
	</table>
	</div>
</div>

<script src="http://www.showseeker.com/inc/foundation/js/vendor/jquery.js"></script>
<script src="http://www.showseeker.com/inc/foundation/js/foundation.min.js"></script>
<script>
	$(document).foundation();
</script>
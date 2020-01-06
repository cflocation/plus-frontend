<?php
	include 'db.php' ; 
	$reportdate = date("F j, Y"); 
		
	if (isset($_GET['y']))
    {
	    $year = $_GET['y'] ;
	}
	else {
		$year = date('Y');
	}
	if (isset($_GET['m']))
    {
	    $month = $_GET['m'] ;
		 if ($month == '1') {$datefrom = $year . "-01-01"; $dateto =  $year . "-01-31";}
		 if ($month == '2') {$datefrom = $year . "-02-01"; $dateto = $year . "-02-28";}
		 if ($month == '3') {$datefrom = $year . "-03-01"; $dateto = $year . "-03-31";}
		 if ($month == '4') {$datefrom = $year . "-04-01"; $dateto = $year . "-04-30";}
		 if ($month == '5') {$datefrom = $year . "-05-01"; $dateto = $year . "-05-31";}
		 if ($month == '6') {$datefrom = $year . "-06-01"; $dateto = $year . "-06-30";}
		 if ($month == '7') {$datefrom = $year . "-07-01"; $dateto = $year . "-07-31";}
		 if ($month == '8') {$datefrom = $year . "-08-01"; $dateto = $year . "-08-31";}
		 if ($month == '9') {$datefrom = $year . "-09-01"; $dateto = $year . "-09-30";}
		 if ($month == '10') {$datefrom = $year . "-10-01"; $dateto = $year . "-10-31";}
		 if ($month == '11') {$datefrom = $year . "-11-01"; $dateto = $year . "-11-30";}
		 if ($month == '12') {$datefrom = $year . "-12-01"; $dateto = $year . "-12-31";}

		$_SESSION['datefrom'] = $datefrom ;
		$_SESSION['dateto'] = $dateto ;
	}

	$datefrom = $_SESSION['datefrom'] ;
	$dateto = $_SESSION['dateto'];

	$sql_keywords = "SELECT  data, count(*) as count from searches where type = '1' AND (createdat BETWEEN '$datefrom' AND '$dateto') group by data order by count desc";
	$search_keywords = mysqli_query($con, $sql_keywords);

	$sql_titles = "SELECT  data, count(*) as count from searches where type = '2' AND (createdat BETWEEN '$datefrom' AND '$dateto') group by data order by count desc";
	$search_titles = mysqli_query($con, $sql_titles);

	$sql_actors = "SELECT  data, count(*) as count from searches where type = '3' AND (createdat BETWEEN '$datefrom' AND '$dateto') group by data order by count desc";
	$search_actors = mysqli_query($con, $sql_actors);

$dft = strtotime($datefrom);
$dtt = strtotime($dateto);
$df = date("m/d/Y",$dft);
$dt = date("m/d/Y",$dtt);

?>

<head>
<title>ShowSeeker Search Trends</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/normalize.css">
<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/foundation.css">
<script src="http://www.showseeker.com/inc/foundation/js/vendor/modernizr.js"></script>
</head>

<?php include 'top-menu.php'; ?>

<br>

<h3 align="center">ShowSeeker Plus - Search Trends - <br> <?php echo $df;?> - <?php echo $dt;?> -  Keyword & Title Searches</h3>
<h4 align="center"><a href="searches.php">Top 10 - Pie Charts</a></h4>

<div class="row">
<table align="center"><tr valign="top"><td>
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

</td><td>

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

</td><td>

	<table>
		<thead>
			<tr>
			<th>Actor</th>
			<th>Count</th>
			</tr>
		  </thead>
		<tbody>
		<?php
		while ($row3 = mysqli_fetch_assoc($search_actors)) {

			$info3 = $row3['data'];
			$count3 = $row3['count'];
			echo "<tr><td>" . $info3 . "</td><td>" . $count3 . "</td></tr>";
		}
		?>
		</tbody>
	</table>


</td></tr></table>
</div>






<script src="http://www.showseeker.com/inc/foundation/js/foundation.min.js"></script>
<script>
$(function() {

$( "#datefrom" ).datepicker({
  showOn: "focus",
  numberOfMonths: 3,
  buttonImageOnly: true,
  onClose: function(dateText, inst) { 
  $.post("d1.php", {"datefrom": dateText});
}
});



 $( "#dateto" ).datepicker({
  showOn: "focus",
  numberOfMonths: 3,
  buttonImageOnly: true,
  onClose: function(dateText, inst) { 
  $.post("d2.php", {"dateto": dateText});
}
});


});
</script>
<script>
	$(document).foundation();
</script>




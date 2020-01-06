<?php
	session_start();
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);

?>



<head>
<title>ShowSeeker Search Trends - Weekly</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/normalize.css">
<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/foundation.css">
<script src="http://www.showseeker.com/inc/foundation/js/vendor/modernizr.js"></script>
</head>
<?php include 'top-menu.php'; ?>
<br />
<?php

	if (isset($_GET['w']))
    {
	    $week = $_GET['w'] ;
	    getStartAndEndDate($week, '2016')  ;
	}

	else {


for ($x = 1; $x <= 52; $x++) {
    getStartAndEndDate($x, '2015')  ;
} 

	}


function getStartAndEndDate($week, $year) {
	$con = mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","logs");
	// Adding leading zeros for weeks 1 - 9.
	$date_string = $year . 'W' . sprintf('%02d', $week);
	$return0 = date('Y-n-j', strtotime($date_string));
	$return1 = date('Y-n-j', strtotime($date_string . '7'));

	$sql_keywords = "SELECT  data, count(*) as count from searches where type = '1' AND (createdat BETWEEN '$return0' AND '$return1') group by data order by count desc limit 0,1";
	$search_keywords = mysqli_query($con, $sql_keywords);

	$sql_titles = "SELECT  data, count(*) as count from searches where type = '2' AND (createdat BETWEEN '$return0' AND '$return1') group by data order by count desc limit 0,1";
	$search_titles = mysqli_query($con, $sql_titles);

	$sql_actors = "SELECT  data, count(*) as count from searches where type = '3' AND (createdat BETWEEN '$return0' AND '$return1') group by data order by count desc limit 0,1";
	$search_actors = mysqli_query($con, $sql_actors);
  
	$row1 = mysqli_fetch_assoc($search_keywords) ;
	$row2 = mysqli_fetch_assoc($search_titles) ;
	$row3 = mysqli_fetch_assoc($search_actors) ;
  
	$keyword = $row1['data'];
	$title = $row2['data'];
	$actor = $row3['data'];

	echo "<div class='row'>";
	echo "<table align='center'><tr align='center'><th>Date</th><th>Week #</th><th>Keyword</th><th>Title</th><th>Actor</th>"; 
	echo "<tr align='center'><td>".$return0 ." - ". $return1  ."</td><td>".$week."</td><td>".$keyword."</td><td>".$title."</td><td>".$actor."</td></tr>" ; 
	echo "</table>" ;
	echo "</div>";

}

?>
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
<script src="http://www.showseeker.com/inc/foundation/js/foundation.min.js"></script>
<script>
	$(document).foundation();
</script>
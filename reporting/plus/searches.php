<?php
	include 'db.php' ; 
	$reportdate = date("F j, Y");  
	$datefrom = $_SESSION['datefrom'] ;
	$dateto = $_SESSION['dateto'];
	$dft = strtotime($datefrom);
	$dtt = strtotime($dateto);
	$df = date("m/d/Y",$dft);
	$dt = date("m/d/Y",$dtt);

//KEYWORDS CHART
	$sql_keywords = "SELECT  data, count(*) as count from searches where type = '1' AND (createdat BETWEEN '$datefrom' AND '$dateto') group by data order by count desc LIMIT 0 , 10";
	$search_keywords = mysqli_query($con, $sql_keywords);

	$sql_titles = "SELECT  data, count(*) as count from searches where type = '2' AND (createdat BETWEEN '$datefrom' AND '$dateto') group by data order by count desc LIMIT 0 , 10";
	$search_titles = mysqli_query($con, $sql_titles);
	
	$k1 = "data: [";
	$k2 = "";
			while ($row1 = mysqli_fetch_assoc($search_keywords)) {
				$info1 = $row1['data'];
				$count1 = $row1['count'];
				$k2 .= "['".$info1 ."'," . $count1 ."]," ;
			}
	$k3 = "]";
	$keywords =  $k2 ;

	$t1 = "data: [";
	$t2 = "";
			while ($row2 = mysqli_fetch_assoc($search_titles)) {
				$info2 = $row2['data'];
				$count2 = $row2['count'];
				$t2 .= "['".$info2 ."'," . $count2 ."]," ;
			}
	$t3 = "]";
	$titles = $t2;

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
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {

	var data = google.visualization.arrayToDataTable([
	  ['Keywords', 'Percentage of Usage'],
	  <?php echo $keywords;?>

	]);

	var options = {
	  title: '10 Most Popular ShowSeeker Keyword Search Terms - <?php echo $df;?> - <?php echo $dt;?>',
	  is3D: true,
	  legend: { position: 'labeled' },
	  colors: ['#365A8E', '#3C659F', '#4370B1', '#567EB9', '#698DC1', '#7B9BC8', '#8EA9D0', '#A1B8D8', '#B4C6E0', '#AFBDD2'],
	};

	var chart = new google.visualization.PieChart(document.getElementById('keyword'));
	chart.draw(data, options);
  }
</script>
<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {

	var data1 = google.visualization.arrayToDataTable([
	  ['Titles', 'Percentage of Usage'],
	  <?php echo $titles;?>

	]);

	var options1 = {
	  title: '10 Most Popular ShowSeeker Title Search Terms - <?php echo $df;?> - <?php echo $dt;?>',
	  is3D: true,
	  legend: { position: 'labeled' },
	  colors: ['#007A5C', '#008F6B', '#00A37A', '#00B88A', '#00CC99', '#19D1A3', '#33D6AD', '#4DDBB8', '#66E0C2', '#80E6CC'],
	};

   var chart = new google.visualization.PieChart(document.getElementById('titles'));
   chart.draw(data1, options1);
  }
</script>

<?php include 'top-menu.php'; ?>

	<div class="row">
		<div class="small-10 small-centered columns">

			<div id="keyword" style="width: 900px; height: 400px;"></div>
			<div id="titles" style="width: 900px; height: 400px;"></div>

		</div>
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

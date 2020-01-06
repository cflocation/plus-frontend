<?php
	session_start();

	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);
	$con = mysqli_connect("61b1ed95616f65903ff311cc9decad51aa4cac3d.rackspaceclouddb.com","vastdb","VastPlus#01","logs");
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
	$keywords =  $k2;

	$t1 = "data: [";
	$t2 = "";
			while ($row2 = mysqli_fetch_assoc($search_titles)) {
				$info2 = $row2['data'];
				$count2 = $row2['count'];
				$t2 .= "['".$info2 ."'," . $count2 ."]," ;
			}
	$t3 = "]";
	$titles = $t1 . $t2 . $t3;

?>


<title>ShowSeeker Search Trends</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">
<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/normalize.css">
<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/foundation.css">
<script src="http://www.showseeker.com/inc/foundation/js/vendor/modernizr.js"></script>
</head>
<script src="http://code.highcharts.com/highcharts.js"></script>

<nav class="top-bar" data-topbar="" role="navigation">
  <!-- Title -->
  <ul class="title-area">
    <li class="name"><h1><a href="index.php">ShowSeeker Search Trends</a></h1></li>
      <li class="divider"></li>
    <!-- Mobile Menu Toggle -->
    <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
  </ul>

  <!-- Top Bar Section -->
  
<section class="top-bar-section">

    <!-- Top Bar Left Nav Elements -->
    <ul class="left">

      <!-- Search | has-form wrapper -->
      <li class="has-form">
        <div class="row collapse">
          <div class="large-6 small-6 columns">
            <input type="text" name="datefrom" id="datefrom" style="width:100px;" placeholder="<?php echo $df;?>">
          </div>
          <div class="large-6 small-6 columns">
            <input type="text" name="dateto" id="dateto" style="width:100px;" placeholder="<?php echo $dt;?>"> 
          </div>
        </div>
      </li>
      <li class="has-form">
        <a class="button" href="trends.php">Generate Search Trends</a>
      </li>
    </ul>

    <!-- Top Bar Right Nav Elements -->
    <ul class="right">
      <!-- Divider -->
<!--       <li class="divider"></li>
      <li><a href="#">Presets:</a></li>
      	  <li class="divider"></li>
      Dropdown
      <li class="has-dropdown not-click"><a href="#"><?php echo date("F");?> Trends by Week</a>
        <ul class="dropdown"><li class="title back js-generated"><h5><a href="javascript:void(0)">Back</a></h5></li><li class="parent-link show-for-small"><a class="parent-link js-generated" href="#">Item 1</a></li>
      		  <li><a href="trends.php?t=w1"><?php echo date("F");?> - Week 1</a></li>
      		  <li><a href="trends.php?t=w2"><?php echo date("F");?> - Week 2</a></li>
      		  <li><a href="trends.php?t=w3"><?php echo date("F");?> - Week 3</a></li>
      		  <li><a href="trends.php?t=w4"><?php echo date("F");?> - Week 4</a></li>
        </ul>
      </li> -->

      <li class="has-dropdown not-click"><a href="#">Trends by Month</a>
        <ul class="dropdown"><li class="title back js-generated"><h5><a href="javascript:void(0)">Back</a></h5></li><li class="parent-link show-for-small"><a class="parent-link js-generated" href="#">Item 1</a></li>
		  <li><a href="trends.php?m=1">January</a></li>
		  <li><a href="trends.php?m=2">February</a></li>
		  <li><a href="trends.php?m=3">March</a></li>
		  <li><a href="trends.php?m=4">April</a></li>
		  <li><a href="trends.php?m=5">May</a></li>
		  <li><a href="trends.php?m=6">June</a></li>
		  <li><a href="trends.php?m=7">July</a></li>
		  <li><a href="trends.php?m=8">August</a></li>
		  <li><a href="trends.php?m=9">September</a></li>
		  <li><a href="trends.php?m=10">October</a></li>
		  <li><a href="trends.php?m=11">November</a></li>
		  <li><a href="trends.php?m=12">December</a></li>
        </ul>
      </li>
      <li class="divider"></li>
    </ul>
  </section></nav>
<br>

<?php
print_r ($keywords);
?>

<html>
  <head>
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
          title: '10 Most Popular ShowSeeker Keyword Search Terms',
		  pieSliceText: 'label',
		  is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
  </body>
</html>
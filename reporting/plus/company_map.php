<?php
	session_start();
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);
	if (isset($_GET['CID'])) { $cid = $_GET['CID'] ; }
	else {$cid = '14'; } 
	$con1 = mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","ShowSeeker") ;

	$sql = "SELECT DISTINCT offices.id AS officeid, offices.name AS office, regions.name AS market, city, abbreviation FROM regions INNER JOIN offices ON regions.id = offices.regionid INNER JOIN officeaddresses ON offices.id = officeaddresses.officeid INNER JOIN addresses ON officeaddresses.addressid = addresses.id INNER JOIN states ON addresses.statesid = states.id WHERE offices.active =1 AND offices.deletedat IS NULL AND regions.corporationid =  $cid";
	$charts = mysqli_query($con1, $sql);

	$sql_company = "SELECT name AS corporation, id AS corporationsid FROM corporations WHERE id = $cid";
	$info = mysqli_query($con1, $sql_company);
	$rowi = mysqli_fetch_array($info);

	$sql_companies = "SELECT * FROM corporations order by name ASC";
	$corps = mysqli_query($con1, $sql_companies);
?>
<?php
		$k2 = "";
		$k3 = "]";
		while ($row = mysqli_fetch_array($charts))
			{ 
				$info1 = $row['office'];
				$OID = $row['officeid'];
				$city = $row['city'];
				$state = $row['abbreviation'];
				
				$office_cnt = "SELECT count( userid ) as count FROM officedefaults INNER JOIN users ON officedefaults.userid = users.id WHERE officedefaults.officeid = $OID AND users.deletedat IS NULL";
				$offices = mysqli_query($con1, $office_cnt);
				$row2 = mysqli_fetch_array($offices);
				$cnt = $row2['count'] ;
				$k2 .= "['".$city .", " . $state ."'," . $cnt ."]," ;
} 
	$offices =  $k2 ;
?>
<head>
<title>ShowSeeker - Graphing</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/normalize.css">
<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/foundation.css">
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css" />
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
<script src="http://www.showseeker.com/inc/foundation/js/vendor/modernizr.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["geochart"]});
      google.setOnLoadCallback(drawRegionsMap);
      function drawRegionsMap() {
        var data1 = google.visualization.arrayToDataTable([

			['City',  'Users per Office'],
				<?php echo $offices;?>
			]);
 
        var options = {
          region: 'US', 
          colorAxis: {colors: ['blue', 'green']},
          backgroundColor: '#BBBBBB',
		  displayMode: 'markers',
          datalessRegionColor: '#69B6BA',
		  resolution: 'provinces',
		  enable: true, zoomFactor: 10.5
        };

        var chart = new google.visualization.GeoChart(document.getElementById('geochart-colors'));
        chart.draw(data1, options);
      };
    </script>
</head>
<body>
<div class="row">
	<div class="small-3 columns">	
		<div data-collapsed="true" data-role="collapsible">
			<h3>Corporations</h3>
				<ul data-role="listview" data-divider-theme="a" data-filter="false">
					<?php while ($rowA = mysqli_fetch_array($corps)): ?>
						<li><a style="font-size:10px;" href="?CID=<?php echo $rowA['id'] ?>" data-ajax="false"><?php echo $rowA['name'] ?></a></li>
					<?php endwhile; ?>
				</ul>
		</div>
	</div>
	<div class="small-9 columns">
		<center><h2><?php echo $rowi['corporation'] . " Office Locations & Users per Office" ?></h2></center>
		<div id="geochart-colors" style="width: 800px; height: 600px;"></div>
	</div>
</div>
</body>
</html>
<script src="http://www.showseeker.com/inc/foundation/js/foundation.min.js"></script>
<script>
	$(document).foundation();
</script>
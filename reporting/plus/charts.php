<?php
	include 'db.php' ; 
	//$con1 = mysqli_connect("61b1ed95616f65903ff311cc9decad51aa4cac3d.rackspaceclouddb.com","vastdb","jK6YK71tJ","ShowSeeker");
	$con1 = mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","ShowSeeker");
?>
<head>
<title>ShowSeeker Search Trends</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/normalize.css">
<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/foundation.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<script src="http://www.showseeker.com/inc/foundation/js/vendor/modernizr.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script>
<script>      
	google.load('visualization', '1.1', {packages: ['bar']});
    google.setOnLoadCallback(drawChart);

    function drawChart() {

      var data = google.visualization.arrayToDataTable([
        ['User',          'Searches', 'Logins'],
        ['Tim Smith',       55,           75],
        ['Sara Johnson',         25,           40],
        ['Roger McTiny',             8,           15],
        ['Emily Robby',             10,           25],
        ['Paul Frank',        9,           21]
      ]);

      var options = {
        chart: {
          title: 'User Activity of Logins & Searches'
        },
        width: 1000,
        height: 563,
        hAxis: {
          title: 'Total Population',
          minValue: 0,
        },
        vAxis: {
          title: 'City'
        },
        bars: 'horizontal',
        axes: {
          y: {
            0: {side: 'right'}
          }
        }
      };

      var chart = new google.charts.Bar(
        document.getElementById('ex9'));

      chart.draw(data, options);
    }
</script>
</head>
<?php include 'top-menu.php'; ?>
<body>
<div class="row">
<div class="small-10 centered-columns">
          <div id="ex9"></div>
</div>
</body>
<script src="http://www.showseeker.com/inc/foundation/js/foundation.min.js"></script>
<script>
	$(document).foundation();
</script>
<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);

	$con1 = mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","logs");
	$sql = "SELECT * FROM historicalLogs WHERE Title ='search' and Year = '2016' ORDER BY week";
	$result=mysqli_query($con1,$sql);


	$t1 = "data: [";
	$t2 = "";
			while ($row2 = mysqli_fetch_assoc($result)) {
				$info2 = $row2['Week'];
				$count2 = $row2['Count'];
				$t2 .= "['".$info2 ."'," . $count2 ."]," ;
			}
	$t3 = "]";
	$titles = $t2;

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <title>Google Charts Tutorial</title>
 
        <!-- load Google AJAX API -->
        <script type="text/javascript" src="http://www.google.com/jsapi"></script>
        <script type="text/javascript">
            //load the Google Visualization API and the chart
            google.load('visualization', '1', {'packages': ['columnchart']});
 
            //set callback
            google.setOnLoadCallback (createChart);
 
            //callback function
            function createChart() {
 
                //create data table object
                var dataTable = new google.visualization.DataTable();
 
                //define columns
                dataTable.addColumn('string','Quarters 2009');
                dataTable.addColumn('number', 'Searches');
 
                dataTable.addRows([
		
				 <?php echo $titles;?>

				]);





 
                //instantiate our chart object
                var chart = new google.visualization.ColumnChart (document.getElementById('chart'));
 
                //define options for visualization
                var options = {width: 800, height: 440, is3D: true, title: '2016 - ShowSeeker Searches by Week'
				};
 
                //draw our chart
                chart.draw(dataTable, options);
 
            }
        </script>
 
    </head>
 
    <body>
 
        <!--Div for our chart -->
        <div id="chart"></div>
 
    </body>
</html>
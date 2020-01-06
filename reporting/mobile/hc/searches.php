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
	$sql_keywords = "SELECT  data, count(*) as count from searches where type = '1' group by data order by count desc LIMIT 0 , 10";
	$search_keywords = mysqli_query($con, $sql_keywords);

	$sql_titles = "SELECT  data, count(*) as count from searches where type = '2' group by data order by count desc LIMIT 0 , 10";
	$search_titles = mysqli_query($con, $sql_titles);
	
	$k1 = "data: [";
	$k2 = "";
			while ($row1 = mysqli_fetch_assoc($search_keywords)) {
				$info1 = $row1['data'];
				$count1 = $row1['count'];
				$k2 .= "['".$info1 ."'," . $count1 ."]," ;
			}
	$k3 = "]";
	$keywords = $k1 . $k2 . $k3;

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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="http://code.highcharts.com/highcharts.js"></script>

<div id="container" style="width:100%; height:400px;"></div>
<script>
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: '10 Most Popular ShowSeeker Keyword Search Terms'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Search Term',
			<?php echo $keywords;?>
        }]
    });
});
</script>
<hr>
<div id="container1" style="width:100%; height:400px;"></div>
<script>
$(function () {
    $('#container1').highcharts({
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: '10 Most Popular ShowSeeker Title Search Terms'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Search Term',
			<?php echo $titles;?>
        }]
    });
});
</script>
<?php
	include 'db.php' ; 
	if (isset($_GET['T'])) { $type = $_GET['T'] ; }
	else {$type = "m" ; }

	$now = date("Y-m-d");
	$year = date('Y');
	$month = date('m');

?>
<head>
<title>ShowSeeker Search Clouds</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/normalize.css">
<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/foundation.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://www.showseeker.com/inc/foundation/js/vendor/modernizr.js"></script>
<style>
	#tagcloud {
		width: 300px;
		background:#CFE3FF;
		color:#0066FF;
		padding: 10px;
		border: 1px solid #559DFF;
		text-align:center;
		-moz-border-radius: 4px;
		-webkit-border-radius: 4px;
		border-radius: 4px;
	}
	#tagcloud a:link, #tagcloud a:visited {text-decoration:none; color: #333; }
	#tagcloud a:hover { text-decoration: underline; }
	#tagcloud span { padding: 4px; }
	#tagcloud .smallest { font-size: x-small; }
	#tagcloud .small { font-size: small; }
	#tagcloud .medium { font-size:medium; }
	#tagcloud .large { font-size:large; }
	#tagcloud .largest { font-size:larger; }
	.active{ background-color: #2AD587; }

</style>
</head>

<?php include 'top-menu.php'; ?>
<br />
<div class="row">

<ul class="button-group round right">
  <li ><a href="?T=m" class="button tiny info <?php if ($type=="m"){echo "active";} ?>">This Month</a></li>
  <li><a href="?T=y" class="button tiny info <?php if ($type=="y"){echo "active";} ?>">This Year</a></li>
</ul>

</div>

<div class="row">
<?php
if ($type == "m") {
	$datefrom = "2015-".$month."-01" ;
	$dateto = $now; 
}
if ($type == "y") {
	$datefrom = $year."-01-01" ;
	$dateto = $now; 
}

$i = 1;
while ($i <= 3 ) { 

$sql_keywords = "SELECT data as term, count(*) as counter from searches where type = '$i' AND (createdat BETWEEN '$datefrom' AND '$dateto') group by data order by counter desc limit 0,25";
$search_keywords = mysqli_query($con, $sql_keywords);
$terms = array(); // create empty array
$maximum = 0; // $maximum is the highest counter for a search term
	while ($row = mysqli_fetch_array($search_keywords))
	{
		$term = $row['term'];
		$counter = $row['counter'];
		if ($counter > $maximum) $maximum = $counter;
		$terms[] = array('term' => $term, 'counter' => $counter);
	}
 
shuffle($terms);

switch ($i) {
case 1:
	$title = "Keyword";
	break;
case 2:
	$title = "Title";
	break;
case 3:
	$title ="Actor";
	break;
}
?>



	<div class="small-4 columns">
		<h3 class="subheader"><center>Popular <?php echo $title;?> Searches</center></h3>
		<div id="tagcloud">
		<?php 
		foreach ($terms as $term):
		 $percent = floor(($term['counter'] / $maximum) * 100);
		 if ($percent < 20): 
		   $class = 'smallest'; 
		 elseif ($percent >= 20 and $percent < 40):
		   $class = 'small'; 
		 elseif ($percent >= 40 and $percent < 60):
		   $class = 'medium';
		 elseif ($percent >= 60 and $percent < 80):
		   $class = 'large';
		 else:
		 $class = 'largest';
		 endif;
		?>
		<span class="<?php echo $class; ?>">
		  <a href="cloud_info.php?search=<?php echo $term['term']; ?>"><?php echo $term['term']; ?></a>
		</span>
		<?php endforeach; ?>
		</div>
	</div>

<?php 
$i ++;
}?>
</div>
<br /><hr>
	<div class="row"><center><div class="small-12 columns">Click on a term for detailed search info.</div></center></div>
<hr>




<script src="http://www.showseeker.com/inc/foundation/js/foundation.min.js"></script>
<script>
	$(document).foundation();
</script>
<?php
	session_start();
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);
	$con = mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","logs") ;
	$id = $_GET['id'] ;
	$sql = "SELECT * FROM eventlogs WHERE userid ='$id' AND eventslogid ='5' ORDER BY createdat DESC LIMIT 0 , 50";
	$result=mysqli_query($con,$sql);

?>
<head>
	<title>ShowSeeker - Search Snooper</title>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="//cdn.jsdelivr.net/foundation/5.5.0/css/foundation.css">
	<link rel="stylesheet" href="//cdn.jsdelivr.net/foundation/5.5.0/css/foundation.min.css">
	<script src="//cdn.jsdelivr.net/foundation/5.5.0/js/vendor/modernizr.js"></script>

	<script>
	function Show_Div(Div_id) {
		if (false == $(Div_id).is(':visible')) {
			$(Div_id).show(250);
		}
		else {
			$(Div_id).hide(250);
		}
	}
	</script>
</head>
<center><h3>ShowSeeker - User Search Archive</h3></center>
<div class="row">
		  <div class="small-3 columns text-center">Request</div>
		  <div class="small-3 columns text-center">Date</div>
		  <div class="small-6 columns text-center">Data</div>
</div>


<?php
	$i = 1;
	while($row = mysqli_fetch_array($result)) {
	
		  
echo "<div class='row'>";
	echo "<div class='small-3 columns'><input type='button' value='Review Data' onclick='Show_Div(Div_".$i.")' /></div>";
	echo "<div class='small-3 columns'>". $row['createdat'] ."</div>";


echo "<div class='small-6 columns' id='Div_".$i."' style='display: none;'>". $row['request'] ."</div>";

echo "</div>";
	$i ++ ;
		  }

?>
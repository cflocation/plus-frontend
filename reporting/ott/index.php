<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);

	$con = mysqli_connect("db2.showseeker.net","vastsupport1","cP7qRiSvaR2M","Programs");
	$sql = "SELECT DISTINCT title, showcardId  FROM OTTEpisode ORDER BY title ASC" ;
	$results = mysqli_query($con,$sql);

?>
<html>
<head>
	<title>ShowSeeker - OTT Menu</title>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="//cdn.jsdelivr.net/foundation/5.5.0/css/foundation.css">
	<link rel="stylesheet" href="//cdn.jsdelivr.net/foundation/5.5.0/css/foundation.min.css">
	<script src="//cdn.jsdelivr.net/foundation/5.5.0/js/vendor/modernizr.js"></script>
<style>
	.frmDronpDown {border: 1px solid #F0F0F0;background-color:#B9B9B9;margin: 2px 0px;padding:20px;}
	.demoInputBox {padding: 10px;border: #F0F0F0 1px solid;border-radius: 4px;background-color: #FFF;}

</style>
<script>
function getEpisode(val) {
	$.ajax({
	type: "POST",
	url: "get_show.php",
	data:'show_id='+val,
	success: function(data){
		$("#episode-list").html(data);
	}
	});
}

function selectShow(val) {
	$("#search-box").val(val);
	$("#suggesstion-box").hide();
}
</script>
</head>
<body>
<div class="row">
	<div class="small-6 columns"><img src="http://plus.showseeker.com/i/logo500.png" width="200px">
	</div>
</div>
<br>

<div class="frmDronpDown">
	<div class="row">
		<div class="small-6 columns">
			<label>Shows: </label><br/>
			<select name="show" id="show-list" class="demoInputBox" onChange="getEpisode(this.value);">
				<option value="">Select Show</option>
				<?php
				foreach($results as $shows) {
				?>
				<option value="<?php echo $shows["showcardId"]; ?>"><?php echo $shows["title"]; ?></option>
				<?php
				}
				?>
			</select>
		</div>
	</div>
	<div class="row">
		<div class="small-6 columns">
		<label>Episodes:</label><br/>
			<select name="episode" id="episode-list" class="demoInputBox">
				<option value="">Select Episode</option>
			</select>
		</div>
	</div>
</div>
</body>
</html>
<script src="//cdn.jsdelivr.net/foundation/5.5.0/js/foundation.min.js"></script>
<script>
	$(document).foundation();
</script>
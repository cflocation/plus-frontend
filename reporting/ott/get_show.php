<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);
	$con = mysqli_connect("db2.showseeker.net","vastsupport1","cP7qRiSvaR2M","Programs");
	$sql = " ORDER BY title ASC" ;

if(!empty($_POST["show_id"])) {
	$sql ="SELECT * FROM OTTEpisode where showcardId = '" . $_POST["show_id"] . "'";
	$results = mysqli_query($con,$sql);
?>
<?php
	foreach($results as $episode) {
?>
	<option value="<?php echo $episode["showcardId"]; ?>"><?php echo $episode["epiTitle"]; ?></option>
<?php
	}
}
?>
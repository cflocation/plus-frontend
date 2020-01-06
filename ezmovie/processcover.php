<?php
// ini_set("display_startup_errors",1);
// ini_set("display_errors",1);
// error_reporting(E_ALL);
session_start();

include_once('config/logs.php');   //Include logs db and basic functions
include_once('config/mysqli.php');

define("TMP_IMG_DIR","/var/www/html/www.showseeker.com/ezshows/tmp/");

$tmsId        = trim($_POST['tmsId']);
$image        = (isset($_POST['image']) && trim($_POST['image']) != "") ? trim($_POST['image']) : '';
$imgUploadErr = "";
$res          = false;     

if(isset($_FILES['fileToUpload'])){
	$targetFile    = TMP_IMG_DIR.basename($_FILES["fileToUpload"]["name"]);
	$imageFileType = pathinfo($targetFile,PATHINFO_EXTENSION);
	$check         = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

	if($check === false || $_FILES['fileToUpload']['error'] !== 0){
		$imgUploadErr = "Sorry, there was an error with upload, please try again.";
	}
	
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ){
		$imgUploadErr = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
	}

	if (file_exists($targetFile)) {
		$imgUploadErr = "Sorry, file already exists.";
	}

	if($imgUploadErr == ""){
		if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)){
			$image = basename($targetFile);
		} else{
			$imgUploadErr = "Sorry, there was an error with upload, please try again.";
		}
	}
}

if($imgUploadErr == "" && $image != "" && file_exists(TMP_IMG_DIR.$image)){
	if (!class_exists('S3')) require_once '../ezshows/s3/S3.php';
	// AWS access info
	//if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAJJBO2SC6ON5AACAA');
	//if (!defined('awsSecretKey')) define('awsSecretKey', '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');
	if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAQBTJZ7I7SNKMVUOY');
	if (!defined('awsSecretKey')) define('awsSecretKey', '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');

	// Check for CURL
	if (!extension_loaded('curl') && !@dl(PHP_SHLIB_SUFFIX == 'so' ? 'curl.so' : 'php_curl.dll'))
		exit("\nERROR: CURL extension not loaded\n\n");

	$bucket = "showseeker/media/movie/$tmsId";
	S3::setAuth(awsAccessKey, awsSecretKey);
	$res = S3::putObject(S3::inputFile(TMP_IMG_DIR.$image, false), $bucket, $image, S3::ACL_PUBLIC_READ);
	unlink(TMP_IMG_DIR.$image);

	if($res){
		$imgUrl = "https://showseeker.s3.amazonaws.com/media/movie/{$tmsId}/{$image}";
		$today  = date("Y-m-d H:i:s");
		$sql    = "INSERT INTO GalleryOverride (connectorId, URI,insertDat) VALUES ('$tmsId', '$imgUrl', '$today') ON DUPLICATE KEY UPDATE URI='$imgUrl', insertDat='$today'" ;
		$res    = mysqli_query($con, $sql);

		$userId  = $_SESSION['userid'];
		$remarks = '{"cover":"'.$imgUrl.'"}';
		$rootId  = getRootId($con,$tmsId);
		$sql     = "INSERT INTO EzMovieLog(rootId,TMSId,userId,updated,remarks,createdAt) VALUES ($rootId,'$tmsId',$userId,'cover','{$remarks}','{$today}')";
		$result  = mysqli_query($logDb, $sql);
	}
}
?>
<!DOCTYPE html>
<html lang="en-IN">
<head>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="/inc/foundation/css/normalize.css">
	<link rel="stylesheet" href="/inc/foundation/css/foundation.css">
	<script src="/inc/foundation/js/vendor/modernizr.js"></script>
</head>
<body>
	<br/>
	<center>
		<table width="85%">
			<tr>
				<td class="text-center">
					<h3>Show ID: <?php print $tmsId;?></h3>
				</td>
			</tr>
		</table>
		<br/>

		<?php if($res): ?>
			<div class="small-10"><div data-alert class="alert-box success radius">Cover image updated.</div></div>
		<?php else: ?>
			<div class="small-10"><div data-alert class="alert-box alert radius"><?php print ($imgUploadErr != "") ? $imgUploadErr : 'Sorry, there was a error. Please try again'; ?> </div></div>
		<?php endif; ?>

		<br/>
		
		<?php if($res): ?>
			<a href="<?php print $imgUrl; ?>" target="_blank"><img  src="<?php print $imgUrl; ?>"/></a>
			<br/>
			<br/>
		<?php else: ?>
			<a class="button small" href="javascript:history.go(-1);"><< Go Back</a>
		<?php endif; ?>
		<a class="button small" href="javascript:window.close()">Close Window</a>
	</center>
</body>
</html>

<?php
function getRootId($con,$tmsid){
	$sql ="SELECT ProgramRootid.rootid FROM ProgramRootid WHERE ProgramRootid.connectorId='$tmsid' GROUP BY ProgramRootid.rootid LIMIT 1";
	$res = mysqli_query($con, $sql);

	if($res->num_rows > 0){
		$row = $res->fetch_assoc();
		return $row['rootid'];
	} else {
		return 0;
	}
}

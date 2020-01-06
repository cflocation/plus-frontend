<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);
	session_start();
	$con = mysqli_connect("db4.showseeker.net","vastdbuser","jK6YK71tJ","ShowSeeker");
	if (date('N', time()) == 1) $dayofyear=date('z');
	else $dayofyear=date('z', strtotime('last Monday'));
//ADJUST FOR BUILDER STARTING AT 0
	$correctMonday =  $dayofyear +1;

$weeknum = date("W");

//GET USERID FROM EMAIL, USE USERID TO DETERMINE WHICH ZONES THEY ARE MEMBERS OF
	if (isset($_GET['uid'])) { $uid = $_GET['uid'] ; }

//GENERAL INFO
$general_sql = "SELECT users.email, users.corporationid, name, roleid FROM users INNER JOIN corporations ON users.corporationid = corporations.id inner join userroles on users.id = userid WHERE users.id = '$uid' ";
$info = mysqli_query($con, $general_sql);
$row_info = mysqli_fetch_assoc($info);
$roleid = $row_info['roleid'];
$cid =  $row_info['corporationid'];
?>
<!doctype html>
<head> 
<title>ShowSeeker - EzGrids</title>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<link rel="stylesheet" href="css/normalize.css">
<link rel="stylesheet" href="css/foundation.css">
<link rel="stylesheet" href="css/styles2.css" />
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<script src="js/vendor/modernizr.js"></script>
<style type="text/css">
body { 	font-family: "Trebuchet MS", "Helvetica", "Arial",  "Verdana", "sans-serif"; }
a { color: #184a74; text-decoration: none; font-weight: bold; }
small { color: #9e9f9f; }

.container {
	min-height: 300px;
	width: 80%;
	margin: 10px auto;
	position: relative;
	text-align:center;
	padding:0;
}
.block {
	height: 320px;
	width: 400px;
	display:inline-block;
	margin:10px;
	padding: 20px;
	background: #eeeeee;
}
.rounded-corners {
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	-khtml-border-radius: 10px;
	border-radius: 10px;
	border: 4px solid #c0c0c0;
}
.wrap{ border: 3px solid #d8d8d8; }
</style>
</head>
<br />
<div class="row">
  <div class="large-12 columns"><center><a href="index.php?logout=true"><img src="img/logo.png"></a></center></div>
</div>
<div id="main" class="container">
	<div class="row">
	  <div class="large-12 columns"><br /><center><h3><strong><em>E-z Grids </em>&#0153; </strong></h3></center></div>
	</div>
	<div class="row">
	  <div class="large-12 columns"><h4 class="subheader"><center><small>Welcome Back, <?php echo $row_info['email']; ?></small></center></h4></div>
	</div>
	<div class="row">
	  <div class="large-12 columns">
<?php 

if ($roleid != '15') {

//USE USERID TO FIND OFFICE ID
$sql1 = "SELECT * FROM useroffices where userid = $uid";
$office = mysqli_query($con, $sql1);
	while ($row1 = mysqli_fetch_assoc($office) ) { $oid[] = $row1['officeid'];  }
$offices = implode(",",$oid);

$sql2= "SELECT zones.id AS zoneid, zones.name AS zone, (SELECT COUNT(*) FROM zonenetworks WHERE zoneid = zones.id AND zones.deletedat IS NULL AND zones.isdma = 'NO') AS total,  marketzones.marketid FROM offices INNER JOIN marketzones ON offices.regionid = marketzones.marketid INNER JOIN zones ON marketzones.zoneid = zones.id WHERE offices.id in ( $offices ) and zones.isdma =  'NO' AND zones.deletedat IS NULL GROUP BY marketzones.marketid";

	$markets = mysqli_query($con, $sql2);
	while ($row2 = mysqli_fetch_assoc($markets) ) { $mid[] = $row2['marketid']; }
	$markets = implode(",",$mid);

}
else {

$sql_national = "SELECT regions.name, regions.id as marketid FROM regions WHERE regions.deletedat IS NULL AND regions.corporationid = $cid ORDER BY id";
$national_info = mysqli_query($con, $sql_national);
	while ($row5 = mysqli_fetch_assoc($national_info) ) { $mid[] = $row5['marketid']; }
	$markets = implode(",",$mid);


}

//get market name
	$sql3 = "SELECT * FROM `regions` WHERE `id` in ($markets) order by name asc";
	$market_info = mysqli_query($con, $sql3);

	while ($row3 = mysqli_fetch_assoc($market_info) ) { $market_name[] = $row3['name']; }
	$mnames = implode(",",$market_name);

//echo  $row_info['name'] ."<br>" . $mnames ."<br>"; 

$market_count = (count($mid));

echo "<table width='500' align='center' border='1' cellpadding='3' cellspacing='0' width='55%'><tr><th id='dlinks' style='font-size:12pt; background-color:#184a74; color:#ffffff; height:30px;'>Market</th><th id='dlinks'style='font-size:12pt; background-color:#184a74; color:#ffffff; height:30px;'>Net Count</th></tr>";

	$x=0;
	foreach ( $mid as $value ) {
	$callsigns= [];	
		$sql4 = "select tms_networks.name, tms_networks.callsign, tms_networks.networkid, zones.timezoneid AS timezoneid from tms_networks inner join zonenetworks on tms_networks.networkid = zonenetworks.networkid inner join zones on zonenetworks.zoneid = zones.id inner join marketzones on zones.id = marketzones.zoneid inner join regions on marketzones.marketid = regions.id where marketid = ($value) and zones.deletedat is null and zones.isdma = 'no' and tms_networks.dmanumber = 0 and regions.deletedat is null group by tms_networks.callsign" ; 
		
		$channel = mysqli_query($con, $sql4);
		$channel_count = mysqli_num_rows($channel);
			
			while ($row4 = mysqli_fetch_assoc($channel) ) {
				$callsigns[] = $row4['callsign']; 
				$tz = $row4['timezoneid'];
				switch ($tz) {
					case 1: 	
						$tza = 'ast';
						break;
					case 2: 	
						$tza = 'pst';
						break;
					case 3: 	
						$tza = 'mst';
						break;
					case 4: 	
						$tza = 'mdt';
						break;
					case 5: 	
						$tza = 'cst';
						break;
					case 6: 	
						$tza = 'est';
						break;
					case 7: 	
						$tza = 'pr';
						break;
					case 8: 	
						$tza = 'hast';
						break;
				}
			}
		$cs = implode(" ",$callsigns);

		//ZIP FILE LOGIC HERE
		$zonename2 = str_replace(' ', '_', $market_name[$x]);
		$zonename = str_replace('/', '_',  $zonename2);

		$zipname = "ShowSeeker_EzGrids_Market-".$zonename."_Week-".$weeknum.".zip"  ; 
		$zip = new ZipArchive;
		if ($zip->open("/var/www/html/showseeker.com/ezgrids/zips/".$zipname,  ZipArchive::CREATE)) {
			$dir1 = "/var/www/html/showseeker.com/ezgrids/download/".$tza."/xlsx/" ;
			$dir2 = "/var/www/html/showseeker.com/ezgrids/download/".$tza."/pdf/" ;


				foreach ( $callsigns as $value ) {			
					$filename1 =  $value .".xlsx" ; 
					$filename2 =  $value .".pdf" ; 
					$zip->addFile($dir1 . $filename1, "xls/" .$filename1);
					$zip->addFile($dir2 . $filename2, "pdf/" .$filename2);
				}

			$zip->close();
			}

		//BUILD TABLE DATA
		echo "<tr><td style='text-align:center;height:35px;'><center><a href='zips/$zipname' target='_blank'>".$row_info['name'] ." - " .$market_name[$x] ."</a></center></td><td style='text-align:center;height:35px;'><center>".$channel_count."</center></td></tr>";

		$x++;
//END OF THE FOR EACH STATEMENT
	}
?>
</table>
</div>
		<div id="myModal" class="reveal-modal" data-reveal>
			<div class="row">
			  <div class="large-12 columns"><br /><center><h3><strong>Contact Us</strong></h3></center></div>
			</div>
			<center>
					For Technical Support (difficulty logging in or other technical issues): 
					<br><a href="mailto:help@showseeker.com">help@showseeker.com</a><br><br>
					For assistance in using ShowSeeker, refer to the User Guides or FAQ's. If your answer is not found:
					<br><a href="mailto:support@showseeker.com">support@showseeker.com</a><br><br>
					For Suggestions on how we may improve our product:
					<br><a href="mailto:suggestions@showseeker.com">suggestions@showseeker.com</a><br><br>
					To submit Success Stories:
					<br><a href="mailto:wins@showseeker.com">wins@showseeker.com</a><br><br>
					If you have specific questions not covered above, call us at: 866-980-8278 
			<a class="close-reveal-modal">&#215;</a>
			</center>
		</div>
		<div style="height:100px;"></div>
		<div style="text-aign:center; width:100%;">
			<div><center><p><i class="fa fa-phone"></i> <a href="#" data-reveal-id="myModal">Contact Us</a></p></center></div>			
			<h4 class="subheader"><center><small>Software developed by Visual Advertising Sales Technology. U.S. Patent No. 7,742, 946 N.Z. Patent No. 537510 Copyright &copy; VAST 2003 - 2015.</small></center></h4>
		</div>



<script src="js/foundation.min.js"></script>
<script>
	$(document).foundation();
</script>

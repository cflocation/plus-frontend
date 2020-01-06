<!DOCTYPE html>

<?php 
	include_once('database.php');

	$urlParams 	= $_GET;
	$marketid 	= $_GET['marketid'];
	$sql 		= "	SELECT 		Corporation.name AS corporation, 
								Corporation.id AS corporationsid, 
								Market.divisionId,
								Market.name AS market, 
								Market.id AS marketid,
								Market.active, 
								Market.iseeker,
								Market.goApp,
								Market.snapshot,
								Market.SCXImporter as scx,
								Market.roundedResults,
								Market.ezRating  					
					
					FROM 		Market
					
					INNER JOIN 	Corporation 
					ON 			Market.corporationId = Corporation.id
					
					WHERE 		Market.id = {$marketid}";
					
	$result 	= mysql_query($sql);
	$row 		= mysql_fetch_assoc($result);
	$corpId 	= $row['corporationsid'];
	$uuid 		= uniqid();	
?>
<html lang="en">
		
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>ShowSeeker | Admin</title>
		
		<link href="https://showseeker.s3.amazonaws.com/public-site/assets/favicon.png" name="favicon" rel="shortcut icon" type="image/png">
		<link rel="stylesheet" href="https://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css" />
		<link rel="stylesheet" href="../inc//fontawesome/css/font-awesome.min.css?<?php print 'v='.uniqid();?>" />
		<link rel="stylesheet" href="css/style.css?<?php print 'v='.uniqid();?>"/>
	
		<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script src="https://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
	
		<script src="js/admin.js?<?php print 'v='.uniqid();?>"></script>
		<script src="js/markets.js?<?php print 'v='.uniqid();?>"></script>
	</head>
	
	<body>
		<div data-role="page" id="market-page" data-dom-cache="false" data-cache="false">
			<div data-role="header" data-position="fixed">
				<a href="/stg.admin/" rel="external" data-iconpos="notext" data-icon="back">Back</a>
				<h1>Region: <?php print $row["market"] ?></h1>
				<a href="#popupMenu" data-rel="popup" data-icon="bars" data-role="button" class="ui-btn-right" data-iconpos="notext" data-position-to="#position-header"></a>
		    </div>
			
			<div data-role="popup" id="popupMenu" data-theme="a">
				<ul data-role="listview" data-inset="true" data-theme="a">
					<li data-icon="false"><a href="/alpha" target="_self">ShowSeeker</a></li>
					<li data-icon="false">
						<a href="javascript:void(0)" onclick="openTutorial('https://showseeker.s3.amazonaws.com/tutorials/manuals/ShowSeeker_Spectrum-Reach_User-Admin-Guide_021419.pdf'); mixTrack('Admin - User Guide');" id="adminUserGuide">Admin User Guide</a>
					</li>			
				</ul>
			</div>
			
			<div data-role="content">
	
				<div data-role="collapsible-set" data-theme="b" data-content-theme="d">
					<?php				
					include_once('widgets/offices.php');
					include_once('widgets/users.php');
					?>
				</div>
			</div>
		</div>
	</body>
	
	<script>
		//check if logged in
	    if (localStorage.getItem("userId") === null || localStorage.getItem("apiKey") === null) {
	        window.location.href = "../login.php?logout=true&app=admin";
	    }    
	    var userid 	= localStorage.getItem("userId");
	    var apiKey 	= localStorage.getItem("apiKey");
	</script>	

	<script src="js/users/index.js?v=<?php print $uuid; ?>"></script>
	<script src="js/users/filter.js?v=<?php print $uuid; ?>"></script>
	<script src="js/filterOffices.js?v=<?php print $uuid; ?>"></script>
</html>
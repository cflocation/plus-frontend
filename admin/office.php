<?php 
    $officeId 	= $_GET['officeid'];
	$uuid 			= uniqid();	
    include_once('database.php');

    $sql = "SELECT 	Market.name AS market, 
				    Market.id AS marketid, 
				    Corporation.name AS corporation, 
				    Corporation.id AS corporationsid, 
				    Office.name AS office, 
				    Office.id AS officeId,
				    Office.phone AS phone,
				    Office.goApp,				         
				    Address.address, 
				    Address.address2, 
				    Address.stateId AS statesid, 
				    Address.city, 
				    Address.zip as zipcode
    FROM 			Office
    INNER JOIN 		Market 
    ON 				Office.regionId = Market.id
    INNER JOIN 		Corporation 
    ON 				Market.corporationId = Corporation.id
    INNER JOIN 		OfficeAddress 
    ON 				Office.id = OfficeAddress.officeId
    INNER JOIN 		Address 
    ON 				OfficeAddress.addressId = Address.id
    WHERE 			Office.id = {$officeId} ";

    $result 	= mysql_query($sql);
    $row 		= mysql_fetch_assoc($result);
    $statesql 	= "SELECT id, name, abbreviation FROM State where id = {$row['statesid']} ORDER BY name";
    $resultsql 	= mysql_fetch_assoc(mysql_query($statesql));
    $corpId 	= $row['corporationsid'];
    
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Admin | ShowSeeker</title>
		
		<link href="https://showseeker.s3.amazonaws.com/public-site/assets/favicon.png" name="favicon" rel="shortcut icon" type="image/png">		
		<link rel="stylesheet" href="https://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css" />
		<link rel="stylesheet" href="../inc/fontawesome/css/font-awesome.min.css?<?php print 'v='.uniqid();?>" />
		<link rel="stylesheet" href="css/style.css?<?php print 'v='.uniqid();?>"/>
		<link rel="stylesheet" href="css/right.panel.css?<?php print 'v='.uniqid();?>"/>		

		<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script src="https://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>		

		<script src="js/global.js?<?php print 'v='.uniqid();?>"></script>
		<script src="js/offices.js?<?php print 'v='.uniqid();?>"></script>		
	</head>
	
	<body>	
		<div data-role="page" id="office-page" data-dom-cache="false" data-cache="false">
		
		    <div data-role="header" data-position="fixed">
		        <a href="#" rel="external" data-iconpos="notext" data-icon="back" id="returnToPage">Back</a>
		        <h1>Office: <?php print $row["office"] ?></h1>
				<a href="#popupMenu" data-rel="popup" data-icon="bars" data-role="button" class="ui-btn-right" data-iconpos="notext" data-position-to="#position-header"></a>
		    </div>
			<div data-role="popup" id="popupMenu">
				<ul data-role="listview" data-inset="true" data-theme="a">
					<li data-icon="false"><a href="/alpha" target="_self">ShowSeeker</a></li>
					<li data-icon="false">
						<a href="javascript:void(0)" onclick="openTutorial('https://showseeker.s3.amazonaws.com/tutorials/manuals/ShowSeeker_Spectrum-Reach_User-Admin-Guide_021419.pdf'); mixTrack('Admin - User Guide');" id="adminUserGuide">Admin User Guide</a>
					</li>			
				</ul>
			</div>
		    
		    <div data-role="content">
		
				<center>
					<h4 class="nopadding">
						<?php print $row["address"] ?>,
						<?php print $row["city"] ?>,
						<?php print $resultsql["abbreviation"] ?>,
						<?php print $row["zipcode"] ?>
					</h4>
			    </center>
		
		        <div data-role="collapsible-set" data-theme="b" data-content-theme="d">
		            <?php
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


		$(document).ready(function(){

			var regionParam 	= getUrlParameter('region');
			var regionIdParam 	= getUrlParameter('regionid');
			var officeFilter 	= getUrlParameter('officeid');
			var href 			= '/admin/';

			if(regionParam){
				href= 'region.php?marketid='+regionParam;
			}
			if(regionIdParam){
				href= 'region.php?marketid='+regionIdParam;
			}

			if(officeFilter){
				$('#usersWidget').trigger('expand');
			}
			
			$('#returnToPage').prop('href',href);
			
		})
	</script>

	<script src="js/users/index.js?v=<?php print $uuid; ?>"></script>
	<script src="js/users/filter.js?v=<?php print $uuid; ?>"></script>
</html>




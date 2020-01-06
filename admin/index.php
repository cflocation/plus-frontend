<!DOCTYPE html>

<html lang="en">
	<?php 	
		include_once('database.php');
		
		$sql 	= 	"SELECT 	name AS corporation, 
								id AS corporationsid,
								Corporation.Note
					FROM 		Corporation 
					
					INNER JOIN 	CorporationSetting
					ON 			Corporation.id = CorporationSetting.corporationId
					
					WHERE 	id = 46";
		
		$result 	= mysql_query($sql);
		$row 		= mysql_fetch_assoc($result);
		$corpId 	= $row['corporationsid'];
		$uuid 		= uniqid();
	?>
		
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link href="https://showseeker.s3.amazonaws.com/public-site/assets/favicon.png" name="favicon" rel="shortcut icon" type="image/png">		
		<link rel="stylesheet" href="https://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css" />
		
		<link rel="stylesheet" href="../inc//fontawesome/css/font-awesome.min.css?<?php print 'v='.uniqid();?>" />
		<link rel="stylesheet" href="css/style.css?<?php print 'v='.uniqid();?>"/>
		
		<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script src="https://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
		<script src="js/mixPanel.js"></script>	
		<script src="js/log.events.js"></script>	
		<script src="js/ip.js?v=<?php print $uuid; ?>"></script>	
		<title>Admin | ShowSeeker</title>
	</head>
	
	
	<body>
		<div data-role="page" id="corporation-page">	
		    <div data-role="header" data-position="fixed">
				<h1><?php print ($row['corporation']); ?></h1>
				<a href="#popupMenu" data-rel="popup" data-icon="bars" data-role="button" class="ui-btn-right" data-iconpos="notext" data-position-to="#position-header"></a>
		    </div>
			<div data-role="popup" id="popupMenu">
				<ul data-role="listview" data-inset="true" data-theme="a">
					<li data-icon="false"><a href="/" target="_self">ShowSeeker</a></li>
					<li data-icon="false">
						<a href="javascript:void(0)" onclick="openTutorial('https://showseeker.s3.amazonaws.com/tutorials/manuals/ShowSeeker_Spectrum-Reach_User-Admin-Guide_021419.pdf'); mixTrack('Admin - User Guide');" id="adminUserGuide">Admin User Guide</a>
					</li>			
				</ul>
			</div>
		    <div data-role="content">
		        <div data-role="collapsible-set" data-theme="b" data-content-theme="d">			
		            <?php
		                include_once('widgets/regions.php');
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
	
	
	<script>
		$(document).on('pagehide', 'div', function (event, ui) { 
		    var page = $(event.target);
		    if (page.attr('data-cache') == 'never') { 
		        page.remove(); 
		    }; 
		});
	</script>
	
	<script src="js/users/index.js?v=<?php print $uuid; ?>"></script>
	<script src="js/users/filter.js?v=<?php print $uuid; ?>"></script>
	<script src="js/filterOffices.js?v=<?php print $uuid; ?>"></script>
</html>
<?php $uuid = uniqid();?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="cache-control" content="no-cache"> <!-- tells browser not to cache -->
		<meta http-equiv="expires" content="0"> <!-- says that the cache expires 'now' -->
		<meta http-equiv="pragma" content="no-cache"> <!-- says not to use cached stuff, if there is any -->
		
		<link href="https://showseeker.s3.amazonaws.com/public-site/assets/favicon.png" name="favicon" rel="shortcut icon" type="image/png">		
		<link rel="stylesheet" rev="stylesheet" href="css/index.css?uid=<?php print $uuid; ?>" />	
		<link rel="stylesheet" rev="stylesheet" href="css/navmenu.css" />
		<link rel="stylesheet" href="css/custom-theme/jquery.ui.all.css">
		<link rel="stylesheet" href="css/custom-theme/jquery-ui-1.8.21.custom.css">
		<link rel="stylesheet" href="css/jquery.timepicker.css">
		<link rel="stylesheet" href="js/jquery-ui-multiselect-widget-master/jquery.multiselect.css"  type="text/css" >
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/ui-lightness/jquery-ui.css" type="text/css">
		<link rel="stylesheet" rev="stylesheet" href="css/ui.dropdownchecklist.themeroller.css" / >
		
		<title>Calendar | ShowSeeker</title>
		
		<script>document.domain = "showseeker.com"</script>
		<script src="js/jquery-1.8.1.min.js"></script>
		<script src="js/ui/minified/jquery.ui.core.min.js"></script>
		<script src="js/ui/jquery.ui.datepicker.js"></script>
		<script src="js/external/jquery.ui.timepicker.js"></script>	
		<script src="js/external/date.js"></script>		
	</head>

	

	<body>
	
		
		<div id ="formContainer" style="z-index=1000000; min-width:1124px;">
		
			<div class="topheader" id="topheaderId" style="width:100%;display:none;"> 
				<div class="filterprograms"></div>
				<div class="separator">&nbsp;</div>
				<div class="innerparams" id="ctlfilterprogram" style="background-color:#3D003D; color:#fff;"></div>
				<div class="innercontrols" id="ctldetails" style="width:50px;">
					<span class="plus">
						<a title="Show More Details">
							+ More Details
						</a>
					</span>
					<span class="minus">
						<a title="Show Less">
							- Less Details
						</a>
					</span> 
				</div>
				
				<div class="separator">&nbsp;</div>
				
				<div class="innercontrols" id="ctlproposal" style="width:auto;">
					<span>
						Proposal: 
					</span>
					<input name="proposalnew" 	id="proposalnew"  	  class="pslinputs"  value=""	>
					<input type="button" 		id="createproposal"    class="add" 			value=" Create " >
		
					<span>
					or
					</span>
					
					<select class="filters"   id="proposalList" name="proposalList">
						<option value=""></option>
					</select>
				</div>
				
				<div class="separator">&nbsp;</div>
				
				<div class="innercontrols" id="ctlSelectall" style="width:auto;">
					Select All
					<input type="checkbox" id="select_all" title="Select / Deselect All" value="0">
				</div>
			</div>
			
			<div class="topheader2" id="topheaderId2" style="width:100%;display:none;">
			
				<div class="innerparams"  style="background-color:#3D003D; color:#fff; padding: 4;">
					<input class="clearable noXButton" type="text" id="searchShow" value="" placeholder="Search Title" style="font-size: 8pt; width: 187px">
				</div>
				
				<div class="separator">&nbsp;</div>	
				<div class="separator">&nbsp;</div>
				<div class="innerparams" style="background-color:#3D003D; color:#fff; display: none;">
					Markets
					<select id="usrmarkets" name="usrmarkets" class="filters"></select>
				</div>
				<div class="innerparams" id="ctlzones" style="background-color:#3D003D; color:#fff;">
			
				</div>
				
				<div class="separator">&nbsp;</div>	
				<div class="separator">&nbsp;</div>
					
				<div class="innerparams" id="timeSelector"  style="background-color:#3D003D; color:#fff;">		
					
				</div>
				<div class="separator">&nbsp;</div>	
				<div class="separator">&nbsp;</div>
				<span class="delete"  type="reset" id="resetegrid" style="padding-top:4px; padding-right:8px; padding-bottom:4px; padding-left:8px;">Reset</span>
			</div>
			
			<div style="height:10px; width:100%; clear:both;"></div>
		</div>
		
		<div id="disclaimer" class="disclaimer"><span class="closedisclaimer" title="Close disclaimer">x</span>
			This information is <b>Projected</b> and is subject to change by the Networks. 
			We are providing this as a courtesy but strongly suggest that if you add these shows to a schedule, you re-run your search once the program enters the ShowSeeker database.
		</div>
		
		<!--- TAB NAVIGATOR --->
		<div id="tabcontainer"></div>
		
		<!--- GRID CONTAINER --->		
		<div id="boxBody" style="position:relative; visibility:hidden; min-width:1124px;"></div>
	
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>	
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js" type="text/javascript"></script>	
		<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
		<script src="js/ui.dropdownchecklist.js"></script>
		<script src="js/calendar/launcher.js?uid=<?php print $uuid; ?>"></script>
		<script src="js/calendar/controls.js?uid=<?php print $uuid; ?>"></script>
		<script src="js/calendar/add.lines.js?uid=<?php print $uuid; ?>"></script>
		<script src="js/calendar/calendars.js?uid=<?php print $uuid; ?>"></script>
		<script src="js/calendar/cell.grayout.js?uid=<?php print $uuid; ?>"></script>
		<script src="js/calendar/cell.height.js?uid=<?php print $uuid; ?>"></script>
		<script src="js/calendar/cell.reset.js?uid=<?php print $uuid; ?>"></script>	
		<script src="js/calendar/delete.programs.js?uid=<?php print $uuid; ?>"></script>
		<script src="js/calendar/filter.networks.js?uid=<?php print $uuid; ?>"></script>
		<script src="js/calendar/filter.othersports.live.js?uid=<?php print $uuid; ?>"></script>	
		<script src="js/calendar/filter.programs.js?uid=<?php print $uuid; ?>"></script>
		<script src="js/calendar/filter.times.js?uid=<?php print $uuid; ?>"></script>	
		<script src="js/calendar/filter.titles.js?uid=<?php print $uuid; ?>"></script>	
		<script src="js/calendar/main.js?uid=<?php print $uuid; ?>"></script>		
		<script src="js/calendar/market.zones.js?uid=<?php print $uuid; ?>"></script>		
		<script src="js/calendar/projected.disclaimer.js?uid=<?php print $uuid; ?>"></script>					
		<script src="js/calendar/proposal.create.js?uid=<?php print $uuid; ?>"></script>				
		<script src="js/calendar/proposal.lines.js?uid=<?php print $uuid; ?>"></script>
		<script src="js/calendar/proposals.js?uid=<?php print $uuid; ?>"></script>
		<script src="js/calendar/reset.search.js?uid=<?php print $uuid; ?>"></script>
		<script src="js/calendar/search.show.js?uid=<?php print $uuid; ?>"></script>
		<script src="js/calendar/select.all.js?uid=<?php print $uuid; ?>"></script>
		<script src="js/calendar/tab.highlighting.js?uid=<?php print $uuid; ?>"></script>
		<script src="js/calendar/tab.navigation.js?uid=<?php print $uuid; ?>"></script>	
		<script src="js/calendar/tbd.js?uid=<?php print $uuid; ?>"></script>		
		<script src="js/calendar/toggle.deleted.programs.js?uid=<?php print $uuid; ?>"></script>	
		<script src="js/calendar/toggle.episode.desc.js?uid=<?php print $uuid; ?>"></script>	
		<script src="js/calendar/user.proposals.js?uid=<?php print $uuid; ?>"></script>			
		<script src="js/calendar/window.manager.js?uid=<?php print $uuid; ?>"></script>
		<script src="js/calendar/zone.controller.js?uid=<?php print $uuid; ?>"></script>
	
	</body>
</html>
	
	
	
	
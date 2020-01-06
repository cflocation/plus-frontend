<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="cache-control" content="no-cache"> <!-- tells browser not to cache -->
	<meta http-equiv="expires" content="0"> <!-- says that the cache expires 'now' -->
	<meta http-equiv="pragma" content="no-cache"> <!-- says not to use cached stuff, if there is any -->
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" /> 
		
	<title>Cable Premiere, Finales, Live Sports Events Calendar</title>
	
		
	<link rel="stylesheet" rev="stylesheet" href="css/index.css?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>" />	
	<link rel="stylesheet" rev="stylesheet" href="css/navmenu.css" />
	
	<link rel="stylesheet" href="css/custom-theme/jquery.ui.all.css">
	<link rel="stylesheet" href="css/custom-theme/jquery-ui-1.8.21.custom.css">
	
	<link rel="stylesheet" href="css/jquery.timepicker.css">
	
	
	
	<link rel="stylesheet" href="js/jquery-ui-multiselect-widget-master/jquery.multiselect.css"  type="text/css" >
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/ui-lightness/jquery-ui.css" type="text/css">
	<link rel="stylesheet" rev="stylesheet" href="css/ui.dropdownchecklist.themeroller.css" / >
	
		
	
	<script>document.domain = "showseeker.com"</script>
	<script src="js/jquery-1.8.1.min.js"></script>
	<script src="js/ui/minified/jquery.ui.core.min.js"></script>
	<script src="js/ui/jquery.ui.datepicker.js"></script>
	<script src="js/external/jquery.ui.timepicker.js"></script>	
	<script src="js/external/date.js"></script>		

</head>

	

<body>


<!--- E-z Grids Launcher gets settings from SS+ --->
<cfinclude template="launcher.cfm">

	
<!--- GETTING NETWORK PROGRAMMING  --->
<cfinclude template="settings.cfm">

<!--- HEADER PANELCONTROLS --->
<div style="z-index=1000000">
	<cfinclude template="controls.cfm">
</div>


<div id="disclaimer" class="disclaimer"><span class="closedisclaimer" title="Close disclaimer">x</span>
	This information is <b>Projected</b> and is subject to change by the Networks. 
	We are providing this as a courtesy but strongly suggest that if you add these shows to a schedule, you re-run your search once the program enters the ShowSeeker database.
</div>

<!--- TAB NAVIGATOR --->
<div id="tabcontainer">

	<cfoutput query="bcmonths"> 
	
	<cfset tms_enddate 	= '#bcmonth#'&'-1-'&'#bcyear#'>

	<cfif tms_enddate LTE tms_data_enddate>
		<div class="top"><span id="tab#BCMONTH#" class="tabNavigator" style="cursor:pointer;">#MonthAsString(bcmonths.bcmonth)# <!--- #bcmonths.BC_YEAR# ---></span></div>
	<cfelse>
		<div class="topprojected"><span id="tab#BCMONTH#" class="tabNavigator" style="cursor:pointer;">#MonthAsString(bcmonths.bcmonth)# <!--- #bcmonths.BC_YEAR# ---></span></div>
	</cfif>
	
	</cfoutput>

</div>


<div id="waitingmsg"><center>Building Calendar . . . Please Wait<BR><img src="assets/ajax.gif"></center></div>
<div id="updatinggmsg" style="display: none;"><center>Updating Data . . .<BR><img src="assets/ajax.gif"></center></div>

<!--- GRID CONTAINER --->		
<div id="boxBody" style="position:relative; visibility:hidden;">

<!--- CREATING THE CALENDAR CONTENTS BY MONTH --->
<cfloop query="bcmonths"> 

<cfset _month = bcmonths.BCMONTH>

<div class="parent">


<!--- DAYS OF WEEK --->
<cfoutput>

<div style="clear:both;">
	<cfloop list="#weekDays#" index="name">
	<div class="dayofWeek">#name#</div>
	</cfloop>
</div>

</cfoutput>


<!--- WEEKS OF BROADCAST MONTH --->
<cfquery name="bcweeks" dbtype="query">
	SELECT WEEK, WDATE FROM bccalendar WHERE BCMONTH = #_month# ORDER BY WDATE 
</cfquery>

<cfloop query="bcweeks">


<cfset _week = bcweeks.WEEK>
<cfset _date = bcweeks.WDATE>

<!--- SHOWS BY WEEK --->					
<cfquery name="showByWeek" dbtype="query">
	SELECT 	* 
	FROM 		programSchedule
	WHERE 	STARTDATE BETWEEN '#DateFormat(_date,"mm/dd/yy")#' AND '#DateFormat(DateAdd("d",6,_date),"mm/dd/yy")#'
</cfquery>



<!--- IF THERE IS AT LEAST ONE SHOW IN THE WEEK --->
<cfif showByWeek.recordCount NEQ 0>

	<cfprocessingdirective suppressWhiteSpace="Yes">
		<div class="weekrow">
	
			<cfloop from="#WDATE#" to="#DateAdd('d',6,WDATE)#" step="#CreateTimeSpan(1,0,0,0)#" index="BC_DATE">
		
				<cfset _wday = BC_DATE>
		
				<!--- INDIVIDUAL SHOWS BY DAY --->
				<cfquery name="showByDay" dbtype="query">
					SELECT 	PROGRAM, CALLSIGN, cast(STARTTIME as time) as STARTTIME, AIRDATE, premiere, EPISODE, SHOWDESC, PROGRAM_KEY,PREMIEREANDLIVE,LIVE, PROJECTED, PACKAGEID, UPDATEDAT, CREATEDAT, DURATION
					FROM 		showByWeek
					WHERE 	STARTDATE = '#DateFormat(_wday,"mm/dd/yy")#' ORDER BY STARTTIME
				</cfquery>
		
				<cfoutput>
				<div class="show">
					<div class="calendarDay">#DateFormat(BC_DATE,"mmm")# #DateFormat(BC_DATE,"dd")#</div>
		
					<div class="dailyProgramming" id="#DateFormat(BC_DATE,"mmm")##DateFormat(BC_DATE,"dd")#">
						<cfloop query="showByDay">								
							<cfif PROJECTED EQ 1 AND showByDay.PREMIEREANDLIVE EQ 'Live'>
							<div class="calendar-program pLive" style="display:none;">
							<cfelse>
							<div class="calendar-program #Replace(showByDay.PREMIEREANDLIVE,' ','')#" style="display:none;">							
							</cfif>	
								<div class="externalprojected">
									<span class="createdRecord" style="float:right">#showByDay.Createdat#</span>
									<span class="updatedRecord" style="float:right">#showByDay.Updatedat#</span>
								</div>
								<cfset programStime = #Replace(Replace(TimeFormat(showByDay.STARTTIME,'h:mmt'),':00',''),'12A','12M')#>
								<span class="schedule">
									<input type="checkbox" class="ssevent" id="#PROGRAM_KEY#-#zoneid#">
									<span class="starttimeclass">#programStime#</span>
									<span class="tbd">#showByDay.DURATION#</span> 
									<i>#showByDay.Live#</i>
								</span>
								<a title="#showByDay.episode#" class="gamedetails programTitle">#showByDay.PROGRAM#</a>
								<span class="callsign">#showByDay.CALLSIGN#</span>
								<div class="allgamedetails">
									<span class="episode">#showByDay.EPISODE#</span>
									<cfif showByDay.PREMIEREANDLIVE NEQ 'Live'>
										<span class="showdescription">#showByDay.SHOWDESC#</span>
										<span class="premiereflag">#PROJECTED# #showByDay.premiere#</span>
									</cfif>
									<span class="packageflag">#PACKAGEID#</span>
								</div>
							</div>
						</cfloop>
					</div>
													
				</div>
				</cfoutput>
			</cfloop>
	
		</div>
	</cfprocessingdirective>
	
<cfelse><!--- NO SHOWS FOUND IN THE DAY --->

	<div class="weekrow">	
		<cfoutput>
			<cfloop from="#WDATE#" to="#DateAdd('d',6,WDATE)#" step="#CreateTimeSpan(1,0,0,0)#" index="BC_DATE">
				<div class="show">
					<div class="calendarDay">#DateFormat(BC_DATE,"mmm")# #DateFormat(BC_DATE,"d")#</div>
				</div>			
			</cfloop>
		</cfoutput>
	</div>

</cfif>

</cfloop>
</div>
</cfloop>
		</div>
	</div>

</body>
</html>


	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>	
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js" type="text/javascript"></script>	

	
	<script src="js/jquery-ui-1.8.13.custom.min.js"></script>
	<script src="js/ui.dropdownchecklist.js"></script>
	
	
	
	<script src="js/calendar/add.lines.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>
	<script src="js/calendar/calendars.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>
	<script src="js/calendar/cell.grayout.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>
	<script src="js/calendar/cell.height.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>
	<script src="js/calendar/cell.reset.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>	
	<script src="js/calendar/delete.programs.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>
	<script src="js/calendar/filter.networks.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>
	<script src="js/calendar/filter.othersports.live.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>	
	<script src="js/calendar/filter.programs.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>
	<script src="js/calendar/filter.times.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>	
	<script src="js/calendar/filter.titles.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>	
	<script src="js/calendar/main.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>		
	<script src="js/calendar/market.zones.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>		
	<script src="js/calendar/program.description.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>			
	<script src="js/calendar/projected.disclaimer.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>					
	<script src="js/calendar/proposal.create.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>				
	<script src="js/calendar/proposal.lines.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>
	<script src="js/calendar/proposals.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>
	<script src="js/calendar/reset.search.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>
	<script src="js/calendar/search.show.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>
	<script src="js/calendar/select.all.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>
	<script src="js/calendar/submit.grid.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>	
	<script src="js/calendar/tab.highlighting.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>
	<script src="js/calendar/tab.navigation.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>	
	<script src="js/calendar/tbd.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>		
	<script src="js/calendar/toggle.deleted.programs.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>	
	<script src="js/calendar/toggle.episode.desc.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>	
	<script src="js/calendar/user.proposals.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>			
	<script src="js/calendar/window.manager.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>
	<script src="js/calendar/zone.controller.js?uid=<cfoutput>#RandRange(0,10000)#</cfoutput>"></script>
	<script>var apiKey = '<cfoutput>#apiKey#</cfoutput>'</script>

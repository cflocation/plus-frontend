	<!--- EZCALENDAR CLASS --->
	<cfset  ezcalendar 	= CreateObject("component", "cfc.index2")>

	<cfset  markets 	= CreateObject("component", "cfc.markets2")>
	
	<!--- ZONE --->
	<cfset zoneid 			= zones>
		
	<!--- USER MARKETS ---> 
	<cfset usermarkets 	 	= markets.getMarkets(userid=#userid#)>	

	<!--- USER ZONES ---> 	
	<!--- cfset zones 	 		= ezcalendar.zones(userid=#userid#, zoneid=#zoneid#) --->	
		
	<!--- LOCAL USER DATE TIME --->
	<!--- cfset localdatetime = DateFormat(DateAdd('h',zones.UTCDIFFERENCE,Now()),'yyyy-mm-dd') --->
	<cfset localdatetime = DateFormat(DateAdd('h',-5,Now()),'yyyy-mm-dd')> <!--- ETS --->

		
	<!--- MAPPING TIME ZONES  --->
	<cfset tzmapped 		=  ezcalendar.timezonemapping(UCase(form.tz))>
	<cfset timezoneid 		= tzmapped[1]>
	<cfset timeoffset 		= tzmapped[2]>	
	<cfset dstStart 		= tzmapped[3]>
	<cfset dstEnd 			= tzmapped[4]>	

		
	<!--- START DATE --->
	<cfif DateDiff('d',localdatetime,startDate) LT 0>
	
		<cfset startDate 	= DateFormat(localdatetime)>
	
	</cfif>


	
		
	<!--- END DATE --->				
	
	<cfif DateDiff('d',localdatetime,endDate) GT 56 >
	
		<cfset gridEDate 	= DateFormat(DateAdd('d',56,localdatetime),"yyyy-mm-dd")>
	
	</cfif>



	<!--- Real Start Date --->
	<cfset rsDate = ezcalendar.GetLastDayOfWeekOfMonth(startDate,1)>
	
	<!--- FORMATTING DATES --->
	<cfset starDate 		= DateFormat(startDate, "yyyy-mm-dd")>
	<cfset endDate 		= DateFormat(endDate, "yyyy-mm-dd")>	
	<cfset sDate 			= DateFormat(DateAdd('d',0,startDate), "yyyy-mm-dd")>
	<!--- cfset sDate 			= DateFormat(rsDate, "yyyy-mm-dd") --->	 

		
	<!--- START TIME --->
	<cfset gridSTime 		= TimeFormat(ReplaceNoCase(sTime,'24:00','00:00:00'),'HH:mm:ss')>


		
	<!--- END TIME --->
	<cfset gridETime 		= TimeFormat(Replace(Replace(eTime,'00:00','23:59'),'24:00','23:59'),'HH:mm:ss')>


				
	<!--- USER PROPOSAL --->	
	<cfset proposal 		=  ezcalendar.formatproposals(ezcalendar.proposals(userid=#userid#))>	




	<!--- selected network schedules --->	
	<cfset programming 			=  ezcalendar.search(sdate,endDate, gridSTime,gridETime, zoneid, userid, apiKey)>	
	<cfset programSchedule 		=  ezcalendar.scheduleformater(programming)>


	<!--- NETWORKS AVAILABLE FROM THE SELECTED ZONE --->
	<cfquery dbtype="query" name="zonenetworks">select CALLSIGN, NETWORKID from programSchedule group by  CALLSIGN, NETWORKID ORDER BY CALLSIGN </cfquery>

	
	<!--- CALCULATING DATE RANGE BY WEEKS TO BUILD TAB NAVIGATOR --->	
	<cfquery dbtype="query" name="getDifferntDays">
		SELECT 	MIN(startDate) AS iniGrid, MAX(startDate) AS endGrid
		FROM 		programSchedule
	</cfquery>


	<cfset wRange 			= {}>
	<cfset fdate 			=  dateAdd('d',0,getDifferntDays.endGrid)>
	<cfset wRange.sDate 	= dayOfWeek(startDate) gt 1 ? dateAdd("d", 2-dayOfWeek(startDate), startDate) : dateAdd("d", -6, startDate) />
	<cfset wRange.tmseDate 	= dayOfWeek(endDate) gt 1 ? dateAdd("d", 2-dayOfWeek(endDate), endDate) : dateAdd("d", -6, endDate) />
	<cfset wRange.eDate 	= dayOfWeek(fdate) gt 1 ? dateAdd("d", 2-dayOfWeek(fdate), fdate) : dateAdd("d", -6, fdate) />
	<cfset wRange.numwks 	= DateDiff("ww",wRange.sDate, wRange.eDate)>					
	<cfset numberofWeeks 	= dateDiff("ww",wRange.sDate,dateAdd('d',6,fdate))+1>			




	<!--- BROADCAST CALENDAR MONTHS/WEEKS ---> 
	<cfset bccalendar 	 	=  ezcalendar.broadcastcalendar(wRange.sDate, wRange.eDate)>



	<!--- BROADCAST CALENDAR MONTHS ---> 
	<cfquery dbtype="query" name="bcmonths">
		SELECT DISTINCT bcmonth, bcyear from bccalendar order by bcyear, bcmonth		
	</cfquery>
	
	
		
	<!--- TMS DATA END MONTH ---> 
	<cfset tms_data_enddate 	= '#wRange.tmseDate#'>




	<!--- GLOBAL DAYS OF THE WEEK VARIABLE --->
	<cfset weekDays 			= "Mon,Tue,Wed,Thu,Fri,Sat,Sun">

		
		
		
	<!--- DAY HOURS --->
	<cfquery datasource="showseeker" name="hours">
		SELECT 	*	FROM		hour 		ORDER	BY HOUR_ID 		LIMIT 0, 100
	</cfquery>



	<!--- FILTER OPTIONS --->
	<cfquery  dbtype="query" name="filters">
		SELECT 	DISTINCT PROGRAM FROM programSchedule WHERE LIVE = 'Live' AND PROGRAM IN ('NFL Football','MLS Soccer','NHL Hockey','NBA Basketball','NASCAR Racing','College Football','College Basketball','College Baseball','MLB Baseball','PGA Tour Golf') 
		 OR PROGRAM LIKE '%Bowling%' OR PROGRAM = 'Tennis' ORDER BY PROGRAM
	</cfquery>

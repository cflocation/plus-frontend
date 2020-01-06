<cfcomponent>
	
	<cffunction name="search">
		<cfargument name="zoneId" 	 	required="yes">			
		<cfargument name="timezone" 	required="no" default="2" >
		<cfargument name="eDate" 		default="#DateFormat(DateAdd("d",56,Now()),'yyyy-mm-dd')#">
		<cfargument name="endTime" 		default="23:59">
		<cfargument name="sDate" 		default="#DateFormat(Now(),'yyyy-mm-dd')#">
		<cfargument name="startTime" 	default="00:00">
		<cfset session.obs_daylight =1>
		<cfset zonenets = stations(arguments.zoneId)>
		<cfset timeoffset = time_zone(arguments.timezone)>
		<cfset projectednets = ''> 
		
		
			
		<cfquery name="searchResults" datasource="NETSCHEDULES" >
		<cfoutput>
				<!--- ARIZONA CLIENTS,  NO DAYLIGHT CAHNGE IS OBSERVED--->
				<CFIF 	session.obs_daylight EQ 0>
						<CFSET searchStartTime 	= "#STARTTIME#">
						<CFSET searchEndTime 	= "#ENDTIME#">
						<CFSET searchStartDate 	= "#sDate#">
						<CFSET searchEndDate	= "#eDate#">
						<cfinclude template="query.cfm">
			
				<!--- 	Current Date/Time IS LESS THAN the Begining of Daylight Time
						AND Search Start Date IS LESS THAN the Begining of Daylight Time
						AND Search End Date IS  LESS THAN the  Begining of Daylight Time
						OR
						Current Date/Time IS GREATER THAN the Ending of Daylight Time
						AND Search Start Date IS GREATER THAN the Ending of Daylight Time
						AND Search End Date  IS GREATER THAN the  Ending of Daylight Time--->
				<CFELSEIF (#DateFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), 'm/dd/yyyy')#& #TimeFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), "HH:MM")# LT application.dstStart&"02:00"
						   AND  DateFormat(sdate,"mm/dd/yyyy")&"#STARTTIME#" LT application.dstStart&"02:00" 
						   AND #eDate# LT  application.dstStart&"02:00") 
						   OR
						  (#DateFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), 'm/dd/yyyy')#& #TimeFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), "HH:MM")# GT application.dstEnd&"02:00"
						   AND DateFormat(sdate,"mm/dd/yyyy")&"#STARTTIME#" GT application.dstEnd&"02:00" 
						   AND #eDate# GT application.dstEnd&"02:00:00")
						   OR
						   (#DateFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), 'm/dd/yyyy')#& #TimeFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), "HH:MM")# GT application.dstStart&"02:00"
						   AND #DateFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), 'm/dd/yyyy')#& #TimeFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), "HH:MM")# LT application.dstEnd&"02:00"
						   AND DateFormat(sdate,"mm/dd/yyyy")&"#STARTTIME#" GT application.dstStart&"02:00" 
						   AND #eDate# LT application.dstEnd&"02:00:00")>
						<CFSET searchStartTime 	= "#STARTTIME#">
						<CFSET searchEndTime 	= "#ENDTIME#">
						<CFSET searchStartDate 	= "#sDate#">
						<CFSET searchEndDate	= "#eDate#">
						<cfset TIMEOFFSET 		= TIMEOFFSET>
						<cfinclude template="query.cfm">
						
				<!--- 	Current Day/Time IS LESS THAN the Begining of Daylight Change 
						AND Search START Date IS GREATER THAN the Begining of Daylight Change
						AND Search END   Date IS GREATER THAN the Begining of Daylight Change --->
				<CFELSEIF #DateFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), 'm/dd/yyyy')#& #TimeFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), "HH:MM")# LT application.dstStart&"02:00"
						AND DateFormat(sdate,"mm/dd/yyyy")&"#STARTTIME#" GT application.dstStart&"02:00" 
						AND #eDate# GT application.dstStart&"02:00:00" >
						<CFSET searchStartTime 	= "#STARTTIME#">
						<CFSET searchEndTime 	= "#ENDTIME#">
						<CFSET searchStartDate 	= "#sDate#">
						<CFSET searchEndDate	= "#eDate#">
						<cfset TIMEOFFSET 		= TIMEOFFSET + 1>
						<cfinclude template="query.cfm">
			
				<!---  Search Start Date LESS THAN the Begining of DayLight Change 
					   AND Search End Date  IS GREATER THAN begining daylight Change
					   SEARCH CROSSES BEGINING OF DAYLIGHT CHANGE  --->
				<CFELSEIF  #DateFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), 'm/dd/yyyy')#& #TimeFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), "HH:MM")# LT application.dstStart&"02:00"
					    AND DateFormat(sDate,"mm/dd/yyyy")&"#STARTTIME#" LT application.dstStart&"02:00" 
					    AND #eDate# GTE application.dstStart&"02:00:00">
						<CFSET searchStartTime 	= "#STARTTIME#">
						<CFSET searchEndTime 	= "#ENDTIME#">
						<CFSET searchStartDate 	= "#sDate#">
						<CFSET searchEndDate	= application.dstStart>
						<cfinclude template="query.cfm">
						<!--- UNION
						<CFSET searchStartTime 	= "#STARTTIME#">
						<CFSET searchEndTime 	= "#ENDTIME#">
						<CFSET searchStartDate 	= application.dstStart>
						<CFSET searchEndDate	= "#eDate#">
						<cfset TIMEOFFSET 		= TIMEOFFSET + 1>
						<cfinclude template="query.cfm" --->
						
				<!--- 	Current Day/Time IS LESS THAN the Ending of Daylight Change 
						AND Search Start Date IS GREATER THAN the Ending of Daylight Change
						AND Search End   Date IS GREATER THAN the Ending of Daylight Change--->
				<CFELSEIF   #DateFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), 'm/dd/yyyy')#& #TimeFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), "HH:MM")# GT application.dstStart&"02:00"
						AND #DateFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), 'm/dd/yyyy')#& #TimeFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), "HH:MM")# LT application.dstEnd&"02:00"
						AND DateFormat(sdate,"mm/dd/yyyy")&"#STARTTIME#" GT application.dstEnd&"02:00" 
						AND #eDate# GT application.dstStart&"02:00:00">
						<CFSET searchStartTime 	= "#STARTTIME#">
						<CFSET searchEndTime 	= "#ENDTIME#">
						<CFSET searchStartDate 	= "#sDate#">
						<CFSET searchEndDate	= "#eDate#">
						<cfset TIMEOFFSET 		= TIMEOFFSET - 1>
						<cfinclude template="query.cfm">									
			
				<!--- Search Date/Time IS LESS THAN the Ending Daylight Date 
					  AND the Search EndDate/Time IS GREATER THAN the  Ending Daylight Date
					  SEARCH CROSSES ENDING DAYLIGHT --->
				<CFELSEIF #DateFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), 'm/dd/yyyy')#& #TimeFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), "HH:MM")# LT application.dstEnd&"02:00"
				AND DateFormat(sdate,"mm/dd/yyyy")&"#STARTTIME#" LT application.dstEnd&"02:00" 
				AND #eDate# GT application.dstEnd&"02:00:00">
						<CFSET searchStartTime 	= "#STARTTIME#">
						<CFSET searchEndTime 	= "#ENDTIME#">
						<CFSET searchStartDate 	= "#sDate#">
						<CFSET searchEndDate	= application.dstEnd>
						<cfinclude template="query.cfm">
						<!--- UNION
						<CFSET searchStartTime 	= "#STARTTIME#">
						<CFSET searchEndTime 	= "#ENDTIME#">
						<CFSET searchStartDate 	= application.dstEnd>
						<CFSET searchEndDate	= "#eDate#">
						<cfset TIMEOFFSET 		= TIMEOFFSET - 1>
						<cfinclude template="query.cfm" --->
				
				<CFELSE>
						<CFSET searchStartTime 	= "#STARTTIME#">
						<CFSET searchEndTime 	= "#ENDTIME#">
						<CFSET searchStartDate 	= "#sDate#">
						<CFSET searchEndDate	= "#eDate#">
						<cfinclude template="query.cfm">
				</CFIF>
		</cfoutput>	
		</cfquery>
	
		<cfreturn searchResults/> 
	</cffunction>

	<cffunction name="stations">
		<cfargument name="zoneid" default=0>
		<cfquery dataSource="showseeker" name="zonestations">
			select networkid from zonenetworks where zoneid = '#arguments.zoneid#'
		</cfquery>
		<cfreturn ValueList(zonestations.networkid)>
	</cffunction>
	
	
	<cffunction name="time_zone">
		<cfargument name="timezoneid" default=2>
		<!---cfquery name="tz" datasource="EX">
			SELECT 	TZ_OFFSET 
			FROM 	TIME_ZONES
			WHERE 	TZ_TIME_ZONE_ID = '#arguments.timezoneid#'
		</cfquery>
		<cfreturn tz.TZ_OFFSET --->
	</cffunction>


	<cffunction name="timeruler">
		<cfquery name="tz" datasource="EX">
			SELECT 	hour_24hrs_format, HOUR_GRID, HOUR_SHORT, HOUR_FIXED
			FROM	HOUR
			ORDER	BY hour_24hrs_format
		</cfquery>
		<cfreturn tz>
	</cffunction>

</cfcomponent>
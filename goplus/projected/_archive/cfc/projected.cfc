<cfcomponent hint="ezgrids" output="yes">


<cffunction name="search" access="remote" returntype="any" output="yes">
	<cfargument name="zoneid" default="11">

	<cfset zonestations = nets()>

	
	<cfquery dataSource="solr" name="projected">
						SELECT
	
						DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Eastern'), '%Y-%c-%d 00:00:00') AS airdate,
						DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Eastern'), '%Y-%c-%d') AS startDate,
						DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Eastern'), '%H:%i') AS startTime,
						DATE_FORMAT(DATE_ADD(CONVERT_TZ(airdate,'GMT','US/Eastern'), INTERVAL ((SUBSTRING(duration,1,2)*60) + SUBSTRING(duration,3,2)) MINUTE), '%Y-%c-%d') AS endDate,
						DATE_FORMAT(DATE_ADD(CONVERT_TZ(airdate,'GMT','US/Eastern'), INTERVAL ((SUBSTRING(duration,1,2)*60) + SUBSTRING(duration,3,2)) MINUTE), '%H:%i') AS endTime,
						ROUND(0.0166666* CAST(Right(duration,2) AS UNSIGNED),1)+CAST(LEFT(duration,2) AS UNSIGNED) AS DURATION,								
						id as PROGRAM_KEY,
						stationnum as STATIONID,
						CALLSIGN,
						title as PROGRAM,
						epititle as EPISODE,
						descembed AS SHOWDESC,
						premierefinale AS PREMIERE,
						GENRE,
						LIVE,
						NEW,
						showid AS TMSID,
						CONCAT(premierefinale,LIVE) as PREMIEREANDLIVE,
						projected_source as SOURCE,
						'1' AS PROJECTED,
						authorized,
						CASE 
						WHEN createdat IS NULL THEN ''
						ELSE DATE_FORMAT(CREATEDAT,'%m-%d-%y')
						END AS CREATEDAT,
						CASE
						WHEN	updatedat IS NULL THEN ''
						ELSE DATE_FORMAT(UPDATEDAT, '%m-%d-%y')
						END  AS UPDATEDAT
			FROM 		programDataProjected 
			WHERE 	stationnum in (	

						#ValueList(zonestations.stationid)#
			
			) 
			AND 		deletedat IS NULL
				
	<!---
		
						SELECT	DATE_FORMAT(Date_Add(airDate,INTERVAL -4 HOUR),'%Y-%c-%d 00:00:00') AS airdate,
						DATE_FORMAT(Date_Add(airDate,INTERVAL -4 HOUR), '%Y-%c-%d') as startDate,
						DATE_FORMAT(Date_Add(airDate,INTERVAL -4 HOUR),'%H:%i') as startTime,
						endTime as endTime,
						endDate,
						ROUND(0.0166666* CAST(Right(duration,2) AS UNSIGNED),1)+CAST(LEFT(duration,2) AS UNSIGNED) AS DURATION,								
						id as PROGRAM_KEY,
						stationnum as STATIONID,
						CALLSIGN,
						title as PROGRAM,
						epititle as EPISODE,
						descembed AS SHOWDESC,
						premierefinale AS PREMIERE,
						GENRE,
						LIVE,
						NEW,
						showid AS TMSID,
						CONCAT(premierefinale,LIVE) as PREMIEREANDLIVE,
						projected_source as SOURCE,
						'1' AS PROJECTED,
						authorized,
						CASE 
						WHEN createdat IS NULL THEN ''
						ELSE DATE_FORMAT(CREATEDAT,'%m-%d-%y')
						END AS CREATEDAT,
						CASE
						WHEN	updatedat IS NULL THEN ''
						ELSE DATE_FORMAT(UPDATEDAT, '%m-%d-%y')
						END  AS UPDATEDAT
			FROM 		programDataProjected 
			WHERE 	stationnum in (	

						#ValueList(zonestations.stationid)#
			
			) 
			AND 		AIRDATE < '2013-11-03 02:00:00'
			AND 		deletedat IS NULL

UNION

						SELECT	DATE_FORMAT(Date_Add(airDate,INTERVAL -5 HOUR),'%Y-%c-%d 00:00:00') AS airdate,
						DATE_FORMAT(Date_Add(airDate,INTERVAL -5 HOUR), '%Y-%c-%d') as startDate,
						DATE_FORMAT(Date_Add(airDate,INTERVAL -5 HOUR),'%H:%i') as startTime,
						endTime as endTime,
						endDate,
						ROUND(0.0166666* CAST(Right(duration,2) AS UNSIGNED),1)+CAST(LEFT(duration,2) AS UNSIGNED) AS DURATION,								
						id as PROGRAM_KEY,
						stationnum as STATIONID,
						CALLSIGN,
						title as PROGRAM,
						epititle as EPISODE,
						descembed AS SHOWDESC,
						premierefinale AS PREMIERE,
						GENRE,
						LIVE,
						NEW,
						showid AS TMSID,
						CONCAT(premierefinale,LIVE) as PREMIEREANDLIVE,
						projected_source as SOURCE,
						'1' AS PROJECTED,
						authorized,
						CASE 
						WHEN createdat IS NULL THEN ''
						ELSE DATE_FORMAT(CREATEDAT,'%m-%d-%y')
						END AS CREATEDAT,
						CASE
						WHEN	updatedat IS NULL THEN ''
						ELSE DATE_FORMAT(UPDATEDAT, '%m-%d-%y')
						END  AS UPDATEDAT					
			FROM 		programDataProjected 
			WHERE 	stationnum in (	

						#ValueList(zonestations.stationid)#						
			
			)
			AND 		AIRDATE > '2013-11-03 02:00:00'
			AND 		AIRDATE < '2014-03-09 02:00:00'			
			AND 		deletedat IS NULL	
			
UNION


						SELECT	DATE_FORMAT(Date_Add(airDate,INTERVAL -4 HOUR),'%Y-%c-%d 00:00:00') AS airdate,
						DATE_FORMAT(Date_Add(airDate,INTERVAL -4 HOUR), '%Y-%c-%d') as startDate,
						DATE_FORMAT(Date_Add(airDate,INTERVAL -4 HOUR),'%H:%i') as startTime,
						endTime as endTime,
						endDate,
						ROUND(0.0166666* CAST(Right(duration,2) AS UNSIGNED),1)+CAST(LEFT(duration,2) AS UNSIGNED) AS DURATION,								
						id as PROGRAM_KEY,
						stationnum as STATIONID,
						CALLSIGN,
						title as PROGRAM,
						epititle as EPISODE,
						descembed AS SHOWDESC,
						premierefinale AS PREMIERE,
						GENRE,
						LIVE,
						NEW,
						showid AS TMSID,
						CONCAT(premierefinale,LIVE) as PREMIEREANDLIVE,
						projected_source as SOURCE,
						'1' AS PROJECTED,
						authorized,
						CASE 
						WHEN createdat IS NULL THEN ''
						ELSE DATE_FORMAT(CREATEDAT,'%m-%d-%y')
						END AS CREATEDAT,
						CASE
						WHEN	updatedat IS NULL THEN ''
						ELSE DATE_FORMAT(UPDATEDAT, '%m-%d-%y')
						END  AS UPDATEDAT
			FROM 		programDataProjected 
			WHERE 	stationnum in (	

						#ValueList(zonestations.stationid)#
			
			) 
			AND 		AIRDATE > '2014-03-09 02:00:00'
			AND 		deletedat IS NULL
			
			 ---->
			
					
			ORDER BY airDate	
					
			
			
		</cfquery>

		
		<cfreturn projected>
		
</cffunction>






	<!--- USER PROPOSALS --->
	<cffunction name="proposals" access="public">
	
		<cfparam  default="160" name="arguments.userid">
		
		<cftry>
			<cfquery datasource="showseeker" name="proposals">
				SELECT 	* 
				FROM 		proposals 
				WHERE 	USERID = #arguments.userid# AND deletedat IS NULL
				ORDER BY CREATEDAT DESC
			</cfquery>
			<cfcatch><cfset proposals = QueryNew("")></cfcatch>
		</cftry>
		<cfreturn proposals>
	
	</cffunction>
	
	
	<!--- USER PROPOSALS --->
	<cffunction name="proposalsjson" access="remote">
	
		<cfparam  default="0" name="arguments.userid">
		
		<cftry>
			<cfquery datasource="showseeker" name="proposals">
				SELECT  	name, id
				FROM 		proposals 
				WHERE 	USERID = #arguments.userid# AND deletedat IS NULL
				ORDER BY CREATEDAT DESC
			</cfquery><cfoutput>
			{
				"proposals":[<cfloop query="proposals">
				{
				"id":"#proposals.id#",
				"name":"#proposals.name#"}<cfif NOT proposals.IsLast()>,</cfif></cfloop>
				]
			}</cfoutput><cfabort><cfcatch>-1<cfabort></cfcatch>
		</cftry>
		<cfreturn proposals>
	
	</cffunction>


	
	<!--- USER ZONES --->
	<cffunction name="zones">
		<cfparam  default="160" name="userid">

		<cfquery datasource="showseeker" name="zones">
			SELECT 	users.id, 
						zones.name AS zonename,
						zones.id as zoneid,
						zones.dmaid,
						zones.syscode,
						zones.zipcode,
						zones.timezoneid,
						zones.corporationid AS zonecorporationid,
						timezones.name AS timezonename,
						timezones.databasename,
						timezones.abbreviation,
						timezones.phpname,
						timezones.utcdifference
			FROM 		users 
			LEFT OUTER JOIN useroffices 
			ON 		users.id = useroffices.userid 
			INNER JOIN offices 
			ON 		useroffices.officeid = offices.id AND offices.deletedat IS NULL 
			LEFT OUTER JOIN officezones 
			ON 		offices.id = officezones.officeid 
			INNER JOIN zones 
			ON 		officezones.zoneid = zones.id AND zones.deletedat IS NULL 
			INNER JOIN timezones ON zones.timezoneid = timezones.id 
			WHERE 	( users.id = #userid# ) AND ( users.deletedat IS NULL ) 
			ORDER BY zones.name ASC
		</cfquery>
	
		<cfreturn zones>
	
	</cffunction>
	
	
	<!--- ZONE STATIONS --->
	<cffunction name="stations">
		<cfargument name="zoneid" default="10">

		<cfquery datasource="showseeker" name="stations">
			SELECT 	zonenetworks.zoneid,zonenetworks.networkid,
						zones.id,zones.name,zones.dmaid,zones.syscode,zones.zipcode,
						zones.timezoneid,	timezones.abbreviation,
						tms_networks.networkid AS tms_networknetworkid,
						tms_networks.timezone,tms_networks.name AS tms_networkname,tms_networks.callsign,
						tms_networks.affiliate,tms_networks.city,tms_networks.state,
						tms_networks.country,tms_networks.dma,tms_networks.dmanumber,
						networklogos.networkid AS networklogonetworkid,networklogos.logoid,
						logos.name AS Logoname,logos.filename 
			FROM 		zonenetworks 
			INNER JOIN zones 
			ON 		zonenetworks.zoneid = zones.id AND zones.deletedat IS NULL 
			INNER JOIN timezones 
			ON 		zones.timezoneid = timezones.id			
			INNER JOIN networks 
			ON 		zonenetworks.networkid = networks.id AND networks.deletedat IS NULL 
			LEFT OUTER JOIN tms_networks 
			ON 		networks.id = tms_networks.networkid 
			LEFT OUTER JOIN networklogos 
			ON 		networks.id = networklogos.networkid AND networklogos.deletedat IS NULL 
			LEFT OUTER JOIN logos 
			ON 		networklogos.logoid = logos.id AND logos.deletedat IS NULL 
			WHERE 	zonenetworks.zoneid = #zoneid# 
			ORDER BY tms_networks.callsign ASC			
		
		</cfquery>


		<cfreturn stations>
	</cffunction>


	
	<!--- COLOR CODING TITLES --->
	<cffunction name="programtitle">
		<cfargument name="i">
		<cfargument name="k">
		<cfargument name="m">
		<cfargument name="PREMIERE">
		<cfargument name="NEW">
		<cfargument name="DESCRIPTION">		
		<cfargument name="EPISODE">
		<cfargument name="PROGRAM">					
		<cfargument name="GENRE">
		<cfargument name="LIVE">
		<cfargument name="x" default="100">
		<cfargument name="y" default="100">				
		<cfargument name="STATIONID">
		<cfargument name="KEY">					
		<cfargument name="TZ">			
		
		<cfif     Len(PREMIERE) LTE 1 AND #arguments.NEW# NEQ "NEW" AND #arguments.LIVE# NEQ "(LIVE)" AND (Len(DESCRIPTION) GT 1 OR LEN(EPISODE) GT 1)>
			<cfreturn 	"<a href=javascript:displayInfo('#i##k##m#','#KEY#','#NEW#','#LIVE#','black','#TZ#',#x#,#y#)>#PROGRAM#</a><div class=programDesc>#DESCRIPTION#</div>">
		
		<cfelseif Len(PREMIERE) GT 1 AND (Len(DESCRIPTION) GT 1 OR LEN(EPISODE) GT 1)>
			<cfreturn 	"<a href=javascript:displayInfo('#i##k##m#','#KEY#','#NEW#','#LIVE#','red','#TZ#',#x#,#y#) style=color:red;   font-weight:700;><b>#PROGRAM#</b></a>">
		
		<cfelseif Len(PREMIERE) LTE 1 AND #arguments.LIVE# EQ "(LIVE)" AND (Len(DESCRIPTION) GT 1 OR LEN(EPISODE) GT 1)>
			<cfif FindNoCase('sports event',GENRE) NEQ 0>
				<cfreturn 	"<a href=javascript:displayInfo('#i##k##m#','#KEY#','#NEW#','#LIVE#','##5801AF','#TZ#',#x#,#y#) style=color:##5801AF; font-weight:700;><b>#PROGRAM# <em>#arguments.LIVE#</em><BR><BR><font style=color:##666666; font-weight:300;>#EPISODE#</font></b></a>">			
			<cfelse>
				<cfreturn 	"<a href=javascript:displayInfo('#i##k##m#','#KEY#','#NEW#','#LIVE#','##000000','#TZ#',#x#,#y#) style=color:##000000; font-weight:700;><b>#PROGRAM# <font color=##5801AF><em>#arguments.LIVE#</em></font></b></a>">			
			</cfif>
		
		<cfelseif Len(PREMIERE) LTE 1 AND #arguments.NEW# EQ "NEW" AND (Len(DESCRIPTION) GT 1 OR LEN(EPISODE) GT 1)>
			<cfreturn 	"<a href=javascript:displayInfo('#i##k##m#','#KEY#','#NEW#','#LIVE#','green','#TZ#',#x#,#y#) style=color:green; font-weight:700><b>#PROGRAM#</b></a>">
		
		<cfelseif Len(PREMIERE) GT 1>
			<cfreturn "<font color=red>#PROGRAM#</font>">
		
		<cfelseif #arguments.NEW# EQ "NEW">
			<cfreturn "<font color=green>#PROGRAM#</font>">
		
		<cfelseif #arguments.LIVE# EQ "(LIVE)">
			<cfreturn "<font color=##000000>#PROGRAM# <font color=##5801AF><em>#arguments.LIVE#</em></font></font>">
		
		<cfelse>
			<cfreturn "#PROGRAM#">
		</cfif>		
		
	</cffunction>








<cffunction name="stationschedules" access="remote" returntype="query">
		<cfargument name="stationNumber">
		<cfargument name="sDate">
		<cfargument name="eDate">		
		<cfargument name="sTime">
		<cfargument name="eTime">
		<cfargument name="timezoneid">		
		<cfargument name="timeoffset">
		<cfargument name="dstStart">
		<cfargument name="dstEnd">						

		<CFSET STARTTIME =  arguments.sTime>
		<CFSET ENDTIME   =  arguments.eTime>
		
	
		<cfquery name="searchResults" datasource="programData" ><cfoutput>
			
			<!---  STANDARD DAYLIGHT TIME & ARIZONA CLIENTS,  NO DAYLIGHT CAHNGE IS OBSERVED --->
			<CFIF (timezoneid EQ 14) OR ((#DateFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), 'm/dd/yyyy')#& #TimeFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), "HH:MM")# LT dstStart&"02:00"
					   AND  DateFormat(sdate,"mm/dd/yyyy")&"#STARTTIME#" LT dstStart&"02:00" 
					   AND #eDate# LT  dstStart&"02:00") 
					   OR
					  (#DateFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), 'm/dd/yyyy')#& #TimeFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), "HH:MM")# GT dstEnd&"02:00"
					   AND DateFormat(sdate,"mm/dd/yyyy")&"#STARTTIME#" GT dstEnd&"02:00" 
					   AND #eDate# GT dstEnd&"02:00:00")
					   OR
					   (#DateFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), 'm/dd/yyyy')#& #TimeFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), "HH:MM")# GT dstStart&"02:00"
					   AND #DateFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), 'm/dd/yyyy')#& #TimeFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), "HH:MM")# LT dstEnd&"02:00"
					   AND DateFormat(sdate,"mm/dd/yyyy")&"#STARTTIME#" GT dstStart&"02:00" 
					   AND #eDate# LT dstEnd&"02:00:00"))>

					   
					<CFSET searchStartTime 	= "#sTime#">
					<CFSET searchEndTime 	= "#eTime#">
					<CFSET searchStartDate 	= "#sDate#">
					<CFSET searchEndDate		= "#eDate#">
					<cfset TIMEOFFSET 		= TIMEOFFSET>						   
					   
					<cfinclude template="ezquery.cfm">
		
					
			<!--- 	Current Day/Time IS LESS THAN the Begining of Daylight Change 
					AND Search START Date IS GREATER THAN the Begining of Daylight Change
					AND Search END   Date IS GREATER THAN the Begining of Daylight Change --->
					
			<CFELSEIF #DateFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), 'm/dd/yyyy')#& #TimeFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), "HH:MM")# LT dstStart&"02:00"
					AND DateFormat(sdate,"mm/dd/yyyy")&"#STARTTIME#" GT dstStart&"02:00" 
					AND #eDate# GT dstStart&"02:00:00" >

					<CFSET searchStartTime 	= "#sTime#">
					<CFSET searchEndTime 	= "#eTime#">
					<CFSET searchStartDate 	= "#sDate#">
					<CFSET searchEndDate		= "#eDate#">
					<cfset TIMEOFFSET 		= TIMEOFFSET + 1>

					<cfinclude template="ezquery.cfm">
					
					
			<!---  SEARCH CROSSES BEGINING OF DAYLIGHT CHANGE  --->
				    			
			<CFELSEIF  #DateFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), 'm/dd/yyyy')#& #TimeFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), "HH:MM")# LT dstStart&"02:00"
				    AND DateFormat(sDate,"mm/dd/yyyy")&"#STARTTIME#" LT dstStart&"02:00" 
				    AND #eDate# GTE dstStart&"02:00:00">
					
					<CFSET searchStartTime 	= "#sTime#">
					<CFSET searchEndTime 	= "#eTime#">
					<CFSET searchStartDate 	= "#DateFormat(DateAdd("d",-1,sDate),'yyyy-mm-dd')#">
					<CFSET searchEndDate		= "#DateFormat(dstStart, 'yyyy-mm-dd')#">
					
					<cfinclude template="ezquery2.cfm">
					
					UNION
					
					<CFSET searchStartTime 	= "#sTime#">
					<CFSET searchEndTime 	= "#eTime#">
					<CFSET searchStartDate 	= "#DateFormat(dstStart, 'yyyy-mm-dd')#">
					<CFSET searchEndDate		= "#eDate#">
					<cfset TIMEOFFSET 		= TIMEOFFSET + 1>
					
					<cfinclude template="ezquery2.cfm">
					
		
			<!--- 	Current Day/Time IS LESS THAN the Ending of Daylight Change 
					AND Search Start Date IS GREATER THAN the Ending of Daylight Change
					AND Search End   Date IS GREATER THAN the Ending of Daylight Change--->
					
			<CFELSEIF   #DateFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), 'm/dd/yyyy')#& #TimeFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), "HH:MM")# GT dstStart&"02:00"
					AND #DateFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), 'm/dd/yyyy')#& #TimeFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), "HH:MM")# LT dstEnd&"02:00"
					AND DateFormat(sdate,"mm/dd/yyyy")&"#STARTTIME#" GT dstEnd&"02:00" 
					AND #eDate# GT dstStart&"02:00:00">

					<CFSET searchStartTime 	= "#sTime#">
					<CFSET searchEndTime 	= "#eTime#">
					<CFSET searchStartDate 	= "#sDate#">
					<CFSET searchEndDate		= "#eDate#">
					<cfset TIMEOFFSET 		= TIMEOFFSET - 1>

					<cfinclude template="ezquery.cfm">			
					
			<!--- SEARCH CROSSES ENDING DAYLIGHT --->
			
			<CFELSEIF #DateFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), 'm/dd/yyyy')#& #TimeFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), "HH:MM")# LT dstEnd&"02:00"
			AND DateFormat(sdate,"mm/dd/yyyy")&"#STARTTIME#" LT dstEnd&"02:00" 
			AND #eDate# GT dstEnd&"02:00:00">

					<CFSET searchStartTime 	= "#arguments.sTime#">
					<CFSET searchEndTime 	= "#arguments.eTime#">
					<CFSET searchStartDate 	= "#DateFormat(DateAdd("d",-1,arguments.sDate),"mm/dd/yyyy")#">
					<CFSET searchEndDate		= "#DateFormat(dstEnd,'mm/dd/yyyy')#">
					
					<cfinclude template="ezquery2.cfm">
					
					UNION 
					
					<CFSET searchStartTime 	= "#arguments.sTime#">
					<CFSET searchEndTime 	= "#arguments.eTime#">
					<CFSET searchStartDate 	=  #dstEnd#>
					<CFSET searchEndDate		= "#arguments.eDate#">
					<cfset TIMEOFFSET 		= TIMEOFFSET - 1>
					
					<cfinclude template="ezquery2.cfm">				
					
					
			<CFELSE>
					<CFSET searchStartTime 	= "#sTime#">
					<CFSET searchEndTime 	= "#eTime#">
					<CFSET searchStartDate 	= "#sDate#">
					<CFSET searchEndDate	= "#eDate#">
					<cfinclude template="ezquery.cfm">
			</CFIF>	
			
			ORDER BY AIRDATE</cfoutput>
		</cfquery>
		
		<cfreturn searchResults>

	</cffunction>



	<!--- CALCULATES DAYLIGHT SAVINGS DATES PERIOD --->
	<cffunction name="dstRange">
		
		<cfargument name="localdatetime" default="#now()#">
		
		<cfset dst = {}>
		
		<!--- DAYLIGHT SAVINGS START DATE --->
		<cfif DayOfWeek("03/01/"&year(localdatetime)) EQ 1>
			<cfset dst.start = "03/08/"&year(localdatetime)>
		<cfelse>
			<cfset dst.start = "03/"&16-DayOfWeek("03/01/"&year(localdatetime))&"/"&year(localdatetime)>
		</cfif>
		
		
		<!--- DAYLIGHT SAVINGS END DATE --->
		<cfif DayOfWeek("03/01/"&year(localdatetime)) EQ 1>
			<cfset dst.end = "11/01/"&year(localdatetime)>
		<cfelse>
			<cfset dst.end = "11/"&9-DayOfWeek("11/01/"&year(localdatetime))&"/"&year(localdatetime)>
		</cfif>
		
		<!--- DAYLIGHT TIME ADJUST --->
		<cfif localdatetime GTE dst.start AND localdatetime LTE dst.end>
			<cfset dst.timeoffset = -1>
		<cfelse>
			<cfset dst.timeoffset = 1>
		</cfif>	
		
		
		<cfreturn dst>
	
	
	</cffunction>
	
	
	<!--- TIME ZONES IN SS+ --->
	<cffunction name="timezones">
	
		<cfset TZS = QueryNew("ID")>
		
		<cfloop list="AST|-9|2|3,PST|-8|4|5,MST|-7|6|8,MDT|-7|14|14,CST|-6|9|10,EST|-5|11|12,PR|-4|13" index="i" delimiters=",">
			<cfset QueryAddRow(TZS)>
			<cfset QuerySetCell(TZS,"ID","#i#")>
		</cfloop>
		
		<cfreturn TZS>
	
	</cffunction>
	
	
	
	<cffunction name="timezonemapping">

		<cfargument name="zoneabbreviation" default="pst">
				
		<cfset TZS = timezones()>
		
		<cfquery dbtype="query" name="tzmapping">
			SELECT ID FROM TZS WHERE ID like '%#zoneabbreviation#%'
		</cfquery>
		
		<cfset tzmapped = ListToArray(ValueList(tzmapping.ID),"|")>

		<cfset dst = dstRange()>

		<cfif dst.timeoffset GT 0>
			<cfreturn ListToArray('#tzmapped[3]#,#tzmapped[2]#,#dst.start#,#dst.end#')>
		<cfelse>
			<cfreturn ListToArray('#tzmapped[4]#,#tzmapped[2]+1#,#dst.start#,#dst.end#')>
		</cfif>		
	
	
	</cffunction>
	
	
	
	<cffunction name="scheduleformater">
		<cfargument name="netschedule"> 

		<cfset schedule = QueryNew("SKEDULEID,PROGRAM_KEY,CALLSIGN,DURATION,AIRDATE,AIRDAY,STARTDATE,ENDDATE,STARTTIME,ENDTIME,STATIONID,NEW,PROGRAM,EPISODE,SHOWDESC,PREMIERE,GENRE,LIVE,DAYNUM,TMSID,PREMIEREANDLIVE,PROJECTED,PACKAGEID,AUTHORIZED,CREATEDAT,UPDATEDAT") >
		  
		<cfloop query="netschedule">
		
		   <cfset newDuration = 0>
		
		   <cfset QueryAddRow(schedule)>
		   <cfset QuerySetCell(schedule,"AIRDATE",			"#netschedule.airDate#")>
		   <cfset QuerySetCell(schedule,"STARTDATE",			"#netschedule.startDate#")>
		   <cfset QuerySetCell(schedule,"STARTTIME",			"#netschedule.startTime#")>
		  
		  
		   <cfset QuerySetCell(schedule,"ENDTIME",			"#netschedule.endTime#")>                         
		   <cfset QuerySetCell(schedule,"DURATION",			"#netschedule.DURATION#")>                              
		  
		  
		   <cfset QuerySetCell(schedule,"SKEDULEID",			"#netschedule.PROGRAM_KEY#")>
		   <cfset QuerySetCell(schedule,"PROGRAM_KEY",		"#netschedule.PROGRAM_KEY#")>                                 
		   <cfset QuerySetCell(schedule,"STATIONID",			"#netschedule.stationid#")>
		   <cfset QuerySetCell(schedule,"CALLSIGN",			"#netschedule.callsign#")>	   
		   <cfset QuerySetCell(schedule,"NEW",       		"#netschedule.new#")>
		   <cfset QuerySetCell(schedule,"PROGRAM", 			"#netschedule.PROGRAM#")>
		   <cfset QuerySetCell(schedule,"EPISODE",			"#netschedule.EPISODE#")>
		   <cfset QuerySetCell(schedule,"SHOWDESC",			"#netschedule.SHOWDESC#")>                           
		   <cfset QuerySetCell(schedule,"PREMIERE",			"#netschedule.premiere#")>
		   <cfset QuerySetCell(schedule,"GENRE",				"#netschedule.GENRE#")>
		   <cfset QuerySetCell(schedule,"LIVE",			 	"#netschedule.LIVE#")>
		   <cfset QuerySetCell(schedule,"TMSID",				"#netschedule.TMSID#")>
			<cfset QuerySetCell(schedule,"PREMIEREANDLIVE", '#netschedule.PREMIEREANDLIVE#')>
			<cfset QuerySetCell(schedule,"PROJECTED", 		'#netschedule.PROJECTED#')>		

			<cfif  IsDefined("netschedule.PACKAGEID")>
			<cfset QuerySetCell(schedule,"PACKAGEID", 		"#netschedule.PACKAGEID#")>
			</cfif>	


			<cfset QuerySetCell(schedule,"AUTHORIZED", 		"#netschedule.authorized#")>	

			<cfset QuerySetCell(schedule,"CREATEDAT", 		"#netschedule.CREATEDAT#")>	
			<cfset QuerySetCell(schedule,"UPDATEDAT", 		"#netschedule.UPDATEDAT#")>


		
		</cfloop>	
	
		<cfreturn schedule>
	
	</cffunction>


	<cffunction name="broadcastcalendar">
		<cfargument name="sdate">
		<cfargument name="edate">
	

		<cfset pastYear 	= GetLastDayOfWeekOfMonth('12-01-'&'#Year(now())-1#',1)>
		<cfset bcSDate 	= DateAdd('d',1,pastYear)>
	
		<cfset broadcast = QueryNew("bcmonth,bcyear,week,wdate")>
		
		<cfset wk = 1>

		<cfloop from="#bcSDate#" to="#edate#" step="#CreateTimeSpan(7,0,0,0)#" index="thisdate">

			<cfif sDate LTE thisdate>
			
				<cfset lSunday = 	GetLastDayOfWeekOfMonth(thisdate,1)>
				
				<cfset QueryAddRow(broadcast)>

				<cfif thisdate LT lSunday>

					<cfset QuerySetCell(broadcast,"bcmonth",	"#Month(thisdate)#")>
					<cfset QuerySetCell(broadcast,"bcyear",	"#Year(thisdate)#")>
				<cfelse>
				
					<cfset QuerySetCell(broadcast,"bcmonth",	"#Month(DateAdd('d',6,lSunday))#")>
					<cfset QuerySetCell(broadcast,"bcyear",	"#Year(DateAdd('d',6,lSunday))#")>
	
				</cfif>

				<cfset QuerySetCell(broadcast,"week",	"#wk#")>

				<cfset QuerySetCell(broadcast,"wdate",	"#dateformat(thisdate, 'mm/dd/yyyy')#")>				

			</cfif>

			<cfset wk = wk+1>

		</cfloop>		

		<cfreturn broadcast>

	</cffunction>






<cffunction name="GetLastDayOfWeekOfMonth" access="public" returntype="date" output="false" hint="Returns the date of the last given weekday of the given month.">
	 
	<!--- Define arguments. --->
	<cfargument	name="Date"	
					type="date"
					required="true"
					hint="Any date in the given month we are going to be looking at."	/>
	 
	<cfargument	name="DayOfWeek"
					type="numeric"
					required="true"
					hint="The day of the week of which we want to find the last monthly occurence."	/>
	 
	<!--- Define the local scope. --->
	<cfset var LOCAL = StructNew() />
	 
	<!--- Get the current month based on the given date. --->
	<cfset LOCAL.ThisMonth = CreateDate(Year( ARGUMENTS.Date ),	Month( ARGUMENTS.Date ), 1	) />
	 
	<cfset LOCAL.LastDay = (DateAdd( "m", 1, LOCAL.ThisMonth ) -	1) /> 
	
	<cfset LOCAL.Day = (LOCAL.LastDay - DayOfWeek( LOCAL.LastDay ) + ARGUMENTS.DayOfWeek) />
	
	<cfif (Month( LOCAL.Day ) NEQ Month( LOCAL.ThisMonth ))>
	 
		<!--- Subract a week. --->
		<cfset LOCAL.Day = (LOCAL.Day - 7) />
	 
	</cfif>
	 
	<!--- Return the given day. --->
	<cfreturn DateFormat( LOCAL.Day ) />
</cffunction>





	<cffunction name="formatproposals" access="public" returnType="any">
		<cfargument name="proposals">
		
		<cfset newlist = QueryNew('id,name')>
		<cfloop query="proposals">
			<cfset QueryAddRow(newlist)>
			<cfset QuerySetCell(newlist,'id', '#proposals.id#')>
			<cfset QuerySetCell(newlist,'name', '#URLDecode(proposals.name)#')>
		</cfloop>
		
		<cfreturn newlist>
	</cffunction>	








	<cffunction name="nets" access="public" returnType="any">

		<cfquery name="allnets" datasource="solr">
			select 		* 
			from 			station_mapper
			where			feed = 'eastern'
			and 			stationid IN (select distinct stationnum from programDataProjected  where deletedat IS NULL)
			order by		station
		</cfquery>
		
		<cfreturn allnets>
		
	</cffunction>	






<!--- REMOVES RECORDS --->
	<cffunction name="deleteshows" access="remote">

		<cfargument name="shows">
		
		<cftry>	
		
			<cfloop list="#shows#" index="idx">
				<cfquery datasource="solr">
					update programDataProjected 
					set deletedat = '#DateFormat(DateAdd("h",-7,now()),"yyyy-mm-dd HH:mm:00")#'
					where right(id,LENGTH(id)-5) = '#idx#'
				</cfquery>
			</cfloop>

				{
					"response":"ok"
				}<cfabort>		

			<cfcatch>
				{
					"response":"error"
				}<cfabort>		
				
			</cfcatch>
		</cftry>
	
	
	</cffunction>
	
	
	
	
	
	
	
	
<!--- UPDATES RECORDS --->
	<cffunction name="updateShows" access="remote">

		<!--- cftry --->
			
		<!--- THE SHOW ID --->
		<cfset showidwzone 	= 	ListToArray(id,'-')>
		<cfset showid 			=	showidwzone[1]>
	
		<cfquery  datasource="solr" name ="reference">
			SELECT 	* 
			FROM 		programDataProjected 
			WHERE 	RIGHT(id,LENGTH(id)-5) = '#RIGHT(showid,LEN(showid)-5) #'
		</cfquery>


		<cfquery datasource='solr' name="feeds">
			select * from station_mapper where stationid in (#ValueList(reference.stationnum)#)
		</cfquery>



		<!--- MAKING UP THE TIME ZONE TWEAKS --->
		<cfset etime = TimeFormat('#ENDDATE# #ENDTIME#',		'HH:mm')>			
		<cfset stime = TimeFormat('#STARTDATE# #STARTTIME#',	'HH:mm')>			
		<cfset sdate = DateFormat('#STARTDATE#',					'yyyy-mm-dd')>		
			
 
		
		
			<cfset thisStime 		= ParseDateTime(STARTTIME)>
			
			<cfset thisEtime 		= ParseDateTime(ENDTIME)>

			<cfset duration_h		= DateFormat(thisETime - thisSTime,'h')>
			
			<cfset duration_m		= TimeFormat(thisETime - thisSTime,'m')>			
					
			<cfset duration_h  	= Len(duration_h) EQ 1 ? '0' & #duration_h# : duration_h />
	
			<cfset duration_m  	= Len(duration_m) EQ 1 ? '0' & #duration_m# : duration_m />
			
			<cfset duration 		= duration_h & duration_m> 		
		
		



		<cfloop query="feeds">

			<cfif feeds.feed EQ 'EASTERN'>			<!--- UPDATES EASTERN FEED --->

				<cfset thisrecord = showupdater(SDATE,STIME,ETIME,ENDDATE,DURATION,PROGRAM,EPISODE,DESCRIPTION,PREMIERES,LIVE,COMMENTS,TWITTER,SOURCE,SITE,FACEBOOK,YOUTUBE,feeds.stationid,SHOWID,'5',NEW)>
			</cfif>
											<!--- UPDATES PACIFIC FEED --->
			<!--- cfelseif >
				<cfif LIVE NEQ 'Live'>
					<cfset toffset = '8'>
				<cfelse>
					<cfset toffset = '5'>
				</cfif >
				<cfset thisrecord = showupdater(SDATE,STIME,ETIME,ENDDATE,DURATION,PROGRAM,EPISODE,DESCRIPTION,PREMIERES,LIVE,COMMENTS,TWITTER,SOURCE,SITE,FACEBOOK,YOUTUBE,feeds.stationid,SHOWID,toffset,NEW)>
							
			</cfif --->
		</cfloop>
		

				{
					"response":"ok"
				}<cfabort>		

			<!--- cfcatch>
				{
					"response":"error"
				}<cfabort>
			</cfcatch>
		
		</cftry --->		

	
			
<cfabort>
	
	</cffunction>	
	


	<cffunction name="oneshow" access="remote">
		<cfargument name="showid" >

	
		<cfif DayOfWeek("03/01/"&year(now())) NEQ 1>
			<cfset dstStart = "03/"&16-DayOfWeek("03/01/"&year(now()))&"/"&year(now())>
		<cfelse>
			<cfset dstStart = "03/08/"&year(now())>
		</cfif>
	
	
		<cfif DayOfWeek("03/01/"&year(now())) NEQ 1>
			<cfset dstEnd = "11/"&9-DayOfWeek("11/01/"&year(now()))&"/"&year(now())>
		<cfelse>
			<cfset dstEnd = "11/01/"&year(now())>
		</cfif>

		<cfif Now() GT dstStart and Now() LT dstEnd>
			<cfset offset = -4>
		<cfelse>
			<cfset offset = -5>	
		</cfif>

		
		<cfset offset = 0>
		
		
		<cfquery dataSource="solr" name="projected">	
				SELECT								
							DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Eastern'), '%Y-%c-%d 00:00:00') AS airdate,
							DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Eastern'), '%Y-%c-%d') AS startDate,
							DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Eastern'), '%H:%i') AS startTime,
							DATE_FORMAT(DATE_ADD(CONVERT_TZ(airdate,'GMT','US/Eastern'), INTERVAL ((SUBSTRING(duration,1,2)*60) + SUBSTRING(duration,3,2)) MINUTE), '%Y-%c-%d') AS endDate,
							DATE_FORMAT(DATE_ADD(CONVERT_TZ(airdate,'GMT','US/Eastern'), INTERVAL ((SUBSTRING(duration,1,2)*60) + SUBSTRING(duration,3,2)) MINUTE), '%H:%i') AS endTime,
							DURATION,								
							id,
							stationnum,
							CALLSIGN,
							title,
							epititle,
							descembed,
							premierefinale,
							GENRE,
							LIVE,
							NEW,
							showid,
							projected_source,
							comments,
							youtube,
							facebook,
							twitter,
							website
				FROM 		programDataProjected 
				WHERE 	id = '#showid#'
		</cfquery>
	
	
	<cfreturn projected>
	
	</cffunction>

















<cffunction name="showupdater" access="remote">

		<cfargument name="SDATE">
		<cfargument name="STIME">
		<cfargument name="ETIME">
		<cfargument name="edate">
		<cfargument name="DURATION">
		<cfargument name="PROGRAM">
		<cfargument name="EPISODE">
		<cfargument name="DESCRIPTION">
		<cfargument name="PREMIERES">
		<cfargument name="LIVE">
		<cfargument name="COMMENTS">
		<cfargument name="TWITTER">
		<cfargument name="SOURCE">
		<cfargument name="SITE">
		<cfargument name="FACEBOOK">
		<cfargument name="YOUTUBE">
		<cfargument name="STATIONID">		
		<cfargument name="SHOWID">		
		<cfargument name="TZ">
		<cfargument name="NEW">
		<cfargument name="showtimeid">		

		<cfset projectedShowDTS = dts(sDate)>

		<cfif sDate GTE projectedShowDTS.start and sDate LTE projectedShowDTS.end>
			<cfset tz = tz-1>
		</cfif>


		<cfset airdate = DateFormat(DateAdd('h', #tz#,STARTDATE&' '&STARTTIME),"yyyy-mm-dd")&' '&TimeFormat(DateAdd('h', #tz#,STARTDATE&' '&STARTTIME),"HH:mm:ss")>
		<cfset sdate	= DateFormat(DateAdd('h', #tz#,STARTDATE&' '&STARTTIME),	'yyyy-mm-dd')>
		<cfset edate	= DateFormat(DateAdd('h', #tz#,ENDDATE&' '&ENDTIME),		'yyyy-mm-dd')>		
		<cfset stime	= TimeFormat(DateAdd('h', #tz#,STARTDATE&' '&STARTTIME),	'HH:mm')>
		<cfset etime	= TimeFormat(DateAdd('h', #tz#,ENDDATE&' '&ENDTIME),		'HH:mm')>		
		<cfset newid 						= left(id,len(id)-11) & DateFormat(DateAdd('h', #tz#,STARTDATE&' '&STARTTIME),"yyyymmdd")>
		<cfset newstationshowtimeid 	= left(id,len(id)-11) & TimeFormat(DateAdd('h', #tz#,STARTDATE&' '&STARTTIME),"HHmm")>		

		<cfquery datasource="solr">
			update 	programDataProjected 
			set 		airdate 				= 	'#AIRDATE#',
						id						=	'#newid#',
						stationshowtimeid	=	'#newstationshowtimeid#',
						startDate 			=	'#SDATE#',
						startTime 			=	'#STIME#',
						endTime  			=	'#ETIME#',
						endDate 				= 	'#edate#',
						DURATION 			=	'#DURATION#',
						title 				=	'#PROGRAM#',
						epititle 			=	'#EPISODE#',
						descembed 			=	'#DESCRIPTION#',
						<CFIF NEW  EQ 'pNew'>
						premierefinale 	=	'#NEW#',						
						<CFELSEIF PREMIERES NEQ  ''>
						premierefinale 	=	'#PREMIERES#',
						</cfif>
						<CFIF LIVE EQ 'Live' AND PREMIERES EQ ''>
						LIVE 					=	'#LIVE#',
						</CFIF>
						
						<CFIF NEW NEQ "pNew">
						NEW 					=	'#NEW#',
						</CFIF>
						
						COMMENTS 			=	'#COMMENTS#',
						TWITTER				=	'#TWITTER#',
						projected_source	= 	'#SOURCE#',
						WEBSITE				=	'#SITE#',
						FACEBOOK				=	'#FACEBOOK#',
						YOUTUBE 				=	'#YOUTUBE#',
						updatedat			=	'#DateFormat(Now(),'yyyy-mm-dd')# #TimeFormat(Now(),'HH:mm:ss')#'	
			WHERE 	id 					= 	'#showid#'
			and		stationnum 			=	'#stationid#'
		</cfquery>	

		<cfset thisPid = Left(Right(showid,Len(showid)-5),len(Right(showid,Len(showid)-5))-8)>

		<cfquery datasource="solr" name="pacificfeed">
			SELECT 				station_mapper.callsign, stationid, airdate, startdate, feed
			FROM 					programDataProjected
			INNER JOIN 
									station_mapper ON programDataProjected.stationnum = station_mapper.stationid
			WHERE showid 	=  '#thisPid#'
			AND 	feed 		=  'pacific'
		</cfquery>		
		
		
<!--- PACIFIC FEED --->		
		
		
		
		<cfif pacificfeed.recordCount GT 0>
		
			
			<cfif LIVE NEQ 'Live'>
				<cfset tz = 3>
			<cfelse>
				<cfset tz = 0>
			</cfif>			
				
				<cfset airdate1 	= DateFormat(DateAdd('h', #tz#,airdate),	'yyyy-mm-dd')&' '&TimeFormat(DateAdd('h', #tz#,airdate),'HH:mm:ss')>
				<cfset sdate1		= DateFormat(DateAdd('h', #tz#,airdate),	'yyyy-mm-dd')>
				<cfset edate1		= DateFormat(DateAdd('h', #tz#,airdate),	'yyyy-mm-dd')>		
				<cfset stime1		= TimeFormat(DateAdd('h', #tz#,airdate),	'HH:mm')>
				<cfset etime1		= TimeFormat(DateAdd('h', #tz#,airdate),	'HH:mm')>		
				<cfset newid 						= pacificfeed.stationid & thisPid & DateFormat(DateAdd('h', #tz#,airdate),'yyyymmdd')>
				<cfset newstationshowtimeid 	= pacificfeed.stationid & thisPid & TimeFormat(DateAdd('h', #tz#,airdate),'HHmm')>		

			<cfset pId = pacificfeed.stationid & Right(showid,Len(showid)-5)>

			<cfquery datasource="solr">
				update 	programDataProjected 
				set 		airdate 				= 	'#airdate1#',
							id						=	'#newid#',
							stationshowtimeid	=	'#newstationshowtimeid#',
							startDate 			=	'#sdate1#',
							startTime 			=	'#stime1#',
							endTime  			=	'#etime1#',
							endDate 				= 	'#edate1#',
							DURATION 			=	'#DURATION#',
							title 				=	'#PROGRAM#',
							epititle 			=	'#EPISODE#',
							descembed 			=	'#DESCRIPTION#',
							<CFIF NEW  EQ 'pNew'>
							premierefinale 	=	'#NEW#',						
							<CFELSEIF PREMIERES NEQ  ''>
							premierefinale 	=	'#PREMIERES#',
							</cfif>
							<CFIF LIVE EQ 'Live' AND PREMIERES EQ ''>
							LIVE 					=	'#LIVE#',
							</CFIF>
							
							<CFIF NEW NEQ "pNew">
							NEW 					=	'#NEW#',
							</CFIF>
							
							COMMENTS 			=	'#COMMENTS#',
							TWITTER				=	'#TWITTER#',
							projected_source	= 	'#SOURCE#',
							WEBSITE				=	'#SITE#',
							FACEBOOK				=	'#FACEBOOK#',
							YOUTUBE 				=	'#YOUTUBE#',
							updatedat			=	'#DateFormat(Now(),'yyyy-mm-dd')# #TimeFormat(Now(),'HH:mm:ss')#'	
				WHERE 	id 					= 	'#pId#'
			</cfquery>			
		
		
		
		</cfif>
		
		

	<cfreturn "oK">

</cffunction>







<!---- CALCULATED PERIOD OF DAYLIGHT SAVINGS TIME ---->
<cffunction name="dts">

	<cfargument name="dtsdate" default="#Now()#">



	<!--- GETTING THE DAYLIGHT SAVINGS DATE/TIME RANGE --->
	<cfif DayOfWeek("03/01/"&year(dtsdate)) EQ 1>
		<cfset dstStart 	= "03/08/"&year(dtsdate)>
	<cfelse>
		<cfset dstStart 	= "03/"&16-DayOfWeek("03/01/"&year(dtsdate))&"/"&year(dtsdate)>
	</cfif>
	


	<cfif DayOfWeek("03/01/"&year(dtsdate)) EQ 1>
		<cfset dstEnd 		= "11/01/"&year(dtsdate)>
	<cfelse>
		<cfset dstEnd 		= "11/"&9-DayOfWeek("11/01/"&year(dtsdate))&"/"&year(dtsdate)>
	</cfif>	

	
	<cfset thisdts 		= {}>
	<cfset thisdts.start = dstStart>
	<cfset thisdts.end 	= dstEnd>	
	
	<cfreturn thisdts>
	
	
</cffunction>





<cffunction name="authorize" access="remote">
	<cfargument name="showids">
	

	<cfloop list="#showids#" index="idx">
	 	<cfquery datasource="solr">
			update 	programDataProjected 
			set		authorized = '1'
			where 	right(id,LENGTH(id)-5) = '#idx#'
		</cfquery>
	</cfloop>
	
	{
	"response":"ok"
	}<cfabort>
	
	
</cffunction>


<cffunction name="unauthorized" access="remote">
	<cfargument name="showids">
	

	<cfloop list="#showids#" index="idx">
	 	<cfquery datasource="solr">
			update 	programDataProjected 
			set		authorized = '0'
			where 	right(id,LENGTH(id)-5) = '#idx#'
		</cfquery>
	</cfloop>
	
	{
	"response":"ok"
	}<cfabort>
	
	
</cffunction>




</cfcomponent>
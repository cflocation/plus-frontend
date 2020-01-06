<cfcomponent hint="ezgrids" output="yes">


	<cffunction name="index" access="public"> 
		<cfargument  name="zoneid" default="10">
	
		<cfset proposal 			= proposals(zoneid)>
		<cfset zones 	 			= zones()>
		<cfset getGridByNetwork 	= search()>
		<cfset stations 			= stations()>
		<cfset calendar				= broadcast(now(),dateadd('d',90,now()))>
		<cfabort>
	</cffunction>
	
	
	

	<cffunction name="search">
		<!--- START DATE --->
		<cfargument name="gridsdate">
							
		<!--- END DATE --->				
		<cfargument name="gridedate">
			
		<!--- START TIME --->
		<cfargument name="gridstime">
			
		<!--- END TIME --->
		<cfargument name="gridetime">
	
		<!--- ZONE --->
		<cfargument name="zoneid">
		
		<cfargument name="userid">
		
		<cfargument name="apiKey">


		<!--- PROJECTED --->
		<cfargument name="projected" default="0">		
		

			<cfset startdate 	= "#gridsdate#T00:00:00Z">
			<cfset enddate 		= "#gridedate#T23:59:59.999Z">
			<cfset starttime 	= "00:00">
			<cfset endtime 		= "23:59">

	
		<!--- PROJECTED STARTDATE --->
		   <cfset pjstartdate 	= "#DateFormat(DateAdd('d',1,GetLastDayOfWeekOfMonth(DateAdd('d',1,gridsdate),1)),'yyyy-mm-dd')#T00:00:00Z">
		   <cfset pjstartdate 	= dateFormat(broadcastcalendar(now(),dateAdd('d',7,now())).wdate,'yyyy-mm-ddT00:00:00Z')>

			<!--- netwok --->
			<cfset nets 		= "fq=">
			<cfset zone 		= stations(zoneid,userid, apiKey) >
			<cfset stationsNum = arrayLen(zone.networks)>

			<cfloop from="1" to="#stationsNum#" index="i">
				<cfset nets 		= nets&"stationnum:#zone.networks[i].ID#">
				<cfif i NEQ  stationsNum>
					<cfset nets 	= nets&"+">
				</cfif>	
			</cfloop>	
			
			<cfset tzABBREVIATION =  LCase(zone.info.timezone)>
	
			<!--- setup the timezones for the solr call --->
			<cfset timezonestart 	= "tz_start_#lcase(tzABBREVIATION)#">
			<cfset timezoneend 		= "tz_end_#lcase(tzABBREVIATION)#">
			<cfset starttimetz 		= "start_#lcase(tzABBREVIATION)#">
			<cfset timezoneday 		= "day_#lcase(tzABBREVIATION)#">
			<cfset daterange 		= "fq=#timezonestart#:[#startdate# TO #enddate#]">
			<cfset timerange 		= "fq=#starttimetz#:[#starttime# TO #endtime#]">
			<cfset pjdaterange 		= "fq=#timezonestart#:[#pjstartdate# *]">


			
			<!--- build the string TMS DATA --->
			<cfset solr = 'http://104.239.197.204:8983/solr/select/?q=*%3A*&version=2.2&start=0&rows=3000&indent=on&wt=json'>
			<cfset solr = solr&'&sort=tz_start_pst asc&fq=projected:0&fq=(genre1:"sports event" AND live:"Live")'>
			<cfset solr = solr&' OR (premierefinale:"Series Premiere"+premierefinale:"Series Finale"+premierefinale:"Season Premiere"+premierefinale:"Season Finale")'>
			<cfset solr = solr&"&fl=id,callsign,showtype,projected,packageid,showid,tmsid,live,genre,stars,descembed,orgairdate,title,premierefinale,new,stationnum">
			<cfset solr = solr&",epititle,#timezoneday#,#starttimetz#,#timezonestart#,#timezoneend#,duration,createdat,updatedat">
			<cfset solr = solr&"&#nets#">
			<cfset solr = solr&"&#daterange#">
			<cfset solr = solr&"&#timerange#">
			
			<cfhttp url="#solr#">
			<cfset theData 			= DeserializeJSON(cfhttp.fileContent)>
			<cfset getGridByNetwork = theData.response.docs>
			

			<!--- ********** Beginning Of Code: new Entry tool Asif 07/08/2017  ****************************************** --->
			<cfset stFields = {
				"startDate" = gridsdate,
				"endDate" = gridedate,
				"startTime" = starttime,
				"endTime" = endtime,
				"timezone" = tzABBREVIATION,
				"pjstartdate" = pjstartdate,
				"networks" = []
				}>
			<cfloop from="1" to="#stationsNum#" index="i">
				<cfset a= ArrayAppend(stFields.networks, zone.networks[i].ID) >
			</cfloop>
			<cfhttp url="http://192.237.167.207:8080/plus/projected" method="post">
    			<cfhttpparam type="header" name="Content-Type" value="application/json" />
    			<cfhttpparam type="body" value="#serializeJSON(stFields)#">
			</cfhttp>

			<cfset prData = DeserializeJSON(cfhttp.fileContent)>
			<cfset getGridByNetwork.addAll( prData.result )>
			<!--- endof ocde Of Code: new Entry tool Asif 07/08/2017 ************************************************** --->

			<cfset programSchedule =queryNew("	airDate,		airDay,
												CALLSIGN,		CREATEDAT,
												DAYNUM,			DURATION,
												endDate,		endTime,
												EPISODE,		GENRE,
												LIVE,			new,
												SHOWDESC,		SKEDULEID,
												startDate,		startTime,
												stationId,		premiere,
												PREMIEREANDLIVE,PROGRAM,
												PROGRAM_KEY,	tmsid,
												PROJECTED,		PACKAGEID, 
												UPDATEDAT")/>
						
						
						
			
			<cfloop  from="1" to="#ArrayLen(getGridByNetwork)#" index="i">


				<cfif getGridByNetwork[i].DURATION GT 6>

					<cfset newDuration = 0>

					<cfset QueryAddRow(programSchedule)>
					
					<cfset sDate = #Replace(Replace(Evaluate('getGridByNetwork[i].#timezonestart#'),'T',' '),'Z',' ')#>
					<cfset eDate = #Replace(Replace(Evaluate('getGridByNetwork[i].#timezoneend#'),'T',' '),'Z',' ')#>
					
					<cfset sTime = TimeFormat(#Evaluate('getGridByNetwork[i].#starttimetz#')#,"HH:mm")>
					<cfset eTime = TimeFormat(#Replace(Replace(Evaluate('getGridByNetwork[i].#timezoneend#'),'T',' '),'Z',' ')#,"HH:mm")>	


					<cfswitch expression="#Right(stime,2)#">
					    <cfcase value="00,01,02,03,04,05,06,07" delimiters=",">
					    	<cfset sTime = Left(stime,3)&'00'>
					    </cfcase>
					    <cfcase value="08,09,10,11,12,13,14,15,16,17,18,19,20,21,22" delimiters=",">
					    	<cfset sTime = Left(stime,3)&'15'>
					    </cfcase>
					    <cfcase value="23,24,25,26,27,28,29,30,31,32,33,34,35,36,37" delimiters=",">
					    	<cfset sTime = Left(stime,3)&'30'>					    
					    </cfcase> 
					    <cfcase value="38,39,40,41,42,43,44,45,46,47,48,49,50,51,52" delimiters=",">
					    	<cfset sTime = Left(stime,3)&'45'>					    
					    </cfcase>
					    <cfcase value="53,54,55,56,57,58,59" delimiters=",">
					    	<cfif stime LT '23:00'>
							   <cfset sTime = TimeFormat(DateAdd('h',1,stime),'HH')&':00'>					    	
							<cfelse>
						    	<cfset sTime = '00:00'>
					    		<cfset sDate = DateAdd('d',1,sDate)>
					    	</cfif>
					    </cfcase>						
					</cfswitch>



					<cfswitch expression="#Right(etime,2)#">
					    <cfcase value="00,01,02,03,04,05,06,07" delimiters=",">
					    	<cfset eTime = Left(etime,3)&'00'>
					    </cfcase>
					    <cfcase value="08,09,10,11,12,13,14,15,16,17,18,19,20,21,22" delimiters=",">
					    	<cfset eTime = Left(etime,3)&'15'>
					    </cfcase>
					    <cfcase value="23,24,25,26,27,28,29,30,31,32,33,34,35,36,37" delimiters=",">
					    	<cfset eTime = Left(etime,3)&'30'>					    
					    </cfcase> 
					    <cfcase value="38,39,40,41,42,43,44,45,46,47,48,49,50,51,52" delimiters=",">
					    	<cfset eTime = Left(etime,3)&'45'>					    
					    </cfcase>
					    <cfcase value="53,54,55,56,57,58,59" delimiters=",">
					    	<cfif etime LT '23:00'>
							   <cfset eTime = TimeFormat(DateAdd('h',1,stime),'HH')&':00'>					    	
							<cfelse>
						    	<cfset eTime = '00:00'>
					    		<cfset eDate = DateAdd('d',1,sDate)>
					    	</cfif>
					    </cfcase>						
					</cfswitch >

					<CFSET THISDURATION = NumberFormat(DateDiff('n',sDate,eDate)/60,'99.000000')>

					<cfset QuerySetCell(programSchedule,"callsign", 		"#getGridByNetwork[i].callsign#")>
					<cfset QuerySetCell(programSchedule,"airDate", 			"#sDate#")>
					<cfset QuerySetCell(programSchedule,"startDate", 		"#Left(DateFormat(sDate,'yyyy-mm-dd'),10)#")>
					<cfset QuerySetCell(programSchedule,"endDate", 			"#Left(DateFormat(sDate,'yyyy-mm-dd'),10)#")>
					<CFIF THISDURATION LT 17>
					<cfset QuerySetCell(programSchedule,"startTime", 		"#sTime#")>
					<cfset QuerySetCell(programSchedule,"endTime", 			"#eTime#")>
					<CFELSE>
						<cfset QuerySetCell(programSchedule,"startTime", 		"06:00")>
						<cfset QuerySetCell(programSchedule,"endTime", 			"23:59")>
					</CFIF>
					<cfset QuerySetCell(programSchedule,"DAYNUM", 			"#getGridByNetwork[i][timezoneday]#")>
					<cfset QuerySetCell(programSchedule,"tmsid", 			"#getGridByNetwork[i].tmsid#")>
					<cfset QuerySetCell(programSchedule,"DURATION", 		"#THISDURATION#")>			
					<cfset QuerySetCell(programSchedule,"SKEDULEID", 		"#getGridByNetwork[i].ID#")>
					<cfset QuerySetCell(programSchedule,"PROGRAM_KEY", 	    "#getGridByNetwork[i].ID#")>
					<!--- <cfset QuerySetCell(programSchedule,"STATIONID", 	"#Left(getGridByNetwork[i].ID,5)#")> --->
					<cfset QuerySetCell(programSchedule,"STATIONID", 		"#getGridByNetwork[i].stationnum#")>
					<cfset QuerySetCell(programSchedule,"NEW", 				"#getGridByNetwork[i].NEW#")>
					<cfset QuerySetCell(programSchedule,"PROGRAM", 			"#getGridByNetwork[i].TITLE#")>
					<cfset QuerySetCell(programSchedule,"EPISODE", 			"#getGridByNetwork[i].EPITITLE#")>
					<cfset QuerySetCell(programSchedule,"SHOWDESC", 		"#getGridByNetwork[i].DESCEMBED#")>			
					<cfset QuerySetCell(programSchedule,"premiere", 		"#getGridByNetwork[i].PREMIEREFINALE#")>
					<cfset QuerySetCell(programSchedule,"GENRE", 			"#getGridByNetwork[i].GENRE#")>
					<cfset QuerySetCell(programSchedule,"LIVE", 			"#getGridByNetwork[i].LIVE#")>					
					<cfset QuerySetCell(programSchedule,"PREMIEREANDLIVE",	'#getGridByNetwork[i].PREMIEREFINALE#'&'#getGridByNetwork[i].LIVE#')>
					<cfset QuerySetCell(programSchedule,"PROJECTED",		'#getGridByNetwork[i].projected#')>

					<cfif  StructKeyExists(getGridByNetwork[i],"packageid")>
						<cfset QuerySetCell(programSchedule,"PACKAGEID",		'#getGridByNetwork[i].packageid#')>
					<cfelse>
						<cfset QuerySetCell(programSchedule,"PACKAGEID",		'0')>					
					</cfif>
				</cfif>
				
				<cftry>
				<cfif StructKeyExists(getGridByNetwork[i],"createdat")>
					<cfset QuerySetCell(programSchedule,"CREATEDAT",		'#DateFormat(getGridByNetwork[i].createdat,"mm-dd-yy")#')>
				<cfelse>
					<cfset QuerySetCell(programSchedule,"CREATEDAT",		'')>
				</cfif>
				
				<cfif StructKeyExists(getGridByNetwork[i],"updatedat")>
					<cfset QuerySetCell(programSchedule,"UPDATEDAT",		'#DateFormat(getGridByNetwork[i].updatedat,"mm-dd-yy")#')>
				<cfelse>
					<cfset QuerySetCell(programSchedule,"UPDATEDAT",		'')>
				</cfif>
					<cfcatch></cfcatch>
				</cftry>
					
			</cfloop>
				
		<cfreturn programSchedule>
	
			
	</cffunction>

	
	


	<!--- USER PROPOSALS --->
	<cffunction name="proposals" access="public">
	
		<cfparam  default="160" name="arguments.userid">
		
		<cftry>
			<cfquery datasource="showseeker" name="proposals">
				SELECT 	name, id, userid 
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
		<cfparam default="YES" name="isJSON">
		
		
			<cfquery datasource="showseeker" name="proposals">
				SELECT  	name, id
				FROM 		proposals 
				WHERE 	USERID = #arguments.userid# AND deletedat IS NULL
				ORDER BY CREATEDAT DESC
			</cfquery>
			<cfif isJSON EQ 'YES'>
			<cfoutput>
			{
				"proposals":[<cfloop query="proposals">
				{
				"id":"#proposals.id#",
				"name":"#proposals.name#"}<cfif NOT proposals.IsLast()>,</cfif></cfloop>
				]
			}</cfoutput><cfabort></cfif>		<cfreturn proposals>
	
	</cffunction>


	
	<!--- USER ZONES --->
	<cffunction name="zones">
		<cfparam  name="userid" 	default="0" >
		<cfparam  name="zoneid" 	default="0">


			<cfquery name="userzones" datasource="showseeker">
				select * from userzones where userid = #userid#
			</cfquery>
			
			<cfquery name="marketzone" datasource="showseeker">
				select marketid as id from marketzones where zoneid = #zoneid#
			</cfquery>

			<cfquery name="roles" datasource="showseeker">
				select * from userroles where userid = #userid# and roleid = 15
			</cfquery>			
			
		   <cfif userzones.recordCount GT 0>


					<cfquery datasource="showseeker" name="zones">
					
							SELECT 		distinct
										users.id, 
										zones.id as zoneid,
										zones.name AS zonename,
										zones.dmaid,
										zones.syscode,
										zones.zipcode,
										zones.timezoneid,
										zones.isdma,
										zones.corporationid AS zonecorporationid,
										timezones.name AS timezonename,
										timezones.databasename,
										timezones.abbreviation,
										timezones.phpname,
										timezones.utcdifference
						FROM 			users 
						INNER JOIN 		userzones 	ON users.id = userzones.userid
						INNER JOIN		zones 		ON userzones.zoneid  = zones.id
						INNER JOIN 		timezones 	ON zones.timezoneid = timezones.id 
						WHERE 			( users.id = #userid# ) 
						AND 			( users.deletedat IS NULL )  
						AND 			( zones.isdma = 'no' )    
						ORDER BY 		zones.name ASC 			
					
				</cfquery>


				   
			<cfelseif roles.recordCount EQ 0> <!--- STANDARD USER ACCOUNT --->


					<cfquery datasource="showseeker" name="zones">
					
						SELECT DISTINCT	users.id, 
												zones.id as zoneid,
												zones.name AS zonename,
												zones.dmaid,
												zones.syscode,
												zones.zipcode,
												zones.timezoneid,
												zones.isdma,
												zones.corporationid AS zonecorporationid,
												timezones.name AS timezonename,
												timezones.databasename,
												timezones.abbreviation,
												timezones.phpname,
												timezones.utcdifference

						FROM 				 	users 
						LEFT OUTER JOIN 	useroffices 
						ON 					users.id = useroffices.userid 
						INNER JOIN 			offices 
						ON 					useroffices.officeid = offices.id AND offices.deletedat IS NULL
						INNER JOIN 			regions 
						ON 					offices.regionid = regions.id AND regions.deletedat IS NULL
						INNER JOIN 			marketzones
						ON 					regions.id = marketzones.marketid
						INNER JOIN 			zones
						ON 					marketzones.zoneid = zones.id AND zones.deletedat IS NULL
						INNER JOIN 			timezones
						ON 					zones.timezoneid = timezones.id
						WHERE 				( users.id = #userid# ) 
						AND 					( users.deletedat IS NULL )  
						<cfif marketzone.id EQ 14 OR marketzone.id EQ 190>
						AND 					( regions.id = #marketzone.id# )
						</cfif>
						AND 					( regions.deletedat IS NULL )						
						AND						( zones.isdma = 'no' ) 
						AND 					( zones.deletedat IS NULL )
						ORDER BY 			zones.name ASC
					
				</cfquery>
				
			<cfelse><!--- REP FIRM ACCOUNT --->

					<cfquery datasource="showseeker" name="zones">

						SELECT distinct 
						zones.id as zoneid,
						zones.name AS zonename,
						zones.dmaid,zones.syscode,
						zones.zipcode,zones.timezoneid,zones.isdma,
						zones.corporationid AS zonecorporationid,
						timezones.name AS timezonename,
						timezones.databasename,
						timezones.abbreviation,timezones.phpname,
						timezones.utcdifference
						
						FROM 
						users 		INNER JOIN corporations
						ON 			corporations.id = users.corporationid
						inner join 	regions 	ON corporations.id = regions.corporationid
						inner join	marketzones 	ON regions.id = marketzones.marketid
						inner join  zones	ON marketzones.zoneid = zones.id AND zones.deletedat IS NULL 
						INNER JOIN timezones ON zones.timezoneid = timezones.id 		     
						WHERE ( users.id = #userid# ) 
						AND 	( users.deletedat IS NULL )  
						AND 	( zones.isdma = 'no' )  
						ORDER BY zones.name ASC 				
				
					</cfquery>

			</cfif>
	
		<cfreturn zones>
	
	</cffunction>
	
	
	
	<!--- ZONE STATIONS --->
	<cffunction name="stations">
		<cfargument name="zoneid">
		<cfargument name="userid">
		<cfargument name="apiKey">
		
		<cfset psl = "https://plusapi.showseeker.com/zone/load/#zoneid#">
		
		<cfhttp url="#psl#" result="stations" method="GET" >
			<cfhttpparam type="header" name="Api-Key" value="#arguments.apiKey#"> 
			<cfhttpparam type="header" name="User" value="#arguments.userid#"> 
		</cfhttp>
		
		<cfset stationsJSON = DeserializeJSON(stations.fileContent.toString())>
		
		<cfreturn stationsJSON>
		
	</cffunction>
	
	
	
	
	<cffunction name="stations_old">
		<cfargument name="zoneid" default="10">

		<cfquery datasource="showseeker" name="stations">
			SELECT 	zonenetworks.zoneid,
					zonenetworks.networkid,
					zones.id,
					zones.name,
					zones.dmaid,
					zones.syscode,
					zones.zipcode,
					zones.timezoneid,	
					timezones.abbreviation,
					tms_networks.networkid AS tms_networknetworkid,
					tms_networks.timezone,
					tms_networks.name AS tms_networkname,
					tms_networks.callsign,
					tms_networks.affiliate,
					tms_networks.city,
					tms_networks.state,
					tms_networks.country,
					tms_networks.dma,
					tms_networks.dmanumber,
					networklogos.networkid AS networklogonetworkid,
					networklogos.logoid,
					logos.name AS Logoname,
					logos.filename 
			FROM 	zonenetworks 
			INNER JOIN zones 
			ON 		zonenetworks.zoneid = zones.id AND zones.deletedat IS NULL 
			INNER JOIN timezones 
			ON 		zones.timezoneid = timezones.id			
			INNER JOIN tms_networks 
			ON 		zonenetworks.networkid = tms_networks.networkid 
			LEFT OUTER JOIN networklogos 
			ON 		tms_networks.networkid = networklogos.networkid AND networklogos.deletedat IS NULL 
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
			<CFIF (timezoneid EQ 1) OR (timezoneid EQ 13) OR (timezoneid EQ 14) OR ((#DateFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), 'm/dd/yyyy')#& #TimeFormat(DateAdd("h",#TIMEOFFSET#,DateConvert("local2utc", NOW())), "HH:MM")# LT dstStart&"02:00"
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
	
		<cfset TZS = QueryNew("ID,abbreviation,timeoffset,standardtime,savingstime")>
		
		<cfloop list="HAST|-10|1|1,AST|-9|2|3,PST|-8|4|5,MST|-7|6|8,MDT|-7|14|14,CST|-6|9|10,EST|-5|11|12,PR|-4|13|13" index="i" delimiters=",">
		
			<cfset fields = ListToArray(i,"|")>		
			
			<cfset QueryAddRow(TZS)>
			<cfset QuerySetCell(TZS,"ID","#i#")>
			<cfset QuerySetCell(TZS,"abbreviation","#fields[1]#")>
			<cfset QuerySetCell(TZS,"timeoffset","#fields[2]#")>
			<cfset QuerySetCell(TZS,"standardtime","#fields[3]#")>
			<cfset QuerySetCell(TZS,"savingstime","#fields[4]#")>												
		</cfloop>
		
		<cfreturn TZS>
	
	</cffunction>
	
	
	
	<cffunction name="timezonemapping">

		<cfargument name="zoneabbreviation" default="pst">
				
		<cfset TZS = timezones()>

		<cfquery dbtype="query" name="tzmapping">
			SELECT ID FROM TZS WHERE abbreviation = '#zoneabbreviation#'
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

		<cfset schedule = QueryNew("SKEDULEID,PROGRAM_KEY,CALLSIGN,DURATION,AIRDATE,AIRDAY,STARTDATE,ENDDATE,STARTTIME,ENDTIME,STATIONID,NEW,PROGRAM,EPISODE,SHOWDESC,PREMIERE,GENRE,LIVE,DAYNUM,TMSID,PREMIEREANDLIVE,PROJECTED,PACKAGEID, NETWORKID,UPDATEDAT, CREATEDAT") >
		  
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
		   <cfset QuerySetCell(schedule,"NETWORKID",			"#netschedule.stationid#")>	  
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
			<cfset QuerySetCell(schedule,"PACKAGEID", 		'#netschedule.PACKAGEID#')>

			<cfset QuerySetCell(schedule,"CREATEDAT",			'#netschedule.CREATEDAT#')>
			<cfset QuerySetCell(schedule,"UPDATEDAT",			'#netschedule.UPDATEDAT#')>
			
		
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

	<cffunction name="zonenetworks" access="public" returnType="any">
		<cfargument name="zoneid">
		
		<cfquery datasource="showseeker" name="s">
			select distinct tms_networks.callsign, tms_networks.networkid, tms_networks.name
			from zones 
			inner join zonenetworks 
			on zones.id = zonenetworks.zoneid
			inner join tms_networks
			on zonenetworks.networkid = tms_networks.networkid
			where zones.id = '#zoneid#' and zones.deletedat is null
			order by callsign		
		</cfquery>
		
		
		
		<cfreturn s>
	</cffunction>	


</cfcomponent>

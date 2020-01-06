<cfcomponent hint="Gets the Data directly from MySQL">

	<cffunction name="searchGracenote">
		
		<cfargument name="timezone">
		<cfargument name="stations">
		<cfargument name="startdate">
		<cfargument name="enddate">
		<cfargument name="starttime">
		<cfargument name="endtime">
		<cfargument name="pjstartdate">

	
		<cfswitch expression="#timezone#">
			<cfcase value="AST">
				<cfset tz = "US/Alaska">
			</cfcase>
			<cfcase value="PST">
				<cfset tz = "US/Pacific">
			</cfcase>
			<cfcase value="MST">
				<cfset tz = "US/Arizona">
			</cfcase>
			<cfcase value="MDT">	
				<cfset tz = "US/Mountain">
			</cfcase>
			<cfcase value="CST">
				<cfset tz = "US/Central">
			</cfcase>
			<cfcase value="EST">
				<cfset tz = "US/Eastern">
			</cfcase>
			<cfcase value="PR">
				<cfset tz = "America/Puerto_Rico">
			</cfcase>
			<cfcase value="HAST">
				<cfset tz = "US/Hawaii">
			</cfcase>
		</cfswitch>


		<cfsavecontent variable="schedules">
			<cfoutput>
			SELECT	id,
					callsign,
					showtype,
					'0' as projected, 
					'' as packageid,
					showid, 
					showid as tmsid,
					live,
					genre,
					stars,
					descembed,
					orgairdate,
					title,
					premierefinale,
					new,
					epititle,
					'' as createdat, 
					'' as updatedat, 
					((SUBSTRING(duration,1,2)*60) + SUBSTRING(duration,3,2)) AS duration,								
					DATE_FORMAT(CONVERT_TZ(airdate,'GMT','#tz#'), '%Y-%m-%dT%TZ')  AS  tz_start_#timezone#,
					DATE_FORMAT(DATE_ADD(CONVERT_TZ(airdate,'GMT','#tz#'), INTERVAL ((SUBSTRING(duration,1,2)*60) + SUBSTRING(duration,3,2)) MINUTE), '%Y-%m-%dT%TZ') AS tz_end_#timezone#,
					TIME(CONVERT_TZ(airdate,'GMT','#tz#')) start_#timezone#,
					DAYOFWEEK(CONVERT_TZ(airdate,'GMT','#tz#')) AS day_#timezone#
						
			FROM 	programData

			WHERE 	stationnum 	IN ('#stations#') 
			AND		((genre1 = 'sports event' AND live = 'Live') OR (premierefinale='Series Premiere' OR  premierefinale='Series Finale' OR premierefinale='Season Premiere' OR premierefinale='Season Finale'))
			AND 	DATE_FORMAT(CONVERT_TZ(airdate,'GMT','#tz#'), '%Y-%m-%dT%TZ')  BETWEEN '#startdate#' AND '#enddate#'
			AND 	DATE_FORMAT(CONVERT_TZ(airdate,'GMT','#tz#'), '%H:%m-%dT%TZ')  BETWEEN '#starttime#' AND '#endtime#'
			AND 	DATE_FORMAT(CONVERT_TZ(airdate,'GMT','#tz#'), '%Y-%m-%dT%TZ')  >= '#pjstartdate#'
			ORDER BY airdate
			
			</cfoutput>
		</cfsavecontent>
		
		<cfreturn schedules>
	
	
		<!--- cfquery dataSource="solr" name="result">
			#schedules#
		</cfquery>

		<cfreturn result --->
		
	</cffunction>
	
	
	
		<cffunction name="searchProjected">
		
		<cfargument name="timezone">
		<cfargument name="stations">
		<cfargument name="fields">
		<cfargument name="condition">
		
	
		<cfswitch expression="#timezone#">
			<cfcase value="AST">
				<cfset tz = "US/Alaska">
			</cfcase>
			<cfcase value="PST">
				<cfset tz = "US/Pacific">
			</cfcase>
			<cfcase value="MST">
				<cfset tz = "US/Arizona">
			</cfcase>
			<cfcase value="MDT">	
				<cfset tz = "US/Mountain">
			</cfcase>
			<cfcase value="CST">
				<cfset tz = "US/Central">
			</cfcase>
			<cfcase value="EST">
				<cfset tz = "US/Eastern">
			</cfcase>
			<cfcase value="PR">
				<cfset tz = "America/Puerto_Rico">
			</cfcase>
			<cfcase value="HAST">
				<cfset tz = "US/Hawaii">
			</cfcase>
		</cfswitch>


		<cfsavecontent variable="schedules">
			<cfoutput>
			SELECT	#fields#,
			
					((SUBSTRING(duration,1,2)*60) + SUBSTRING(duration,3,2)) AS duration,					
			
					CASE 
							WHEN 	tbd = 1 THEN 	DATE_FORMAT(CONVERT_TZ(airdate,'GMT','#tz#'), '%Y-%m-%dT06:00:00Z')
							ELSE	DATE_FORMAT(CONVERT_TZ(airdate,'GMT','#tz#'), '%Y-%m-%dT%TZ') 
							END AS  tz_start_#timezone#,
					
					CASE 	
							WHEN 	tbd = 1 THEN  DATE_FORMAT(CONVERT_TZ(airdate,'GMT','#tz#'), '%Y-%m-%dT23:59:00Z') 
							ELSE	DATE_FORMAT(DATE_ADD(CONVERT_TZ(airdate,'GMT','#tz#'), INTERVAL ((SUBSTRING(duration,1,2)*60) + SUBSTRING(duration,3,2)) MINUTE), '%Y-%m-%dT%TZ') 
							END AS 	tz_end_#timezone#,
					
					CASE 	
							WHEN 	tbd <> 1 then TIME(CONVERT_TZ(airdate,'GMT','#tz#')) 
							ELSE 	'06:00:00' 
							END AS 	start_#timezone#,
					
					DAYOFWEEK(CONVERT_TZ(airdate,'GMT','#tz#')) AS day_#timezone#
						
			FROM 	programData

			WHERE 	stationnum 	IN ('#stations#') 
			AND 	#condition#
			</cfoutput>
		</cfsavecontent>
		
		<cfreturn schedules>
	
	
		<!--- cfquery dataSource="solr" name="result">
			#schedules#
		</cfquery>

		<cfreturn result --->
		
	</cffunction>

			
</cfcomponent>
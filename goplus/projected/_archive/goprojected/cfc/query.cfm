<cfoutput>
SELECT 		'' AS PROGRAM_KEY,
			'1'	AS DURATION,
			CAST(STARTDATE as DATETIME)  +' '+Cast(STARTTIME as DATETIME)   as airDate, 
			LEFT({fn DAYNAME(DATEADD(HH,  0, Cast(STARTDATE as DATETIME)) )},3) AS airDay,
			CONVERT(char(8),  DATEADD(HH,  0, Cast(STARTDATE +' '+STARTTIME as DATETIME)),1) AS startDate, 			 
			RIGHT(CONVERT(char(20),DATEADD(HH,  0, Cast(STARTDATE as DATETIME) +' '+CAST(STARTTIME AS DATETIME)),0),8) AS startTime,
			CONVERT(char(8),  DATEADD(HH,  0, Cast(ENDDATE as DATETIME)  +' '+Cast(ENDTIME as DATETIME)),1) AS endDate,
			RIGHT(CONVERT(char(20),DATEADD(HH,  0, Cast(ENDDATE as DATETIME) +' '+CAST(ENDTIME AS DATETIME)),0),8) AS endTime,
			'10000' AS tf_station_num,
			RTRIM(LTRIM(NEW)) as NEW,
			'' AS TF_DATABASE_KEY,
			CASE 
				WHEN IsNull(RTRIM(LTRIM(PREMIERES)),'LIVE') <> '' THEN RTRIM(LTRIM(PREMIERES)) 
				ELSE 'LIVE'
				END AS  tf_premiere_finale,
			RTRIM(LTRIM(LIVE)) as tf_live_tape_delay,
			'' tf_star_rating,
			'' AS GENRE,
			'' AS tf_org_air_date,
			RTRIM(LTRIM(TITLE)) AS tf_title, 
			RTRIM(LTRIM(DESCRIPTION)) as SHOWDESC, 
			RTRIM(LTRIM(EPISODE)) as EPISODE, 
			RTRIM(LTRIM(NETWORK)) AS tf_station_call_sign, 
			RTRIM(LTRIM(NETWORK)) AS tf_station_name,
			'1' AS ISPROJECTED			
FROM 		PROJECTED
WHERE		CAST(STARTDATE AS DATETIME)  >= '#DateFormat(DateAdd("d",56,Now()),"mm/dd/yyyy")#'

		
ORDER BY airDate, tf_station_call_sign
</cfoutput>
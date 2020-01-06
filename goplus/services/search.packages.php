<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

	$tz = $_GET["tz"];
	$id = $_GET["id"];
	$stations = $_GET["stations"];	
	$sdate = $_GET["startdate"];
	$edate = $_GET["enddate"];
	$stime = $_GET["stime"];
	$etime = $_GET["etime"];


	$dbHost		= 'db5.showseeker.net';
	$dbUserName	= 'vastdb';
	$dbPassWord	= 'VastPlus#01';
	$dbName		= 'showseeker';

	$cnn = mysqli_connect($dbHost,$dbUserName,$dbPassWord,$dbName);
	mysqli_set_charset($cnn, "utf8");


	if (mysqli_connect_errno()){
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}	

$sql = "select * from (SELECT
					affiliate,						callsign, 						createdat,
					country,							color,							desc60,
					descembed,						dmanumber,						dmaname,
					dubbed,							epititle,						gamedate,						
					gametime,						gametimezone,
					hdtv,								holiday,							id,
					language,						live,								madefortv,						
					new AS isnew,					new,								orgairdate,						
					packageid,
					premierefinale,				projected,						projected_source,
					rating,							reduceddesc,					runtime,
					stars,							stationshowid,					stationshowtimeid,			
					showtype,						stationnum,						showid AS tmsid,				
					stationname,					stationzipcode,				stationtimezone,
					source,							tbd,								title AS sort,
					title,							tvrating,						type,
					updatedat,						year,
								
					CASE WHEN credits = '  , , , , ,' then ''	else credits END 	AS credits,
					((SUBSTRING(duration,1,2)*60) + SUBSTRING(duration,3,2)) 	AS duration,					
					CONCAT_WS(' ',search,credits) 										AS search,
					replace(SUBSTRING( showid, 1, 10 ),'EP','SH') 					AS showid ,		

					
					LOWER(genre) AS genre,		
					LOWER(genre1) AS genre1,
					LOWER(genre2) AS genre2,
					LOWER(genre3) AS genre3,
					LOWER(genre4) AS genre4,
					LOWER(genre5) AS genre5,
					LOWER(genre6) AS genre6,
			
			
				case when tbd = 1 then 	DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Pacific'), '%Y-%m-%dT06:00:00Z')
				else	DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Pacific'), '%Y-%m-%dT%TZ') end AS tz_start_pst,
				case when tbd = 1 then  DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Pacific'), '%Y-%m-%dT23:59:00Z') 
				else	DATE_FORMAT(DATE_ADD(CONVERT_TZ(airdate,'GMT','US/Pacific'), INTERVAL ((SUBSTRING(duration,1,2)*60) + SUBSTRING(duration,3,2)) MINUTE), '%Y-%m-%dT%TZ') end AS tz_end_pst,
				case when tbd <> 1 then TIME(CONVERT_TZ(airdate,'GMT','US/Pacific')) 
				else '06:00:00' end AS start_pst,
				DAYOFWEEK(CONVERT_TZ(airdate,'GMT','US/Pacific')) AS day_pst,
			



				case when tbd = 1 then 	DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Alaska'), '%Y-%m-%dT06:00:00Z')
				else	DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Alaska'), '%Y-%m-%dT%TZ') end AS tz_start_ast,
				case when tbd = 1 then  DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Alaska'), '%Y-%m-%dT23:59:00Z') 
				else	DATE_FORMAT(DATE_ADD(CONVERT_TZ(airdate,'GMT','US/Alaska'), INTERVAL ((SUBSTRING(duration,1,2)*60) + SUBSTRING(duration,3,2)) MINUTE), '%Y-%m-%dT%TZ') end AS tz_end_ast,			
				case when tbd <> 1 then TIME(CONVERT_TZ(airdate,'GMT','US/Alaska')) 
				else '06:00:00' end AS start_ast,
				DAYOFWEEK(CONVERT_TZ(airdate,'GMT','US/Alaska')) AS day_ast,
			

				case when tbd = 1 then 	DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Arizona'), '%Y-%m-%dT06:00:00Z')
				else	DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Arizona'), '%Y-%m-%dT%TZ') end AS tz_start_mst,
				case when tbd = 1 then  DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Arizona'), '%Y-%m-%dT23:59:00Z') 
				else	DATE_FORMAT(DATE_ADD(CONVERT_TZ(airdate,'GMT','US/Arizona'), INTERVAL ((SUBSTRING(duration,1,2)*60) + SUBSTRING(duration,3,2)) MINUTE), '%Y-%m-%dT%TZ') end AS tz_end_mst,			
				case when tbd <> 1 then TIME(CONVERT_TZ(airdate,'GMT','US/Arizona')) 
				else '06:00:00' end AS start_mst,
				DAYOFWEEK(CONVERT_TZ(airdate,'GMT','US/Arizona')) AS day_mst,
			


				case when tbd = 1 then 	DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Mountain'), '%Y-%m-%dT06:00:00Z')
				else	DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Mountain'), '%Y-%m-%dT%TZ') end AS tz_start_mdt,
				case when tbd = 1 then  DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Mountain'), '%Y-%m-%dT23:59:00Z') 
				else	DATE_FORMAT(DATE_ADD(CONVERT_TZ(airdate,'GMT','US/Mountain'), INTERVAL ((SUBSTRING(duration,1,2)*60) + SUBSTRING(duration,3,2)) MINUTE), '%Y-%m-%dT%TZ') end AS tz_end_mdt,			
				case when tbd <> 1 then TIME(CONVERT_TZ(airdate,'GMT','US/Mountain')) 
				else '06:00:00' end AS start_mdt,
				DAYOFWEEK(CONVERT_TZ(airdate,'GMT','US/Mountain')) AS day_mdt,
			


				case when tbd = 1 then 	DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Central'), '%Y-%m-%dT06:00:00Z')
				else	DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Central'), '%Y-%m-%dT%TZ') end AS tz_start_cst,
				case when tbd = 1 then  DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Central'), '%Y-%m-%dT23:59:00Z') 
				else	DATE_FORMAT(DATE_ADD(CONVERT_TZ(airdate,'GMT','US/Central'), INTERVAL ((SUBSTRING(duration,1,2)*60) + SUBSTRING(duration,3,2)) MINUTE), '%Y-%m-%dT%TZ') end AS tz_end_cst,
				case when tbd <> 1 then TIME(CONVERT_TZ(airdate,'GMT','US/Central')) 
				else '06:00:00' end AS start_cst,				
				DAYOFWEEK(CONVERT_TZ(airdate,'GMT','US/Central')) AS day_cst,
			
			

				case when tbd = 1 then 	DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Eastern'), '%Y-%m-%dT06:00:00Z')
				else	DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Eastern'), '%Y-%m-%dT%TZ') end AS tz_start_est,
				case when tbd = 1 then  DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Eastern'), '%Y-%m-%dT23:59:00Z') 
				else	DATE_FORMAT(DATE_ADD(CONVERT_TZ(airdate,'GMT','US/Eastern'), INTERVAL ((SUBSTRING(duration,1,2)*60) + SUBSTRING(duration,3,2)) MINUTE), '%Y-%m-%dT%TZ') end AS tz_end_est,
				case when tbd <> 1 then TIME(CONVERT_TZ(airdate,'GMT','US/Eastern')) 
				else '06:00:00' end AS start_est,								
				DAYOFWEEK(CONVERT_TZ(airdate,'GMT','US/Eastern')) AS day_est,



				case when tbd = 1 then 	DATE_FORMAT(CONVERT_TZ(airdate,'GMT','America/Puerto_Rico'), '%Y-%m-%dT06:00:00Z')
				else	DATE_FORMAT(CONVERT_TZ(airdate,'GMT','America/Puerto_Rico'), '%Y-%m-%dT%TZ') end AS tz_start_pr,
				case when tbd = 1 then  DATE_FORMAT(CONVERT_TZ(airdate,'GMT','America/Puerto_Rico'), '%Y-%m-%dT23:59:00Z') 
				else	DATE_FORMAT(DATE_ADD(CONVERT_TZ(airdate,'GMT','America/Puerto_Rico'), INTERVAL ((SUBSTRING(duration,1,2)*60) + SUBSTRING(duration,3,2)) MINUTE), '%Y-%m-%dT%TZ') end AS tz_end_pr,	
				case when tbd <> 1 then TIME(CONVERT_TZ(airdate,'GMT','America/Puerto_Rico')) 
				else '06:00:00' end AS start_pr,
				DAYOFWEEK(CONVERT_TZ(airdate,'GMT','America/Puerto_Rico')) AS day_pr,


				case when tbd = 1 then 	DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Hawaii'), '%Y-%m-%dT06:00:00Z')
				else	DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Hawaii'), '%Y-%m-%dT%TZ') end AS tz_start_hast,
				case when tbd = 1 then  DATE_FORMAT(CONVERT_TZ(airdate,'GMT','US/Hawaii'), '%Y-%m-%dT23:59:00Z') 
				else	DATE_FORMAT(DATE_ADD(CONVERT_TZ(airdate,'GMT','US/Hawaii'), INTERVAL ((SUBSTRING(duration,1,2)*60) + SUBSTRING(duration,3,2)) MINUTE), '%Y-%m-%dT%TZ') end AS tz_end_hast,	
				case when tbd <> 1 then TIME(CONVERT_TZ(airdate,'GMT','US/Hawaii')) 
				else '06:00:00' end AS start_hast,


				DAYOFWEEK(CONVERT_TZ(airdate,'GMT','US/Hawaii')) AS day_hast
			
			
				FROM programDataProjected";
				
				if($id == 0){
					$sql = $sql." WHERE authorized = '1' AND deletedat IS NULL AND PACKAGEID IN (132,133,134) AND stationnum in ({$stations}) ";
				}
				elseif($id == 1){
					$sql = $sql." WHERE authorized = '1' AND deletedat IS NULL AND PACKAGEID IN (128,129,130) AND stationnum in ({$stations}) ";
				}
				elseif($id == 2){
					$sql = $sql." WHERE authorized = '1' AND deletedat IS NULL AND PACKAGEID IN (149,150) AND stationnum in ({$stations}) ";
				}
				else{
					$sql = $sql." WHERE authorized = '1' AND deletedat IS NULL AND PACKAGEID = {$id} AND stationnum in ({$stations}) ";
				}
				
					
				if($tz == 'hast'){$sql = $sql."AND CONVERT_TZ(airdate,'GMT','US/Hawaii') BETWEEN '$sdate' AND '$edate' ";}
				if($tz == 'ast'){$sql = $sql."AND CONVERT_TZ(airdate,'GMT','US/Alaska') BETWEEN '$sdate' AND '$edate' ";}
				if($tz == 'pst'){$sql = $sql."AND CONVERT_TZ(airdate,'GMT','US/Pacific') BETWEEN '$sdate' AND '$edate' ";}
				if($tz == 'mst'){$sql = $sql."AND CONVERT_TZ(airdate,'GMT','US/Arizona') BETWEEN '$sdate' AND '$edate' ";}
				if($tz == 'mdt'){$sql = $sql."AND CONVERT_TZ(airdate,'GMT','US/Mountain') BETWEEN '$sdate' AND '$edate' ";}
				if($tz == 'cst'){$sql = $sql."AND CONVERT_TZ(airdate,'GMT','US/Central') BETWEEN '$sdate' AND '$edate' ";}
				if($tz == 'est'){$sql = $sql."AND CONVERT_TZ(airdate,'GMT','US/Eastern') BETWEEN '$sdate' AND '$edate' ";}
				if($tz == 'pr'){$sql  = $sql."AND CONVERT_TZ(airdate,'GMT','America/Puerto_Rico') BETWEEN '$sdate' AND '$edate' ";}
				
				
				$sql = $sql." ) resuls where ";

				
				if($tz == 'hast'){$sql = $sql." Time_Format(start_hast,'%H:%i:%S') >= '$stime' AND Time_Format(start_hast,'%H:%i:%S')  <= '$etime' ";}
				if($tz == 'ast'){$sql = $sql."  Time_Format(start_ast,'%H:%i:%S') >= '$stime' AND  Time_Format(start_ast,'%H:%i:%S')<= '$etime' ";}
				if($tz == 'pst'){$sql = $sql."  Time_Format(start_pst,'%H:%i:%S') >= '$stime' AND Time_Format(start_pst,'%H:%i:%S') <= '$etime' ";}
				if($tz == 'mst'){$sql = $sql."  Time_Format(start_mst,'%H:%i:%S') >= '$stime' AND Time_Format(start_mst,'%H:%i:%S') <= '$etime' ";}
				if($tz == 'mdt'){$sql = $sql."  Time_Format(start_mdt,'%H:%i:%S') >= '$stime' AND Time_Format(start_mdt,'%H:%i:%S') <= '$etime' ";}
				if($tz == 'cst'){$sql = $sql."  Time_Format(start_cst,'%H:%i:%S') >= '$stime' AND Time_Format(start_cst,'%H:%i:%S') <= '$etime' ";}
				if($tz == 'est'){$sql = $sql."  Time_Format(start_est,'%H:%i:%S') >= '$stime' AND Time_Format(start_est,'%H:%i:%S') <= '$etime' ";}
				if($tz == 'pr'){$sql  = $sql."  Time_Format(start_pr,'%H:%i:%S') >= '$stime' AND Time_Format(start_pr,'%H:%i:%S') <= '$etime' ";}




			$result = mysqli_query($cnn, $sql);

			if($result->num_rows == 0){
	   			$data = array();
			}
			else{
				//IF NOT RESULTS RETURN EMPTY ARRAY
				while ($row = $result->fetch_assoc()) {
		   			$data[] = $row;
		    	}
		   }
		   
			$packagedata = array("responseHeader"=>array(),"response"=>array("numfound"=>$result->num_rows, "start"=>"0", "docs"=>$data));

			$re = json_encode($packagedata);

			print_r($re);


?>
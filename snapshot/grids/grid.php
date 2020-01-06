<!DOCTYPE HTML>
<html>
	<head>
		<title>ShowSeeker | SnapShot</title>
		<link 	rel="shortcut icon" href="../../images/favicon.ico" type="image/x-icon" /> 
		<script 	src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script 	src="../../js/date.js"></script>		
	</head>
	<body>	
		<div align="center">
			<div style="font-size: x-large; font-family: Arial, Verdana, Tahoma; color:#999; width:50%; line-height:90px; height:90px;">
				ShowSeeker SnapShot Grids Loading . . . 
			</div>
		</div>
		
		<form action="index.php" method="post" id="redirector">
			<input name="userid" 		id="userid" 			type="hidden">
			<input name="tokenid" 		id="tokenid"			type="hidden">
			<input name="apiKey" 		id="apiKey"				type="hidden">
			<input name="marketid" 		id="marketid"			type="hidden">
			<input name="zones" 			id="zones"				type="hidden">
			<input name="station" 		id="station"			type="hidden">			
			<input name="startDate" 	id="startDate"			type="hidden">
			<input name="endDate" 		id="endDate"			type="hidden">															
			<input name="sTime" 			id="sTime"				type="hidden">
			<input name="eTime" 			id="eTime"				type="hidden">
			<input name="timezone" 		id="timezone"			type="hidden">															
		</form>
		
		<script language="javascript">
			document.domain = "showseeker.com";
			$(document).ready(function(){
	
				sswin = window.opener;
	
	
				if (null == sswin) {
					closemsg  =  "<!DOCTYPE html><html><head>"
					closemsg += "<style>p{font-family: sans-serif; font-size: 14pt; color: #666;} a{font-family: Arial; font-size:14pt; color:blue; text-decoration: none; font-weight: 700;}</style>";
					closemsg +=  "</head><body>";
					closemsg +=  "<p align=center><img src=http://plus.dev.showseeker.com/i/logo500.png></p>";						
					closemsg += "<p align=center><BR><BR>Please launch EzGrids from <span><i>ShowSeeker +</i></span>.<BR><BR>";
					closemsg += "Click  <a href=http://plus.dev.showseeker.com>here</a> to login </p>"
					closemsg += "</body></html>";
					window.document.write(closemsg);
					return;
				}
				else{
					$('#userid').val(sswin.userid);
					$('#tokenid').val(sswin.tokenid);
					$('#apiKey').val(sswin.apiKey);
			 	
				 	params = sswin.solrSearchParamaters();
				
				 	var sdate = Date.parse(params.startdate).toString("MM/dd/yyyy");
				 	var edate = Date.parse(params.enddate).toString("MM/dd/yyyy");
					
					mysdate = sdate.substr(6,4);
					myedate = edate.substr(6,4);
					
					if(mysdate.substr(0,2) == '19'){
						sdate = sdate.substr(0,6)+'20'+mysdate.substr(2,2);
					}
					
					if(myedate.substr(0,2) == '19'){
						edate = edate.substr(0,6)+'20'+myedate.substr(2,2);
					}
	
					$('#zones').val(params.zoneid);
					$('#marketid').val(params.marketid);					
					$('#station').val(params.networks[0].id);						
					$('#startDate').val(sdate);
					$('#endDate').val(edate);
					$('#sTime').val(params.starttime);
					$('#eTime').val(String(params.endtime).substr(0, 5));
					$('#timezone').val(params.timezone);
					$('#redirector').submit();		
				}
			})
		</script>		
		
		
		
	</body>	
</html>
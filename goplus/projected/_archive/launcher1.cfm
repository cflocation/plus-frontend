	<cfif NOT IsDefined("zones")>
	<!-- !DOCTYPE HTML>
	<html>
		<head -->
		<script language="javascript">
			//document.domain = "showseeker.com";
					
			$(document).ready(function(){
				document.domain = "showseeker.com";
				sswin = window.opener;

				if (null == sswin) {
					closemsg  =  "<!DOCTYPE html><html><head>"
					closemsg += "<style>span{color:}p{font-family: Arial; font-size: 14pt; color: #666;} a{font-family: Arial; font-size:14pt; color:blue; text-decoration: none; font-weight: 700;}</style>";
					closemsg +=  "</head><body>";
					closemsg +=  "<p align=center><img src=http://plus.showseeker.com/i/logo500.png></p>";						
					closemsg += "<p align=center><BR><BR>Please launch our EzCalendar from <span><i>ShowSeeker +</i></span>.<BR><BR>";
					closemsg += "Click  <a href=http://plus.showseeker.com>here</a> to login </p>"
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
				 	var stime = String(params.starttime).substr(0,5);
				 	var etime = String(params.endtime).substr(0,5);
				 	
				 	if(stime.substr(0,1) == '0')
					 	stime  = stime.substr(1,5);					
					
					if(etime.substr(0,1) == '0')
					 	etime  = etime.substr(1,5);

					$('#zones').val(params.zoneid);					
					$('#startDate').val(sdate);
					$('#endDate').val(edate);
					$('#sTime').val(stime);
					$('#eTime').val(etime);
					
					$('#redirector').submit();		
				}
			})
		</script>		
		
		
		<!-- /head>
		<body -->
		<div align="center"><div style="font-size:14 pt; color:#999; width:50%; line-height:90px; height:90px;"><i>ShowSeeker+</i>Projected Calendar Loading . . .</div></div><cfoutput>
		<cfform action="index.cfm?token=#CreateUUID()#" method="post" id="redirector">		
			<cfinput name="userid" 		id="userid" 			type="hidden">
			<cfinput name="tokenid" 	id="tokenid"			type="hidden">
			<cfinput name="zones" 		id="zones"				type="hidden">
			<cfinput name="startDate" 	id="startDate"			type="hidden">
			<cfinput name="endDate" 	id="endDate"			type="hidden">															
			<cfinput name="sTime" 		id="sTime"				type="hidden">
			<cfinput name="eTime" 		id="eTime"				type="hidden">	
			<cfinput name="t" 			id="t"					type="hidden" 		value="#t#">
		</cfform></cfoutput>
	
		<!-- /body>
					
		</html -->				
	<cfabort>
	</cfif>
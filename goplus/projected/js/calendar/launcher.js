var 	allShows 			= 0;
var 	apiKey  			= '';
var 	dataRespones 		= '';
var 	endDate   			= '';
var 	eTime   			= '';
var 	filtering 			= new Array();
var		firstload			= true;
var 	liveEvents 			= [];
var 	loadedProposalId 	= 0;
var 	otherSportsLive		= 0;
var 	proposallines 		= [];
var		showid 				= new Array();
var 	sportLiveEvents 	= ["College Baseball","College Basketball","College Football","MLB Baseball","MLS Soccer","NBA Basketball","NFL Football","NFL","NASCAR Racing","PGA Tour Golf"];
var 	selectedsport 		= '';
var 	selectedOptions		= '';
var 	mktzones			= [];
var 	userid 				= '';
var 	tokenid 			= '';
var 	sTime   			= '';
var 	startDate 			= '';
var		calendarMonth		= {};

if (typeof zones == 'undefined'){
	var t = GetURLParameter('t');
	document.body.innerHTML = document.body.innerHTML +'<div align="center" id="ctlMessage"><div class="loading"><p><br></p><p>ShowSeeker Calendar Loading ...<br/><br/><img src="../i/ajax.gif"></p></div></div>';

	$(document).ready(function(){
				document.domain = "showseeker.com";
				sswin = window.opener;

				if (null == sswin) {
					closemsg  =  "<!DOCTYPE html><html><head>"
					closemsg += "<style>span{color:}p{font-family: Arial; font-size: 14pt; color: #666;} a{font-family: Arial; font-size:14pt; color:blue; text-decoration: none; font-weight: 700;}</style>";
					closemsg +=  "</head><body>";						
					closemsg += "<p align=center><BR><BR>Please launch our EzCalendar from <span><i>ShowSeeker +</i></span>.<BR><BR>";
					closemsg += "Click  <a href=http://plus.showseeker.com>here</a> to login </p>"
					closemsg += "</body></html>";
						window.document.write(closemsg);
					return;
				}
				else{
					userid = sswin.userid;
					tokenid = sswin.tokenid;
					apiKey  = sswin.apiKey;
				 	
				 	params = sswin.solrSearchParamaters();
				 	var sdate = Date.parse(params.startdate).toString("MM/dd/yyyy");
				 	var edate = Date.parse(params.enddate).toString("MM/dd/yyyy");
				 	var stime = String(params.starttime).substr(0,5);
				 	var etime = String(params.endtime).substr(0,5);
				 	
				 	if(stime.substr(0,1) == '0')
					 	stime  = stime.substr(1,5);					
					
					if(etime.substr(0,1) == '0')
					 	etime  = etime.substr(1,5);

					var zones = params.zoneid;
					var tz    = params.timezone;
					startDate = sdate;
					endDate   = edate;
					sTime     = stime;
					eTime     = etime;
					var url 	  = 'https://projectedcal.showseeker.com/projected?userid='+userid+'&apiKey='+apiKey+'&zoneid='+zones+'&startDate='+startDate+'&endDate='+endDate+'&sTime='+sTime+'&eTime='+eTime+'&tokenid='+tokenid;

					$.ajax({
						crossOrigin: true,
						url:url,
						type:'GET',
						success: function(result){
							dataRespones = result;
							main();
						}
					});		
				}
			})

};

function GetURLParameter(sParam){
    var sPageURL      = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++){
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam){
            return sParameterName[1];
        }
    }
}

	
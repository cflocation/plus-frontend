function openEZGrids(){
	if(solrSearchParamaters().networks.length == 1){
		closeAllDialogs();
		launchgrids = false;
		var h		= screen.height*.85;
		ezgridsOpen = true;
		ezgrids 	= window.open("grids/", "ezgridswindow", "location=no,status=yes,resizable=yes,scrollbars=yes,width=1140,height="+h);
		try{
			$(ezgrids.document).ready(function () {
				ezgrids.focus();
			});
		}
		catch(e){};
		
		var params = solrSearchParamaters();
    		
		mixTrack('Sidebar - Grids Button',{	"callsign":params.networks[0].callsign,
											"endDate":params.enddate.substr(0,10),
											"endDateTime":params.endtime,
											"networkId":params.networks[0].id,
											"startDate":params.startdate.substr(0, 10),
											"startTime":params.starttime,
											"zoneId":params.zoneid,
											"zoneName":params.zone});
	}
	else{
		
		dialogNetworkList();
		loadDialogWindow('singlenetwork', 'ShowSeeker Plus', 450, 180, 1, 0);
		launchgrids = true;
	}
}

function openEZCalendar(type){


	var sPageURL 	= window.location.href;
	var url 		= "/goplus/projected/index.php?t="+type+"";
	var h   		= screen.height*.85;	
	var w   		= screen.width;
	

	if(w < 1800){
		var w = screen.width*.85;
	} else if(w > 1800) {
		var w = screen.width*.65;		
	} else {
		w =988;
	}	
	var winsettings = "location=0, status=0,resizable=1,scrollbars=1,width="+w+",height="+h+"";
	ezcalendarOpen  = true;
	ezcalendar      = window.open(url, "EzCalendar", winsettings).focus();
}


function openImageSelector(type){
       imageswindow = window.open("services/image.selector.php?id="+type, "imageswindow", "location=no,status=yes,resizable=yes,scrollbars=yes,width=980,height=600");
}


function openFAQ(){
		url= 'https://showseeker.zendesk.com/hc/en-us'
   	faq = window.open(url, "faqwindow", "location=no,status=yes,resizable=yes,scrollbars=yes,width=1024,height=700");
}


function openTutorial(url,appendUserId){
	var w = 1024;
	var h = 880;

	if(appendUserId){
		url  += '&userid='+userid;
	}

	LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
	TopPosition  = (screen.height) ? (screen.height-h)/2 : 0;
	ezcalendar   = window.open(url, "tutorialwindow", "location=no,status=yes,resizable=yes,scrollbars=yes,width="+w+",height="+h+",top="+TopPosition+",left="+LeftPosition);
	ezcalendar.focus();
}

function openBroadcasrtCalendar(){
	var w = 1024;
	var h = 880;
	var url  = 'https://showseeker.s3.amazonaws.com/support/ShowSeeker_2020-2021_Broadcast_Calendar.pdf';

	LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
	TopPosition  = (screen.height) ? (screen.height-h)/2 : 0;
	var bcCalendar   = window.open(url, "calendar", "location=no,status=yes,resizable=yes,scrollbars=yes,width="+w+",height="+h+",top="+TopPosition+",left="+LeftPosition);
	bcCalendar.focus();
}


//EXTERNAL FUNCTIONS
//add the line to the proposal from a external source
function externalAddLineToProposal(id,zone,zoneid){
	var ids = [];
	var thisid;

	$.each(id, function(i, value){
		thisid 	= 	String(value).split('-');
		ids.push(thisid[0]);
	});
	
	var data 		= {};
	data.ids 		= ids;
	data.timezone 	= timezone;

	//setup the url to call to get the show information
	$.ajax({
            type:'post',
            url: apiUrl+"plus/showinfo",
            dataType:"json",
            headers:{"Api-Key":apiKey,"User":userid},
            processData: false,
            contentType: 'application/json',    	
			data:JSON.stringify(data),    	
			success:function(resp){
	    		var rows = datasourceBuildGridOld(resp.result);
				var v;
				addFixedLinesToProposal(rows,v);
    		},
    		error:function(){}
	});

	if(datagridProposal.isRowInHiddenWeek() === true){
		loadMessage('hiddenweeks');
	}
}


function externalDeleteLineFromProposal(id,zoneid){
	datagridProposal.deleteLineFromProposal(id);
}



function resetPassword(){
	var url = '/reset.password.php?t='+localStorage.getItem("token")+'&app=plus';
	var w 	= 600;
	var h 	= 600;
	
	var LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
	var TopPosition  = (screen.height) ? (screen.height-h)/2 : 0;
	resetPwd   = window.open(url, "resetPassword", "location=0,status=1,resizable=1,scrollbars=1,width="+w+",height="+h+",top="+TopPosition+",left="+LeftPosition);
	resetPwd.focus();
}


function pwdToken(){
	var data 	= {};
	data.userId = userid;

	$.ajax({type:'post',
            url: apiUrl+"user/passwordreset/passwordtoken",
            dataType:"json",
            headers:{"Api-Key":apiKey,"User":userid},
            processData: false,
            contentType: 'application/json',
			data:JSON.stringify(data),
			success:function(resp){
				localStorage.setItem("token", resp.token);
			}
	});
}


function autoLogOut(event){
    localStorage.removeItem("userId");
    localStorage.removeItem("apiKey");
    localStorage.removeItem("token");
    var loc = "https://plus.showseeker.com/reset.php?app=plus";
    if(event !== undefined){
	    loc = "https://plus.showseeker.com/login.php?app=plus";
	}
	window.location.href = loc;
}

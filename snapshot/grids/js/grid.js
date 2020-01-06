var skedulesList = '';
var boxItems='';
var selectedWeek;
var tdIds = '';
var tilesGrayed = '';
var scheduledPrograms='';
var schedulesToDelete='';
var itemsTmp='';	
var selectedTab='0';		
var printDate='';			
var sdate='';
var edate='';

function addScheduledProgram(programId){
	if(scheduledPrograms != ''){
		scheduledPrograms = scheduledPrograms +','+programId;
	}
	else{
		scheduledPrograms = programId;
	}
}


function addSkedules(){
	displayNetworkGrid.submit();
}


function closeObject(divId) {
	divId = document.getElementById(divId);
	divId.style.visibility = 'hidden';
}


function deleteSchedules(programId){
	if(scheduledPrograms.search(programId) != -1){
		if(document.getElementById('chk'+programId).checked == true){
			schedulesToDelete = schedulesToDelete.replace(programId,'');
		}
		if(document.getElementById('chk'+programId).checked == false){
			if(schedulesToDelete.length < 1){
			schedulesToDelete = String(programId);
			}
			else{
			schedulesToDelete = schedulesToDelete + ',' +String(programId);						
			}

		}

	}
}					
			

function saveThisSked(skeduleId, selectedItemId, tileId){
	
	//the program is not in the Proposal
	if(scheduledPrograms.search(tileId) == -1 || schedulesToDelete != ''){ 
		
		if(skedulesList.search(skeduleId) == -1){
			skedulesList = skedulesList + ',' + skeduleId;				
		}
		else{
			skedulesList = skedulesList.replace(","+skeduleId,'');
		}
		
		document.displayNetworkGrid.skedules.value=skedulesList;
		
		//store checkbox ids to make them hidden
		if(boxItems.search(selectedItemId) == -1){
			boxItems = boxItems + ',' + selectedItemId;	
		}
		else{
			boxItems = boxItems.replace(","+selectedItemId,'');
		}

		document.displayNetworkGrid.selectedItems.value=boxItems;

	}

	
	if (tdIds.search(tileId) == -1){
		if(tdIds != '' ){
			tdIds = tdIds + ',' + tileId;
		}
		else{
			tdIds = tileId;
		}
	}
	else{
		tdIds = tdIds.replace(tileId,'');
		tdIds = tdIds.replace(",,",',').replace(" ,",',');						
	}		
			
	document.displayNetworkGrid.selectedTiles.value=tdIds;				
	
		
	if(itemsTmp.search(selectedTab+selectedItemId) == -1 && document.getElementById(selectedItemId).checked == true){
		itemsTmp = itemsTmp + ',' + selectedTab + selectedItemId;
	}
	else{
		itemsTmp = itemsTmp.replace(","+selectedTab + selectedItemId,'');
	}

}

			
// Show/Hide functions for pointer objects
function showObject(divId) {
	divId = document.getElementById(divId);
	divId.style.visibility = 'visible';
	}
						
function updateBackground(Id,numberOfTabs){
	$('#'+Id).css('background-image','url(images/wk2.gif)');
	var selectedTab = Id;
	for(var i=0; i< numberOfTabs; i++){
		if('wkNavigation'+i != Id)
		$('#wkNavigation'+i).css('background-image','url(images/wk.gif)');
	}
}

function selectFirstTab(){
	$('#wkNavigation0').css('background-image','url(images/wk2.gif)');			
}

function setSelectedWeek(referenceWeek){
	selectedWeek = referenceWeek;
}

function changeTabColor(items, tabId){
	var itmsSelected;
	
	itmsSelected = itemsTmp;

	var selectedItems = itmsSelected.substr(1,itmsSelected.length);
	
	var itemArray = selectedItems.split(",");
	var tabNum = '';
	
	for(var i=0; i<itemArray.length; i++){
		if(tabNum.indexOf(itemArray[i].substr(0,1)) == -1){
			tabNum = tabNum + itemArray[i].substr(0,1);
		}
	}																					
	
	for(var j=0; j<9; j++){
		if(tabNum.search(j) >= 0){
			if(selectedTab != j){
				document.getElementById('wkNavigation'+j).style.backgroundImage= "url(../images/wk3.gif)";
			}
			else{
				document.getElementById('wkNavigation'+j).style.backgroundImage= "url(../images/wk2.gif)";						
			}
		}
	}
}
			
			
function closeEz(){
	 window.close();	
}


function downloadExcel(){
	newwindow=window.open('gridSelector.cfm?t=excel&sDate='+selectedWeek,'Download_Window','height=99,width=165,resizable=0,left=500,top=330');
}

function downloadPdf(){
	newwindow=window.open('gridSelector.cfm?t=pdf&sDate='+selectedWeek,'Download_Window','height=99,width=165,resizable=0,left=500,top=330');			
}
				

function dateCompare(sDate, eDate) {
    var str1 	= sDate;
    var str2 	= eDate;
    var mon1  	= parseInt(str1.substring(0,2),10);
    var dt1 	= parseInt(str1.substring(3,5),10);
    var yr1  	= parseInt(str1.substring(6,10),10); 
    var mon2  	= parseInt(str2.substring(0,2),10);
    var dt2 	= parseInt(str2.substring(3,5),10);
    var yr2  	= parseInt(str2.substring(6,10),10);
    var date1 	= new Date(yr1, mon1, dt1); 
    var date2 	= new Date(yr2, mon2, dt2); 

    if(date2 < date1){
        return false; 
    } 
    else{ 
        return true; 
    }
} 			
			
			
			
function validateGRange(sTime, eTime){
	if(eTime.substring(0,2) - sTime.substring(0,2) <= 8 && eTime.substring(0,2) - sTime.substring(0,2) >=0){
		var minutes = (eTime.substring(3,5) - sTime.substring(3,5))/60;
		var hours = (eTime.substring(0,2) - sTime.substring(0,2)) + minutes;
		if(8.0 - hours >= 0){
			return true;
		} 
		else{
			return false;
		}
	}
	else{
		return false;
	}
	
}				


function proposalChecker(skeduleid, pgmId, pgmkey){
	if(document.getElementById('proposalList').value != 0){
		saveThisSked(skeduleid, pgmId, pgmkey);
	}
	else{
		document.getElementById('chk'+pgmkey).checked = false;					
		alert("Please select or create a Proposal before choosing shows")
	}
}

			
	
	
//SYNCHING SELECTED PROPOSAL FROM SHOWSEEKER +
function setSelectedProposal(){}
		
		
// SENDING SHOWSEEKER + REQUEST TO UPDATE THE SELECTED PROPOSAL
function selectedProposal(){
	var proposalid = $('#proposalList').val();
	
	if(proposalid == 0){
		return;
	}
};
	
		
		
function proposalChecker2(row,checkid){
	var val = $('#'+checkid).attr('checked');
	if(val == 'checked'){
		addLineToProposal(row);
	}else{
		var zoneid = $("#zones").val();
		var id = row.ssid+"-"+zoneid;
		window.opener.removeCheckedLine(id);
	}
}
		
		
		
//ADDING SPOTS TO THE PROPOSAL
function addLineToProposal(data){
	var zone = $('#zones option:selected').text();
	var zoneid = $("#zones").val();
	
	var a = data.stationnum+data.title+data.starttime24+data.endtime24;
	var b = a.replace(/[^a-z0-9]/gi,'');
	
	var title = decodeURIComponent(data.title);
	var desc = decodeURIComponent(data.desc);
	var epititle = decodeURIComponent(data.epititle);

	
	var row = new Object();
	//basic varibles
	row.id = data.ssid+"-"+zoneid;
	row.ssid = data.ssid;
    row.zone = zone,
    row.zoneid =  zoneid,
    row.linetype = 'Fixed',
    row.split = 0,
    row.title = title,
    row.startdate = data.startdate,
    row.enddate = data.enddate,
    row.starttime = data.starttime,
    row.endtime = data.endtime,
    row.startdatetime = data.formatStartDateTime,
    row.enddatetime = data.formatEndDateTime,
    row.desc = desc,
    row.epititle = epititle,
    row.live = data.live,
    row.genre = data.genre,
	row.premiere = data.premierefinale,
    row.isnew =  data.isnew,
    row.stars = '',
    row.day = data.day,
    row.stationnum = data.stationnum,
    row.callsign = data.callsign,
    row.programid = data.tmsid,
   	row.search = '',
   	
   	//scheduler features
   	row.locked = false,
   	row.ratecardid = 0,
   	row.rate = 0,
   	row.ratevalue = '',
   	row,ratename = '',
   	row.weeks = 1,
   	row.spotsweek = 1,
   	row.spots = 1,
   	row.weekdays = 0,
   	row.ncc = '',
   	row.avail = b,
   	row.broadcastweek = '',
   	row.timestamp = new Date(),
   	row.total = 0,
   	row.split = 0,
   	
   	//formatters
    row.titleFormat = title + "|" + epititle,
    row.dayFormat = setDayofWeek(data.day),
    row.titlenetworkFormat = data.callsign + " - " + title,
    row.callsignFormat = data.callsign + "|" + data.stationname,
    row.statusFormat = data.premierefinale + "|" + data.live + "|" + data.isnew,
    row.zonetitle = zone + " - " + title,
    row.zonenetwork = zone + " - " + data.stationname,
    row.networktitle = data.callsign + " - " + title,
    row.zonenetworktitle = zone + " - " + data.callsign + " - " + title,
	row.lineactive = 1,
	row.premiereFormat = data.premierefinale
	
	
	var rows = [row];
}
			
			
			

function setDayofWeek(value){
	switch (value){
		case '1':
  			return "SUN";
		case '2':
			return "MON";
		case '3':
			return "TUE";
		case '4':
			return "WED";
		case '5':
			return "THU";
		case '6':
			return "FRI";
		case '7':
			return "SAT";
	} 
}
			
			
			

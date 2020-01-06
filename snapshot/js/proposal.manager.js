/* Set the proposal varibles for this page */
var eventrows 			= [];
var isloading 			= false;
var cntCloneProposal 	= 0;
var proposalCount 		= 0;
var proposalHolder 		= [];
var paramcallAfterProposalCreate = null;
var callAfterProposalCreate      = null;


/* Proposal Clear */
function clearProposal(){
	proposalid 			= 0;
	needSaving 			= false;
	datagridProposal.emptyGrid();
	$(".label-proposal-name,#snapShotFileName").html('No SnapShot Loaded');
};


/* Proposal List *Updated 10/15/2014 */
function getUserProposals() {
	$.ajax({
		type:'get',
		url: apiUrl+"snapshot/list",
		dataType:"json",
		headers:{"Api-Key":apiKey,"User":userid},
		success:function(data){
			var reData = [];
			var t;
			$.each(data,function(i,row){
				t        		= {};
				t.id        	= row.id;
				t.name       	= row.name;
				t.zone       	= row.zones;
				t.events   	 	= row.events;
				t.fstart     	= row.startDate;
				t.fend       	= row.endDate;
				t.created    	= row.createdAt;
				t.updatedat  	= row.updatedAt;
				reData.push(t);
			});
			populateDownloadList(reData);
			datagridProposalManager.populateDataGrid(reData);
			datagridProposalManager.setSelectRow(proposalid);

			$('#fullwrapper').css('visibility', 'visible');
		}
	});
};


/* MAKE THE SERVER CALL AND POPULATE THE PROPOSAL *Updated 10/15/2014*/
function loadProposalFromServer(id,loc) {	
	if(id == null){
		return;
	}	
	
	proposalid = parseInt(String(id).trim());
	
	$('#panel1,#panel3').css('display', 'none');
	
	builderpanel['panel3'] = builderpanel['panel1'] = false;

	if(builderpanel['panel2'] == false){
		setPanel('panel2');
	}

	closeAllDialogs();
	loadDialogWindow('loading', 'ShowSeeker SnapShot', 450, 180, 1);

	$.ajax({
		type:'get',
		url: apiUrl+"snapshot/load/"+proposalid,
		dataType:"json",
		headers:{"Api-Key":apiKey,"User":userid},
		success:function(data){
			closeAllDialogs();
			
			if(data.snapshot){
				
				resetmini();
				var jdata = [];
				var t,dataweeks,dataset,wdata;

				$.each(data.snapshot,function(i,l){
					t       			= $.extend({}, l);
    				t['_dirty'] 		= true;					
					t.callsign      	= l.callsign;
					t.callsignFormat 	= l.callsign+"|"; //TODO_ASIF
					t.day     			= l.day;
					t.dayFormat     	= formatterDayOfWeek(String(l.day));
					t.desc      		= l.descembed;					
					t.enddate       	= l.tz_end;
					t.enddatetime   	= l.tz_end;
					t.endtime       	= String(l.tz_end.split('T')[1]).substr(0, 8);
					t.epititle  		= l.epititle;
					t.genre     		= l.genre1;
					t.id        		= l.id;
					t.isnew     		= l.isnew;
					t.programid 		= l.showid;
					t.showid    		= l.showid;					
					t.startdatetime 	= l.tz_start;
					t.starttime     	= String(l.tz_start.split('T')[1]).substr(0, 8);
					t.startdate     	= l.tz_start;
					t.statusFormat  	= l.live || l.premierefinale || l.isnew;
					t.stationname    	= l.callsign; //TODO_ASIF
					t.stationnum    	= l.stationnum;
					t.spots			 	= 1;
					t.spotsweek 	 	= 1;
					t.titleFormat   	= l.title+"|"+l.epititle;
					t.total  		 	= 0;
					t.weekIdMapping  	= {};
					t.weeks 		 	= 1;
					t.zone          	= l.zoneName;
					t.zoneid       		= l.zoneId;
					jdata.push(t);			
				});

				
				$(".label-proposal-name").html(data.name);
				$("#snapShotFileName").text(data.name);
				$("#download-proposal-list").val(proposalid).change();


				if(jdata.length == 0){
	
					datagridProposal.emptyGrid();
					datagridProposal.buildEmptyGrid();
					$("#dialog-window").dialog("close");
					if(loc != 'download'){
						menuSelect('proposal-build');
					}
					//ezgridsSynch(id);
	
					return;
				}

				datagridProposal.emptyGrid();
				
				dataset 	= datagridProposal.setDataSet(jdata);
				datagridProposal.buildSimpleGrid(jdata);
				datagridProposal.populateDataGridNew(jdata);	
				//flightLabel();
				needSaving = false;
				$("#dialog-window").dialog("close");
	
				if(loc != 'download'){
					menuSelect('proposal-build');
				}
				
				if(jdata[0].hasOwnProperty('zoneid')){
					//autoSelectMarketAndZone(jdata[0].zoneid);
				}
				
				//ezgridsSynch(id);
			}
			else{
				datagridProposal.emptyGrid();
				datagridProposal.buildEmptyGrid();
				$("#dialog-window").dialog("close");
				menuSelect('proposal-build');
				//ezgridsSynch(id);
				return;
			}
		}
	});
}






/* Proposal Clone To New  *Updated 10/15/2014 */
function proposalClonetoNew(srows,zones,clonetype){
	//set the zone length
	var ttl = zones.length;

	//if the all Select zones is selected return
	if(zones[0] == 0){
	    return;
	}   

	//set the rows as srows
	var rows = jQuery.parseJSON(srows);
    var url = "/services/1.0/ratecard.php";
    
    $.when(buildToken(url)).done(function(token){
    	url = token['url']+"&zoneid=" + zones[cntCloneProposal] + "&type=" + rateCardMode + "&startdate=" + ratecardDate + "&cardid=" + rateCardID + "&group=" + ratecardGroup;

		//grab the ratecard data
		$.ajax({  
		    url: url,  
		    dataType: 'json',  
		    async: false,  
		    success: function(xratedata){
				//get the networks too see if available in this zone
				var url = '/services/1.0/network.list.php';
				$.when(buildToken(url)).done(function(token){
					url = token['url']+"&zoneid="+zones[cntCloneProposal];

					$.ajax({  
    					url: url,  
    					dataType: 'json', 
    					async: false,  
    					success: function(xdata){
    						var netlist = xdata.response.networks;
        					var zid = xdata.responseHeader.zoneid;
        					var zname = xdata.responseHeader.zonename;
    						
    						$.each(rows, function(i, row){
								try{
						            //get the station id
						            var rowstation = parseInt(row.stationnum);
	
						            //see if this station is avaiable
						            var avail = findStationForDupe(netlist,rowstation);
						            
						            //start avail process
						            if(avail != 0){
						                var temprow = jQuery.extend({}, row);
						                temprow.id = GUID()+"-"+zid;
						                temprow.zone = zname;
						                temprow.zoneid =  zid;
						                temprow.zonetitle = zname + " - " + temprow.title;
						                temprow.zonenetwork = zname + " - " + temprow.callsign;
						                temprow.zonenetworktitle = zname + " - " + temprow.callsign + " - " + temprow.title;
						                temprow.sortFormat =   sortbuilder(zname,temprow.startdatetime,temprow.callsign);
										temprow.zonetitleFormat = zname + ' - ' + temprow.title;
										temprow.zonetitletimeFormat = zname + ' - ' + temprow.title + ' - ' + temprow.starttime;
										temprow.showLine = temprow.callsign + ' - ' + temprow.title + ' - ' + temprow.starttime + ' - ' + temprow.endtime;
										temprow.hot =  false;
						                temprow.search =  "";
	
						                var rate = ratecardType(rateType,temprow,xratedata.response,xratedata.hotprograms);
										temprow.ratevalue = rate;
	
										temprow.rate = temprow.rate;
										temprow.total = temprow.total;
						                proposalHolder.push(temprow);
						            }
						        }catch(e){}
							});//end for each

					        //add one to count
					        cntCloneProposal++;

					        if(ttl==cntCloneProposal){
					            cntCloneProposal = 0;

					            //get the clone typow and see what lines to show
					            if(clonetype == 'both'){
					            	var d = proposalCloneProcessLines(proposalHolder);
					            }else{
					            	var d = proposalHolder;
					            }
					            proposalHolder = [];
					            proposalCloneSave(d);
					        }else{
					            proposalClonetoNew(srows,zones);
					        }
    					}});//end ajax
				});//end ajax network list
		}});
	});
};



/* Proposal Save the Clone  *Updated 10/15/2014 */
function proposalCloneSave(d){
    //parse lines to json
    var json = JSON.stringify(d);
    //set weeks blank
    var dataweeks = '[]';
    //name the proposal
    var name = $("#rename-proposal2").val();
    var url = "/services/1.0/proposal.create.php";

    $.when(buildToken(url)).done(function(token){
        tokenid = token['key'];
        userid = token['userid'];
    	//post the proposal to the server
		$.post(url, {
			proposal: json,
			weeks:  dataweeks,
			name:   name,
			userid: userid,
			tokenid: tokenid,
			calendar:1
		}, function(data) {

			$("#dialog-window").dialog("destroy");
			//load the proposal
			var jdata = jQuery.parseJSON(data);
			r = loadProposalFromServer(jdata.id,'save');
			r = getUserProposals();
		});
	});
};



/* Proposal Save the Clone  *Updated 10/15/2014 */
function proposalCloneProcessLines(rows){
	var flight = $("#flight2").attr("checked");
	var params = solrSearchParamaters();
	var myrows = [];
	$.each(rows, function(i, row){
		if(row.linetype == 'Fixed'){
			var temp = proposalCloneProcessLineFixed(row,params.startdate,params.enddate);
			if(temp){
				myrows.push(temp);
			}
		}
		if(row.linetype == 'Rotator'){
			var temp = proposalCloneProcessLineRotator(row,params.startdate,params.enddate);
			myrows.push(temp);
		}
		if(row.linetype == 'Line'){
			var temp = proposalCloneProcessLineLine(row,params.startdate,params.enddate);
			myrows.push(temp);
		}
	});
	return myrows;
};




/* Proposal Clone Processed Lines */
function proposalCloneProcessLineFixed(row,start,end){	
	var rowstart = new Date(row.startdate).toString("yyyyMMdd");
	var flightstart = new Date(start).toString("yyyyMMdd");

	if(flightstart > rowstart){
		return false;
	}
	return row;
};




/* Proposal Copy Event  *Updated 10/15/2014 */
function proposalCopyChecked2(){
	var zones   = $("#clone-zones").attr("checked");
	var flight  = $("#flight2").attr("checked");
	var newname = $("#rename-proposal2").val();
	eventrows   = datagridProposalManager.getSelectedRows(); //get teh selected id
	var data    = {"proposalId":eventrows[0].id,"proposalName":newname};
	
	if(flight == 'checked'){
		var params     = solrSearchParamaters();
		data.startDate = params.startdate.split("T")[0];
		data.endDate   = params.enddate.split("T")[0];		
	} else {
		data.startDate = eventrows[0].fstart.split(" ")[0];
		data.endDate   = eventrows[0].fend.split(" ")[0];	
	}

	if(zones == 'checked'){
		data.zones = $("#clone-zone-selector").val();;	
	}

	$.ajax({
        type:'post',
        url: apiUrl+"proposal/copy",
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        processData: false,
        contentType: 'application/json',
        data: JSON.stringify(data),
        success:function(resp){
            loadProposalFromServer(resp.proposalId,"");
        }
    });

};




/* Proposal Create Event */
function proposalCreateNewEvent(){
	var name = String($('#proposal-save-name-input').val()).trim();
	
	if(name.length == 0)
		return

	if(name.length > 75){
		if($('#temp-psl-msg').length == 0){
			$('#saveerror').append('<center id=temp-psl-msg><BR><b>Please limit the SnapShot name to 75 characters or less.</b></center>');
			setTimeout(function(){$('#temp-psl-msg').empty().remove()},3500);
		}
		return;
	}
	proposalCreateNew(name,true);
};



/* Create Proposal  *Updated 10/15/2014 */
function proposalCreateNew(name,isnew) {
	var createnewpopup = $('#dialog-window');

	if(createnewpopup.length > 0){
		$('#proposal-save-name,#discard-save-btn').hide();
		$('#dialog-window #div-center').append('<center><img src="i/ajax.gif"></center>');
	}

	if(!isBlank(String(name).trim())){
		loadDialogWindow('blankproposalname', 'ShowSeeker SnapShot', 450, 180, 1, 0);
		$("#proposal-name").val("");
		return;
	}

	if( String(name).length > 75 ){
		loadDialogWindow('longproposalname', 'ShowSeeker SnapShot', 450, 180, 1, 0);
		return;
	}
	
	

	$("#proposal-name").val("");
	$(".label-proposal-name,#snapShotFileName").html(name);

	$.ajax({
		type:'post',
		url: apiUrl+"snapshot/new",
		dataType:"json",
		headers:{"Api-Key":apiKey,"User":userid},
		processData: false,
		contentType: 'application/json',
		data: JSON.stringify({"name":name}),
		success:function(resp){
            try{
			    usrIp("SnapShot - Proposal Create",{"proposalName":name,"proposalId":resp.snapshotId});
            }catch(e){}  
			closeAllDialogs();
			proposalid = parseInt(resp.snapshotId);
			$("#dialog-window").dialog("close");
			if(callAfterProposalCreate !== null){
				addFixedLinesToProposal(paramcallAfterProposalCreate);
				callAfterProposalCreate      = null;
				paramcallAfterProposalCreate = null;
			}
			else{
				loadProposalFromServer(proposalid,'');
			}
		}
	});
};




/* Proposal Delete *Updated 10/16/2014 */
function proposalDeleteChecked(){
	var proposalIds = [];
	eventrows       = datagridProposalManager.getSelectedRows();

	$.each(eventrows, function(index, value) {
		proposalIds.push(value.id);
		if(parseInt(proposalid) === parseInt(value.id)){
			clearProposal()
		}
	});

	$.ajax({
        type:'post',
        url: apiUrl+"snapshot/delete",
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        processData: false,
        contentType: 'application/json',
        data: JSON.stringify({"snapshotId":proposalIds}),
        success:function(resp){
            getUserProposals();
        }
    });
    
};



/* Proposal Delete Confermation */
function proposalDeleteCheckedConfirmation(){
	var rows = [];
	eventrows = datagridProposalManager.getSelectedRows();

	if(eventrows.length == 0) {
		loadDialogWindow('leastone', 'ShowSeeker SnapShot', 450, 180, 1);
		return;
	}	
	loadDialogWindow('proposaldelete', 'ShowSeeker SnapShot', 450, 180, 1);
}






/* Proposal Download List */
function populateDownloadList(data){
	$('#download-proposal-list').empty();
	var pslId = parseInt(proposalid);
	for(var i = 0; i < data.length; i++) {
		var x  = Date.parse(data[i].created).toString("MM/dd/yyyy");
		if(pslId != parseInt(data[i].id))
			$('#download-proposal-list').append($("<option></option>").attr("value", data[i].id).text(data[i].name + ' - ' + x));
		else
			$('#download-proposal-list').append($("<option selected=selected></option>").attr("value", data[i].id).text(data[i].name + ' - ' + x));
	}
	$('#download-proposal-list').trigger('create');
}




/* Proposal Merged Checked *Updated 10/16/2014 */
function proposalMergeChecked(){
	eventrows = datagridProposalManager.getSelectedRows();
	var data = {"proposalName":$('#proposal-merge-name').val(),"proposalIds":[]};

	if(eventrows.length < 2){
		loadDialogWindow('mergeamount', 'ShowSeeker SnapShot', 450, 180, 1);
		return;
	}

	$.each(eventrows, function(index, value) {
		data.proposalIds.push(value.id);
	});

	$.ajax({
        type:'post',
        url: apiUrl+"proposal/merge",
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        processData: false,
        contentType: 'application/json',
        data: JSON.stringify(data),
        success:function(resp){
            loadProposalFromServer(resp.proposalId,"");
        }
    });

}



/* Proposal Rename Event Checked */
function proposalRenameChecked() {
	eventrows = datagridProposalManager.getSelectedRows();
	if(eventrows.length > 1 || eventrows.length == 0){
		loadDialogWindow('onlyone', 'ShowSeeker SnapShot', 450, 180, 1);
		return;
	}
	$("#proposal-rename").val(eventrows[0].name);
	loadDialogWindow('proposal-rename', 'Rename SnapShot', 380, 150, 0);
}





/* Proposal Share 10/20/2014*/
function proposalShare(type){
	closeAllDialogs();
	proposalShareType = type;

	var rows = [];
	eventrows = datagridProposalManager.getSelectedRows();

	if(eventrows.length == 0) {
		loadDialogWindow('onlyone', 'ShowSeeker SnapShot', 450, 180, 1);
		return;
	}
	//loadDialogWindow('share', 'Share Proposals', 600, 680, 2);
	loadDialogWindow('share', 'Share Proposals', 600, 600, 2, true,'','',[300, 15]);
}



/* Proposal Rename  *Updated 10/15/2014 */
function renameCheckedProposalEvent(){
	var name = $.trim($("#proposal-rename").val());

	if(!isBlank(name)){
		loadDialogWindow('blankproposalname', 'ShowSeeker SnapShot', 450, 180, 1, 0);
		$("#proposal-name").val("");
		return;
	}

	if( String(name).length > 75 ){
		loadDialogWindow('longproposalname', 'ShowSeeker SnapShot', 450, 180, 1, 0);
		return;
	}

	$('#label-proposal-name').text(name);

	$.ajax({
        type:'post',
        url: apiUrl+"snapshot/rename/"+eventrows[0].id,
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        processData: false,
        contentType: 'application/json',
        data: JSON.stringify({"name":$.trim($("#proposal-rename").val())}),
        success:function(resp){
            getUserProposals();
            $("#dialog-window").dialog("destroy");
        }
    });



}

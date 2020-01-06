var dropcount = 0;
var linescount = 0;
var groupcounter = 0;
var tempDataGridDataSet = [];

function bindProposalDatagrid(){
	$("#proposal-build-grid").unbind('dropstart');
	$("#proposal-build-grid").unbind('drop');

	$("#proposal-build-grid").bind("dropstart", function (e, dd) {
		if(dd.mode !== "ssresults") {
			return;
		}
		
	}).bind("drop", function (e, dd) {
		if(dd.mode !== "ssresults") {
			return;
		}
		
		loadDialogWindow('addlines','Creating Lines',400,200,1);		

		dropcount = 0;
		var rows  = datagridSearchResults.selectedRows();
		var type  = datagridSearchResults.getGroupByColumn(); //get group tyoes to see if it is a avail

		setTimeout(function(){
			processDropLines(rows,type);
			needSaving=true;			
		
		},500);
	});
}


function addRotatorsFromFixedGrouping(){
	//loadDialogWindow('addlines','Creating Lines',400,200,1);		
	dialogCreatingAvails();
	dropcount = 0;
	var rows  = datagridSearchResults.selectedRows();
	var type  = datagridSearchResults.getGroupByColumn(); //get group tyoes to see if it is a avail
	processDropLines(rows,type);
	needSaving=true;			
}




function processDropLines(rows,type){

	$("#avail_total").html(rows.length);

	var availtype = datagridSearchResults.getGroupByColumn();
	
	if(dropcount < rows.length){

		// add lines fixed position mode
		if(type == 'off'){
			addFixedLinesToProposal(rows);
		}

		//add lines group mode
		if(type == 'showLine' && typeof rows[dropcount].__group == 'undefined'){
			addFixedLinesToProposal(rows);
		}

		//add lines single mode and grouped mode
		if(type == 'showLine' &&  ('__group' in rows[dropcount])){

			if(proposalid < 1){
				saveProposal();
				callAfterProposalCreate = addRotatorsFromFixedGrouping;	
				paramcallAfterProposalCreate = close;
				return;
			} 			

			processGroupLines2(rows,zoneid,zone,type,availtype);
		}

		// add avails day
		if(type == 'availsShow' || type == 'availsDay'){
			processAvailLines2([rows[dropcount]],zoneid,zone,type,availtype);
		}

		$("#avail_current").html(dropcount+1);
		dropcount ++ ;

	}
	else{
		$("#dialog-window").dialog("close");
		
		datagridProposal.populateDataGrid();
		datagridProposal.buildGrid();
		datagridTotals.populateDataGrid(datagridProposal.dataSet());

		if(proposalid == 0){
			saveProposal();
    	}
	}

	if(datagridProposal.isRowInHiddenWeek() == true){
		loadMessage('hiddenweeks');
	}
}


function addSelectionToProposal(rows,zoneid,zone,type){	

	for(var i = 0; i < rows.length; i++) {

		//if this is a group please find all the shows in the group
		if (typeof rows[i].__group == 'undefined') {
			datagridProposal.addRowToProposal(rows[i]);
		}else{
			var group = rows[i].rows;
			for(var i = 0; i < group.length; i++) {
				datagridProposal.addRowToProposal(group[i]);
			}
			
		}
	}


	
	datagridProposal.populateDataGrid();
	datagridProposal.buildGrid();
	datagridTotals.populateDataGrid(datagridProposal.dataSet());

	if(proposalid == 0){
    	saveProposal();
    }
}


function unbindProposalDatagrid() {
	//$("#proposal-build-grid").unbind('dropstart');
	//$("#proposal-build-grid").unbind('drop');
}


function addFixedLinesToProposal(rows, callBack){
	if(proposalid > 0){
		
		var linesToBeSent 	= [];
		var episodes 		= [];
		var endTimeArr, group, pslLline, startTimeArr, tmpStartDate, tmpEndDate;
		var i,j;
		
		if(callBack === undefined){
			$("#avail_current").html('1');		
			$("#avail_total").html(String(rows.length));
		}
		
		if(rows.length >= 1000){
			lineLimit = 100;
		}
		else if(rows.length >= 500 && rows.length < 1000){
			lineLimit = 75;			
		}
		else if(rows.length >= 100 && rows.length < 500){
			lineLimit = 50;			
		}
		
		$.each(rows,function(indx,row){
			
			//if this is a group please find all the shows in the group
			if (!("__group" in row)){

				startTimeArr = row.startdatetime.split(/[^0-9]/);
				endTimeArr   = row.enddatetime.split(/[^0-9]/);
				tmpStartDate = new Date(startTimeArr[0],startTimeArr[1]-1,startTimeArr[2],startTimeArr[3],startTimeArr[4]);
				tmpEndDate	 = new Date(endTimeArr[0],endTimeArr[1]-1,endTimeArr[2],endTimeArr[3],endTimeArr[4]);
				
				if(tmpStartDate.toString("yyyyMMdd") === tmpEndDate.toString("yyyyMMdd") || userSettings.autoSplitLines === false){
					pslLline = formatLineToAdd(row,tmpStartDate.toString("yyyy-MM-dd HH:mm:ss"),tmpEndDate.toString("yyyy-MM-dd HH:mm:ss"));
					episodes.push(pslLline);
				}
				else{
					pslLline = autoSplitLines(row,tmpStartDate,tmpEndDate)
					for(j = 0; j < pslLline.length; j++){
						episodes.push(pslLline[j]);	
					}
				}

			}
			else{	
				group = row.rows;
				
				for(i = 0; i < group.length; i++) {
					startTimeArr = group[i].startdatetime.split(/[^0-9]/);
					endTimeArr   = group[i].enddatetime.split(/[^0-9]/);
					tmpStartDate = new Date(startTimeArr[0],startTimeArr[1]-1,startTimeArr[2],startTimeArr[3],startTimeArr[4]);
					tmpEndDate	 = new Date(endTimeArr[0],endTimeArr[1]-1,endTimeArr[2],endTimeArr[3],endTimeArr[4]);

					if(tmpStartDate.toString("yyyyMMdd") === tmpEndDate.toString("yyyyMMdd") || userSettings.autoSplitLines === false){
						pslLline = formatLineToAdd(group[i],tmpStartDate.toString("yyyy-MM-dd HH:mm:ss"),tmpEndDate.toString("yyyy-MM-dd HH:mm:ss"));
						episodes.push(pslLline);
					}
					else{
						pslLline = autoSplitLines(group[i],tmpStartDate,tmpEndDate)
						for(j = 0; j < pslLline.length; j++){
							episodes.push(pslLline[j]);	
						}
					}
				}		
			}	
			
			if(indx % lineLimit === 0 && indx > 0){
				linesToBeSent.push(episodes);
				episodes = [];
			}
		});
		
		
		if(episodes.length > 0){
			refreshGrid = true;
			linesToBeSent.push(episodes);
		}

		saveDataInServer(linesToBeSent,rows.length,callBack);
		
	} else {
		saveProposal();
		callAfterProposalCreate = addFixedLinesToProposal;
		paramcallAfterProposalCreate = rows;
	}
}



function saveDataInServer(episodesArray,totalLines, callBack){
	var data 			= {};
	var hiddenWeeksArray= [];
	data.proposalId 	= proposalid;
	data.zoneId 		= zoneid;
	data.spots 			= 1;
	data.rate 			= 0;
	data.episodes		= [];
	data.autoSplitLines = false;
	data.sendNewLines 	= true
	data.ratecardId 	= solrSearchParamaters().ratecardId;
	data.ratingsSettings=setProposalRatings();
	refreshGrid			= false;
	data.episodes 		= episodesArray[groupcounter];
	//datagridProposal.unselectAllRows();
	
	
	if(weeksdata.length > 0){
		var tmpD;
		for(var k = 0; k < weeksdata.length; k++){
			tmpD = String(weeksdata[k]).substr(1, 8);
			hiddenWeeksArray.push(tmpD.substr(4,4) + '-' + tmpD.substr(0,2) + '-' + tmpD.substr(2,2));
		}
		data.inactiveWeeks = hiddenWeeksArray;
	}		
		
		$.ajax({
				type:'post',
				url: apiUrl+"proposal/addlinesplus/fixed",
				dataType:"json",
				headers:{"Api-Key":apiKey,"User":userid},
				processData: false,
				contentType: 'application/json',
				data: JSON.stringify(data),
				error:function(){
					closeAllDialogs();
					loadDialogWindow('addLinesError','Creating Lines',400,200,1);
				},
				success:function(resp){
					proposalRattingsOn = resp.ratings;
					var lineDemo;
					groupcounter++;
					if(resp.lines.length>0){
						var t;
						$.each(resp.lines,function(i,l){
							linescount ++;								
							t  = formatProposalLine(l);
							tempDataGridDataSet.push(t);
			            });
			            
			            if(callBack === undefined){
			            	if(totalLines >= linescount){
				            	$("#avail_current").html(String(linescount));
			            	}
			        	}
							
			            if(groupcounter < episodesArray.length){
							saveDataInServer(episodesArray,totalLines,callBack);
				        }
				        else{
							datagridProposal.buildEmptyGrid();
							$.when(datagridProposal.populateProposalDataGrid2(tempDataGridDataSet)).then(function(){
								
								var thisProposalLines = datagridProposal.dataSet();
								datagridProposalManager.updateSelectedProposalRow(resp);										
								datagridTotals.populateDataGridGoPlus(thisProposalLines,resp.dates.startDate,resp.dates.endDate,resp.totals);	
								linescount			= 0;
								groupcounter		= 0;
								tempDataGridDataSet = [];

								if(callBack === undefined){
									closeAllDialogs();
								} else {
									callBack();
								}
								datagridProposal.unselectAllRows();
									
							});	
						}
					}
					else if(resp.lines.length === 0 && groupcounter >= episodesArray.length){//NO LINES WERE ADDED
						linescount=0;
						groupcounter=0;
						closeAllDialogs();
						tempDataGridDataSet = [];
						datagridProposal.unselectAllRows();
					}
				}		
			});
}




function autoSplitLines(row,lineStartDate,lineEndDate){

    var enddatetime,enddate,endtime;
	var startdatetime,startdate,starttime;
	var re, rowa, rowb;
	var tmpEDate 		= new Date(lineEndDate.getFullYear(),lineEndDate.getMonth(),lineEndDate.getDate());
    var msSinceMidnight = (lineEndDate - tmpEDate)/60000;

    if(msSinceMidnight < 9){//less than 9 minutes we remove the second part
	    enddatetime 	= lineStartDate.toString("yyyy-MM-dd 23:59:59");
	    rowa 			= formatLineToAdd(row,lineStartDate.toString("yyyy-MM-dd HH:mm:ss"),enddatetime);
        re 				= [];
        re.push(rowa);
        return re;
    }
	

    enddatetime 		= lineStartDate.toString("yyyy-MM-dd 23:59:59");
    rowa 				= formatLineToAdd(row,lineStartDate.toString("yyyy-MM-dd HH:mm:ss"),enddatetime);
    rowa.split 			= 1;
    

   
    if(rowa.packageId === 0){
		rowa.extra = 'Split Line Pt. 1';
    }
    else if(rowa.packageId !== undefined && rowa.packageId !== 0){
        rowa.extra = ['Package','Split Lines Pt. 1'];
    }

    startdatetime 		= lineEndDate.toString("yyyy-MM-dd 00:00:00");
    rowb 				= formatLineToAdd(row,startdatetime,lineEndDate.toString("yyyy-MM-dd HH:mm:ss"));

    if(rowb.day == 7){
        rowb.day = "1";
    }
    else{
        var n = parseInt(rowb.day) + 1;
        rowb.day = n.toString();
    }

    var d = formatterDayOfWeek(rowb.day.toString());
    rowb.dayFormat 		= d;
    rowb.split 			= 1;
    

    if(rowb.packageId === 0){
        rowb.extra = 'Split Line Pt. 2';
    }
	else if(rowb.packageId !== undefined && rowb.packageId !== 0){
	        rowb.extra = ['Package','Split Lines Pt. 2'];
    }

    re = [];
    re.push(rowa);
    re.push(rowb);
    return re;
}


function  formatLineToAdd(row,tmpSDate,tmpEDate){
	var pslLline 			= {};
	var packageId 			= 0;
	pslLline.id				= row.ssid;
	pslLline.tmsid			= row.programid;
	pslLline.stationnum		= row.stationnum;
	pslLline.startDateTime 	= tmpSDate;
	pslLline.endDateTime 	= tmpEDate;
	pslLline.day			= row.day;
	pslLline.rateCardValue	= (ratecard && ratecardZone) ? ratecardType(rateType,row,ratecardData,ratecardHotPrograms) : 0;
	pslLline.live			= row.live;
	pslLline.isnew			= row.isnew;
	pslLline.premiere		= row.premiere;
	pslLline.showid			= row.showid;
	pslLline.title			= row.title;
	pslLline.epititle		= row.epititle;
	pslLline.desc			= row.desc;
	pslLline.genre1			= row.genre;
	pslLline.genre2			= row.genre2;
	pslLline.stars			= row.stars;

	if(('packageId' in row)){
		if(row.packageId){
			packageId = row.packageId;
		}
	}
	pslLline.packageId		= packageId;	

	if(String(row.search) !== ''){
		pslLline.extra	= row.search;
	}
	else if(parseInt(row.projected) === 1){
		pslLline.extra	= "Projected";
	}	
	
	return pslLline;
}


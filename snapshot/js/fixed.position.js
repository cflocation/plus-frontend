var dropcount = 0;

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
		var type  = datagridSearchResults.getGroupByColumn(); //get group types to see if it is a avail

		setTimeout(function(){
			processDropLines(rows,type);
			needSaving=true;			
		
		},500);
	});
}





function processDropLines(rows,type){

	var availtype = datagridSearchResults.getGroupByColumn();

	if(dropcount < rows.length){
		addFixedLinesToProposal(rows);
	}
	else{
		$("#dialog-window").dialog("close");
		datagridProposal.populateDataGrid();
		datagridProposal.buildGrid();

		if(proposalid == 0){
			saveProposal();
    	}
	}
}


function addSelectionToProposal(rows,zoneid,zone,type){	

	for(var i = 0; i < rows.length; i++) {

		//if this is a group please find all the shows in the group
		if (!"__group" in rows[i]) {
			datagridProposal.addRowToProposal(rows[i]);
		}
		else{
			var group = rows[i].rows;
			for(var i = 0; i < group.length; i++) {
				datagridProposal.addRowToProposal(group[i]);
			}
		}
	}

	datagridProposal.populateDataGrid();
	datagridProposal.buildGrid();

	if(proposalid == 0){
    	saveProposal();
    }
}

//EXTERNAL FUNCTIONS
//add the line to the proposal from a external source
function externalAddLineToProposal(id,zone,zoneid){
	var ids = '&fq=';
	var thisid;	
	
	$.each(id, function(i, value){
		thisid 	= 	String(value).split('-');
		ids		+=	'id:'+thisid[0] + '+';
	});

	//setup the url to call to get the show information
	var url = "http://snapshot.prod.showseeker.com:8983/solr/snapshot/select/?q=*%3A*&version=2.2&rows=500&start=0&indent=on&wt=json"+ids;
	url += "&json.wrf=callback";

	url = apiUrl+'proxy/'+ encodeURI(url);
    
    $.ajax({
        type:'get',
        url: url,
        dataType:"jsonp",
        jsonpCallback: 'callback',
        success:function(data){
			  var rows = datasourceBuildGridOld(data.response.docs);
	        if(rows.length > 0){
				var v;
				addFixedLinesToProposal(rows,v,true);	
			  }
			  else{
					externalAddLineToProposalSnapShot(id,zone,zoneid)
			  }
		  
        }
    });
}




function externalAddLineToProposalSnapShot(id,zone,zoneid){
	var ids = '&fq=';
	var thisid;	
		var netId	=	'';
		var showId	=	'';
		var airDate	=	'';
		var airTime	=	'';
		var altKey 	= 	'';


	$.each(id, function(i, value){
		thisid 	= 	String(value).split('-');
				
		netId	=	thisid[0].substr(0,5);//NETWORK ID
		showId	=	thisid[0].substr(5,14);//SHOW ID
		airDate	=	thisid[0].substr(thisid[0].length-8); //DATE
		airDate 		=	airDate.substr(0, 4)+'-'+airDate.substr(4, 2)+'-'+airDate.substr(6, 2);
		airTime	=	thisid[0].substr(19,4); //TIME
		airTime		= 	airTime.substr(0, 2)+':'+airTime.substr(2, 2)+':00';
		altKey = 	showId+netId+airDate+' '+airTime		

		ids		+=	'id:"'+altKey + '"+';
	});

	//setup the url to call to get the show information
	var url = "http://snapshot.prod.showseeker.com:8983/solr/snapshot/select/?q=*%3A*&version=2.2&rows=500&start=0&indent=on&wt=json"+ids;
	url += "&json.wrf=callback";

	url = apiUrl+'proxy/'+ encodeURI(url);
    
    $.ajax({
        type:'get',
        url: url,
        dataType:"jsonp",
        jsonpCallback: 'callback',
        success:function(data){
			  var rows = datasourceBuildGridOld(data.response.docs);
	        if(rows.length > 0){
				var v;
				addFixedLinesToProposal(rows,v,true);	
			  }
		  
        }
    });
}




//New function to add fixed line to a proposalid
//Asif 07/02/2016 - GO API integraton
function addFixedLinesToProposal(rows){
	
	if(proposalid > 0){
		
		var group, pslLline,j,t;	
		var data 		= {};
		data.snapshotId = proposalid;
		data.zoneId 	= zoneid;
		data.tz 		= timezone;
		data.lines		= [];
		var endTz 		= 'tz_end_'+timezone;
		var stTz 		= 'tz_start_'+timezone;
		var dayTz 		= 'day_'+timezone;
		var timeTz 		= 'start_'+timezone;
		var records 	= datagridProposal.getDataSet();
		var uniqueIds  	= [];
		var zoneName	= $('#zone-selector option:selected').text();

		for(j=0;j<records.length;j++){
			uniqueIds.push(records[j].id);	
		}
		
		//if this is a group please find all the shows in the group		
		$.each(rows,function(i,row){

			if(row.epititle === undefined){
				row.epititle = '';
			}		
			
			if (!("__group" in row)) {
				if(uniqueIds.indexOf(row.id) !== -1){
					return;	
				}
				
				pslLline 					= {};
				pslLline.id					= row.id;
				pslLline.tmsid				= row.showid;
				pslLline.callsign			= row.callsign;
				pslLline.stationnum			= row.stationnum;
				pslLline.stationname		= row.stationname;
				pslLline.startDateTime 		= Date.parse(row.startdate +' '+ row.starttime).toString("yyyy-MM-dd HH:mm:ss");
				pslLline.endDateTime		= Date.parse(row.enddate+' '+row.endtime).toString("yyyy-MM-dd HH:mm:ss");
				pslLline[dayTz]				= row.day;
				pslLline.live				= row.live;
				pslLline.isnew				= row.isnew;
				pslLline.premierefinale		= row.premiere;
				pslLline.search				= row.search;
				pslLline.showid				= row.showid;
				pslLline.showtype			= row.showtype;
				pslLline.title				= row.title;
				pslLline.epititle			= row.epititle;
				pslLline.descembed			= row.desc;
				pslLline.duration			= row.duration;
				pslLline.genre1				= row.genre1;
				pslLline.genre2				= row.genre2;
				pslLline.stars				= row.stars;
				pslLline[endTz]				= Date.parse(row.enddate+' '+row.endtime).toString("yyyy-MM-ddTHH:mm:ssZ");
				pslLline[stTz]				= Date.parse(row.startdate +' '+ row.starttime).toString("yyyy-MM-ddTHH:mm:ssZ");
				pslLline[stTz]				= Date.parse(row.startdate +' '+ row.starttime).toString("yyyy-MM-ddTHH:mm:ssZ");
				pslLline[timeTz]			= Date.parse(row.starttime).toString("HH:mm:ss");
				
				data.lines.push(pslLline);
				
				t       					= {};
				t['_dirty'] 				= true;					
				t.callsign      			= row.callsign;
				t.callsignFormat 			= row.callsign+"|"; //TODO_ASIF
				t.day     					= row.day;
				t.dayFormat     			= formatterDayOfWeek(row.day);
				t.desc      				= row.desc;					
				t.enddate       			= Date.parse(row.enddate+' '+row.endtime).toString("yyyy-MM-dd HH:mm:ss");
				t.enddatetime   			= Date.parse(row.enddate +' '+ row.endtime).toString("yyyy-MM-dd HH:mm:ss");
				t.endtime       			= Date.parse(row.endtime).toString("HH:mm:ss");
				t.epititle  				= row.epititle;
				t.genre     				= row.genre1;
				t.id        				= row.id;
				t.isnew     				= row.isnew;
				t.programid 				= row.showid;
				t.search    				= row.search;
				t.showid    				= row.showid;					
				t.startdatetime 			= Date.parse(row.startdate +' '+ row.starttime).toString("yyyy-MM-dd HH:mm:ss");
				t.starttime     			= Date.parse(row.starttime).toString("HH:mm:ss");
				t.startdate     			= Date.parse(row.startdate).toString("yyyy-MM-dd");
				t.statusFormat  			= row.premiere || row.live || row.isnew;
				t.stationname    			= row.stationname; //TODO_ASIF
				t.stationnum    			= row.stationnum;
				t.spots			 			= 1;
				t.spotsweek 	 			= 1;
				t.titleFormat   			= row.title+"|"+row.epititle;
				t.total  		 			= 0;
				t.weekIdMapping  			= {};
				t.weeks 		 			= 1;
				t.zone          			= zoneName;
				t.zoneid       				= zoneid;
				
				datagridProposal.addRowToProposal(t);			

			}
			else{
				group = row.rows;

				for(var i = 0; i < group.length; i++) {

					pslLline 				= {};
					pslLline.id 			= group[i].id;
					pslLline.tmsid 			= group[i].showid;
					pslLline.callsign 		= group[i].callsign;
					pslLline.stationnum 	= group[i].stationnum;
					pslLline.stationname 	= group[i].stationname;
					pslLline.startDateTime 	= Date.parse(group[i].startdate+' '+group[i].starttime).toString("yyyy-MM-dd HH:mm:ss");
					pslLline.endDateTime	= Date.parse(group[i].enddate +  ' '+group[i].endtime).toString("yyyy-MM-dd HH:mm:ss");
					pslLline[dayTz]			= group[i].day;
					pslLline.live  			= group[i].live;
					pslLline.isnew			= group[i].isnew;
					pslLline.premierefinele = group[i].premiere;
					pslLline.search			= group[i].search;
					pslLline.showid			= group[i].showid;
					pslLline.showtype		= group[i].showtype;
					pslLline.title			= group[i].title;
					pslLline.epititle		= group[i].epititle;
					pslLline.descembed		= group[i].desc;
					pslLline.duration		= group[i].duration;
					pslLline.genre1			= group[i].genre;
					pslLline.genre2 		= group[i].genre2;
					pslLline.stars			= group[i].stars;
					pslLline[endTz]			= Date.parse(group[i].enddate +' '+ group[i].endtime).toString("yyyy-MM-ddTHH:mm:ssZ");
					pslLline[stTz]			= Date.parse(group[i].startdate +' '+ group[i].starttime).toString("yyyy-MM-ddTHH:mm:ssZ");
					pslLline[stTz]			= Date.parse(group[i].startdate +' '+ group[i].starttime).toString("yyyy-MM-ddTHH:mm:ssZ");
					pslLline[timeTz]		= Date.parse(group[i].starttime).toString("HH:mm:ss");

					data.lines.push(pslLline);
					
					t       				= {};
					
					t.titleFormat   		= group[i].title;
					if(group[i].epititle !== undefined){
						t.titleFormat+="|"+group[i].epititle;
					}
					
					t['_dirty'] 			= true;					
					t.callsign      		= group[i].callsign;
					t.callsignFormat 		= group[i].callsign +"|"; //TODO_ASIF
					t.day     				= group[i].day;
					t.dayFormat     		= formatterDayOfWeek(group[i].day);
					t.desc      			= group[i].desc;					
					t.enddate       		= Date.parse(group[i].enddate +' '+ group[i].endtime).toString("yyyy-MM-dd HH:mm:ss");
					t.enddatetime   		= Date.parse(group[i].enddate +' '+ group[i].endtime).toString("yyyy-MM-dd HH:mm:ss");
					t.endtime       		= Date.parse(group[i].endtime).toString("HH:mm:ss");
					t.epititle  			= group[i].title;
					t.genre     			= group[i].genre;
					t.id        			= group[i].id;
					t.isnew     			= group[i].isnew;
					t.programid 			= group[i].showid;
					t.search    			= group[i].search;
					t.showid    			= group[i].showid;					
					t.startdatetime 		= Date.parse(group[i].startdate +' '+ group[i].starttime).toString("yyyy-MM-dd HH:mm:ss");
					t.starttime     		= Date.parse(group[i].starttime).toString("HH:mm:ss");
					t.startdate     		= Date.parse(group[i].startdate).toString("yyyy-MM-dd");
					t.statusFormat  		= group[i].premiere || group[i].live || group[i].isnew;
					t.stationname    		= group[i].stationname; //TODO_ASIF
					t.stationnum    		= group[i].stationnum;
					t.spots			 		= 1;
					t.spotsweek 	 		= 1;
					t.total  		 		= 0;
					t.weekIdMapping  		= {};
					t.weeks 		 		= 1;
					t.zone          		= zoneName;
					t.zoneid       			= zoneid;
					
					datagridProposal.addRowToProposal(t);						
				}		
			}		
		});
		
		datagridProposal.populateDataGrid();

		
		$.ajax({
			type:'post',
			url: apiUrl+"snapshot/addlines",
			dataType:"json",
			headers:{"Api-Key":apiKey,"User":userid},
			processData: false,
			contentType: 'application/json',
	    	data: JSON.stringify(data),
			success:function(resp){
				closeAllDialogs();
			}		
		});


	} else {
		saveProposal();
		callAfterProposalCreate = addFixedLinesToProposal;
		paramcallAfterProposalCreate = rows;
	}
}

function externalDeleteLineFromProposal(id,zoneid){
	datagridProposal.deleteLineFromProposal(id);
}


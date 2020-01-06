<div style="width: 100%;">
	<div class="ui-widget">
		<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
			<p> 
				<i class="fa fa-exclamation-triangle fa-lg" style="float: left; margin-right: .3em;"></i>
				<strong>Alert: </strong>	
				<span id="importMsg"></span>
			</p>
		</div>
	</div>
</div>

<div id="surveyReport" style="width:100%; left:0; display: none;">
	<p></p>
	<center>
		<div class="boldReportSubtitle" id="psl-surveys" style="width:90%; line-height:20px; margin-left:auto; margin-right:auto; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"></div>
	<center>
</div>
					
<div id="zoneReport" style="width:100%; left:0; display: none;">
	<ul class="boldReportSubtitle" id="psl-zones" style="width:75%; line-height:20px; margin-left:auto; margin-right:auto; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"></ul>
</div>
					
<div id="netsReport" style="width:100%; left: 0; display: none; max-height: 270px; overflow-y: scroll;">
	<table  class="table_usrRatings summaryTblHeader" cellspacing="0" cellpadding="0" style="border: solid 1px #ddd;">
		<thead>
			<tr class="tr_usrRatings divHeader">
				<th class="td60">Net</th>
				<th class="td80">Start Date</th>
				<th class="td80">End Date</th>
				<th class="td60">Start Time</th>
				<th class="td60">End Time</th>
				<th class="td60">Spots</th>
			</tr>
		</thead>
		<tbody id="psl-lines" style="background-color: #fff;"></tbody>
	</table>
</div>

<div id="dmasReport" style="width:100%; left:0; display: none;">
	<div>ShowSeeker imported only the first DMA found in the file.</div>
</div>

<div id="demosReport" style="width:100%; left:0; display: none;">
	<ul class="boldReportSubtitle" id="psl-demos"></ul>
</div>


<center>
	<p></p>
	<div style="width: 100%; font-size: 8pt;" id="note"></div>
	<p></p>
	<div style="width:100%; height: 25px; padding: 5px;">
		<button id="closingReport" class="btn-red"><i class="fa fa-times-circle"></i> Close</button>
	</div>
</center>

<script>

	$('#closingReport').on('click',function(){
		$('#dialog-disclaimer').dialog('close');
	});
	
	$('#closingReport').button();
	
	$('#closingReport').on('click',function(){
		$("#dialog-image-ppt-selector").dialog("destroy");
	});

	var data = $("#dialog-disclaimer").data("psllines");


	//NO SURVEY
	if(data.unknownSurvey.length > 0){
		var svy = data.unknownSurvey;
		updateImportMsg('ShowSeeker did not match the following survey:')
		$('#surveyReport').show();
		$('#note').html('Note: This schedule has been imported without a survey or ratings.');
		reportNonMappedSurveys(svy);

	}
	//NO ZONES
	else if(data.unMappedZone.length > 0){
		var msg 		= 'ShowSeeker did not match the following Zone';
		var finalNote 	= 'Note: This schedule ';
		if(data.unMappedZone.length > 1){
			msg += 's';
		}
		msg += ':';
		updateImportMsg(msg);
		$('#zoneReport').show();

		if(data.proposalId !== null){
			finalNote += 'has been imported without ';
			
			if(data.unMappedZone.length > 1){
				finalNote += 'these zones.';
			}
			else{
				finalNote += 'this zone.';
			}
		}
		else{
			finalNote += 'could not be imported.';
		}

		$('#note').html(finalNote);
		reportNonMappedZone(data.unMappedZone);
	}
	else if(data.unMappedNet.length > 0){
		var msg = 'This schedule has been imported without the following line';
		if(data.unMappedNet.length > 1){
			msg += 's';
		}
		msg += ':';		
		updateImportMsg(msg);
		$('#netsReport').show();
		reportNonMappedStations(data.unMappedNet);
	}
	else if(data.multipleDMA){
		updateImportMsg('ShowSeeker did not match the following DMAs:');
		$('#dmasReport').show();
	}
	else if(data.unknownDemos.length > 0){
		updateImportMsg('ShowSeeker did not match the following demo(s):');
		var demos = data.unknownDemos;
		$('#demosReport').show();		
	}



	function reportNonMappedZone(zones){
		for(var z=0; z<zones.length; z++){		
			$('#psl-zones').append($('<li>'+String(zones[z].name).replace('null',' - ') +' ('+String(zones[z].syscode).replace('null',' . ')+')</li>'));
		};
		return false;
	};

	function reportNonMappedSurveys(svy){
		$.each(svy,function(i,val){
			$('#psl-surveys').append(val);
		});
		return false;
	};	
	
	function reportNonMappedDemos(demos){
		$.each(demos,function(i,val){
			$('#psl-demos').append(val+'<br>');			
		});
		return false;		
	}
	
	function updateImportMsg(msg){
		$('#importMsg').text(msg);
	}
	
	function reportNonMappedStations(psllines){
		closeAllDialogs()
		var zn = ttlspots = ttlcost = 0;
		var xml_line;
	
		$('.ui-dialog').css({'height':'auto','max-height':'400px'});
		$('#dialog-disclaimer').css({'height':'92%'});
	
		$.each(psllines,function(i,val){

			if(zn != val.zone){
				xml_line = $("<tr><td colspan=6 height='26' valign='middle' class='maingroup' style='border:solid 1px #f1f1f1; padding-left:4px;'>Zone: "+val.zone+"</td></tr>").appendTo($('#psl-lines'));
				zn = val.zone;
			}

			xml_line = $("<tr class='tr_usrRatings'/>");
			st 		 = Date.parse('01/01/2016 '+ val.startTime).toString("HH:mm");	
			et 		 = Date.parse('01/01/2016 '+ val.endTime).toString("HH:mm");	
			title 	 = String(val.title).substr(0, 50);
			
			$("<td class='borderBottom td_usrRatings td60 cred' />").html(val.callSign).appendTo(xml_line);
			$("<td class='borderBottom td_usrRatings td60' />").html(Date.parse(val.startDate).toString('MM/dd/yy')).appendTo(xml_line);
			$("<td class='borderBottom td_usrRatings td60'/>").html(Date.parse(val.endDate).toString('MM/dd/yy')).appendTo(xml_line);
			$("<td class='borderBottom td_usrRatings td60'/>").html(String(val.startTime).substr(0,5)).appendTo(xml_line);
			$("<td class='borderBottom td_usrRatings td60'/>").html(String(val.endTime).substr(0,5)).appendTo(xml_line);
			$("<td class='borderBottom td_usrRatings td60'/>").html(val.spots).appendTo(xml_line);

			ttlspots= ttlspots+val.spots;
			ttlcost = ttlcost+val.total;

			xml_line.appendTo("#psl-lines");
		});
		return false;	
	};
		
</script>
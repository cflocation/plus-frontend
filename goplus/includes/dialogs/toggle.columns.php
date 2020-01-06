<p></p>

<div class="ezrating-panel-title" style="height: 30px; line-height: 30px; color: #fff; padding-left: 5px;">
	<i class="fa fa-columns fa-lg"></i> 
	Columns Settings
</div>

<div class="ezrating-wrapper" align="center"><!-- start wrapper -->
	<br>
	<div  style="height: 30px; padding: 5px; line-height: 30px;" align="center">
		<span id="colControlButtons">
			<label for="colsGlobal">All Proposals</label><input id="colsGlobal" 	 type="radio" name="columnsControl" value="global">
			<label for="colsProposal">Current Proposal</label><input id="colsProposal" type="radio" name="columnsControl" value="proposal">
		</span>
	</div>
		
	<div style="height: 370px; padding: 5px;">
		<div class="columnCtrlContainer">
			<div class="controlsHeader rounded-corners" id="ssColRoundedCorners">
				<table cellspacing="0" width="100%">
					<thead>
						<tr>
							<td colspan="2" class="toggleColsTd" style="padding: 3px;" align="center">
								<span class="colsWrapper">
									<label for="allSSColumns">Select All</label>
									<input type="checkbox" value="hide-all" id="allSSColumns" class="toggleAll toggleColumn ratingsCol" checked=""/>
								</span>
							</td>
						</tr>
					</thead>
					<tbody id="ssColumnsCtrlBody"></tbody>
				</table> 
			</div>
		</div>
			
		<div class="columCtrlSeparator">&nbsp;</div>
			
		<div class="columnCtrlContainer rtgColumnCtrlContainer">
			<div class="controlsHeader rounded-corners">
				<table cellspacing="0" width="100%">
					<thead>
						<tr>
							<td colspan="2" align="center" class="toggleColsTd" style="padding: 3px;">
								<span class="colsWrapper">
								<label for="allRtgsColumns" >Select All</label>
								<input type="checkbox" value="allRtgCols" id="allRtgsColumns" checked="checked" class="toggleColumn ratingsCol" />
								</span>
							</td>
						</tr>
					</thead>
					<tbody id="rtgColumnsCtrlBody"></tbody>	
				</table>
			</div>
		</div>
		<br clear="both"/><br>
		<center>
			<button class="btn-green" id="saveColControl" onclick="saveProposalColumns();"><i class="fa fa-floppy-o fa-lg"></i> Save</button>
			<button class="btn-red" id="cancelColControl" onclick="closeAllDialogs();"><i class="fa fa-times-circle fa-lg"></i> Cancel</button>
		</center>
	</div>
</div>

<script>

	$('#rtgColumnsCtrlBody,#ssColumnsCtrlBody').empty();
	
	var l;		
	var ssCols ={'Search Criteria':'search','Status':'statusFormat','Day':'day','Start Date':'startdate','End Date':'enddate','Start Time':'starttime',
	'End Time':'endtime','Spot Len':'spotLength','Rate Card':'ratevalue'};
	var rtgCols ={'Ratings':'rating','Share':'share','Impressions':'impressions','GRPs':'gRps','GIMPs':'gImps','CPP':'displayCpp','CPM':'CPM','Reach':'reach','Freq':'freq'};



	for(var key in ssCols) {
		l =	 '<tr><td class="columnCtrlWrapper" ><span class="colsWrapper">';
		l += '<label  for="'+ssCols[key]+'-col" class="label100">'+key+'</label>';
		l += '<input type="checkbox"  value="'+ssCols[key]+'" id="'+ssCols[key]+'-col"  class="toggleColumn ssCol" />';
		l += '</span></td></tr>';
		$('#ssColumnsCtrlBody').append(l);
	};


	for(var key in rtgCols) {
		l =	 '<tr><td class="columnCtrlWrapper" ><span class="colsWrapper">';
		l += '<label  for="'+rtgCols[key]+'-col" class="label100">'+key+'</label>';
		l += '<input type="checkbox"  value="'+rtgCols[key]+'" id="'+rtgCols[key]+'-col"  class="toggleColumn rtgsCol" />';
		l += '</span></td></tr>';
		$('#rtgColumnsCtrlBody').append(l);
	};	

	$('#colControlButtons,.colsWrapper').buttonset();
	$('#saveColControl,#cancelColControl').button();
	
	if(proposalRattingsOn < 1){
		$('.columCtrlSeparator,.rtgColumnCtrlContainer').hide();
		$('.columnCtrlContainer').css({'width':'100%'});
		$('#ssColRoundedCorners').css({'width':'55%'});;	
	}

	if($.isEmptyObject(myEzRating) || myEzRating.getRatings('saved') !== 1){
			$('.columCtrlSeparator,.rtgColumnCtrlContainer').hide();
			$('.columnCtrlContainer').css({'width':'100%'});
			$('#ssColRoundedCorners').css({'width':'55%'});
	}

	setupColsCtrlPopup();
	setCtrlColumnsButtonState();	
	
	function saveProposalColumns(){
		
		var hiddenCols = manageColumnEvents();
		var url 			= apiUrl;
		var d 			= {};
		var opt 			= $('input[name=columnsControl]:checked').val();
		var allPsl 		= false;
		
		if(opt === 'global'){
			userSettings.hiddenColumns 	= hiddenCols;
			allPsl = true;
			userSettings.proposalId = proposalid;
		    saveUserSettings();		    			
		}
		else{
			url +='proposal/settings/'+ proposalid;	
			d.proposalId = proposalid;
			d.settings 	= {};
			d.settings.hiddenColumns = hiddenCols;
			d.settings.showWeeksOff = true;	// toggle control for hidden weeks
			d.settings.proposalShowTotals = true; //show totals section or not by default
			d.settings.proposalCollapse = true; //show proposal section or not by default
			d.settings.sortCol = "startDate"; //default proposal lines sorting
			d.settings.sortOrder = "ASC"	; //default proposal lines sorting
			$.ajax({
		        type: 'post',
		        url: url,
		        dataType: "json",
		        headers: {"Api-Key":apiKey,"User":userid},
		        processData: false,
		        contentType: 'application/json',
			     data: JSON.stringify(d),
		        success:function(resp){
		        },
		        error:function(){}
		    });
		}
		setProposalHiddenCols(hiddenCols,allPsl);
		datagridProposal.buildEmptyGrid(); 
		$('#dialog-window').dialog('destroy');
		displayColumns();
	};
</script>
























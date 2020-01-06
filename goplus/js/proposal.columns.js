$('#allSSColumns,#allRtgsColumns').live('change',function(){
	
	var columnsType = 'ssCol';
	
	if($(this).prop('id') !== 'allSSColumns'){
		columnsType = 'rtgsCol';
	}

	var allstate = $(this).is(':checked');

	$('.'+columnsType).each(function(){
		if(allstate){
			$(this).prop('checked',true).trigger('refresh');
		}
		else{
			$(this).prop('checked',false).trigger('refresh');
		}
	});
		
	$('#colControlButtons,.colsWrapper').buttonset('refresh');	
});



$('.toggleColumn').live('change',function(){
	ctrlToggleAll();
});



function displayColumns(colUpdate){
	var ids 		= [];
	var columnsOff 	= [];	
	var removingCols, col, i, j;
	var proposalCols = setColumns();
		

	removingCols = proposalHiddenColumns.columns;
	
	if(!userSettings.showWeeksOff){
		columnsOff = removingCols.concat(weeksdata);
	}
	else{
		columnsOff = removingCols;
	}

	if(colUpdate){
		setupColsCtrlPopup(columnsOff);
	}

	for(i=0; i<columnsOff.length; i++){
		for(j=proposalCols.length-1; j>=0; j--){
			if(columnsOff[i] == proposalCols[j]['id'] || proposalCols[j]['id'].indexOf(columnsOff[i]) !== -1){
				proposalCols.splice(j, 1);
			}
		}
	}
	datagridProposal.toggleColumns(proposalCols);
	datagridProposal.invalidateWeeks();	

	setCtrlColumnsButtonState();
	
	//UPDATES FREEZE COLUMNS FOR THE MENU	
	updateFreezeOptions(proposalCols);
	return false;
};



function ctrlToggleAll(){
	var c, status;
	var colsCtrls = {'allSSColumns':'ssCol','allRtgsColumns':'rtgsCol'};
	var lbl = "";
	for(key in colsCtrls){
		status = false;
		lbl = "Select All";		
		c = 0;
		$('.'+colsCtrls[key]).each(function(){
			if($(this).is(':checked')){
				c++;
			}
		});
		
		if(c === $('input[type="checkbox"].'+colsCtrls[key]).length){
			status = true;
			lbl = "Deselect All";			
		}
		
		$('#'+key).prop('checked',status).trigger('refresh');
		$('#'+key).siblings('label').find('span.ui-button-text').text(lbl);
	}
	
	$('.colsWrapper').buttonset('refresh');
};



function manageColumnEvents(){
	var removingCols = [];
	var col;
	var headers = ['allRtgCols','hide-all'];
	$('.toggleColumn').each(function(){
		col  = $(this).val();
		if(headers.indexOf(col) === -1 &&  !$(this).is(':checked')){
			if(removingCols.indexOf(col) === -1){
				removingCols.push(col);
			}
		}
	});
	return removingCols;
}



function setProposalHiddenCols(cols,isGlobal){
	proposalHiddenColumns.columns = cols;	
	proposalHiddenColumns.isGlobal = isGlobal;
};



function setCtrlColumnsButtonState(){
	var c = 0;
	var rtgCols = ["rating","displayCpp","gRps","share","impressions","CPM","gImps","reach","freq"];	
	var rtgColsOff = 0;
	var pslHidenColsCount = proposalHiddenColumns.columns.length;


	$('.toggleColumn').each(function(){
		if(! $(this).is(':checked')){
			c++;
			return false;
		}
	});

	//if(proposalRattingsOn === 1){
		for(var i=0; i<pslHidenColsCount; i++){
			if(rtgCols.indexOf(proposalHiddenColumns.columns[i]) !== -1){
				rtgColsOff ++;
			}
		}
	//} 

	if((pslHidenColsCount > 0 && proposalRattingsOn > 0) || (proposalRattingsOn < 1 && (pslHidenColsCount - rtgColsOff) > 0)){	
		$('#customcolumnsbtn').addClass('highlight');
	}
	else{
		$('#customcolumnsbtn').removeClass('highlight');
	}

	$('#proposal-buttons').buttonset('refresh');	
};



function setColumns(){
	var demoCols 	= clone(datagridProposal.buildDemoColumns(formatDemos()));
	var dynaCols 	= datagridProposal.buildDynamicColumns(); 
	var re 			= demoCols.concat(dynaCols);	
	return re;
};



function setupColsCtrlPopup(removingCols){

	var columns  = proposalHiddenColumns.columns;
	
	if(removingCols){
		columns = removingCols;
	}
	
	$('#colsGlobal,#colsProposal').prop('checked',false);

	if(proposalHiddenColumns.isGlobal === true || proposalid === 0){
		$('#colsGlobal').prop('checked',true);
	}
	else{
		$('#colsProposal').prop('checked',true);
	}

	$('.toggleColumn').each(function(){
		col 		= $(this).prop('id').replace('-col', '');
		state 	= true;
		if($.inArray( col, columns ) !== -1){
			state = false;
		}
		$(this).prop('checked',state).trigger('refresh');
	});
	
	$('#colsGlobal,#colsProposal').trigger('refresh');
	$('#colControlButtons,.colsWrapper').buttonset();
	
	ctrlToggleAll();
	
	return false;
};



function updateFreezeOptions(cols){
	var item;
	$('#freezeColsCtrl').find('li').remove();
	for(var i=0; i<cols.length; i++){
		item = '<li class="package-menu" id="freezeTitle">';
		item += '<a href="javascript:datagridProposal.freezeByColumn('+i+');">';
		item += cols[i].name.replace('<br>',' ')+'</a></li>';
		$('#freezeColsCtrl').append(item);
		if(cols[i].name.indexOf('Cost') !== -1){
			break;				
		}
	}
	return false;
};


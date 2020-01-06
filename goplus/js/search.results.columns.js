$(document).mouseup(function (e){

    if (btnOpener.is(e.target)){
        return;
    }

    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
        container.hide();
    }
});

$('#hide-all-col').click(function(){
	var c = 0;
	
	var allstate = $(this).is(':checked');

	$('.toggleColumn').each(function(){
		if(allstate){
			$(this).prop('checked',false);
			$(this).closest('label').removeClass('multiselect-on');
		}
		else{
			c++;
			$(this).prop('checked',true);
			$(this).closest('label').addClass('multiselect-on');
		}
	});
	
	
	displayColumns();
	
});


$('#customcolumnsbtn').click(function(){
	customColumns();
});

//$('#customcolumnsbtn').hover(function(){th},function(){});

$('.toggleColumn').change(function(){
	$.when(displayColumns()).then(ctrlToggleAll());
	return;
});






function configProposalColumns(){

	var proposalCols = setColumns();
	var removingCols = userSettings.hiddenColumns;
	
	$('.toggleColumn').each(function(){
		var col = $(this).prop('id');
		var column = col.replace('-col', '');
		if($.inArray( column, removingCols ) != -1){
			$(this).prop('checked',false);
			$(this).closest('label').removeClass('multiselect-on');
		}
	});
	
	for(i=0; i<removingCols.length; i++){	
		for(j=0; j<proposalCols.length; j++){		
			if(removingCols[i] == proposalCols[j]['id']){
				proposalCols.splice(j, 1);
			}
		}
	}

	datagridProposal.toggleColumns(proposalCols);
	
	ctrlInterface();

}

function displayColumns(){
	
	var ids = [];
	var removingCols = [];

	userSettings.hiddenColumns = [];
	
	$('.toggleColumn').each(function(){
		if(! $(this).is(':checked'))
			ids.push($(this).val());
	});
	

	for(i=0; i<ids.length; i++){
		var coldata = ids[i];	
		var column = coldata.split("-");
		removingCols.push(column[0]);
		userSettings.hiddenColumns.push(column[0]);
	}
	
	var proposalCols = setColumns();


	
	for(i=0; i<removingCols.length; i++){	
		for(j=0; j<proposalCols.length; j++){		
			if(removingCols[i] == proposalCols[j]['id']){
				proposalCols.splice(j, 1);
			}
		}
	}

	datagridProposal.toggleColumns(proposalCols);
	
		ctrlInterface();
		saveUserSettings();
	
	return;
	
}


function customColumns(){
	
	if($('#custom-proposal-cols').is(':visible')){
		$('#custom-proposal-cols').hide();
	}
	else{
		var pos = $('#customcolumnsbtn').position();
		var t = parseInt(pos.top)  + 27;
		var l = parseInt(pos.left) + 2;

		$('#custom-proposal-cols').attr("style", "overflow:hidden !important");
		$('#custom-proposal-cols').css({'left':l+'px','top':t+'px'});
	}

}


function ctrlInterface(){
	var c = 0;
	
	$('.toggleColumn').each(function(){
		if(! $(this).is(':checked'))
			c++;
	});

	if(c>0){
		$('#customcolumnsbtn').addClass('highlight');
		$('#customcolumnsbtn').attr('title', 'Hidden Columns');
		
	}
	else{
		$('#customcolumnsbtn').removeClass('highlight');
		$('#customcolumnsbtn').attr('title', '');
	}	
}


function ctrlToggleAll(){
	var c = 0;
	$('.toggleColumn').each(function(){
		if(! $(this).is(':checked'))
			c++;
	});
	if(c==8){
		$('#hide-all-col').prop('checked',true);	
		$('#hide-all-col').closest('label').addClass('multiselect-on');
	}
	else{
		$('#hide-all-col').prop('checked',false);	
		$('#hide-all-col').closest('label').removeClass('multiselect-on');
	}	
}


function setColumns(){
	
	var    columns = [
		            {
		            id: "callsignFormat", 
		            name: "Net", 
		            field: "callsignFormat", 
		            sortable: true,
		            width:60, 
		            minWidth:60, 
		            maxWidth:60,
		            dynamic:0,
		            formatter: Slick.Formatters.Callsign
		        },   
		        {
		            id: "titleFormat", 
		            sortable: true,
		            name: "Program Title", 
		            field: "titleFormat",
		            width:275, 
		            minWidth:275,
		            dynamic:0,
		            formatter: Slick.Formatters.EPITitle,
		            editor: Slick.Editors.LongText
		        },
		        {
		            id: "search", 
		            name: "Search Criteria", 
		            sortable: true,
		            field: "search", 
		            width:140,
		            minWidth:140,
		            maxWidth:140,
		            resizable: true
		        },
		        {
		            id: "statusFormat", 
		            name: "Status", 
		            sortable: true,
		            field: "statusFormat", 
		            width:60, 
		            minWidth:60, 
		            maxWidth:100,
		            formatter: Slick.Formatters.StatusIcons
		        },
		        {
		            id: "day", 
		            name: "Day", 
		            field: "dayFormat", 
		            sortable: true,
		            width:80, 
		            minWidth:80,
		            dynamic:0
		        },
		
		        {
		            id: "startdate", 
		            name: "Start Date", 
		            field: "startdatetime", 
		            sortable: true,
		            width:80, 
		            minWidth:80, 
		            maxWidth:80,
		            dynamic:0,
		            formatter: Slick.Formatters.FormatDate
		            
		        },
		        {
		            id: "enddate", 
		            name: "End Date", 
		            field: "enddatetime", 
		            sortable: false,
		            width:80, 
		            minWidth:80, 
		            maxWidth:80,
		            dynamic:0,
		            formatter: Slick.Formatters.FormatDate
		        },
		        {
		            id: "starttime", 
		            name: "Start Time", 
		            field: "startdatetime", 
		            sortable: true,
		            width:80, 
		            minWidth:80,
		            maxWidth:80,
		            dynamic:0,
		            formatter: Slick.Formatters.FormatTime
		        },
		        {
		            id: "endtime", 
		            name: "End Time", 
		            field: "enddatetime", 
		            sortable: false,
		            width:75, 
		            minWidth:75, 
		            maxWidth:75,
		            dynamic:0,
		            formatter: Slick.Formatters.FormatTime
		        },
		        {
		            id: "weeks", 
		            name: "Wks", 
		            field: "weeks", 
		            sortable: true,
		            width:50, 
		            minWidth:50, 
		            maxWidth:60,
		            dynamic:0,
		            editor: Slick.Editors.Integer,
		            formatter: Slick.Formatters.FormatRed
		        },
		        {
		            id: "spotsweek", 
		            name: "Sp/Wk", 
		            field: "spotsweek", 
		            sortable: true,
		            width:60, 
		            minWidth:60, 
		            maxWidth:60,
		            dynamic:0,
		            editor: Slick.Editors.Integer,
		            formatter: Slick.Formatters.FormatRed
		        },
		        {
		            id: "ratevalue", 
		            name: "Card", 
		            field: "ratevalue", 
		            sortable: true,
		            width:60, 
		            minWidth:60, 
		            maxWidth:60,
		            dynamic:0,
					cssClass: "dynamicRight",
		            formatter: Slick.Formatters.Ratecard
		        },
		        {
		            id: "rate", 
		            name: "Rate", 
		            field: "rate", 
		            sortable: true,
		            width:60, 
		            minWidth:60, 
		            maxWidth:70,
		            dynamic:0,
		            editor: Slick.Editors.Float,
					cssClass: "dynamicRight",
		            formatter: Slick.Formatters.Rates
		        },
		        {
		            id: "spots", 
		            name: "Spots", 
		            field: "spots", 
		            sortable: true,
		            width:50, 
		            minWidth:50, 
		            maxWidth:60,
		            dynamic:0,
					cssClass: "dynamicRight",
		            formatter: Slick.Formatters.FormatRed
		        }
		        ,
		        {
		            id: "total", 
		            name: "Cost", 
		            field: "total", 
		            sortable: true,
		            width:70,
		            minWidth:70,
		            dynamic:0,
		            sortable: true,
					cssClass: "dynamicRight",
		            formatter: Slick.Formatters.TotalCost
		        }];
		        
        if(!ratecard){
            columns[11].cssClass = "noratecards";
        }
        
        return columns;
	
}

  

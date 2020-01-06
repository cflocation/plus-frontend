$(window).on('resize', refresh);

window.onload = maxWindow;

function maxWindow() {
    window.moveTo(0, 0);

    if (document.all) {
        top.window.resizeTo(screen.availWidth, screen.availHeight);
    } else if (document.layers || document.getElementById) {
        if (top.window.outerHeight < screen.availHeight || top.window.outerWidth < screen.availWidth) {
            top.window.outerHeight = screen.availHeight;
            top.window.outerWidth = screen.availWidth;
            //http://jsfiddle.net/NfmX3/2/
        }
    }
}


function windowManager(){
	
	$('#custom-proposal-cols').hide();//toggle columns control
	 
	//get the height and width of the content
	var divHeight  = Math.round($("#container").height()) - 10;
	$('#proposal-list').height(divHeight-175);
	$('#saved-searches-datagrid').css('height', divHeight-190);
	
	datagridProposalManager.renderGrid();
	datagridSearchResults.renderGrid();
	datagridProposal.renderGrid();
	datagridTotals.renderGrid();
	datagridSavedSearches.renderGrid();

	sizePanels(builderpanel);   
	sizingTotalsBar();
}

function sizingTotalsBar(){
	if($('#totals-wrapper').width() < 1125){
		if($('#broadcast').is(':checked')){
			$('#label-total-name').text("Totals BC");
		}
		else{
			$('#label-total-name').text("Totals SC");
		}
					
		$('#agcy-lbl').text('Agcy Comm');
		$('#pck-lbl').text('Pkg Disc');
	}
	else{
		if($('#broadcast').is(':checked')){
			$('#label-total-name').text("Totals Broadcast Calendar");
		}
		else{
			$('#label-total-name').text("Totals Standard Calendar");
		}	
		$('#agcy-lbl').text('Agency Commission');
		$('#pck-lbl').text('Package Disc');
	}
}


//resize window stuff
$(window).resize(debouncer(function(e){
  if(!firstload){
    windowManager();
  }
}));



function debouncer(func, timeout) {
	var timeoutID = timeout || 200;
		return function() {
				var scope = this,
				args = arguments;
				clearTimeout(timeoutID);
				timeoutID = setTimeout(function() {
					func.apply(scope, Array.prototype.slice.call(args));
					}, timeout);
				};
};


function setPanel(id){
	$('#'+id).slideToggle( 0, function(){
		builderpanel[id] = (builderpanel[id] === true) ? false : true;    
		sizePanels(builderpanel);
		toggleCollapseIcon(builderpanel);    
	});
};



function sizePanels(builderpanel){
	
	var rowsOfTotals = datagridTotals.dataSet().length;
	
	if(rowsOfTotals === 0){
		rowsOfTotals = 2;
	}
	
	var totalsHeight = (parseInt(rowsOfTotals) * 30)+70;
	var divHeight  	 = Math.round($("#container").height());
	var cnt 		 = builderpanel['panel1'] + builderpanel['panel2'];
	var gap 		 = 200;
	
	
	if(cnt > 0){
		divHeight = divHeight/cnt;
	}

	if(builderpanel['panel3']){
		divHeight = divHeight - (totalsHeight/cnt);
	}
	
	if(cnt > 1){
		gap = gap/cnt;
	}
	var h = 0;

	if(builderpanel['panel1'] == true){
		$("#panel1").height(divHeight-gap);
		h = Math.round($("#panel1").height()-11);
		$("#search-results").height(h);
		datagridSearchResults.renderGrid();
		$(".serchResults").show();
	}
	else{
		$(".serchResults").hide();
	}
	
	if(builderpanel['panel2'] == true){
		$("#panel2").height(divHeight-gap);
		h = Math.round($("#panel2").height()-11);
		$("#proposal-build-grid").height(h);
		datagridProposal.renderGrid();
		$("#proposal-buttons").css('display','inline');
	}
	else{
		$("#proposal-buttons").css('display','none');
	}
	
	if(builderpanel['panel3'] == true){
		$("#panel3").height(totalsHeight);
		h = Math.round($("#panel3").height()-11);
		$("#total-fixed-datagrid").height(totalsHeight-11);
		datagridTotals.renderGrid();
	}
}


//sidebar settings
function toggleSidebar(){
  var sideloc = $('#sidebar2').css('left');

  if(sideloc == '0px'){
    sidebarClose();
  }else{
    sidebarOpen();
  }
  windowManager();
}

function sidebarClose(){
    $('#sidebar2').css('left', -300);
    $('#container').css('left', 0);
    $('#collapse-settings').html('<i class="fa fa-arrow-circle-right fa-lg"></i>');
}


function sidebarOpen(){
    $('#sidebar2').css('left', 0);
    $('#container').css('left', 297);
    $('#collapse-settings').html('<i class="fa fa-arrow-circle-left fa-lg"></i>');
	windowManager();
}





//side panel manager
function swapSettingsPanel(mode,edit){
	//reset all the panels
	$('#btn-dayparts').show();	
	$("#fixed-panel,#rotator-panel,#rotator-type,#avails-panel,#info-panel,#side-menu,#multipleSelectTxt").hide();	
	setHeaderLabel('search');
	editRotator = false;
	
	closeAllDialogs();
	
	if(mode == 'none'){
		return;
	}
	
	if(mode == 'info'){
		$('#side-menu').hide();
		$('#info-panel').show();
		return;
	}
	
	if(mode == 'avails'){
		
		$('#sidebar-row-more').prependTo("#avails-panel");
		$('#more-marathons,#more-marathons-label').hide();		
		$('#btn-dayparts').hide();
		$('#side-menu,#avails-panel').show();
		setHeaderLabel('avails');
		return;
	}

	if(mode == 'editrotator'){
		if(proposalid == 0){//check to see if editing rotators is even valid
			loadDialogWindow('noproposal', 'ShowSeeker Plus', 450, 180, 1, 0);
			$('#side-menu,#fixed-panel').show()
			return;
		}
		//if they want to edit a rotator and there is no rotator selected lets warn them
		var itemcount = datagridProposal.selectedRowsByType();

		if(itemcount.Rotator == 0 && itemcount.Avail == 0){
			loadDialogWindow('nolinesrotator', 'ShowSeeker Plus', 450, 180, 1, 0);
			$('#side-menu,#fixed-panel').show();
			return;
		}
		editRotator = true;
		
		if(itemcount.Rotator != itemcount.Avail){
			$('#update-line-span').text(' Update Rotator').button("refresh");
		}
		else{
			$('#update-line-span').text(' Update Avail').button("refresh");
		}
	    $(".header-rotator-create,#more-marathons,#more-marathons-label").hide();		
		$('#side-menu,#rotator-panel,#rotator-type,.header-rotator-edit').show();
		$('#sidebar-row-more').prependTo("#rotator-panel");		
		$('#more-demographics').prop('disabled',true).button('refresh');
		setHeaderLabel('editRotator');
		return;
	}

	if(mode == 'rotator'){
		$('#more-demographics').prop('disabled',false).button('refresh');		
		$('#sidebar-row-more').prependTo("#rotator-panel");
		$('#more-marathons,#more-marathons-label,.header-rotator-edit').hide();
		$('#side-menu,#rotator-panel,#rotator-type,.header-rotator-create,#multipleSelectTxt').show();	
		setHeaderLabel('createRotator');
		dayPartsState();
		return;
	}
	
	if(mode == 'search'){
		$('#sidebar-row-more').insertAfter("#sidebar-row-filter");
		$('#more-marathons,#more-marathons-label').show();
		$('#side-menu').css('display', 'inline');
		$("#fixed-panel").css('display','inline');
		return;
	}
}


function toggleCollapseIcon(builderpanel){  //toggles collpase and expand icons

	//toggles plus icon at proposal search results grid
  if(builderpanel['panel1'] === true){
		$('#wrapper-result-icon .fa-minus-square').show();
		$('#wrapper-result-icon .fa-plus-square').hide();		

  }else{
		$('#wrapper-result-icon .fa-plus-square').show();
		$('#wrapper-result-icon .fa-minus-square').hide();
  }

	//toggles plus icon at proposal lines grid
  if(builderpanel['panel2'] === true){
		$('#wrapper-proposal-icon .fa-minus-square').show();  
		$('#wrapper-proposal-icon .fa-plus-square').hide();  
	}else{
		$('#wrapper-proposal-icon .fa-plus-square').show();
		$('#wrapper-proposal-icon .fa-minus-square').hide();			
  }

	//toggles plus icon at total table
  if(builderpanel['panel3'] === true){
		$('#wrapper-total-icon .fa-minus-square').show();
		$('#wrapper-total-icon .fa-plus-square').hide();
  }
  else{
		$('#wrapper-total-icon .fa-plus-square').show();  
		$('#wrapper-total-icon .fa-minus-square').hide();
	}
};

function toggleProposalGrid(){};

function openApiCallFlag(){
	$('#updateLinesFlag').show();
	$('#applyRcBtn,#applyRateBtn,#applySpotsBtn,').prop('disabled', true).button("refresh");	
	return false;	
};

function closeApiCallFlag(){
	$('#updateLinesFlag').hide();
	$('#applyRcBtn,#applyRateBtn,#applySpotsBtn,').prop('disabled', false).button("refresh");	
	return false;
};



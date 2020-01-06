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
    
	/*var w=1300,h=710,x=(screen.width) ? (screen.width-w)/2 : 0,y=(screen.height) ? (screen.height-h)/2 : 0;
    window.moveTo(x, y);
	if ((screen.width<1024) || (screen.height<768))window.resizeTo(1024,710);else window.resizeTo(w,h);*/	
}


function windowManager(){
	$('#custom-proposal-cols').hide();//toggle columns control
	 
	//get the height and width of the content
	var divHeight  = Math.round($("#container").height()) - 10;
	$('#proposal-list').height(divHeight-175);
	
	datagridProposalManager.renderGrid();
	datagridSearchResults.renderGrid();
	datagridProposal.renderGrid();
	sizePanels(builderpanel);   
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
}



function setPanel(id){
	$('#'+id).slideToggle( 0, function() {
		builderpanel[id] = (builderpanel[id] == true) ? false : true;    
		sizePanels(builderpanel);
		toggleCollapseIcon(builderpanel);    
	});
}



function sizePanels(builderpanel){
	var divHeight  = Math.round($("#container").height());	
	var cnt = builderpanel['panel1'] + builderpanel['panel2'];
	var gap = 145;
	
	if(cnt > 0){
		divHeight = divHeight/cnt;
	}

	if(builderpanel['panel3']){
		divHeight = divHeight - (divHeight/cnt);
	}

	if(cnt > 1){
		gap = gap/cnt;
	}
	
	if(builderpanel['panel1'] == true){
		$("#panel1").height(divHeight-gap);
		var h = Math.round($("#panel1").height()-11);
		$("#search-results").height(h);
		datagridSearchResults.renderGrid();
	}
	
	if(builderpanel['panel2'] == true){
		$("#panel2").height(divHeight-gap);
		var h = Math.round($("#panel2").height()-11);
		$("#proposal-build-grid").height(h);
		datagridProposal.renderGrid();
		$("#proposal-buttons").css('display','inline');
	}else{
		$("#proposal-buttons").css('display','none');
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
}


function toggleCollapseIcon(builderpanel){  //toggles collpase and expand icons

	//toggles plus icon at proposal search results grid
  if(builderpanel['panel1'] == true){
		$('#wrapper-result-icon .fa-minus-square').css({'display':'inline'});
		$('#wrapper-result-icon .fa-plus-square').hide();		

  }else{
		$('#wrapper-result-icon .fa-plus-square').css({'display':'inline'});
		$('#wrapper-result-icon .fa-minus-square').hide();
  }

	//toggles plus icon at proposal lines grid
  if(builderpanel['panel2'] == true){
		$('#wrapper-proposal-icon .fa-minus-square').css({'display':'inline'});  
		$('#wrapper-proposal-icon .fa-plus-square').hide();  
	}else{
		$('#wrapper-proposal-icon .fa-plus-square').css({'display':'inline'});
		$('#wrapper-proposal-icon .fa-minus-square').hide();			
  }

	//toggles plus icon at total table
  if(builderpanel['panel3'] == true){
		$('#wrapper-total-icon .fa-minus-square').css({'display':'inline'});
		$('#wrapper-total-icon .fa-plus-square').hide();
  }
  else{
		$('#wrapper-total-icon .fa-plus-square').css({'display':'inline'});  
		$('#wrapper-total-icon .fa-minus-square').hide();
	}
};

function openEZGrids(){
	if(solrSearchParamaters().networks.length == 1){
		closeAllDialogs();
		var params = solrSearchParamaters();
		mixTrack('SnapShot - Grids Button',{"callsign":params.networks[0].callsign,
											"endDate":params.enddate.substr(0,10),
											"endDateTime":params.endtime,
											"networkId":params.networks[0].id,
											"startDate":params.startdate.substr(0, 10),
											"startTime":params.starttime,
											"zoneId":params.zoneid,
											"zoneName":params.zone});
											
		launchgrids = false;
		var h= screen.height*.85;
		ezgridsOpen = true;
		ezgrids = window.open("grids/grid.php", "ezgridswindow", "location=no,status=yes,resizable=yes,scrollbars=yes,width=1024,height="+h);
		
				
	}
	else{
		
		dialogNetworkList();
		loadDialogWindow('singlenetwork', 'ShowSeeker Plus', 450, 180, 1, 0);
		launchgrids = true;
	}
}

function toggleProposalGrid(){}

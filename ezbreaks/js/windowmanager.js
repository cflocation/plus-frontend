// WINDOW //
$(window).resize(debouncer(function(e){
	windowManager();
}));


function windowManager(){
	//get the height and width of the content
	var divHeight  = Math.round($(".main").height());

	$('#datagrid-networks').css('height', divHeight-100);
  $('#datagrid-viewer').css('height', divHeight-100);
  $('#datagrid-download-scheduler').css('height', divHeight-100);
  $('#datagrid-breaks').css('height', divHeight-100);
  $('#datagrid-download-update-scheduler').css('height', divHeight-100);
  $('#datagrid-changes').css('height', divHeight-100);
  $('#datagrid-queue').css('height', divHeight-100);

  datagridNetworks.renderGrid();
  datagridViewer.renderGrid();
  datagridDownloadSchedule.renderGrid();
  datagridBreaks.renderGrid();
  datagridDownloadUpdateSchedule.renderGrid();
  datagridChanges.renderGrid();
  datagridQueue.renderGrid();

  if($('#is_superadmin_true').length == 1)
  {
    $('#datagrid-custom-breaks').css('height', divHeight-100);
    $('#datagrid-custom-breaks-rulesets').css('height', divHeight-100);
    $('#datagrid-custom-titles').css('height', divHeight-100);
    $('#datagrid-access').css('height', divHeight-100);
    datagridCustomBreakRulesets.renderGrid();
    datagridCustomBreaks.renderGrid();
    datagridCustomTitles.renderGrid();
    datagridAccessNetworks.renderGrid();
  }
  
}


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

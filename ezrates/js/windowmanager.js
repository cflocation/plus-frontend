// WINDOW //
$(window).resize(debouncer(function(e){
	windowManager();
}));


function windowManager(){
	//get the height and width of the content
	var divHeight  = Math.round($(".main").height());

	$('#datagrid-dayparts').css('height', divHeight-100);
  $('#datagrid-ratecards').css('height', divHeight-100);
  $('#datagrid-dayparts-selected').css('height', divHeight-90);
  $('#datagrid-pricing-cable').css('height', divHeight-100);
  $('#datagrid-pricing-broadcast').css('height', divHeight-100);
  $('#datagrid-hot-programming').css('height', divHeight-148);


  datagridDayparts.renderGrid();
  datagridRatecards.renderGrid();
  datagridDaypartSelected.renderGrid();
  datagridPricing.renderGrid();
  datagridPricingBroadcast.renderGrid();
  datagridHotProgramming.renderGrid();
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

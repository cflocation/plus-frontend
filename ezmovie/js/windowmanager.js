// WINDOW //
$(window).resize(debouncer(function(e){
	windowManager();
}));


function windowManager(){
	//get the height and width of the content
	var divHeight  = Math.round($(".main").height());

	$('#datagrid-show-list').css('height', divHeight-100);

  datagridShowList.renderGrid();
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

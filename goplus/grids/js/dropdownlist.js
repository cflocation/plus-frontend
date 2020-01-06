/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
function openList() {
    $("#showTypes").addClass("show");
}

// Close the dropdown menu if the user clicks outside of it
$(document).mouseup(function(e) {
    var container = $("#showTypes");
    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0){
	    $("#showTypes").removeClass("show");
    }
});

	


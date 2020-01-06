jQuery.fn.multiselect = function() {
    $(this).each(function() {
        var checkboxes = $(this).find("input:checkbox");
                
		checkboxes.each(function() {
			var checkbox = $(this);
			
            // Highlight pre-selected checkboxes
            if (checkbox.is(":checked"))
                checkbox.closest('label').addClass("multiselect-on");
 
            // Highlight checkboxes that the user selects
            checkbox.click(function() {
                if (checkbox.is(":checked"))
                    checkbox.closest('label').addClass("multiselect-on");
                else
                    checkbox.closest('label').removeClass("multiselect-on");
            });
        });
    });
};
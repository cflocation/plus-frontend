	///////////////////		
	///
	///////////////////	

	function grayout(){
		$('input.ssevent[type=checkbox]:visible:checked').each(function(){
			$(this).closest('div').css('background-color','gray');
		})
	
	}
		
	 /////////////////////////////////////
	 //  START AND END DATE CALENDARS   //
	/////////////////////////////////////


	function setCalendars(){			
		$('#startDate').datepicker({
			defaultDate: "+0",
			changeMonth: false,
			numberOfMonths: 3,
				onSelect: function( selectedDate ) {
					$('#endDate').datepicker( "option", "minDate", selectedDate );
				}
		});
		
		$('#endDate').datepicker({
			defaultDate: "+0",
			changeMonth: false,
			numberOfMonths: 3,
				onSelect: function( selectedDate ) {
					$('#startDate').datepicker( "option", "maxDate", selectedDate );
				}
		});	
		
		//populate the dates in the selectors
		$('#startDate').datepicker("setDate", sdate);
		$('#endDate').datepicker("setDate", edate);
			
		
		setCalendarType();		
	}
	
	
	function setCalendarType(){
		//var type = $('input:radio[name=calendar-mode-selector]:checked', sswin.document).val();
		
		var type = "broadcast";
		
		if(type == "broadcast"){
			$('#startDate').datepicker('option', 'firstDay', 1 );
			$('#startDate').datepicker('option', 'showTrailingWeek', false );
			$('#startDate').datepicker('option', 'showOtherMonths', true );
			$('#startDate').datepicker('option', 'selectOtherMonths', true );
			
			$('#endDate').datepicker('option', 'firstDay', 1 );
			$('#endDate').datepicker('option', 'showTrailingWeek', false );
			$('#endDate').datepicker('option', 'showOtherMonths', true );
			$('#endDate').datepicker('option', 'selectOtherMonths', true );
			
		}else{
			$("#startDate").datepicker("option", "firstDay", 0 );
			$("#startDate").datepicker( "option", "showTrailingWeek", true );
			$("#startDate").datepicker( "option", "showOtherMonths", false );
			$("#startDate").datepicker( "option", "selectOtherMonths", false );
			
			$("#endDate").datepicker("option", "firstDay", 0 );
			$("#endDate").datepicker( "option", "showTrailingWeek", true );
			$("#endDate").datepicker( "option", "showOtherMonths", false );
			$("#endDate").datepicker( "option", "selectOtherMonths", false );
		}
	}
			

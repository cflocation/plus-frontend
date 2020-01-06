
$('#addDemo').live('click',function(){
	var g,a,ages,i,cols,re;
	var sDemo 		= {};
	var fixedGroups = [4,5,6];
	var groupId 	= parseInt( $('#demoGroup').val() );
	
	sDemo.id 		= groupId;

	var demoName	= String($("#demoGroup option:selected").text());
	var pos 		= demoName.indexOf('(') + 1;
	sDemo.name  	= demoName.substr(pos, demoName.length-1-pos).trim();
		
	if(fixedGroups.indexOf(groupId) === -1){
		sDemo.ageFrom = $('#demoAgeFrom').val();
		sDemo.ageTo   = $('#demoAgeTo').val();
	}

	if(sDemo.id === 0){//NO DEMO SELECTED
		re = false;
	}
	else if((parseInt(sDemo.id) < 4) && !('ageFrom' in sDemo)){ //EXCLUDING MEN AND WOMEN IF NO AGES ARE PASSED
		re = false;
	}
	else{

		if(!dupeDemo(sDemo)){
			myEzRating.pushDemo(sDemo);
	
			var d = formatDemos();
			tagDemos(d);	
			resetDemos();
			re = true;
			toggleSubmitRatings();			
			additionalDemoStatus();
			ratingsCtrlButton();
		}
		resetSavedSettings();
	}
	unselectSavedParameters();
	activateAddCustomDemo();		
	return re;
});


$('.demoGroup').live('click',function(){
	var tObj;
	
	$('input.stdDemos').each(function(i,val){
		$(this).prop('checked',false);
	});
	
	for(var d = 0; d < demoGroups.length; d++){
	
		if(parseInt(demoGroups[d].id) === parseInt($(this).val())){

			$('input.stdDemos').each(function(i,val){
				
				tObj = val.value.split(',');

				for(var d1 = 0; d1 < demoGroups[d].startRange.length; d1++){
					if(tObj.indexOf(String(demoGroups[d].startRange[d1])) !== -1){
						$(val).prop('disabled', false);
						break;
					}
					else{
						$(val).prop('disabled', true);
					}
				}
			});
			
			break;
		}

	}
});


//DEMO SELECTION LISTENER
$('#demoGroup').change(function(){

	var selectedGroup = parseInt($(this).val());
	var fixedGroups = [0,4,5,6];
	
	if(fixedGroups.indexOf(selectedGroup) === -1){
		$('#demoAgeFrom, #demoAgeTo').prop('disabled',false);
		$('#demoAgeFrom, #demoAgeTo').siblings('label').removeClass('disabledText');
	}
	else{
		$('#demoAgeFrom, #demoAgeTo').prop('disabled',true);
		$('#demoAgeFrom, #demoAgeTo').siblings('label').addClass('disabledText');
	}

	if(selectedGroup === 7){  
		$("#demoAgeFrom > option").each(function(i,demoOpt) {
			if(parseInt(demoOpt.value) < 2 || parseInt(demoOpt.value) > 6){
		    	$(demoOpt).remove();
		    }
		});		
		
		$("#demoAgeTo > option").each(function(i,demoOpt) {
			if(parseInt(demoOpt.value) < 5 || parseInt(demoOpt.value) > 11 || demoOpt.value === '+'){
				$(demoOpt).remove();
			}
		});
		$("#demoAgeFrom").val(2);
		$("#demoAgeTo").val(11);		
	}
	else{
		removeDemosOptions();
		loadDemos();
	}
	
	activateAddCustomDemo();
	
});



//DMA SELECTOR LISTENER 
$('#ratingsDMA').live('click',function(){
	myEzRating.setRatings('dma',false);
	myEzRating.setRatings('cdma',true);
	if($(this).is(':checked')){
		myEzRating.setRatings('dma',true);
		myEzRating.setRatings('cdma',false);
	}
});

//CDMA SELECTOR LISTENER 
$('#ratingsCDMA').live('click',function(){
	myEzRating.setRatings('cdma',false);		
	myEzRating.setRatings('dma',true);
	if($(this).is(':checked')){
		myEzRating.setRatings('cdma',true);
		myEzRating.setRatings('dma',false);
	}
});


//RATINGS SELECTOR LISTENER
$('#ratingsOn').live('click',function(){
	myEzRating.setRatings('ratings', 0);
	$('#rtgsOnLbl span.ui-button-text').text('Off');
	
	if($(this).is(':checked')){
		$('#rtgsOnLbl span.ui-button-text').text('On');
		myEzRating.setRatings('ratings', 1);
	}
	
	$(this).trigger('refresh');
});

//IMPRESSIONS SELECTOR LISTENER
$('#impressionsOn').live('click',function(){
	myEzRating.setRatings('impressions', 0);
	$('#impsOnLbl span.ui-button-text').text('Off');
	if($(this).is(':checked')){
		myEzRating.setRatings('impressions', 1);
		$('#impsOnLbl span.ui-button-text').text('On');
	}
	$(this).trigger('refresh');
});


//ROUNDED SELECTOR LISTENER
//$('#roundDecimal').on('change',function(){
	//myEzRating.setRatings('rounded',parseInt($(this).val()));
//});


//BOOKS/SURVEY SELECTED
$('#booksList').live('change',function(){
	myEzRating.setRatings('bookId', $('#booksList option:selected').val());
	myEzRating.setRatings('survey', $('#booksList option:selected').text());
	ratingsCtrlButton();
});


//EVENTS LISTERNER TO MANAGE DEMOGRAPHICS
$('#add-custom-demos').live('click',function(){
	if(verifyDemoParams() && countSavedDemos() < 10){
		addCustomDemo();
	}
});

//EVENTS LISTERNER TO MANAGE DEMOGRAPHICS
$('#saveRatingsParams').live('click',function(){
	var params = validateRatingsParams();
	if(params.valid){
		loadDialogWindow('save-ratings', 'ShowSeeker Save Ratings Settings', 300, 180, 0, 0);
	}
});


$('#close-custom-demos').live('click',function(){
	toggleCustomDemosForm('off');
});


//REMOVE DEMOS FROM THE SETTINGS
//$('.tagDemos').live('click',function(){
$('.delSelectedDemo').live('click',function(){	
	removeSelectedDemo($(this).prop('id'));
});


$('#group1').live('change',function(){
	var g = parseInt($('#group1').val());
	var fixedGroups = [4,5,6];
	
	$('#ageFrom1,#ageTo1').prop('disabled',true).siblings('label').addClass('disabledText');	
	$('#saveCustomDemoBtn').prop('disabled',true).addClass('disabledText').removeClass('hander');	
	
	if(g !== 0 && fixedGroups.indexOf(g) === -1){
		$('#ageFrom1,#ageTo1').prop('disabled',false).siblings('label').removeClass('disabledText');	
		$('#saveCustomDemoBtn').prop('disabled',false).removeClass('disabledText').addClass('hander');
	}
});


$('.favDemo').live('click',function(){
	var sDemo 		 = {};
	var favoriteDemo = $(this).val().replace(' ',',').replace(' - ',',');
	var favDemo 	 = favoriteDemo.split(',');
	sDemo.name 		 = favDemo[0];

	if(favDemo[1]){
		sDemo.ageFrom 	= favDemo[1];
		sDemo.ageTo 	= favDemo[2];
	}

	if(!dupeDemo(sDemo)){
		myEzRating.pushDemo(sDemo);
		var d = formatDemos();
		tagDemos(d);
		resetDemos();
		re = true;
	}
	else{
		favDemo = favoriteDemo;
		removeSelectedDemo(favDemo);
	}

	toggleSubmitRatings();
	additionalDemoStatus();
	unselectSavedParameters();
});


$('#del-custom-demos').click(function(){
	if(countSelectedCustomDemos() > 0){
		loadDialogWindow('deletedemos', 'ShowSeeker', 480, 200, 1, 1);
	}
});


//LOAD SAVED SETTINGS
$('#savedRatingsList').on('click','td.td_usrRatings input',function(e){
    e.stopPropagation(); 
});

$('#savedRatingsList').on('click','tr.tr_usrRatings',function(){
	$('tr.tr_usrRatings').find('td').removeClass('savedSetSelected');
	var $sel = $(this);
	$('input.delFavRtgSetting:checkbox').prop('checked',false);
	$sel.find('td').addClass('savedSetSelected');
	
	var favSettings = myEzRating.savedParams;
	$('td.savedSetSelected input:checkbox').prop('checked',true);
	var id 	= parseInt($('td.savedSetSelected input:checkbox').prop('id').replace('favRtgSet', ''));
	var isSavedData = true;
		
	for(var i=0; i < favSettings.length; i++){
		for(var j=0; j<favSettings[i].settings.length; j++){
			if(parseInt(favSettings[i].settings[j].id) === id){
				loadSelectedSettings(favSettings[i].settings[j].ratingsSettings,isSavedData);
				break;
			}
		}
	}	
});


$('i.delFavDemo').live('click',function(){
	deleteDemos($(this).parent().siblings('label').prop('id'));
});


// TOGGLE DELETE SAVED SETTING BUTTON
$('.delFavRtgSetting').live('click',function(){
	var selectSettings = $('input[type="checkbox"].delFavRtgSetting:checked');
	if(selectSettings.length > 0){
		$('.activeDeleteIcon').children('i').removeClass('disabledText');
	}	
	else{
		$('.activeDeleteIcon').children('i').addClass('disabledText');		
	}
});

//TOGGLE RATING COLUMNS
$('input[type=radio][name=toggle-rtgs-col]').on('change',function(){
	toggleRtnColumns($(this).val());	
});


//AGE RANGE VALIDATION
$('#demoAgeFrom, #demoAgeTo').on('change',function(){
	validateRangeSelection();
});


$('.sub-mkt').on('click',function(){
	$('#sub-markets').slideToggle();
	$('.sub-mkt').toggle();
});


$('#submitRatings').live('click',function(){
	saveProposalParams();
});


$('#ezrating-tab-summary').live('click',function(){
	showSummary();
});


$('#months-selector').on('change',function(){
	var selectedMonth = $(this).val();
	filterBooks(selectedMonth,null);
});

$('input.bookType:checkbox').live('click',function(){
	var selectedTypes = [];	
	$('.bookType').each(function(i,item){
		if($(this).is(':checked')){
			selectedTypes.push($(this).prop('id'));	
		}	
	});
	filterBooks(null,selectedTypes);
	
	$(this).trigger('refresh');	
});


$('.headerRtgSettings').live('click', function(){
   	var $icn =  $(this).find('span');
   	if($icn.hasClass('expanded')){
	   $icn.removeClass('expanded').addClass('collapsed');	
   	}
   	else{
	   $icn.removeClass('collapsed').addClass('expanded');	   	
   	}
    $(this).nextUntil('tr.headerRtgSettings').slideToggle(100, function(){});
});

////----------------------------------------------------------------------

//TOGGLE ALL OPTIONS
$('#rtgAllOpt').on('change',function(){
	
	if($(this).is(':checked')){
		 $("input.rtgOpts").prop('checked',true);
	}
	else{
		 $("input.rtgOpts").prop('checked',false);		
	}
});

$("input.rtgOpts").on('change',function(){
	if($("input:checkbox.rtgOpts:checked").length === $("input.rtgOpts").length){
		$('#rtgAllOpt').prop('checked',true);
	}
	else{
		$('#rtgAllOpt').prop('checked',false);		
	}
});

//AVERAGE EVENTS
$('#rtgAverage').on('change',function(){
	if($(this).is(':checked')){
		myEzRating.setRatings('average',1);
		myEzRating.setRatings('project',0);
		$('#rtgProject').prop('checked',false);
		$('#rtgProject').button('refresh');
		activateSurveysMultiSelect('average');
	}
	else{
		myEzRating.setRatings('average',0);
		deactivateSurveysMultiSelect();
	}
});

//PROJECT EVENTS
$('#rtgProject').on('change',function(){
	if($(this).is(':checked')){
		myEzRating.setRatings('project',1);
		myEzRating.setRatings('average',0);
		$('#rtgAverage').prop('checked',false);
		$('#rtgAverage').button('refresh');
		activateSurveysMultiSelect('project');
	}
	else{
		myEzRating.setRatings('project',0);
		deactivateSurveysMultiSelect();
	}
});

//SUMMARY NAVIGATION
$('#summaryBack').on('click',function(){
	var selected = $("#tab-ezrating").tabs("option", "selected") - 1;
	$("#tab-ezrating").tabs('select', selected);	
});

$('#summaryNext').on('click',function(){
	if(validateMultiSelect()){
		var selected = $("#tab-ezrating").tabs("option", "selected");
		$("#tab-ezrating").tabs('select', selected+1);			
	}
});

//RESET SURVEYS
$('#rtgResetBooks').on('click',function(){
	$('#rtgProject,#rtgAverage').prop('checked',false);
	$('.rtgSvyFilter').val('');
	$('.rtgSvyFilter').trigger("search");
	resetBooks();
	myEzRating.setRatings('average',0);
	myEzRating.setRatings('project',0);
	datagridSurveys.resetStringFilter();
	$('#rtgProject,#rtgAverage').button('refresh');
	deactivateSurveysMultiSelect();	
	toggleSubmitRatings();
	$('#rtgSummarySurvey').html('');	
});

$('#resetSelectedDemos').on('click',function(){
	resetDemos();
	resetCustomDemos();
	$('#rtgSummaryDemo').html('');
	myEzRating.setRatings('demos',[]);
	additionalDemoStatus();		
	toggleSubmitRatings();
	/*$('#demoGroup').val(0).trigger('change');
	$('#ageFrom').val(25).trigger('change');
	$('#ageFrom').val(54).trigger('change');*/
});




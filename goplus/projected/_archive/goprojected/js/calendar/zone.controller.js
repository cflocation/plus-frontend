////////////////////////////////////////////
//   REFLECT THE ZONE SELECTION IN PLUS  //
//////////////////////////////////////////

$('#zones').change(function(){
	//SELECTING THE ZONE FROM THE PARENT WINDOW	
	try{
		sswin.mixTrack("Projected - Zone Select",{"zoneId":$('#zones').val()});
	}
	catch(e){}
	$.each(sswin.marketzones,function(i,zone){
		if(parseInt(zone.id) === parseInt($('#zones').val())){
			$('#tz').val(zone.abbreviation);
		}
	});
	r = zoneSynch();
	$('#updatinggmsg').show();
	$('#boxBody').hide();
	$('#premiefinales').submit();
});

function zoneSynch(){
	zone		= $('#zones').val();	
	if(sswin.allMarkets === undefined){
		$('#zone-selector', sswin.document).val(zone);		
	}
	else{
		sswin.autoSelectMarketAndZone(zone);
	}
	if(parseInt(sswin.zoneid) !== parseInt(zone)){
		sswin.zoneSelected();
	}	
	return true;
}
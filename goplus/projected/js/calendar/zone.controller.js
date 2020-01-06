////////////////////////////////////////////
//   REFLECT THE ZONE SELECTION IN PLUS  //
//////////////////////////////////////////

$(document).on('change','#zones',function(e){
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
	var zone = $('#zones').val();
	var sDate = Date.parse(startDate).toString("MM/dd/yyyy");
	var eDate = Date.parse(endDate).toString("MM/dd/yyyy");
	$('#boxBody').empty();
	$("#ctlMessage").show();


	var url = 'https://projectedcal.showseeker.com/projected?userid='+userid;
	url 	+= '&apiKey='+apiKey+'&zoneid='+zone+'&startDate='+sDate;
	url 	+= '&endDate='+eDate+'&sTime='+sTime+'&eTime='+eTime+'&tokenid='+tokenid;
	
	$.ajax({
			crossOrigin: true,
			url:url,
			type:'GET',
			success: function(result){
			dataRespones = result;
			$('#boxBody').empty();
			$("#ctlMessage").hide();
			loadGrid();
			updateGrid();
			sswin.autoSelectMarketAndZone(zone);			
			}
		});
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
function autoSelectMarketAndZone(znid){
 	
 	if(znid == 0 || znid == null){
 		return;
 	}
   
	$.each(sswin.allMarkets, function(h, market) {

	      $.each(market.zones, function(i, value) {

	          if (value.id == znid) {
					  // proposal located in a different market
					  if( $('#usrmarkets').val() != market.id ){

							$('#usrmarkets').val(market.id);
							updateZonesList(market, znid);

					  // proposal is located in market
					  }else if( $('#zones').val() != znid){

						$('#zones').val(znid);

					  }

					  return;
	          }

	      });

	});
	
}

function loadmarkets(){
	
	if(mktzones != undefined){

		selectedmkt = $('#market-selector', sswin.document).val();		
		znid 		= $('#zone-selector', sswin.document).val();		

		$.each(sswin.markets,function(i,row){
			if(selectedmkt == row.id){
				$('#usrmarkets').append('<option value='+row.id+' selected="selected">'+row.name+'</option>');
			}
			else{
				$('#usrmarkets').append('<option value='+row.id+'>'+row.name+'</option>');
			}
		});

		$.each(sswin.allMarkets,function(i,row){
			if(selectedmkt == row.id){
				updateZonesList(row, znid);
				return;
			}

		});
	}
}


function updateZonesList(thismarket, znid){
	//console.log(thismarket);
	$('#zones')[0].options.length = 0;	
	$.when(zoneListBuilder(thismarket.zones, 'zones')).then($('#zones').val(znid));
	return;

}


//appends list of Zones to a Select Box
function zoneListBuilder(zns, selectorList){

	$.each(zns, function(i, value) {
	
		if (value.isdma == "NO") 
			$('#'+selectorList).append($("<option></option>").attr("value", value.id).text(value.name));
	});

}
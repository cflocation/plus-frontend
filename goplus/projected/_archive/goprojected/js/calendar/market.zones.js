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
function zoneListBuilder_OK(zns, selectorList){

	$.each(zns, function(i, value) {
	
		if (value.isdma == "NO") 
			$('#'+selectorList).append($("<option></option>").attr("value", value.id).text(value.name));
	});

}


function zoneListBuilder(){
	
	$('#zones').html('');
	
	var selectedDma 	= parseInt($('#dma-selector', sswin.document).val());
	var selectedZone 	= $('#zone-selector', sswin.document).val();
	var ssZones = sswin.zonesArray;	
	if(sswin.roles.ezRatings || parseInt(sswin.corpid) === 46){
		for(key in ssZones){
			if(ssZones[key].dmaId === selectedDma && ssZones[key].isdma === 'NO'){
				$('#zones').append('<option value='+key+'>'+ssZones[key].name+'</option>');					
			}
		}
	}
	else{
		for(key in ssZones){
			if(ssZones[key].isdma === 'NO'){
				$('#zones').append('<option value='+key+'>'+ssZones[key].name+'</option>');					
			}
		}		
	}
	
	var options = $('#zones option');
	var arr 	= options.map(function(_, o) { return { t: $(o).text(), v: o.value }; }).get();

	arr.sort(function(o1, o2) { return o1.t > o2.t ? 1 : o1.t < o2.t ? -1 : 0; });
	options.each(function(i, o) {
	  o.value = arr[i].v;
	  $(o).text(arr[i].t);
	});	
	
	$('#zones').val(selectedZone);
	return false;
};
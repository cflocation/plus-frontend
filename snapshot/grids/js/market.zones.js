
function loadmarkets(){
	var mktslen = sswin.allMarkets.length;
	
	if($('#usrmarkets option').length == 0){
		var selectedmkt = $('#market-selector', sswin.document).val();
		$.each(sswin.allMarkets,function(i,row){
			$('#usrmarkets').append('<option value='+row.id+'>'+row.name+'</option>');
		});
		$('#usrmarkets').val(selectedmkt);
	}
	//LOAD MARKET AND ZONES
	if(mktslen > 1){
		$('#usrmarkets').parent('.row').show();
	}
	return false;
};

function setDmas(){
	$('#usrdmas').html('');
	var selectedDma = $('#dma-selector', sswin.document).val();
	if(parseInt(sswin.corpid) !== 46){
		for(key in sswin.uniqueDmaList){
			$('#usrdmas').append('<option value='+key+'>'+sswin.uniqueDmaList[key].name+'</option>');
		}
	}
	else{
		for(var d =0; d<sswin.uniqueDmaList.length; d++){
			$('#usrdmas').append('<option value='+sswin.uniqueDmaList[d].id+'>'+sswin.uniqueDmaList[d].name+'</option>');
		}
		$('#usrdmas').parent('.row').show();
	}	
	$('#usrdmas').val(selectedDma);

	if('ezRatings' in sswin.roles || parseInt(sswin.corpid) === 46){
		if(sswin.roles.ezRatings){
			$('#usrdmas').parent('.row').show();
		}
	}
	return false;
};

function setZones(){
	
	$('#zones').html('');
	//$('#usrmarkets').val($('#market-selector', sswin.document).val());
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


		
function zoneSynch(){
	var newZone		= $('#zones').val();
	var newMktid	= $('#usrmarkets').val();
	//sswin.synchMarketAndZone(newMktid,newZone);
	sswin.autoSelectMarketAndZone(newZone);	
	return true;
};	

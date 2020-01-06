$('#zone-selector').change(function(){
	zoneSelected();
});

function autoSelectMarketAndZone(znid){
	
 	if(znid){
	 	var zId = parseInt(znid);
	 	if(zId !== 0 ){
	   
			$.each(marketzones, function(h, zone){
		
				//FINDS THE MARKET
				if(parseInt(zone.id) === zId){ 
					
					userSettings.lastZoneId = zId;
					zoneid = zId;
						
					if(!(zId in zonesArray)){
						$('#market-selector').val(zone.marketId).change();
					}
					else{
						var mapDMA 			= parseInt(zonesArray[zId].dmaId);
						var selectedMarket 	= parseInt($('#market-selector').val());
						var selectedDma		= parseInt($('#dma-selector').val())
						var selectedZone 	= parseInt($('#zone-selector').val())
						
						//DIFFERENT MARKET
						if(selectedMarket !== zone.marketId){
							$('#market-selector').val(zone.marketId).change();
						}//DIFFERENT DMA
						else if(selectedDma !== mapDMA){
							$('#dma-selector').val(mapDMA).change();
						}//DIFFERENT ZONE
						else if(selectedZone !== zId){
							$('#zone-selector').val(znid).change();
						}
					}
					
				}
			});
		}
	}
	return false;
};


function compare(a,b) {
	if (a.name < b.name)
		return -1;
	if (a.name > b.name)
		return 1;
	return 0;
};


function dmaSynch(zoneId){
	if(roles.ezRatings){
		if(!$.isEmptyObject(zonesArray) && zonesArray[zoneId] !== undefined){
			$('#dma-selector').val(zonesArray[zoneId].dmaId).trigger('refresh');
			$('#dma-books').text(' ( '+$('#dma-selector option:selected').text()+' )');
		}
	}
	return false;
};

//BUILD LIST OF ZONE OPTIONS
function filterZones(){
	var dmaId  = parseInt($('#dma-selector').val());
	userSettings.lastDMAId	= dmaId;
	$('#zone-selector').html('');
	
	if(parseInt(corpid) === 46){
		$.when(zoneListBuilder(marketid, 'zone-selector',dmaId))
		.then(broadcastZone(marketid, dmaId))
		.then($('#zone-selector').val(zoneid).change());		
	}
	else{
		$.when(zoneListBuilder(marketid, 'zone-selector',dmaId))
		.then(broadcastSeparator())
		.then(broadcastOption('zone-selector', dmaId))
		.then($('#zone-selector').val(zoneid).change());
	}


	if(ezgridsOpen){
		if(!ezgrids.closed){
			ezgrids.setZones();
		}
	}
};


function getZonesByMarketId(id){

	if(parseInt(id) === 0 || id === "DMA"){
        return;
    }	
	$('#zone-selector,#dma-selector').html('');
		
	var dmaId;
	var lastZoneId		= userSettings.lastZoneId;
	var dmasList  		= [];
	var zoneMap 		= [];
	var DMAZones 		= [];
	var mktId			= parseInt(id);
	var newZoneId		= 0;



	//SETTING UP VARIABLES WITH NEW ZDMAS AND ZONES
    $.each(marketzones, function(h, zone) {
    	if(parseInt(zone.marketId) === mktId){
	    	if (zone.isdma === "NO") {
				//allzones.push(zone);
				if(newZoneId === 0){
					newZoneId = zone.id;
				}
				DMAZones.push({'id':zone.id,'name':zone.name,'syscode':zone.sysCode,'dmaId':zone.dmaId})
			}
			else{
				if(dmasList.indexOf(zone.dmaId) === -1){
					dmasList[zone.dmaId] = zone;
				}
			}
			zoneMap[zone.id] = zone;
    	}
    });


	
	//SPECTRUM REACH USERS
	if(parseInt(corpid) === 46){
	    $.each(allMarkets, function(h, region) {
	    	if(parseInt(region.id) === parseInt(id)){
		    	uniqueDmaList = region.dmas;
	    	}
	    });
	    //datagridZones.populateDataGrid(DMAZones);
	}
	else{
		//GETTING LIST OF ZONES AND DMAS BY REGION
		uniqueDmaList = dmasList.reduce(function(acc, cur, i) {
			acc[i] = cur;
			return acc;
		}, {});
	}
	
	zonesArray = zoneMap.reduce(function(acc, cur, i) {
		acc[i] = cur;
		return acc;
	}, {});
	

	
	userSettings.lastMarketId 	= mktId;

	if(zonesArray[lastZoneId] !== undefined){
		dmaId 	= zonesArray[lastZoneId].dmaId;
	}
	else{//SELECTS FIRST ZONE FROM THE NEW REGION
		lastZoneId	= newZoneId;
		dmaId 	= zonesArray[lastZoneId].dmaId;
	}
	
	userSettings.lastZoneId = lastZoneId;
	userSettings.lastDMAId	= dmaId;	
		
	//SPECTRUM REACH 
	if(parseInt(corpid) === 46){
		$.when(populateDmas(dmaId))
		.then(zoneListBuilder(mktId, 'zone-selector',dmaId))
		.then(broadcastZone(mktId, dmaId))
		.then(function(){
			$('#zone-selector').val(lastZoneId).trigger('change');
		});
	}
	else{
		$.when(dmaListBuilder(mktId, 'dma-selector', dmaId))
		.then(zoneListBuilder(mktId, 'zone-selector',dmaId))
		.then(broadcastSeparator())
		.then(function(){	if(roles.ezRatings){
								broadcastOption('zone-selector', dmaId);
							}
							else{
								dmaListBuilder(mktId, 'zone-selector');
							}
						})
		.then(function(){
			$('#zone-selector').val(lastZoneId).trigger('change');
		});
	}
	
	if(ezgridsOpen){
		if(ezgrids){
			$.when(ezgrids.setDmas())
			.then(ezgrids.setZones());
		}
	}

	return false;
};


function ezGridsSynchZones(zoneid){
	try{
		if(isEzgridsOpen){
			var selectedDma = $('#dma-selector').val();
			
			if($(ezgrids.document).contents().find('#usrdmas').val()!== selectedDma){
				$(ezgrids.document).contents().find('#usrdmas').val(selectedDma).change();
			}
				
			if(parseInt($(ezgrids.document).contents().find('#zones').val())!== parseInt(zoneid)){
				$(ezgrids.document).contents().find('#zones').val(zoneid).change();
				$(ezgrids.document).contents().find('#ezgridsform').submit();
			}
		}
	}catch(err){}
}



function isEzgridsOpen(){
    if (!ezgrids) {
	        return false; //'EzGrids' has never been opened!";
    } else {
        if (ezgrids.closed) { 
            return false; //'EzGrids' has been closed!";
        } else {
            return true;// 'EzGrids' has not been closed!";
        }
    }	
}



function setAllZones(){
	if(allzones.length <= 0){	
		$.each(allMarkets, function(h, market){
			if(parseInt(market.id) === parseInt(marketid)){
				$.each(market.zones, function(i, zone){
					if (zone.isdma === "NO"){
						allzones.push(zone);
					}
				});
				return false;
			}
		});
	}
	
	allzones.sort(compare);	
	return allzones;
};


function getMarketZones(mktid){
	$.each(marketzones, function(h, thismarket) {
		if (thismarket.marketId == mktid) {
			return thismarket;
		}
	});
};


//updates the list of zones for the settings panel
function updateZonesList(thismarket, znid){
	var z;
	$('#zone-selector,#dma-selector').html('');	
	
	var dmaId = zonesArray[znid].dmaId;
	
	z = zoneListBuilder(thismarket, 'zone-selector',dmaId);
	z = broadcastSeparator();
	
	if(roles.ezRatings){
		z = broadcastOption('zone-selector', dmaId);			
	}
	else{
		z = dmaListBuilder(thismarket, 'zone-selector');	
	}

	z = dmaListBuilder(thismarket, 'dma-selector');	
	
	$('#zone-selector').val(znid);
	zoneSelected();      
	return;
};

// Updates the list of Zones and DMAs for the clone of zones
function updateCloneZonesList(thismarket){
	return false;
};


//appends list of Zones to a Select Box
function zoneListBuilder(marketId, selectorList,dmaId){
	
	if((roles.ezRatings || parseInt(corpid) === 46) && dmaId !== undefined){		
		$.each(marketzones, function(h, zone) {
			if (parseInt(zone.marketId) === parseInt(marketId) && zone.isdma === "NO" && parseInt(zone.dmaId) === parseInt(dmaId)){
				$('#'+selectorList).append($("<option></option>").attr("value", zone.id).text(zone.name ));
			}
		});		
	}
	else{
		$.each(marketzones, function(h, zone) {
			if (parseInt(zone.marketId) === parseInt(marketId) && zone.isdma === "NO"){
				$('#'+selectorList).append($("<option></option>").attr("value", zone.id).text(zone.name));
			}
		});
	}
	
	return false;
};


//appends list of DMAs to a Select Box
function dmaListBuilder(marketId, selectorList,dmaId){
	$.each(marketzones, function(h, zone){
		if (parseInt(zone.marketId) === parseInt(marketId) && zone.isdma === "YES"){
			if(selectorList === 'dma-selector'){
				$('#'+selectorList).append($("<option></option>").attr("value", zone.dmaId).text(zone.name));
			}
			else{
				$('#'+selectorList).append($("<option class='dma-select dma-select-title'></option>").attr("value", zone.id).text(zone.name));
			}
		}
	});
      
	$('#'+selectorList).trigger('create');

	if(dmaId !== undefined && selectorList === 'dma-selector'){
		$('#dma-selector').val(dmaId);
	}
	return false;
};


function broadcastOption(selectorList, dmaId){
	if(!$.isEmptyObject(uniqueDmaList)){
		$('#'+selectorList).append($("<option class='dma-select dma-select-title'></option>").attr("value", uniqueDmaList[dmaId].id).text(uniqueDmaList[dmaId].name));
		$('#'+selectorList).trigger('create');
	}
	return false;
};

//appends list of DMAs to a Select Box
function broadcastZone(marketId, dmaId){
	$.each(marketzones, function(h, zone){
		if(parseInt(zone.marketId) === parseInt(marketId) && zone.isdma === "YES" && parseInt(zone.dmaId) === parseInt(dmaId)){
			$('#zone-selector').append($("<option class='dma-select dma-select-title'></option>").attr("value", "DMA").text("===== Broadcast ====="));
			$('#zone-selector').append($("<option class='dma-select dma-select-title'></option>").attr("value", zone.id).text(zone.name));
			return;
		}
	});
      
	$('#zone-selector').trigger('create');
	return false;
};

//appends list of DMAs to a Select Box
function populateDmas(dmaId){
	for(var d=0; d<uniqueDmaList.length; d++){
		$('#dma-selector').append($("<option></option>").attr("value", uniqueDmaList[d].id).text(uniqueDmaList[d].name));
	};
	$('#dma-selector').trigger('create');
	$('#dma-selector').val(dmaId);
	return false;
};




//appends list of DMAs to a Select Box
function broadcastSeparator(){
	if(!$.isEmptyObject(uniqueDmaList)){	
		$('#zone-selector').append($("<option class='dma-select dma-select-title'></option>").attr("value", "DMA").text("===== Broadcast ====="));
	}
	return false;
};


/* button/select events */
function zoneSelected(ezSynch) {
	marketid 	= $('#market-selector').val();
    zone 		= $('#zone-selector :selected').text();
    zoneid 		= $('#zone-selector').val();

    if(zoneid === null || zoneid === "DMA"){
		zone 		= '';
		zoneid 	= 0;
		return;
    }

    populateNetworkList(zoneid);
    
    datagridSearchResults.emptyGrid();

    //set the user settings and save them
    userSettings.lastZoneId = zoneid;
    saveUserSettings();
	
	if(isEzgridsOpen()){
		ezGridsSynchZones(zoneid);
	}
		
	gridOpenBtnState();
}


function gridOpenBtnState(){
	$('#gridsOpenBtn').prop('disabled',false);
	
	if($('#zone-selector option:selected').hasClass('dma-select')){
		$('#gridsOpenBtn').prop('disabled',true);
	}
	$('#gridsOpenBtn').button('refresh');	
}


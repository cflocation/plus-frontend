function marathonsSearch(params){
	var marathonData = {};
	var nets = [];
	var g = [];
	var keywords = [];
	var titles = [];
	var actors = [];
	
	for(var i =0; i <params.networks.length; i++){
		nets.push(params.networks[i].id);
	}

	$.each(params.genre,function(i, val){
		g.push(val);
	});
	
	for(var i =0; i <params.searchKeywordsArray.length; i++){
		keywords.push(params.searchKeywordsArray[i].id);
	}
	for(var i =0; i <params.searchTitlesArray.length; i++){
		titles.push(params.searchTitlesArray[i].id);
	}
	for(var i =0; i <params.searchActorsArray.length; i++){
		actors.push(params.searchActorsArray[i].id);
	}	



	marathonData.premieres	= params.premiere;
	marathonData.genres		= g;
	marathonData.keywords 	= keywords;
	marathonData.titles 	= titles;
	marathonData.actors 	= actors;
	marathonData.days 		= params.days	;
	marathonData.networks 	= nets;
	marathonData.endTime	= params.endtime;
	marathonData.startTime	= params.starttime;
	marathonData.endDate	= String(params.enddate).substr(0,10);
	marathonData.startDate	= String(params.startdate).substr(0,10);
	marathonData.returnCount= 8000;
	marathonData.zoneId		= params.zoneid;
	marathonData.timeZone	= params.timezone;
	
	$.ajax({
        type:'post',
		url: apiUrl+"solr/marathons",
		dataType:"json",
		headers:{"Api-Key":apiKey,"User":userid},
		contentType: 'application/json',
		data: JSON.stringify(marathonData),
		success:function(data){
			doSearchPopulation(data, params);
		}
	});
}
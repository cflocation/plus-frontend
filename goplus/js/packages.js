function packageFlyerInfo(url){
	var flyerDetails = window.open(url, '_blank');
	return false;
}
function buildPackageString(ids){

	var re = "&fq=";
	//loop over and build a solr freindly list for networks
	$.each(ids, function(i, val) {
		re += 'packageid:' + val + '+';
	});
	return re;
}


function injectPackage(id, packageName){
	
	var title = id;

	if(packageName){
		title = decodeURIComponent(packageName);
	}

	mixTrack('Packages - Load',{"packageId":id[0],"package":title});
	
	
	//reset all the result stuff
	dataSourceResult = [];
	dataSourceResultCounter = 0;
	$("#search-result-counter-total").html('0');
	$("#search-result-counter-current").html('0');
    sidebarOpen();
	menuSelect('proposal-build');
	togglesearchpanels();	
    setSearchCountLabel(0);
	dialogSearching();
	

    datagridSearchResults.emptyGrid();
	//var packages = buildPackageString(id);
	var params   = solrSearchParamaters();

	var stime = String(params.starttime).substr(0, 5);
	var etime = String(params.endtime).substr(0, 5);
	var sdate = new Date().toString("yyyy-MM-dd");
	var edate = new Date().add(500).days().toString("yyyy-MM-dd");

	var sd    = new Date().toString("MM/dd/yyyy");
	var ed    = new Date(sd).add(56).days().toString("MM/dd/yyyy");
	var tmpsd = $("#date-start").val();
	var tmped = $('#date-end').val();

	if(sd != tmpsd){
		sdate = new Date(tmpsd).toString("yyyy-MM-dd");
	}
	 
	if(ed != tmped){
		edate = new Date(tmped).add(1).days().toString("yyyy-MM-dd");
	}

	var stations = solrNetworkFormatter(params.networks);
	stations	 = String(stations).replace(/stationnum:/g,"").replace("&fq=0","");
	stations     = stations.substr(1,stations.length);
	stations     = stations.substr(0,stations.length-1);	
	stations	 = stations.replace(/\+/g,",");
	stations	 = stations.replace("fq=","");
	stations     = stations.split(',');
	
	var thisURL = apiUrl+"plus/package";
	

	$.ajax({
		url: thisURL,
		type:"post",
		headers: {
			"content-type": "application/json"
		},
		data:JSON.stringify({"packageId":id,'startDate':sdate,'endDate':edate,'startTime':stime,'endTime':etime,'tz':params.timezone,'stations':stations}),
		dataType:"json",
		success:function(resp){
			var re = datasourceBuildGrid(resp.response.docs,'Package');
			var searchnum = resp.response.numFound;
			
			if (searchnum == 0) {
	           loadMessage('noresults');
	        }
		},
		error:function(){

		}
	});
}

function customPackageNetworkCheck(id){
	var pkg      = datagridCustomPackage.getItemById(id);
	var params   = solrSearchParamaters();
	var zoneNets = [];

	$.each(params.networks, function(i, net){
		if(net.id != '0')
			zoneNets.push(net.id);
	});

	//for(station of pkg.stations){
	for(var i=0; i<pkg.stations.length; i++){
		var station = pkg.stations[i];
		if(station.length==2 && zoneNets.indexOf(station[0]) === -1 && zoneNets.indexOf(station[1]) === -1){	
			loadDialogWindow('custom-package-missing-network','ShowSeeker Plus',450,150,0,true,'',id);		
			return false;
		} else if(station.length==1 && zoneNets.indexOf(station[0]) === -1){
			loadDialogWindow('custom-package-missing-network','ShowSeeker Plus',450,150,0,true,'',id);		
			return false;
		}
	}

	injectCustomPackage(id);
}

function injectCustomPackage(id){
	//reset all the result stuff
	closeAllDialogs();
	dataSourceResult = [];
	dataSourceResultCounter = 0;
	$("#search-result-counter-total").html('0');
	$("#search-result-counter-current").html('0');
    sidebarOpen();
	menuSelect('proposal-build');
	togglesearchpanels();	
    setSearchCountLabel(0);
	dialogSearching();
	

    datagridSearchResults.emptyGrid();
	var params   = solrSearchParamaters();
	var postData = {};

	postData['packageId'] = id;
	postData['startDate'] = params.startdate.split('T')[0];
	postData['endDate']   = params.enddate.split('T')[0];
	postData['startTime'] = params.starttime;
	postData['endTime']   = params.endtime;
	postData['marketId']  = params.marketid;
	postData['timeZone']  = params.timezone;
	postData['zoneId']    = params.zoneid;
	postData['stations']  = [];

	$.each(params.networks, function(i, net){
		if(net.id != '0')
			postData['stations'].push(net.id);
	});

	postData['tz']       = postData['timeZone'];

	$.ajax({
		url: apiUrl+"custom/package",
		type:"post",
		headers: {
			"content-type": "application/json",
			"Api-key":apiKey,
			"User":userid
		},
		data:JSON.stringify(postData),
		dataType:"json",
		success:function(resp){
			var re = datasourceBuildGrid(resp.response.docs,'Package');
			var searchnum = resp.response.numFound;

			$("#dialog-custom-packages").dialog("close");
			
			if (searchnum == 0) {
	           loadMessage('noresults');
	        }
		},
		error:function(){

		}
	});
}


function searchCustompackage(){
	var searchTxt = $('#custompkg-search-text').val().toLowerCase();
	datagridCustomPackage.updateFilter(searchTxt);
}


var datagridCustomPackage;

function loadCustomPackageList(){
	var params   = solrSearchParamaters();
	var postData = {};

	postData['startDate'] = params.startdate.split('T')[0];
	postData['endDate']   = params.enddate.split('T')[0];
	postData['startTime'] = params.starttime;
	postData['endTime']   = params.endtime;
	postData['endTime']   = params.endtime;
	postData['marketId']  = params.marketid;
	postData['officeId']  = userOfficeId
	postData['timeZone']  = params.timezone;
	postData['zoneId']    = params.zoneid;
	postData['networks']  = [];

	$.ajax({
		url: apiUrl+ "regional/packagelist/"+corpid,
		type:"post",
		headers: {
			"content-type": "application/json",
			"Api-key":apiKey,
			"User":userid
		},
		data:JSON.stringify(postData),
		dataType:"json",
		success:function(data){
			var html = '';

			data.packages.forEach(function(p){
				p.id       = p.packageId;
				p.userInfo = data.userInfo[p.userId];
			});

			datagridCustomPackage = new DatagridCustomPackage();
			datagridCustomPackage.populateDataGrid(data.packages);
			datagridCustomPackage.renderGrid();
		},
		error:function(){

		}
	});
}

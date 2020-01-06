

function injectPackageOld(id){

	//reset all the result stuff
	dataSourceResult = [];
	dataSourceResultCounter = 0;
	$("#search-result-counter-total").html('0');
	$("#search-result-counter-current").html('0');

	menuSelect('proposal-build');
	dialogSearching();


	var packages = buildPackageString(id);
	var params = solrSearchParamaters();

	var startdateTZ = "tz_start_" + params.timezone;

	var startdate = new Date().toString("yyyy-MM-ddTHH:mm:ssZ");
	var enddate = new Date().add(500).days().toString("yyyy-MM-ddTHH:mm:ssZ");



	var dates = '&fq=' + startdateTZ + ':[' + startdate + ' TO ' + enddate + ']';
	//var enddate = new Date(enddate).add(500).days().toString("yyyy-MM-ddTHH:mm:ssZ");


	var url = 'http://10.15.10.102:8983/solr/gracenote/select/?q=*%3A*&version=2.2&start=0&indent=on&wt=json';
	url += "&rows=500";
	//url += "&fq=packageid:"+id;
	url += dates;
	//var dates = '&fq=' + startdate + ':[' + params.startdate + ' TO ' + params.enddate + ']';
	url += packages;
	//add the networks
	url += solrNetworkFormatter(params.networks);

	var xrl = url;
	//var xrl = 'services/search.php?xrl='+encodeURIComponent(url);

	var url = "/services/1.0/search.php";	
	
	$.when(buildToken(url)).done(function(token){
		url = token['url']+"&xrl="+encodeURIComponent(xrl);

		$.getJSON(url, function(data) {
		//var xrl = 'services/search.php?xrl='+encodeURIComponent(url);
		//$.getJSON(xrl, function(data) {
		var re = datasourceBuildGrid(data.response.docs,'Package');
		});
	});

}



function buildPackageString(ids){

	var re = "&fq=";
	//loop over and build a solr freindly list for networks
	$.each(ids, function(i, val) {
		re += 'packageid:' + val + '+';
	});
	return re;
}



function injectPackage(id){

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
	var packages = buildPackageString(id);
	var params = solrSearchParamaters();

	var startdateTZ = "tz_start_" + params.timezone;
	var starttime = "start_" + params.timezone;
	var stime = String(params.starttime).substr(0, 5);
	var etime = String(params.endtime).substr(0, 5);

	var startdate = new Date().toString("yyyy-MM-ddTHH:mm:ssZ");
	var enddate = new Date().add(500).days().toString("yyyy-MM-ddTHH:mm:ssZ");

	var sdate = new Date().toString("yyyy-MM-dd");
	var edate = new Date().add(500).days().toString("yyyy-MM-dd");

	var dates = '&fq=' + startdateTZ + ':[' + startdate + ' TO ' + enddate + ']';
	var times = '&fq=' + starttime + ':[' + params.starttime + ' TO ' + params.endtime + ']';



	 var sd = new Date().toString("MM/dd/yyyy");
	 var ed = new Date(sd).add(56).days().toString("MM/dd/yyyy");
	 var tmpsd = $("#date-start").val();
	 var tmped = $('#date-end').val();

	 if(sd != tmpsd){
		 sdate = new Date(tmpsd).toString("yyyy-MM-dd");
	 }
	 
	 if(ed != tmped){
		 edate = new Date(tmped).add(1).days().toString("yyyy-MM-dd");
	 }



	var url = 'http://10.15.10.102:8983/solr/gracenote/select/?q=*%3A*&version=2.2&start=0&indent=on&wt=json';
	url += "&rows=5000";

	url += dates;
	url += times;
	//var dates = '&fq=' + startdate + ':[' + params.startdate + ' TO ' + params.enddate + ']';
	url += packages;
	//add the networks
	url += solrNetworkFormatter(params.networks);

	//var xrl = 'services/search.php?xrl='+encodeURIComponent(url);
	//var xrl2 = 'services/search.php?xrl='+url;
	var xrlT = 'services/search.php?xrl='+url;
	
	var stations = solrNetworkFormatter(params.networks);
	stations	 = String(stations).replace(/stationnum:/g,"").replace("&fq=0","");
	stations     = stations.substr(1,stations.length);
	stations     = stations.substr(0,stations.length-1);	
	stations	 = stations.replace(/\+/g,",");
	stations	 = stations.replace("fq=","");	
	
	var xrl = 'services/search.packages.php?id='+id+'&startdate='+sdate+'&enddate='+edate+'&stime='+stime+'&etime='+etime+'&tz='+params.timezone+'&stations='+stations;

	$.getJSON(xrl, function(data) {
		var re = datasourceBuildGrid(data.response.docs,'Package');
        var searchnum = data.response.numFound;

        if (searchnum == 0) {
            loadMessage('noresults');
        }		
		
	});
}
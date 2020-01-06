/* GET THE PARAMATERS */

function solrSearchParamaters(){
	var startdate 		= $("#date-start").val();
	var enddate 		= $("#date-end").val()+" 23:59:59";
	var starttime 		= "01/01/2000 "+$("#time-start").val();
	var endtime 		= "01/01/2000 "+$("#time-end").val();
	var re 				= new Object();
	
	re.calendar 		= $('input:radio[name=calendar-mode-selector]:checked').val();
	re.days 				= arrayDays;
	re.decades 			= $("#decade-options").val();
	re.enddate 			= new Date(enddate).toString("yyyy-MM-ddTHH:mm:ssZ");	
	re.endtime 			= new Date(endtime).toString("HH:mm:ss");
	re.genre 			= arrayGenre;		
	re.marathons 		= $("#more-marathons").attr('checked');
	re.marketid 		= $('#market-selector').val();
	re.networks 		= arrayNetworks;
	re.premiere 		= arrayPremiere;
	re.scheduleweeks 	= $("#schedule-weeks").val();
	re.schedulespots 	= $("#schedule-spots").val();
	re.schedulerate 	= $("#schedule-rate").val();	
	re.searchType 		= searchType;
	re.searchTitlesArray 	= datagridTitlesSelected.getSelectedData();
	re.searchKeywordsArray 	= datagridKeywords.getSelectedData();
	re.showtype 		= $('#showtype-mode1 input:checkbox:checked').val();
	re.sports 			= $('#sports-mode input:checkbox:checked').val(); 
	re.startdate 		= new Date(startdate).toString("yyyy-MM-ddTHH:mm:ssZ");
	re.starttime 		= new Date(starttime).toString("HH:mm:ss");
	re.timezone 		= timezone;
	re.years 			= $("#year-options").val();
	re.zoneid 			= $("#zone-selector").val();
	re.zone 			= $('#zone-selector option:selected').text();

		
	re.rows = 5000;

	return re;
}

/* END GET THE PARAMATERS */






//build the main search string
function solrSearchString(params, type) {

	var startdate = "tz_start_" + params.timezone;
	var enddate = "tz_end_" + params.timezone;
	var starttime = "start_" + params.timezone;
	var startday = "day_" + params.timezone;


	//fix the end time by subtracting 1 minute
	//var startdate = Date.parse(params.startdate).toString("yyyy/MM/dd");
	var endfix = Date.parse("01/01/1980 " + params.endtime).add({minutes: -1});
	var endfixFormat = Date.parse(endfix).toString("HH:mm:ss");

	var dates = '&fq=' + startdate + ':[' + params.startdate + ' TO ' + params.enddate + ']';
	var times = '&fq=' + starttime + ':[' + params.starttime + ' TO ' + endfixFormat + ']';


	var zrl;
	
	if(params.searchType === "title") {
		zrl = 'http://snapshot.prod.showseeker.com:8983/solr/snapshot/select/?q=*%3A*&version=2.2&start=0&indent=on&wt=json';	
	}
	else{
		zrl = 'http://snapshot.prod.showseeker.com:8983/solr/snapshot/select/?q=*%3A*&version=2.2&start=0&indent=on&wt=json';		
	}

	zrl += "&rows=5000";
	zrl += dates;
	zrl += times;
	zrl += solrNetworkFormatter(params.networks);


//To Be Announced
	//if genre 0 ignore it
	var genrecnt = Object.keys(params.genre).length;
	if(genrecnt > 0){

		zrl += solrGenreFormatter(params.genre);
	}


	//premieres 0 ignore
	var premierecnt = params.premiere.length;
	if(premierecnt > 0){
		zrl += solrPremiereFormatter(params.premiere);
	}



	//if showtype type undefined ignore
	if(typeof params.showtype != "undefined"){
		zrl += solrShowtypeFormatter(params.showtype);
	}


	//if sports type undefined ignore
	if(typeof params.sports != "undefined"){
		zrl += solrSportFormatter(params.sports);
	}



	//if 7 days ignore
	var daycnt = params.days.length;
	if(daycnt != 7){
		zrl += solrDaysOfWeekFormatter(params.days, params.timezone);
	}


	if(params.searchType === "title") {
		zrl += solrTitleSearchFormatter(params.searchTitlesArray);
	}


	if(params.searchType === "keyword") {
		zrl += solrKeywordSearchFormatter(params.searchKeywordsArray);
	}

	if(params.searchType === "actor") {
		zrl += solrActorSearchFormatter(params.searchActorsArray);
	}


	if(params.showtype === "new") {
		//var exclude = new Array("sports non-event", "sports talk", "newsmagazine", "sports event", "news");
		var exclude = new Array([]);
		var removelist = solrExcludeFormatter(exclude);
		zrl += removelist;
	}


	if(type === "genre"){
		zrl += "&fl=genre1,genre2";
	}
	else{
		zrl += "&fl=id,epititle,genre1,genre2,descembed,showtype,stationnum,callsign,stars,showid,isnew,tmsid,stationnum,title,new,live,stationname,duration,rating,premierefinale," + startdate + "," + starttime + "," + startday + "," + enddate;
	}

	if(type === "full") {
		zrl += "&group=true&group.field=title&fl=title,showid";
		zrl += "&sort=sort asc";
	}

	//zrl += "&json.wrf=callback";
	
	
	return encodeURI(zrl);
	
}



//do group search

function solrSearchGroup(params, type) {

	var startdate 		= "tz_start_" + params.timezone;
	var enddate 		= "tz_end_" + params.timezone;
	var starttime 		= "start_" + params.timezone;
	var startday 		= "day_" + params.timezone;
	var endfix 			= Date.parse("01/01/1980 " + params.endtime).add({minutes: -1});
	var endfixFormat 	= Date.parse(endfix).toString("HH:mm:ss");
	var dates 			= '&fq=' + startdate + ':[' + params.startdate + ' TO ' + params.enddate + ']';
	var times 			= '&fq=' + starttime + ':[' + params.starttime + ' TO ' + endfixFormat + ']';
	var zrl 			= 'http://snapshot.prod.showseeker.com:8983/solr/snapshot/select/?q=*%3A*&version=2.2&start=0&indent=on&wt=json&fq=-title:"Paid Programming"&fq=-title:"To Be Announced"&fq=-genre1:"consumer"&fq=-genre2:"consumer"';
	if(type !== 'full'){
		zrl += "&rows=5000";
	}
	else{
		zrl += "&rows=14000";		
	}
	zrl += dates;
	zrl += times;	
	//console.log(zrl);
	
	zrl += solrNetworkFormatter(params.networks);

	//if genre 0 ignore it
	if(type !== "genre"){
	var genrecnt = Object.keys(params.genre).length;
		if(genrecnt > 0){
			zrl += solrGenreFormatter(params.genre);
		}
	}

	//premieres 0 ignore
	var premierecnt = params.premiere.length;
	if(premierecnt > 0){
		zrl += solrPremiereFormatter(params.premiere);
	}

	//if showtype type undefined ignore
	if("showtype" in params){
		zrl += solrShowtypeFormatter(params.showtype);
	}

	//if sports type undefined ignore
	if("sports" in params){
		zrl += solrSportFormatter(params.sports);
	}
	
	//if 7 days ignore
	var daycnt = params.days.length;
	if(daycnt != 7){
		zrl += solrDaysOfWeekFormatter(params.days, params.timezone);
	}

	if(type === "genre") {
		zrl += "&group=true&group.field=showid&fl=genre1,genre2";
		zrl +=  "&sort=genre1 asc";
		
	}


	if(type === "full") {
		zrl += "&group=true&group.field=sort&fl=sort,showid";
		zrl += "&sort=sort asc";
	}

	if(type === "actor") {
		zrl += "&group=true&group.field=sort&fl=credits";
		zrl += "&fq=credits:*";
	}

	//zrl += "&json.wrf=callback";

	return encodeURI(zrl);	
	//return zrl;
}



function solrGetDateColumns(params) {
	var startdate = "tz_start_" + params.timezone;
	var enddate = "tz_end_" + params.timezone;
	var starttime = "start_" + params.timezone;
	var startday = "day_" + params.timezone;

	return startdate + "," + starttime + "," + startday + "," + enddate;
}



//do a search 

function solrSearchSolr(data) {
	var url = solrSearchString(data, 'full');
	return url;
}


//build the genre search

function solrGenreSearch(data) {
	var urls = [];

	var base = solrSearchString(data, 'genre');
	var url1 = base + '&group=true&group.field=genre1&sort=genre1 asc&fq=-genre1:""';
	var url2 = base + '&group=true&group.field=genre2&sort=genre2 asc&fq=-genre2:""';

	urls[0] = url1;
	urls[1] = url2;
	return urls;
}



/////////////////////// formatters ///////////////////////
//MARATHON KEYWORDS

function marathonKeywordFormatter(data) {

	var re = "";
	//loop over and build a solr freindly list for networks
	$.each(data, function(i, value) {
		re += data[i].id + ',';
	});
	return re;
}



//MARATHON GENRE

function marathonGenreFormatter(data) {
	if(data.length == 0){
		return '';
	}

	var re = "";

	for (var key in data) {
   		var obj = data[key];
   		re += obj + ',';
   		
	}

	return re;
}

//MARATHON PREMIERE

function marathonPremiereFormatter(data) {
	if(data === 0) {
		return "";
	}

	var re = "";
	//loop over and build a solr freindly list for networks
	$.each(data, function(i, value) {
		re += data[i] + ',';
	});
	return re;
}


//MARATHON NETWORKS

function marathonNetworkFormatter(data) {
	var re = "";

	for (var key in data) {
   		var obj = data[key];
   		re += obj.id + ',';
	}
	return re;
}


//MARATHON DAYS

function marathonDaysFormatter(data) {

	if(data === 'ms') {
		return '1,2,3,4,5,6,7';
	}

	if(data === 'ss') {
		return '1,7';
	}

	if(data === 'mf') {
		return '1,2,3,4,5';
	}

	var re = "";
	//loop over and build a solr freindly list of days
	$.each(data, function(i, value) {
		re += data[i] + ',';
	});

	return re;
}



//exclude formatters

function solrExcludeFormatter(data) {
	var re = '';
	$.each(data, function(i, value) {
		re += '&fq=-genre1:"' + value + '"';
	});
	return re;
}


//actors formatters

function solrActorSearchFormatter(data) {
	if(data === 0) {
		return '';
	}

	var re = "&fq=";
	//loop over and build a solr freindly list for premieres
	$.each(data, function(i, value) {
		re += 'credits:"' + value.title + '"+';
	});
	return re;
}



//keyword formatter

function solrKeywordSearchFormatter(data) {
	if(data === 0) {
		return '';
	}


	var re = "&fq=";
	var stars = '';

	//loop over and build a solr freindly list for premieres
	$.each(data, function(i, value) {

		var isstar = value.title.indexOf("^");

		if(value.title === "^") {
			stars = '&fq=stars:"*" OR stars:"**" OR stars:"***" OR stars:"****" OR stars:"*****"';
		}

		if(value.title === "^^") {
			stars = '&fq=stars:"**" OR stars:"***" OR stars:"****" OR stars:"*****"';
		}

		if(value.title === "^^^") {
			stars = '&fq=stars:"***" OR stars:"****" OR stars:"*****"';
		}

		if(value.title === "^^^^") {
			stars = '&fq=stars:"****" OR stars:"*****"';
		}

		if(value.title === "^^^^^") {
			stars = '&fq=stars:"*****"';
		}

		if(isstar < 0) {
			var startchar = value.title.charAt(0);
			var endchar = value.title.charAt(value.title.length-1);
			var title = value.title.toLowerCase().trim();

			if(startchar == " " && endchar == " "){
				re += 'search:(' + escape(title) + ')+';
			}else{
				re += 'search:(' + escape(title) + ' OR ' + escape(title) + '*)+';
			}
		}
	});

	var xre = stars + re;
	return xre;
}



//genre formatter

function solrGenreFormatter(data) {

	//if it is all genres then return blank there is no need to pass back anything	
	if(data === 0) {
		return '';
	}

	var re = "&fq=(";
	//loop over and build a solr freindly list for genres
	$.each(data, function(i, value) {
		re += 'genre1:"' + data[i] + '" OR genre2:"' + data[i] + '" + ';
	});
	re += ')';
	return re;
}


//premiere seelctions

function solrTitleSearchFormatter(data) {
	if(data === 0) {
		return '';
	}

	var re = "&fq=";
	//loop over and build a solr freindly list for premieres
	$.each(data, function(i, value) {
		re += 'sort:"' + encodeURIComponent(value.title) + '"+';
	});
	return re;
}



//premiere seelctions

function solrPremiereFormatter(data) {
	if(data === 0) {
		return '';
	}

	var re = "&fq=";
	//loop over and build a solr freindly list for premieres
	$.each(data, function(i, value) {
		re += 'premierefinale:"' + data[i] + '"+';
	});
	return re;
}


//only new

function solrNewFormatter(data) {
	//if new make new string
	if(data === 1) {
		return '&fq=isnew:"New"';
	}
	return '';
}


//showtype

function solrShowtypeFormatter(data) {

	//if new selected
	if(data === 'new') {
		return '&fq=isnew:"New"';
	}

	//if live selected
	if(data === 'live') {
		return '&fq=live:"Live"';
	}

	//if movies selected
	if(data === 'movies') {
		return '&fq=showtype:"MV"';
	}

	//if nothing return null
	return '';
}


//solr days of the week

function solrDaysOfWeekFormatter(data, timezone) {
	var re = '';
	if(data === 'ms') {
		return '';
	}

	if(data === 'ss') {
		re = '&fq=day_' + timezone + ':1+';
		re += 'day_' + timezone + ':7';
		return re;
	}

	if(data === 'mf') {
		re = '&fq=day_' + timezone + ':2+';
		re += 'day_' + timezone + ':3+';
		re += 'day_' + timezone + ':4+';
		re += 'day_' + timezone + ':5+';
		re += 'day_' + timezone + ':6';
		return re;
	}


	re = "&fq=";
	//loop over and build a solr freindly list of days
	$.each(data, function(i, value) {
		re += 'day_' + timezone + ':' + data[i] + '+';
	});
	return re;
}



//build networks from selected

function solrNetworkFormatter(data) {
	var re = "&fq=";
	//loop over and build a solr freindly list for networks
	$.each(data, function(i, value) {
		re += 'stationnum:' + value.id + '+';
	});
	return re;
}

//sports options

function solrSportFormatter(data) {

	var re = '';
	if(data === 'all') {
		re += '&fq=genre1:"sports event"';
	}
	if(data === 'live') {
		re += '&fq=genre1:"sports event"&fq=live:"Live"';
	}
	return re;
}


//movies by decade

function solrMoviesByDecadeFormatter(data) {
	var re = "&fq=";
	
	$.each(data, function(i, value) {
		re += 'year:[' + value + ']+';
	});		
	return re;
}



//movies by year

function solrMoviesByYearFormatter(data) {

	var re = "&fq=";
	//loop over and build a solr freindly list for networks
	$.each(data, function(i, value) {
		re += 'year:' + value + '+';
	});
	return re;
}
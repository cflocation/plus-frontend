function ratecardType(type,row,ratedata,hotprograms){

	if(type == 1){
		var z = getRateFromCard1(row,ratedata);
		if(typeof z == "undefined"){
			return 0;
		}else{
			return z;
		}
	}


	//gci
	if(type == 2){
		var z = getRateFromCard2(row,ratedata);
		if(typeof z == "undefined"){
			return 0;
		}else{
			return z;
		}
	}



	//ZOLO
	if(type == 3){
		var z = getRateValue(row,ratedata);
		if(typeof z == "undefined"){
			return 0;
		}else{
			return z;
		}
	}


	//NEW MASTER
	if(type == 4){
		row.hot = false;

		var z = getRateValue(row,ratedata);
		z = hotprogram(row,z,hotprograms);


		if(typeof z == "undefined"){
			return 0;
		}else{
			row.ratecardid = ratecardID;
			row.ratecardgroup = ratecardGroup;
			return z;
		}
	}


}






//GLOBAL RATECARD MANAGER
function hotprogram(row,rate,hotprograms){
	//var hotprograms = ratecardHotPrograms;

	if(row.linetype !== "Fixed"){
		return rate;
	}


	var hot = false;
	var show = '';
	var title = row.title.replace(/\s/g, "").toLowerCase();
	

	$.each(hotprograms, function(i, value) {

		if(String(value.showtitle) === String(title)){
			if(parseInt(value.networkid) === 0 || parseInt(value.networkid) === parseInt(row.stationnum)){
				hot = true;
				show = value;
			}
		}
	});

	if(hot === false){
		return rate;
	}

	//premiere = Season Premiere Or Series Premiere
	if(row.premiere === "Season Premiere" || row.premiere === "Series Premiere"){
		var type = JSON.parse(show.premieretype);
		var amount = parseInt(show.premiere);
		var newrate = rate;

		if(amount == 0){
			return rate;
		}

		if(type == false){
			newrate = boostpercent(rate,amount);
			row.search = 'Hot Premiere! + '+ amount + '%';
			row.hot = true;
			return newrate;
		}		
			
		if(type == true){
			newrate = boostamount(rate,amount);
			row.search = 'Hot Premiere! = $'+ amount;
			row.hot = true;
			//row.search = 'keyhotprogrammingpremiere';
			return newrate;
		}	
	}



	//Finale = Season Finale OR Series Finale
	if(row.premiere === "Season Finale" || row.premiere === "Series Finale"){
		var type = JSON.parse(show.finaletype);
		var amount = parseInt(show.finale);
		var newrate = rate;

		if(amount == 0){
			return rate;
		}

		if(type === false){
			newrate = boostpercent(rate,amount);
			row.search = 'Hot Finale! + '+ amount + '%';
			row.hot = true;
			return newrate;
		}		
			
		if(type == true){
			newrate = boostamount(rate,amount);
			row.search = 'Hot Finale! = $'+ amount;
			row.hot = true;
			return newrate;
		}	
	}



	//live = "Live"
	if(row.live === "Live"){
		var type = JSON.parse(show.livetype);
		var amount = parseInt(show.live);
		var newrate = rate;


		if(amount == 0){
			return rate;
		}

		if(type == false){
			newrate = boostpercent(rate,amount);
			row.search = 'Hot Live! + '+ amount + '%';
			row.hot = true;
			return newrate;
		}		
			
		if(type == true){
			newrate = boostamount(rate,amount);
			row.search = 'Hot Live! = $'+ amount;
			row.hot = true;
			return newrate;
		}	

	}


	//isnew = "New"
	if(row.isnew === "New"){
		var type = JSON.parse(show.newtype);
		var amount = parseInt(show.isnew);
		var newrate = rate;

		if(amount === 0){
			return rate;
		}
		

		if(type === false){
			newrate = boostpercent(rate,amount);
			row.search = 'Hot New! + '+ amount + '%';
			row.hot = true;
			return newrate;
		}		
			
		if(type === true){
			newrate = boostamount(rate,amount);
			row.search = 'Hot New! = $'+ amount;
			row.hot = true;
			return newrate;
		}	

	}

	
		

	//daypart
	var type = JSON.parse(show.boosttype);
	var amount = parseInt(show.boost);
	var newrate = rate;


	if(amount == 0){
		return rate;
	}

	if(type == false){
		newrate = boostpercent(rate,amount);
		row.search = 'Hot Show! + '+ amount + '%';
		row.hot = true;
		return newrate;
	}		
			
	if(type == true){
		newrate = boostamount(rate,amount);
		row.search = 'Hot Show! = $'+ amount;
		row.hot = true;
		return newrate;
	}	

}



function boostamount(rate,amount){
	var re = parseFloat(amount);
	return re;
}


function boostpercent(rate,amount){
	var pct = Math.round(rate/100*amount);
	var re = parseFloat(pct)+parseFloat(rate);
	return re;
}



function getRateValue(row,ratedata){




	//check to make sure they use ratecards
	var good = Boolean(ratedata);
	
	//if there is no rate card data loaded then lets return 0 for the rate
	if(good == false){
		return 0;
	}

	//setup the network id for the rate value
	var networkId = row.stationnum;

	//lets grab the network that is in question for the ratecard
	var network = getRateNetwork(networkId,ratedata);


	//if the network is question is not available in the selected zone then return zero
	if(network == 0){
		return 0;
	}


	//get the days of the week
	var daysDayparts = getRateDaysInDayparts(row,network);

	//get the rate day parts and add them to the weight
	var rateDayparts = getRateDayparts(row,daysDayparts);

	//what is the lenght of the show
	var rateType = useFixedRate(row.startdatetime,row.enddatetime);


	//if fixed override line type
	if(row.linetype == "Fixed"){
		rateType = 'ratefixed';
	}

	//return 0 if no matches are found
	if(rateDayparts.length == 0){
		return 0;
	}


	//if only one
	if(rateDayparts.length == 1){
		var xrate = rateDayparts[0][rateType];
		if(xrate == 0 || xrate == ""){
			xrate = rateDayparts[0]["rate"];
		}
		return xrate;
	}




	//if there are some rates to go through then lets sort then by the weight system
	var re = sortByKey(rateDayparts,'weight');



	//if there is a single 0 weight then pick it and nothing else. This will swap the rate to a daypart rate since it fixs exactly where it is needed
	if(re[0].weight == 0){
		if(row.linetype != "Fixed"){
			rateType = 'rate';
		}
		return re[0][rateType];
	}
	


	//if the line is a fixed position then lets return the one that weighed in the best
	if(rateType == "ratefixed"){
		//var re = sortByKey2(rateDayparts,'ratefixed');
		//re.reverse();
		if(re.length > 1){
			dropLongDayparts(re);
		}

		var re = sortByKey2(rateDayparts,'weight');
		var xrate = re[0][rateType];

		if(xrate == 0 || xrate == ""){
			re = sortByKey2(rateDayparts,'rate');
			re.reverse();
			xrate = re[0]["rate"];
		}

		//This is a HACK we need to fix for the front end for Ivan and Mark to implement 12/30/2015
		if(parseInt(corpid) === 20 || parseInt(marketid) === 211){
			return xrate*1.5;
		}else{
			return xrate;
		}
		
	}



	if(re.length == 1){
		return re[0][rateType];
	}


	if(ratecardRotatorType == 2){
		var resort = sortByKey2(rateDayparts,'rate');
		resort.reverse();
		return resort[0]["rate"];
	}


	//if the first weight is more then 0 then lets loop over all the rates and add them up
	if(re.length > 1){
		dropLongDayparts(re);
		var num = 0;
		$.each(re, function(i, value) {	
			num += parseFloat(value[rateType]);
		});
		
		var rate = Math.round(num/re.length);
		return rate;
	}

}



//get the most drilled down daypart if available
function dropLongDayparts(rows){
	for (var i = 0; i < rows.length; i++) {
		if(rows[i].statDaypartCnt >= 70){
			rows.splice(i, 1);
		}
	}	
}



//count the 0 weights
function totalZeroWeights(rows){
	var re = 0;

	for (var i = 0; i < rows.length; i++) {
		if(rows[i].weight == 0){
			re++;
		}
	}
	return re;
}


//do the times match the dayparts
function getRateDayparts(row,networkData){

	//set the arrays for the return
	var rows = [];
	var line = [];

	var sd = String(row.startdatetime).split(/[^0-9]/);
	var ed = String(row.enddatetime).split(/[^0-9]/);
	var sDate = new Date(sd[0], parseInt(sd[1])-1, sd[2], sd[3], sd[4]);
	var eDate = new Date(ed[0], parseInt(ed[1])-1, ed[2], ed[3], ed[4]);
	
	//get the start date and end date for the row
	var starts 	= roundMinutesTo30End(sDate);
	var ends 	= roundMinutesTo30End(eDate);

	//set the start day of the week this handels the rotator that are months long
	var startDay = starts.getDate();

	//if the end date is midnight add a day
	if(ends.getHours() == 0){
		ends.add(1).days();
	}else{
		ends = new Date(starts.getFullYear(), starts.getMonth(), starts.getDate(), ends.getHours(), ends.getMinutes());
	}
	
	var block;

	//loop over the dates and get them all in order
	while (starts<ends){
		//set the date as a HHmm to match the day parts
		//var block = starts.getHours()+''+starts.getMinutes();
		block = starts.getHours()+''+('0'+starts.getMinutes()).slice(-2); //NEW IVAN ADDED ON JAN 9 2017
		//push the 30 min block into the array
		line.push(block);
		//add 30 mins for the next daypart
		starts = addMinutes(starts,15);
		//if a new day is detected then break the loop
		var thisDay = starts.getDate();

		if(startDay != thisDay){
			break;
		}
	}

	$.each(networkData, function(i, network) {
		var dayparts = [];

		var fstart = new Date('1980', '0', '1', network.starts.substr(0, 2), network.starts.substr(3, 2));
		var fstop = new Date('1980', '0', '1', network.stops.substr(0, 2), network.stops.substr(3, 2));


		if(network.stops == '24:00'){
			/*fstop = Date.parse('01/01/1980 23:59:59');*/
			fstop = new Date('1980', '0', '1', '23', '59', '59');
		}


		if(network.stops < network.starts){
			fstop.add(1).days();
		}

		while (fstart<fstop){
			/*var block = Date.parse(fstart).toString("HHmm");*/
			//block = fstart.getHours()+''+fstart.getMinutes();
			block 	= fstart.getHours()+''+('0'+fstart.getMinutes()).slice(-2); //NEW IVAN ADDED ON JAN 9 2017			
			dayparts.push(block);
			fstart = addMinutes(fstart,15);
		}

		var xx = getPartCount(dayparts,line);


		if(xx > 0){
			network.statPartsFound = xx;
			network.statLineCnt = line.length;
			network.statLineDiff = line.length - xx;
			network.statDaypartCnt = dayparts.length;
			network.statDaypartDiff = dayparts.length - xx;
			
			if(xx == line.length && xx == dayparts.length){
				network.weight = 0;
			}else{
				network.weight = xx;
			}
			rows.push(network);
		}
	});
	return rows;
}



function getPartCount(dayparts,line){
	var re = 0;

	if(dayparts.length >= line.length){
		for (var i = 0; i < dayparts.length; i++) {
			if(line.indexOf(dayparts[i]) != -1){
				re ++;
			}
		}
	}else{
		for (var i = 0; i < line.length; i++) {
			if(dayparts.indexOf(line[i]) != -1){
				re ++;
			}
		}
	}

	return re;
}





//get the days and time that fit into the fringe
function getRateDaysInDayparts(row,networkData){
	//if days is not an array we need to make it one
	var days;

	//set the days as array if needed
	if(!$.isArray(row.day)){
		days = String(row.day).split(',');
	}else{
		days = row.day;
	}

	//set the rows default
	var rows = [];
	var daycnt = 1000;
	var good, weekdays,diff,i;

	//loop over avaiable rates in the ratecard
	$.each(networkData, function(i, value) {
		
		//set the weekdays for the row as an array
		weekdays = value.weekdays.split(',');
		
		//set good to true
		good = false;

		//loop over the row and look for the days. try to match them with the daypart. 
		//If all of them match then we can move on. If one fails then skip this since it does not match the days exactly
		for (i=0; i < days.length; i++) {
			//look for the line day inside the weekdays
			//if it is NOT found then set good to false
			if (weekdays.indexOf(days[i].toString()) > -1) {
				good = true;
			}
		}

		if(weekdays.length > days.length){
			diff = weekdays.length - days.length;
		}else{
			diff = days.length - weekdays.length;
		}


		//set the dayscount for later use after the we do the dayparts filter
		value.daysInDaypart = weekdays.length;
		value.daysInRow = days.length;
		value.daysDiff = diff;

		value.weight =  days.length - weekdays.length;

		//if good is true then add it to the array for processing the dayparts out
		if(good === true){
			rows.push(value);
		}

	//end the each loop	
	});

	//return
	return rows;
}



//get the rate column to use
function useFixedRate(start,end){
	//var a = Date.parse(start);
	//var b = Date.parse(end);
	//var len = Math.floor((b-a)/60);

	//var stime = start.substr(11, 5).replace(":"); //Date.parse(start).toString("HHmm");
	//var etime = end.substr(11, 5).replace(":");   //Date.parse(end).toString("HHmm");
	//var diff  = parseInt(etime - stime);

	var sT 		= start.split(/[^0-9]/);
	var eT 		= end.split(/[^0-9]/);
	var stime 	= new Date(sT[0],parseInt(sT[1])-1,sT[2], sT[3],sT[4]);
	var etime 	= new Date(eT[0],parseInt(eT[1])-1,eT[2], eT[3],eT[4]);
	var diff 	= (etime - stime)/1000;

	
	

	//200
	if(diff > ratecardFixedSeconds){
		return 'rate';
	}else{
		return 'ratefixed';
	}
}



//this function will grab the network that is needed for the ratecard value
function getRateNetwork(networkId,ratedata){
	//set the default return value
	var re = ratedata[networkId];
	//loop over all the networks and look for the proper one
	if(re !== undefined){
		return re;		
	}
	else{
		return 0;
	}
	
	/*$.each(ratedata, function(key, value) {
		if(networkId == key){
			//when the value is matched by the KEY then lets return the network data. This will also include the rates
			re = value;
		}
	});

	//do the return
	//return re;*/
}



//END GLOBAL RATE CARD MANAGER

//get the differences from the array
Array.prototype.diff = function(arr2) {
    var ret = [];
    this.sort();
    arr2.sort();
    for(var i = 0; i < this.length; i += 1) {
        if(arr2.indexOf( this[i] ) > -1){
            ret.push( this[i] );
        }
    }
    return ret;
};



//add the minutes to time
function addMinutes(date, minutes) {
    return new Date(date.getTime() + minutes*60000);
}


//round the minutes to the end of 30 for easy management
function roundMinutesTo30End(date){
	var now = date;
	var mins = now.getMinutes();
	var quarterHours = Math.round(mins/15);
	if (quarterHours == 4){
	    now.setHours(now.getHours()+1);
	}
	var rounded = (quarterHours*15)%60;
	now.setMinutes(rounded);
	return now;
}




/* TYPE 3 */
function getRateFromCard3(row,ratedata){

	var good = Boolean(ratedata);

	if(good == false){
		return 0;
	}


	//ratecardData
	var networkId = row.stationnum;
	var zoneid = row.zoneid;
	var networkData = findRatecardNetwork3(networkId,zoneid,ratedata);
	var rate = 0;	

	if(networkData != 0){
		var rateFringe = findRatecardFringe3(row,networkData);


		if(rateFringe.length == 0){
			return 0;
		}

		if(row.linetype == "Fixed"){

			if(rateFringe[0].ratefixed == 0){
				rate = rateFringe[0].rate;
			}else{
				rate = rateFringe[0].ratefixed;
			}

		}else{

			var num = 0;
			$.each(rateFringe, function(i, value) {
				if(value.fname.toString() != "-8"){
					num += parseInt(value.rate);
				}
			});
			num = Math.round(num/rateFringe.length);
			rate = num;
		}
	}

	return rate;

}


function findRatecardFringe3(row,networkData){
	var min = 1000;
	var rows = getRatecardWeekDays(row,networkData);
	var re = sortByKey(rows,'between');
	return re;
}



function findRatecardNetwork3(networkId,zoneid,ratedata){
	
	var good = Boolean(ratedata);
	var re = 0;

	if(good == false){
		return 0;
	}

	$.each(ratedata, function(key, value) {
		if(networkId == key){
			re = value;
		}
	});

	return re;
}




function sortByKey(array, key) {
    return array.sort(function(a, b) {
        var x = a[key]; var y = b[key];
        return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    });
}



function sortByKey2(array, key) {
    return array.sort(function(a, b) {
        var x = parseFloat(a[key]); var y = parseFloat(b[key]);
        return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    });
}


function getRatecardWeekDays(row,networkData){
	var days = $.isArray(row.day);

	if(days == false){
		days = row.day.split(',');
	}else{
		days = row.day;
	}
	
	var rows = [];
	var daycnt = 1000;

	//loop over avaiable rates in the ratecard
	$.each(networkData, function(i, value) {
		//set the weekdays for the row as an array
		var weekdays = value.weekdays.split(',');
		var good = true;
		for (var i=0; i < days.length; i++) {

			var index = weekdays.indexOf(days[i].toString());
			if (index == -1) {
				good = false;
			}
		}
		
		var times = isTimeBetween(row,value);


		if(good == true && weekdays.length < daycnt && times > 0 || good == true && weekdays.length == daycnt && times > 0 || value.fname.toString() == "8-"){
			daycnt = weekdays.length;
			value.between = times;
			rows.push(value);
		}
	
	});

	return rows;
}




function isTimeBetween(row,network){
	var starts = row.startdatetime.substr(11, 5); //Date.parse(row.startdatetime).toString("HH:mm");
	var ends = row.enddatetime.substr(11, 5);   //Date.parse(row.enddatetime).toString("HH:mm");

	

	if(starts >= '00:00' && starts <= '06:00' && ends > '06:30'){
		starts = '06:00';
	}else if(starts >= '19:00' && starts <= '22:00' && ends > '22:00'){
		ends = '22:00';
	}else if(starts >= '06:00' && starts < '18:00' && ends > '18:00'){
		starts = '18:00';
	}

	//set the start and end times
	var fstart = network.starts.trim();
	var fstop = network.stops.trim();


	//set the times as numbers
	var tempstart = fstart.replace(":",""); 
	var tempend = fstop.replace(":",""); 

	//find out the time between the two
	var timebetween = tempend - tempstart;

	if(starts >= fstart && starts < fstop || ends > fstart && ends <= fstop || fstart >= starts && fstart < ends){
		return timebetween;
	}

	return 0;
}

/* END TYPE 3 */


function findRatecardNetwork(networkId,zoneid,ratedata){

	var networkList = ratedata.zone[zoneid];
	var good = Boolean(networkList);

	if(good == false){
		return 0;
	}

	for(var i = 0; i < networkList.length; i++) {
		if(networkList[i][networkId] != undefined){
			return networkList[i];
		}
	}
	return 0;
}


function findRatecardNetwork1(networkId,zoneid,ratedata){
	var networkList = ratedata.zones[zoneid];

	var good = Boolean(networkList);

	if(good == false){
		return 0;
	}

	for(var i = 0; i < networkList.length; i++) {
		if(networkId == networkList[i].id){
			return networkList[i];
		}
	}
	return 0;
}


function getRateFromCard1(row,ratedata){

	var good = Boolean(ratedata);

	if(good == false){
		return 0;
	}

	//ratecardData
	var networkId = row.stationnum;
	var zoneid = row.zoneid;

	var networkData = findRatecardNetwork(networkId,zoneid,ratedata);



	if(networkData != 0){

		var rateFringe2 = findRatecardFringe1(row,ratedata);

		
		if(rateFringe2.length != 0) {

			//if(row.linetype == 'Rotator') {
				var num = 0;
				var temprate = 0;
				
				for(var i = 0; i < rateFringe2.length; i++) {
					
					var temp = rateFringe2[i]; 					
					for(var j = 0; j < networkData[row['stationnum']].length; j++){
					
						if(networkData[row['stationnum']][j][temp] != undefined){

							for(k=0;k<ratedata['fringe'].length;k++){	
								if(ratedata['fringe'][k]['fringe'] == temp){

									var startTimeArr = row.startdatetime.split(/[^0-9]/);
									var linestarts   = new Date(2000, 0, 1, startTimeArr[3], startTimeArr[4]); //Date.parse(row.starttime);
									var endTimeArr   = row.enddatetime.split(/[^0-9]/);
									var lineends 	 = new Date(2000, 0, 1, endTimeArr[3], endTimeArr[4]); //Date.parse(row.endtime);

									var fstarts		= new Date(2000, 0, 1, ratedata['fringe'][k]['starts'].split(":")[0], ratedata['fringe'][k]['starts'].split(":")[1]); //Date.parse(ratedata['fringe'][k]['starts']);
									var fstops		= new Date(2000, 0, 1, ratedata['fringe'][k]['stops'].split(":")[0], ratedata['fringe'][k]['stops'].split(":")[1]); //Date.parse(ratedata['fringe'][k]['stops']);
																		
									var linediff    = (lineends-linestarts)/3600; //time diff in the line
									var fdiff 	    =  (fstops-fstarts)/3600;//time diff in the fringe

									if(temprate < networkData[row['stationnum']][j][temp]){
										temprate = networkData[row['stationnum']][j][temp];


										if(row.linetype != 'Rotator'){
											temprate = temprate*2;										
										}
										else if(linediff < fdiff && linediff > 3000){
											temprate = temprate*1.5;										
										}
										else if(linediff < fdiff && linediff <= 3000){
											temprate = temprate*2;
										}

										
									}


								}
							}
						}
					}														

				}
				//var rate = Math.round(num/rateFringe2.length);
				var rate = temprate;


				return rate;
			/*} else {

				var rate = Math.round(networkData[rateFringe2]);
				var addfixedpercent = Math.round(rate/100*25);
				var fixedRate = Math.round(rate+addfixedpercent);
				return fixedRate;
			}*/
		}
	}	
}


function findRatecardFringe1(row,ratedata){
	var re = [];
	var fringeList = ratedata.fringe;

	var starts 	= row.startdatetime.substr(11, 5); //Date.parse(row.startdatetime).toString("HH:mm");
	var ends 	= row.enddatetime.substr(11, 5); //Date.parse(row.enddatetime).toString("HH:mm");
	var timespan= parseInt((new Date(2000, 0,1, ends.split(':')[0], ends.split(':')[1])	- new Date(2000, 0,1, starts.split(':')[0], starts.split(':')[1]))/3600);
	//var timespan= parseInt((Date.parse(ends)-Date.parse(starts))/3600);
	
	for(var i = 0; i < fringeList.length; i++) {
		var fstart = fringeList[i].starts.trim();
		var fstop = fringeList[i].stops.trim();

		//if(row.linetype == 'Rotator'){
			if(timespan == 18983){//ROS 5a-m
				re.push('307677258');
				return re;
			}
			
			if((fringeList[i].fname != 'ROS')&&(starts >= fstart && starts < fstop || ends > fstart && ends <= fstop || fstart >= starts && fstart < ends)){
				re.push(fringeList[i].fringe.trim());
			}
			
		/*}
		else{
			if(starts >= fstart && starts < fstop){
				re.push(fringeList[i].fringe.trim());
			}
		}*/
	}


	if(re.length == 0){
		return 0;
	}else{
		return re;
	}
}


function getRateFromCard2(row,ratedata){


	var good = Boolean(ratedata);

	if(good == false){
		return 0;
	}



	//ratecardData
	var networkId = row.stationnum;
	var zoneid = row.zoneid;
	//var rateFringe2 = findRatecardFringe1(row,ratedata);
	var networkData = findRatecardNetwork1(networkId,zoneid,ratedata);
	

	if(networkData != 0){
		var rateFringe2 = findRatecardFringe2(row,ratedata);

		if(rateFringe2.length != 0) {

			if(row.linetype == 'Rotator' || row.linetype == 'Line') {

				var formatStartDateTime = row.startdatetime.substr(11, 5).replace(":"); //Date.parse('01/01/2000 '+row.starttime).toString("HHmm");
				var formatEndDateTime   = row.enddatetime.substr(11, 5).replace(":");   //Date.parse('01/01/2000 '+row.endtime).toString("HHmm");
				var hourDiff            = parseInt(formatEndDateTime) - parseInt(formatStartDateTime);    
				
				var multiFindRate = [];
				var timelength = 0;
				
				if(rateFringe2.length > 0){
					for(var i = 0; i < rateFringe2.length; i++) {

						var x = parseInt(networkData[rateFringe2[i][0]]);

						if(rateFringe2[i][1] < timelength){
							timelength = i;
						}
						
						multiFindRate.push(x);
					}


					var rate = Math.round(multiFindRate[timelength]);
				}else{
					var rate = Math.round(networkData[rateFringe2[0]]);
				}

				if(hourDiff <= 300){
					var addfixedpercent = Math.round(rate/100*25);
					rate = Math.round(rate+addfixedpercent);
				}



				return rate;
			} else {

				var multiFindRate = [];
				var timelength = 0;

				if(rateFringe2.length > 0){
					for(var i = 0; i < rateFringe2.length; i++) {
						
						var x = parseInt(networkData[rateFringe2[i][0]]);

						if(rateFringe2[i][1] < timelength){
							timelength = i;
						}
						
						multiFindRate.push(x);
					}

					var rate = Math.round(multiFindRate[timelength]);
				}else{
					var rate = Math.round(networkData[rateFringe2[0]]);
				}

				var addfixedpercent = Math.round(rate/100*25);
				var fixedRate = Math.round(rate+addfixedpercent);
				return fixedRate;
			}
		}

	}	
}


function findRatecardFringe2(row,ratedata){
	var re = [];
	var min = 100000;


	var fringeList = ratecardData.fringe;


	var starts = row.startdatetime.substr(11, 5); // Date.parse(row.startdatetime).toString("HH:mm");
	var ends   = row.enddatetime.substr(11, 5);   //Date.parse(row.enddatetime).toString("HH:mm");



	if(starts >= '00:00' && starts <= '06:00' && ends > '06:30'){
		starts = '06:00';
	}else if(starts >= '19:00' && starts <= '22:00' && ends > '22:00'){
		ends = '22:00';
	}else if(starts >= '06:00' && starts < '18:00' && ends > '18:00'){
		starts = '18:00';
	}


	for(var i = 0; i < fringeList.length; i++) {
		var fstart = fringeList[i].starts.trim();
		var fstop = fringeList[i].stops.trim();

		//set the times as numbers
		var tempstart = fstart.replace(":",""); 
		var tempend = fstop.replace(":",""); 

		//find out the time between the two
		var timebetween = tempend - tempstart;
	
		if(starts >= fstart && ends <= fstop){

				if(timebetween < min){
					re = new Array();
					min = timebetween;
					var tmp = new Array(fringeList[i].fname.trim(),timebetween);
					re.push(tmp);
				}
		}


	}


	if(re.length == 0){
		return 0;
	}else{
		return re;
	}
}




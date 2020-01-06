			
	//ADDING SPOTS TO THE PROPOSAL
	function addLine(data){
		var zone = $('#zones option:selected').text();
		var zoneid = $("#zones").val();
		
		var a = data.stationnum+data.title+data.starttime24+data.endtime24;
		var b = a.replace(/[^a-z0-9]/gi,'');
		
		var title = decodeURIComponent(data.title);
		var desc = decodeURIComponent(data.desc);
		var epititle = decodeURIComponent(data.epititle);
	
		
		var row = new Object();
		//basic varibles
		row.id = data.ssid+"-"+zoneid;
		row.ssid = data.ssid;
        row.zone = zone,
        row.zoneid =  zoneid,
        row.linetype = 'Fixed',
        row.split = 0,
        row.title = title,
        row.startdate = data.startdate,
        row.enddate = data.enddate,
        row.starttime = data.starttime,
        row.endtime = data.endtime,
        row.startdatetime = data.formatStartDateTime,
        row.enddatetime = data.formatEndDateTime,
        row.desc = desc,
        row.epititle = epititle,
        row.live = data.live,
        row.genre = data.genre,
		row.premiere = data.premierefinale,
        row.isnew =  data.isnew,
        row.stars = '',
        row.day = data.day,
        row.stationnum = data.stationnum,
        row.callsign = data.callsign,
        row.programid = data.tmsid,
       	row.search = '',
       	
       	//scheduler features
       	row.locked = false,
       	row.ratecardid = 0,
       	row.rate = 0,
       	row.ratevalue = '',
       	row,ratename = '',
       	row.weeks = 1,
       	row.spotsweek = 1,
       	row.spots = 1,
       	row.weekdays = 0,
       	row.ncc = '',
       	row.avail = b,
       	row.broadcastweek = '',
       	row.timestamp = new Date(),
       	row.total = 0,
       	row.split = 0,
       	
       	//formatters
        row.titleFormat = title + "|" + epititle,
        row.dayFormat = setDayofWeek(data.day),
        row.titlenetworkFormat = data.callsign + " - " + title,
        row.callsignFormat = data.callsign + "|" + data.stationname,
        row.statusFormat = data.premierefinale + "|" + data.live + "|" + data.isnew,
        row.zonetitle = zone + " - " + title,
        row.zonenetwork = zone + " - " + data.stationname,
        row.networktitle = data.callsign + " - " + title,
        row.zonenetworktitle = zone + " - " + data.callsign + " - " + title,
		row.lineactive = 1,
		row.premiereFormat = data.premierefinale
		
		
		var rows = [row];


		//window.opener.addSelectionToProposal(rows,zoneid,zone,'drop');

		console.log(rows);
	}
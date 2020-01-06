function schedulerCountWeeksFromDates() {
	var ttl = 0;
	var x = new Date($("#date-start").val());
	var date1 = new Date(getProperMonday(x));
	var date2 = new Date($("#date-end").val());

	for(var d = date1; d <= date2; d.setDate(d.getDate() + 7)) {
		ttl++;
	}

	$("#schedule-weeks").val(ttl);
}


function getProperMonday(x) {
	var d = new Date(x);
	var n = d.getDay();
	var re = '';


	if(n === 1) {
		re = d.toString("MM/dd/yyyy");
	} else {
		re = new Date(x).last().monday().toString("MM/dd/yyyy");
	}
	return re;
}

/*
function schedulerCountWeeksFromInput() {
	var weeks = $("#schedule-weeks").val();
	var date1 = new Date($("#date-start").val());
	var diff = weeks * 10080 - 10080;

	var newdate = new Date(date1.getTime() + diff * 60000);

	var ends = new Date($("#date-end").val());
	var sunday = new Date(newdate).sunday().toString("MM/dd/yyyy");

	$("#date-end").val(sunday);

}
*/


function schedulerDaysOfWeek(days) {
	var ndays = [];
	var re = '';


	if(days[0] === 'ms') {
		return 'M-Su';
	}

	if(days[0] === 'ss') {
		return 'Sa-Su';
	}

	if(days[0] === 'mf') {
		return 'M-F';
	}

	$.each(days, function(i, val) {
		if(val === 1) {
			ndays.push(7);
		} else {
			ndays.push(val - 1);
		}
	});

	//if one day pass it back bro
	if(days.length === 1) {
		return daysAbbrSmallDayFix(ndays[0]);
	}

	var diff = ndays[ndays.length - 1] - ndays[0];

	if(ndays.length - diff === 1) {
		re = daysAbbrSmallDayFix(ndays[0]) + "-" + daysAbbrSmallDayFix(ndays[ndays.length - 1]);
	} else {
		var daylist = [];
		$.each(ndays, function(i, val) {
			daylist.push(daysAbbrSmallDayFix(val));
		});
		return daylist;
	}
	return re;
}



function schedulerAddLineToProposal(close) {
	var params = searchParamaters();
	var netid = params.networks[0];

	if(netid === 0) {
		var url = 'includes/errors.php?type=selectnetworks';
		$('#error-overlay').load(url, function() {
			displayError();
		});
		return;
	}

	//lets remove the rotator button so users cannnot click it untill the new line is added
	$("#btn-rotator-group").css('display', 'none');
	$("#btn-rotator-group-wait").css('display', 'inline');

	var weeks = 0;

	//addMenuZone(params.zoneid,params.zone);
	ssSchedulerDataGrid.unSelectAll();

	if(params.schedulerate === '') {
		params.schedulerate = 0;
	}

	if(params.schedulespots === '') {
		params.schedulespots = 0;
	}

	var daysformat = schedulerDaysOfWeek(params.days);

	var starts = Date.parse(params.rawstartdate).toString("yyyy/MM/dd");
	var ends = Date.parse(params.rawenddate).toString("yyyy/MM/dd");

	var starttime = Date.parse(params.starttime).toString("hh:mm tt");
	var endtime = Date.parse(params.endtime).toString("hh:mm tt");

	//FORMATS
	var formatStartDateTime = Date.parse(params.rawstartdate).toString("yyyy/MM/dd HH:mm");
	var formatEndDateTime = Date.parse(params.rawenddate).toString("yyyy/MM/dd HH:mm");

	var xurl = "https://ww2.showseeker.com/plus/services/weeks.php?callback=?&start=" + starts + "&end=" + ends + "";

	$.getJSON(xurl, function(data) {
		weeks = data.length;

		$.each(params.rawnetworks, function(i, network) {

			params.networks = [network.id];

			var titleurl = solrSearchGroup(params, 'full');

			$.getJSON(titleurl, function(titledata) {


				var ttltitles = titledata.grouped.sort.groups.length;
				var xtitle = '';
				if(ttltitles < 30) {
					xtitle = getTitlesByCount(titledata.grouped.sort.groups);
				} else {
					xtitle = 'Various';
				}

				var spots = params.schedulespots * weeks;
				var row = {};

				//basic varibles
				row.id = GUID() + "-" + params.zoneid;
				row.ssid = '';
				row.zone = params.zone;
				row.zoneid = params.zoneid;
				row.linetype = 'Rotator';
				row.split = 0;
				row.title = xtitle;
				row.startdate = starts;
				row.enddate = ends;
				row.starttime = starttime;
				row.endtime = endtime;
				row.startdatetime = formatStartDateTime;
				row.enddatetime = formatEndDateTime;
				row.stationnum = network.id;
				row.callsign = network.callsign;
				row.day = params.days;
				row.total = 0;
				row.desc = '';
				row.epititle = '';
				row.live = '';
				row.genre = '';
				row.premiere = '';
				row.isnew = '';
				row.stars = '';
				row.programid = '';
				row.search = '';

				//scheduler varibles
				row.locked = false;
				row.ratecardid = 0;
				row.rate = params.schedulerate;
				row.ratevalue = '';
				row.ratename = '';
				row.weeks = weeks;
				row.spotsweek = parseInt(params.schedulespots, 10);
				row.spots = spots;
				row.weekdays = 0;
				row.ncc = '';
				row.avail = '';
				row.broadcastweek = '';
				row.timestamp = new Date();
				row.cost = spots * params.schedulerate;

				//formatters
				row.titleFormat = xtitle + '|';
				row.dayFormat = daysformat;
				row.titlenetworkFormat = network.callsign + " - " + "Various";
				row.callsignFormat = network.callsign + "|" + network.id;
				row.statusFormat = '|||';
				row.zonetitle = params.zone + " - " + 'Various';
				row.zonenetwork = params.zone + " - " + network.callsign;
				row.networktitle = network.callsign + " - " + 'Varoius';
				row.zonenetworktitle = params.zone + " - " + network.callsign + " - " + 'Various';
				row.lineactive = 1;
				row.premiereFormat = '';

				$.each(data, function(i, value) {
					var z = 'w' + value.column;
					row[z] = parseInt(params.schedulespots, 10);
				});


				dataViewProposal.push(row);
				populateProposalDatagrid(dataViewProposal);
				//buttonSaveProposalClicked();
			});
		});

		//if you want the dialogs closed after insert do it
		if(close === 1) {
			$("#select-networks-overlay").dialog("destroy");
			$("#proposal-line-overlay").dialog("destroy");
		}

		needSaving = true;

		//clear the fields after insert
		$("#schedule-spots").val('');
		$("#schedule-rate").val('');
	});
}



function getTitlesByCount(data) {
	var re = Array();

	$.each(data, function(i, value) {
		var num = parseInt(value.doclist.numFound, 10);
		var row = Array(num, value.groupValue);
		re.push(row);
	});

	re.sort(function(element_a, element_b) {
		return element_a[0] - element_b[0];
	});

	re.reverse();

	var z = 0;
	var xre = '';
	$.each(re, function(i, value) {
		//xre += value[1]+' ('+value[0]+'), ';
		xre += value[1] + ',';
		if(z > 3) {
			return false;
		}
		z++;
	});
	return xre;
}



function getLineTitles(params, num) {
	var starttime24 = Date.parse(params.starttime).toString("HH:mm");
	var endtime24 = Date.parse(params.endtime).toString("HH:mm");
	var url = solrSearchGroup(params, 'full');
}


var cnt = 0;

function duplicateSelectedLines() {
	closeAllDialogs();
	var zones = $('#duplicate-zone-selector').val();
	var ttl = zones.length;

	if(zones[0] === 0) {
		return;
	}

	var rows = ssSchedulerDataGrid.getSelectedRows();
	var tokenid = gtokinid;
	var userid = guserid;

	//set the url for the networks list
	var url = '/services/networklist.php?zoneid=' + zones[cnt] + '&userid=' + userid + '&tokenid=' + tokenid;

	//get the network list
	$.getJSON(url, function(data) {
		var netlist = data.response.networks;
		var zid = data.responseHeader.zoneid;
		var zname = data.responseHeader.zonename;



		$.each(rows, function(i, row) {
			//get the station id
			var rowstation = parseInt(row.stationnum, 10);
			var avail = findStationForDupe(netlist, rowstation);


			if(avail !== 0) {

				//var temprow = new Object();
				var temprow = jQuery.extend({}, row);
				temprow.id = GUID() + "-" + zid;
				temprow.zone = zname;
				temprow.zoneid = zid;
				temprow.zonetitle = zname + " - " + temprow.title;
				temprow.zonenetwork = zname + " - " + temprow.callsign;
				temprow.zonenetworktitle = zname + " - " + temprow.callsign + " - " + temprow.title;
				dataViewProposal.push(temprow);
			}

		});

		cnt++;

		//check to see if there is mosr network to loop over
		if(ttl === cnt) {
			cnt = 0;
			populateProposalDatagrid(dataViewProposal);
			buttonSaveProposalClicked();
			return;
		}
		duplicateSelectedLines();
	});

}



function findStationForDupe(arr, station) {
	var re = 0;
	$.each(arr, function(i, value) {
		var id = parseInt(value.id, 10);
		if(id === station) {
			re = id;
			return id;
		}
	});
	return re;
}



function setSpotsRates(action) {
	var spots = $("#schedulerspots").val();
	var rate = $("#schedulerrate").val();

	if(spots.length > 0) {
		ssSchedulerDataGrid.setSelectedSpots(spots);
	}

	if(rate.length > 0) {
		ssSchedulerDataGrid.setSelectedRate(rate);
	}

	if(action === 1) {
		$("#set-pricing-overlay").dialog("destroy");
	}
}



//ADD SHOW FROM EXTERNAL SOFTWARE

function addShowToProposalFromID(id) {
	var zrl = 'https://solr.prod.showseeker.com:8983/solr/gracenote/select/?q=*%3A*&version=2.2&start=0&indent=on&json.wrf=?&wt=json';
	zrl += "&fq=id:" + id;

	//load the search grid
	$.getJSON(zrl, function(data) {
	});
}
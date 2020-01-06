//Welcome to ShowSeeker Plus
//varibles list
document.domain = "showseeker.com";

//GoPlus Related vars
var	apiDldUrl 	= "https://downloadsapi.showseeker.com/";
var apiUrl 		= "https://plusapi.showseeker.com/";
var appVersion 	= '1.5.16';


var activeWeeks = [];
var allzones 	= [];
var allMarkets;
var demographics = {};
var downloadtype = 0;
var ezcalendar;
var ezgrids;
var ezcalendarOpen = false
var ezgridsOpen = false;
var firstload = true;
var inactiveWeeks = [];
var ishidden = false;
var isresetting = false;
var launchgrids = false;
var lineLimit = 50;
var loadingNets = false;
var loadingSearch = false;
var loadedSearch;
var markets = {};
var marketid = 0;
var marketzones = {};
var proposalShareType = 'Proposal';
var proposalHiddenColumns ={};
var rndDecimalPlaces= 2;
var searchtitletype = 'title';
var selectedShowId = '';
var showinfourl = '';
var ssDialogs;
var stdcalendar = 0;
var timezone = '';
var trackProposalRow;
var tokendl	='';
var uniqueDmaList;
var zone = '';
var zoneid = 0;
var zonesArray;
var userOfficeId = 0;

var demographicsList = [];


//datagrids
var datagridClients;
var datagridGenres;
var datagridGenresSelected;
var datagridHeaders;
var datagridImport;
var datagridMessages;
var datagridNetworks;
//var datagridZones;
var datagridProposal;
var datagridProposalManager;
var datagridSearchResults;
var datagridTotals;
var datagridUsers;
var datagridSurveys;

//ez search
var datagridActors;
var datagridActorsSelected;
var datagridKeywords;
var datagridSavedSearches;
var datagridTitles;
var datagridTitlesSelected;



//serarch setting holders
var arrayDays = [1, 2, 3, 4, 5, 6, 7];
var arrayGenre = [];
var arrayNetworks = [];
var arrayPremiere = [];
var arrayTVR = [];
var searchType = 'all';


var dataSourceResult = [];
var dataSourceResultCounter = 0;


//proposal
var discountagency = 0;
var discountpackage = 0;
var discountpackagetype = 0;
var proposalid = 0;
var tmpPslId = 0;
var weeksdata = [];

//saving states
var issaving = false;
var needSaving = false;


/* User Info */
var corpid = 0;
var fname = '';
var iseeker = 'No';
var lname = '';
var tokenid;
var userid;
var apiKey;
var roles = {};
var userSettings = {};


//showcard
var myShowcard;


//search results
userSettings.resultsCollapse = true;
userSettings.resultsGroup = 'off';

//scheduler datagrid
userSettings.schedulerCollapse = false;
userSettings.schedulerGroup = 'zone';

//search settings
userSettings.lastZoneId = 0;
userSettings.lastMarketId = 0;

//proposals
userSettings.proposalCollapse = true;
userSettings.proposalShowTotals = true;

//user settings
userSettings.autoSplitLines = true;


//display weeks off
userSettings.showWeeksOff = true;
/* End User Info */



var editRotatorItems = {};
var editRotator = false;

//ratecards
var ratecard = false;
var ratecardData;
var ratecardDate = new Date().toString("yyyy-MM-dd");
var ratecardDefaultMode = 0;
var ratecardFixedSeconds = 5000;
var ratecardFixedPct = 5000;
var ratecardGroup = 0;
var ratecardHotPrograms;
var rateCardID = 0;
var ratecardID = 0;
var rateCardMode = 0;
var ratecardRotatorType = 1;
var rateType = 0;
var ratecardZone = false;

//DOWNLOAD
var agencyid = 0;
var agencyid2 = 0;
var clientid = 0;
var clientid1 = 0;
var clientid2 = 0;
var headerid = 0;
var repfirmid = 0;


//proposal panels
var builderpanel = {};
builderpanel.panel1 = true;
builderpanel.panel2 = true;
builderpanel.panel3 = false;

//var container = {};
var btnOpener = {};

//RATINGS
var myEzRating;
var	searchSelectedLines	= {};
var	searchRatingsComplete	= true;
var rangeInitial = 0;
var rangeFinal = 0;
var proposalRattingsOn = 0;

//jquery setup
$.ajaxSetup({
    cache: false
});



var obj = document.body; // obj=element for example body
// bind mousewheel event on the mouseWheel function
if (obj.addEventListener) {
    obj.addEventListener('DOMMouseScroll', mouseWheel, false);
    obj.addEventListener("mousewheel", mouseWheel, false);
} else obj.onmousewheel = mouseWheel;




/* on document ready start the loading */
$(document).ready(function() {
	
    //check if logged in
    if (localStorage.getItem("userId") === null || localStorage.getItem("apiKey") === null) {
        window.location.href = "login.php?logout=true";
    }

    userid = localStorage.getItem("userId");
    apiKey = localStorage.getItem("apiKey");

    $.cookie = function (key, value, options) {
        if (arguments.length > 1 && String(value) !== "[object Object]") {
            options = jQuery.extend({}, options);
            if (value === null || value === undefined) {
                options.expires = -1;
            }
            if (typeof options.expires === 'number') {
                var days = options.expires, t = options.expires = new Date();
                t.setDate(t.getDate() + days);
            }
            value = String(value);
            return (document.cookie = [
                encodeURIComponent(key), '=',
                options.raw ? value : encodeURIComponent(value),
                options.expires ? '; expires=' + options.expires.toUTCString() : '',
                options.path ? '; path=' + options.path : '',
                options.domain ? '; domain=' + options.domain : '',
                options.secure ? '; secure' : ''
                ].join(''));
        }
    
        options = value || {};
        var result, decode = options.raw ? function (s) { return s; } : decodeURIComponent;
        return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
    };

    /*$.when(compViewChek()).done(function(result){
		if(result){
			dialogCompView();
		}
		else{*/
			//start the overlay
			loadDialogWindow('loading', 'ShowSeeker Plus', 450, 180, 1);
		/*}
	});	*/
	
	//ini columns status
	setProposalHiddenCols([],true);
	
    //start the overlay
    loadDialogWindow('loading', 'ShowSeeker Plus', 450, 180, 1);

    //build the proposal datagrid
    bindProposalDatagrid();

    //when the document is ready get the user settings to start the loading process
    getUserSettings();

    //items that remove parts of jquery and slickgrids
    $('#proposal-list .slick-header-columns input[type="checkbox"]').remove();
    $('#menu_proposal_manager').addClass("menu-selected");

    //setup the menu
    $('ul.sf-menu').superfish({
        delay: 800,
        speed: 'slow'
    });

	allDialogs();
	

	if(parseInt(userid) !== 3709){
		myShowcard = new Showcard();
	}
	else{
		myShowcard = new Showcardapi();	
	}
	
	//displayDisclaimer();
});



function compViewChek(){
	var url = '../services/1.0/comp.view.php';
	return $.ajax({
                url: url,
	           dataType: "json"
			});
}


function allDialogs(){
	
    $.ajax({
        type:'get',
        url: apiUrl+"dialog/list",
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        success:function(data){
			ssDialogs =  data.dialogs;
        }
    });

};

function displayDisclaimer(){
	//if ($.cookie('flyover') != 'yes') {
	var w = $(document ).width() - 965 +'px';
	$('#popupDisclaimer').css({'max-width': w, 'width': w, 'left':965});
	var xSeconds = 35000; // 1 second
	
	var d = {};
	d.width	= Math.abs($( document ).width() - 965);
	d.opacity= 0.9;
	d.marginLeft	= "0.05in";
	d.borderWidth	= "5px";
	$('#disclaimerMain').show().animate(d, 2500);
	setTimeout(function(){
		$('#popupDisclaimer').fadeOut('fast');
		$('#popupDisclaimer').hide();
		}, xSeconds);
};


function availsOff() {
    setToggleResult();
}

function buildBroadcastCal() {
    var re = [];
    var fisrday = Date.january().first();
    var starts = fisrday;

    if (fisrday.getDay() != 1) {
        starts = new Date(fisrday).last().monday();
    }

    var weekcnt = 1;
	var r;

    for (var i = 0; i < 125; i++) {
        r = {};

        r.week = weekcnt++;

        if (weekcnt == 53) {
            weekcnt = 1;
        }

        r.column 	= 'w' + new Date(starts).toString("MMddyyyy");
        r.date 		= new Date(starts).toString("MM/dd/yyyy");
        r.dateShort = new Date(starts).toString("MM/dd/yy");
        re.push(r);

        starts = new Date(starts).add({
            days: 7
        });
    }


    return re;
}


/* datagrid functions */

function buildGrids(buildGrids) {
	datagridSurveys 		= new DatagridSurvey("#surveys-container");
	datagridGenres 		   	= new DatagridGenres("#datagrid-genre","Genre  <span id='genresCount'></span>");
	datagridGenresSelected 	= new DatagridGenres('#genre-selected','Selected Genres',true);
	datagridNetworks 	   	= new DatagridNetworks();
	//datagridZones			= new DatagridZones();
	datagridProposalManager = new DatagridProposalManager(buildGrids);
	datagridProposal 		= new DatagridProposal();
	datagridSavedSearches 	= new DatagridSavedSearches();
	datagridSearchResults 	= new DatagridSearchResults();
	datagridTotals 		   	= new DatagridTotals();
	datagridTitles 			= new DatagridTitles('#titles-available', 'Titles Available <span id="titlesCount"></span>', false);
	datagridTitlesSelected 	= new DatagridTitles('#titles-selected', 'Selected Titles', true);
	datagridKeywords 		= new DatagridTitles('#keywords-entered', 'Keywords', true);
	datagridActors 			= new DatagridTitles('#actors-available', 'Actors Available <span id="actorsCount"></span>', false);
	datagridActorsSelected 	= new DatagridTitles('#actors-selected', 'Selected Actors', true);


    datagridSearchResults.groupByColumn(userSettings.resultsGroup);
    datagridProposal.groupByColumn('zone');
    windowManager();
    firstload = false;

	if($("#zone-selector").length > 1){
	    if (userSettings.lastZoneId != 0) {
	        $("#zone-selector").val(userSettings.lastZoneId).change();
	    }else {
	        var a = $("#zone-selector option:eq(1)").val();
	        userSettings.lastZoneId = a;
	        $("#zone-selector").val(a).change();
	    }
	}
    setInterval(function(){
        getUserMessages()
        checkUpdate();
    }, 100000);
    
    loadSavedSearches();
}

//INSTANTIATING EZRATES
function buildEzRatings(){
	myEzRating = new Ezrating();	
};

//build the token to call the webservice
function buildToken(url) {
    var url = "includes/token.php?userId="+userid+"&tokenId="+tokendl+"&url="+url;
    return $.ajax({
        url: url,
        dataType: "json"
     }).done(function(data){
	    if(data == 0){}
    });
};


function checkSpots() {
    var bad = datagridProposal.spotCount();

	if(proposalid != 0){
	    if (bad == 0) {
	        proposalSaveChanges(true);
	    } else {
		    if(bad == -1){
		        menuSelect('proposal-build');
				sidebarOpen();
		    	loadDialogWindow('emptyproposal','ShowSeeker Plus', 450, 180, 1);
				return;
			}
			else{
		        menuSelect('proposal-build');
				sidebarOpen();
				loadDialogWindow('nospots','ShowSeeker Plus', 450, 180, 1);
			}
	    }
	}
};


function closeEditRotators() {
    datagridProposal.filterFixedLines(false);
    swapSettingsPanel(false);
};


function discountLabels() {
    $("#proposal-discount-package").val(discountpackage);
    $('input:radio[name=discount-mode-selector][value=' + discountpackagetype + ']').attr('checked', true);

    if (discountagency == 0) {
        $('#discount-agency').prop('checked', false);
    } else {
        $('#discount-agency').prop('checked', true);
    }
};


function getExternalSyncData(){
  
	$.ajax({
		type:'get',
		url: apiUrl+"client/"+corpid+"/agencylist",
		dataType:"json",
        headers: {'Api-Key': apiKey,'User': userid},
		success: function(data){
	        $('#agency-download-selectorlist').append($("<option></option>").attr("value", "0").text("Select Agency"));
		    $('#proposal-download-selector-2').append($("<option></option>").attr("value", "0").text("Select Agency"));
	        $.each(data.agencyList, function(i, value) {
	            if(parseInt(corpid) !== 16){
		            $('#agency-download-selectorlist').append($("<option></option>").attr("value", value.id).text(value.name));
				}
				else{
		            $('#proposal-download-selector-2').append($("<option></option>").attr("value", value.customerNumber).text(value.company));
				}
	        });
		}		
	});
	
	$.ajax({
		type:'get',
		url: apiUrl+"client/"+corpid+"/clientlist",
		dataType:"json",
        headers: {'Api-Key': apiKey,'User': userid},
		success: function(data){
	        $('#client-download-selectorlist').append($("<option></option>").attr("value", "0").text("Select Client"));
            $('#proposal-download-selector-1').append($("<option></option>").attr("value", "0").text("Select Customer"));
	        $.each(data.clientsList, function(i, value) {
	            if(parseInt(corpid) !== 16){
		            $('#client-download-selectorlist').append($("<option></option>").attr("value", value.id).text(value.name));
		        }
		        else{
		            $('#proposal-download-selector-1').append($("<option></option>").attr("value", value.customerNumber).text(value.company));
		        }
	        });
		}		
	});

	$.ajax({
		type:'get',
		url: apiUrl+"repfirm",
		dataType:"json",
        headers: {'Api-Key': apiKey,'User': userid},
		success: function(data){
	        $('#repfirm-download-selectorlist').append($("<option></option>").attr("value", "0").text("Select RepFrim"));
	        $.each(data.repFirm, function(i, value) {
	            $('#repfirm-download-selectorlist').append($("<option></option>").attr("value", value.id).text(value.name));
	        });
		}		
	});



	$.ajax({
		type:'get',
		url: apiUrl+"corporation/users",
		dataType:"json",
        headers: {'Api-Key': apiKey,'User': userid},
		success: function(data){
	        $('#proposal-download-selector-3').append($("<option></option>").attr("value", "0").text("Select Sales Person"));
	        $.each(data.users, function(i, value){
	            $('#proposal-download-selector-3').append($("<option></option>").attr("value", value.initials).text(value.name));
	        });
		}		
	});

}


function getStartProposalWeek(start) {
    //build year one cal
    var weeks1 = buildBroadcastCal();
    for (var i = 0; i < weeks1.length; i++) {
        if (start == weeks1[i].column) {
            return i;
        }
    }
}



function getProposalWeekNumber(weeks, week) {
    for (var i = 0; i < weeks.length; i++) {
        var w = weeks[i].name;
        if (week == w) {
            return i + 1;
        }
    }
    return '';
}


//EXTERNAL REVE TYPE
function getRevenueTypes() {

	//API used here does not use session/cookie
	$.ajax({
		type:'get',
		url: apiUrl+"client/"+corpid+"/revenuetypes",
		dataType:"json",
        headers: {'Api-Key': apiKey,'User': userid},
		success: function(data){
			$('#select-revenue-type').append($("<option></option>").attr("value", '').text("Select Revenue Type"));
	        $.each(data.revenueType, function(i, value){
				$('#select-revenue-type').append($("<option></option>").attr("value", value.id).text(value.label));
	        });
		}		
	});	
	
}


//RATE CARD
function getRateCard(zoneid, cardid) {
	
    //if there is no card id then lets pass a 0
    if (typeof cardid === "undefined") {
        cardid = 0;
    }

    rateCardID = cardid;
    var selectedid = 0;

    if (zoneid == 0 && rateCardID == 0) {
        $('.apply-ratecard').css("display", "none");
        return;
    }

	ratecardDate = new Date().toString("yyyy-MM-dd");
    var url = apiUrl+"ratecard/" + zoneid + "/" + ratecardDate + "/" + rateCardMode + "/" + rateCardID + "/"+ ratecardGroup;
    
    $.ajax({
        type:'get',
        url: url,
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        success:function(data){
            $('#ratecard-selector').find('option').remove().end();
            if (data.result !== false) {
                ratecardData = data.response;
                ratecardSetup(true);

                ratecardHotPrograms = data.hotprograms;
                rateType = parseInt(data.responseHeader.ratecardtype);
                if(data.responseHeader.ratecardtype > 0){
                    ratecardZone = true; 
                }
                if(rateCardMode == 1) {
                    $('#ratecard-block').css("display", "inline");
                    var ratecards        = data.ratecards;
                    ratecardDate         = data.responseHeader.startdate;
                    ratecardGroup        = encodeURIComponent(data.responseHeader.name);
                    ratecardFixedPct     = data.rule.fixedpct;
                    ratecardFixedSeconds = data.rule.fixedseconds;
                    ratecardRotatorType  = data.rule.rotatortype;
                    ratecardID           = data.responseHeader.id;
                          ratecardZone = true;                    
                    if (ratecards.length == 0) {
                        $('#ratecard-selector').append($("<option></option>").attr("value", 0).text("No Ratecard Found"));
                    } else {
                        $.each(data.ratecards, function(i, value) {
                            var cardname = value.name;
                            $('#ratecard-selector').append($("<option></option>").attr("value", value.id).text(cardname));

                            if (value.select == 1) {
                                selectedid = value.id;
                            };
                        });
                    }
                }
                $("#ratecard-selector").val(selectedid);
            } 
            else{
                ratecardData;
                ratecardHotPrograms;
                ratecardSetup(false);
                $('#ratecard-selector').append($("<option></option>").attr("value", 0).text("No Ratecard Found"));
					ratecardZone = false;
            }

            $("#dialog-window").dialog("destroy");
        }
    });
};

//get the messages for the user
function getUserMessages() {
    $.ajax({
        type:'get',
        url: apiUrl+"share/newmessagecount",
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        success:function(data){
	        
			if(!data.isAuthorized){
				logout();
				return false;
			}
			if(!data.isPassSecure){
				loginMessage('NonSecurePwd');
				return false;
			}
			if(data.requireLogout){
				loginMessage('EmailUpdate');
				return false;
			}	
            if (data.messages > 0) {
                $('#message-count').html(data.messages);
                $('#message-count').css("display", "inline");
            } else {
                $('#message-count').css("display", "none");
            }
        }
    });
}

function loginMessage(event){
    if(localStorage.getItem("admin") !== '1' || event === 'after-migration'){	
		var title 	= '';
		var url 	= '';
		
		switch(event){
			case 'EmailUpdate':
				title 	= "ShowSeeker Email Update";
				url 	= 'includes/dialogs.php?evt=email-update&type=1&downloadformat=pdf&proposalid='+0+'&user='+userid+'&token='+apiKey;
				break;
			case 'NonSecurePwd':	
				title 	= "ShowSeeker Reset Password";
				url 	= 'includes/dialogs.php?evt=password-reset&type=1&downloadformat=pdf&proposalid='+0+'&user='+userid+'&token='+apiKey;
				break;
			case 'after-migration':	
				title 	= "ShowSeeker Notification";
				url 	= 'includes/dialogs.php?evt=after-migration&type=1&downloadformat=none&proposalid='+0+'&user='+userid+'&token='+apiKey;
				localStorage.setItem("ssmigrationdone","1");
				break;
		}
	
		$("#dialog-image-ppt-selector").empty().dialog("destroy");
		$("#dialog-image-ppt-selector").dialog({
		    show: {effect: "blind",duration: 1},   
			width:550,
			height:220,
			resizable: false,
			modal: true,
			draggable: false,
	        closeOnEscape: false,
			title: title,
			dialogClass: "pepper",
			open: function( event, ui ) {
				$('.ui-dialog :button').blur();
				$(".ui-dialog-titlebar-close", ui.dialog | ui).hide();			
				
			}
		}).load(url, function() {});
	}
	return false;	
};


/* Get and save user settings from the server and load them into memory */
function getUserSettings() {
	var settings;
    $.ajax({
        type:'get',
        url: apiUrl+"user/load/"+userid,
        dataType:"json",
        async:false,
        headers:{"Api-Key":apiKey,"User":userid},
        error:function(data){
	        var msg = '<center><div>Hi! Something went wrong. <br><br>Please contact <a href="mailto::support@showseeker.com">support@showseeker.com</a></div></center>';
	        $(msg).appendTo('body');
        },
        success:function(data){

	        //auto logout
	        if(data === null){
		        logout();
		        return false;
	        }
	        	        
			//pwdToken
			if(localStorage.getItem("token") === null) {
				pwdToken();
			}
				        
            if(localStorage.getItem("isLogin") === "1"){
		       localStorage.setItem("isLogin","0"); 
	        }
	        else{
		        //if(adminid === 0){            
				mixpanel.identify (userid);
				mixpanel.people.set({	"$email": data.userInfo.email,
										"$first_name": data.userInfo.firstName,
										"$last_name": data.userInfo.lastName});
				usrIp("Plus Refresh",{});
		        //}
	        }        
            settings 			= data.settings;
            altid1       		= data.userInfo.initials;
            corpid       		= data.userInfo.corporationId;
            fname        		= data.userInfo.firstName;
            lname        		= data.userInfo.lastName;
            markets      		= data.markets;
            tokendl      		= data.userInfo.tokenId
            rateCardMode 		= data.corporationSettings.ratecardMode;
			ratecardDefaultMode = data.corporationSettings.ratecardMode;
            regionsid          	= data.defaultOffice.marketId; 
            userOfficeId   		= data.defaultOffice.OfficeId;
            marketzones  		= data.zones;
			zoneid              = data.settings.lastZoneId;
            demographicsList 	= data.demographic;
			roles				= data.roles;
            
            //LAST OBSERVED MARKET/REGIION
            marketid 			= data.settings.lastMarketId;

			//LAST OBSERVED DMA            
			userSettings.lastDMAId = settings.lastDMAId;


			//hardcode to get Suddenlink proper RC Mode
			if(parseInt(marketid) === 210){
				rateCardMode = 0;
			}
			else if(parseInt(marketid) === 211){
				rateCardMode = 1;		
			}
            
			//KEEPER OF DMAS/ZONES
			allMarkets			= data.marketZones;
			

            //if last market id is not in users available markets then reset to the first market
            var tMarketIds = [];
            for (var i = 0; i < markets.length; i++) {
                tMarketIds.push(markets[i].id);
            };

            if(tMarketIds.indexOf(marketid) === -1){
                data.settings.lastMarketId = 0;
			}
			
            //if the last maket id is 0 then select teh first market
            if(data.settings.lastMarketId === 0){
                data.settings.lastMarketId = markets[0].id;
                marketid = markets[0].id;
            }


            //label the proposal title
            $('#label-user-name').html(data.userInfo.firstName + " " + data.userInfo.lastName + "'s Proposals");


            if (data.corporationSettings.ratecard == "1") {
                ratecard = true;
            }
            else {
                $('.apply-ratecard').css("display", "none");
                $('#div-ratecard-label').css("display", "none");
                $('#ratecard-block').css("display", "none");
                $('#download-show-ratecard').css("display", "none");
                $('#btn-apply-rates').css("display", "none");
                $('#btn-apply-rates-grey').css("display", "inline");
                $('#rcbutton-grp').css("display", "none");
                $('#download-show-rates-wrapper').css("display", "none");
            }

            //iseeker
            iseeker = data.defaultOffice.iseeker;
            if (iseeker == "No") {
                $('#iseeker-images-download').css("display", "none");
            }

            if (corpid == 14) {
                $('#betalink').css("display", "inline");
            }

            if (corpid == 4 || corpid == 13) {
                $('#termsblock').css("display", "inline");
            }

			if(corpid == 45){			
				$('#download-add-terms').prop('checked',true);
			}

            if (corpid == 25) {
                $('#termsblock').css("display", "inline");
                $('#download-add-terms').prop('checked', true);
            }
            //zolo
            if (corpid == 18) {
                $('#download-images-novar').css("display", "inline");
                $('#cleint-download-selectorlist-wrapper').css("display", "inline");
                $('#agency-download-selectorlist-wrapper').css("display", "inline");
                $('#repfirm-download-selectorlist-wrapper').css("display", "inline");

                $('#download-images-adsails').css("display", "none");
                $('#client-icon-wrapper').css("display", "none");
                $('#cleint-download-selector-wrapper').css("display", "none");
                getExternalSyncData();
            }
            //GCI
            if (corpid == 16) {
                $('#proposal-download-list-1').css("display", "inline");
                $('#proposal-download-list-2').css("display", "inline");
                $('#proposal-download-list-3').css("display", "inline");
                $('#download-images-eclipse').css("display", "inline");

                $('#proposal-download-label-1').html('Customer');
                $('#proposal-download-label-2').html('Agency');
                $('#proposal-download-label-3').html('Sales Person');

                $('#cleint-download-selector-wrapper').css("display", "none");
                $('#client-icon-wrapper').css("display", "none");

                //getExternalCorpData('Customer', 'Agency', 'Sales Person', altid1);
                getExternalSyncData();
                getRevenueTypes();
                $('.btn-apply-rates-disc').css("display", "inline");
            }

            //ICAN
            if (corpid === 33) {
				$('#iSeeker-access-btn, #ratecard-access-btn').hide();
				$('#iseekerManual, #rate-card-manual').hide();
            }
            
            //SNAPSHOT
            if(roles.snapshot === true){
                $('#snapshotLink').toggle();
					 $('#snapshot-manual').show();
            }
            
			//RATINGS ALPHA
            if(roles.ezRatings === '*'){
					$('#ss-alpha,#ezRatingsBtn,#ezratings-search-results-btn,#rtg-cols-ctrl').show();
					//$('#ezratings-downloads,#download-show-rates-inlineRtg,#download-show-ratings-wrapper').show();
					$('#ezratings-downloads,#download-show-ratings-wrapper').show();
					$('ratingsCol').closest('label').show();
					$('#dma-selector').closest('.row').show();					
					$('#ss-cols-ctrl,#rtg-cols-ctrl').css({'width':'49%'});
					$('#custom-proposal-cols').css({'width':'250px'});	           
					buildRatingsPopup();	//POPUP INTERFACE 
					getRatigsParamsList();	//SAVED RTG
					//loadBooksByMarket(settings.lastDMAId,[]); //LOAD RATINGS BOOKS
					userSettings.lastDMAId = settings.lastDMAId;
            }
            
            if($.inArray(parseInt(corpid),[14,46]) >= 0){
				$('#dma-selector').closest('.row').show();
				//$('#zoneSearh').show();	            
            }

			//SCX IMPORTER
            if(roles.SCXImporter === true){
	            $('#proposal-name').toggleClass( 'scxImporterOn', 'scxImporterOff');
				$('#scxImporter').show();
            }

            //Custom Package Builder access by Role
            if(roles.customPackageBuilder === true){
                $('#customPackageBuilderLink, #pkgs-custom-pkg-list').show();
            }

			//EZGRIDS ROLES
            if(roles.ezGrids === true){
	            $('#ezgridsLink').show();
            }

            //RATE CARD MANAGER
	        if(roles.RateCardManager === true){
    	        $('#rcm-redirect,#rate-card-manual,#ratecard-access-btn').show();
            }
            
            
            //USERS ADMIN 
	        if(roles.UsersAdmin === true && corpid === 46){    
    	        $('#adminLink').show();
            }

            
			if(parseInt(userid) === 3709){
				$('#api-images-download').show();
				$('#showtype-new').prop('checked',true).button('refresh');
			}

            $('#fullwrapper').css("visibility", "visible");
            $("#dialog-window").dialog("destroy");
			//$('#appVer').append('<br>Media Math Ver <span class="updatedpackage"> '+data.mediamathVersion.date+'</span><br>');
			$('#appVer').append('API Ver <span class="updatedpackage"> '+data.apiVersion.date+'</span>');

            //setup the interface based on the user settings
            if (settings) {
                userSettings = settings;
                if (settings.resultsGroup == 'off') {
                    setTimeout(function(){
                        $('#toggle-results').prop("checked", true);
                        $("#toggle-results-label .ui-button-text").text('Fixed');
                        $('#toggle-results').button("refresh");
                    }, 1000)
                }
            }

            //POPULATE REGIONS BASE IN USER PROFILE
            $.each(markets, function(i, value) {
                $('#market-selector').append($("<option></option>").val(value.id).text(value.name));
            });

            //setup the interface
            $.each(data.exports, function(i, value) {
                $(value.icon).css("display", "inline");
                if(value.name === 'adsails'){
	               $('#adSailsGuide').show();
                }
            });


			//***** HACK TO ALLOW SPECTRUM BAKESFIELD FORMERLY BRIGHHOUSE 
            $.each(data.markets, function(i, value) {
                if(parseInt(value.id) === 208){
	                $('#download-images-adsails').css("display", "inline"); 
	                return;
                }
            });

            //select the market from the dropdown
            $("#market-selector").val(marketid);

            //makes visible the list of markets
            if(markets.length > 1){
                $('#market-selector-block').css("display", "inline");
            }
        
            //call the functions needed for the next step
            loadShowSeekerPlus(roles);
            getZonesByMarketId(marketid);
            getUserMessages();
			
			//GETTING LIST OF PACAKGES
			packages_menu();
			
			if(!data.isPassSecure){
				//passwordExpiration();
				loginMessage('NonSecurePwd');
				return false;
			}
			if(data.requireLogout){
				loginMessage('EmailUpdate');
				return false;
			}
        }
    });
}

//grouping

//results datagrid
function groupByResultsDatagrid(type) {
    datagridSearchResults.groupByColumn(type);

    if (type == "availsDay" || type == 'availsShow') {
        return;
    }

    userSettings.resultsGroup = type;
    saveUserSettings();
}



//proposal
function groupByProposalDatagrid(type) {
    datagridProposal.groupByColumn(type);
    userSettings.schedulerGroup = type;
    saveUserSettings();
}



/* place any code that needs to be loaded in here */

//main loader that will trigger all the events

function loadShowSeekerPlus(roles) {
    buildGrids(roles);
    getUserProposals();
    showseekerLoaded();
    buildEzRatings()
}



//Menu system
function menuSelect(type) {

    $('#menu_saved, #menu_proposal_manager, #menu_build_proposal, #menu_downloads').removeClass("menu-selected");

    //swapSettingsPanel(false,false);

    //close them all
    $('#proposal-manager').css('display', 'none');
    $('#proposal-build').css('display', 'none');
    $('#proposal-download').css('display', 'none');
    $('#saved-searches').css('display', 'none');

    //panelManager('close');

    $('#' + type).css('display', 'inline');

    if (type == 'proposal-build') {
        $('#menu_build_proposal').addClass("menu-selected");
        $("#btn-pricing").css('display', 'inline');
    } else {
        $("#btn-pricing").css('display', 'none');
    }

    if (type == 'proposal-manager') {
        $('#menu_proposal_manager').addClass("menu-selected");
        //getUserProposals();
    }

    if (type == 'proposal-download') {
        $('#menu_downloads').addClass("menu-selected");
    }


    if (type == 'saved-searches') {
        $('#menu_saved').addClass("menu-selected");
    }



    windowManager();
}




function mouseWheel(e) {
    // disabling
    e = e ? e : window.event;
    if (e.ctrlKey) {
        if (e.preventDefault) e.preventDefault();
        else e.returnValue = false;
        return false;
    }
}


//goto edit mode
function openEditPanel() {
    resetEditRotatorItems();
    editRotator = true;
    datagridProposal.filterFixedLines(true);
    $(".header-rotator-edit").css('display', 'inline');
    $("#rotator-panel,rotator-type").css('display', 'inline');
    $(".header-rotator-create").css('display', 'none');
    $("#fixed-panel").css('display', 'none');
}



//panels
function panelManager(x,showInfo){
	if($('#totals-wrapper').is(':visible') && !showInfo){
		return;
	}
    if (x == 'close') {
        $('#side-menu,#fixed-panel').css('display', 'inline');
        $('#info-panel').css('display', 'none');
        datagridProposal.unselectAllRows();
    } else {
        $('#info-panel').css('display', 'inline');
        $('#side-menu').css('display', 'none');
    }
}


//AVAILS avails-selector
$('#avails-selector').change(function() {
    var val = $('#avails-selector').val();

    if (val == 'off') {
        availsOff();
    }

    if (val == 'daytime') {
        groupByResultsDatagrid('availsDay');
        datagridSearchResults.sortByColumn('availsDaySort');
    }

    if (val == 'titletime') {
        groupByResultsDatagrid('availsShow');
        datagridSearchResults.sortByColumn('titleFormat');
    }
});



//clone-zone-selector-available
$('#clone-zone-selector-available').change(function() {
    var val = $('#clone-zone-selector-available').val();

    if (val.length > 1) {
        $("#clone-zone-selector").attr("disabled", "disabled");
        $("#clone-zone-selector").css("opacity", 0.5);
    } else {
        $("#clone-zone-selector").removeAttr("disabled");
        $("#clone-zone-selector").css("opacity", 1);

    }
});


function ratecardSetup(rateon){
    if (rateon == true) {
        $('.apply-ratecard').css("display", "inline");
        $('#div-ratecard-label').css("display", "inline");
        $('#download-show-ratecard').css("display", "inline");
        $('#btn-apply-rates').css("display", "inline");
        $('#btn-apply-rates-grey').css("display", "none");        
        $('#download-show-rates-wrapper').css("display", "inline");

        if(rateCardMode == 1){
            $('#rcbutton-grp').css("display", "inline");
            $('#ratecard-block').css("display", "inline");
        }else{
            $('#rcbutton-grp').css("display", "none");
            $('#ratecard-block').css("display", "none");
        }

        //ratecard = true;
    } else {
        $('.apply-ratecard').css("display", "none");
        $('#div-ratecard-label').css("display", "none");
        $('#ratecard-block').css("display", "none");
        $('#download-show-ratecard').css("display", "none");
        $('#btn-apply-rates').css("display", "none");
        $('#btn-apply-rates-grey').css("display", "inline");
        $('#rcbutton-grp').css("display", "none");
        $('#download-show-rates-wrapper').css("display", "none");
        //ratecard = false;
    }
}


//browser zoom manager
function refresh() {
    return;
    //HACKED for certian users that always see ZOOM
    if (userid == '200' || userid == '152') {
        return;
    }
    var zoom = detectZoom.zoom();

    if (zoom != 1) {
        if (zoom != 0) {
            dialogZoom();
        };
    } else {
        $("#dialog-zoom").dialog("destroy");
    }
}


function reminderSend(id) {
    sendReminderOverlay();


    var d = new Date();
    var cnt = 0;

    //start
    var s = Date.parse("next monday").toString("yyyy-MM-dd");
    s += 'T00:00:00Z';

    //end
    var e = Date.parse("next monday").addWeeks(2).toString("yyyy-MM-dd");
    e += 'T23:59:59Z';

    var url = 'services/reminder-datasource.php?id=' + id;


    $.getJSON(url, function(data) {
        $.each(data, function(i, value) {
            var params = jQuery.parseJSON(value.search);

            params.startdate = s;
            params.endtime = e;

            var surl = solrSearchString(params, params.searchMode);
            surl += '&group=true&group.field=sort&sort=tz_start_pst asc';

            $.getJSON(surl, function(data) {
                $.post("services/reminders-send.php", {
                    data: data.grouped.sort.groups,
                    timezone: params.timezone,
                    title: value.name,
                    email: value.email,
                    notes: value.notes
                }, function(xdata) {
                    $("#dialog-send-reminder").dialog("destroy");
                });
            });
        });
    });
}



function resetEditRotatorItems() {
	editRotatorItems = {};

    //resetDaysButton();

    $('#sidebar-row-dates').css('background-color', '#f1f1f1');
    $('#sidebar-row-days').css('background-color', '#f1f1f1');
    $('#sidebar-row-times').css('background-color', '#f1f1f1');
    $('#sidebar-row-weeks').css('background-color', '#f1f1f1');
    $('#sidebar-row-spots').css('background-color', '#f1f1f1');
    $('#sidebar-row-rate').css('background-color', '#f1f1f1');
    $('#sidebar-row-networks').css('background-color', '#f1f1f1');
    schedulerCountWeeksFromDates();
}



function resetEditRotatorItems_OLD(){
    editRotatorItems.dates 	= 0;
    editRotatorItems.times 	= 0;
    editRotatorItems.days 	= 0;
    editRotatorItems.weeks 	= 0;
    editRotatorItems.spots 	= 0;
    editRotatorItems.rate 	= 0;

    $('#sidebar-row-dates').css('background-color', '#f1f1f1');
    $('#sidebar-row-days').css('background-color', '#f1f1f1');
    $('#sidebar-row-times').css('background-color', '#f1f1f1');
    $('#sidebar-row-weeks').css('background-color', '#f1f1f1');
    $('#sidebar-row-spots').css('background-color', '#f1f1f1');
    $('#sidebar-row-rate').css('background-color', '#f1f1f1');
    $('#sidebar-row-networks').css('background-color', '#f1f1f1');
    //$("#schedule-spots").val('');
    //$("#schedule-rate").val('');
    //schedulerCountWeeksFromDates();
    //resetDaysButton();
	datagridProposal.unselectAllRows();
}

//save user settings
function saveUserSettings() {
    $.ajax({
        type:'post',
        url: apiUrl+"user/settings",
        dataType:"json",
        processData:false,
        contentType: 'application/json',
        headers:{"Api-Key":apiKey,"User":userid},
        data: JSON.stringify({"settings":userSettings}),
        success:function(data){
            setUserSettingsToInterface(userSettings);
        }
    });
}



function setToggleResult() {
    var type = $('input:checkbox[name=toggle-results]:checked').val();

    if (type == "fixed") {
        groupByResultsDatagrid('off');
        userSettings.resultsGroup = 'off';
        $("#toggle-results-label .ui-button-text").text('Fixed');
    } else {
        groupByResultsDatagrid('showLine');
        userSettings.resultsGroup = 'showLine';
        $("#toggle-results-label .ui-button-text").text('Grouped');
    }
}



//once it is loaded lets run these functions
function showseekerLoaded() {
    //if there is unsaved data let give them an option to save it
    if (localStorage.proposalLines) {
        unsavedProposalOnExit();
    }
}



function togglesearchpanels(){
	if(builderpanel['panel1'] == false){
	    setPanel('panel1');
	}
	
	if(builderpanel['panel2'] == true){
	    setPanel('panel2');
	}
	
	if(builderpanel['panel3'] == true){
	    setPanel('panel3');
	}   
}



/* EVENT CLEAR INPUT FORM */
function tog(v){return v?'addClass':'removeClass';} 
    $(document).on('input', '.clearable', function(){
        $(this)[tog(this.value)]('x');
    }).on('mousemove', '.x', function( e ){
        $(this)[tog(this.offsetWidth-18 < e.clientX-this.getBoundingClientRect().left)]('onX');
    }).on('click', '.onX', function(){
        $(this).removeClass('x onX').val('').change();

        if(this.id == 'searchinput'){
            datagridTitles.resetFilter();
        }

        if(this.id == 'searchinput-actors'){
            datagridActors.resetFilter();
        }
});


//update the settings this is triggered on all the sode bar items but genres
function updateSettings() {

    if (loadingSearch) {
        saveSearchLoadParams();
        return;
    }


    var genreOpen = $("#dialog-genre").dialog("isOpen");
    if (genreOpen == true) {
        searchGenres();
    }


    if (searchType == 'title') {
        var titleOpen = $("#dialog-title").dialog("isOpen");
        if (titleOpen == true) {
            searchTitles();
        }
    }

    if (searchType == 'actor') {
        searchActors();
    }
}

function checkUpdate(){
    $.ajax({
        type:'get',
        url: apiUrl+"checkgoplusupdate",
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        success:function(data){
            if(String(appVersion) !== String(data.result.version)){
				loadDialogWindow('newversion', 'ShowSeeker Plus', 450, 180, 1, 0);
            }
        }
    });
}


function logout(){
    localStorage.removeItem("userId");
    localStorage.removeItem("apiKey");
    localStorage.removeItem("ssmigrationdone"); 
    localStorage.removeItem("admin");
    window.location.href = "login.php?logout=true";
}

function reloadShowSeeker(){
	window.location.reload();
}

function closeThisDialog(){
	setTimeout(function(){
		$('#dialog-disclaimer').dialog('destroy');
	}, 600)
}


function logUserEvent(eventId,requestBody,responseBody,proposalId){
    $.ajax({
        type:'post',
        url: apiUrl+"user/log/",
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        processData: false,
        contentType: 'application/json',
        data: JSON.stringify({"userId":userid,"location":1,"event":eventId,"request":requestBody,"response":responseBody,"proposalId":proposalId}),
        success:function(resp){}
    });
};


function errorLog(event,requestBody,responseBody,page){
		
	try{
	    $.ajax({
	        type:'post',
	        url: apiUrl+"error",
	        dataType:"json",
	        headers:{"Api-Key":apiKey,"User":userid},
	        processData: false,
	        contentType: 'application/json',
	        data: JSON.stringify({"userId":userid,"location":1,"error":event,"request":requestBody,"response":responseBody,"proposalId":proposalid,"page":page}),
	        success:function(resp){	        
		        return false;
	        },
	        error:function(){
				console.log('error');		        
		        return false;		        
	        }
	    });
	}catch(e){}
	return false;
};

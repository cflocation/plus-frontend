//Welcome to ShowSeeker Plus
//varibles list
document.domain = "showseeker.com";

//GoPlus Related vars
var apiUrl 		= "https://plusapi.showseeker.com/";
    apiUrl 		= "https://plusapi.showseeker.com/";
var appVersion 	= '1';

//Plus Rebuild Varibe Structure. This will be where we store a clean list as we progress
var plusZones = [];


var allzones = [];
var allMarkets;
var demographics = {};
var downloadtype = 0;
var ezcalendar;
var ezgrids;
var ezcalendarOpen = false
var ezgridsOpen = false;
var firstload = true;
var isresetting = false;
var launchgrids = false;
var loadingSearch = false;
var loadedSearch;
var markets = {};
var marketid = 0;
var marketzones = {};
var proposalShareType = 'Proposal';
var searchtitletype = 'title';
var selectedShowId = '';
var showinfourl = '';
var ssDialogs;
var stdcalendar = 0;
var timezone = '';
var tokendl	='';
var uniqueDmaList;
var zone = '';
var zoneid = 0;
var settings ='';

//datagrids
var datagridClients;
var datagridGenres;
var datagridHeaders;
var datagridImport;
var datagridMessages;
var datagridNetworks;
var datagridProposal;
var datagridProposalManager;
var datagridSearchResults;
var datagridTotals;
var datagridUsers;


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
/* End User Info */




//proposal panels
var builderpanel = {};
builderpanel.panel1 = true;
builderpanel.panel2 = true;
builderpanel.panel3 = false;

var container = {};
var btnOpener = {};



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


    container = $("#custom-proposal-cols");
	btnOpener = $('#customcolumnsbtn');	
	$(".multiselect").multiselect();    
});


function allDialogs(){
    $.ajax({
        type:'get',
        url: apiUrl+"dialog/list",
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        success:function(data){
	        var popDialogs = [];
			$.each(data.dialogs,function(i,val){
				val.message = val.snapshotMessage;
				popDialogs.push(val);
			});
			ssDialogs =  popDialogs;
        }
    });
}


/* datagrid functions */

function buildGrids() {
    datagridGenres 		    = new DatagridGenres();
    datagridNetworks 	    = new DatagridNetworks();
    datagridProposalManager = new DatagridProposalManager();
    datagridProposal 		= new DatagridProposal();
    datagridSearchResults 	= new DatagridSearchResults();
    //datagridTitles 			= new DatagridTitles('#titles-available', 'Titles Available', false);
	datagridTitles 			= new DatagridTitles('#titles-available', 'Titles Available <span id="titlesCount"></span>', false);
    datagridTitlesSelected 	= new DatagridTitles('#titles-selected', 'Selected Titles', true);
    datagridKeywords 		= new DatagridTitles('#keywords-entered', 'Keywords', true);


    datagridSearchResults.groupByColumn(userSettings.resultsGroup);
    datagridProposal.groupByColumn('zone');

    windowManager();
    firstload = false;
}


//build the token to call the webservice
function buildToken(url) {
    var url = "includes/token.php?userId="+userid+"&tokenId="+tokendl+"&url="+url;
    return $.ajax({
        url: url,
        dataType: "json"
     }).done(function(data){
	    if(data == 0){
		//    window.location = "/plus";
	    }
    });
}



/* Get and save user settings from the server and load them into memory */
function getUserSettings(){
	var settings;
    $.ajax({
        type:'get',
        url: apiUrl+"user/load/"+userid,
        dataType:"json",
        async:false,
        headers:{"Api-Key":apiKey,"User":userid},
        success:function(data){
            settings     = data.settings;
            corpid       = data.userInfo.corporationId;
            fname        = data.userInfo.firstName;
            lname        = data.userInfo.lastName;
            markets      = data.markets;
            lastZoneId   = data.settings.lastZoneId;
            marketid     = data.settings.lastMarketId;
            //allzones     = data.zones;
            marketzones  = data.zones;   
			roles		 = data.roles;       
			allMarkets	 = data.marketZones;
			
            //populate the markets based on the user
            $.each(markets, function(i, value) {
                $('#market-selector').append($("<option></option>").val(value.id).text(value.name));
            });


            //if the last maket id is 0 then select teh first market
            if(data.settings.lastMarketId === 0){
                data.settings.lastMarketId = markets[0].id;
                marketid = markets[0].id;
            }

            userSettings = settings;

            //select the market from the dropdown
            $("#market-selector").val(marketid);

            //makes visible the list of markets
            if(markets.length > 1){
	            $('#market-selector-block').css("display", "inline");
				}
            //label the proposal title
            $('#label-user-name').html("Currently using SnapShot");

			if(corpid == 33){
				$('#ez-grids-mod').toggle();
			}
            
            if(parseInt(corpid) === 46 || roles.ezRatings === true){
				$('#dma-selector').closest('.row').show();
            }

            //call the functions needed for the next step
            loadShowSeekerPlus();
            getZonesByMarketId(marketid);
        }
    });

}



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




//main loader that will trigger all the events
function loadShowSeekerPlus() {
    buildGrids();
    getUserProposals();
    showseekerLoaded();
    allDialogs();
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
        getUserProposals();
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







//panels
function panelManager(x){
    if (x == 'close') {
        $('#side-menu,#fixed-panel').css('display', 'inline');
        $('#info-panel').css('display', 'none');
    } else {
        $('#info-panel').css('display', 'inline');
        $('#side-menu').css('display', 'none');
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
        //searchActors();
    }
}

function checkUpdate(){
    $.ajax({
        type:'get',
        url: apiUrl+"checkgoplusupdate",
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        success:function(data){
            if(appVersion != data.result.version){
                console.log("Show reload message");
            }
        }
    });
}


function logout(){
    localStorage.removeItem("userId");
    localStorage.removeItem("apiKey");
    window.location.href = "../login.php?logout=true&app=snapshot";
}
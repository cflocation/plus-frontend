

//open the show information page
function showInformation(data) {

	//if the showdata is undefined then return it
	if (typeof data == "undefined" || typeof data.programid == "undefined")
		return;
	
	var infolive 		= "";
	var infonew 		= "";
	var premiereflag	= data.premiere;	 
	var url 			= "/services/1.0/show.cover.php";
	
	selectedShowId	= data.programid.substr(0,10);

	if(! $('#info-panel-wrapper').is(':visible'))
	    swapSettingsPanel('info',false);
  
    $("#info-panel-wrapper").load( "includes/showinfo.php", function() {

		if(showinfourl == ''){
			getShowInfoIni(url,data.programid,data.showid);
		}
		else{
			getShowInfo(data.programid,data.showid);
		}

	    $("#showinfo-title").html(data.title +' '+data.stars);
	
	    $("#showinfo-epititle").html(data.epititle);    
	
	    $("#showinfo-desc").html(data.desc);
		
	    if (data.live != "") {	    
			switch(data.live) {
			    case "Live":
			      infolive = '<span class="showinfo-live-block rounded-corners">Live</span>';
			      break;
			    case "Delay":
			      infolive = '<span class="showinfo-live-block rounded-corners">Delay</span>';
			      break;
			    default:
			      infolive = '<span class="showinfo-delay-block rounded-corners">Tape</span>';
			}	    
	    }
	
	    if (data.isnew != "") {
	        infonew = '<span class="showinfo-new-block rounded-corners">New</span>';
	    }
		
	    if (data.premiere != "") {
	    		if(premiereflag == 'Premiere'){
	    			premiereflag = 'Movie Premiere';
	    		}
	        infonew = '<span class="showinfo-premiere-block rounded-corners">' + premiereflag + '</span>';
	    }	
		
		
		var genre 		= '<span class=showinfo-genre-block rounded-corners>'+data.genre+'</span>';
	
		if(data.genre2 != ''){
			genre = genre+'<span class=showinfo-genre-block rounded-corners>'+data.genre2+'</span>';
		}
		
		if (data.year != "0" && typeof data.year != "undefined" && data.showtype == 'MV') {
			if(data.tvrating != '')
				$("#showinfo-released").html('<b>Rating: </b>' + data.tvrating);
			else
				$("#showinfo-released").html('');
			
			$("#showinfo-tvrating").html('<b>Released: </b>' + data.year);
		}
		
		if(String(data.genre).trim().length > 0){
			$("#showinfo-genre").html(genre).show();			
		}

		$("#showinfo-premiere").html(infonew);
		$("#showinfo-live").html(infolive);
	          
    });
    
}


function getShowInfoIni(url,programid,showid){


    $.when(buildToken(url)).done(function(token){
	    
	    showinfourl = token['url']+"&id=";

        url = token['url']+"&id="+showid;
        
        getShowCover(url);
         
	    getVideoInfo(programid);
	    
		getMoreInfo(showid)
	});
	
	
}

function getShowInfo(programid,showid){

        url = showinfourl+showid;

	    getVideoInfo(programid);
	    
	    getMoreInfo(showid);
	    
        getShowCover(url);
}


function getShowCover(url){

	$.getJSON(url, function(xdata) {
	    var img = xdata.images.cover;
		$('#showinfo-cover').html(img);
	    
	 });
			
}
         
function getVideoInfo(programid){

    $.ajax({
        url: "includes/moreinfo.video.php?id=" + programid,
        cache: false
    }).done(function(html) {
        if (html == '0' || iseeker == "No") {
            $("#video-launcher").hide();
        } else {
            $("#video-launcher").attr("onclick","openVideoPlayer('"+html+"')").show().button();
        }
    });

}
	    
function getMoreInfo(showid){
	$.ajax({
			url: "includes/moreinfo.check.php?id=" + showid,
			cache: false
		}).done(function(data) {
		if (data == 0 || iseeker == "No") {
			$("#btn-more-info").hide();
		}
		else {
			$("#btn-more-info").show();
			$("#btn-more-info").css({'width':'90px'}).button();
		}
		});		
	
}
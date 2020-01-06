function openEpisodeBox(showId,cellHeight,w,$thisShow){
	$('#shortDesc').removeClass();	
	$('#shortDesc').addClass('showCardSmall');
	boxDescription(showId.split('-')[0],w,cellHeight,$thisShow);	
};


function boxDescription(id,week,cellHeight,$thisShow){
	var html;
	var show 			= searchEvent(id,week);
	var globalPosition 	= $thisShow.parents().eq(1).position();
	var $showDetails 	= $('<div>',{"id":"desc"+id, "class":"showCardSmall"});
	var tmpShowCard		= new Showcard();

	//FIX DATE AND TIME
	var sD 				= show['tz_start_'+tz].replace('T',' ').replace('Z','');
	var sDate 			= tmpShowCard.shortDateFormat(sD);
	var sTime 			= tmpShowCard.shortTimeFormat(sD);
	var top				= globalPosition.top+20;
	var left			= globalPosition.left+20;
	
	//FIX BOX POSITION
	if(globalPosition.top+260 > globalHeight){		
		top = (top+cellHeight) - 260;
	}
	//FIX POSITION OF THEBOX
	if(globalPosition.left > 850){
		left = left - 98;
	}
	
	$showDetails.css({'top':top,'left':left});
  

   	html = 	"<div id=ptitle style='bottom:0px;' class='popupTitle hander'>";
   	html +=	"<div style='float:left'></div>";
   	html += "<div style='float:right'>";
   	html += "<i class='fa fa-window-close fa-lg' onclick=closeBox('"+id+"') style='color:#ff0000;'></i>";
   	html += "</div>";
   	html += "</div><br clear='both'>"
	$showDetails.append(html); 


    html = "<div class=title>"+show.title+"</div>";
	$showDetails.append(html);


    if(show.epititle.length > 1){
        html = "<div class=epititle>"+show.epititle+"</div>";
		$showDetails.append(html);
    }        

   	html = 	"<div class='tiny-title ellipsis' style='padding-bottom:2px; padding-top:2px;'>";
   	html +=	"<div style='float:left'>"+sDate+"</div>";
   	html += "<div style='float:right'>"+sTime+"</div>";
   	html += "</div>"
	$showDetails.append(html);
    
	if(show.stars){
		html = "<div class=stars>";        
		stars = show.stars.split('');
		for (i = 0; i < stars.length; i++) { 
        	if(stars[i] === '*'){
                html += "<i class='fa fa-star fa-lg showcard-star'></i>";
			}
			if(stars[i] === '+'){
            	html += "<i class='fa fa-star-half-o fa-lg showcard-star' ></i>";
			}
		}
		html +="</div>";  
		$showDetails.append(html);
    }         
	

	html = "<div class=showdescription>"+show.descembed+"</div></div>"
	$showDetails.append(html); 


	
    if(show.live.length > 0){
	    html = "<div id=islive class='flagLive'><b><i>"+show.live+"</i></b></div>";
		$showDetails.append(html);
	}
	

    if(show.premierefinale.length > 1){
	    if(show.premierefinale === 'Premiere'){
		    show.premierefinale = 'Movie Premiere';
	    }
        html = "<div class=premiere>"+String(show.premierefinale)+"</div>";
		$showDetails.append(html);
    }

    if(show.new.length > 0 && show.live.length < 1 && show.premierefinale.length < 1){
		html = "<div id='isNew' class='New'>"+show.new+"</i></b></div>";
		$showDetails.append(html);
    }

    
	if('tvrating' in show){
	    if(show.tvrating.length > 0){
			html = "<div class='tvratings'>"+show.tvrating+"</div>";
			$showDetails.append(html);
		}
	}

    if(show.year > 0){
        html = "<div id=year>Released: <b>"+show.year+"</b></div>";
		$showDetails.append(html);
    }


	html = "<div id=genre style=padding-top:5px;padding-bottom:5px; display:none;>"
	if('genre1' in show){
	    html += "<span class=genre>"+show.genre1+"</span>";
	    $('#genre').show();
    }
    
	if('genre2' in show){
		if(show.genre2 !== ''){
			html +="<span class=genre>"+show.genre2+"</span>"
		}
	}
    
    html +="</div>";
    
	$showDetails.append(html);



     
    
	
	$showDetails.draggable();		

	$thisShow.parents().eq(2).append($showDetails);
	//console.log(padre);
	
}

function searchEvent(id,wk){
	var sk = schedules.programming[wk];
	var i,j,daySk;
	var showInfo = {};
	
	loop1:	
		for(i=0; i<sk.length;i++){
			daySk = sk[i];
			for(j=0; j<daySk.length;j++){
				
				if(id	=== daySk[j].id){
					showInfo = daySk[j];
					break loop1;
				}
			}
		}
	return showInfo;
};


function closeBox(id){
	$("#desc"+id).remove();
	$("#"+id).find('div.cellText').removeClass('highlightedCell');
	$("#"+id).find('div.cellText').find('span.programTitle').removeClass('selectedEpisode');
}

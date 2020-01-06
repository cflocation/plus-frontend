function Showcard(){
    var self = this;

    //set all the varibles
    self.s3 = "https://showseeker.s3.amazonaws.com/on/";
    self.row;
    self.data = [];
    self.title;
    self.epititle;
    self.desc;
    self.genre;
    self.genre2;
    self.premiere;
    self.isnew;
    self.live;
    self.orgairdate;
    self.dateTime;
    self.showType;
    self.showId;
	 self.packageId;
    self.stationNum;
    self.year;
    self.pagerLength;
    self.pagerPage = 1;
    self.pagerCnt = 4;
    self.pagerNext = false;
    self.pagerPrev = false;
    self.pagerStart = 0;
    self.pagerEnd = self.pagerCnt;
    self.windowHeight = window.innerHeight;
    self.add = [];
	var showDetails;

    $(window).resize(function() {
        //self.windowHeight = window.innerHeight;
        //self.resizer();
    });


    //fix the showid
    self.fixId = function(showId){
        if(showId.length === 10)
            return showId + "0000";
 
        return showId;
    }


    //get the template
    self.getTemplate = function(){
		var rtgsOn = ratingsEnabled.getRatings('saved');
		$("#showcard").show().load("includes/showcard.html?ver="+ Math.random(), function(){
         $("#tab-showcard").tabs();
         self.parse();
     	});  
    };


    self.resizer = function(){}


    //parse the showcard data
    self.parse = function(){
		self.showCount();
		self.visible('epititle');
		self.visible('genre');
		self.visible('genre2');
		self.isVisible('year');
		$('.showcard-genre').hide();
		
		$("#showcard-desc").html(self.desc);
		$("#showcard-title").html(self.title);
		$("#showcard-epititle").html(self.epititle);
		$("#showcard-genre").html(self.genre);
		$("#showcard-genre2").html(self.genre2);
		$("#showcard-year").html('Released: '+self.year);
		$("#showcard-rating").html(self.rating);
		$("#showcard-cover").attr("src", self.thumb);
		$("#showcard-cover-full").attr("src", self.thumb);
		
		if(self.thumb == "https://showseeker.s3.amazonaws.com/on/default/max-default-dark.png"){
			$("#showcard-tab-cover").hide();
			$("#showcard-cover-full").hide();
		}
		
		//setup the responsive items for smaller browsers
		self.resizer();
		
		//turn on-off tabs
		self.tab('social');
		self.tab('cast');
		self.tab('add');
		
		//if the type is a movie then we will just hide the trailer tab
		if(self.showType !== 'MV' && self.data.trailerType !== "internetvideoarchive"){
			self.tab('trailer');
			self.buildTrailer();
		}
		else if(self.showType === 'MV' && self.mvtrailer.length > 0){
			$("#showcard-rating-wrapper").hide();            
			self.buildTrailerMV();    
		}

		//build the social tab
		self.buildSocial();
		
		if(self.cast.length > 0){
			self.buildCast();
		}

		//setup the premiere new live flag
		self.showFlag();

		//setup the stars
		self.buildStars(self.stars);

		//self.fixShowCount();
		

		self.fixDateTime();
		
		//show the sidebar
		$("#showcard-loading").hide();
		$("#showcard-container").show();
		
		if(self.genre!== undefined){
			if(self.genre.length > 0){
				$('#showcard-genre').show();
			}
		}
		if(self.genre2!== undefined){
			if(self.genre2.length > 0){
				$('#showcard-genre2').show();
			}
		}
    }
    

    //build the stars
    self.buildStars = function(stars){
        var stars = stars.split('');
        for (i = 0; i < stars.length; i++) { 
            if(stars[i] === '*'){
                $("#showcard-stars").append("<i class='fa fa-star fa-lg showcard-star' aria-hidden='true'></i>");
            }
            if(stars[i] === '+'){
                $("#showcard-stars").append("<i class='fa fa-star-half-o fa-lg showcard-star' aria-hidden='true'></i>");
            }
        }
    }
    
    //build the stars
    self.fixDateTime = function(){
	     var sD = self.dateTime.replace('T',' ').replace('Z','');
	     var sDate = self.shortDateFormat(sD);
	     var sTime = self.shortTimeFormat(sD);
	     
       $("#showcard-date").text(sDate);
       $("#showcard-time").text(sTime);
	};


    //setup the tab information
    self.tab = function(id){
        var x = "#showcard-tab-"+id;
        if(id === 'add' && !($.isEmptyObject(self[id]))){
            $(x).show();
        }	    
        if(self[id].length > 0){
            $(x).show();
        }
        
    }

    //is the tab visible or not
    self.visible = function(id){
	    if(self[id] !== undefined){
	        if(self[id].length === 0){
	            var x = "#showcard-"+id;
	            $(x).hide();
	        }
		}
    }

    //is the tab visible or not
    self.isVisible = function(id){
        if(parseInt(self[id]) === 0){
            var x = "#showcard-"+id;
            $(x).hide();
        }
    }


    //get the pager total
    self.getPagerCnt = function(cnt){
        self.pagerButtons();
        if(self.pagerEnd > cnt)
            return cnt;
        return self.pagerEnd;
    }

    //pager previous page
    self.pagerNext = function(){
        self.pagerStart = self.pagerPage * self.pagerCnt;
        self.pagerPage++;
        self.pagerEnd = self.pagerPage * self.pagerCnt;
        self.buildCast();
    }

    //pager previous page
    self.pagerPrev = function(){
        self.pagerPage--;
        self.pagerStart = self.pagerStart - self.pagerCnt;
        self.pagerEnd = self.pagerEnd - self.pagerCnt;
        self.buildCast();
    }

    //pager button visible
    self.pagerButtons = function(){
        //pager next visible
        if(self.pagerEnd >= self.pagerLength){
            $("#showcard-next").hide();
        }else{
            $("#showcard-next").show();
        }
            
        //pager prev visible
        if(self.pagerPage == 1){
            $("#showcard-prev").hide();
        }else{
            $("#showcard-prev").show();
        }
    }

    //build the cast list for the application
    self.buildCast = function(){
        $('#showcard-cast').empty();
        for (i = self.pagerStart; i < self.getPagerCnt(self.cast.length); i++) { 
            var url = self.s3+"photos/celebs/"+self.cast[i].images[0].URI;
            var name = self.cast[i].first + " " + self.cast[i].last;
            var html = '<div style="float:left;" class="castCover">';
            html	+= '<img style="width:110px;padding:2px" src="';
            html	+= url+'" onerror="imgError(this);"></img><br>';
            html 	+= '<center><small>'+name+'</small></center></div>';
            $('#showcard-cast').append(html);
        }
    };


    //build the social links for the application
    self.buildSocial = function(){
        for (i = 0; i < self.social.length; i++) { 
            $('#showcard-links').append('<span class="showcard-link"><a href="'+self.social[i].url+'" target="_blank" onclick="javascript:regSocial('+i+')"><img style="width:75px;padding:2px" src="'+self.social[i].icon+'"></img></a></span>');
        }
    };


    //build the ait trailer
    self.buildTrailerMV = function(){
		$('#showcard-year-wrapper').append('<span> &nbsp; </span><span class="hand" onclick="trailerShowcard(\''+self.mvtrailer[0].url+'\')" style="text-decoration:underline;"> Trailer <i class="fa fa-play-circle fa-lg" style="color:green;"></i></span>');
    };

    //build the multipule trailer links
    self.buildTrailer = function(){
        var len = self.trailer.length;
        if(self.trailer.length > 8){
            len = 8;
        }
        for (i = 0; i < len; i++) { 
            $('#showcard-trailers').append('<span class="showcard-hand" onclick="trailerShowcard(\''+self.trailer[i].url+'\'),regClip('+i+');"><img style="width:112px;padding:2px" src="'+self.trailer[i].thumb+'"></img></span>');
        }
    };


    //format the special
    self.showFlag = function(){
        var premiere = self.row.premierefinale;
        var isnew = self.row.isnew;
        var live = self.row.live;

        if(premiere.length > 0){
	        if(premiere === 'Premiere'){
		       premiere = 'Movie Premiere'; 
	        }
            $('#showcard-flag').append('<span class="showcard-premiere">'+premiere.replace('pNew','')+'</span>');
        }
        else if(live.length > 0){
            $('#showcard-flag').append('<span class="showcardLive">'+live+'</span>');
        }        
		else if(isnew.length > 0){
            $('#showcard-flag').append('<span class="showcard-new">'+isnew+'</span>');
        }
    }



    //get the showcard data from the server
    this.getShowcard = function(row){
        //set all the row varibles
        self.row 		= row;
        self.title 		= row.title;
        self.epititle 	= row.epititle;
        self.desc 		= row.descembed;
        self.genre 		= row.genre1;
        self.genre2 	= row.genre2;
        self.isnew 		= row.isnew;
        self.live 		= row.live;
        self.stars 		= row.stars;
        self.showId 	= self.fixId(row.showid);
        self.dateTime	= row['tz_start_'+tz];
		  self.year 	= 0;
        self.premiere 	= row.premierefinale;
        self.showType 	= row.showtype;
		self.packageId	= row.packageid;
        self.orgairdate = String(row.orgairdate).substr(5, 5)+'-'+String(row.orgairdate).substr(0, 4);
        self.stationNum = row.stationnum;
        
        if('year' in row){
	        self.year = row.year;
	    	}
        
        self.rating = "";
        
        if('tvrating' in row){
            self.rating = row.tvrating.trim();
        }
        
        //set the api urlx
        self.url = apiUrl + "showcard";

        //setup the data to post to the API server. This is how we get the proper cover
        var postData = {"showId":row.showid, 
	        			"title":self.title, 
	        			"live":row.live, 
	        			"epititle":self.epititle, 
	        			"genre1":self.genre, 
	        			"genre2":self.genre2, 
	        			"stationnum":self.stationNum,
	        			"packageId":self.packageId};

        //grab the showcard from the service
       $.ajax({
            type:'post',
            contentType: "application/json",
            url: self.url,
            data: JSON.stringify(postData),
            dataType:"json",
            headers:{"Api-Key":apiKey,"User":sswin.userid},
            success:function(resultData){

                //set the data from the return to use later
                self.data 	= resultData;
                self.thumb 	= resultData.thumb;
                self.cast 	= resultData.cast;
                self.social = self.fixSocial(resultData.links);
                self.trailer= resultData.trailer;
                self.mvtrailer= resultData.trailer;                
                self.pagerLength = self.cast.length;

                //override the description if it is blank
                if(self.desc.length === 0){
                    self.desc = resultData.info.description;
                }
                
                self.getTemplate();
            }
        });
    };


	self.showCount = function(){
		var url = apiUrl + "ezgrids/schedule/count";
		var data 		= {};
		data.showId 	= self.row.showid;
		data.zoneId 	= $('#zones').val();
		data.networkId	= d.networkId;
					
		$.ajax({
            type:'post',
            contentType: "application/json",
            url: url,
            data: JSON.stringify(data),
            dataType:"json",
            headers:{"Api-Key":apiKey,"User":sswin.userid},
            success:function(resp){
	            self.add = resp.counts;
	            self.fixShowCount();
            }
        });
		
				
	};
	
	self.fixShowCount = function(){
			var counts 		= self.add;
            //set the data from the return to use later
			var $all 		= $('<div>', {id:'showAll', 'class':'btn btn-warning hand'});
			var $premieres 	= $('<div>', {id:'showPremieres', 'class':'btn btn-danger hand'});
			var $finales 	= $('<div>', {id:'showFinales', 'class':'btn btn-danger hand'});
			var $live 		= $('<div>', {id:'showLive', 'class':'btn btn-live hand'});
			var $news 		= $('<div>', {id:'showNews', 'class':'btn btn-success hand'});
			var ids,ln,ln2,allCnt;
			var allAdded = false;
			$('#showcard-add').append($('<div>', {"class":"quickAddSep"}));
			
			
            $all.html('All <span class="badge">'+counts.allcnt+'</span>');
			$all.on('click',function(){bulkAdd(counts.all,'showAll')});
			$('#quickAdd').append($all);
			$('#quickAdd').append($('<div>', {"class":"quickAddSep"}));
			ids 	= fixLineIds(counts.all);
			ln 		= sswin.datagridProposal.mapLines(ids).length;
			ln2 	= counts.allcnt;
			if(ln === ln2){
				allAdded = true;
				$('#showAll').addClass('grayedoutButton');
			}			
            
            if(counts.premierecnt > 0){
				$premieres.html('Premieres <span class="badge">'+counts.premierecnt+'</span>');
				$premieres.on('click',function(){bulkAdd(counts.premiere,'showPremieres')});
				$('#quickAdd').append($premieres);
				$('#quickAdd').append($('<div>', {"class":"quickAddSep"}));
				if(!allAdded){
					ids 	= fixLineIds(counts.premiere);
					ln 		= sswin.datagridProposal.mapLines(ids).length;
					ln2 	= counts.premierecnt;
					if(ln > 0 && ln === ln2){
						$('#showPremieres').addClass('grayedoutButton');	
						allCnt+= ln2;
					}
				}
				
            }
            if(counts.finalecnt > 0){
				$finales.html('Finales <span class="badge">'+counts.finalecnt+'</span>');
				$finales.on('click',function(){bulkAdd(counts.finale,'showFinales')});
				$('#quickAdd').append($finales);
				$('#quickAdd').append($('<div>', {"class":"quickAddSep"}));
				if(!allAdded){
					ids 	= fixLineIds(counts.finale);
					ln 		= sswin.datagridProposal.mapLines(ids).length;
					ln2 	= counts.finalecnt;
					if(ln > 0 && ln === ln2){
						$('#showFinales').addClass('grayedoutButton');
						allCnt+= ln2;
					}
				}
            }
            if(counts.livecnt > 0){
				$live.html('Live <span class="badge">'+counts.livecnt+'</span>');
				$live.on('click',function(){bulkAdd(counts.live,'showLive')});
				$('#quickAdd').append($live);
				$('#quickAdd').append($('<div>', {"class":"quickAddSep"}));
				if(!allAdded){
					ids 	= fixLineIds(counts.live);
					ln 		= sswin.datagridProposal.mapLines(ids).length;
					ln2 	= counts.livecnt;
					if(ln > 0 && ln === ln2){
						$('#showLive').addClass('grayedoutButton');
						allCnt+= ln2;
					}
				}
            }
            if(counts.newcnt > 0){
				$news.on('click',function(){bulkAdd(counts.new,'showNews')});
				$news.html('New <span class="badge">'+ counts.newcnt+'</span>');
				$('#quickAdd').append($news);
				if(!allAdded){
					ids 	= fixLineIds(counts.new);
					ln 		= sswin.datagridProposal.mapLines(ids).length;
					ln2 	= counts.newcnt;
					if(ln > 0 && ln === ln2){
						$('#showNews').addClass('grayedoutButton');
						allCnt+= ln2;
					}
				}
            }

            if( allCnt === counts.allcnt || allAdded){
				var allBtns = '#showPremieres,#showFinales,#showLive,#showNews';
				$(allBtns).addClass('grayedoutButton disabledButton');
            }
            
			return false;
	};


    //fix the social links for display
    self.fixSocial = function(social){
        //if there is no social links then return nothing
        if(Object.keys(social).length === 0){
            return [];
        }
            
        //setup the array to return the values
        var rows = [];

        if(social.facebook.length > 0)
            rows.push({title:"Facebook", url:social.facebook, icon:self.s3+"default/links/facebook.png"});
        
        if(social.futon.length > 0)
            rows.push({title:"Futon Critic", url:social.futon, icon:self.s3+"default/links/futoncritic.png"});

        if(social.imdb.length > 0)
            rows.push({title:"IMDB", url:social.imdb, icon:self.s3+"default/links/imdb.png"});

        if(social.instagram.length > 0)
            rows.push({title:"Instagram", url:social.instagram, icon:self.s3+"default/links/instagram.png"});

        if(social.networkurl.length > 0)
            rows.push({title:"Network", url:social.networkurl, icon:self.s3+"default/links/network.png"});

        if(social.pintrest.length > 0)
            rows.push({title:"Pinterest", url:social.pintrest, icon:self.s3+"default/links/pinterest.png"});

        if(social.rottentomatoes.length > 0)
            rows.push({title:"Rotten Tomatoes", url:social.rottentomatoes, icon:self.s3+"default/links/rottentomatoes.png"});

        if(social.twitter.length > 0)
            rows.push({title:"Twitter", url:social.twitter, icon:self.s3+"default/links/twitter.png"});

        if(social.wiki.length > 0)
            rows.push({title:"Wiki", url:social.wiki, icon:self.s3+"default/links/wiki.png"});

        if(self.showType !== 'MV'){
            if(social.tvcom.length > 0)
                rows.push({title:"TV.com", url:social.tvcom, icon:self.s3+"default/links/tvcom.png"});

            if(social.tvdb.length > 0)
                rows.push({title:"TVDB", url:social.tvdb, icon:self.s3+"default/links/tvdb.png"});
        }

        //return the data
        return rows;
    };
    

	self.shortDateFormat = function(value) {
		var dArray = value.split(/[^0-9]/);
		return new Date(dArray[0],dArray[1]-1,dArray[2]).toString("MM/dd/yy");
	}; 
	    
	self.shortTimeFormat = function(value){
		var dArray = value.split(/[^0-9]/);
		var d =  new Date(dArray[0],dArray[1],dArray[2],dArray[3],dArray[4]).toString("h:mmtt");
		return d.replace('M','');//.replace(':00','');
	};   
    
};


//start the new showcard
var myShowcard = new Showcard();

//load the showcard
function loadShowcard(id) {
	var row = $('#'+id).data();
	
    if(!row){
        return;
	}

    //hide the showcard panel
    $("#showcard-container").hide();
    $("#info-panel").show();
    $("#showcard-loading").show();

    //start the showcard
    myShowcard.pagerStart = 0;
    myShowcard.pagerEnd = 4;
    myShowcard.pagerPage = 1;
    myShowcard.getShowcard(row);
};

//showcard next
function showcardNext(){
    myShowcard.pagerNext();
};


//showcard prev
function showcardPrev(){
    myShowcard.pagerPrev();
};

function fixLineIds(ids){
	var zoneid = $('#zones').val();
	var newIds = [];
	for(var i=0;i<ids.length;i++){
		newIds.push(ids[i]+'-'+zoneid);
	}
	return newIds;
}

trailerShowcard = function(url,myname,pos){
	var pos 			= 'center';
	var myname 			= 'mytrailer';
	var w 				= 760;
	var h 				= 475;
    var win				= null;
    var local 			= "../player/?url="+encodeURIComponent(url);
	var mixData 		= {};
	mixData.title 		= myShowcard.title;
	mixData.epiTitle	= myShowcard.epititle;
	mixData.callsign	= myShowcard.callsign;
	mixData.networkId	= myShowcard.stationNum;
	mixData.isnew		= myShowcard.isnew;
	mixData.live    	= myShowcard.live;
	mixData.showType	= myShowcard.showType;
	mixData.showId 		= myShowcard.showId;
	mixData.premieres	= myShowcard.premiere;
	mixData.trailer 	= myShowcard.trailer[0].url;


    if(pos=="center"){
        LeftPosition = (screen.width)?(screen.width-w)/2:100;TopPosition=(screen.height)?(screen.height-h)/2:100;
    }
    else if((pos!="center" && pos!="random") || pos==null){
        LeftPosition 	= 0;
        TopPosition		= 20;
    }
    settings='width='+w+',height='+h+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=no';
    
	sswin.mixTrack("Grids - ShowCard Movie Trailer",mixData);
    win = window.open(local,myname,settings);
}

function imgError(imageItem){
	$(imageItem).parent().remove();
}



regSocial = function(i){
	var mixData 		= {};
	mixData.title 		= myShowcard.title;
	mixData.epiTitle	= myShowcard.epititle;
	mixData.callsign	= myShowcard.callsign;
	mixData.networkId	= myShowcard.stationNum;
	mixData.isnew		= myShowcard.isnew;
	mixData.live    	= myShowcard.live;
	mixData.showType	= myShowcard.showType;
	mixData.showId 		= myShowcard.showId;
	mixData.premieres 	= myShowcard.premiere;
	mixData.social 		= myShowcard.social[i].title;	
	mixData.socialUrl 	= myShowcard.social[i].url;	
	
	sswin.mixTrack("Grids - ShowCard Social",mixData);     
	return false;   
}

regClip = function(i){
	var mixData 		= {};
	mixData.title 		= myShowcard.title;
	mixData.epiTitle	= myShowcard.epititle;
	mixData.callsign	= myShowcard.callsign;
	mixData.networkId	= myShowcard.stationNum;
	mixData.isnew		= myShowcard.isnew;
	mixData.live    	= myShowcard.live;
	mixData.showType	= myShowcard.showType;
	mixData.showId 		= myShowcard.showId;
	mixData.premieres 	= myShowcard.premiere;
	mixData.clip 		= myShowcard.trailer[i].url;	
	mixData.posted 		= myShowcard.trailer[i].posted;	
	
	sswin.mixTrack("Grids - ShowCard Clips",mixData);     
	return false;   
}


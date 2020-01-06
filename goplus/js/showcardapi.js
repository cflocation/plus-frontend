function Showcardapi(){
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
    self.showType;
    self.showId;
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


    $(window).resize(function() {
        self.windowHeight = window.innerHeight;
        self.resizer();
    });


    //fix the showid
    self.fixId = function(showId){
        if(showId.length === 10)
            return showId + "0000";
 
        return showId;
    }


    //get the template
    self.getTemplate = function(){
        $("#showcard").load( "includes/showcard.html", function() {
            $("#tab-showcard").tabs();
            self.parse();
        });
    }


    self.resizer = function(){
        if(self.windowHeight > 605){
            $("#showcard-cover-full").show();
            $("#showcard-tab-cover").hide();
        }else{
            $("#showcard-cover-full").hide();
            $("#showcard-tab-cover").show();
        }
    }


    //parse the showcard data
    self.parse = function(){
        self.visible('epititle');
        self.visible('genre');
        self.visible('genre2');
        self.isVisible('year');
        

		$("#showcard-desc").html(self.desc.substr(0, 255));
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
            //$('#tab-showcard').tabs('select', 1);
        }

        //setup the responsive items for smaller browsers
        self.resizer();


        //turn on-off tabs
        self.tab('social');
        self.tab('cast');


        //if the type is a movie then we will just hide the trailer tab
        if(self.showType !== 'MV' && self.data.trailerType !== "internetvideoarchive"){
            self.tab('trailer');
            self.buildTrailer();
        }else{
            $("#showcard-rating-wrapper").hide();
            self.buildTrailerMV();
        }
        
        //build the social tab
        self.buildSocial();
   
        if(self.cast.length > 0)
            self.buildCast();

        //setup the premiere new live flag
        self.showFlag();

        //setup the stars
        self.buildStars(self.stars);

        //show the sidebar
        $("#showcard-loading").hide();
        $("#showcard-container").show();
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



    //setup the tab information
    self.tab = function(id){
        if(self[id].length > 0){
            var x = "#showcard-tab-"+id;
            $(x).show();
        }
        
    }

    //is the tab visible or not
    self.visible = function(id){
        if(self[id].length === 0){
            var x = "#showcard-"+id;
            $(x).hide();
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

        //self.pagerEnd = self.pagerPage * self.pagerCnt;
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
            $('#showcard-cast').append('<div style="float:left;"><img style="width:110px;padding:2px" src="'+url+'"></img><br><center><small>'+name+'</small></center></div>');
        }
    }


    //build the social links for the application
    self.buildSocial = function(){
        for (i = 0; i < self.social.length; i++) { 
            $('#showcard-links').append('<span class="showcard-link"><a href="'+self.social[i].url+'" target="_blank"><img style="width:75px;padding:2px" src="'+self.social[i].icon+'"></img></a></span>');
        }
    }


    //build the ait trailer
    self.buildTrailerMV = function(){
        if(self.trailer.length > 0){
            $('#showcard-trailer').append('<div class="showcard-trailer" onclick="trailerShowcard(\''+self.trailer[0].url+'\')">Watch Trailer</div>');
            $("#showcard-trailer").show();
        }
    }


    //build the multipule trailer links
    self.buildTrailer = function(){
        var len = self.trailer.length;
        if(self.trailer.length > 8){
            len = 8;
        }
        for (i = 0; i < len; i++) { 
            $('#showcard-trailers').append('<span class="showcard-hand" onclick="trailerShowcard(\''+self.trailer[i].url+'\')"><img style="width:112px;padding:2px" src="'+self.trailer[i].thumb+'"></img></span>');
        }
    }


    //build the multipule trailer links
    self.buildApi = function(){
        var len = self.trailer.length;
        if(self.trailer.length > 8){
            len = 8;
        }
        for (i = 0; i < len; i++) { 
            $('#showcard-trailers').append('<span class="showcard-hand" onclick="trailerShowcard(\''+self.trailer[i].url+'\')"><img style="width:112px;padding:2px" src="'+self.trailer[i].thumb+'"></img></span>');
        }
    }



    //format the special
    self.showFlag = function(){
        var premiere = self.row.premiere.trim();
        var isnew = self.row.isnew.trim();
        var live = self.row.live.trim();

        if(live.length > 0){
            $('#showcard-flag').append('<span class="showcard-live">'+live+'</span>');
            return;
        }

        if(premiere.length > 0){
	        if(premiere === 'Premiere'){
		       premiere = 'Movie Premiere'; 
	        }
            $('#showcard-flag').append('<span class="showcard-premiere">'+premiere.replace('pNew','')+'</span>');
            return;
        }

        if(isnew.length > 0){
            $('#showcard-flag').append('<span class="showcard-new">'+isnew+'</span>');
            return;
        }

        $('#showcard-flag').hide();
    }



    //get the showcard data from the server
    this.getShowcard = function(row){
	    
        //set all the row varibles
        self.row = row;
        self.title = row.title.trim();
        self.epititle = row.epititle.trim();
        self.desc = row.desc.trim();
        self.genre = row.genre.trim();
        self.genre2 = row.genre2.trim();
        self.premiere = row.premiere.trim();
        self.isnew = row.isnew.trim();
        self.live = row.live.trim();
        self.stars = row.stars.trim();
        self.showType = row.showid.substring(0, 2);
        self.showId = self.fixId(row.showid);
        self.stationNum = row.stationnum;
        self.packageId = 0;
        if('packageId' in row){
	        if(row.packageId !== ''){
		        self.packageId = row.packageId;
		    }
	    }
        self.packageId = row.packageId;
        if(row.year){
	        self.year = row.year;
	    }
	    else{
	        self.year = 0;		    
	    }

        if(row.tvrating !== undefined){
            self.rating = row.tvrating.trim();
        }else{
            self.rating = "";
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
            headers:{"Api-Key":apiKey,"User":userid},
            success:function(data){

                //set the data from the return to use later
                self.data = data;
                self.thumb = data.thumb;
                self.cast = data.cast;
                self.social = self.fixSocial(data.links);
                self.trailer = data.trailer;
                self.pagerLength = self.cast.length;
				self.getExtras();
                //override the description if it is blank
                if(self.desc.length === 0){
                    self.desc = data.info.description;
                }
                self.getTemplate();
            }
        });
    };



	this.getExtras = function(row){
		

		var d = {"shows": [{"showId": String(self.row.showid),"station": String(self.stationNum)}]};

        //grab the showcard from the service
        $.ajax({
            type:'PUT',
            contentType: "application/json",
            url: 'https://showcards.showseeker.com/v1/shows',
            data: JSON.stringify(d),
            dataType:"json",
            headers:{"api-key":"e0084608db104217adb644761d733b7331829d4318143b588da812"},
            success:function(data){

				var $apiData = $('<div>', {id:'showcard-5', "class": "extraApi"});
						        
		        var $apiLinks = $('<div>', {id:'showcard-apiLink', "class": ""});
					$apiLinks.html('Social : <span class="ratematch">No</span>');
					
				var $apiRecomend = $('<div>', {id:'showcard-apiRecomend', "class": ""});;
					$apiRecomend.html('Recommendations : <span class="ratematch">No</span>');		        
	
				var $apiImg = $('<div>', {id:'showcard-apiImg', "class": ""});
					$apiImg.html('Image : <span class="ratematch">No</span>');
								        
				if(data.Result){
			        var r = data.Result[0];
			        
			        if(r.Links.length>0){
						$apiLinks.html('Social : Yes');
			        }	        
		
			        if(r.Recommend.length>0){
						$apiRecomend.html('Recommendations : Yes');
			        }
			       
					if(r.MediaType === 'MOVIE'){
						if(r.Images[0].Orientation === 'Portrait'){
							$apiImg.html('Image : Yes');
						}
					}
					else{							
						if(r.Images[0].Orientation === 'Landscape'){
							var img;
							if(data.Result[0].Images[1]){
								img = encodeURI(data.Result[0].Images[1].Url);
							}
							else{
								img = encodeURI(data.Result[0].Images[0].Url);
							}
							$apiImg.html('Image : Yes <span style="color:blue; text-decoration:underline" onClick=dialogImg("'+img+'")> Display Image</i></span>');
						}
					}
		            
	            }
				$($apiData).append('<hr>');
				$apiData.append($apiLinks);
				$apiData.append($apiRecomend);
				$apiData.append($apiImg);
				$('#showcard').append($apiData);
            }
        });
		
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
    }

};





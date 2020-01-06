var demoGroups;

function Ezrating(){
	//setting varibles	
	var self = this;
	self.row;
	self.htmlData;
	self.pagerPage 		= 0;
	self.windowHeight 	= window.innerHeight;
	self.ratingsData	= {};
	self.savedRatingsSettings = {};
	self.savedParams 	= [];

    $(window).resize(function() {
        self.windowHeight = window.innerHeight;
        self.resizer();
    });


    //get the template
    self.getTemplate = function(){
		$("#dialog-ratings").dialog({
			width:550,
			height:600,
			resizable: false,
			modal: false,
			draggable: true,
			dialogClass: "pepper",
			open: function( event, ui ) {
				$('.ui-dialog :button').blur();
				setUpRatingsPopUp();
				$(this).parent().children().children('.ui-dialog-titlebar-close').show();
			},
			beforeClose: function( event, ui ){
				var r = self.isSaved();
				if(!r){
					dialogDiscardRatings();
				}
				return r;
			}
		});
    };


    //get the pager total
    self.getPagerCnt = function(cnt){
        self.pagerButtons();
        if(self.pagerEnd > cnt){
            return cnt;
        }
        return self.pagerEnd;
    };

	self.pushDemo = function(demo){
		self.ratingsData.demos.push(demo);
	}

	//get the ezrating data from the server
	this.getEzRating = function(){  
		if($.isEmptyObject(self.ratingsData)){
			self.ini();
		}
	
		self.getTemplate();
	};

	self.get = function(v){
		return self[v];
	}
	
	self.getRatings = function(v){
		var r;
		if(!v){
			r = deepClone(self.ratingsData);
		}
		else{
			r = self.ratingsData[v];
		}
		return r;
	}

	self.getRatingsSettings = function(v){
		return self.savedRatingsSettings[v] ;
	}

    //initail state
    self.ini = function(){
	    
		self.userDemos 					= 0;
		self.ratingsData.average 		= 0;
		self.ratingsData.ratings 		= 1;
		self.ratingsData.ratingsEnabled = 1;
		self.ratingsData.impressions	= 1;
		self.ratingsData.rounded 		= 2;
		self.ratingsData.dma 			= true;
		self.ratingsData.bookId 		= 0;
		self.ratingsData.books 			= [];
		self.ratingsData.survey 		= '';
		self.ratingsData.cdma 			= false;
		self.ratingsData.marketId 		= 0;
		self.ratingsData.marketName 	= '';
		self.ratingsData.demos 			= [];
		self.ratingsData.saved 			= 0;
		self.ratingsData.project 		= 0;

		$('.demoValue').prop('disabled', true);
		return false;
    };

	self.isEmpty = function(item){
		return $.isEmptyObject(self[item]);
	}
	
	self.isSaved = function(){
		return self.validateState();
	}
	 
    //pager button visible
    self.pagerButtons = function(){};


    self.resizer = function(){
        if(self.windowHeight > 480){
            $("#ezrating-cover-full").show();
            $("#ezrating-tab-cover").hide();
        }else{
            $("#ezrating-cover-full").hide();
            $("#ezrating-tab-cover").show();
        }
    };

    self.reset = function(){
		//self.proposalParams = {};
		//self.ratingsData = {};
		self.pagerPage = 0;
		self.savedRatingsSettings = {};
		self.ini();
    };

	self.saveTempParams = function(){
		self.savedRatingsSettings = deepClone(self.ratingsData);
	};

	self.setRatingsData = function(){
		self.ratingsData = deepClone(self.savedRatingsSettings);
	};

	self.setProposalParams = function(pslRatings){
		self.savedRatingsSettings = pslRatings;
	};

	self.setRatings = function(v,val){
		self.ratingsData[v] = val;
	};

	self.set = function(v,val){
		self[v] = val;
	};

	self.status = function(){
		var r = true;
		if(isEmpty(self.savedRatingsSettings) && isEmpty(self.ratingsData)){
			r = false;
		}
		else if(isEmpty(self.ratingsData) && proposalid !== 0){
			r = false;			
		}
		else if(self.ratingsData.demos.length < 1){
			r = false;			
		}	
		
		return r;
	};

    //parse the ezrating data
    self.parse = function(){
	    
	    //setup the responsive items for smaller browsers
	    self.resizer();
	
	    //show the sidebar
	    $("#ezrating-loading").hide();
	    $("#ezrating-container").show();
    };

	//check if ratings is set in the app
	self.ratingsOn = function(){
		return self.getRatings('saved');
	};

    //setup the tab information
    self.tab = function(id){
        if(self[id].length > 0){
            var x = "#ezrating-tab-"+id;
            $(x).show();
        }
        
    };


	self.validateState = function(){
		var r = true;
		var demosCount 	= self.ratingsData.demos.length;
		var books 		= self.ratingsData.books;
		var saved 		= self.ratingsData.saved;
		
		if((saved !== 1 && $.isEmptyObject(self.savedRatingsSettings)) && (demosCount > 0 || books.length !== 0)){
			r = false;
		}			 	
		else if(!$.isEmptyObject(self.savedRatingsSettings)){
			if(saved !== 1){
				r = false;
			}
			else{
				var newBooksArray,pslBooksArray;

				for(var key in self.ratingsData){
				
					if(! $.isArray(self.ratingsData[key])){
						if(key === 'survey'){
							if(self.savedRatingsSettings[key].indexOf(self.ratingsData[key]) === -1){
								r = false;
								break;
							}
						}
						else if(self.ratingsData[key] !== self.savedRatingsSettings[key]){
							r = false
							break;
						}
					}
					else{
						newBooksArray = self.ratingsData[key];
						pslBooksArray = self.savedRatingsSettings[key];
				
						if(newBooksArray.length !== pslBooksArray.length){
							r = false
							break;				 	
						}
				
						if(key === 'books'){
							loop1:
							for(var k=0; k<self.ratingsData.books.length; k++){
							loop2:
								for(var kk in self.ratingsData.books[k]){
									if(books[k][kk] !== self.savedRatingsSettings.books[k][kk]){
										r = false
										break loop1;
									}
								}
							}
						}
						else{
							for(var i=0; i<newBooksArray.length; i++){
								if(pslBooksArray.indexOf(newBooksArray[i]) === -1){
									r = false;
								}
							}
						}
					}
				}
			}
		}
		else if($.isEmptyObject(self.savedRatingsSettings) && $.isEmptyObject(self.ratingsData)){
			r = false;
		}
		return r;
	};
	 

    //is the tab visible or not
    self.visible = function(id){
        if(self[id].length === 0){
            var x = "#ezrating-"+id;
            $(x).hide();
        }
    };    

};

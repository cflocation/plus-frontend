	var xhrDescription = false;

	var currentDivId = 0;
	var container = '';
	var currentNew = '';
	var currentLive = '';
	var currentColor = '';
	var zmax = 100000;
	var x_ = 20;
	var y_ = 25;
	
	function displayInfo(id,key,news,live,titleColor,x1,y1){
		currentDivId 	= id;
		container 		= 'outerContainer'+(id.split('-')[0]);
		currentNew 		= news;
		currentLive 	= live;
		currentColor 	= titleColor;
		x_=x1+3;
		y_=y1+15;
 		zmax=zmax+1;
 			

					//decompose alternative id
					
		var netId	=	key.substr(0,5);//NETWORK ID
		var showId	=	key.substr(5,14);//SHOW ID
		var airDate	=	key.substr(key.length-8); //DATE
		airDate 		=	airDate.substr(0, 4)+'-'+airDate.substr(4, 2)+'-'+airDate.substr(6, 2);
		var airTime	=	key.substr(19,4); //TIME
		airTime		= 	airTime.substr(0, 2)+':'+airTime.substr(2, 2)+':00';
		
		var altKey = 	showId+netId+airDate+' '+airTime
 		
 		$.getJSON("services/getinfo.php?showid="+key+"&altKey="+altKey, 
 			function(data) {	 	
	 			if(data.length>0){
					desc 		= data[0]['descembed']?data[0]['descembed']:'';
					isnew 		= currentNew;
					premiere 	= data[0]['premierefinale']?data[0]['premierefinale']:'';

					if(premiere == 'Premiere')
						premiere = "Movie Premiere";
						
					title 		= data[0]['title'];
					episode 	= data[0]['epititle'];
					
					if(!episode){
						episode = '';
					}

					orgairdate 	= '';//String(data[0]['orgairdate']);


					
					genre 	= data[0]['genre']?data[0]['genre']:'';
					live		= currentLive;
					if(live == 'Live')
						live = '(Live)';
																

				$("<div class=viewDescription id=" + container+currentDivId+" style=top:"+y_+"px;left:"+ x_ +"px;zIndex:"+ zmax +"><div class=closepopupdiv ><span class=closepopup>X</span></div><div class=insiderDiv><span id=islive>"+live+" </i></b></span><span id=ptitle style=color:"+currentColor+">"+title+"</span><BR><BR><span id=epititle>"+episode+" </span><span id=genre> "+genre+"</span><span id=premiere>"+premiere+"</span><br><span id=orgairdate>"+orgairdate+"</span><BR>"+desc+"</div></div>").appendTo('#'+container);

				$('#'+container+currentDivId +' .closepopupdiv').click(function(){$(this).parent('div').remove()});
			}
				return false;});
	}
	


	 

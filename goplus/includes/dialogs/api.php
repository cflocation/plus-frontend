<?php
	
	$id     		= $_GET['proposalid'];
	$userId			= trim(urldecode($_GET['user']));
	$apiKey			= trim(urldecode($_GET['token']));
	$proposal  		= getProposalMedia($id, $userId, $apiKey);	
	$shows  		= $proposal->shows;
	$utoken			= 0;
	$ids  			= array();
	$nets 			= array();
	$titles  		= array();
	$movies 		= array();
	$tv 			= array();

	print_r('<div id="apiJson" style="display:none;">{"shows":'.json_encode($shows).'}</div>');
	
	print('<div style="height:500px; overflow-x: hidden; overflow-y:scroll; width:354px;">');
	$cnt = 0;
	$observedTitles  = [];
	$observedShows   = [];
	
	foreach($proposal->showInfo as $key => $value){
    	
    	$thisTitle = substr($value->showid,0,2).$value->title;
    	$thisShow = $value->showid;
    	
        if ( in_array($thisTitle, $observedTitles) )
            continue;
            
    		print_r('<div class="apiSelected" style="float:left; width: 160px; padding-bottom:10px;"><center>');
    		print_r('<img src="'.$value->cover.'" style="max-width: 100px;"><br><div id="showcard-title" class="apiFixTitle">');
    		print_r($value->title.'</div></center></div>');	
    		if($cnt%2 != 0){
    			print_r('<div class="separator"></div>');
    		}
    		array_push($observedTitles,$thisTitle);
    		array_push($observedShows, $thisShow);
        
	}
	
	print_r('</div><br style="clear:bloth"><br><hr><center>');
	print_r('<div id="copyApi" class="button btn-green hander" style="width:150px; float:left;">');
	print_r('Copy All</div><div id="dnlApiJson" onclick="javascript:downloadApiJSON()" style="width:150px; float:right;" class="button btn-blue hander">');
	print_r('Download</div>');
	print_r('<div id="statApiJson" style="width:150px; float:right; display:none;"><i class="fa fa-spinner fa-spin fa-fw fa-3x"></i></div></center>');
	
	
	function getProposalMedia($id, $userId, $apiKey) {
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://plusapi.showseeker.com/proposal/showstationids/{$id}",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_SSL_VERIFYHOST=> false,
			CURLOPT_SSL_VERIFYPEER=> false,			
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_POSTFIELDS => "",
			CURLOPT_HTTPHEADER => array(
			"api-key: {$apiKey}",
			"cache-control: no-cache",
			"user: {$userId}"
			),
		));
	
		$response = curl_exec($curl);
		$err      = curl_error($curl);
		curl_close($curl);

		return json_decode($response);
	}

	
	
?>

<script>
	$('#copyApi,#dnlApiJson').button()
	$("#copyApi").on("click", function(){
		copyText(document.getElementById("apiJson"))
	});
	
	function copyText(element) {
		var $temp = $("<input>");
		$("body").append($temp);
		$temp.val($(element).text()).select();
		document.execCommand("copy");
		$temp.remove();
		$('.apiSelected').addClass('apiHighlight');
	}	
	
	function copyText1(elem){
		var origSlectionStart = elem.selectionStart;
		var origSelectionEnd = elem.selectionEnd;
		var target = elem;
		var currentFocus = document.activeElement;
		target.focus();
		target.setSelectionRange(0,target.value.length);
		var succeed = false;
		try{
			succeed = document.execCommand("copy");
		}catch(e){
			console.log(e);
		}
		return succeed;
	}


	function downloadJSON(d){
		var dt = {};
		dt.r = JSON.stringify(d);
		dt.psl = ($('#download-proposal-list option:selected').text()).replace(/[^\w\s]/gi, '').replace(/\s/g, '');
        $.ajax({
			type: "POST",
			url:"https://plus.showseeker.com/goplus/downloads/api.php",
            data: (dt),
            success:function(sd){
	            $('#statApiJson,#dnlApiJson').toggle();
	            window.location = sd;
				try{
					var pslInfo = datagridProposalManager.getProposalInfo(proposalid);
					var mixType	= 'SR-API-JSON';
					var d 		= buildMixDownloadParams(mixType, pslInfo.name,sd);
					usrIp('Download',d);
				}catch(e){}	
            },
            error:function(e){
				$('#statApiJson,#dnlApiJson').toggle();
            }
        });
        

	}
		

	function downloadApiJSON(){
		$('#statApiJson,#dnlApiJson').toggle();
		var d       = $('#apiJson').text();
        var shows   = JSON.parse(d)['shows'];
        var stations= [];
        var thisNet = 0;
        for(var s = 0; s< shows.length; s++){
            stations.push(shows[s]['station']);
        }
        //grab the showcard from the service
        $.ajax({
            type:'PUT',
            contentType: "application/json",
            url: 'https://showcards.showseeker.com/v1/shows',
            data: d,
            dataType:"json",
            headers:{"api-key":"e0084608db104217adb644761d733b7331829d4318143b588da812"},
            success:function(data){
                for(var i= 0; i< data.Result.length;i++){
                    
                    schedules   = [];
                    networks    = [];
                    var thisNet = 0;
                    
                    //FILTERING/REMOVING NETWORKS OUT OF THE PROPOSAL
                    for(var j = data.Result[i].Network.length-1; j >=0; j--){
                        
                        if(stations.includes(data.Result[i].Network[j]['Stationnum'])){
                            networks.push(data.Result[i].Network[j]);
                            thisNet = data.Result[i].Network[j]['Stationnum'];
                            stations.splice(stations.indexOf(thisNet),1);
                            break;
                        }
                    }

                    //FILTERING/REMOVING SCHEDULES OUT OF THE PROPOSAL                    
                    for(j = data.Result[i].Schedule.length-1; j >=0; j--){
                        
                        if(thisNet == data.Result[i].Schedule[j]['Stationnum']){
                            schedules.push(data.Result[i].Schedule[j]);
                        }
                    }
                    
                    data.Result[i].Network     = networks;
                    data.Result[i].Schedule    = schedules;    
                }
                
                downloadJSON(data);
            }
        });		

	}
	
	
	
	
	
</script>
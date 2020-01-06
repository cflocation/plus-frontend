function logEvent(log){
	mixpanel.track(log);
}

function mixTrack(event,params){
	mixpanel.track(event,params);
}

function mixId(userId){
	mixpanel.identify(userId);
}

function mixPeople(item){
	mixpanel.people.set(item);
}


function mixTrackDetails(eventName,params){
	var genre = '';
	for(var key in params.genre){
		genre += key;
	}
	mixpanel.track("Genre", {"genre": "hip-hop"});
	return false;
}

function usrIp(e,mixData){
	$.ajax({
        	type:'get',		
			url: 'https://godownload.showseeker.com/ip.php',
			success:function(ip){
				mixData.userIp = ip;
				mixTrack(e, mixData);
			}});
}

function buildMixDownloadParams(type,fileName,fileLink){
	
	var sort1 		= $("#download-sort-1 option:selected").text();
	var sort2 		= $("#download-sort-2 option:selected").text();
	var sort3 		= $("#download-sort-3 option:selected").text();
	var logos 		= $("#download-include-logos").prop("checked");
	var description= $("#download-include-description").prop("checked");
	var includenew 	= $("#download-include-new").prop("checked");
	var epititle	= $('#download-show-episode').prop("checked");
	
	var data = {"downloadType":type,
				"fileLink":fileLink,
				"fileName":fileName,
				"includeDesc":description,
				"includeEpiTitle":epititle,
				"includeLogos":logos,
				"includeNew":includenew,
				"proposalId":proposalid,
				"proposalName":fileName,
				"sortOpt1": sort1, 
				"sortOpt2":sort2,
				"sortOpt3":sort3,
				"userId":userid};
	return data;
};
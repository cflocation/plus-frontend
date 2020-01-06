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
	//mixTrack(e,params);	
	var genre = '';
	for(var key in params.genre){
		genre += key;
	}
	/*mixTrack(eventName, {"genre":genre});
	console.log(eventName,{'genre':genre});*/
	mixpanel.track("Genre", {"genre": "hip-hop"});
	console.log(eventName,{'genre':genre});
	return false;
}
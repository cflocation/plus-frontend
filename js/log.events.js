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
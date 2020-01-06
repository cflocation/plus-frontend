Array.prototype.contains = function(v) {
    for(var i = 0; i < this.length; i++) {
        if(this[i] === v) return true;
    }
    return false;
};

Array.prototype.unique = function() {
    var arr = [];
    for(var i = 0; i < this.length; i++) {
        if(!arr.contains(this[i])) {
            arr.push(this[i]);
        }
    }
    return arr; 
}

function effectiveDays(){
	
	var dayofwk 	= [];
	$.each($('#search-days').val(),function(i,val){
		if(val == 'ms'){
			dayofwk = ["0","1","2","3","4","5","6"];
			return;
		};
		if(val == 'mf'){
			dayofwk = ["0","1","2","3","4"];
			return;
		};
		if(val == 'ss'){
			dayofwk = ["5","6"];
			return;
		};
		var result = val-2;
		result = String(result).replace(-1, 6);
		dayofwk.push(result);
	});
	
	return dayofwk;
}

function effectiveLineDays(linedays){
	
	var dayofwk 	= [];
	
	$.each(linedays,function(i,val){
		if(val == 'ms'){
			dayofwk = ["0","1","2","3","4","5","6"];
			return;
		};
		if(val == 'mf'){
			dayofwk = ["0","1","2","3","4"];
			return;
		};
		if(val == 'ss'){
			dayofwk = ["5","6"];
			return;
		};
		var result = val-2;
		result = String(result).replace(-1, 6);
		dayofwk.push(result);
	});
	
	return dayofwk;
		
}
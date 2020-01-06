/* Formatters data outside Slickgrids*/

function formatterDays(val) {
  switch(val) {
  case 2:
    return "M";
  case 3:
    return "T";
  case 4:
    return "W";
  case 5:
    return "Th";
  case 6:
    return "F";
  case 7:
    return "Sa";
  case 1:
    return "Su";
  case "ms":
    return "M-Su";
  case "ss":
    return "S-Su";
  case "mf":
    return "M-F";
  }
}

//switch for the days
function daysAbbrSmallDayFix(val){
  switch (val)
    {
  case 1:
      x="M";
      return x;
      break;
  case 2:
      x="T";
      return x;
      break;
  case 3:
      x="W";
      return x;
      break;
  case 4:
      x="Th";
      return x;
      break;
  case 5:
      x="F";
      return x;
      break;
    case 6:
      x="Sa";
      return x;
      break;
    case 7:
      x="Su";
      return x;
      break;
    case "ms":
      x="M-SU";
      return x;
      break;
    case "ss":
      x="S-SU";
      return x;
      break;
    case "mf":
      x="M-F";
      return x;
      break;

  } 
}


function formatterDayOfWeek(val) {
    switch (val){
      case '1':
          return "Su";
      case '2':
        return "M";
      case '3':
        return "Tu";
      case '4':
        return "W";
      case '5':
        return "Th";
      case '6':
        return "F";
      case '7':
        return "Sa";
    }
}

//set the user info to save
function setUserSettingsToInterface(userSettings){

  var resultsGroup = "resultsGroup_"+userSettings.resultsGroup;
  var schedulerGroup = "schedulerGroup_"+userSettings.schedulerGroup;
  var autoSplitLines = "autoSplitLines_"+userSettings.autoSplitLines;

  $(".sf-menu li").each(function( index ) {
    $(this).removeClass("mselected");
  });


  $('#'+resultsGroup).addClass("mselected");
  $('#'+schedulerGroup).addClass("mselected");
  $('#'+autoSplitLines).addClass("mselected");
}


function checkArrays( arrA, arrB ){
	if(arrA.length !== arrB.length) return false;
	var cA = arrA.slice().sort().join(","); 
	var cB = arrB.slice().sort().join(",");
	return cA===cB;
}
function menuSelect(type,item){

  //panelEditBroadcastLine(1);
	$('#menu-1,#menu-2,#menu-3,#menu-4,#menu-5,#menu-6,#menu-7').removeClass("active");
	$('#tab-1, #tab-2, #tab-3, #tab-4, #tab-5, #tab-6, #tab-7').css('display', 'none');

	$('#'+type).css('display', 'inline');
	$('#'+item).addClass("active");
  

  tab = 0;


  if(type == 'tab-1'){
    //datagridRatecards.renderGrid();
    $('#sidebar-tab-1').css('display', 'inline');  
  }else{
    $('#sidebar-tab-1').css('display', 'none'); 
  }


  if(type == 'tab-2'){
    //datagridViewer.renderGrid();
    $('#sidebar-tab-2').css('display', 'inline');  
  }else{
    $('#sidebar-tab-2').css('display', 'none'); 
  }


  if(type == 'tab-3'){
    $('#sidebar-tab-3').css('display', 'inline'); 
    getNetworkCustomRules($("#sidebar-custom-breaks-network").val());
  }else{
    $('#sidebar-tab-3').css('display', 'none'); 
  }
  

  if(type == 'tab-4'){
    $('#sidebar-tab-4').css('display', 'inline'); 
    //datagridDayparts.renderGrid();
  }else{
    $('#sidebar-tab-4').css('display', 'none'); 
  }
  

  if(type == 'tab-5'){
    $('#sidebar-tab-5').css('display', 'inline');
    //changeDaypartToSelectedMarket();
    //datagridDaypartSelector.renderGrid();
  }else{
    $('#sidebar-tab-5').css('display', 'none'); 
  }


}



function checkApplicationStatus(type){



  /*
  var needsaving = datagridPricing.checkneedsaving();

  desiredItem = type;

  if(needsaving == true){
    loadDialogWindow('save-changes','Save Changes',380,180);
    return;
  }


  if(ratecardid == 0 && type != 1){
    loadDialogWindow('select-ratecard','ShowSeeker Error',380,150);
    return;
  }
*/

  menuSelect('tab-'+type,'menu-'+type);
}



function noSaveEvent(){
  datagridPricing.updateneedsaving(false);
  closeAllDialogs();
  menuSelect('tab-'+desiredItem,'menu-'+desiredItem);
}













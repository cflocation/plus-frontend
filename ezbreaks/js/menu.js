function menuSelect(type,item){

  //panelEditBroadcastLine(1);
	$('#menu-1,#menu-2,#menu-3,#menu-4,#menu-5,#menu-6,#menu-7,#menu-11,#menu-12,#menu-13,#menu-14,#menu-9').removeClass("active");
	$('#tab-1, #tab-2, #tab-3, #tab-4, #tab-5, #tab-6, #tab-7,#tab-11,#tab-12,#tab-13,#tab-14,#tab-9').css('display', 'none');
  $('#sidebar-tab-1, #sidebar-tab-2, #sidebar-tab-3, #sidebar-tab-4, #sidebar-tab-5, #sidebar-tab-6, #sidebar-tab-7,#sidebar-tab-11,#sidebar-tab-12,#sidebar-tab-14,#sidebar-tab-9').css('display', 'none');


	$('#'+type).css('display', 'inline');
	$('#'+item).addClass("active");
  

  tab = 0;


  if(type == 'tab-1'){
    datagridNetworks.renderGrid();
  }

  if(type == 'tab-2'){
    datagridViewer.renderGrid();
  }

  if(type == 'tab-3'){
    datagridCustomBreaks.renderGrid();
  }
  
  if(type == 'tab-5'){
    datagridDownloadSchedule.renderGrid();
  }

  if(type == 'tab-7'){
    datagridBreaks.renderGrid();
  }

  if(type == 'tab-9'){
    datagridCustomBreakRulesets.renderGrid();
  }

  if(type == 'tab-11'){
    datagridAccessNetworks.renderGrid();
  }

  if(type == 'tab-12'){
    datagridDownloadUpdateSchedule.renderGrid();
  }

  if(type == 'tab-13'){
    datagridChanges.renderGrid();
  }
  
  if(type == 'tab-14'){
    datagridQueue.renderGrid();
  }


}



function checkApplicationStatus(type){


  if(networkid == 0  && type == 2){
    loadDialogWindow('select-network','ShowSeeker Error',380,150);
    return;
  }

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
  $('#sidebar-tab-'+type).css('display', 'inline');
}



function noSaveEvent(){
  datagridPricing.updateneedsaving(false);
  closeAllDialogs();
  menuSelect('tab-'+desiredItem,'menu-'+desiredItem);
}













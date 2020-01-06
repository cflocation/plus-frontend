var datagridCustomrulewizard = null;

//Loading the wizardpopup
function showCustomRuleWizard()
{
	var w = $(window).width();
	var h = $(window).height();
	
	$('#dialog-window').load("/ezbreaks/include/custom-rule-wizard/step1.php", function()
	{
		$("#dialog-window").dialog({
			//dialogClass: "dialog-no-close",
			width:1200,
			height:530,
			resizable: true,
			title: 'Custom Break Rule Wizard',
			modal: true,
			draggable: true,
			open: function( event, ui ) {
				$('.ui-dialog :button').blur();

				//******************************************************************************
				datagridCustomrulewizard = new DatagridCustomrulewizard();
				$("#custom-rule-wizard-startdate,#custom-rule-wizard-enddate").datepicker( {numberOfMonths: 1});
				$("#custom-rule-wizard-startdate,#custom-rule-wizard-enddate").datepicker("option", "firstDay", 1 );
				$("#custom-rule-wizard-startdate,#custom-rule-wizard-enddate").datepicker( "option", "showTrailingWeek", true );
				$("#custom-rule-wizard-startdate,#custom-rule-wizard-enddate").datepicker( "option", "showOtherMonths", true );
				$("#custom-rule-wizard-startdate,#custom-rule-wizard-enddate").datepicker( "option", "selectOtherMonths", true );
				$("#custom-rule-wizard-starttime,#custom-rule-wizard-endtime,#custom-breakrule-wizard-step7-breaktime").timepicker({timeFormat: "hh:mm tt"});		
				$( "#selectable-step1,#selectable-step2,#selectable-step3,#selectable-step6").selectable();

				$("#selectable-step1 li, #selectable-step1 li").click(function() { $(this).addClass("ui-selected").siblings().removeClass("ui-selected"); });

				$("#selectable-step6 li").click(function() 
				{
					$(this).addClass("ui-selected").siblings().removeClass("ui-selected");
					if($('li.ui-selected','#selectable-step6').length > 0)
					{
						$('#custom-rule-wizard-step6-templateid,#custom-rule-wizard-step6-prevruleid').attr('disabled','disabled').css('opacity','0.45');		
						if($('li.ui-selected','#selectable-step6').data('choice') == 'template')
							$('#custom-rule-wizard-step6-templateid').removeAttr('disabled').css('opacity','1');
						if($('li.ui-selected','#selectable-step6').data('choice') == 'previousrule')
							$('#custom-rule-wizard-step6-prevruleid').removeAttr('disabled').css('opacity','1');
					}
				});

				$("#custom-rule-wizard-starttime").timepicker("option","onSelect", function(rel){
					if($('li.ui-selected','#selectable-step1').data('task') == 2 )
					{
						$("#custom-rule-wizard-endtime, #custom-breakrule-wizard-step7-breaktime").timepicker("option","minTime",rel);
						$("#custom-breakrule-wizard-step7-breaktime").val(rel);
					}
					
				});
				$("#custom-rule-wizard-endtime").timepicker("option","onSelect", function(rel){
					if($('li.ui-selected','#selectable-step1').data('task') == 2 )
						$("#custom-rule-wizard-starttime, #custom-breakrule-wizard-step7-breaktime").timepicker("option","maxTime",rel);
				});	

				$.getJSON("services/customrulewizard.php?eventtype=gettemplateoptions", function(data){
					$.each(data.data, function(i, value) {
				        $('#custom-rule-wizard-step6-templateid').append($("<option></option>").attr("value", value.id).text(value.name));
				    });
					console.log(data);
				});
				//******************************************************************************
			}
		});
	});
}

//Wizard Navigation functions
function goToStep2()
{
	if($('li.ui-selected','#selectable-step1').length == 1)
	{
		if($('li.ui-selected','#selectable-step1').data('task') == 2 )
		{
			$("#custom-breakrule-wizard-step7-breaktime").timepicker("option","timeFormat","HH:mm:ss");
			$("#custom-breakrule-wizard-step7-breaktime").timepicker("destroy");
			$("#custom-breakrule-wizard-step7-breaktime").timepicker({timeFormat:"HH:mm"});
		}

		$('#custom-breakrule-wizard-step1,#custom-breakrule-wizard-step2').toggle();
	}
	else
	{
		if($('#custom-rule-wizard-step1-valerror').length ==0)
			$('#custom-rule-wizard-step1-title').append('<span id="custom-rule-wizard-step1-valerror" class="custom-rule-validation-error"> - Select your choice</span>');
		return false;
	}	
}

function showStep3()
{
	if($('li.ui-selected','#selectable-step2').length > 0)
	{
		$('#selectable-step3').empty();
		var csIds = '';
		$('li.ui-selected','#selectable-step2').each(function(){  csIds += $(this).data('networkid')+',';});
		csIds = csIds.substr(0,csIds.length-1);
		$.getJSON("services/customrulewizard.php?eventtype=networkinstances&networkids="+csIds, function(data) {
	    	 $.each(data, function(i, value) {	
				$('#selectable-step3').append($('<li class="ui-widget-content ui-selected" data-instanceid="'+value.id+'"><img src="'+value.logofullpath+'"/><br/>'+value.instancecode+'</li>'));
	        });
	    });
	     $('#custom-breakrule-wizard-step2,#custom-breakrule-wizard-step3').toggle();
		return true;
	} else
	{
		if($('#custom-rule-wizard-step2-valerror').length ==0)
			$('#custom-rule-wizard-step2-title').append('<span id="custom-rule-wizard-step2-valerror" class="custom-rule-validation-error"> - Select network</span>');
		return false;
	}	
}

function showStep4()
{
 	if($('li.ui-selected','#selectable-step3').length > 0)
	{
		 if($('li.ui-selected','#selectable-step1').data('task') == 1 )
		  $('#custom-breakrule-wizard-step3,#custom-breakrule-wizard-step4').toggle();
		else if($('li.ui-selected','#selectable-step1').data('task') == 2 )
			$('#custom-breakrule-wizard-step3,#custom-breakrule-wizard-titlestep').toggle();
		else
			$('#custom-breakrule-wizard-step1,#custom-breakrule-wizard-step3').toggle();
		 return true;
	} else
	{
		if($('#custom-rule-wizard-step3-valerror').length ==0)
				$('#custom-rule-wizard-step3-title').append('<span id="custom-rule-wizard-step3-valerror" class="custom-rule-validation-error"> - Select network instance</span>');
			return false;
	}
}

function showStep4FromtitleStep()
{
	if($('#custom-rule-wizard-title').val() != "")
	{
		$('#custom-breakrule-wizard-titlestep,#custom-breakrule-wizard-step4').toggle();
		return true;
	} else
	{
		if($('#custom-rule-wizard-titlestep-valerror').length ==0)
				$('#custom-rule-wizard-titlestep-title').append('<span id="custom-rule-wizard-titlestep-valerror" class="custom-rule-validation-error"> - Enter title</span>');
			return false;
	}
}

function showStep5()
{
	if($('li.ui-selected','#selectable-step1').data('task') == 1 && ($('#custom-rule-wizard-startdate').val()=="" || $('#custom-rule-wizard-enddate').val()=="" ))
	{
		if($('#custom-rule-wizard-step4-valerror').length ==0)
				$('#custom-rule-wizard-step4-title').append('<span id="custom-rule-wizard-step4-valerror" class="custom-rule-validation-error"> - Start and End dates required</span>');
		return false;
	}

	$('#custom-breakrule-wizard-step4,#custom-breakrule-wizard-step5').toggle();
}

function showStep6()
{
	if($('li.ui-selected','#selectable-step1').data('task') == 1 && ($('#custom-rule-wizard-starttime').val()=="" || $('#custom-rule-wizard-endtime').val()=="" ))
	{
		if($('#custom-rule-wizard-step5-valerror').length ==0)
				$('#custom-rule-wizard-step5-title').append('<span id="custom-rule-wizard-step5-valerror" class="custom-rule-validation-error"> - Stand and End times required</span>');
		return false;
	}

	$('#custom-breakrule-wizard-step5,#custom-breakrule-wizard-step6').toggle();
}

function showStep7()
{
	if($('li.ui-selected','#selectable-step6').length > 0)
	{
		var ruleTemplType = $('li.ui-selected','#selectable-step6').data("choice");
		if(ruleTemplType != "manual")
		{
			var templid = (ruleTemplType == "template")?$("#custom-rule-wizard-step6-templateid").val():$("#custom-rule-wizard-step6-prevruleid").val();
			var url = "services/customrulewizard.php?eventtype=buildcustomruleitems&ruletempltype="+ruleTemplType+"&templid="+templid;
			url += "&rulesetType="+ $('li.ui-selected','#selectable-step1').data('task');
			url += "&fromtime="+$('#custom-rule-wizard-starttime').val();
			url += "&totime="+$('#custom-rule-wizard-endtime').val();

			$.getJSON(url, function(data) {
				datagridCustomrulewizard.populateDatagrid(data.data);
				datagridCustomrulewizard.refreshSorting();				
			});
		}
		datagridCustomrulewizard.renderGrid();			
		$('#custom-breakrule-wizard-step6,#custom-breakrule-wizard-step7').toggle();
	} else
	{
		if($('#custom-rule-wizard-step6-valerror').length ==0)
			$('#custom-rule-wizard-task1-step6-title').append('<span id="custom-rule-wizard-step6-valerror" class="custom-rule-validation-error"> - Select choice</span>');
		return false;
	}
}

function showStep8()
{
	var suggestedTitle = "";
	suggestedTitle += $('li.ui-selected','#selectable-step2').eq(0).text().trim() + " ";
	if($('li.ui-selected','#selectable-step2').length > 1)
	{
		var extraSel = Number($('li.ui-selected','#selectable-step2').length) - 1;
		suggestedTitle += "+ "+extraSel+" Nets ";
	}

	if($('li.ui-selected','#selectable-step1').data('task') == 1 )
		suggestedTitle += $('#custom-rule-wizard-startdate').val().substr(0,5);
	 else if($('li.ui-selected','#selectable-step1').data('task') == 2 )
	 	suggestedTitle += $('#custom-rule-wizard-title').val();

	$('#custom-rule-wizard-step8-rulesettitle').val(suggestedTitle);
	$('#custom-breakrule-wizard-step7,#custom-breakrule-wizard-step8').toggle();
}


//Wizard helper function
function showCustomBreakItemAddForm()
{
	if($('#custom-breakrule-wizard-step7-editnorowsselected').is(":visible"))
		$('#custom-breakrule-wizard-step7-editnorowsselected').slideUp()

	if(!$('#custom-breakrule-wizard-step7-edit').is(":visible")) return false;
	$('#custom-breakrule-wizard-step7-edit, #custom-breakrule-wizard-step7-delete, .custom-breakrule-wizard-step7-addeditform').slideToggle();
	$('#oprtype').val("ADD");
}

function showCustomBreakItemEditForm()
{
	var selectedIndexes = datagridCustomrulewizard.selectedIndexes();
	if(selectedIndexes.length != 1)
	{
		$('#custom-breakrule-wizard-step7-editnorowsselected').slideDown()
		return false;
	}

	$('#custom-breakrule-wizard-step7-editnorowsselected').slideUp()
	if(!$('#custom-breakrule-wizard-step7-add').is(":visible")) 
		return false;
	var selectedRows = datagridCustomrulewizard.selectedRows();

	$('#custom-breakrule-wizard-step7-add, #custom-breakrule-wizard-step7-delete, .custom-breakrule-wizard-step7-addeditform').slideToggle();
	$('#oprtype').val("EDIT");
	$('#editRowIndex').val(selectedIndexes[0]);
	$('#custom-breakrule-wizard-step7-breaktime').val(selectedRows[0].breakclocktime);
	$('#custom-breakrule-wizard-step7-length').val(selectedRows[0].breaklength);
}

function CustomBreakItemDeleteSelected()
{
	var selectedIndexes = datagridCustomrulewizard.selectedIndexes();
	if(selectedIndexes.length < 1)
	{
		$('#custom-breakrule-wizard-step7-deletenorowsselected').slideDown()
		return false;
	}

	datagridCustomrulewizard.deleteRows(selectedIndexes);
}

function cancelCustomBreakitemUpdate()
{
	$('#oprtype, #editRowIndex').val("");

	if(!$('#custom-breakrule-wizard-step7-add').is(":visible"))
		$('#custom-breakrule-wizard-step7-add').slideToggle();

	if(!$('#custom-breakrule-wizard-step7-edit').is(":visible"))
		$('#custom-breakrule-wizard-step7-edit').slideToggle();

	if(!$('#custom-breakrule-wizard-step7-delete').is(":visible"))
		$('#custom-breakrule-wizard-step7-delete').slideToggle();

	if($('.custom-breakrule-wizard-step7-addeditform').is(":visible"))
		$('.custom-breakrule-wizard-step7-addeditform').slideToggle();
}

function commitCustomBreakItemUpdate()
{
	if($('#oprtype').val() == "ADD")
	{
		var breakshowtime = $('#custom-breakrule-wizard-step7-breaktime').val();
		var breakclocktime = $('#custom-breakrule-wizard-step7-breaktime').val();
		var breaklength = Number($('#custom-breakrule-wizard-step7-length').val());
		datagridCustomrulewizard.addNewRow(breakshowtime,breakclocktime,breaklength);	
	}

	if($('#oprtype').val() == "EDIT")
	{
		var rowid = $('#editRowIndex').val();
		var breakshowtime = $('#custom-breakrule-wizard-step7-breaktime').val();
		var breakclocktime = $('#custom-breakrule-wizard-step7-breaktime').val();
		var breaklength = $('#custom-breakrule-wizard-step7-length').val();
		datagridCustomrulewizard.updateRow(rowid, breakshowtime,breakclocktime,breaklength);
		cancelCustomBreakitemUpdate();	
	}
}

function saveRuleSet()
{
	if($('#custom-rule-wizard-step8-rulesettitle').val().trim() == "")
	{
		if($('#custom-rule-wizard-step8-valerror').length ==0)
			$('#custom-rule-wizard-step8-title').append('<span id="custom-rule-wizard-step8-valerror" class="custom-rule-validation-error"> - Enter title</span>');
		return false;
	}

	var postData = {};

	postData.rulesetType = Number($('li.ui-selected','#selectable-step1').data('task'));

	postData.networkIds = [];
	$('li.ui-selected','#selectable-step2').each(function(){ postData.networkIds.push($(this).data('networkid')); });

	postData.instanceIds = [];
	$('li.ui-selected','#selectable-step3').each(function(){ postData.instanceIds.push($(this).data('instanceid')); });

	postData.title 			= $('#custom-rule-wizard-title').val();
	postData.fromDate 		= $('#custom-rule-wizard-startdate').val();
	postData.toDate 		= $('#custom-rule-wizard-enddate').val();
	postData.fromTime 		= $('#custom-rule-wizard-starttime').val();
	postData.toTime 		= $('#custom-rule-wizard-endtime').val();
	postData.timezone 		= $('#custom-rule-wizard-timezone').val();
	postData.rulesetLabel 	= $('#custom-rule-wizard-step8-rulesettitle').val();
	postData.liveSports 	= $('#custom-rule-wizard-livesportsonly').is(":checked")?true:false;
	postData.rulesetItems 	= datagridCustomrulewizard.getAllRows();
	
	$.post("services/customrulewizard.php?eventtype=createnewcustomruleset",postData,function(resp){ getNetworkCustomRules(0); closeAllDialogs(); }, 'json');
}

function viewCustomRuleItems(ruleSetId)
{
	loadDialogWindow('custom-rule-set-items','Rules',720, 480, ruleSetId);
	console.log(ruleSetId);
}

function confirmCustomBreakRuleSetDelete() {
	var selectedRows = datagridCustomBreakRulesets.selectedRows();
	if(selectedRows.length == 0)
	{
		loadDialogWindow('warning-no-rows-selected', 'No Rows Selected', 380, 160);

	} else
	{
		loadDialogWindow('confirm-delete-customruleset', 'Confirm Delete', 380, 150);
	}
}

function eventCustomBreakRuleSetDelete()
{
	var selectedRowIds = datagridCustomBreakRulesets.selectedRowIds();
	$.post("services/customrulewizard.php?eventtype=deletecustomruleset",{"ids":selectedRowIds},function(resp){ getNetworkCustomRules(0); closeAllDialogs(); }, 'json');
}
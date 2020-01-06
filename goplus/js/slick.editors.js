/***
 * Contains basic SlickGrid editors.
 * @module Editors
 * @namespace Slick
 */

(function ($) {
  // register namespace
  $.extend(true, window, {
    "Slick": {
      "Editors": {
        "Text": TextEditor,
        "Integer": IntegerEditor,
        "IntegerNonZero": IntegerEditorNonZero,
        "WeekSpots": SpotsValidator,
        "Spots": Spots,
        "Float": FloatEditor,
        "Date": DateEditor,
        "YesNoSelect": YesNoSelectEditor,
        "Checkbox": CheckboxEditor,
        "PercentComplete": PercentCompleteEditor,
        "LongText": LongTextEditor,
        "SpotsByWeek": SpotsByWeek
      }
    }
  });

	function TextEditor(args) {  
	    var $input;
	    var defaultValue;
	    var scope = this;

		this.init = function () {
			$input = $("<INPUT type=text class='editor-text' onkeypress='validate(event)' />")
			.appendTo(args.container)
			.bind("keydown.nav", function (e) {
				if (e.keyCode === $.ui.keyCode.LEFT || e.keyCode === $.ui.keyCode.RIGHT) {
					e.stopImmediatePropagation();
				}
			})
			.focus()
			.select();
		};

		this.destroy = function () {
			$input.remove();
		};
		
		this.focus = function () {
			$input.focus();
		};
		
		this.getValue = function () {
			return $input.val();
		};
		
		this.setValue = function (val) {
			$input.val(val);
		};
		
		this.loadValue = function (item) {
			defaultValue = item[args.column.field] || "";
			$input.val(defaultValue);
			$input[0].defaultValue = defaultValue;
			$input.select();
		};

		this.serializeValue = function () {
			return $input.val();
		};
		
		this.applyValue = function (item, state) {
			item[args.column.field] = state;
		};
		
		this.isValueChanged = function () {
			return (!($input.val() == "" && defaultValue == null)) && ($input.val() != defaultValue);
		};

		this.validate = function () {
			if (args.column.validator) {
				var validationResults = args.column.validator($input.val());
				if (!validationResults.valid) {
					return validationResults;
				}
			}
			return {
				valid: true,
				msg: null
			};
		};
		
		this.init();
	}
  
  
	function FloatEditor(args) {
		var $input;
		var defaultValue;
		var scope = this;
	
		this.init = function (){
			$input = $("<INPUT type=text class='editor-text'  onkeypress='return isNumberKey(event, this.value);' />");
	
			if(args.column.id.indexOf('rating') !== -1){
				$input = $("<INPUT type=text class='editor-text'  onkeyup='return userRating(this.value);'  maxlength='6' />");	
			}
	
			$input.bind("keydown.nav", function (e) {
				if (e.keyCode === $.ui.keyCode.LEFT || e.keyCode === $.ui.keyCode.RIGHT) {
				e.stopImmediatePropagation();
				}
			});
	
			$input.appendTo(args.container);
			$input.focus().select();
		};
	
		this.destroy = function(){
			$input.remove();
		};
	
		this.focus = function(){
			$input.focus();
		};
	
		this.loadValue = function (item) {
			defaultValue = item[args.column.field];
			$input.val(defaultValue);
			$input[0].defaultValue = defaultValue;
			$input.select();
		};
	
		this.serializeValue = function () {
			return parseFloat($input.val()) || 0;
		};
	
		this.applyValue = function (item, state) {
			item[args.column.field] = state;
		};
	
		this.isValueChanged = function () {
			return (!($input.val() == "" && defaultValue == null)) && ($input.val() != defaultValue);
		};
	
		this.validate = function () {
			if (isNaN($input.val())) {
				return {valid: false,	msg: "Please enter a valid Float"};
			}
		
			return {valid: true,msg: null};
		};
	
		this.init();
	};
  

	function IntegerEditor(args) {
		var $input;
		var defaultValue;
		var scope = this;
		var isValid = true;
		var decPlaces = 3;
		
		if(parseInt(args.column.dynamic) === 1){
			isValid = validateSpotsInFligtDates(args.item,args.column.id);
		}
		
	   	if(args.column.id.indexOf('impressions') !== -1){
	   		decPlaces = 10;
	   	}		
		
		this.init = function (isValid) {
			if(isValid === true){
				$input = $("<INPUT type=text class='editor-text'  onkeypress='return isValidNumberOnKeyPress(event,this.value);' maxlength='"+decPlaces+"'/>");
			}
			else{
				$input = $("<div class='outOfFlight' onclick='dialogHelpFlightDates()'></div>");				
			}
			
			$input.bind("keydown.nav", function (e) {
				if (e.keyCode === $.ui.keyCode.LEFT || e.keyCode === $.ui.keyCode.RIGHT) {
					e.stopImmediatePropagation();
				}
			});
			$input.appendTo(args.container);
			$input.focus().select();
		};
		
		this.destroy = function () {
			$input.remove();
		};
		
		this.focus = function () {
			$input.focus();
		};
		
		this.loadValue = function (item){
			if(args.item.lineType === 4){
				$input.prop('readonly',true).css({'background-color':'#ddd'});
			}		
			defaultValue = 0;
			if(item[args.column.field]){
				defaultValue = item[args.column.field];
			}
			$input.val(defaultValue);
			$input[0].defaultValue = defaultValue;
			$input.select();
		};
		
		this.serializeValue = function (){
			return parseInt($input.val(), 10) || 0;
		};
		
		this.applyValue = function (item, state) {
			item[args.column.field] = state;
		};
		
		this.isValueChanged = function(){
			return (!($input.val() == "" && defaultValue == null)) && ($input.val() != defaultValue);
		};
		
		this.validate = function (){
			if (isNaN($input.val())){
				return { valid: false, msg: "Please enter a valid integer" };
			}
			
			return { valid: true, msg: null };
		};
		
		this.init(isValid);
	
};
function IntegerEditorNonZero(args) {
		var $input;
		var defaultValue;
		var scope = this;
		
		this.init = function () {
			$input = $("<INPUT type=text class='editor-text'  onkeypress='return isValidNonZeroNumberOnKeyPress(event,this.value);' maxlength='3'/>");
			$input.bind("keydown.nav", function (e) {
				if (e.keyCode === $.ui.keyCode.LEFT || e.keyCode === $.ui.keyCode.RIGHT) {
					e.stopImmediatePropagation();
				}
			});
			$input.appendTo(args.container);
			$input.focus().select();
		};
		
		this.destroy = function () {
			$input.remove();
		};
		
		this.focus = function () {
			$input.focus();
		};
		
		this.loadValue = function (item){

			if(args.item.lineType === 4){
				$input.prop('readonly',true).css({'background-color':'#ddd'});
			}		
			defaultValue = 1;
			if(item[args.column.field]){
				defaultValue = item[args.column.field];
			}

			$input.val(defaultValue);
			$input[0].defaultValue = defaultValue;
			$input.select();
	

		};
		
		this.serializeValue = function (){
			return parseInt($input.val(), 10) || 0;
		};
		
		this.applyValue = function (item, state) {
			item[args.column.field] = state;
		};
		
		this.isValueChanged = function(){
			return (!($input.val() == "" && defaultValue == null)) && ($input.val() != defaultValue);
		};
		
		this.validate = function (){
			if (isNaN($input.val()) || $input.val() < 1){
				return { valid: false, msg: "Please enter a valid integer grater than zero" };
			}
			return { valid: true, msg: null };
		};
		
		this.init();
	
	};	


function Spots(args) {
		var $input;
		var defaultValue;
		var scope = this;
		
		this.init = function () {
			$input = $("<INPUT type=text class='editor-text'  onkeypress='return isNumberKey(event,this.value);' maxlength='3'/>");
			$input.bind("keydown.nav", function (e) {
				if (e.keyCode === $.ui.keyCode.LEFT || e.keyCode === $.ui.keyCode.RIGHT) {
					e.stopImmediatePropagation();
				}
			});
			$input.appendTo(args.container);
			$input.focus().select();
		};
		
		this.destroy = function () {
			$input.remove();
		};
		
		this.focus = function () {
			$input.focus();
		};
		
		this.loadValue = function (item){

			if(args.item.lineType === 4){
				$input.prop('readonly',true).css({'background-color':'#ddd'});
			}		
			defaultValue = 1;
			if(item[args.column.field]){
				defaultValue = item[args.column.field];
			}

			$input.val(defaultValue);
			$input[0].defaultValue = defaultValue;
			$input.select();
	

		};
		
		this.serializeValue = function (){
			return parseInt($input.val(), 10) || 0;
		};
		
		this.applyValue = function (item, state) {
			item[args.column.field] = state;
		};
		
		this.isValueChanged = function(){
			return (!($input.val() == "" && defaultValue == null)) && ($input.val() != defaultValue);
		};
		
		this.validate = function (){
			if (isNaN($input.val()) || $input.val() < 0){
				return { valid: false, msg: "Please enter a valid integer number" };
			}
			return { valid: true, msg: null };
		};
		
		this.init();
	
	};		

function SpotsValidator(args) {
		var $input;
		var defaultValue;
		var scope = this;
		var isvalid = validateSpotsInFligtDates(args.item,args.column.id);

		this.init = function (isvalid) {
			if(isvalid){
				$input = $("<INPUT type=text class='editor-text'  onkeypress='return isValidNonZeroNumberOnKeyPress(event,this.value)' maxlength='3'/>");
			}
			else{
				$input = $("<INPUT type=text class='editor-text' readonly='true' maxlength='3'/>");	
			}
			$input.bind("keydown.nav", function (e) {
				if (e.keyCode === $.ui.keyCode.LEFT || e.keyCode === $.ui.keyCode.RIGHT) {
					e.stopImmediatePropagation();
				}
			});
			$input.appendTo(args.container);
			$input.focus().select();
		};
		
		this.destroy = function () {
			$input.remove();
		};
		
		this.focus = function () {
			$input.focus();
		};
		
		this.loadValue = function (item){

			if(args.item.lineType === 4){
				$input.prop('readonly',true).css({'background-color':'#ddd'});
			}		
			defaultValue = 1;
			if(item[args.column.field]){
				defaultValue = item[args.column.field];
			}

			$input.val(defaultValue);
			$input[0].defaultValue = defaultValue;
			$input.select();
	

		};
		
		this.serializeValue = function (){
			return parseInt($input.val(), 10) || 0;
		};
		
		this.applyValue = function (item, state) {
			item[args.column.field] = state;
		};
		
		this.isValueChanged = function(){
			return (!($input.val() == "" && defaultValue == null)) && ($input.val() != defaultValue);
		};
		
		this.validate = function (){
			if (isNaN($input.val()) || $input.val() < 1){
				return { valid: false, msg: "Please enter a valid integer grater than zero" };
			}
			return { valid: true, msg: null };
		};
		
		this.init(isvalid);
	
	};	
	

  function DateEditor(args) {
    var $input;
    var defaultValue;
    var scope = this;
    var calendarOpen = false;

    this.init = function () {
      $input = $("<INPUT type=text class='editor-calendar'/>");
      $input.appendTo(args.container);
      $input.focus().select();
      $input.datepicker({
        showOn: "button",
        buttonImageOnly: true,
        buttonImage: "slickgrids/images/calendar.gif",
        firstDay: 1,
        showTrailingWeek: false,
        beforeShow: function () {
          calendarOpen = true
        },
        onClose: function () {
          calendarOpen = false
        }
      });
            
      $input.width($input.width() - 18);
    };

    this.destroy = function () {
      $.datepicker.dpDiv.stop(true, true);
      $input.datepicker("hide");
      $input.datepicker("destroy");
      $input.remove();
    };

    this.show = function () {
      if (calendarOpen) {
        $.datepicker.dpDiv.stop(true, true).show();
      }
    };

    this.hide = function () {
      if (calendarOpen) {
        $.datepicker.dpDiv.stop(true, true).hide();
      }
    };

    this.position = function (position) {
      if (!calendarOpen) {
        return;
      }
      $.datepicker.dpDiv
          .css("top", position.top + 30)
          .css("left", position.left);
    };

    this.focus = function () {
      $input.focus();
    };

    this.loadValue = function (item) {
      defaultValue = item[args.column.field];
      $input.val(defaultValue);
      $input[0].defaultValue = defaultValue;
      $input.select();
    };

    this.serializeValue = function () {
      return $input.val();
    };

    this.applyValue = function (item, state) {
      item[args.column.field] = state;
    };

    this.isValueChanged = function () {
      return (!($input.val() == "" && defaultValue == null)) && ($input.val() != defaultValue);
    };

    this.validate = function () {
      return {
        valid: true,
        msg: null
      };
    };

    this.init();
  }

  function YesNoSelectEditor(args) {
    var $select;
    var defaultValue;
    var scope = this;

    this.init = function () {
      $select = $("<SELECT tabIndex='0' class='editor-yesno'><OPTION value='yes'>Yes</OPTION><OPTION value='no'>No</OPTION></SELECT>");
      $select.appendTo(args.container);
      $select.focus();
    };

    this.destroy = function () {
      $select.remove();
    };

    this.focus = function () {
      $select.focus();
    };

    this.loadValue = function (item) {
      $select.val((defaultValue = item[args.column.field]) ? "yes" : "no");
      $select.select();
    };

    this.serializeValue = function () {
      return ($select.val() == "yes");
    };

    this.applyValue = function (item, state) {
      item[args.column.field] = state;
    };

    this.isValueChanged = function () {
      return ($select.val() != defaultValue);
    };

    this.validate = function () {
      return {
        valid: true,
        msg: null
      };
    };

    this.init();
  }

  function CheckboxEditor(args) {

    var $select;
    var defaultValue;
    var scope = this;

    this.init = function () {
      $select = $("<INPUT type=checkbox value='true' class='editor-checkbox' hideFocus>");
      $select.appendTo(args.container);
      $select.focus();
    };

    this.destroy = function () {
      $select.remove();
    };

    this.focus = function () {
      $select.focus();
    };

    this.loadValue = function (item) {
      defaultValue = item[args.column.field];
      if (defaultValue) {
        $select.attr("checked", "checked");
      } else {
        $select.removeAttr("checked");
      }
    };

    this.serializeValue = function () {
      return $select.attr("checked");
    };

    this.applyValue = function (item, state) {
      item[args.column.field] = state;
    };

    this.isValueChanged = function () {
      return ($select.attr("checked") != defaultValue);
    };

    this.validate = function () {
      return {
        valid: true,
        msg: null
      };
    };

    this.init();
  }

  function PercentCompleteEditor(args) {
    var $input, $picker;
    var defaultValue;
    var scope = this;

    this.init = function () {
      $input = $("<INPUT type=text class='editor-percentcomplete' />");
      $input.width($(args.container).innerWidth() - 25);
      $input.appendTo(args.container);

      $picker = $("<div class='editor-percentcomplete-picker' />").appendTo(args.container);
      $picker.append("<div class='editor-percentcomplete-helper'><div class='editor-percentcomplete-wrapper'><div class='editor-percentcomplete-slider' /><div class='editor-percentcomplete-buttons' /></div></div>");

      $picker.find(".editor-percentcomplete-buttons").append("<button val=0>Not started</button><br/><button val=50>In Progress</button><br/><button val=100>Complete</button>");

      $input.focus().select();

      $picker.find(".editor-percentcomplete-slider").slider({
        orientation: "vertical",
        range: "min",
        value: defaultValue,
        slide: function (event, ui) {
          $input.val(ui.value)
        }
      });

      $picker.find(".editor-percentcomplete-buttons button").bind("click", function (e) {
        $input.val($(this).attr("val"));
        $picker.find(".editor-percentcomplete-slider").slider("value", $(this).attr("val"));
      })
    };

    this.destroy = function () {
      $input.remove();
      $picker.remove();
    };

    this.focus = function () {
      $input.focus();
    };

    this.loadValue = function (item) {
      $input.val(defaultValue = item[args.column.field]);
      $input.select();
    };

    this.serializeValue = function () {
      return parseInt($input.val(), 10) || 0;
    };

    this.applyValue = function (item, state) {
      item[args.column.field] = state;
    };

    this.isValueChanged = function () {
      return (!($input.val() == "" && defaultValue == null)) && ((parseInt($input.val(), 10) || 0) != defaultValue);
    };

    this.validate = function () {
      if (isNaN(parseInt($input.val(), 10))) {
        return {
          valid: false,
          msg: "Please enter a valid positive number"
        };
      }

      return {
        valid: true,
        msg: null
      };
    };

    this.init();
  }

  /*
   * An example of a "detached" editor.
   * The UI is added onto document BODY and .position(), .show() and .hide() are implemented.
   * KeyDown events are also handled to provide handling for Tab, Shift-Tab, Esc and Ctrl-Enter.
   */
  function LongTextEditor(args) {
    var $input, $wrapper;
    var defaultValue;
    var scope = this;

    this.init = function () {
      var $container = $("body");

      $wrapper = $("<DIV style='z-index:10000;position:absolute;background:white;padding:5px;border:2px solid #cccccc; -moz-border-radius:3px; border-radius:3px;'/>")
          .appendTo($container);

      $input = $("<TEXTAREA hidefocus rows=5  id='textEditorLong'>")
          .appendTo($wrapper);

      $("<DIV style='text-align:right'><BUTTON>Save</BUTTON><BUTTON>Cancel</BUTTON></DIV>")
          .appendTo($wrapper);

      $wrapper.find("button:first").bind("click", this.save);
      $wrapper.find("button:last").bind("click", this.cancel);
      $input.bind("keydown", this.handleKeyDown);

      scope.position(args.position);
      $input.focus().select();
    };

    this.handleKeyDown = function (e) {
      if (e.which == $.ui.keyCode.ENTER && e.ctrlKey) {
        scope.save();
      } else if (e.which == $.ui.keyCode.ESCAPE) {
        e.preventDefault();
        scope.cancel();
      } else if (e.which == $.ui.keyCode.TAB && e.shiftKey) {
        e.preventDefault();
        args.grid.navigatePrev();
      } else if (e.which == $.ui.keyCode.TAB) {
        e.preventDefault();
        args.grid.navigateNext();
      }
    };

    this.save = function () {
      args.commitChanges();
    };

    this.cancel = function () {
      $input.val(defaultValue);
      args.cancelChanges();
    };

    this.hide = function () {
      $wrapper.hide();
    };

    this.show = function () {
      $wrapper.show();
    };

    this.position = function (position) {
      $wrapper
          .css("top", position.top - 5)
          .css("left", position.left - 5)
    };

    this.destroy = function () {
      $wrapper.remove();
    };

    this.focus = function () {
      $input.focus();
    };

    this.loadValue = function (item) {
      $input.val(defaultValue = item[args.column.field]);
      $input.select();
    };

    this.serializeValue = function () {
      return $input.val();
    };

    this.applyValue = function (item, state) {
      item[args.column.field] = state;
    };

    this.isValueChanged = function () {
      return (!($input.val() == "" && defaultValue == null)) && ($input.val() != defaultValue);
    };

    this.validate = function () {
      return {
        valid: true,
        msg: null
      };
    };

    this.init();
  }
  
  function SpotsByWeek(args){
    var $input, $wrapper;
    var defaultValue, sD, eD;
    var weekTotal;
    var scope = this;

    this.init = function () {
      var $container = $("body");
      $wrapper 	= $("<DIV class='gridwrapper' style='z-index:10000;position:absolute;'/>").appendTo($container);      
      $input 	= $("<div style='width:100%; height:30px background-color:yellow; line-height:30px;' class='ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix'>Spots by Week</div>").appendTo($wrapper);  


 $input = $('<table style="width: 100%;" class="spotsCalendar" cellpadding="0" cellspacing="0" id="spotByDayContainer"><thead><tr><th class=spotsMonday>M</th><th class=spotsTuesday>Tu</th><th class=spotsWednesday>W</th><th class=spotsThursday>Th</th><th class=spotsFriday>F</th><th class=spotsSaturday>Sa</th><th class=spotsSunday>Su</th></tr></thead><tbody><tr><td><input type="text" size="2" maxlength="2" id="spotsMonday" class="spotsbyweek spotsbyweekinline input-quarter rounded-corners"></td><td><input type="text" size="2" maxlength="2" id="spotsTuesday" class="spotsbyweek spotsbyweekinline input-quarter rounded-corners"></td><td><input type="text" size="2" maxlength="2" id="spotsWednesday" class="spotsbyweek spotsbyweekinline input-quarter rounded-corners"></td><td><input type="text" size="2" maxlength="2" id="spotsThursday" class="spotsbyweek spotsbyweekinline input-quarter rounded-corners"></td><td><input type="text" size="2" maxlength="2" id="spotsFriday" class="spotsbyweek spotsbyweekinline input-quarter rounded-corners"></td><td><input type="text" size="2" maxlength="2" id="spotsSaturday" class="spotsbyweek spotsbyweekinline input-quarter rounded-corners"></td><td><input type="text" size="2" maxlength="2" id="spotsSunday" class="spotsbyweek spotsbyweekinline input-quarter rounded-corners"></td></tr></tbody></table>').appendTo($wrapper);

 	$("<br><DIV style='text-align:right'><center><BUTTON class=btn-green>Save</BUTTON>&nbsp;<BUTTON class=btn-red>Cancel</BUTTON></center></DIV>").appendTo($wrapper);
      
 	 $inputs = $(".spotsbyweekinline");

      //$input = $("<TEXTAREA hidefocus rows=5  id='textEditorLong'>").appendTo($wrapper);

      $wrapper.find("button:first").bind("click", this.save);
      $wrapper.find("button:last").bind("click", this.cancel);
      //$input.bind("keydown", this.handleKeyDown);

      scope.position(args.position);
      //$input.focus().select();
      
      
    };

    this.handleKeyDown = function (e) {
      if (e.which == $.ui.keyCode.ENTER && e.ctrlKey) {
        scope.save();
      } else if (e.which == $.ui.keyCode.ESCAPE) {
        e.preventDefault();
        scope.cancel();
      } else if (e.which == $.ui.keyCode.TAB && e.shiftKey) {
        e.preventDefault();
        args.grid.navigatePrev();
      } else if (e.which == $.ui.keyCode.TAB) {
        e.preventDefault();
        args.grid.navigateNext();
      }
    };

    this.save = function () {
      	args.commitChanges();
    };

    this.cancel = function () {
      	$input.val(defaultValue);
      	args.cancelChanges();
    };

    this.hide = function () {
      	$wrapper.hide();
    };

    this.show = function () {
		$wrapper.show();
    };

    this.position = function (position) {
		$wrapper.css("top", position.top - 5).css("left", position.left - 5)
    };

    this.destroy = function () {
		$wrapper.remove();
    };

    this.focus = function () {
		$input.focus();
    };

    this.loadValue = function (item){
	    $('.btn-green,.btn-red').button();

		var theseDays 	= {};		
		var tmpDays 	= [];
		var ii			= 0;
	    var c 			= 0;
	    var thisWk  	= String(args.column.field);
		var nDay,tmpval;	

	    weekTotal 		= item[args.column.field];
        
        var sDateArr  	= item.startdatetime.split(/[^0-9]/);
        var eDateArr  	= item.enddatetime.split(/[^0-9]/);
        
	    sD 				= new Date(parseInt(sDateArr[0]), parseInt(sDateArr[1])-1, parseInt(sDateArr[2])).getTime();
	    eD 				= new Date(parseInt(eDateArr[0]), parseInt(eDateArr[1])-1, parseInt(eDateArr[2])).getTime();
			
		theseDays.spotsMonday 	= 2;
		theseDays.spotsTuesday 	= 3;
		theseDays.spotsWednesday= 4;
		theseDays.spotsThursday = 5;
		theseDays.spotsFriday 	= 6;
		theseDays.spotsSaturday = 7;
		theseDays.spotsSunday 	= 1;

		// CAST TO INTEGER 
		for(var x=0; x < item.day.length; x++){
			tmpDays.push(parseInt(item.day[x]));
		}

	    $input.find('input').each(function(i,val){
			$(this).val(0);
	    });



		for(var i in theseDays){

			nDay = new Date(parseInt(thisWk.substr(5, 4)),parseInt(thisWk.substr(1, 2))-1,parseInt(thisWk.substr(3, 2)));
			nDay.add(ii).day().getTime();
			$('.'+i).closest('th').html($('.'+i).closest('th').text() +'<br>'+ nDay.toString('M-d-yy'));
			if(tmpDays.indexOf(theseDays[i]) === -1 || nDay < sD  || nDay > eD){
				$('#'+i).prop("disabled", true).css({"background-color" : "#ccc"});
			}
			
			ii++;
		}
	
	    
		while(c < weekTotal){
			$input.find('input').each(function(i,element){
				
				if(c < weekTotal && $(this).is(':enabled')){

					tmpval = $(this).val();
					
					if(isNaN(tmpval) || tmpval === ''){
						tmpval = 1;
					}
					else{
						tmpval = parseInt(tmpval)+1;
					}
					
					$(this).val(tmpval);
					c++;
				}
			});
		};	    

    };

    this.serializeValue = function () {
	    //console.log('serilize');
	    return;
      //return $input.val();
    };

    this.applyValue = function (item, state) {
		//item[args.column.field] = state;
	    //console.log('apply Value');
		item[args.column.field] = newSpotsByDay
    };

    this.isValueChanged = function () {
	    //console.log('changed');
      return true;//(!($input.val() == "" && defaultValue == null)) && ($input.val() != defaultValue);
    };

    this.validate = function () {
	    console.log('calidate');
      return {
        valid: true,
        msg: null
      };
    };

    this.init();	  
  }
  
})(jQuery);

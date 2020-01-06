/***
 * Contains basic SlickGrid formatters.
 *
 * NOTE:  These are merely examples.  You will most likely need to implement something more
 *        robust/extensible/localizable/etc. for your use!
 *
 * @module Formatters
 * @namespace Slick
 */

(function($) {
    // register namespace
    $.extend(true, window, {
        "Slick": {
            "Formatters": {
                "PercentComplete": PercentCompleteFormatter,
                "PercentCompleteBar": PercentCompleteBarFormatter,
                "NetworkLogoSmall": NetworkLogoSmallFormatter,
                "YesNo": YesNoFormatter,
                "Days": DaysFormatter,
                "Time": TimeFormatter,
                "Item": ItemFormatter,
                "Published": PublishedFormatter,
                "Datetime": DatetimeFormatter,
                "Datetime2": DatetimeFormatter2,
                "Checkmark": CheckmarkFormatter,
                "Amt": AmtFormatter,
                "Amtpct": AmtpctFormatter,
                "Hottype": HottypeFormatter,
                "Special": SpecialFormatter,
                "Trash": TrashFormatter,
                "Yesno": YesnoFormatter,
                "Viewrow": ViewrowFormatter,
                "Viewrow2": ViewrowFormatter2,
                "EditRow": EditRowFormatter,
                "Breaklength": BreaklengthFormatter,
                "Html": HtmlFormatter,
                "ViewBreaks": ViewBreaksFormatter,
                "NoBreak": NoBreakFormatter,
                "LongTitle": LongTitleFormatter,
                "LongText": LongTextFormatter,
                "Download": DownloadFormatter,
                "Custombreakrulesettype": CustombreakrulesettypeFormatter,
                "ViewCustomRuleItems": ViewCustomRuleItemsFormatter,
                "Queuestatus": QueueStatusFormatter,                
                "SfoDownload": SfoDownloadFormatter,
                "SfoDataCompare": sfodatacompareFormatter,
            }
        }
    });




    function DownloadFormatter(row, cell, value, columnDef, dataContext) {
        return '<a title="View Email" href="http://ezbreaks.showseeker.com/xmls/'+value+'">'+value+'</a>';
    }




//return '<span title="'+network[1]+'">'+network[0]+'</span>';Viewrow

    function ViewrowFormatter2(row, cell, value, columnDef, dataContext) {
        return '<center><a title="View Email" href="javascript:viewchangesemail(\''+value+'\')"><i class="fa fa-eye fa-lg"></i></a></center>';
    }


    function ViewCustomRuleItemsFormatter(row, cell, value, columnDef, dataContext) {
        return '<center><a title="View Rule Items" href="javascript:viewCustomRuleItems(\''+value+'\')"><i class="fa fa-eye fa-lg"></i></a></center>';
    }

    function SfoDownloadFormatter(row, cell, value, columnDef, dataContext) {
        return '<center><a title="Download SFO file" href="javascript:downloadSfoFile(\''+value+'\')"><i class="fa fa-download fa-lg"></i></a></center>';
    }

    function sfodatacompareFormatter(row, cell, value, columnDef, dataContext) {
        return '<center><a title="..." href="javascript:showSfoDataCompare(\''+value+'\')"><i class="fa fa-eye fa-lg"></i></a></center>';
    }



    function DatetimeFormatter2(row, cell, value, columnDef, dataContext) {
        var re = Date.parse(value).toString("MM/dd/yyyy hh:mm tt");
        return re;
    }



    function LongTitleFormatter(row, cell, value, columnDef, dataContext) {
        return '<span  class="tooltip" title="'+value+'">'+value+'</span>';
    }

    function LongTextFormatter(row, cell, value, columnDef, dataContext) {
        if(typeof(value) == 'undefined') return '';
        return '<span title="'+value+'">'+value+'</span>';
    }



    function NoBreakFormatter(row, cell, value, columnDef, dataContext) {
        if(value == "0"){
            return '<span style="color:red">' + value + '</span>';
        }
        return value;
    }




    function CustombreakrulesettypeFormatter(row, cell, value, columnDef, dataContext) {
        if(value == "1")
            return 'Date Time';

        if(value == "2")
            return 'Show Title';

        return value;
    }


    function BreaklengthFormatter(row, cell, value, columnDef, dataContext) {
        if(value == "00:00:00"){
            return '<span style="color:red">' + value + '</span>';
        }
        return value;
    }



    function ViewrowFormatter(row, cell, value, columnDef, dataContext) {
        return '<center><a title="View Network" href="javascript:getNetworkBreaksForViewer('+value+')"><i class="fa fa-eye fa-lg"></i></a></center>';
    }

    function ViewBreaksFormatter(row, cell, value, columnDef, dataContext) {
        return '<center><a title="View Break Structure" href="javascript:getBreakStructure('+value+')"><i class="fa fa-folder-o fa-lg"></i></a></center>';
    }




    function YesnoFormatter(row, cell, value, columnDef, dataContext) {
        if (value == 0) {
            return 'No';
        }

        return "<span style='color:green'>Yes</span>";
    }



    function EditRowFormatter(row, cell, value, columnDef, dataContext) {
        return '<center><a title="Edit Row" href="javascript:editNetworkBreak('+value+')"><i class="fa fa-pencil-square-o fa-lg"></i></a></center>';
    }






    function PublishedFormatter(row, cell, value, columnDef, dataContext) {

        if (value > 0) {
            return '<center><a href="javascript:loadRatecardByID(' + value + ',1);"><img src="/images/tiny-s.png"></a></center>';
        }

        return '<center><img src="/images/tiny-s-off.png"></center>';
    }


    function TrashFormatter(row, cell, value, columnDef, dataContext) {
        return '<a href="javascript:removeDaypartFromMarket(' + value + ')"><i class="fa fa-trash-o fa-lg"></i></a>';
    }



    function SpecialFormatter(row, cell, value, columnDef, dataContext) {
        if (value == 1) {
            return 'Yes';
        }
        return 'No';
    }



    function HottypeFormatter(row, cell, value, columnDef, dataContext) {
        if (value == true) {
            return '$';
        }
        return "%";
    }


    function AmtpctFormatter(row, cell, value, columnDef, dataContext) {
        if (value == 0) {
            return '<span style="color:red">' + value + '</span>';
        }
        return value + "%";
    }


    function AmtFormatter(row, cell, value, columnDef, dataContext) {

        if (value == 0) {
            return '<span style="color:red">' + value + '</span>';
        }
        return value;
    }

    function ItemFormatter(row, cell, value, columnDef, dataContext) {
        return '<center><a href="javascript:loadRatecardByID(' + value + ',0);"><i class="fa fa-folder-open fa-lg"></i></a></center>';
    }


    function DatetimeFormatter(row, cell, value, columnDef, dataContext) {
        var re = Date.parse(value + " 00:00:00").toString("MM/dd/yyyy");
        return re;
    }






    function TimeFormatter(row, cell, value, columnDef, dataContext) {
        var d = value;
        var re = Date.parse("01/01/1980 " + d).toString("hh:mm tt");



        if (d == "") {
            return " NA ";
        }

        if (re == "") {
            //return "12M";
        }

        if (re == "11:59 PM") {
            return "12:00 MID";
        }

        if (d == "00:00:00") {
            return "12:00 AM";
        }
        return re;
    }


    function DaysFormatter(row, cell, value, columnDef, dataContext) {
        var arr = value.split(',');

        //return processDays(value);

        var re = '';
        for (var i = 0; i < arr.length; i++) {
            if (arr[i] == 2) {
                re += 'M ';
            }
            if (arr[i] == 3) {
                re += 'T ';
            }
            if (arr[i] == 4) {
                re += 'W ';
            }
            if (arr[i] == 5) {
                re += 'Th ';
            }
            if (arr[i] == 6) {
                re += 'F ';
            }
            if (arr[i] == 7) {
                re += 'Sa ';
            }
            if (arr[i] == 1) {
                re += 'Su ';
            }
        }
        return re;
    }

    function NetworkLogoSmallFormatter(row, cell, value, columnDef, dataContext) {
        if (value != "") {
           // return "<img width='25' src='http://ww2.showseeker.com/images/_thumbnailsW/"+value+"'>";
            return "<img width='25' src='https://showseeker.s3.amazonaws.com/images/netwroklogo/75/"+value+".png'>";
        } else {
            return;
        }
    }

    function PercentCompleteFormatter(row, cell, value, columnDef, dataContext) {
        if (value == null || value === "") {
            return "-";
        } else if (value < 50) {
            return "<span style='color:red;font-weight:bold;'>" + value + "%</span>";
        } else {
            return "<span style='color:green'>" + value + "%</span>";
        }
    }

    function PercentCompleteBarFormatter(row, cell, value, columnDef, dataContext) {
        if (value == null || value === "") {
            return "";
        }

        var color;

        if (value < 30) {
            color = "red";
        } else if (value < 70) {
            color = "silver";
        } else {
            color = "green";
        }

        return "<span class='percent-complete-bar' style='background:" + color + ";width:" + value + "%'></span>";
    }

    function YesNoFormatter(row, cell, value, columnDef, dataContext) {
        return value ? "Yes" : "No";
    }

    function CheckmarkFormatter(row, cell, value, columnDef, dataContext) {
        return value ? "<img src='../images/tick.png'>" : "";
    }


    function HtmlFormatter(row, cell, value, columnDef, dataContext) {
         return value ?  value.toString() : "";
    }

    function QueueStatusFormatter(row, cell, value, columnDef, dataContext) {
        
        if(value == "1") return "Queued";
        if(value == "2") return "In progress";
        if(value == "3") return "Complete";
    }
})(jQuery);
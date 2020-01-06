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
                "Checkmark": CheckmarkFormatter,
                "Amt": AmtFormatter,
                "Amtpct": AmtpctFormatter,
                "Hottype": HottypeFormatter,
                "Special": SpecialFormatter,
                "Trash": TrashFormatter,
                "Yesno": YesnoFormatter,
                "Viewrow": ViewrowFormatter,
                "EditRow": EditRowFormatter,
                "Url": UrlFormatter,
                "TVDB": TVDBFormatter,
                "UrlFB": UrlFBFormatter,
                "UrlTwitter": UrlTwitterFormatter,
                "History":MovieHistoryLinkFormatter
            }
        }
    });



    function UrlFBFormatter(row, cell, value, columnDef, dataContext) {
        if(value != ""){
            return '<center><a target="_balnk" href="'+value+'"><i class="fa fa-facebook-square fa-lg"></i></a></center>';
        }
        return "";
    }


    function UrlTwitterFormatter(row, cell, value, columnDef, dataContext) {
        if(value != ""){
            return '<center><a target="_balnk" href="'+value+'"><i class="fa fa-twitter fa-lg"></i></a></center>';
        }
        return "";
    }


    function MovieHistoryLinkFormatter(row, cell, value, columnDef, dataContext) {
        return '<a href="javascript:showMovieEditHistory();" title="Movie Edit logs"><i class="fa fa-history" aria-hidden="true"></i></a>';
    }



    function TVDBFormatter(row, cell, value, columnDef, dataContext) {
        if(value != "" && value > 0){
            return '<center><a target="_balnk" href="http://thetvdb.com/?tab=series&id='+value+'"><i class="fa fa fa-globe fa-lg"></i></a></center>';
        }
        return "";
    }



    function UrlFormatter(row, cell, value, columnDef, dataContext) {
        if(value != ""){
            return '<center><a target="_balnk" href="'+value+'"><i class="fa fa-globe fa-lg"></i></a></center>';
        }
        return "";
    }



    function ViewrowFormatter(row, cell, value, columnDef, dataContext) {
        return '<center><a href="javascript:getNetworkBreaksForViewer('+value+')"><i class="fa fa-eye fa-lg"></i></a></center>';
    }



    function YesnoFormatter(row, cell, value, columnDef, dataContext) {
        if (value == 0) {
            return 'No';
        }

        return "<span style='color:green'>Yes</span>";
    }



    function EditRowFormatter(row, cell, value, columnDef, dataContext) {
        return "<i class='fa fa-pencil-square fa-2x'></i>";
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
        return '<center><a href="javascript:loadShowImages(' + value + ');"><i class="fa fa-folder-open fa-lg"></i></a></center>';
    }


    function DatetimeFormatter(row, cell, value, columnDef, dataContext) {
        if(value == '') return '';
        var re = Date.parse(value + " 00:00:00").toString("MM/dd/yyyy");
        return re;
    }



    function TimeFormatter(row, cell, value, columnDef, dataContext) {
        var d = value;
        var re = Date.parse("01/01/1980 " + d).toString("hh:mm tt");



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

        return processDays(value);

        var re = '';
        re += '<span class="label secondary round">' + arr.length + '</span> ';
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
            return "<img width='25' src='http://ww2.showseeker.com/images/_thumbnailsW/"+value+"'>";
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
})(jQuery);
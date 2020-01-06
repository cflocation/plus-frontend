//function build datagrid
function DatagridPricingBroadcast() {
    var self = this;
    var grid;
    var data = [];
    var datatemp;
    var dataView;
    var selectedRows = [];
    var daypartslist;;

    var togglerate = true;
    var togglefixedrate = true;
    var togglefixedratepercent = false;
    var undos = [];
    var sortcol = "starts";
    var editmode = false;


    //set the columns
    var columns = [{
        id: "fname",
        name: "Name",
        field: "fname",
        width: 150,
        minWidth: 150,
        selectable: true,
        editor: Slick.Editors.Text,
        sortable: true
    }, {
        id: "starts",
        name: "Start Time",
        field: "starts",
        width: 100,
        minWidth: 100,
        formatter: Slick.Formatters.Time,
        sortable: true
    }, {
        id: "stops",
        name: "End Time",
        field: "stops",
        width: 100,
        minWidth: 100,
        formatter: Slick.Formatters.Time,
        sortable: true
    }, {
        id: "weekdays",
        name: "Days",
        field: "weekdays",
        width: 100,
        minWidth: 100,
        formatter: Slick.Formatters.Days,
        sortable: true
    }, {
        id: "rate",
        name: "Daypart",
        field: "rate",
        width: 100,
        minWidth: 100,
        maxWidth: 100,
        formatter: Slick.Formatters.Amt,
        editor: Slick.Editors.Text,
        cssClass: 'centertext rate',
        sortable: true
    }, {
        id: "ratefixed",
        name: "Fixed",
        field: "ratefixed",
        width: 100,
        minWidth: 100,
        maxWidth: 100,
        formatter: Slick.Formatters.Amt,
        editor: Slick.Editors.Text,
        cssClass: 'centertext ratefixed',
        sortable: true
    }];





    //set the options for the columns
    var options = {
        enableCellNavigation: true,
        editable: true,
        forceFitColumns: true,
        enableColumnReorder: false,
        multiColumnSort: true,
        rowHeight: 32,
        autoEdit: false
    };



    //var groupItemMetadataProvider = new Slick.Data.GroupItemMetadataProvider();

    dataView = new Slick.Data.DataView({
        //groupItemMetadataProvider: groupItemMetadataProvider,
        inlineFilters: true
    });


    grid = new Slick.Grid("#datagrid-pricing-broadcast", dataView, columns, options);

    //register plugins
    grid.setSelectionModel(new Slick.RowSelectionModel());


    // wire up model events to drive the grid
    dataView.onRowCountChanged.subscribe(function(e, args) {
        grid.updateRowCount();
        grid.render();
    });

    dataView.onRowsChanged.subscribe(function(e, args) {
        grid.invalidateRows(args.rows);
        grid.render();
    });



    grid.onSort.subscribe(function(e, args) {
        gridSorter(args.sortCols, dataView);
    });



    function gridSorter(sortCols, dataview) {
        dataview.sort(function(row1, row2) {
            for (var i = 0, l = sortCols.length; i < l; i++) {
                var field = sortCols[i].sortCol.field;
                var sign = sortCols[i].sortAsc ? 1 : -1;
                var x = row1[field],
                    y = row2[field];
                var result = (x < y ? -1 : (x > y ? 1 : 0)) * sign;
                if (result != 0) {
                    return result;
                }
            }
            return 0;
        }, true);
    }



    this.populateDatagrid = function(xdata) {
        data = xdata;
        dataView.beginUpdate();
        dataView.setItems(data);
        dataView.sort(comparer, true);
        

        //gridSorter(sortcol, dataView);
        dataView.endUpdate();
        //grid.invalidateRows();
        grid.invalidate();
        grid.render();
    }


    //custom grid scaler render
    this.renderGrid = function() {
        grid.resizeCanvas();
    };


    //prepare save
    this.prepareSave = function() {
        return data;
    }


    //ids
    this.selectedIds = function() {
        var selectedData = [];
        var selectedIndexes = grid.getSelectedRows();

        jQuery.each(selectedIndexes, function(index, value) {
            selectedData.push(grid.getData().getItem(value).id);
        });

        return selectedData;
    }

    //rows
    this.selectedRows = function() {
        var selectedData = [];
        var selectedIndexes = grid.getSelectedRows();

        jQuery.each(selectedIndexes, function(index, value) {
            selectedData.push(grid.getData().getItem(value));
        });

        return selectedData;
    }


    //empty grid
    this.emptyGrid = function() {
        data = [];
        grid.resetActiveCell();
        grid.setData(data);
        grid.render();
    };



    //unselect rows
    this.unSelectAll = function() {
        grid.getSelectionModel().setSelectedRanges([]);
        grid.invalidate();
        grid.render();
        grid.resetActiveCell();
    }


    this.formatTime = function(d) {
        var re;
        var min = Date.parse("01/01/1980 " + d).toString("mm");

        if (min == "00") {
            re = Date.parse("01/01/1980 " + d).toString("ht");
        } else {
            re = Date.parse("01/01/1980 " + d).toString("h:mmt");
        }



        if (re == "0A") {
            return "12M";
        }

        if (re == "11:59P") {
            return "12A";
        }
        return re;
    }


    this.processDays = function(d) {
        var ndays = [];
        var re = '';

        var days = d.split(',');
        var cnt = days.length;

        if (cnt == 7) {
            return 'M-Su';
        }

        $.each(days, function(i, val) {
            if (val == 1) {
                ndays.push(7);
            } else {
                ndays.push(val - 1);
            }
        });

        //if one day pass it back bro
        if (days.length == 1) {
            return daysAbbrSmallDayFix(ndays[0]);
        }

        var diff = ndays[ndays.length - 1] - ndays[0];

        if (ndays.length - diff == 1) {
            re = daysAbbrSmallDayFix(ndays[0]) + "-" + daysAbbrSmallDayFix(ndays[ndays.length - 1]);
        } else {
            var daylist = [];
            $.each(ndays, function(i, val) {
                daylist.push(daysAbbrSmallDayFix(val));
            });
            return daylist.join(",");
        }
        return re;
    }



    //before you destroy the cell edit reset the edit mode to false
    grid.onCellChange.subscribe(function(e, args) {
        var x = args.cell;
        var i = args.row;
        var col = columns[x].field;
        var items = self.colItem(col);


        //if this is a daypart lets go ahead and abd set the rate. We have other functions to set percents 
        if (items.daypartname == 'daypart') {
            var rate = parseFloat(args.item[items.daypart]);
            var frate = parseFloat(data[i][items.daypartfixed]);
            var fixed2pct = (frate / rate * 100) - 100;

            //if not greather than 0 then return 0
            if (isNaN(fixed2pct) || isFinite(fixed2pct) == false) {
                fixed2pct = 0;
            }

            data[i][items.daypartfixedpct] = parseInt(fixed2pct);
        }


        //if this is a daypart lets go ahead and abd set the rate. We have other functions to set percents 
        if (items.daypartname == 'fixed') {
            var rate = args.item[items.daypartfixed];
            var frate = data[i][items.daypart];
            var fixed2pct = (rate / frate * 100) - 100;
            //fixed2pct = parseInt(fixed2pct);
            data[i][items.daypartfixedpct] = parseInt(fixed2pct);
            data[i][items.daypartfixed] = rate;
        }


        //update based on pct
        if (items.daypartname == 'pct') {
            var rate = args.item[items.daypartfixedpct];
            var pct = rate;
            var rateval = data[i][items.daypart];
            var boost = (rateval * pct) / 100;
            var pct2fixed = parseInt(rateval) + parseInt(boost);
            data[i][items.daypartfixed] = parseInt(pct2fixed);
            data[i][items.daypartfixedpct] = parseInt(rate);
        }


        dataView.beginUpdate();
        dataView.setItems(data);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
    });



    grid.onSelectedRowsChanged.subscribe(function(e, args) {
        var cnt = self.selectedRows();

        if (cnt.length > 1 && editmode == true) {
            loadDialogWindow('single-row', 'Select Row', 380, 150);
            return;
        } else {
            self.parseBroadcastLine();
        }
    });




    this.parseBroadcastLine = function(row) {
        if (editmode) {
            var selectedIndexes = grid.getSelectedRows();

            if (selectedIndexes.length == 0 || selectedIndexes.length > 1) {
                return;
            }

            var row = data[selectedIndexes];
            var sdate = Date.parse("01/01/1980 " + row.starts).toString("hh:mm tt");
            var edate = Date.parse("01/01/1980 " + row.stops).toString("hh:mm tt");

            if (row.starttime == '00:00:00') {
                sdate = '12:00 AM';
            }

            $('#ratecard-broadcast-start-time').val(sdate);
            $('#ratecard-broadcast-end-time').val(edate);
            $('#pricing-title-alt-selector-broadcast').val(row.fname);


            //handel days
            var d = row.weekdays.split(',');

            if (d.length == 7) {
                $('#ratecard-broadcast-days').val('1,2,3,4,5,6,7');
            } else if (row.weekdays == '1,7') {
                $('#ratecard-broadcast-days').val('1,7');
            } else if (row.weekdays == '2,3,4,5,6') {
                $('#ratecard-broadcast-days').val('2,3,4,5,6');
            } else {
                $('#ratecard-broadcast-days').val(d);
            }

            //set pricing for the boxes
            $('#pricing-daypart-broadcast').val(row.rate);
            $('#pricing-fixed-broadcast').val(row.ratefixed);

            searchBroadcastTitles();
        }
    }




    grid.onDblClick.subscribe(function(e, args) {

        return;

        var selectedIndexes = grid.getSelectedRows();

        if (selectedIndexes.length == 0 || selectedIndexes.length > 1) {
            panelEditBroadcastLine(1);
            return;
        }

        panelEditBroadcastLine(0);

        var row = data[selectedIndexes];
        var sdate = Date.parse("01/01/1980 " + row.starts).toString("hh:mm tt");
        var edate = Date.parse("01/01/1980 " + row.stops).toString("hh:mm tt");

        if (row.starttime == '00:00:00') {
            sdate = '12:00 AM';
        }

        $('#ratecard-broadcast-start-time').val(sdate);
        $('#ratecard-broadcast-end-time').val(edate);
        $('#pricing-title-alt-selector-broadcast').val(row.fname);


        //handel days
        var d = row.weekdays.split(',');

        if (d.length == 7) {
            $('#ratecard-broadcast-days').val('1,2,3,4,5,6,7');
        } else if (row.weekdays == '1,7') {
            $('#ratecard-broadcast-days').val('1,7');
        } else if (row.weekdays == '2,3,4,5,6') {
            $('#ratecard-broadcast-days').val('2,3,4,5,6');
        } else {
            $('#ratecard-broadcast-days').val(d);
        }

        //set pricing for the boxes
        $('#pricing-daypart-broadcast').val(row.rate);
        $('#pricing-fixed-broadcast').val(row.ratefixed);


        searchBroadcastTitles();
    });





    //update the selected line
    this.updateBroadcastLine = function(row) {
        var selectedIndexes = grid.getSelectedRows();

        //check for duplicate shoq and pass back the row
        for (var i = 0; i <= data.length - 1; i++) {
            if (selectedIndexes[0] != i && row.id == data[i].id) {
                return i;
            }
        }
        data[selectedIndexes[0]] = row;
        dataView.beginUpdate();
        dataView.setItems(data);
        dataView.sort(comparer, true);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
        return 0;
    }


    this.setEditmode = function(mode) {
        editmode = mode;
    }

    this.colItem = function(col) {
        //make the daypart into an array 
        var ratearr = col.split("|");

        //set the daypart id
        var daypartid = ratearr[1];

        //set name
        var daypartname = ratearr[0];

        //get the normal rate column for this line
        var daypart = 'daypart|' + daypartid;
        var daypartfixed = 'fixed|' + daypartid;
        var daypartfixedpct = 'pct|' + daypartid;

        var re = new Object();
        re.daypartid = daypartid;
        re.daypartname = daypartname;
        re.daypart = daypart;
        re.daypartfixed = daypartfixed;
        re.daypartfixedpct = daypartfixedpct;

        return re;
    }




    //SET THE MAIN RATE
    this.setRate = function(rate) {

        var coldata = grid.getSelectionModel().getSelectedRanges();
        var fromRow = coldata[0].fromRow;
        var toRow = coldata[0].toRow;

        var fromCell = coldata[0].fromCell;
        var toCell = coldata[0].toCell;

        for (var i = fromRow; i <= toRow; i++) {
            for (var x = fromCell; x <= toCell; x++) {

                //what is the name of the column
                var col = columns[x].field;

                //call the items function to get a list of everything we are going to need
                var items = this.colItem(col);


                //if this is a daypart lets go ahead and abd set the rate. We have other functions to set percents 
                if (items.daypartname == 'daypart') {
                    data[i][col] = rate;
                    var frate = data[i][items.daypartfixed];
                    var fixed2pct = (frate / rate * 100) - 100;

                    //if not greather than 0 then return 0
                    if (isNaN(fixed2pct) || isFinite(fixed2pct) == false) {
                        fixed2pct = 0;
                    }

                    data[i][items.daypartfixedpct] = parseInt(fixed2pct);
                }
            }
        }
        dataView.beginUpdate();
        dataView.setItems(data);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
    }
    //END THE SET RATE FUNCTION




    //RATE FIXED
    this.setRateFixed = function(rate, type) {

        var coldata = grid.getSelectionModel().getSelectedRanges();

        var fromRow = coldata[0].fromRow;
        var toRow = coldata[0].toRow;

        var fromCell = coldata[0].fromCell;
        var toCell = coldata[0].toCell;

        for (var i = fromRow; i <= toRow; i++) {
            for (var x = fromCell; x <= toCell; x++) {

                //what is the name of the column
                var col = columns[x].field;

                //call the items function to get a list of everything we are going to need
                var items = this.colItem(col);

                //if this is a daypart lets go ahead and abd set the rate. We have other functions to set percents 
                if (items.daypartname == 'fixed' && type == 'fixed') {
                    var frate = data[i][items.daypart];
                    var fixed2pct = (rate / frate * 100) - 100;
                    data[i][items.daypartfixedpct] = parseInt(fixed2pct);
                    data[i][items.daypartfixed] = rate;
                }

                //update based on pct
                if (items.daypartname == 'pct' && type == 'pct') {
                    var pct = rate;
                    var rateval = data[i][items.daypart];
                    var boost = (rateval * pct) / 100;
                    var pct2fixed = parseInt(rateval) + parseInt(boost);
                    data[i][items.daypartfixed] = parseInt(pct2fixed);
                    data[i][items.daypartfixedpct] = parseInt(rate);
                }


            }
        }

        dataView.beginUpdate();
        dataView.setItems(data);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
    }

    //END RATE FIXED





    //custom grid scaler render
    this.addRow = function(row) {

        var scrollto = row.id;
        var f = false;

        for (var i = 0; i < data.length; i++) {
            if (data[i].id == row.id) {
                f = true;
            }
        }

        if (f == false) {
            data.push(row);
        }

        dataView.beginUpdate();
        dataView.setItems(data);
        dataView.sort(comparer, true);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();

        grid.scrollRowIntoView(data.length);
    };





    function comparer(a, b) {
        var x = a[sortcol],
            y = b[sortcol];
        return (x == y ? 0 : (x > y ? 1 : -1));
    }




    //prepare the download array
    this.setDownloadInfo = function() {
        var dldata = {};
        dldata.dayparts = daypartslist;
        dldata.ratecard = data;

        return dldata;
    }



    this.removeRows = function() {

        var id = grid.getSelectedRows();
        id.sort(function(a, b) {
            return b - a
        });

        for (var i = 0; i < id.length; i++) {

            var row = grid.getData().getItem(id[i]);

            //if the row is a group then lets delete them all
            if (row.__group == true) {
                $.each(row.rows, function(i, rid) {

                    $.each(data, function(i, value) {
                        if (value.id == rid.id) {
                            data.splice(i, 1);
                            return false;
                        }
                    })

                });
            } else {
                $.each(data, function(i, value) {
                    if (value.id == row.id) {
                        data.splice(i, 1);
                        return false;
                    }
                })
            }
        }


        dataView.beginUpdate();
        dataView.setItems(data);
        dataView.sort(comparer, true);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
    }

    //end main function
}
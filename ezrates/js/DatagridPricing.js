//function build datagrid
function DatagridPricing() {
    var self = this;
    var grid;
    var data = [];
    var datatemp;
    var dataView;
    var selectedRows = [];
    var daypartslist;;

    var togglerate = true;
    var togglefixedrate = false;
    var togglefixedratepercent = false;
    var undos = [];
    var needsaving = false;


    //set the columns
    var columns = [{
        id: "logo",
        name: "",
        field: "logo",
        width: 45,
        minWidth: 45,
        maxWidth: 45,
        selectable: false,
        formatter: Slick.Formatters.NetworkLogoSmall
    }, {
        id: "callsign",
        name: "",
        field: "callsign",
        width: 100,
        minWidth: 100,
        maxWidth: 100,
        selectable: false,
    }];



    //set the options for the columns
    var options = {
        enableCellNavigation: true,
        editable: true,
        forceFitColumns: true,
        enableColumnReorder: false,
        multiColumnSort: true,
        rowHeight: 32,
        autoEdit: false,
        frozenColumn: 1
    };






    var undoRedoBuffer = {
        commandQueue: [],
        commandCtr: 0,
        queueAndExecuteCommand: function(editCommand) {
            needsaving = true;
            $('#sidebar-tab-3-error-save').css('display', 'inline');
            //clippedRange


            jQuery.each(editCommand.clippedRange, function(index, value) {

                jQuery.each(value, function(index2, value2) {
                    var val = value2.replace(/[^\d.-]/g, '');
                    if (val == "") {
                        val = 0;
                    }
                    editCommand.clippedRange[index][index2] = val;
                });

            });


            //console.log(editCommand);


            this.commandQueue[this.commandCtr] = editCommand;
            this.commandCtr++;
            editCommand.execute();

        },

        undo: function() {
            if (this.commandCtr == 0)
                return;

            this.commandCtr--;
            var command = this.commandQueue[this.commandCtr];

            if (command && Slick.GlobalEditorLock.cancelCurrentEdit()) {
                command.undo();
            }
        },
        redo: function() {
            if (this.commandCtr >= this.commandQueue.length)
                return;
            var command = this.commandQueue[this.commandCtr];
            this.commandCtr++;
            if (command && Slick.GlobalEditorLock.cancelCurrentEdit()) {
                command.execute();
            }
        }
    }





    // undo shortcut
    $(document).keydown(function(e) {
        if (e.which == 90 && (e.ctrlKey || e.metaKey)) { // CTRL + (shift) + Z
            if (e.shiftKey) {
                needsaving = true;
                $('#sidebar-tab-3-error-save').css('display', 'inline');
                undoRedoBuffer.redo();
            } else {
                needsaving = true;
                $('#sidebar-tab-3-error-save').css('display', 'inline');
                undoRedoBuffer.undo();
            }
        }
    });



    var pluginOptions = {
        clipboardCommandHandler: function(editCommand) {
            undoRedoBuffer.queueAndExecuteCommand.call(undoRedoBuffer, editCommand);
        },
        includeHeaderWhenCopying: false
    };

    //var groupItemMetadataProvider = new Slick.Data.GroupItemMetadataProvider();

    dataView = new Slick.Data.DataView({
        //groupItemMetadataProvider: groupItemMetadataProvider,
        inlineFilters: true
    });


    grid = new Slick.Grid("#datagrid-pricing-cable", dataView, columns, options);

    //register plugins
    grid.setSelectionModel(new Slick.CellSelectionModel());


    // wire up model events to drive the grid
    dataView.onRowCountChanged.subscribe(function(e, args) {
        grid.updateRowCount();
        grid.render();
    });

    dataView.onRowsChanged.subscribe(function(e, args) {
        grid.invalidateRows(args.rows);
        grid.render();
    });


    // set keyboard focus on the grid
    grid.getCanvasNode().focus();

    grid.registerPlugin(new Slick.CellExternalCopyManager(pluginOptions));
    grid.onCellChange.subscribe(function(e, args) {
        needsaving = true;
        $('#sidebar-tab-3-error-save').css('display', 'inline');
    });


    this.populateDatagrid = function(xdata) {
        data = xdata;
        dataView.beginUpdate();
        dataView.setItems(data);
        dataView.endUpdate();
        //grid.invalidateRows();
        grid.invalidate();
        grid.render();
    }


    //custom grid scaler render
    this.renderGrid = function() {
        grid.resizeCanvas();
    };

    this.checkneedsaving = function() {
        return needsaving;
    };

    this.updateneedsaving = function(type) {
        needsaving = type;
        return needsaving;
    };


    //prepare save
    this.prepareSave = function() {
        needsaving = false;
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
            return "12A";
        }

        if (re == "11:59P") {
            return "12M";
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

            var x = daylist.join(",");
            if(x == "Su,Sa"){
                return "Sa,Su";
            }
            return daylist.join(",");
        }
        return re;
    }



    //before you destroy the cell edit reset the edit mode to false
    grid.onCellChange.subscribe(function(e, args) {
        needsaving = true;
        $('#sidebar-tab-3-error-save').css('display', 'inline');

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
            var pct2fixed = parseInt(rateval) +  Math.round(boost);
            data[i][items.daypartfixed] = parseInt(pct2fixed);
            data[i][items.daypartfixedpct] = parseInt(rate);
        }


        dataView.beginUpdate();
        dataView.setItems(data);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
    });


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
        needsaving = true;
        $('#sidebar-tab-3-error-save').css('display', 'inline');
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
        needsaving = true;
        $('#sidebar-tab-3-error-save').css('display', 'inline');
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
                    var pct2fixed = parseInt(rateval) + Math.round(boost);

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



    //pass back the columns
    this.getCols = function() {
        var re = togglerate + "," + togglefixedrate;
        return re;
    }




    //prepare the download array
    this.setDownloadInfo = function() {
        var dldata = {};
        dldata.dayparts = daypartslist;
        dldata.ratecard = data;

        return dldata;
    }






    this.toggleGrid = function(type) {
        columns = [];

        if (type == "rate") {
            if (togglerate) {
                togglerate = false;
                $('#button-toggle-dayparts').css('background-color', '#174A74');
                $('#form-pricing-rate-wrapper').css('display', 'none');
            } else {
                togglerate = true;
                $('#button-toggle-dayparts').css('background-color', '#0F8012');
                $('#form-pricing-rate-wrapper').css('display', 'inline');
            }
        }

        if (type == "fixed") {
            if (togglefixedrate) {
                togglefixedrate = false;
                $('#button-toggle-fixed').css('background-color', '#174A74');
                $('#form-pricing-fixed-wrapper').css('display', 'none');
            } else {
                togglefixedrate = true;
                $('#button-toggle-fixed').css('background-color', '#0F8012');
                $('#form-pricing-fixed-wrapper').css('display', 'inline');
            }
        }


        if (type == "fixedpct") {
            if (togglefixedratepercent) {
                togglefixedratepercent = false;
                $('#button-toggle-fixedpct').css('background-color', '#174A74');
                $('#form-pricing-fixedpct-wrapper').css('display', 'none');

                $('#sidebar-tab-2-percent-message').css('display', 'none');
            } else {
                togglefixedratepercent = true;
                $('#button-toggle-fixedpct').css('background-color', '#0F8012');
                $('#form-pricing-fixedpct-wrapper').css('display', 'inline');

                $('#sidebar-tab-2-percent-message').css('display', 'inline');
            }
        }


        if (!togglerate && !togglefixedrate && !togglefixedratepercent) {
            $('#sidebar-tab-2-sub-2').css('display', 'inline');
        } else {
            $('#sidebar-tab-2-sub-2').css('display', 'none');
        }


        self.buildGridCols(daypartslist);
    }






    this.buildColumns = function() {
        columns = [{
            id: "logo",
            name: "",
            field: "logo",
            width: 45,
            minWidth: 45,
            maxWidth: 45,
            selectable: false,
            formatter: Slick.Formatters.NetworkLogoSmall,
            cssClass: 'centertext',
            headerCssClass: 'pricingheader'
        }, {
            id: "callsign",
            name: "",
            field: "callsign",
            width: 100,
            minWidth: 100,
            maxWidth: 100,
            selectable: false,
            cssClass: 'centertext',
            headerCssClass: 'pricingheader'
        }];
    }

    this.buildGridCols = function(dayparts) {
        columns = [];
        this.buildColumns();

        daypartslist = dayparts;

        for (var i = 0; i < dayparts.length; i++) {
            var st = this.formatTime(dayparts[i].starttime);
            var ed = this.formatTime(dayparts[i].endtime);
            var days = this.processDays(dayparts[i].days);
            var title = days + '<br>' + st + '-' + ed;
            var titleFixed = 'Fixed<br>' + days + '<br>' + st + '-' + ed;
            var titlePct =  'Percent<br>' + days + '<br>' + st + '-' + ed;

            var rateDatpart = "daypart|" + dayparts[i].key;
            var rateFixed = "fixed|" + dayparts[i].key;
            var ratePct = "pct|" + dayparts[i].key;

            if (togglerate) {
                columns.push({
                    id: rateDatpart,
                    name: title,
                    field: rateDatpart,
                    width: 70,
                    minWidth: 70,
                    maxWidth: 70,
                    selectable: true,
                    cssClass: 'centertext rate',
                    headerCssClass: 'pricingheader',
                    formatter: Slick.Formatters.Amt,
                    editor: Slick.Editors.Text
                })
            }


            if (togglefixedrate) {
                columns.push({
                    id: rateFixed,
                    name: titleFixed,
                    field: rateFixed,
                    width: 70,
                    minWidth: 70,
                    maxWidth: 70,
                    selectable: true,
                    cssClass: 'centertext ratefixed',
                    headerCssClass: 'pricingheader',
                    editor: Slick.Editors.Text,
                    formatter: Slick.Formatters.Amt
                })
            }

            if (togglefixedratepercent) {
                columns.push({
                    id: ratePct,
                    name: titlePct,
                    field: ratePct,
                    width: 70,
                    minWidth: 70,
                    maxWidth: 70,
                    selectable: true,
                    cssClass: 'centertext ratepct',
                    headerCssClass: 'pricingheader',
                    editor: Slick.Editors.Text,
                    formatter: Slick.Formatters.Amtpct
                })
            }
        }

        grid.setColumns(columns);
    }





    //end main function
}
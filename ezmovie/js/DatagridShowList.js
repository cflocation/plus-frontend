//function build datagrid
function DatagridShowList() {

    var grid;
    var data = [];
    var dataView;
    var selectedRows = [];
    var dayparts = [];
    var groupby = "name";
    var sortcol = "title";
    var sortdir = 1;
    var searchString = '';


    //set the columns
    var columns = [{
        id: "rating",
        name: "Rating",
        field: "rating",
        sortable: true,
        width: 75,
        minWidth: 75,
        maxWidth: 75
    },{
        id: "title",
        name: "Title",
        field: "title",
        sortable: true,

    },{
        id: "facebook",
        name: "",
        field: "facebook",
        minWidth: 40,
        maxWidth: 40,
        sortable: true,
        formatter: Slick.Formatters.UrlFB
    },{
        id: "twitter",
        name: "",
        field: "twitter",
        minWidth: 40,
        maxWidth: 40,
        sortable: true,
        formatter: Slick.Formatters.UrlTwitter
    },{
        id: "networkurl",
        name: "Site",
        field: "networkurl",
        minWidth: 50,
        maxWidth: 50,
        sortable: true,
        formatter: Slick.Formatters.Url
    },{
        id: "imdb",
        name: "IMDB",
        field: "imdb",
        minWidth: 60,
        maxWidth: 60,
        sortable: true,
        formatter: Slick.Formatters.Url
    },{
        id: "theMovieDB",
        name: "TMDB",
        field: "theMovieDB",
        minWidth: 60,
        maxWidth: 60,
        sortable: true,
        formatter: Slick.Formatters.Url
    },{
        id: "instagram",
        name: "Instagram",
        field: "instagram",
        minWidth: 60,
        maxWidth: 60,
        sortable: true,
        formatter: Slick.Formatters.Url
    },{
        id: "pintrest",
        name: "Pintrest",
        field: "pintrest",
        minWidth: 60,
        maxWidth: 60,
        sortable: true,
        formatter: Slick.Formatters.Url
    },{
        id: "rottentomatoes",
        name: "RT",
        field: "rottentomatoes",
        minWidth: 50,
        maxWidth: 50,
        sortable: true,
        formatter: Slick.Formatters.Url
    },{
        id: "youtube",
        name: "YouTube",
        field: "youtube",
        minWidth: 50,
        maxWidth: 50,
        sortable: true,
        formatter: Slick.Formatters.Url
    },{
        id: "wiki",
        name: "Wiki",
        field: "wiki",
        minWidth: 50,
        maxWidth: 50,
        sortable: true,
        formatter: Slick.Formatters.Url
    },{
        id: "year",
        name: "Year",
        field: "year",
        minWidth: 60,
        maxWidth: 60,
        sortable: true
    },{
        id: "createdat",
        name: "Created",
        field: "createdat",
        sortable: true,
        minWidth: 85,
        maxWidth: 85,
        formatter: Slick.Formatters.Datetime
    },{
        id: "updatedat",
        name: "Updated",
        field: "updatedat",
        sortable: true,
        minWidth: 85,
        maxWidth: 85,
        formatter: Slick.Formatters.Datetime
    },{
        id: "logs",
        name: "",
        field: "id",
        sortable: false,
        minWidth: 30,
        maxWidth: 30,
        formatter: Slick.Formatters.History
    }];

    //set the options for the columns
    var options = {
        enableCellNavigation: true,
        editable: false,
        forceFitColumns: true,
        enableColumnReorder: false,
        multiColumnSort: false,
        rowHeight: 30
    };


    var groupItemMetadataProvider = new Slick.Data.GroupItemMetadataProvider();

    dataView = new Slick.Data.DataView({
        groupItemMetadataProvider: groupItemMetadataProvider,
        inlineFilters: true
    });


    //create the datagrod
    grid = new Slick.Grid("#datagrid-show-list", dataView, columns, options);


    //register plugins
    grid.registerPlugin(groupItemMetadataProvider);
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



    //sorting function
    grid.onSort.subscribe(function (e, args) {
        sortdir = args.sortAsc ? 1 : -1;
        sortcol = args.sortCol.field;

        dataView.beginUpdate();
        dataView.sort(comparer, args.sortAsc);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
    });



    grid.onSelectedRowsChanged.subscribe(function (e, args) {
        var selectedrow = grid.getSelectedRows();

        if(selectedrow.length > 1 || selectedrow.length == 0){
            $('#scover').attr("src","");
            return;
        }

        var d   = dataView.getItem(selectedrow[0]);

        getMovieImage(d.tmsId);

        //var d = data[selectedrow[0]];
        $('#link-futon').val(d.futon).removeClass('input-changed');
        $('#link-facebook').val(d.facebook).removeClass('input-changed');
        $('#link-twitter').val(d.twitter).removeClass('input-changed');
        $('#link-wiki').val(d.wiki).removeClass('input-changed');
        $('#link-networkurl').val(d.networkurl).removeClass('input-changed');
        $('#link-imdb').val(d.imdb).removeClass('input-changed');
        $('#link-instagram').val(d.instagram).removeClass('input-changed');
        $('#link-rottentomatoes').val(d.rottentomatoes).removeClass('input-changed');
        $('#link-pintrest').val(d.pintrest).removeClass('input-changed');
        $('#link-youtube').val(d.pintrest).removeClass('input-changed');
        $('#link-theMovieDB').val(d.theMovieDB).removeClass('input-changed');
        $('#link-youtube').val(d.youtube).removeClass('input-changed');

    });

    //filter by the title typed int he above box
    function myFilter(item, args) {
        if(args.searchString !== "" && item.title.toLowerCase().indexOf(args.searchString.toLowerCase()) === -1) {
            return false;
        }

        return true;
    }


    //update from keywords
    this.updatFromKeyword = function(e, val) {
        grid.resetActiveCell();
        Slick.GlobalEditorLock.cancelCurrentEdit();
        // clear on Esc
        if(e.which === 27) {
            this.value = "";
        }

        searchString = val;
        updateFilter();
    }


    //once the term is set 
    function updateFilter() {

        dataView.setFilterArgs({
            searchString: searchString
        });
        dataView.refresh();
        grid.invalidate();
        grid.render();
    }


    //sorting
    function comparer(a,b) {
        var x = a[sortcol].toLowerCase().trim(), y = b[sortcol].toLowerCase().trim();
        var z = (x == y ? 0 : (x > y ? 1 : -1));

        if(z == 0 && sortcol != 'title'){
            x = a['title'].toLowerCase().trim();
            y = b['title'].toLowerCase().trim();
            z = (x == y ? 0 : (x > y ? 1 : -1));
        }

        return z;
    }


    //group the columns 
    this.groupByColumn = function(col) {
        groupby = col;

        if (col == 'off') {
            dataView.groupBy(null);
            return;
        }

        dataView.groupBy(col,
            function(g) {
                return "<span style='color:#32639a;font-weight: bold;'>" + g.value + "</span> - <span style='color:#32639a;font-weight: bold;'>(" + g.count + " items)</span>";
            },
            function(a, b) {
                return a.value - b.value;
            }
        );
    }


    //collape all grpups
    this.collapseAllGroups = function() {
        dataView.beginUpdate();
        for (var i = 0; i < dataView.getGroups().length; i++) {
            dataView.collapseGroup(dataView.getGroups()[i].value);
        }
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
    }


    this.populateDatagrid = function(xdata, group) {

        data = xdata;
        dataView.beginUpdate();
        dataView.setItems(data);
        dataView.setFilterArgs({
            searchString: searchString
        });

        dataView.setFilter(myFilter);
        dataView.sort(comparer, sortdir==1);
        dataView.endUpdate();
        
        grid.invalidateAllRows();
        grid.render();

        grid.setSelectedRows([]);
    }




    //custom grid scaler render
    this.renderGrid = function() {
        grid.resizeCanvas();
    };


    //ids
    this.selectedIds = function() {
        var selectedData = [];
        var selectedIndexes = grid.getSelectedRows();

        jQuery.each(selectedIndexes, function(index, value) {
            selectedData.push(grid.getData().getItem(value).id);
        });

        return selectedData;
    }




    this.updateRow = function(showcardid){
        var selectedIndexes = grid.getSelectedRows();
        data[selectedIndexes].showcardid = showcardid;
        
        dataView.beginUpdate();
        dataView.setItems(data);
        dataView.endUpdate();
        grid.resetActiveCell();
        grid.invalidateAllRows();
        grid.render();


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
        dataView.beginUpdate();
        dataView.setItems(data);
        dataView.endUpdate();
        grid.resetActiveCell();
        grid.invalidateAllRows();
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


    //end main function
}
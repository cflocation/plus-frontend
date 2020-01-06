//datagrids
var datagridShowList = new DatagridShowList();

//varibles
var networkid = 0;
var tzid = 0;
var currentfile;


$(document).ready(function() {
    menuSelect('tab-1', 'menu-1');
    $('.sidebar-inpt-movielinks').keyup(function(){
      checkForChanges(this);
    });
    $('.sidebar-inpt-movielinks').change(function(){
      checkForChanges(this);
    });

    //getShowList();
});



function loadShowImages(id){
    $.getJSON("services/images.php?showid="+id, function(data) {
      //$('#image-zoom img').attr("src",loc);
      console.log(data[0]);
    });
}



$("#year-selector").change(function() {
  getShowList(this.value);
});



$('#movielist-nowshowing').click(function() {
    if ($(this).hasClass("active")) {
        //Now showing mode already active, nothing to do, bye
        return;
    }

    $(this).addClass('active'); //make this one active
    $('#movielist-byyear').removeClass('active'); //remove active from the other mode
    $('#sidebar-div-year').hide(); //hide year selector, we dont need it in this mode
    getShowList('nowshowing'); //get listings to populate the grid
});

$('#movielist-byyear').click(function() {
    if ($(this).hasClass("active")) {
        //By year mode already active, nothing to do, bye
        return;
    }

    $(this).addClass('active'); //make this one active
    $('#movielist-nowshowing').removeClass('active'); //remove active from the other mode
    $('#sidebar-div-year').show(); //show year selector, we need it in this mode
    getShowList($("#year-selector").val()); //get listings to populate the grid
});





function getShowList(year) {
    if(year == ''){
      datagridShowList.populateDatagrid([]);
      return;
    }

    $('#tab1-movielisttypes li.active a i').addClass('fa fa-spin fa-spinner');
    $.getJSON("services/shows.php?eventtype=list&year="+year, function(data) {
    	datagridShowList.populateDatagrid(data);
      $('#tab1-movielisttypes li.active a i').removeClass('fa fa-spin fa-spinner');
    });
}



function openTitleDialog(){
	var rows = datagridShowList.selectedRows();

	if(rows.length > 1){
		return;
	}

	loadDialogWindow('find-showid', 'Find Show ID', 380, 180);
}



function updateShowLinks(){
	
  if($('.sidebar .input-changed').length == 0){
    //nothing was changed
    return false;
  }
    
  var networkurl = $('#link-networkurl').val();
	var futon      = $('#link-futon').val();
	var facebook   = $('#link-facebook').val();
	var twitter    = $('#link-twitter').val();
	var wiki       = $('#link-wiki').val();
	var imdb       = $('#link-imdb').val();
  var instagram  = $('#link-instagram').val();
  
  var pintrest       = $('#link-pintrest').val();
  var rottentomatoes = $('#link-rottentomatoes').val();
  var youtube        = $('#link-youtube').val();
  var theMovieDB     = $('#link-theMovieDB').val();
  var updates        = [];

  $('.sidebar .input-changed').each(function(){
    updates.push(this.name);
  });

	var rows   = datagridShowList.selectedRows();
  var row    = rows[0];
  var rootId = row['id'];

	$.post("services/shows.php", {
        eventtype: "updatelinks",
        rootId: rootId,
        futon: futon,
        facebook: facebook,
        twitter: twitter,
        wiki: wiki,
        youtube: youtube,
        imdb: imdb,
        theMovieDB: theMovieDB,
        instagram: instagram,
        pintrest: pintrest,
        rottentomatoes: rottentomatoes,
        networkurl: networkurl,
        updates: updates
    }).done(function(data){
	  //function to update the tvdb shgow details
      //updateTheTVdbDetails(tvdb);
      datagridShowList.unSelectAll()
      resetSideBarForm();

      if($('#tab1-movielisttypes li.active').attr('id') == "movielist-byyear"){
      	getShowList($("#year-selector").val());
      } else{
      	getShowList('nowshowing');
      }
      
  });
}



function searchTVDB(){
	var rows = datagridShowList.selectedRows();
  	var row = rows[0];
  	var title = encodeURIComponent(row['title']);
  	var url = "http://thetvdb.com/?string="+title+"&searchseriesid=&tab=listseries&function=Search";
  	window.open(url,'_blank');
}


function searchNetworkURL(){
	var rows = datagridShowList.selectedRows();
  	var row = rows[0];
  	var title = encodeURIComponent(row['title']);
  	var url = "https://www.google.com/search?q="+title;
  	window.open(url,'_blank');
}


function searchFacebook(){
	var rows = datagridShowList.selectedRows();
  	var row = rows[0];
  	var title = encodeURIComponent(row['title']) + " facebook";
  	var url = "https://www.google.com/search?q="+title;
  	window.open(url,'_blank');
}




//https://www.youtube.com/results?search_query=The+Hungover+Games

function searchYoutube(){
  var rows = datagridShowList.selectedRows();
    var row = rows[0];
    var title = encodeURIComponent(row['title']) + " trailer";
    var url = "https://www.youtube.com/results?search_query="+title;
    window.open(url,'_blank');
}




function searchWiki(){
	var rows = datagridShowList.selectedRows();
  	var row = rows[0];
  	var title = encodeURIComponent(row['title']) + " wiki";
  	var url = "https://www.google.com/search?q="+title;
  	window.open(url,'_blank');
}








function searchFuton(){
	var rows = datagridShowList.selectedRows();
  	var row = rows[0];
  	var title = encodeURIComponent(row['title']);
  	var url = "http://www.thefutoncritic.com/search.aspx?q="+title+"&type=titles";
  	window.open(url,'_blank');
}





function searchImdb(){
  var rows = datagridShowList.selectedRows();
    var row = rows[0];
    var title = encodeURIComponent(row['title']);
    var url = "http://www.imdb.com/find?ref_=nv_sr_fn&q="+title+"&s=all";
    window.open(url,'_blank');
}




function searchInstagram(){
  var rows = datagridShowList.selectedRows();
    var row = rows[0];
    var title = encodeURIComponent(row['title']) + " instagram";
    var url = "https://www.google.com/search?q="+title;
    window.open(url,'_blank');
}



function searchPintrest(){
  var rows = datagridShowList.selectedRows();
    var row = rows[0];
    var title = encodeURIComponent(row['title']) + " pinterest";
    var url = "https://www.google.com/search?q="+title;
    window.open(url,'_blank');
}




function searchRotten(){
  var rows = datagridShowList.selectedRows();
    var row = rows[0];
    var title = encodeURIComponent(row['title']);
    var url = "http://www.rottentomatoes.com/search/?search="+title;
    window.open(url,'_blank');
}

function searchRecommendations(){
  var rows = datagridShowList.selectedRows();
    var row = rows[0];
    var id = encodeURIComponent(row['id']);
    var url = "http://loveit.tv/admin/sort.php?fid="+id;
    window.open(url,'_blank');
}




function searchTheMovieDB(){
  var rows = datagridShowList.selectedRows();
    var row = rows[0];
    var title = encodeURIComponent(row['title']);

    var url = "http://www.themoviedb.org/search/movie?language=en&query="+title;
    window.open(url,'_blank');

}





function searchTwitter(){
	var rows = datagridShowList.selectedRows();
  	var row = rows[0];
  	var title = encodeURIComponent(row['title']) + " twitter";
  	var url = "https://www.google.com/search?q="+title;
  	window.open(url,'_blank');
}



// wire up the search textbox to apply the filter to the model
$("#show-list-filter").keyup(function(e) {
    datagridShowList.updatFromKeyword(e, this.value);
});


function updateTheTVdbDetails(tvdbid){
	$.post("services/importtvdbshowdetails.php", {
        tvdbid: tvdbid,
        
    });
}


function getMovieImage(tmsId){
  $('#scover').attr("src","load.gif");
  $.getJSON("services/images.php?showid="+tmsId, function(data) {
    $('#scover').attr("src",data['thumb']);
  });
}

function uploadCover(){
  var rows = datagridShowList.selectedRows();

  if(rows.length == 0){
    return;
  };

  var row = rows[0];
  var id = row['tmsId'];
  var genre = encodeURIComponent(row['genre'].trim());
  var title = encodeURIComponent(row['title'])
  var url = "upload.php?id=" + id + "&title=" + title + "&genre=" + genre;
  window.open(url, 'Uploader', 'width=800,height=700');
}

function uploadCoverBeta(){
  var rows = datagridShowList.selectedRows();

  if(rows.length == 0){
    return;
  };

  var row    = rows[0];
  var id     = row['id'];
  var genre  = row['genre'].trim().split(',');
  var genre1 = genre[0];
  var genre2 = genre.length > 1 ? genre[1] : '';
  var title  = encodeURIComponent(row['title'])
  //var url    = "http://50.193.201.245/generatecover/"+id+"/"+title+"/"+genre1+"/"+genre2+"/";
  var url    = "https://toolsapi.showseeker.com:5000/generatecover/"+id+"/"+title+"/"+genre1+"/"+genre2+"/";
  
  window.open(url, 'UploaderBeta', 'width=800,height=700')
}


function uploadCoverManual(){

  var url = "singleImageMaker.php";
  window.open(url, 'Uploader', 'width=800,height=700');
}

function checkForChanges(inpt){
  var rows = datagridShowList.selectedRows();

  if(rows.length == 0){
    return;
  };

  var row = rows[0];

  if(inpt.value == row[inpt.name]){
    $(inpt).removeClass('input-changed');
  } else {
    $(inpt).addClass('input-changed');
  }
}


function resetSideBarForm(){
  var rows = datagridShowList.selectedRows();

  if(rows.length == 0){
    $('#link-futon').val('').removeClass('input-changed');
    $('#link-facebook').val('').removeClass('input-changed');
    $('#link-twitter').val('').removeClass('input-changed');
    $('#link-wiki').val('').removeClass('input-changed');
    $('#link-networkurl').val('').removeClass('input-changed');
    $('#link-imdb').val('').removeClass('input-changed');
    $('#link-instagram').val('').removeClass('input-changed');
    $('#link-rottentomatoes').val('').removeClass('input-changed');
    $('#link-pintrest').val('').removeClass('input-changed');
    $('#link-youtube').val('').removeClass('input-changed');
    $('#link-theMovieDB').val('').removeClass('input-changed');
    $('#link-youtube').val('').removeClass('input-changed');
  } else {
    var d = rows[0]
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
  }
}

function showMovieEditHistory(){
  var rows = datagridShowList.selectedRows();
  if(rows.length == 0){
    return false;
  }

  loadDialogWindow('movie-history', 'Movie Edit History', 580, 380,rows[0]['id']);
}

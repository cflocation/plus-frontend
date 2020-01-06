<div class="small-12 columns">
      <div class="row collapse">
        <div class="small-11 columns">
          <input type="text" placeholder="Show Title" required id="search-title"/>
        </div>
        <div class="small-1 columns">
          <span class="postfix"><div onclick="searchTitles()"><i class="fa fa-search"></i></div></span>
        </div>
      </div>
</div>



<div class="row">
  <div class="small-12 columns">
    <select id="select-title"></select>
  </div>
</div>


<div class="row">
  <div class="small-12 columns">
    <center><button type="submit" class="button tiny green" onclick="addNewTitle();"><i class="fa fa-plus-circle fa-lg"></i> Add Title to Database</button></center>
  </div>
</div>


<script type="text/javascript">
	var ready = true;

  function searchTitles(){
    var title = $('#search-title').val();
    
    if(title.length > 2 && ready == true){
    	var url = 
"http://solr.prod.showseeker.com:8983/solr/gracenote/select?q=*:*&sort=sort+ASC&fl=title%2Cshowid&wt=json&indent=true&group=true&group.field=sort&rows=50";
      url += '&fq=title:"'+title+'"';

      $('#select-title').empty().append($("<option></option>").attr("value", 0).text('Searching'));
      //$('#select-title').append($("<option></option>").attr("value", 0).text('Updating'));
      ready = false;
      $.getJSON('services/search.php?url='+encodeURIComponent(url), function(data) {
        $('#select-title').empty();
        var data = data.grouped.sort.groups;
        if(data.length == 0){
          $('#select-title').empty().append($("<option></option>").attr("value", 0).text('No Titles Found'));
        }

        $.each(data, function(i, value) {
          var id = value.doclist.docs[0].showid;
          var label = value.doclist.docs[0].title;
          $('#select-title').append($("<option></option>").attr("value", id).text(label));
        });
        ready = true;
      });

       //doSearch(title);
    }else{
      //datagridHotTitles.emptyGrid();
    }
  }


</script>



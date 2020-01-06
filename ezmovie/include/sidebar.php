<div id="sidebar-tab-1" style="display:none;">
  <form data-abide onsubmit="return false;">
    <div class="row padder" id="sidebar-div-year">
      <div class="small-12">
        <div class="row">
          <div class="small-3 columns">
            <label for="year-filter" class="right inline">Year:</label>
          </div>
          <div class="row collapse">
          <div class="small-9 columns">
            <select id="year-selector">
              <option value="">Select Year</option>
              <?php 
                $years = range((date("Y")+1), 1960);
	              foreach($years as $x => $x_value) { 
                  echo "<option>" . $x_value ."</option>";
                }
              ?>
            </select>
          </div>
        </div>
        </div>
      </div>
    </div>
    
    <div class="row padder">
      <div class="small-12">
        <div class="row">
          <div class="small-3 columns">
            <label for="show-list-filter" class="right inline">Search:</label>
          </div>

          <div class="row collapse">
          <div class="small-9 columns">
              <input type="text"  placeholder="Filter Results" id="show-list-filter" value=""></input>
          </div>
        </div>
        </div>
      </div>
    </div>
    
    <div class="row padder">
      <div class="small-12">
        <div class="row">
          <div class="small-3 columns">
            <label for="link-networkurl" class="right inline">Website:</label>
          </div>
          <div class="row collapse">
            <div class="small-7 columns">
              <input type="text"  placeholder="Website Address" id="link-networkurl" value="" name="networkurl" class="sidebar-inpt-movielinks"/>
            </div>
            <div class="small-2 columns">
              <a href="javascript:searchNetworkURL();" class="button postfix radius"><i class="fa fa-search fa-lg"></i></a>
            </div>
          </div>      
        </div>
      </div>
    </div>
    
    <div class="row padder">
      <div class="small-12">
        <div class="row">
          <div class="small-3 columns">
            <label for="link-facebook" class="right inline">Facebook:</label>
          </div>

          <div class="row collapse">
          <div class="small-7 columns">
               <input type="text"  placeholder="Facebook URL" id="link-facebook" value="" name="facebook" class="sidebar-inpt-movielinks"/>
          </div>
          <div class="small-2 columns">
            <a href="javascript:searchFacebook();" class="button postfix radius"><i class="fa fa-search fa-lg"></i></a>
          </div>
        </div>
        
        </div>
      </div>
    </div>
    
    <div class="row padder">
      <div class="small-12">
        <div class="row">
          <div class="small-3 columns">
            <label for="link-twitter" class="right inline">Twitter:</label>
          </div>

          <div class="row collapse">
          <div class="small-7 columns">
               <input type="text"  placeholder="Twitter URL" id="link-twitter" value="" name="twitter" class="sidebar-inpt-movielinks"/>
          </div>
          <div class="small-2 columns">
              <a href="javascript:searchTwitter();" class="button postfix radius"><i class="fa fa-search fa-lg"></i></a>
          </div>
        </div>
        
        </div>
      </div>
    </div>
    
    <div class="row padder">
      <div class="small-12">
        <div class="row">
          <div class="small-3 columns">
            <label for="link-wiki" class="right inline">Wiki:</label>
          </div>

          <div class="row collapse">
          <div class="small-7 columns">
               <input type="text"  placeholder="Wiki Page" id="link-wiki" value="" name="wiki" class="sidebar-inpt-movielinks"/>
          </div>
          <div class="small-2 columns">
              <a href="javascript:searchWiki();" class="button postfix radius"><i class="fa fa-search fa-lg"></i></a>
          </div>
        </div>
        
        </div>
      </div>
    </div>

    <div class="row padder">
      <div class="small-12">
        <div class="row">
          <div class="small-3 columns">
            <label for="link-youtube" class="right inline">Youtube:</label>
          </div>

          <div class="row collapse">
          <div class="small-7 columns">
               <input type="text"  placeholder="Youtube Trailer" id="link-youtube" value="" name="youtube" class="sidebar-inpt-movielinks"/>
          </div>
          <div class="small-2 columns">
              <a href="javascript:searchYoutube();" class="button postfix radius"><i class="fa fa-search fa-lg"></i></a>
          </div>
        </div>
        
        </div>
      </div>
    </div>

    <div class="row padder">
      <div class="small-12">
        <div class="row">
          <div class="small-3 columns">
            <label for="link-imdb" class="right inline">IMDB:</label>
          </div>

          <div class="row collapse">
          <div class="small-7 columns">
               <input type="text"  placeholder="IMDB Page" id="link-imdb" value="" name="imdb" class="sidebar-inpt-movielinks"/>
          </div>
          <div class="small-2 columns">
              <a href="javascript:searchImdb();" class="button postfix radius"><i class="fa fa-search fa-lg"></i></a>
          </div>
        </div>
        
        </div>
      </div>
    </div>

    <div class="row padder">
      <div class="small-12">
        <div class="row">
          <div class="small-3 columns">
            <label for="link-theMovieDB" class="right inline">Movie&nbsp;DB:</label>
          </div>

          <div class="row collapse">
          <div class="small-7 columns">
               <input type="text"  placeholder="The Movie DB Page" id="link-theMovieDB" value="" name="theMovieDB" class="sidebar-inpt-movielinks"/>
          </div>
          <div class="small-2 columns">
              <a href="javascript:searchTheMovieDB();" class="button postfix radius"><i class="fa fa-search fa-lg"></i></a>
          </div>
        </div>
        
        </div>
      </div>
    </div>

    <div class="row padder">
      <div class="small-12">
        <div class="row">
          <div class="small-3 columns">
            <label for="link-instagram" class="right inline">Instagram:</label>
          </div>

          <div class="row collapse">
          <div class="small-7 columns">
               <input type="text"  placeholder="Instagram Page" id="link-instagram" value="" name="instagram" class="sidebar-inpt-movielinks"/>
          </div>
          <div class="small-2 columns">
              <a href="javascript:searchInstagram();" class="button postfix radius"><i class="fa fa-search fa-lg"></i></a>
          </div>
        </div>
        
        </div>
      </div>
    </div>

    <div class="row padder">
      <div class="small-12">
        <div class="row">
          <div class="small-3 columns">
            <label for="link-pintrest" class="right inline">Pinterest:</label>
          </div>

          <div class="row collapse">
          <div class="small-7 columns">
               <input type="text"  placeholder="Pinterest Page" id="link-pintrest" value="" name="pintrest" class="sidebar-inpt-movielinks"/>
          </div>
          <div class="small-2 columns">
              <a href="javascript:searchPintrest();" class="button postfix radius"><i class="fa fa-search fa-lg"></i></a>
          </div>
        </div>
        
        </div>
      </div>
    </div>

    <div class="row padder">
      <div class="small-12">
        <div class="row">
          <div class="small-3 columns">
            <label for="link-rottentomatoes" class="right inline">Rotten:</label>
          </div>

          <div class="row collapse">
          <div class="small-7 columns">
               <input type="text"  placeholder="Rotten Tomatoes" id="link-rottentomatoes" value="" name="rottentomatoes" class="sidebar-inpt-movielinks"/>
          </div>
          <div class="small-2 columns">
              <a href="javascript:searchRotten();" class="button postfix radius"><i class="fa fa-search fa-lg"></i></a>
          </div>
        </div>
        
        </div>
      </div>
    </div>

    <div class="row padder">
      <div class="small-12">
        <div class="row">
          <div class="small-3 columns">
            <label class="right inline"><br /><br /><br /><br /><br />Imagery:</label>
          </div>
          <div class="row collapse">
          <div class="small-9 columns">
           <button type="submit" class="button tiny green" onclick="updateShowLinks();"><i class="fa fa-save fa-lg"></i> Save Changes</button>
            <a href="javascript:resetSideBarForm();" class="button tiny alert"><i class="fa fa-refresh"></i> Reset</a>            
            <a href="javascript:uploadCover();" class="button tiny"><i class="fa fa-upload"  alt="Original Image Generator"></i> U</a>
            <a href="javascript:uploadCoverBeta();" class="button tiny disabled"><i class="fa fa-upload" alt="Python Image Generator"></i> B</a> 
            <a href="javascript:uploadCoverManual();" class="button tiny"><i class="fa fa-upload" alt="Manual Image Generator"></i> M</a> 
          </div>
          </div>
        </div>
      </div>
    </div>

     <center>
    <img id="scover" src="" style="height:200px"/>
  </center>

    <!-- <div class="row padder">
    <div class="small-12">
        <div class="row text-center">
      <img id="scover" src="" style="height:200px"/>
    </div>
    </div>
    </div> -->





  </form>
</div>

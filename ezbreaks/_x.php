<?php
	session_start();
	if(!isset($_SESSION['userid'])){
		header( 'Location: /login?redirectto='.rawurlencode($_SERVER['REQUEST_URI'])) ;
	}
	$uuid = uniqid();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/icon.gif" type="image/x-icon" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="cache-control" content="no-cache"> <!-- tells browser not to cache -->
    <meta http-equiv="expires" content="0"> <!-- says that the cache expires 'now' -->
    <meta http-equiv="pragma" content="no-cache"> <!-- says not to use cached stuff, if there is any -->
    <link rel="stylesheet" href="../inc/fontawesome4/css/font-awesome.min.css">
    <link rel="stylesheet" href="../inc/foundation/css/normalize.css">
    <link rel="stylesheet" href="../inc/foundation/css/foundation.css">
    <!-- <link rel="stylesheet" href="../inc/timepicker/jquery.timepicker.css"> -->
    <link rel="stylesheet" href="../css/drk-theme/jquery-ui-1.10.4.custom.min.css">
    <link rel="stylesheet" href="../css/jquery-ui-timepicker-addon.css">

    <link rel="stylesheet" href="../css/global.css">
    <title>ShowSeeker - EZ-Breaks</title>

<style type="text/css">
  .blues{
    background-color: #deedf8!important;
    border-bottom: 1px solid #aed0ea;
  }

  .purples dd a{
    background-color: #4c1a4c!important;
    border-bottom: 1px solid #ffffff;
    color: white!important;
  }
</style>

</head>

<body>



<nav id="main-nav" class="top-bar" data-topbar>
  <ul class="title-area">
    <li class="name" style="width: 290px;"><img src="../images/logosm_ezbreaks.png" style="padding-left:5px;"></li>
  </ul>

  <section class="top-bar-section" data-topbar data-options="is_hover: false">
    <ul class="left"></ul>
    <ul class="right">
    <li class="has-dropdown">
        <a href="#"><i class="fa fa-user fa-lg"></i> <?php print $_SESSION['name']; ?>&nbsp;&nbsp;<span class="label round success"><?php print $_SESSION['corporation']; ?></span></a>
        <ul class="dropdown right">
          <li><a href="/login">Logout</a></li>
        </ul>
      </li>
    </ul>
  </section>
</nav>
<br>


<div class="row">


  <div class="small-2 columns">

      <dl id="accordionLeft" class="accordion blues" data-accordion>
        <dd>
          <a class="blues" href="javascript:void(0)"><i class="fa fa-building" onclick="openSingle('panelCorporations','panelMarkets,panelOffices');"></i> <span onclick="togglePanel('panelCorporations');">Corporations</span></a>
          <div id="panelCorporations" class="content active">
              

            <div class="row">
                <div class="small-12 columns">
                  <input type="text" placeholder="Search Corporations" />
                </div>
            </div>

            <div class="row">
                <div class="small-12 columns">


                    <select multiple style="height:125px;">
                      <option value="husker">Husker</option>
                      <option value="starbuck">Starbuck</option>
                      <option value="hotdog">Hot Dog</option>
                      <option value="apollo">Apollo</option>
                    </select>
                </div>

                <div class="small-12 columns">
                <ul class="button-group">
                    <li><a href="#" class="tiny button green">Add</a></li>
                    <li><a href="#" class="tiny button">Edit</a></li>
                    <li><a href="#" class="tiny button red">Delete</a></li>
                </ul>
              </div>
              </div>

          </div>
        </dd>
        <dd>
          <a class="blues"  href="javascript:void(0)"><i class="fa fa-building-o" onclick="openSingle('panelMarkets','panelCorporations,panelOffices');"></i> <span onclick="togglePanel('panelMarkets');">Markets</span></a>
          <div id="panelMarkets" class="content active">
              

            <div class="row">
                <div class="small-12 columns">
                  <input type="text" placeholder="Search Markets" />
                </div>
            </div>


            <div class="row">
                <div class="small-12 columns">
                    <select multiple style="height:125px;">
                      <option value="husker">Husker</option>
                      <option value="starbuck">Starbuck</option>
                      <option value="hotdog">Hot Dog</option>
                      <option value="apollo">Apollo</option>
                    </select>
                </div>

                <div class="small-12 columns">
                <ul class="button-group">
                    <li><a href="#" class="tiny button green">Add</a></li>
                    <li><a href="#" class="tiny button">Edit</a></li>
                    <li><a href="#" class="tiny button red">Delete</a></li>
                </ul>
              </div>
              </div>

          </div>
        </dd>

        <dd>
          <a class="blues" href="javascript:void(0)"><i class="fa fa-fax" onclick="openSingle('panelOffices','panelCorporations,panelMarkets');"></i> <span onclick="togglePanel('panelOffices');">Offices</span></a>
          <div id="panelOffices" class="content active">
             

            <div class="row">
                <div class="small-12 columns">
                  <input type="text" placeholder="Search Offices" />
                </div>
            </div>


            <div class="row">
                <div class="small-12 columns">
                    <select multiple style="height:125px;">
                      <option value="husker">Husker</option>
                      <option value="starbuck">Starbuck</option>
                      <option value="hotdog">Hot Dog</option>
                      <option value="apollo">Apollo</option>
                    </select>
                </div>

                <div class="small-12 columns">
                <ul class="button-group">
                    <li><a href="#" class="tiny button green">Add</a></li>
                    <li><a href="#" class="tiny button">Edit</a></li>
                    <li><a href="#" class="tiny button red">Delete</a></li>
                </ul>
              </div>
              </div>
          </div>
        </dd>
      </dl>

  </div>
  


















  <div class="small-8 columns">

<dl class="accordion purples" data-accordion>
  <dd>
    <a href="#panelc1"><i class="fa fa-building"></i> Corporation Settings</a>
    <div id="panelc1" class="content">
        <br><br><br><br><br><br>
    </div>
  </dd>
  <dd>
    <a href="#panelc2"><i class="fa fa-building-o"></i> Market Settings</a>
    <div id="panelc2" class="content">
<br><br><br><br><br><br>
    </div>
  </dd>
  <dd>
    <a href="#panelc3"><i class="fa fa-fax"></i> Office Info</a>
    <div id="panelc3" class="content active">
<br><br><br><br><br><br><br>
    </div>
  </dd>

  <dd>
    <a href="#panelc4"><i class="fa fa-user"></i> User Info</a>
    <div id="panelc4" class="content active">
        

<form>
  <div class="row">

    <div class="small-6 columns">



      <div class="small-12">
        <div class="row">
          <div class="small-3 columns">
            <label for="right-label" class="right inline">Firstname</label>
          </div>
          <div class="small-9 columns">
            <input type="text" id="right-label" placeholder="Inline Text Input">
          </div>
        </div>
      </div>



      <div class="small-12">
        <div class="row">
          <div class="small-3 columns">
            <label for="right-label" class="right inline">Lastname</label>
          </div>
          <div class="small-9 columns">
            <input type="text" id="right-label" placeholder="Inline Text Input">
          </div>
        </div>
      </div>




      <div class="small-12">
        <div class="row">
          <div class="small-3 columns">
            <label for="right-label" class="right inline">Email</label>
          </div>
          <div class="small-9 columns">
            <input type="text" id="right-label" placeholder="Inline Text Input">
          </div>
        </div>
      </div>


      <div class="small-12">
        <div class="row">
          <div class="small-3 columns">
            <label for="right-label" class="right inline">Phone</label>
          </div>
          <div class="small-9 columns">
            <input type="text" id="right-label" placeholder="Inline Text Input">
          </div>
        </div>
      </div>

  </div>
    


  <div class="small-6 columns">


      <div class="small-12">
        <div class="row">
          <div class="small-3 columns">
            <label for="right-label" class="right inline">Address</label>
          </div>
          <div class="small-9 columns">
            <input type="text" id="right-label" placeholder="Inline Text Input">
          </div>
        </div>
      </div>


      <div class="small-12">
        <div class="row">
          <div class="small-3 columns">
            <label for="right-label" class="right inline">Address 2</label>
          </div>
          <div class="small-9 columns">
            <input type="text" id="right-label" placeholder="Inline Text Input">
          </div>
        </div>
      </div>


      <div class="small-12">
        <div class="row">
          <div class="small-3 columns">
            <label for="right-label" class="right inline">City</label>
          </div>
          <div class="small-9 columns">
            <input type="text" id="right-label" placeholder="Inline Text Input">
          </div>
        </div>
      </div>


      <div class="small-12">
        <div class="row">
          <div class="small-3 columns">
            <label for="right-label" class="right inline">State</label>
          </div>
          <div class="small-9 columns">
            <input type="text" id="right-label" placeholder="Inline Text Input">
          </div>
        </div>
      </div>

  </div>







  </div>
</form>


    </div>
  </dd>

</dl>

  </div>
  

















<div class="small-2 columns">
<dl class="accordion blues" data-accordion>
  <dd>
    <a class="blues" href="#panelr1"><i class="fa fa-users"></i> Users</a>
    <div id="panelr1" class="content active">
      
    <div class="row">
          <div class="small-12 columns">
            <input type="text" placeholder="Search Corporations" />
          </div>
      </div>

      <div class="row">
          <div class="small-12 columns">


              <select multiple style="height:125px;">
                <option value="husker">Husker</option>
                <option value="starbuck">Starbuck</option>
                <option value="hotdog">Hot Dog</option>
                <option value="apollo">Apollo</option>
              </select>
          </div>

          <div class="small-12 columns">
          <ul class="button-group">
              <li><a href="#" class="tiny button green">Add</a></li>
              <li><a href="#" class="tiny button">Edit</a></li>
              <li><a href="#" class="tiny button red">Delete</a></li>
          </ul>
        </div>
        </div>


    </div>
  </dd>
  <dd>
    <a class="blues" href="#panelr2"><i class="fa fa-file-o"></i> Zones</a>
    <div id="panelr2" class="content active">
        
    <div class="row">
          <div class="small-12 columns">
            <input type="text" placeholder="Search Corporations" />
          </div>
      </div>

      <div class="row">
          <div class="small-12 columns">


              <select multiple style="height:125px;">
                <option value="husker">Husker</option>
                <option value="starbuck">Starbuck</option>
                <option value="hotdog">Hot Dog</option>
                <option value="apollo">Apollo</option>
              </select>
          </div>

          <div class="small-12 columns">
          <ul class="button-group">
              <li><a href="#" class="tiny button green">Add</a></li>
              <li><a href="#" class="tiny button">Edit</a></li>
              <li><a href="#" class="tiny button red">Delete</a></li>
          </ul>
        </div>
        </div>


    </div>
  </dd>
  <dd>
    <a class="blues" href="#panelr3"><i class="fa fa-file"></i> DMAs</a>
    <div id="panelr3" class="content active">
        

    <div class="row">
          <div class="small-12 columns">
            <input type="text" placeholder="Search Corporations" />
          </div>
      </div>

      <div class="row">
          <div class="small-12 columns">


              <select multiple style="height:125px;">
                <option value="husker">Husker</option>
                <option value="starbuck">Starbuck</option>
                <option value="hotdog">Hot Dog</option>
                <option value="apollo">Apollo</option>
              </select>
          </div>

          <div class="small-12 columns">
          <ul class="button-group">
              <li><a href="#" class="tiny button green">Add</a></li>
              <li><a href="#" class="tiny button">Edit</a></li>
              <li><a href="#" class="tiny button red">Delete</a></li>
          </ul>
        </div>
        </div>


    </div>
  </dd>
 </dl>


  </div>
</div>


<!-- Latest compiled and minified JavaScript -->
<script src='../js/jquery-1.7.2.min.js'></script>
<script src='../js/jquery.event.drag-2.0.min.js'></script>
<script src='../js/jquery.event.drop-2.0.min.js'></script>
<script src='../js/ui/minified/jquery.ui.core.min.js'></script>
<script src='../js/ui/minified/jquery.ui.widget.min.js'></script>
<script src='../js/ui/jquery.ui.datepicker.js'></script>
<script src='../js/ui/minified/jquery.ui.mouse.min.js'></script>
<script src='../js/ui/minified/jquery.ui.draggable.min.js'></script>
<script src='../js/ui/minified/jquery.ui.position.min.js'></script>
<script src='../js/ui/minified/jquery.ui.slider.min.js'></script>
<script src='../js/ui/minified/jquery.ui.resizable.min.js'></script>
<script src='../js/ui/minified/jquery.ui.dialog.min.js'></script>
<script src='../js/ui/minified/jquery.ui.resizable.min.js'></script>
<!--<script src='../js/ui/minified/jquery.ui.sortable.min.js'></script>-->
<script src='../inc/timepicker/jquery.ui.timepicker.js'></script>
<script src='../inc/foundation/js/foundation.min.js'></script>

<script src='../inc/foundation/js/vendor/modernizr.js'></script>




  <script>
  $(document).foundation({
    accordion: {
      // specify the class used for active (or open) accordion panels
      active_class: 'active',
      // allow multiple accordion panels to be active at the same time
      multi_expand: true,
      // allow accordion panels to be closed by clicking on their headers
      // setting to false only closes accordion panels when another is opened
      toggleable: true
    }
  });

  function openSingle(panel,panels){
    var panelIDs = panels.split(','); 
    $.each(panelIDs, function(i, value) {

      $("#"+value).removeClass("active");
    });

    $("#"+panel).addClass("active");
  }


  function togglePanel(panel){
    var panel = $("#"+panel);
    $(panel).toggleClass("active");
  }


  </script>
	</body>
</html>





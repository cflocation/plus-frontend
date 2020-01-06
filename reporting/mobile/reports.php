
<style>
.green { color:#008000;}
.red {color:#F00; }
.purple {color:#5801AF; } 
.blue {color:#0000FF; }
</style>

<title>ShowSeeker Mobile Reports</title>
<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/normalize.css">
<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/foundation.css">
<script src="http://www.showseeker.com/inc/foundation/js/vendor/modernizr.js"></script>


<nav class="top-bar" data-topbar>
  <ul class="title-area">
    <li class="name">
      <h1><a href="#">ShowSeeker Mobile Reports</a></h1>
    </li>
     <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
    <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
  </ul>

  <section class="top-bar-section">
    <!-- Right Nav Section -->
    <ul class="right">
      <li class="has-dropdown">
        <a href="#">Report Options</a>
        <ul class="dropdown">
          <li><?php echo $olink;?></li>
          <li><a href="index.php">Data Reports</a></li>
        </ul>
      </li>
    </ul>


  </section>
</nav>
<br>
<h4 align="center">ShowSeeker Mobile Reports</h4>
<table width="95%" align="center">
<tr><td><a href="hc/moblie_logins.php" target="_blank">Logins by Company</a></td></tr>
<tr><td><a href="hc/moblie_searches.php" target="_blank">Search Terms</td></tr>
</table>
<script src="http://www.showseeker.com/inc/foundation/js/vendor/jquery.js"></script>
<script src="http://www.showseeker.com/inc/foundation/js/foundation.min.js"></script>
<script>
	$(document).foundation();
</script>


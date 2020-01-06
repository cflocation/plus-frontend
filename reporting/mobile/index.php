<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);
	//$con = mysqli_connect("61b1ed95616f65903ff311cc9decad51aa4cac3d.rackspaceclouddb.com","vastdb","jK6YK71tJ","logs");
	//$con1 = mysqli_connect("61b1ed95616f65903ff311cc9decad51aa4cac3d.rackspaceclouddb.com","vastdb","jK6YK71tJ","On");
	$con = mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","logs");
	$con1 = mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","On");	
	

	if (isset($_GET['o'])) 	{
		$o = $_GET['o'] ; 	
		$olink = "<a href='index.php'>Show VAST</a>" ; 
		}
	else { 
		$o='0';
		$olink = "<a href='index.php?o=1'>Hide VAST</a>" ; 
	}
	if (isset($_GET['t'])) 	{
		$t = $_GET['t'] ; 	
	}
	else { 
		$t='';
	}
?>
<style>
.green { color:#008000;}
.red {color:#F00; }
.purple {color:#5801AF; } 
.blue {color:#0000FF; }
</style>

<?php if ($t !=='pdf') {?>
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
          <li><a href="pdf.php?o=<?php echo $o;?>" target="_blank">Print PDF</a></li>
        </ul>
      </li>
    </ul>


  </section>
</nav>
<br>
<?php }?>
<h4 align="center">ShowSeeker Mobile Reports</h4>
<table width="95%" align="center">
<tr>
	<th width="21.25%"><u>Corporation</u></th>
	<th width="21.25%"><u>Date</u></th>
	<th width="21.25%"><u>User</u></th>
	<th width="21.25%"><u>Action</u></th>
</tr>
<?php
	
	
if ($o == '1') { 
	$sql = "SELECT mobilelogs.userid, eventslogid, mobilecodes.name as codename, request, result, mobilelogs.createdat, firstname, lastname, corporationid, corporations.name as corpname FROM `mobilelogs` INNER JOIN mobilecodes ON mobilelogs.eventslogid = mobilecodes.id INNER JOIN ShowSeeker.users ON mobilelogs.userid = ShowSeeker.users.id INNER JOIN ShowSeeker.corporations ON ShowSeeker.users.corporationid = ShowSeeker.corporations.id where ShowSeeker.corporations.id != '14' order by createdat DESC";

}
else {
	$sql = "SELECT mobilelogs.userid, eventslogid, mobilecodes.name as codename, request, result, mobilelogs.createdat, firstname, lastname, corporationid, corporations.name as corpname FROM `mobilelogs` INNER JOIN mobilecodes ON mobilelogs.eventslogid = mobilecodes.id INNER JOIN ShowSeeker.users ON mobilelogs.userid = ShowSeeker.users.id INNER JOIN ShowSeeker.corporations ON ShowSeeker.users.corporationid = ShowSeeker.corporations.id  order by createdat DESC";
}
	$reportlookup = mysqli_query($con,$sql);


	while($row = mysqli_fetch_array($reportlookup)) {
	
		$userid = $row['userid'];
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		$corpname = $row['corpname'];
		$createdat = $row['createdat'];
		$codename = $row['codename'];
		$code = $row['eventslogid'];
		$request = $row['request'];
		$value = $row['result'];

		if  (($codename == 'PROPOSAL CREATED') || ($codename == 'PROPOSAL RENAME') ) {
			$codename = '<font color="#0000FF">'.$codename.'</font>';
		}
		elseif (($codename == 'PROPOSAL DELETED') or ($codename == 'PROPOSAL DELETE LINE')) {
			$codename = '<font color="#F00">'.$codename.'</font>';
		}
		elseif (($codename == 'LOGIN - iSEEKER') ||($codename == 'LOGIN - iPHONE') ) {
			$codename = '<font color="#197F18">'.$codename.'</font>';
		}

		$createDate = new DateTime($createdat);
		$datestamp = $createDate->format('m-d-y - H:i');
		$date = $createDate->format('m-d-y');

		if  ($code == '55') {
			$extra = "(".$request.")";
		}
		else {
			$extra= "";
		}
		if  (($code == '37') || ($code == '38')  ){
			$extra = "(".$value.")<br>(".$request.")";
		}
		if  (($code == '1') || ($code == '60') || ($code == '67') || ($code == '72') ){
			$extra = "(".$value.")";
		}

		if  ($code == '72')  {
			$show_lookup = "SELECT onShowcardId.showcardId, title FROM onShowcardId INNER JOIN onShowcardTitles ON onShowcardId.showcardId = onShowcardTitles.showcardId WHERE TMSid = '{$value}' LIMIT 1, 1";
			$show_title = mysqli_query($con1,$show_lookup);
			$row_title = mysqli_fetch_array($show_title);
			$extra = "( ". $row_title['title'] . " )";
		}

		echo "<tr align=center><td>{$corpname}</td><td>{$datestamp}</td><td>{$firstname} {$lastname}</td><td>{$codename} {$extra}</td></tr>";

	}
?>
</table>
<script src="http://www.showseeker.com/inc/foundation/js/vendor/jquery.js"></script>
<script src="http://www.showseeker.com/inc/foundation/js/foundation.min.js"></script>
<?php if ($t !=='pdf') {?>
<script>
	$(document).foundation();
</script>
<?php }?>
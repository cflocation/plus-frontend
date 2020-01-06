<?php
include 'db.php' ; 
$now = date("Y-m-d H:i:s");

if (isset($_GET['search'])) {
$search = ($_GET['search']); }

$sql = "SELECT ShowSeeker.users.firstname, ShowSeeker.users.lastname, ShowSeeker.users.corporationid, ShowSeeker.corporations.name, searches.createdat FROM `searches` INNER JOIN ShowSeeker.users ON searches.userid = users.id INNER JOIN ShowSeeker.corporations ON ShowSeeker.users.corporationid = ShowSeeker.corporations.id WHERE `data` LIKE '$search' ORDER BY corporations.name, ShowSeeker.users.lastname, searches.createdat ASC" ;
$searches = mysqli_query($con, $sql);
?>

<head>
<title>ShowSeeker Search Clouds</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/normalize.css">
<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/foundation.css">
<script src="http://www.showseeker.com/inc/foundation/js/vendor/modernizr.js"></script>
</head>
<?php include 'top-menu.php'; ?>
<br />
<center><h4>Search Term: <u><?php echo $search;?></u></h4></center>
<table align="center">
<tr><th>Corporation</th><th>User</th><th>Date Searched</th></tr>
<?php 

while ($row = mysqli_fetch_array($searches)) {

echo "<tr><td>".$row['name']."</td><td>".$row['firstname'] ." ".$row['lastname']."</td><td>" .  $row['createdat'] . "</td></tr>"; 
}
?>
</table>
<script src="http://www.showseeker.com/inc/foundation/js/foundation.min.js"></script>
<script>
$(function() {

$( "#datefrom" ).datepicker({
  showOn: "focus",
  numberOfMonths: 3,
  buttonImageOnly: true,
  onClose: function(dateText, inst) { 
  $.post("d1.php", {"datefrom": dateText});
}
});



 $( "#dateto" ).datepicker({
  showOn: "focus",
  numberOfMonths: 3,
  buttonImageOnly: true,
  onClose: function(dateText, inst) { 
  $.post("d2.php", {"dateto": dateText});
}
});


});
</script>
<script>
	$(document).foundation();
</script>
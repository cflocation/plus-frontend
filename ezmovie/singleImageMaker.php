<!DOCTYPE html>
<html lang="en">
<head>
<title>Single Image Generator</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="cache-control" content="no-cache"> <!-- tells browser not to cache -->
<meta http-equiv="expires" content="0"> <!-- says that the cache expires 'now' -->
<meta http-equiv="pragma" content="no-cache"> <!-- says not to use cached stuff, if there is any -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<link rel="stylesheet" href="/inc/foundation/css/normalize.css">
<link rel="stylesheet" href="/inc/foundation/css/foundation.css">
<script src="/inc/foundation/js/vendor/modernizr.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
</head>


<?
//Example: 
//http://plus.showseeker.com/ezmovie/upload.php?id=MV004978610000&title=12%20Years%20a%20Slave&genre=drama
?>
<br />
<center><h4>Single Image Generator</h4></center>
<form action="upload.php">
	<center><table width="85%">
		<tr><td><input type="text" name="id" id="id" placeholder="Movie ID" required></td><td><input type="text" name="title" id="title" placeholder="Movie Title" required></tr></td>
		<tr><td colspan="2"><center><input type="submit" value="Continue" id="submit" name="submit" class="button tiny"></center></tr></td>
		<tr><td colspan="2"><br /><center><h5><u>How to Use:</u><br />Copy & Paste <strong>Movie ID</strong> and <strong>Movie Title</strong> <br />from ShowSeeker <a href="http://go.showseeker.com" target="_blank">GO</a> for each Movie</h5><br /><img src="css/howTo.png" border="1"></center></tr></td>
	</table></center>
</form>








<script>
$(document).ready(function(){
   $("#submit").click(function(){   
	$('#id').val($('#id').val() + '0000');
  });
});
</script>


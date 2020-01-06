<?php
	session_start();
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);

?>
<head>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
</head>
<script>
$.ajax({
   url: "http://usatoday30.usatoday.com/life/nielsen/data_tables/Cable_20150225.js",
   success: function(data){
     $("#responseArea").text(data);
   }
 });
</script>
<div id="responseArea"> </div>
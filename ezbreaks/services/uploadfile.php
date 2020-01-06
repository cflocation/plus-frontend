<?php
ini_set("display_errors", "on");
error_reporting(E_ALL);
$path = "/var/www/html/www.showseeker.com/tmp/breaks/";
$allowedExts = array("xls");
$temp = explode(".", $_FILES["file"]["name"]);
$filetype = end($temp);

if($filetype == "xls"){
	$file = "espn".date("mdY_").strtolower($_FILES["file"]["name"]);
	move_uploaded_file($_FILES["file"]["tmp_name"], $path . $file);
	print_r($file);
}
return;
?> 
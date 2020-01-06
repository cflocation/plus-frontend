<?php
$allowedExts = array("xls");

$temp = explode(".", $_FILES["file"]["name"]);
$filetype = end($temp);


if(end($temp) == "xls"){
	$file = strtolower($_FILES["file"]["name"]);
	move_uploaded_file($_FILES["file"]["tmp_name"], "/tmp/" . $file);
	print_r($file);
}
return;
?> 
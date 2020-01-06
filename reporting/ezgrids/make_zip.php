<?php
	session_start();
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);

/*
* PHP ZIP - Create a ZIP archive
*/

$zip = new ZipArchive;
if ($zip->open('/var/www/html/showseeker.com/reporting/ezgrids/temp/test112.zip',  ZipArchive::CREATE)) {
    $zip->addFile('test.txt', 'newname.txt');
    $zip->close();
    echo 'Archive created!';
} else {
    echo 'Failed!';
}
?>
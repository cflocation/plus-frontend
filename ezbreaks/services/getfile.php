<?php
    // Fetch the file info.
    if(!isset($_GET['file'])){
        return;
    }


    session_start();
    $filename = $_GET['file'];
    $file = '/var/www/html/www.showseeker.com/tmp/ratecards/'.$filename;


    if(file_exists($file)) {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Content-Length: ' . filesize($file));
        readfile($file);                
        exit();
    }
    else {
        print '{"filename":"error"}';
    }
?>
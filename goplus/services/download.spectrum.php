<?php    // Fetch the file info.    if(!isset($_GET['filename'])){        return;    }    $filename = $_GET['filename'];    $filePath = '/var/www/html/showseeker.com/plus/downloads/temp/'.$filename;        if(file_exists($filePath)) {        $fileName = basename($filename);        $fileSize = filesize($filePath);        // Output headers.        header("Cache-Control: private");        header("Content-Type: application/stream");        header("Content-Length: ".$fileSize);        header("Content-Disposition: attachment; filename=".$fileName);        // Output file.        readfile ($filePath);                           exit();    }    else {        print '{"filename":"error"}';    }?>
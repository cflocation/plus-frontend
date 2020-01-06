<?php

function updatedAt(){
	$dir    = dirname(__FILE__);
	$files2 = scandir($dir, 1);
	
	$close_date = date ("m/d/Y H:i", filemtime($files2[0]));
	foreach ($files2 as $filename) {
	
		if (file_exists($filename)) {
			$newFile = date ("m/d/Y H:i", filemtime($filename));
			if( abs(strtotime('now') - strtotime($newFile)) < abs(strtotime('now') - strtotime($close_date))){
				$close_date = $newFile;
				$file       = $filename;
			}
			
		} 
	}
	return $close_date;
}
?>
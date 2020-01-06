<?php
header("Content-type: text/css", true);
$files = array(

	'../css/custom-theme/jquery.ui.core.css',
	'../css/custom-theme/jquery.ui.resizable.css',
	'../css/custom-theme/jquery.ui.selectable.css',
	'../css/custom-theme/jquery.ui.accordion.css',
	'../css/custom-theme/jquery.ui.autocomplete.css',
	'../css/custom-theme/jquery.ui.button.css',
	'../css/custom-theme/jquery.ui.dialog.css',
	'../css/custom-theme/jquery.ui.slider.css',
	'../css/custom-theme/jquery.ui.tabs.css',
	'../css/custom-theme/jquery.ui.datepicker.css',
	'../css/custom-theme/jquery.ui.progressbar.css',
	'../css/custom-theme/jquery-ui-1.8.21.custom.css',
    '../inc/superfish/css/superfish.css',
    '../inc/superfish/css/superfish-navbar.css',
    '../inc/timepicker/jquery.timepicker.css',
    'slickgrids/slick.grid.css',
    'slickgrids/grids.css',
    'css/style.css'
    
);

foreach($files as $file) {
    print file_get_contents($file);
}
?>


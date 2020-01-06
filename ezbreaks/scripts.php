<?php
include('../inc/jshrink/Minifier.php');
ini_set("display_errors",1);
header("content-type: application/javascript");
$files = array(
    '../js/jquery-1.7.2.min.js',
    '../js/jquery.event.drag-2.0.min.js',
    '../js/jquery.event.drop-2.0.min.js',
    '../js/ui/minified/jquery.ui.core.min.js',
    '../js/ui/minified/jquery.ui.widget.min.js',
    '../js/ui/jquery.ui.datepicker.js',
    '../js/ui/minified/jquery.ui.mouse.min.js',
    '../js/ui/minified/jquery.ui.draggable.min.js',
    '../js/ui/minified/jquery.ui.position.min.js',
    '../js/ui/minified/jquery.ui.resizable.min.js',
    '../js/ui/minified/jquery.ui.dialog.min.js',
    '../js/ui/minified/jquery.ui.resizable.min.js',
    '../js/ui/minified/jquery.ui.sortable.min.js',
    '../inc/timepicker/jquery.ui.timepicker.js',
    '../inc/foundation/js/foundation.min.js',
    '../js/date.js'
    
);

foreach($files as $file) {
    $js = file_get_contents($file);
    print JShrink\Minifier::minify($js);
}
?>


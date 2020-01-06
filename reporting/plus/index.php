<?php
session_start();
$month = date("n") ; 

$launchstring = "trends_monthly.php?m=".$month;

header('Location: '.$launchstring.'') ;

?>

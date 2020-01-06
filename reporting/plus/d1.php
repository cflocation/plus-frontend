<?php
session_start();
// set the variable
	$_SESSION['dfc'] = $_POST['datefrom'];
	$dfc =  date("Y-m-d", strtotime($_POST['datefrom']));
    $_SESSION['datefrom'] = $dfc ;

?>
<?php
session_start();
// set the variable
	$_SESSION['dtc'] = $_POST['dateto'];
	$dtc = date("Y-m-d", strtotime($_POST['dateto']));
    $_SESSION['dateto'] = $dtc ;

?>
<?php
	include_once('../../inc/permissions.php');

	$superadmin = isRole($roles,2);
	$re = array("sa"=>$superadmin);
	print json_encode($re);
?>
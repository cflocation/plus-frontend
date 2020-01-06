<?php 
	include_once('../database.php');	

	$defaultoffice = $_GET['id'];

	$officesql 	    = "	SELECT 	
						Address.address 		AS address,
						Address.address2 		AS address2,
						Address.city 			AS city,
						State.name 				AS state,
						Address.zip 	 		AS zipcode,
						Country.abbreviation 	AS country,
						Office.phone			AS officephone, 
						Office.name				AS officeName, 
						Market.name 			AS marketname,
						Market.id 				AS marketid
						
						FROM 					OfficeAddress
						
						LEFT OUTER JOIN 		Address 
						ON 						OfficeAddress.addressId = Address.id
						
						LEFT OUTER JOIN 		State 
						ON 						Address.stateId =  State.id
						
						LEFT OUTER JOIN 		Country 
						ON 						Address.countryId =  Country.id
						
						LEFT OUTER JOIN 		Office
						ON						OfficeAddress.officeId = Office.id
						
						LEFT OUTER JOIN			Market
						ON						Office.regionId = Market.id
						
						WHERE 					officeId = ".$defaultoffice."
						AND 					Market.deletedAt is NULL 
						AND 					Office.deletedAt is NULL";
						
	$officesdefault = mysql_query($officesql);
	$officedefault 	= mysql_fetch_assoc($officesdefault);	
	
	header("Content-type: application/json; charset=utf-8");	
	print json_encode($officedefault);
	exit();
?>
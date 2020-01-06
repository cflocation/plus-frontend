
<?php
	$id 			= $_GET['id'];
	$defaultoffice 	= $_GET['officeid'];  
	$uuid 			= uniqid();
	
	
	include_once('database.php');

	$sql = 'SELECT			User.active,
							User.mobile AS cell,
							User.corporationId AS corporationid,
							User.email,
							User.fax,
							User.firstName AS firstname,
							User.id,
							User.lastName AS lastname,
							User.phone,
							User.title,
							DATE_FORMAT(IFNULL(CONVERT_TZ(emailSent,"UTC","US/Pacific"),"Never"),"%m/%d/%Y %H:%i") as emailSent,
							DATE_FORMAT(IFNULL(CONVERT_TZ(passUpdatedAt,"UTC","US/Pacific"),"Never"),"%m/%d/%Y %H:%i") as passUpdatedAt,
							IFNULL(User.passwordHash,0) as passwordHash,
							User.apiKey AS tokenid,
							MD5(CONCAT(User.id,User.apiKey)) AS apiKey,
							UserRole.roleId AS roleid,
							UserOfficeDefault.officeId AS officedefault,
							UserOffice.officeId AS officeid,
							UserAddress.address,
							UserAddress.address2,
							UserAddress.city,
							UserAddress.stateId AS statesid,
							UserAddress.zip AS zipcode,
							UserAddress.countryIid AS countriesid,
							Market.name as marketname, 
							Market.id as marketid
		FROM 				User
		
		LEFT OUTER JOIN 	UserRole  
		ON 					User.id = UserRole.userId			
		
		LEFT OUTER JOIN 	UserOffice AS UserOfficeDefault  
		ON 					User.id = UserOfficeDefault.userId AND UserOfficeDefault.default=1
		
		LEFT OUTER JOIN 	UserOffice  
		ON 					User.id = UserOffice.userId
		
		LEFT OUTER JOIN 	UserAddress  
		ON 					UserAddress.userId = User.id 
		
		LEFT OUTER JOIN 	Office 
		ON 					UserOffice.officeId = Office.id 
		
		LEFT OUTER JOIN		Market 
		ON 					Office.regionId = Market.id
		WHERE 				User.corporationid = 46
		AND 				User.id 		= '.$id;

	$re 			= mysql_query($sql);
 	$officeid 		= array();


	$userFirstname 	= "";
	$userLastname 	= "";
	$userEmail 		= "";
	$userTitle 		= "";	
	$userPhone 		= "";
	$userCell 		= "";
	$userEmailSent 	= "";
	$userPwdUpdated = "";
	$userStateId 	= 0;
	$userCity 		= "";
	$userAddress 	= "";
	$userAddress2 	= "";
	$userZipCode 	= "";
	$userActive 	= 1;


 	//loop over the records and set the ids
	while($row = mysql_fetch_array($re)){
	    $officeid[] 	= $row['officeid'];
	}

	mysql_data_seek($re, 0); 
	$row 				= mysql_fetch_assoc($re);
		 
	$corpid = 46;		

	if($id != 0){
		
		$userFirstname 	= $row["firstname"];
		$userLastname 	= $row["lastname"];
		$userEmail 		= $row["email"];
		$userTitle 		= $row["title"];
		$userPhone 		= $row["phone"];
		$userCell 		= $row["cell"];
		$userEmailSent 	= $row['emailSent'];
		$userPwdUpdated = $row['passUpdatedAt'];
		$userStateId 	= $row["statesid"];
		$userCity 		= $row["city"];
		$userAddress 	= $row["address"];
		$userAddress2 	= $row["address2"];
		$userZipCode 	= $row["zipcode"];
		$userActive 	= $row['active'];		
		$defaultoffice 	= $row['officedefault'];
	}

    //get all the offices for the corporation id						
	$sql_office 	= "SELECT 		Office.id AS officeid, 
									Office.name AS office, 
									Market.name AS market
						FROM 		Market 
						
						INNER JOIN 	Office 
						ON 			Market.id = Office.regionId 
						
						WHERE 		Market.deletedAt IS NULL 
						AND 		Office.active = 1 
					    AND 		Office.deletedAt IS NULL 
						AND 		Office.id not IN (415,416)
					    AND 		Market.corporationId = {$corpid}
						ORDER BY 	Market.name, Office.name";										
						
	$offices 		= mysql_query($sql_office);
    $cnt 			= mysql_num_rows($offices);

	//OFFICES
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
    $officeCnt 		= mysql_num_rows($officesdefault);

	
	$statesql 		= "	SELECT 		id, name 
						FROM 		State 
						ORDER BY 	name";
	
	$resultsql 		= mysql_query($statesql);

	if(isset($_GET['officeid']) && $_GET['officeid']){
		$defaultoffice = $_GET['officeid'];
		$officeid[]    = $_GET['officeid'];
		$row['active'] = 1;
	}
	
	$usrName = 'New Account';

	if($id != 0){
		$usrName =$row['firstname'].' '.$row['lastname'];
	}

   //get all the offices for the corporation id						
	$sql_role 		= 	"SELECT 	*
						FROM 		UserRole
						WHERE 		userId = {$id}";
	$roles 			= mysql_query($sql_role);
	$isAdmin 		= false;

	while($r = mysql_fetch_array($roles)){
		if($r['roleId'] == 6){
			$isAdmin = true;
		}
	}



?>

<!DOCTYPE html>
<html>
    <head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">

		<link href="https://showseeker.s3.amazonaws.com/public-site/assets/favicon.png" name="favicon" rel="shortcut icon" type="image/png">
		<link rel="stylesheet" href="https://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css" />
		<link rel="stylesheet" href="../inc/fontawesome/css/font-awesome.min.css?<?php print 'v='.uniqid();?>" />	    
		<link rel="stylesheet" href="css/style.css?<?php print 'v='.uniqid();?>"/>
		
		<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script src="https://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>	
		<script src="js/mixPanel.js"></script>	
		<script src="js/log.events.js"></script>	
		<script src="js/ip.js?v=<?php print $uuid; ?>"></script>
		
	    <title>Admin | ShowSeeker</title>		
	</head>

	<body>

		<div data-role="page" id="user-page" data-dom-cache="false">	
		
		    <div data-role="header" data-position="fixed" id="page-header-container">
				<a href="#" rel="external" data-iconpos="notext"  data-icon="back" id="returnToPage">Back</a>
				<h1><?php print($usrName)?></h1>	
				<a href="#popupMenu" data-rel="popup" data-icon="bars" data-role="button" class="ui-btn-right" data-iconpos="notext" data-position-to="#position-header"></a>
		    </div>
			<div data-role="popup" id="popupMenu">
				<ul data-role="listview" data-inset="true" data-theme="a">
					<li data-icon="false"><a href="/plus" target="_self">ShowSeeker</a></li>
					<li data-icon="false">
						<a href="javascript:void(0)" onclick="openTutorial('https://showseeker.s3.amazonaws.com/tutorials/manuals/ShowSeeker_Spectrum-Reach_User-Admin-Guide_021419.pdf'); mixTrack('Admin - User Guide');" id="adminUserGuide">Admin User Guide</a>
					</li>			
				</ul>
			</div>
		
		    <!-- content -->
		    <div data-role="content">				

				<center>
					<h4 class="nopadding"></h4>
			    </center>
		    		   
				<div data-role="collapsible-set" data-theme="b" data-content-theme="d">	
		
					<div data-role="collapsible"  data-collapsed="false">
						<h3>User Information </h3>		
			
						<div class="ui-grid-a my-breakpoint">						
							<div class="ui-block-a" style="padding-left:10px;padding-right:10px;">
								<label for="firstname">First Name: <span class="required">*</span></label>
				            	<input required="true" name="firstname" id="firstname" value="<?php print $userFirstname; ?>" type="text" data-clear-btn="true">
							</div>
							
							<div class="ui-block-b" style="padding-left:10px;padding-right:10px;">
								<label for="lastname">Last Name: <span class="required">*</span></label>
								<input required="true" name="lastname" id="lastname" value="<?php print $userLastname; ?>" type="text" data-clear-btn="true">
							</div>
							
							<div class="ui-block-a" style="padding-left:10px;padding-right:10px;">
								<label for="email">Email: <span class="required">*</span></label>
								<input data-clear-btn="true"  required="true" name="email" id="email" value="<?php print $userEmail; ?>" type="text">
								<input name="override" id="override" value="0" type="hidden">
							</div>
							
							<div class="ui-block-b" style="padding-left:10px;padding-right:10px;">
								<label for="title">Title:  <span class="required">*</span></label>
								<input data-clear-btn="true" name="title" id="title" value="<?php print $userTitle; ?>" type="text">
							</div>
						
							<div class="ui-block-a" style="padding-left:10px;padding-right:10px;">
								<label for="phone">Phone:</label>
								<?php if($id == 0){?>
								<input data-clear-btn="true" name="phone" id="phone" value="<?php print $officedefault["officephone"] ?>" type="text">
								<?php }else{?>
								<input data-clear-btn="true" name="phone" id="phone" value="<?php print $userPhone; ?>" type="text">			    	
								<?php }?>
							</div>
						
							<div class="ui-block-b" style="padding-left:10px;padding-right:10px;">
								<label for="cell">Cellphone:</label>
								<input data-clear-btn="true" name="cell" id="cell" value="<?php print $userCell; ?>" type="text">
							</div>

							<div class="ui-block-a" style="padding-left:10px;padding-right:10px;">
								<?php if($userEmailSent != ''){?>
								<label for="cell">Sent Email:</label>
								<input  name="sentEmail" id="sentEmail" value="<?php print $userEmailSent;?>" type="text" readonly="true">
								<?php }?>
							</div>
		
							<div class="ui-block-b" style="padding-left:10px;padding-right:10px;">
								<?php if($userEmailSent != ''){?>
								<label for="register">Password Updated At:</label>
								<input  name="register" id="register" value="<?php print $userPwdUpdated;?>" type="text" readonly="true">
								<?php }?>						
							</div>
			
							<div class="ui-block-a" style="padding-left:5px;padding-right:10px;">
								<input type="hidden" value="<?php print($defaultoffice);?>"  id="defaultoffice">
								<label  style="padding-left:5px;" for="active">Office: <span class="required">*</span> 
									<a href="#popupInfo" data-rel="popup" data-transition="pop">
										<i class="fa fa-info-circle" style="color: #333;"></i>
									</a>
								</label>
								<a href="#both" data-rel="popup" data-role="button" id="officeNameInfo">
									<?php 
										if($officeCnt > 0){
											print($officedefault['officeName']);
										}
										else{
											print('Select Office');
										}?>
								</a>
								
								
								<p style="display: none;">
								<label  style="padding-left:5px;" for="active">Active:</label>
								<select name="active" id="active">
									<option <?php if($userActive == 1){print 'selected="selected"';} ?> value="1">Yes</option>
									<option <?php if($userActive == 0){print 'selected="selected"';} ?> value="0">No</option>
								</select>
								</p>
							</div>
							
							<div class="ui-block-b" style="padding-left:10px;padding-right:10px;">
								<label for="userRole">Role:</label>
								<select id="userRole">
									<option <?php if(!$isAdmin){print('selected=selected');}?> value="15">User</option>
									<option <?php if($isAdmin){print('selected=selected');}?> value="6">Admin</option>
								</select>
							</div>
						</div>
					</div>
					
					
					
					<div data-role="collapsible"  data-collapsed="true">
		
						<h3>Alternate Address</h3>
		
						<div>
							<div class="ui-grid-a my-breakpoint">
								<div class="ui-block-a" style="padding-left:10px;padding-right:10px;">
							    	<label for="address">Address:</label>
							    	<input data-clear-btn="true" name="address" id="address" value="<?php print $userAddress; ?>" type="text">
								</div>
								<div class="ui-block-b" style="padding-left:10px;padding-right:10px;">
									<label for="address2">Address2:</label>
							    	<input data-clear-btn="true" name="address2" id="address2" value="<?php print $userAddress2; ?>" type="text">
								</div>
								<div class="ui-block-a" style="padding-left:10px;padding-right:10px;">
							    	<label for="city">City:</label>
							    	<input data-clear-btn="true" name="city" id="city" value="<?php print $userCity; ?>" type="text">
								</div>
								<div class="ui-block-b" style="padding-left:10px;padding-right:10px;">
									<label for="state">State:</label>
					                <select name="state" id="state">
										<?php while ($state = mysql_fetch_assoc($resultsql)): ?>
											<option <?php if($state["id"] == $userStateId){print 'selected="selected"';} ?> value="<?php print $state["id"]; ?>"><?php print $state["name"]; ?></option>
										<?php endwhile; ?> 
									</select>
								</div>
								<div class="ui-block-a" style="padding-left:10px;padding-right:10px;">
							    	<label for="zip">Zip:</label>
							    	<input data-clear-btn="true" name="zip" id="zip" value="<?php print $userZipCode; ?>" type="text">
								</div>
								<div class="ui-block-b" style="padding-left:10px;padding-right:10px;">
									<?php if($userAddress != ''){ ?>
									<label  style="padding-left:5px;" for="active" id="lbl-del-alt-address">
										Remove Alternative Address:
									</label>
									<a data-icon="check" href="#" id="a-del-alt-address" data-role="button" data-theme="e" data-inline="true" onclick="deleteAltAddress(<?php print $row['id'];?>);">Delete</a>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
					<p>
						<fieldset class="ui-grid-c">
							<div class="ui-block-a">
								<?php if($id != 0):?>
								<center>
								<a 	href="#popupDialog" 
										data-role="button" 
										data-inline="true"
										data-rel="popup" 
										style="width: 80%;">
										<i style="color:red;"   class="fa fa-minus-circle fa-lg"></i>
										Delete
								</a>
								</center>
								<?php endif?>
							</div>

							<div class="ui-block-b">
								<?php if($id != 0):?>
								<center>								
								<a  style="width: 80%;"
									href="javascript:sendToken('<?php print $id ?>');" 
									data-role="button" 
									data-inline="true"
									id="resetUserAccount">
									<i style="color:red;" class="fa fa-repeat fa-lg"></i> 
									Pwd Reset
								</a>
								</center>
								<?php endif?>
							</div>
							
							<div class="ui-block-c">									
								<center>
									<a 	href="#popupNotification"  
										data-transition="fade" 
										data-role="button" 
										data-inline="true" 
										data-rel="popup" 
										id="usrEmailer"
										onclick="clearPopup();"
										style="width: 80%; display: none;">
										<i style="color:green;" class="fa fa-envelope"></i>
										Send New User Email
									</a>
								</center>
							</div>


							<div class="ui-block-d">
								<center>								
								<a  style="width: 80%;"
									href="javascript:userSetup(<?php print $id ?>,<?php print $corpid ?>,<?php print $id ?>);" 
									data-role="button" 
									data-inline="true"
									id="saveUserAccount">
									<i style="color:green;" class="fa fa-check fa-lg"></i> 
									Save Changes
								</a>
								</center>
							</div>
						</fieldset>	
					</p>					

				</div>	
				
				
				<!-- NEW USER NOTIFICATION UI -->				
				<div data-role="popup" id="popupNotification" data-overlay-theme="a"  data-theme="c" data-corners="false" class="ui-corner-all" style="width:320px;">
					
					<a href="#" data-rel="back" class="ui-btn ui-corner-all  ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">
						<i style="color: #777;" class="fa fa-times-circle fa-2x"></i>
					</a>
					
					<div style="padding:10px 30px;" id="emailFormBody">
						<h3><center>Send New User Email</center></h3>
						<label for="usrEmail" class="ui-hidden-accessible">Email Address</label>
						<input readonly="true" type="text" name="usrEmail" id="usrEmail" value="<?php print $userEmail; ?>" data-theme="a" />
						<br />
						<input type="hidden" name="first" id="first" value="<?php print $userFirstname; ?>" />
						<input type="hidden" name="id" id="id" value="<?php print $id; ?>" />
						<input type="hidden" id="corporationId" value="<?php print $corpid;?>" />
						<input type="button" id="searchForm" data-theme="b" onclick="sendNotification();" value="Send Email" />
					</div>
					
					<div id="emailFormConfirmation" style="display: none;">
						<center>
							<p>Alright! <br/></br> Email sent!</p>					
							<a 	href="#" data-role="button" data-inline="true" data-rel="back" data-theme="c" data-corners="true" 
								data-shadow="true" data-iconshadow="true" data-wrapperels="span" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-up-a">
								<span class="ui-btn-inner ui-btn-corner-all">
									<span class="ui-btn-text">Ok</span>
								</span>
							</a>
						</center> 
					</div>
				
				</div>				
				
				
				<!-- OFFICE ADDRESS -->
				<div data-role="popup" id="popupInfo" class="ui-content" data-theme="c" style="max-width:350px;">
					<p>
						<center id="officeAddressInfo">
							<?php print $officedefault["address"] ?>,
							<?php print $officedefault["city"] ?><br />
							<?php print $officedefault["state"] ?>,
							<?php print $officedefault["zipcode"] ?>
						</center>
					</p>
				</div>	

				<!-- DELETE USER POP UP -->
				<div data-role="popup" id="popupDialog" data-overlay-theme="a" data-theme="c" style="min-width:320px; max-width:400px;" 
					class="ui-corner-all ui-popup ui-body-c ui-overlay-shadow" aria-disabled="false" data-disabled="false" 
					data-shadow="true" data-corners="true" data-transition="none" data-position-to="window">
			
					<div data-role="header" data-theme="a" class="ui-corner-top ui-header ui-bar-a" role="banner">
						<h1 class="ui-title" role="heading" aria-level="1">Delete User?</h1>
					</div>
			
					<div data-role="content" data-theme="d" class="ui-corner-bottom ui-content ui-body-d" role="main">
						<center>
							<p>Do you want us to continue?</p>
						</center>
						<center>
							<a 	href="#" data-role="button" data-inline="true" data-rel="back" data-theme="c" data-corners="true" 
								data-shadow="true" data-iconshadow="true" data-wrapperels="span" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-up-c">
								<span class="ui-btn-inner ui-btn-corner-all">
									<span class="ui-btn-text">Cancel</span>
								</span>
							</a>
							<a 	href="#" onclick="userDelete(<?php print $id; ?>,<?php print $corpid; ?>);" data-role="button" data-inline="true" data-rel="back" data-transition="flow" 
								data-theme="b" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-up-b">
								<span class="ui-btn-inner ui-btn-corner-all">
									<span class="ui-btn-text">Delete</span>
								</span>
							</a> 
						</center> 
					</div>
				</div>

				<!-- UNDO DELETE POP UP -->
				<div data-role="popup" id="popupUndo" data-overlay-theme="a" data-theme="c" style="min-width:320px; max-width:400px;" 
					class="ui-corner-all ui-popup ui-body-c ui-overlay-shadow" aria-disabled="false" data-disabled="false" 
					data-shadow="true" data-corners="true" data-transition="none" data-position-to="window">
			
					<div data-role="header" data-theme="a" class="ui-corner-top ui-header ui-bar-a" role="banner">
						<h1 class="ui-title" role="heading" aria-level="1">Undo Delete ?</h1>
					</div>
			
					<div data-role="content" data-theme="d" class="ui-corner-bottom ui-content ui-body-d" role="main">
						<p>
							<center>Just curious, want to undo?</center>
						</p>
						<center>
							<a 	href="#" id="navBack" data-role="button" data-inline="true" data-theme="c" data-corners="true" 
									data-shadow="true" data-iconshadow="true" data-wrapperels="span" 
									class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-up-c">
								<span class="ui-btn-inner ui-btn-corner-all">
									<span class="ui-btn-text">No</span>
								</span>
							</a>
							<a 	href="#" onclick="undoSingleUserDelete(<?php print $id; ?>,<?php print $corpid; ?>);" data-role="button" data-inline="true" data-rel="back" data-transition="flow" 
								data-theme="b" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-up-b">
								<span class="ui-btn-inner ui-btn-corner-all">
									<span class="ui-btn-text">Undo</span>
								</span>
							</a> 
						</center> 
					</div>
				</div>


				
				<!-- WARNING MSG -->
				
				<div data-role="popup" id="popupWarning" data-overlay-theme="a" data-theme="c" style="min-width:320px; max-width:400px;" 
					class="ui-corner-all ui-popup ui-body-c ui-overlay-shadow" aria-disabled="false" data-disabled="false" 
					data-shadow="true" data-corners="true" data-transition="none" data-position-to="window">
			
					<div data-role="content" data-theme="d" class="ui-corner-bottom ui-content ui-body-d" role="main">
						<center>
							<p>	
								Oops! 
								<br/><br/>
								Please fill all required fields.
							</p>
							<a 	href="#" data-role="button" data-inline="true" data-rel="back" data-theme="c" data-corners="true" 
								data-shadow="true" data-iconshadow="true" data-wrapperels="span" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-up-c">
								<span class="ui-btn-inner ui-btn-corner-all">
									<span class="ui-btn-text">OK</span>
								</span>
							</a>							
						</center> 
					</div>
				</div>


				<!-- NEW USER MSG -->
				
				<div data-role="popup" id="popupNewUser" data-overlay-theme="a" data-theme="c" style="min-width:320px; max-width:400px;" 
					class="ui-corner-all ui-popup ui-body-c ui-overlay-shadow" aria-disabled="false" data-disabled="false" 
					data-shadow="true" data-corners="true" data-transition="none" data-position-to="window">
			
					<div data-role="content" data-theme="d" class="ui-corner-bottom ui-content ui-body-d" role="main">
						<center>
							<p>	
								<span id="msg1" class="newUserMsg">New Account Successfully Added!</span>
								<span id="msg2" class="newUserMsg">New Account Successfully Added!</span>
								<span id="msg3" class="newUserMsg">New Account Successfully Added!</span>

								<br/><br/>
								<span style="font-size:small;"> The New User email has been sent.</span>
							</p>
							<a 	href="#" data-role="button" data-inline="true" data-rel="back" data-theme="c" data-corners="true" 
								data-shadow="true" data-iconshadow="true" data-wrapperels="span" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-up-c">
								<span class="ui-btn-inner ui-btn-corner-all">
									<span class="ui-btn-text">Ok</span>
								</span>
							</a>
						</center> 
					</div>
				</div>
				
				<!-- DELETED ADDRESS MSG -->
				
				<div data-role="popup" id="popupDeletedAddress" data-overlay-theme="a" data-theme="c" style="min-width:320px; max-width:400px;" 
					class="ui-corner-all ui-popup ui-body-c ui-overlay-shadow" aria-disabled="false" data-disabled="false" 
					data-shadow="true" data-corners="true" data-transition="none" data-position-to="window">
			
					<div data-role="content" data-theme="d" class="ui-corner-bottom ui-content ui-body-d" role="main">
						<center>
							<p>	Great! 
								<br/><br/> 
								<span>That address has been removed!</span>
							</p>
							<a 	href="#" data-role="button" data-inline="true" data-rel="back" data-theme="c" data-corners="true" 
								data-shadow="true" data-iconshadow="true" data-wrapperels="span" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-up-c">
								<span class="ui-btn-inner ui-btn-corner-all">
									<span class="ui-btn-text">Ok</span>
								</span>
							</a>
						</center> 
					</div>
				</div>


				<!-- VALIDATIO EMAILMSG -->
				
				<div data-role="popup" id="popupEmailAddress" data-overlay-theme="a" data-theme="c" style="min-width:320px; max-width:400px;" 
					class="ui-corner-all ui-popup ui-body-c ui-overlay-shadow" aria-disabled="false" data-disabled="false" 
					data-shadow="true" data-corners="true" data-transition="none" data-position-to="window">
			
					<div data-role="content" data-theme="d" class="ui-corner-bottom ui-content ui-body-d" role="main">
						<center>
							<p>	Oh Wait! 
								<br/><br/> 
								<span>Please review the email address.</span>
							</p>
							<a 	href="#" data-role="button" data-inline="true" data-rel="back" data-theme="c" data-corners="true" 
								data-shadow="true" data-iconshadow="true" data-wrapperels="span" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-up-c">
								<span class="ui-btn-inner ui-btn-corner-all">
									<span class="ui-btn-text">Ok</span>
								</span>
							</a>
						</center> 
					</div>
				</div>


				<!-- USER UPDATED -->
				
				<div data-role="popup" id="popupUserUpdated" data-overlay-theme="a" data-theme="c" style="min-width:320px; max-width:400px;" 
					class="ui-corner-all ui-popup ui-body-c ui-overlay-shadow" aria-disabled="false" data-disabled="false" 
					data-shadow="true" data-corners="true" data-transition="none" data-position-to="window">
			
					<div data-role="content" data-theme="d" class="ui-corner-bottom ui-content ui-body-d" role="main">
						<center>
							<p>Super Easy! 
								<br/><br/> 
								<span>This account has been updated successfully!</span>
							</p>
							<a 	href="#" data-role="button" data-inline="true" data-rel="back" data-theme="c" data-corners="true" 
								data-shadow="true" data-iconshadow="true" data-wrapperels="span" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-up-c">
								<span class="ui-btn-inner ui-btn-corner-all">
									<span class="ui-btn-text">Ok</span>
								</span>
							</a>
						</center> 
					</div>
				</div>

				<!-- OVERIDE ACCOUNT -->
				<div 	data-role="popup" id="account-override-panel" data-overlay-theme="a" data-theme="c" style="min-width:320px; max-width:400px;" 
						class="ui-corner-all ui-popup ui-body-c ui-overlay-shadow" aria-disabled="false" data-disabled="false" 
						data-shadow="true" data-corners="true" data-transition="none" data-position-to="window">
					<div data-role="content" data-theme="d" class="ui-corner-bottom ui-content ui-body-d" role="main">
						<center>
							<p>
								Woah! 
								<br/>
								<span style="font-size: small">This email account has been set up previously</span>
							</p>
							<table class="ui-shadow-inset ui-corner-all ui-btn-shadow ui-body-c ui-input-has-clear" 
									 id="existing-user-accounts">
							</table>
						</center>
					</div>
				</div>


				<!-- RESET PASSWORD FAIL -->
				<div 	data-role="popup" id="errorMessage" data-overlay-theme="a" data-theme="c" style="min-width:320px; max-width:400px;" 
						class="ui-corner-all ui-popup ui-body-c ui-overlay-shadow" aria-disabled="false" data-disabled="false" 
						data-shadow="true" data-corners="true" data-transition="none" data-position-to="window">
					<div data-role="content" data-theme="d" class="ui-corner-bottom ui-content ui-body-d" role="main">
						<center>
							<p>
								Uh oh! 
								<br/>
								<span style="font-size: small">Something went wrong!<br>Contact <a href="mailto:support@showseeker.com">support@showseeker.com</a></span>
							</p>
							<a 	href="#" data-role="button" data-inline="true" data-rel="back" data-theme="c" data-corners="true" 
								data-shadow="true" data-iconshadow="true" data-wrapperels="span" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-up-c">
								<span class="ui-btn-inner ui-btn-corner-all">
									<span class="ui-btn-text">Ok</span>
								</span>
							</a>
						</center>
					</div>
				</div>

				<!-- RESET PASSWORD OK -->
				<div 	data-role="popup" id="okMessage" data-overlay-theme="a" data-theme="c" style="min-width:320px; max-width:400px;" 
						class="ui-corner-all ui-popup ui-body-c ui-overlay-shadow" aria-disabled="false" data-disabled="false" 
						data-shadow="true" data-corners="true" data-transition="none" data-position-to="window">
					<div data-role="content" data-theme="d" class="ui-corner-bottom ui-content ui-body-d" role="main">
						<center>
							<p>
								Done! 
								<br/><br/>
								<span style="font-size: small">A Reset Password notification has been sent<br/>to this email address</span>
							</p>
							<a 	href="#" data-role="button" data-inline="true" data-rel="back" data-theme="c" data-corners="true" 
								data-shadow="true" data-iconshadow="true" data-wrapperels="span" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-up-c">
								<span class="ui-btn-inner ui-btn-corner-all">
									<span class="ui-btn-text">Ok</span>
								</span>
							</a>
						</center>
					</div>
				</div>


				
				<!-- LIST OF OFFICES -->				
				<div data-role="popup" id="both" data-overlay-theme="a" data-theme="b" class="ui-content" style="min-width: 320px; max-width: 400px; height: 420px;">					

					<div data-role="content"  style="background-color:#f1f1f1; height: 83%; overflow: scroll">  
					
						<ul data-role="listview" data-divider-theme="b" data-input="#filterBasic-input"  data-filter="true"> 
							<?php 
								$mkt = '';
								while ($row = mysql_fetch_assoc($offices)): 
									if($row['market'] != $mkt){ 
										print('<li data-role="list-divider">'.$row['market'].'</li>');
										$mkt = $row['market'];
									}?>
									<li data-icon="false" id=officeId-<?php print($row['officeid'])?> class="officeName">
									<div class="checkBoxLeft" style="position: absolute; top:12px;">
										<?php if($row['officeid'] == $defaultoffice){
										print('<i class="fa fa-check-circle-o fa-lg" style="color: #999;"></i>');
										}
										else{
										print('<i class="fa fa-circle-o fa-lg" style="color: #999;"></i>');																
										}
									?>
									</div>
									<a href="#"><?php print $row['office'] ?></a>
							</li>        	
							<?php endwhile; ?> 
						</ul>
					</div>					
					
					
					<center>
						<a 	href="#" data-role="button" data-inline="true" data-rel="back" data-theme="c" data-corners="true" 
							data-shadow="true" data-iconshadow="true" data-wrapperels="span" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-up-c">
							<span class="ui-btn-inner ui-btn-corner-all">
								<span class="ui-btn-text">
									<i class="fa fa-times-circle" style="color: red;"></i>
									Close
								</span>
							</span>
						</a>
						<a 	href="#" data-role="button" data-inline="true" data-rel="back" data-theme="c" data-corners="true" id="defaultOfficeSelector"
							data-shadow="true" data-iconshadow="true" data-wrapperels="span" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-up-c">
							<span class="ui-btn-inner ui-btn-corner-all">
								<span class="ui-btn-text">
									<i style="color:green;" class="fa fa-check fa-lg"></i>
									Select
								</span>
							</span>
						</a>						
					</center> 
				</div>				
				
		    </div>

		</div>	
	
	</body>
	
	<script>
		//check if logged in
	    if (localStorage.getItem("userId") === null || localStorage.getItem("apiKey") === null) {
	        window.location.href = "../login.php?logout=true&app=admin";
	    }    
	    var userid 	= localStorage.getItem("userId");
	    var apiKey 	= localStorage.getItem("apiKey");

			    

		$(document).ready(function(){

			var url_string 	= window.location.href;
			var url 		= new URL(url_string);
			var userId 		= url.searchParams.get("id");
			userEmail 		= $('#email').val();
			
			$('#adminLogin').show();

			if(parseInt(userId) === 0){
				$('#adminLogin').hide();
			}
			
			$('#page-main-container').css({'padding':'0px'});
			
			$('#email').blur(function() {
				if($(this).val() !== userEmail){
					searchExistingAccount(<?php print $id;?>);
				}
			});

			if(parseInt(userId) > 0){
				$('#usrEmailer').show();
			}

			var officeParam 	= getUrlParameter('officeid');			
			var regionParam 	= getUrlParameter('region');
			var href 			= '/admin/';

			if(officeParam){
				href= 'office.php?officeid='+officeParam;
			}
			else if(regionParam){
				href= 'region.php?marketid='+regionParam;
			}
			
			$('#returnToPage,#navBack').prop('href',href);
		});	
					
		


	</script>	
		
	<script src="js/users/index.js?v=<?php print $uuid;?>"></script>	
	<script src="js/users/account.override.js?<?php print 'v='.uniqid();?>"></script>	
	<script src="js/users/add.js?v=<?php print $uuid;?>"></script>	
	<script src="js/users/reset.password.js?v=<?php print $uuid;?>"></script>	
	
</html>
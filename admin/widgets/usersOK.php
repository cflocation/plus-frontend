<?php

	$sql 	= 	"SELECT DISTINCT 	User.id AS userid, 
									User.firstName AS firstname, 
									User.lastName AS lastname, 
									User.apiKey AS tokenId,
									Corporation.id as corpId 
				FROM 				UserOffice 
				
				INNER JOIN 			Office 
				ON 					UserOffice.officeid = Office.id AND Office.active = 1

				INNER JOIN 			User 
				ON 					User.id = UserOffice.userId  AND User.active = 1
				
				INNER JOIN 			Corporation 
				ON 					User.corporationId = Corporation.id

				INNER JOIN 			Division 
				ON 					Corporation.id = Division.corporationid AND Division.active = 1
				
				INNER JOIN 			Market 
				ON 					Division.id = Market.divisionid AND Market.active = 1
				
				WHERE 				UserOffice.default = 1 
				AND 				Corporation.id = 46 
				AND 				User.deletedAt IS NULL 
				AND 				Market.deletedAt is NULL 
				AND 				Office.deletedAt is NULL 
				GROUP BY 			User.id ORDER BY User.firstName ASC, lastName ASC";	

	if(isset($marketid) && $marketid){
		$sql = "SELECT			Office.name,
								User.id AS userid,
								User.firstName AS firstname, 
								User.lastName AS lastname, 
								User.apiKey AS tokenId,
								Corporation.id as corpId
					FROM 		UserOffice
					
					INNER JOIN 	Office 
					ON 			UserOffice.officeid = Office.id AND Office.active = 1					
					
					INNER JOIN 	User 
					ON 			User.id = UserOffice.userId AND User.active = 1
					
					INNER JOIN 	Corporation 
					ON 			User.corporationId = Corporation.id
					
					INNER JOIN 	Market 
					ON 			Office.regionId = Market.id AND Market.active = 1
					
					WHERE 	UserOffice.default=1 
					AND 	Market.id = $marketid 
					AND 	User.deletedAt IS NULL 
					AND 	Corporation.id = 46 
					AND		Market.deletedAt is NULL
					AND		Office.deletedAt is NULL
					GROUP BY User.id
					ORDER BY User.firstName ASC, lastName ASC";
	}


	if(isset($officeId)){
		$sql = "SELECT 			Office.name AS office, 
								User.id AS userid, 
								User.firstName AS firstname, 
								User.lastName AS lastname, 
								User.apiKey AS tokenId,
								User.corporationid as corpId
				FROM 			UserOffice
				
				INNER 	JOIN 	User 
				ON 		UserOffice.userId = User.id AND User.active = 1

				INNER 	JOIN 	Office 
				ON 		Office.id = UserOffice.officeId AND Office.active = 1
		
				WHERE 			UserOffice.default=1 
				AND 			UserOffice.officeId = $officeId 
				AND 			User.corporationId = 46 
				AND 			User.deletedAt IS NULL 
				AND				Office.deletedAt is NULL
				GROUP BY 		User.id
				ORDER BY 		User.firstName ASC";
	}

    $result = mysql_query($sql);
    $cnt = mysql_num_rows($result);
?>

<div data-role="collapsible"  data-collapsed="true" id="usersWidget">

    <h3>Users (<span id="users-count"><?php print $cnt ?></span>)</h3>
    
	<div>			
		<a 	href="#" 
			data-mini="true"
			data-role="button" 
			data-inline="true"
			onclick="preDeleteUser()">
			<i style="color:red;"   class="fa fa-minus-circle fa-lg"></i>
			Delete
		</a>			
		
		<a href="user.php?id=0&corpId=<?php print $corpId; ?>&region=<?php print $marketid; ?>&officeid=<?php print $officeId ?>" rel="external" data-mini="true" data-role="button"  data-inline="true">
			<i style="color:green;" class="fa fa-plus-circle fa-lg"></i> 
			New User
		</a>
		
		<a href="#" data-mini="true" data-role="button" data-inline="true" onclick="undousersDelete();"  style="display:none;" id="user-delete-undo">
			<i style="color:green;" class="fa fa-undo fa-lg"></i> UNDO
		</a>
		
		<span id="confirmation-msg-area" style="background-color:yellow;font-weight:500;font-size:11pt;font-style:italic;display:none;padding:9px;border-radius:12px;"></span>
	</div>

	<br/>
		
	<ul id="usersListWidget" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Filter Users …" data-dom-cache="false" data-cache="false"> 
		<?php 
		while ($row = mysql_fetch_assoc($result)): ?>
			<li id=<?php print($row['userid'])?> class="allUsersList">
				<div class="checkBoxLefty" >
					<i class="fa fa-square-o fa-lg" style="color: #999;" onmousemove="cursor:'pointer';"></i>
				</div>
				<a style="position:relative; left:40px;" rel="external" href="user.php?id=<?php print $row['userid'];?>&corpId=<?php print $corpId;?>&region=<?php print $marketid;?>&officeid=<?php print $officeId;?>">
					<?php print ucwords($row['firstname']) ?> <?php print ucwords($row['lastname']) ?>
				</a>
			</li>        	
		<?php endwhile; ?> 
	</ul>	
	    
</div>


<!-- DELETE USER POP UP -->
<div data-role="popup" id="popupDelete" data-overlay-theme="a" data-theme="c" style="min-width:320px; max-width:400px;" 
	class="ui-corner-all ui-popup ui-body-c ui-overlay-shadow" aria-disabled="false" data-disabled="false" 
	data-shadow="true" data-corners="true" data-transition="none" data-position-to="window">

	<div data-role="header" data-theme="a" class="ui-corner-top ui-header ui-bar-a" role="banner">
		<h1 class="ui-title" role="heading" aria-level="1">Delete Users?</h1>
	</div>

	<div data-role="content" data-theme="d" class="ui-corner-bottom ui-content ui-body-d" role="main">
		<h3 class="ui-title">
			Do you want to delete 
			<span id="deletedUsersNum"></span>
			User(s)?</h3>
		<center>
			<a 	href="#" data-role="button" data-inline="true" data-rel="back" data-theme="c" data-corners="true" 
				data-shadow="true" data-iconshadow="true" data-wrapperels="span" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-up-c">
				<span class="ui-btn-inner ui-btn-corner-all">
					<span class="ui-btn-text">Cancel</span>
				</span>
			</a>
			<a 	href="#" onclick="javascript:usersDelete()" data-role="button" data-inline="true" data-rel="back" data-transition="flow" 
				data-theme="b" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-inline ui-btn-up-b">
				<span class="ui-btn-inner ui-btn-corner-all">
					<span class="ui-btn-text">Delete</span>
				</span>
			</a> 
		</center> 
	</div>
</div>


<!-- DELETE USER POP UP -->
<div data-role="popup" id="nonSelectedUsers" data-overlay-theme="a" data-theme="c" style="min-width:320px; max-width:400px;" 
	class="ui-corner-all ui-popup ui-body-c ui-overlay-shadow" aria-disabled="false" data-disabled="false" 
	data-shadow="true" data-corners="true" data-transition="none" data-position-to="window">
	<div data-role="content" data-theme="d" class="ui-corner-bottom ui-content ui-body-d" role="main">
		<center>
			<p>	
				Oops! 
				<br/><br/>
				Please select at least one account to delete.
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


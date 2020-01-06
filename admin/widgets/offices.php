<?php

	$RegionId = "";

    $sql = "SELECT 		Office.id AS officeid, 
                        Office.name AS office, Market.name AS market,
                        Address.address, 
                        Address.address2, 
                        Address.stateId AS statesid, 
                        Address.city, 
                        Address.zip as zipcode
		    FROM 		Market 
			INNER JOIN 	Office 
			ON          Market.id = Office.regionId 
            INNER JOIN  OfficeAddress 
            ON 			Office.id = OfficeAddress.officeId
            INNER JOIN 	Address 
            ON 			OfficeAddress.addressId = Address.id
			WHERE 		Market.deletedAt IS NULL 
			AND 		Office.active = 1 
    		AND 		Office.deletedAt IS NULL 
			AND 		Office.id not IN (415,416)
    		AND 		Market.corporationId = 46
			ORDER BY 	Office.name";

	if(isset($marketid) && $marketid){
		$sql = "SELECT T.*, (SELECT 	Count(User.id) as total 
							FROM 		User 
	             
		                    INNER JOIN 	UserOffice 
		                    On 			User.id = UserOffice.userId 
		                     
		                    INNER JOIN Office 
		                    On 			UserOffice.officeId = Office.id
		                     
		                    WHERE 		Office.id = T.officeid
		                    AND 		Office.deletedAt is NULL
		                    AND 		User.deletedAt is NUll) as offices
				FROM  	(
							SELECT 	 Office.id AS officeid, 
									    Office.name AS office, 
									    Market.name AS market,
                                        Address.address, 
                                        Address.address2, 
                                        Address.stateId AS statesid, 
                                        Address.city, 
                                        Address.zip as zipcode
							FROM 	    Office
					        INNER JOIN 	Market 
					        ON 			Office.regionId = Market.id
                            INNER JOIN  OfficeAddress 
                            ON 			Office.id = OfficeAddress.officeId
                            INNER JOIN 	Address 
                            ON 			OfficeAddress.addressId = Address.id
					        WHERE		Market.deletedAt IS NULL 
					        AND 		Office.active = 1 
					        AND 		Office.deletedAt IS NULL 
					        AND 		Office.id not IN (415,416)
							AND 		Market.corporationId = 46
					        AND 		Office.regionId = ".$marketid.") AS T
				ORDER BY	office";
		$RegionId = $marketid;
				
	}

    $result = mysql_query($sql);
    $cnt 	= mysql_num_rows($result);
    mysql_data_seek($result, 0); 
?>


<div <?php /*if(isset($marketid) && $marketid){print 'data-collapsed="false"';}*/ ?> data-role="collapsible" id="office-container">
	<h3>Offices (<?php print $cnt ?>)</h3>

	<input type="search" id="filterOffiesInput" placeholder="Filter Offices ...">
	<ul data-role="listview" id="officesListWidget" data-inset="true">
    	<?php while ($row = mysql_fetch_assoc($result)): ?>
        <li class="allOfficesList">
	        <!-- a  href="office.php?officeid=<?php print $row['officeid']; ?>&regionid=<?php print $RegionId; ?>"  data-ajax="false"><?php print $row['office'] ?></a -->
    	    <a  href="office.php?officeid=<?php print $row['officeid'] ?>&panel=office-container"  data-ajax="false">
        	    <span class="officeName"><?php print $row['office']?></span>
                <div style="font-size: 7pt; color: #777; padding-top:4px; display:none;" class="officeAddress"  id="officeId-<?php print $row['officeid'];?>"><?php print $row['address']?></div>
            </a>

			<?php if(isset($marketid) && $marketid){?>
				<span class="ui-li-count"><?php print $row['offices'] ?></span>
			<?php } else{?>
				<span class="ui-li-count"><?php print $row['market'] ?></span>
			<?php }?>		        
        </li>        	
        <?php endwhile; ?> 
    </ul>
</div>

<script>    
    
    function find_duplicate_office(arra1) {
        var object = {};
        var result = [];
        arra1.forEach(function (item) {
          if(!object[item])
              object[item] = 0;
            object[item] += 1;
        })

        for (var prop in object) {
           if(object[prop] >= 2) {
               result.push(prop);
           }
        }

        return result;
    }
    
    var officeNames = [];
    
    $('.officeName').each(function(i,item){
        officeNames.push($(this).text())
    });

    var duplicated = find_duplicate_office(officeNames);

    $('li a span').each(function(i,item){
        if(duplicated.indexOf($(this).text()) !== -1){
            $(this).siblings('div').show();
        }
    });

</script>

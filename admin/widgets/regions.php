<?php
    $corporationid = (isset($_GET['corporationid']) && intval($_GET['corporationid']) > 0)?$_GET['corporationid']:false;

	//MARKETS 210 & 211 ARE HIDDEN ON PURPOSE MAY 25 2017
    $sql 	= "	SELECT   	Market.name, 
    						Market.id 
				FROM     	Market 
				WHERE    	Market.deletedAt IS NULL 
				AND      	Market.corporationId = 46 
				AND      	Market.id NOT IN (210,211,222)
				ORDER BY 	name";

    $result = mysql_query($sql);
    $cnt 	= mysql_num_rows($result);
    mysql_data_seek($result, 0); 
?>

<div data-role="collapsible" data-collapsed="true">
    <h3>Regions (<?php print $cnt ?>)</h3>
    <ul data-role="listview">
        <?php while ($row = mysql_fetch_assoc($result)): ?>
            <li>
            	<a href="region.php?marketid=<?php print $row['id'] ?>"  data-ajax="false"><?php print $row['name'] ?></a>
            </li>            
        <?php endwhile; ?> 
    </ul>
</div>
    

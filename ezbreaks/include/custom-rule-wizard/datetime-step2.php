<?php
session_start();
include_once('../../../config/database.php');
$userid = $_SESSION['userid'];
$corporationid = $_SESSION['corporationid'];
$allowedNets = getUsersAllowedNetworks($userid);

$sql = " SELECT tn.networkid AS id, tn.callsign, tn.name, logos.filename, nm.charter_mapping AS charter_callsign, CONCAT('http://ww2.showseeker.com/images/_thumbnailsW/',IFNULL(logos.filename,'default.gif')) AS logofullpath
				FROM ezbreaks.breakgroups AS bg 
				INNER JOIN ezbreaks.breakgroups_items AS bgi ON bg.id = bgi.breakgroupsid
				INNER JOIN ShowSeeker.tms_networks AS tn  ON tn.networkid = bgi.tmsid
				INNER JOIN ShowSeeker.networkmapping AS nm  ON tn.networkid = nm.id
				LEFT JOIN ShowSeeker.networklogos ON networklogos.networkid = tn.networkid
				LEFT JOIN ShowSeeker.logos ON logos.id = networklogos.logoid 
				WHERE corporationid=$corporationid AND bgi.deletedat IS NULL AND bg.deletedat IS NULL
				GROUP BY tn.networkid ORDER BY tn.callsign ";
$result = mysql_query($sql);
while($row = mysql_fetch_assoc($result))
{
	if(in_array($row['id'], $allowedNets))
		$networksArr[] = $row;
}

function getUsersAllowedNetworks($userId)
{
	$sql = "SELECT pb.networkinstances FROM ShowSeeker.permissionbreakuser AS pbu INNER JOIN ShowSeeker.permissionbreaks AS pb ON pb.id=pbu.groups WHERE pbu.userid = $userId";
	$res = mysql_query($sql);
	
	if(mysql_num_rows($res) ==0) return array();
	
	$obj = mysql_fetch_object($res);
	
	if(count(explode(',',$obj->networkinstances)) == 0) return array();

	$sql = "SELECT DISTINCT breakgroups_items.tmsid FROM ezbreaks.breakgroups_items WHERE id IN ({$obj->networkinstances}) ";
	$result = mysql_query($sql);

	$networks = array();
    while($row = mysql_fetch_object($result))
    {
    	$networks[] = $row->tmsid;
    }

	return $networks;
}

?>


<p style="margin-top:5px;" id="custom-rule-wizard-task1-step2-title">Select the network(s)</p>
<ol id="selectable-task1-step2">
	<?php foreach ($networksArr as $net) { ?>
		<li class="ui-widget-content" data-networkid="<?php print $net['id']; ?>">
			<img src="<?php print $net['logofullpath']; ?>"/>
			<?php print $net['callsign']; ?>
		</li>
	<?php } ?>
</ol>
<script type="text/javascript">
$( "#selectable-task1-step2" ).selectable();
</script>
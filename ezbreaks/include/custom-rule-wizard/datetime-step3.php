<?php
session_start();
include_once('../../../config/database.php');

$userid 			= $_SESSION['userid'];
$corporationid 		= $_SESSION['corporationid'];
$networkids  		= $_GET['networkids'];
//$networkids  		= explode(',',$_GET['networkids']);
$allowedInstances 	= getUsersAllowedInstanceIds($userid);


$sql = " SELECT bgi.id, bgi.instancecode, tz.name AS timezone, tz.abbreviation AS tzabbreviation, CONCAT('http://ww2.showseeker.com/images/_thumbnailsW/',IFNULL(logos.filename,'default.gif')) AS logofullpath
		 FROM ezbreaks.breakgroups_items AS bgi
		 INNER JOIN ShowSeeker.timezones AS tz ON tz.id = bgi.timezoneid
		 LEFT JOIN ShowSeeker.networklogos ON networklogos.networkid = bgi.tmsid
		 LEFT JOIN ShowSeeker.logos ON logos.id = networklogos.logoid 
		 WHERE bgi.deletedat IS NULL AND bgi.tmsid IN( $networkids )
		 ORDER BY bgi.instancecode ";

$result = mysql_query($sql);

$data = array();
//loop over and add to list
while($row = mysql_fetch_assoc($result)) {
	if(in_array($row['id'], $allowedInstances)) 
		$data[] = $row;
}

function getUsersAllowedInstanceIds($userId)
{
	$sql = "SELECT pb.networkinstances FROM ShowSeeker.permissionbreakuser AS pbu INNER JOIN ShowSeeker.permissionbreaks AS pb ON pb.id=pbu.groups WHERE pbu.userid = $userId";
	$res = mysql_query($sql);
	
	if(mysql_num_rows($res) ==0) return array();
	
	$obj = mysql_fetch_object($res);
	$instances = explode(',',$obj->networkinstances);
	return $instances;
}

?>


<p style="margin-top:5px;" id="custom-rule-wizard-task1-step3-title">Select the network instance(s)</p>
<ol id="selectable-task1-step3">
	<?php foreach ($data as $net) { ?>
		<li class="ui-widget-content ui-selected" data-instanceid="<?php print $net['id']; ?>">
			<img src="<?php print $net['logofullpath']; ?>"/>
			<?php print $net['instancecode']; ?>
		</li>
	<?php } ?>
</ol>
<script type="text/javascript">
$( "#selectable-task1-step3" ).selectable();
</script>
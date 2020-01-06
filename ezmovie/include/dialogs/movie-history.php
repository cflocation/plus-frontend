<?php
	session_start();
	require_once "../config/logs.php";

	$rootId  = $_GET['i'];
	$sql     = "SELECT id, userId, created, updated, remarks, DATE_FORMAT(createdAt,'%m/%d/%Y %h:%i %p') AS createdAt FROM EzMovieLog WHERE rootId=$rootId ORDER BY EzMovieLog.createdAt DESC";
	$res     = mysqli_query($logDb, $sql);
	$logs    = [];
	$userIds = []; 

	while($r = mysqli_fetch_assoc($res)){
		$r['updated'] = str_replace(',', ', ', $r['updated']);
		$r['updated'] = str_replace('networkurl', 'Website', $r['updated']);
		$logs[]    = $r;
		$userIds[] = $r['userId'];
	}

	$userIds = array_unique($userIds);
	$apiKey  = md5($_SESSION['userid'].$_SESSION['tokenid']);

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "http://apidev.showseeker.com:8585/user/getuserinfo",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS =>  json_encode(array('userIds'=>$userIds)),
		CURLOPT_HTTPHEADER => array(
			"api-key: {$apiKey}",
			"cache-control: no-cache",
			"content-type: application/json",
			"user: {$_SESSION['userid']}"
	  ),
	));

	$infos = curl_exec($curl);
	$err   = curl_error($curl);
	curl_close($curl);

	$infos     = json_decode($infos)->result;
	$userInfos = [];

	foreach($infos AS $i){
		$userInfos[$i->id] = $i;
	}
?>
<div class="small-12">
	<table width="100%">
		<thead>
			<tr>
				<th width="25%">User</th>
				<th>Updates</th>
				<th width="20%">Datetime</th>
			</tr>
		</thead>
		<tbody>
			<?php if(count($logs) > 0): ?>
				<?php foreach($logs AS $log): ?>
					<tr>
						<td><?php print "{$userInfos[$log['userId']]->firstName} {$userInfos[$log['userId']]->lastName}"; ?></td>
						<td><?php print ucwords($log['updated']); ?></td>
						<td><span title="<?php print $log['createdAt']; ?>"><?php print date("m/d/Y",strtotime($log['createdAt'])); ?></span></td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr>
					<td colspan="3">No records found for this movie</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
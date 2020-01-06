<?php

	$showid = str_replace("EP","SH",$showid);

	include_once('../../config/database.php');
	
	//select our database
	mysql_select_db("On", $con);

	$sql = "SELECT 		onShowcardDescriptions.type, 
						onShowcardDescriptions.lang, 
						onShowcardDescriptions.size, 
						onShowcardDescriptions.title AS descscription, 
						onShowcardTitles.type, 
						onShowcardTitles.lang, 
						onShowcardTitles.size, 
						onShowcardTitles.title

			FROM 		onShowcardDescriptions 
			INNER JOIN 	onShowcardId 
			ON 			onShowcardDescriptions.showcardId = onShowcardId.showcardId
			
			INNER JOIN 	onShowcardTitles 
			ON 			onShowcardTitles.showcardId = onShowcardDescriptions.showcardId
			
			WHERE 		onShowcardId.TMSId = '".$showid."0000' 
			AND 		onShowcardDescriptions.size = 1000 
			AND 		onShowcardTitles.type = 'full'";

	$result = mysql_query($sql);
	$num_rows_info = mysql_num_rows($result);
	$showinfo = mysql_fetch_array($result, MYSQL_ASSOC);


	$sql = "SELECT onCelebritiesNames.first, 
	onCelebritiesNames.last, 
	onShowcardCast.characterName, 
	onCelebritiesImages.URI, 
	onCelebritiesImages.caption, 
	onCelebritiesImages.creditLine
	FROM onShowcardCast INNER JOIN onShowcardId ON onShowcardCast.showcardId = onShowcardId.showcardId
	INNER JOIN onCelebritiesNames ON onCelebritiesNames.personId = onShowcardCast.personId
	INNER JOIN onCelebritiesImages ON onCelebritiesImages.personId = onCelebritiesNames.personId
	WHERE onShowcardId.TMSId = '".$showid."0000' AND onCelebritiesImages.width = 270
	GROUP BY onShowcardCast.personId ORDER BY onCelebritiesNames.first";

	$result = mysql_query($sql);
	$num_rows = mysql_num_rows($result);
	$cast = $result;



	/*//mysql connector
	$con = mysql_connect("127.0.0.1","root","Vastcf01");
		if (!$con)
	  {
	  	die('Could not connect: ' . mysql_error());
	  }

	//select our database
	mysql_select_db("ishowseeker", $con);

	$sql = "SELECT nodefull.title, 
	content_type_show_link.field_show_url_value AS url, 
	node.title AS type, 
	content_type_shows.field_tmsid_value AS tmsid
	FROM content_field_showid INNER JOIN node nodefull ON content_field_showid.field_showid_nid = nodefull.nid
	INNER JOIN content_type_show_link ON content_field_showid.nid = content_type_show_link.nid
	INNER JOIN node ON content_type_show_link.field_link_type_nid = node.nid
	INNER JOIN content_type_shows ON content_type_shows.nid = nodefull.nid
	WHERE nodefull.type = 'shows' AND node.status = 1 AND content_type_shows.field_tmsid_value = '".$showid."'
	GROUP BY type
	";


	$result = mysql_query($sql);
	$social = $result;

	$num_rows_social = mysql_num_rows($result);*/
	$num_rows_social = 0;

?>




<?php if($num_rows_info > 0): ?>
	<p></p>
	<h1><?php print $showinfo['title']; ?></h1>
	<?php print $showinfo['descscription']; ?>
<?php endif; ?>




<?php if($num_rows > 0): ?>
	<p></p>
	<h1 id="moreinfo-cast-label" style="display: none;">Cast</h1>
	<?php 
		
				
				while($row = mysql_fetch_array($cast)): 		
					$p = 'http://50.57.74.41/photos/celebs/'.$row['URI'];
					$ch 	= curl_init($p);
					curl_setopt($ch, CURLOPT_NOBODY, true);
					curl_exec($ch);
					$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					if($retcode == 200){	?>

						<div class="actorwrapper">
							<img width='135' src="http://50.57.74.41/photos/celebs/<?php print $row['URI']; ?>" class="moreinfo-img"><br>
							<div class="actorname"><?php print $row['first']; ?> <?php print $row['last']; ?></div>
						</div>

					<?php 
					}
				endwhile;
		
			curl_close($ch); ?>
<?php endif; ?>





<?php if($num_rows_social > 0): ?>
	<br style="clear:both;"><p></p>
	<h1>Social</h1>
	<?php while($row = mysql_fetch_array($social)): ?>
		<a target="_blank" href="<?php print $row['url'];?>"><img border=0 width="50" src="<?php print getSocialIcon($row['type']); ?>"></a>
	<?php endwhile; ?>
<?php endif; ?>





<?php
	function getSocialIcon($i){
		switch ($i) {
	    case 'Facebook':
			return '/i/social/facebook.png';
	        break;
	    case 'Twitter':
	       	return '/i/social/twitter.png';
	       	break;
	    case 'YouTube':
	       	return '/i/social/youtube.png';
	    case 'Logo':
	   		return 'db_photos/sportslogos/';
	       	break;
	       	
		}
	}
?>

<script>
//$(".moreinfo-img").error(function () { 
//    $(this).hide();
//    $(this).parent('.actorwrapper').hide();
    var imgs = $('.actorwrapper :visible');
	if(imgs.length > 0){
		$('#moreinfo-cast-label').show();
		//$('#moreinfo-cast-label').hide();
		//$('#dialog-window').parent('div.ui-dialog, div.ui-dialog-content').css({'width':'450px','height':'450px'});
		//$('#dialog-window').css({'width':'430px','height':'410px'});
	}
//});	
</script>



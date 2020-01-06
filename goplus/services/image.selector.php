<?php
	include_once('../../config/database.php');
	
	mysql_select_db("On", $con);
	
	$id = $_GET['id'];

	$sql = "SELECT 			onGalleryMedia.width, 
							onGalleryMedia.height, 
							onGalleryMedia.title, 
							onGalleryMedia.URI, 
							onGalleryMedia.category 

			FROM 			onGalleryMedia 

			INNER JOIN 		onGalleryIds 
			ON 				onGalleryMedia.mediaId = onGalleryIds.mediaId 
			
			WHERE 			onGalleryIds.id = ('".$id."') 

			ORDER BY 		onGalleryMedia.width";

	$result = mysql_query($sql);

	$re = array();


	while ($row = mysql_fetch_assoc($result)) {

		$base = 'https://showseeker.s3.amazonaws.com/on/';
		$path = setpath($row['category']);
		$uri = $row['URI'];
		$p = $base.$path.$uri;
		$path = $path.$uri;

		$ch = curl_init($p);
		
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);
		$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if($retcode == 200){

			$name = $row['title'].' '.$row['width'].'x'.$row['height'];

			$s = preg_replace("/[^a-z0-9\s]/i", "", $name);
			$s = str_replace(' ', '_', $s);



			$array = array(
			    "url" => $p,
			    "title" => $row['title'],
			    "height" => $row['height'],
			    "width" => $row['width'],
			    "path" => $path,
			    "name" => $s

			);

			array_push($re, $array);
			
		}

		curl_close($ch);

		
	}


	function setpath($i){

		switch ($i) {
	    case 'Poster Art':
			return 'photos/movieposters/';
	        break;
	    case 'Box Art':
	       	return 'photos/dvdboxart/';
	       	break;
	    case 'Banner':
	       	return 'photos/tvbanners/';
	    case 'Logo':
	   		return 'db_photos/sportslogos/';
	       	break;
	       	
		}
	}

?>

<style type="text/css">
	body{
		font-size: 12px;
		font-family: "Trebuchet MS", "Helvetica", "Arial",  "Verdana", "sans-serif";
	}

	.image{
		padding: 10px;
		background-color: #eaeaea;
		margin: 5px;
		-moz-border-radius: 20px;
    	-webkit-border-radius: 20px;
    	-khtml-border-radius: 20px;
    	border-radius: 20px;
	}

</style>


<?php if(count($re) > 0): ?>
	<?php foreach ($re as &$value):?>
		<center>
			<div><img class="image" src="<?php print $value['url']; ?>">
				<br>
				Size: <?php print $value['width']; ?> X <?php print $value['height']; ?>
				<br>
				<a href="fdownload.php?filename=https://showseeker.s3.amazonaws.com/on/<?php print $value['path']; ?>">Download</a>
				<br><br>
			</div>
		</center>
	<?php endforeach;?>
<?php endif; ?>

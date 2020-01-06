<?php
	include_once('../../../config/database.php');
	
	$id = $_GET['id'];

	$sql = "SELECT 			users.*,
							users.tokenid as tk, 
							proposals.*,
							regions.iseeker
							
			FROM	 		users 
				
			INNER JOIN 		proposals 
			ON 				users.id = proposals.userid
				
			INNER JOIN 		officedefaults 
			ON 				officedefaults.userid = users.id
			
			INNER JOIN 		offices 
			ON 				officedefaults.officeid = offices.id
			
			INNER JOIN 		regions 
			ON 				offices.regionid = regions.id
			
			WHERE	 		proposals.id = {$id}
				
			ORDER BY 		proposals.createdat DESC
			LIMIT 1";

	$result 		= mysql_query($sql);
 	$array 			= mysql_fetch_assoc($result);
	$proposal 		= json_decode($array['proposal']);
	$ids  			= array();
	$nets 			= array();
	$titles  		= array();
	
	
	foreach ($proposal as &$value) {

		$idarr = explode(",", $value->showid);
		
		array_push($nets, array(rtrim($value->showid,' ,'), $value->stationnum));
		
		foreach ($idarr as &$invalue) {
			$i = "'".trim($invalue)."0000'";
			array_push($ids, $i);
		}
		
		$lineTitle 	= rtrim($value->title,' ,');
		$titlearr 	= explode(",", $lineTitle);

		foreach ($titlearr as &$innervalue) {
			if($innervalue != 'Various' && strlen($innervalue) > 0){
				$j = "'".trim(str_replace("'","''",$innervalue))."'";
				array_push($titles, $j);
			}
		}
		
	}


	// get vieo links
	$uniqueIds 		= array_unique($ids);
	$uniqueTitles 	= array_unique($titles);
	$matches 		= implode(',', $uniqueIds);

	asort($uniqueTitles);
	
	$proposaltitles = implode(',', $uniqueTitles);

	//connect to the ON database
	//mysql_select_db("On", $con);


	$sql = "SELECT 			onGalleryMedia.lastModified,
							SUBSTRING(onGalleryIds.id, 1, 2) AS id, 								
							onGalleryIds.type, 
							onGalleryMedia.title,
							onGalleryIds.id AS fid,
							onGalleryIds.mediaId,
							onGalleryMedia.width, 
							onGalleryMedia.height, 
							onGalleryMedia.category, 
							onGalleryMedia.URI
							
			FROM 			onGalleryMedia 
			
			INNER JOIN 		onGalleryIds 
			ON 				onGalleryMedia.mediaId = onGalleryIds.mediaId
			
			WHERE 			onGalleryIds.type='TMSId' 
			
			AND 			onGalleryIds.id IN (".$matches.") 
			AND 			title IN (".$proposaltitles.") 				
			AND 			onGalleryMedia.height = 360 
			AND 			onGalleryMedia.width = 240
			ORDER BY 		title asc, RIGHT(URI,6) asc, onGalleryMedia.lastModified DESC";



	$con = mysql_connect("10.5.10.110","vastsupport1","cP7qRiSvaR2M");
		mysql_select_db("Programs", $con);
		mysql_query("SET NAMES 'utf8'", $con);
		mysql_query("SET CHARACTER_SET 'utf8'", $con);


		asort($uniqueTitles);
		//$uniqueTitles = array_map('mysql_real_escape_string',$uniqueTitles);
		$proposaltitles = implode(',', $uniqueTitles);
	

		
		// looking for images
		$sql = "SELECT 	* FROM (	
				 SELECT 							
							SUBSTRING(GalleryTVBanner.TMSid, 1, 2) AS id,
							GalleryTVBanner.TMSid as fid,
							GalleryTVBanner.width, 
							GalleryTVBanner.height, 
							GalleryTVBanner.category, 
							GalleryTVBanner.URI, 
							ProgramTitle.title,
							ProgramRootid.connectorid
				FROM GalleryTVBanner
				INNER JOIN ProgramRootid ON GalleryTVBanner.rootId = ProgramRootid.rootid
				INNER JOIN ProgramTitle ON ProgramTitle.connectorid = ProgramRootid.connectorid
				WHERE ProgramRootid.connectorid IN  (".$matches.")
				AND ProgramTitle.title IN (".$proposaltitles.") 
				AND ProgramTitle.type = 'full'
				and GalleryTVBanner.width = '240' 
				and GalleryTVBanner.height = '360'
				and GalleryTVBanner.process = '1'
				AND  GalleryTVBanner.action <>  'delete'
				order by GalleryTVBanner.lastModified ASC)  AS A
				GROUP BY A.connectorid";




	$result = mysql_query($sql);
	$movies = array();
	$tv 	= array();	
	

	$arrayofTitles = array();	


	while ($row = mysql_fetch_assoc($result)) {

		if(array_search($row['title'],$arrayofTitles) === false){

			$base 	= 'https://showseeker.s3.amazonaws.com/on/';
			$path 	= setpath($row['category']);
			$uri 	= $row['URI'];
			$p 		= $base.$path.$uri;
			$ch 	= curl_init($p);
			
			curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_exec($ch);
			$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
			if($retcode == 200){
				array_push($arrayofTitles, $row['title']);			
				$array = array(
				    "url" => $p,
				    "title" => $row['title'],
				    "showid" => $row['fid']
				);

				if($row['id'] == 'MV'){
					array_push($movies, $array);
				}

				if($row['id'] == 'SH'){
					array_push($tv, $array);
				}
			}
			curl_close($ch);
		}
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
	.image{
		float: left;
		padding: 10px;
		background-color: white;
		margin: 5px;
		-moz-border-radius: 4px;
    	-webkit-border-radius: 4px;
    	-khtml-border-radius: 4px;
    	border-radius: 4px;
	}

</style>


	<?php if(count($tv) > 0): ?>
		<h1>TV Shows</h1>
		<?php foreach ($tv as &$value):?>
			<div class="image">
				<img width="150" src="<?php print $value['url']; ?>">
				<center>
					<!-- button onclick="openImageSelector('<?php print $value['showid']; ?>')">
						View Sizes
					</button -->
				</center>
			</div>
		<?php endforeach;?>
	<?php endif; ?>


<br style="clear:both;">

<?php if(count($movies) > 0): ?>
	<h1>Movies</h1>
	<?php foreach ($movies as &$value):?>
		<div class="image"><img width="150" src="<?php print $value['url']; ?>">
			<center>
				<!-- button onclick="openImageSelector('<?php print $value['showid']; ?>')">
					View Sizes
				</button -->
			</center>
		</div>
	<?php endforeach;?>
<?php endif; ?>

<script>
	$("button").button();
</script>




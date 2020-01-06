<?php
// ini_set("display_startup_errors",1);
// ini_set("display_errors",1);
// error_reporting(E_ALL);
session_start();

define("BG_BASE_DIR",      "/var/www/html/www.showseeker.com/ezshows/images/");
define("GENRE_BG_BASE_DIR","/var/www/html/www.showseeker.com/ezshows/genre/");
define("FONTS_DIR","/var/www/html/www.showseeker.com/ezshows/fonts/");
define("TMP_IMG_DIR","/var/www/html/www.showseeker.com/ezshows/tmp/");
define("TMP_IMG_URL","https://plus.showseeker.com/ezshows/tmp/");

include_once('config/mysqli.php');
require_once('services/simple_html_dom.php');
include_once('services/imagesfromlinks.php');


function GetAvailableGenres(){
	$ret       = array();
	$genreDirs = glob(GENRE_BG_BASE_DIR."*",GLOB_ONLYDIR);
	
	foreach ($genreDirs as $g) {
		$name   = ucwords(str_replace('-',' ',basename($g)));
		$images = glob("{$g}/*.png");
		$ret[]  = array(
					"name"=>$name,
					"genreDir"=>$g,
					"images"=>$images,
				   );
	}	

	return $ret;
}

function filterUnavailableGenre($genre,$availGenres){
	$ret = array();
	foreach($availGenres as $ag){
		if(in_array(strtolower($ag['name']), $genre)){
			$ret[] = strtolower($ag['name']);
		}
	}
	return $ret;
}

//GET data passed from the url
$tmsId = (isset($_GET['id'])) ? $_GET['id'] : false;
$title = (isset($_GET['title'])) ? trim($_GET['title']) : '';
$genre = (isset($_GET['genre'])) ? trim($_GET['genre']) : '';
$genre = explode(',',str_replace('-', ' ', $genre));

$availGenres = GetAvailableGenres(); //Get the list of avaialble genres by checking the directory
$genre       = filterUnavailableGenre($genre,$availGenres); //remove genres passed in the url that do not have images..
$webImages   = getImagesFromLinks($tmsId);

function generateTitleCover($tmsid, $showTitle,$imagewidth=240, $imageheight=360, $originalImage, $fontstyle){
	$dir  = html_entity_decode($showTitle).' ';
	$sig  = wordwrap($dir, 10, "<br />", true);
	$text = str_replace('<br />', "\n", $sig);  //Just wrap text by newlines
	
	$randomNumber = rand(1, 4);
	
	$im = imagecreatefrompng($originalImage);
	imagesavealpha($im, true); // important to keep the png's transparency 
	
	if(!$im) {
		die("im is null");
	}

	switch ($fontstyle) {
	    case "1":
			$fontfile = FONTS_DIR.'Ubuntu-Title.ttf';
			$fontsize = 26;
			$fontcolor = imagecolorallocate($im, 255, 64, 35);
	        break;

	    case "2":
			$fontfile = FONTS_DIR.'Montserrat-Bold.ttf';
			$fontsize = 28;
			$fontcolor = imagecolorallocate($im, 0, 0, 0);
	        break;

	    case "3":
			$fontfile = FONTS_DIR.'Sabandija-font-ffp.otf';
			$fontsize = 30 ;
	 		$fontcolor = imagecolorallocate($im, 0, 0, 0);
	       break;

	    case "4":
			$fontfile = FONTS_DIR.'crkdwno2.ttf';
			$fontsize = 28;
	 		$fontcolor = imagecolorallocate($im, 255, 255, 255);
	       break;

	    case "5":
			//best one so far
			$fontfile = FONTS_DIR.'Exo-ExtraBoldItalic.ttf';
			$fontsize = 28;
	  		$fontcolor = imagecolorallocate($im, 0, 0, 0);
	      break;

   case "6":
		$fontsize = 30;
		$fontfile = '/var/www/html/www.showseeker.com/ezshows/fonts/AvenirLTStd-Black.otf';
  		$fontcolor = imagecolorallocate($im, 0, 0, 0);
		$overlay_pic = imagecreatefrompng("/var/www/html/www.showseeker.com/ezshows/images/overlay2.png");
      break;

    case "7":
		$fontsize = 30;
		$fontfile = '/var/www/html/www.showseeker.com/ezshows/fonts/AvenirLTStd-Black.otf';
  		$fontcolor = imagecolorallocate($im, 255, 255, 255);
		$overlay_pic = imagecreatefrompng("/var/www/html/www.showseeker.com/ezshows/images/overlay2.png");
      break;

    case "8":
		$fontsize = 32;
		$fontfile = '/var/www/html/www.showseeker.com/ezshows/fonts/AvenirNextLTPro-Demi.otf';
  		$fontcolor = imagecolorallocate($im, 255, 255, 255);
		$overlay_pic = imagecreatefrompng("/var/www/html/www.showseeker.com/ezshows/images/overlay2.png");
      break;

    case "9":
		$fontsize = 32;
		$fontfile = '/var/www/html/www.showseeker.com/ezshows/fonts/AvenirNextLTPro-Demi.otf';
  		$fontcolor = imagecolorallocate($im, 255, 255, 255);
		$overlay_pic = imagecreatefrompng("/var/www/html/www.showseeker.com/ezshows/images/overlay2.png");
      break;

	    case "10":
			$fontsize = 26;
			$fontfile = FONTS_DIR.'pirulen.ttf';
	  		$fontcolor = imagecolorallocate($im, 0, 0, 0);
	      break;
	}

	$black  = imagecolorallocate($im, 255, 255, 255);
	$width  = 36; // the width of the image
	$height = 36; // the height of the image
	$font   = 4; // font size
	$digit  = 4; // digit
	$leftTextPos = 25 - (strlen($digit)*3);
	$unique = rand();

	$newImageName = "show_".$tmsid. "_".$unique. ".png";	
	$outputImage  = TMP_IMG_DIR.$newImageName;
	
	imagecopy($im, $overlay_pic, 0, 0, 0, 0, 240, 360);

	//new code starts 
	$dimensions = imagettfbbox($fontsize, 0, $fontfile, $showTitle);
	$margin     = 10;
	$text       = explode("\n", wordwrap(ucwords($text), $fontsize)); // <-- you can change this number
	$delta_y    = 0;
	$y          = (imagesy($im) - (($dimensions[1] - $dimensions[7]) + $margin)*count($text)) / 3.1; //Centering y

	$cnt = 0;
	foreach($text as $line) {
		$dimensions = imagettfbbox($fontsize, 0, $fontfile, $line);
		$delta_y    =  $delta_y + ($dimensions[1] - $dimensions[7]) + $margin;
		$x          = imagesx($im) / 2 - ($dimensions[4] - $dimensions[6]) / 2; //centering x:
	    
	    if($cnt > 0)
	    	$y = $y+20;
	    imagettftext($im, $fontsize, 0, $x, $y + $delta_y, $fontcolor, $fontfile, $line);
		$cnt++;
	}

	//new code ends

	imagepng($im, $outputImage, 2);
	imagedestroy($im);

	return $newImageName;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>ShowSeeker Plus - Upload Images</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="cache-control" content="no-cache"> <!-- tells browser not to cache -->
<meta http-equiv="expires" content="0"> <!-- says that the cache expires 'now' -->
<meta http-equiv="pragma" content="no-cache"> <!-- says not to use cached stuff, if there is any -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<link rel="stylesheet" href="/inc/foundation/css/normalize.css">
<link rel="stylesheet" href="/inc/foundation/css/foundation.css">
<script src="/inc/foundation/js/vendor/modernizr.js"></script>
<script type='text/javascript'>
	function showDiv() {
		if (document.getElementById('hiddenDiv').style.display == 'block') {
			document.getElementById('hiddenDiv').style.display = 'none';
		} else {
			document.getElementById('hiddenDiv').style.display = 'block';
		}
	}
</script>
<style type="text/css">
	.selected-image{
		border:5px solid #43ac6a;
	}
</style>
</head>
<br />
<center>
	<table width="85%">
		<tr>
			<td>
				<input type="button" class="button tiny" name="answer" value="Adjust Genre" onclick="showDiv()" />
			</td>
			<td colspan="2">
				<h3>Show ID: <?php print $tmsId;?> - Genre: <?php echo ucwords(implode(', ',$genre)); ?></h3>
			</td>
		</tr>
	</table>
</center>

<div id="hiddenDiv" class="row" style="display:none;" class="answer_list" >
	<div class="small-12 small-centered columns">
		<a class="button tiny success" href="upload.php?id=<?php echo $tmsId ?>&title=<?php echo $title ?>&genre=generic">Generic</a>
		<?php foreach($availGenres as $g): ?>
			<a class="button tiny" href="upload.php?id=<?php echo $tmsId ?>&title=<?php echo $title ?>&genre=<?php echo strtolower($g['name']); ?>"><?php echo $g['name']; ?></a>
		<?php endforeach; ?>
	</div>
</div>

<form action="processcover.php" method="post" enctype="multipart/form-data" data-abide>
	<div class="row">
		<center>
			<table width="85%">
				<tr>
					<td>
						<center>
							<input type="file" name="fileToUpload" id="fileToUpload" required>
								<small class="error">File is required, and must be an image.</small>
						</center>
					</td>
					<td>
						<center>
							<input type="submit" value="Upload Image" name="submit" class="button tiny">
						</center>
					</td>
				</tr>
			</table>
		</center>
		<input type="hidden" value="<?php print $tmsId;?>" name="tmsId">	
	</div>
</form>

<form action="processcover.php" method="post"  data-abide>
	<center>
		<table width="80%">
			<tr>
				<td colspan='5'>
					<input type="submit" value="Use Image" name="submit" class="button tiny success">
				</td>
			</tr>

			<?php if(count($webImages) > 0): ?>
				<tr>
					<?php foreach($webImages as $i=>$wi): ?>
						<td width="20%" class="text-center">
							<label>
								<input type="radio" name="image" value="<?php print $wi; ?>" id="image0<?php print $i; ?>" required> 
								Use This Image
								<img src="<?php print TMP_IMG_URL.$wi; ?>"/>
							</label>
						</td>
					<?php endforeach; ?>
				</tr>
			<?php endif; ?>

			<?php foreach($genre as $iGenre): ?>
				<tr>
					<td width="20%" class="text-center">
						<?php
							$img1 = GENRE_BG_BASE_DIR.$iGenre.'/'.$iGenre.'-image1.png';
							$newImageName = generateTitleCover($tmsId, $title, 240, 360, $img1,8);
							$uri = TMP_IMG_URL.$newImageName;
						?>
						<label>
							<input type="radio" name="image" value="<?php print $newImageName; ?>" id="image1" required> 
							Use This Image
							<img src="<?php print $uri; ?>"/>
						</label>
					</td>
					<td width="20%" class="text-center">
						<?php
							$img2 = GENRE_BG_BASE_DIR.$iGenre.'/'.$iGenre.'-image2.png';
							$newImageName = generateTitleCover($tmsId, $title, 240, 360, $img2,9);
							$uri = TMP_IMG_URL.$newImageName;
						?>
						<label>
							<input type="radio" name="image" value="<?php print $newImageName; ?>" id="image2" required>
							Use This Image
							<img src="<?php print $uri; ?>"/>
						</label>
					</td>
					<td width="20%" class="text-center">
						<?php
							$img3 = GENRE_BG_BASE_DIR.$iGenre.'/'.$iGenre.'-image3.png';
							$newImageName = generateTitleCover($tmsId, $title, 240, 360, $img3,8);
							$uri = TMP_IMG_URL.$newImageName;
						?>
						<label>
							<input type="radio" name="image" value="<?php print $newImageName; ?>" id="image3" required>
							Use This Image
							<img src="<?php print $uri; ?>"/>
						</label>
					</td>
					<td width="20%" class="text-center">
						<?php
							$img4 = GENRE_BG_BASE_DIR.$iGenre.'/'.$iGenre.'-image4.png';
							$newImageName = generateTitleCover($tmsId, $title, 240, 360, $img4,9);
							$uri = TMP_IMG_URL.$newImageName;
						?>
						<label>
							<input type="radio" name="image" value="<?php print $newImageName; ?>" id="image4" required>
							Use This Image
							<img src="<?php print $uri; ?>"/>
						</label>
					</td>
					<td width="20%" class="text-center">
						<?php
							$img5 = GENRE_BG_BASE_DIR.$iGenre.'/'.$iGenre.'-image5.png';
							$newImageName = generateTitleCover($tmsId, $title, 240, 360, $img5,8);
							$uri = TMP_IMG_URL.$newImageName;
						?>
						<label>
							<input type="radio" name="image" value="<?php print $newImageName; ?>" id="image5" required>
							Use This Image
							<img src="<?php print $uri; ?>"/>
						</label>
						<input type="hidden" value="<?php print $tmsId;?>" name="tmsId"/>	
					</td>
				</tr>

			<?php endforeach; ?>
	

		</table>
	</center>
	<input type="hidden" value="<?php print $tmsId;?>" name="tmsId"/>	
</form>
<script src="https://www.showseeker.com/inc/foundation/js/foundation.min.js"></script>
<script>
	$(document).foundation();
	$(function(){
		$("input[name='image']").change(function(){
			$('img.selected-image').removeClass('selected-image');
			$(this).parent().children('img').first().addClass('selected-image');
		});
	});
</script>
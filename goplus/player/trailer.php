<?php 
	$url 	= $_GET["url"];
	
	if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad')){
	  	header('Location: '.$url);
		exit();
	}

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_exec($ch);
	$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if($retcode == 200){
		$file = $url;
		$img = 1;
	}
	curl_close($ch);
?>

<html>
	<head>
		<style type="text/css">
			h2{
				color: white;
				font-family: Arial;
				font-weight: bold;
			}
			.bdr{
				border: 20px solid #333333;
			}
			.lnk{
				text-decoration: none;
				color: white;
				font-family: Arial;
				font-weight: bold;
			}
		</style>
	
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<link href="https://showseeker.s3.amazonaws.com/public-site/assets/favicon.png" name="favicon" rel="shortcut icon" type="image/png">
		<title>Trailers | ShowSeeker</title>
	</head>

	<body bgcolor="#000000">
		<br>
		<center>
			<p style="width: 600px">
				<video
					src='<?php print($url);?>'
					height='400'
					width='100%'
					preload='auto'
				    autoplay="yes"
					controls="true"
					mediaelement>
				</video>
			</p>
		</center>
	</body>
</html>
<?php
	session_start();
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);
?>
<!doctype html>
<head> 
<title>ShowSeeker - EzGrids</title>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<link rel="stylesheet" href="css/normalize.css">
<link rel="stylesheet" href="css/foundation.css">
<link rel="stylesheet" href="css/styles2.css" />
<link rel="stylesheet" href="css/font-awesome.css">

<script type="text/javascript" src="js/shadowbox.js"></script>

<script type="text/javascript">
	var options = {
	overlayColor: '#996655'
	overlayOpacity: 0.5
	}; 

	Shadowbox.init(options);
</script>	

<script src="js/vendor/modernizr.js"></script>


<style type="text/css">
body {
	font-family: "Trebuchet MS", "Helvetica", "Arial",  "Verdana", "sans-serif";
}

a {
	color: #184a74;
	text-decoration: none;
	font-weight: bold;
}
small {
	color: #9e9f9f;
}
.container {
	min-height: 300px;
	width: 80%;
	margin: 10px auto;
	position: relative;
	text-align:center;
	padding:0;
}

.block {
	height: 320px;
	width: 400px;
	display:inline-block;
	margin:10px;
	padding: 20px;
	background: #eeeeee;
}
.rounded-corners {
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	-khtml-border-radius: 10px;
	border-radius: 10px;
	border: 4px solid #c0c0c0;
}

.wrap{
	border: 3px solid #d8d8d8;
}

</style>
</head>

<br />
<div class="row">
  <div class="large-12 columns"><center><img src="img/logo.png"></center></div>
</div>

<div id="main" class="container">
	<div class="row">
	  <div class="large-12 columns"><br /><center><h3><strong><em>E-z Grids </em>&#0153; </strong></h3></center></div>
	</div>

	<div class="row">
	  <div class="large-12 columns"><h6 class="subheader"><center><small>Please sign in with your showseeker username and password.</small></center></h6><br /></div>
	</div>
	<form>
		<div class="row">
		  <div class="small-5 columns"><div id="ftext">email:</div></div>
		  <div class="small-7 columns"><input type="text"  name="email" id="email" class="finput"></div>
		</div>

		<div class="row">
		  <div class="small-5 columns"><div id="ftext">password:</div></div>
		  <div class="small-7 columns"><input type="password"  name="password" id="password" class="finput"></div>
		</div>		

		<div class="row">
		  <div class="small-12 columns"><center><input id="ftextsm" type="submit" value="Access Grids"></center> </div>
		</div>
	</form>
</div>

<div id="contact" style="display:none">
		<div class="row">
	  <div class="large-12 columns"><br /><center><h3><strong>Contact Us</strong></h3></center></div>
	</div>

	
	<center>

			For Technical Support (difficulty logging in or other technical issues): 
			<br><a href="mailto:help@showseeker.com">help@showseeker.com</a><br><br>


			For assistance in using ShowSeeker, refer to the User Guides or FAQ's. If your answer is not found:
			<br><a href="mailto:support@showseeker.com">support@showseeker.com</a><br><br>


			For Suggestions on how we may improve our product:
			<br><a href="mailto:suggestions@showseeker.com">suggestions@showseeker.com</a><br><br>

			
			To submit Success Stories:
			<br><a href="mailto:wins@showseeker.com">wins@showseeker.com</a><br><br>

			If you have specific questions not covered above, call us at: 866-980-8278 

		<p>
			<i class="fa fa-arrow-circle-left"></i> <a href="javascript:back();">Back</a>
		</p>
	</center>
</div>

		<div style="height:100px;"></div>

	
		<div style="text-aign:center; width:100%;;">
			<div>
				<center>
			    <p>
			    	<i class="fa fa-phone"></i> <a href="javascript:toggle();">Contact Us</a>
			    </p>
				</center>
			</div>			
		
		<center>
			<h6 class="subheader">Software developed by Visual Advertising Sales Technology. U.S. Patent No. 7,742, 946 N.Z. Patent No. 537510 Copyright &copy; VAST 2003 - 2015.</h6>
		</center>
		</div>


<script src="js/foundation.min.js"></script>
<script>
	$(document).foundation();
</script>

<script type="text/javascript">
		$(".block").hover(
		function() {
				$(this).css("background","#d8ebf9");
			}, function() {
				$(this).css("background","#eeeeee");
			}
		);
	var win = null;
		function NewWindow(mypage,myname,w,h,scroll){
			LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
			TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
			if ((screen.width<1024) || (screen.height<768)){
				settings ='height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars=1,resizable=1,status=0'
			}
			else{
				settings ='height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',resizable=1,status=0'
			}
			win = window.open(mypage,myname,settings)
		}

		function toggle(){
			$("#main").css("display","none");
			$("#contact").css("display","inline");
		}

		function back(){
			$("#main").css("display","inline");
			$("#contact").css("display","none");
		}


</script>
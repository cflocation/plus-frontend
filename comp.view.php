<!DOCTYPE HTML>
<html lang="eng">
	<head>
		<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" /> 
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="cache-control" content="no-cache"> <!-- tells browser not to cache -->
		<meta http-equiv="expires" content="0"> <!-- says that the cache expires 'now' -->
		<meta http-equiv="pragma" content="no-cache"> <!-- says not to use cached stuff, if there is any -->
		<link rel="stylesheet" href="inc/fontawesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="inc/foundation/css/normalize.css">
		<link rel="stylesheet" href="inc/foundation/css/foundation.css">
		<link rel="stylesheet" href="inc/timepicker/jquery.timepicker.css">
		<link rel="stylesheet" href="css/drk-theme/jquery-ui-1.10.4.custom.min.css">
		
		<link rel="stylesheet" href="css/global.css">
	
	<title>ShowSeeker Plus - Login</title>
	</head>
  
	<body>
		<center>
			<div id="msg">
				<div style="font-size:10pt">
					<table>
						<tr>
							<td align="center" valign="middle"></td>
							<td align="center" valign="middle"><img src="plus/i/logo500.png" style="width:240px; height:97px;"  width="240" height="97"></td>
							<td align="center" valign="middle"></td>
						</tr>
						<tr>
							<td colspan="3" valign="middle" style="padding:20px; background-color: white;" align="justify">
								<center>
									<h3 style="color:#184a74;">Your session is running under compatibility mode.</h3>
								</center>
								<br><br>
								<div style="font-size:10pt; padding-left: 40px; padding-right: 40px;"><span style="color: #444444 !important;">
									<center><h4>Attention!</h4></center>
									<BR>
									Internet Explorer is running under compatibility mode and ShowSeeker will not operate correctly in this situation. 
									<BR>
									Please follow the <u><a style="color:blue !important;" href="#" onclick="$('#msg,#steps').toggle();" target="_self">next steps</a></u> 
									to remedy the situation and then try again.</span>
									<BR><BR><BR><BR><BR>
									<span style="float: right;">If you have any questions, please contact <a style="color:blue !important;" href="mailto:support@showseeker.com">support@showseeker.com</a>.</span>
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="footer text-center"><small>Software developed by Visual Advertising Sales Technology.  - U.S. Patent No. 7,742, 946 N.Z. Patent No. 537510 Copyright &copy; VAST 2003 - <?php print date("Y");?>.</small></div>				
				
			</div>
		</center>

		<div id="steps" style="display: none;">
			
			<nav id="main-nav" class="top-bar" data-topbar>
			  <ul class="title-area">
			    <li class="name" style="width: 290px;"><img src="plus/i/logosm_w.png" style="padding-left:5px;"></li>
			  </ul>
			  <section class="top-bar-section">
			    <ul class="right">
			      <li id="menu-4"><a href="#">Questions? Call us at 866-980-8278</a></li>
			    </ul>
			  </section>
			</nav>
			<br>			
			
			<div class="row">
				<div class="small-12 large-centered columns text-center">
					<h3>Compatibility View</h3><br> 
				</div>
			</div>
			<div class="row">
				<div class="small-12 large-centered columns">
				
				    <div class="content">
			
						<p style="padding-left: 100px; padding-right: 100px; text-indent: 50px; text-align: justify;">
							To fix Compatibility View issues, go to your Tools – click on Compatibility View Settings – and REMOVE
							ShowSeeker from the box as shown below. Make sure you UNCHECK the 2 boxes and that should clear up
							your Compatibility issues. If not, please let us know at <a href="mailto:support@showseeker.com">support@showseeker.com</a> and make sure you
							send us a screen-shot of your screen and include the Operating System and Browser you are using.
						</p>
						<p style="text-indent: 50px;">
							<center><img src="plus/i/dialogs/comp.png"></center>
						</p>
			
						<p style="text-indent: 50px;">
							<center><img src="plus/i/dialogs/comp2.png"></center>
						</p>
			
						<p style="padding-left: 100px; padding-right: 100px; text-indent: 50px;">
							After completing the previous steps please try to access <a href="http://plus.showseeker.com/plus">ShowSeeker</a> again.
						</p>
				    </div>
				</div>
			</div>
			<br/><br/>
			<div class="footer text-center"><small>Software developed by Visual Advertising Sales Technology.  - U.S. Patent No. 7,742, 946 N.Z. Patent No. 537510 Copyright &copy; VAST 2003 - <?php print date("Y");?>.</small></div>
		</div>
		
		
		
  <!-- Latest compiled and minified JavaScript -->
  <script src='js/jquery-1.7.2.min.js'></script>
  <script src='js/jquery.event.drag-2.0.min.js'></script>
  <script src='js/jquery.event.drop-2.0.min.js'></script>
  <script src='inc/foundation/js/foundation.min.js'></script>
  <script>
    $(document).foundation();
  </script>		
		
	</body>
</html>
<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);
	session_start();
	
	$u_agent = $_SERVER['HTTP_USER_AGENT'];	
	if(preg_match('/MSIE 7.0/i',$u_agent) && preg_match('/compatible/i',$u_agent) && $_COOKIE['userid'] != 3144){
        header('Location:comp.view.php');
		return;
	}	
	

	if($_SERVER['HTTP_HOST'] == "managed.showseeker.com" || $_SERVER['HTTP_HOST'] == "plus.showseeker.com"){
		header('Location:plus');
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>ShowSeeker</title>
	<link rel="stylesheet" href="/inc/foundation-5.4.6/css/normalize.css">
    <link rel="stylesheet" href="/inc/foundation-5.4.6/css/foundation.css">
	<link href="https://showseeker.s3.amazonaws.com/public-site/assets/favicon.png" name="favicon" rel="shortcut icon" type="image/png">
	<script src="/inc/foundation-5.4.6/js/vendor/modernizr.js"></script>
	<link type="text/css" href="css/app.css" rel="stylesheet" media="all" />

	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<meta name="description" content="ShowSeeker Television Commercial Proposal Builder">
	<meta name="keywords" content="proposals, television, commercials, iseeker, showseeker plus">
	<meta name="author" content="ShowSeeker">


</head>
<nav class="top-bar" data-topbar="" role="navigation" style="background-color: #184a74!important;">
  <ul class="title-area">
    <li class="name"><h1><a href="#">ShowSeeker <sup><font size="1">&reg;</font></sup></a></h1></li>
	<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
  </ul>
  <section class="top-bar-section">
    <ul class="left">
		<li class="name"><a href="#" data-reveal-id="about_us" style="background: #184a74!important;">About Us</a></li>
		<li class="name"><a href="#" data-reveal-id="contact_us" style="background: #184a74!important;">Contact Us</a></li>
		<li class="name"><a href="#" data-reveal-id="products"style="background: #184a74!important;">Products</a></li>
		<li class="name"><a href="#" data-reveal-id="testimonials" style="background: #184a74!important;">Testimonials</a></li>
	</ul>

    <ul class="right">
      <li style="background-color: #184a74!important;"><a href="http://plus.showseeker.com/login.php" class="button tiny round radius" style="background:#7893bf;">ShowSeeker Plus Login</a></li>
      <li style="background-color: #184a74!important;">&nbsp;&nbsp;&nbsp;&nbsp;</li>
    </ul>

  </section>
</nav>

<div id="wrapper-mobile"></div>
<div id="wrapper">
	<div class="row">
		<div class="small-12 large-centered columns">
				<div class="box">
					<ul>
						<li class="one-block"><div><img class="shuffle" src="" alt="" /></div></li>
						<li class="two-block"><div><img class="shuffle" src="" alt="" /></div></li>
						<li class="three-block"><div><img class="shuffle" src="" alt="" /></div></li>
					</ul>
				</div>
		</div>
	</div>
</div>

<div style="background: linear-gradient(#D6D6D6, #808080);width: 100% !important;">
	<div class="row">
	  <div class="small-8 columns">
	  <p class="text-left"><strong>ShowSeeker <sup>&reg;</sup></strong> <font color="#FFFFFF"> is a web based application that allows users to search for advance TV programming information quickly and accurately.</font></p>
	  </div>
	  <div class="small-4 columns"><br>
			<a href="#" data-reveal-id="how_it_works" class="button tiny round radius" style="background:#7893bf;">How it Works</a>
			<a href="#" data-reveal-id="trial" class="button tiny round radius" style="background:#345077;">Request a Free Trial</a>
	  </div>
	</div>
</div>

<div class="row collapse">
<br />
			  <div class="medium-3 large-uncentered columns">
				<ul class="no-bullet list-graph1"><li><p>&nbsp;</p></li></ul>
			  </div>
			  <div class="medium-3 large-uncentered columns">
				<ul class="no-bullet list-graph2"><li><p>&nbsp;</p></li></ul>
			  </div>
			  <div class="medium-3 large-uncentered columns">
				<ul class="no-bullet list-graph3" ><li><p>&nbsp;</span></p></li></ul>
			  </div>
			  <div class="medium-3 large-uncentered columns">
					<a class="twitter-timeline" width="95%" height="125" data-chrome="nofooter noheader"  data-dnt="true" href="https://twitter.com/showseekertv" data-widget-id="530426387337711617"></a>
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

			  </div>
  </div>
 



<div class="row">
<hr>
	<div class="small-3 large-3 columns">		
	<ul class="social">
			<li><a href="https://www.facebook.com/showseeker" target="_blank" class="ico-facebook"></a></li>
			<li><a href="https://twitter.com/showseekertv" target="_blank" class="ico-twitter"></a></li>
			<li><a href="https://www.linkedin.com/company/showseeker" target="_blank" class="ico-in"></a></li>
	</ul>
  </div>
  <div class="small-6 large-6 columns"><br /><center>Questions? Need help? - Call us at 866.980.8278</center></div>
  <div class="small-3 large-3 columns"><img align="right" src="images/show-seeker-company.gif"></div>
</div>
<div class="row">
  <div class="small-12 large-centered columns">
	 <center><font size="1">Software developed by Visual Advertising Sales Technology - U.S. Patent No. 7,742,946 N.Z. Patent No. 537510 <br /> Copyright &copy; VAST 2003 - <?php echo date("Y")?> </font></center>
  </div>
</div>

<?php // ABOUT US MODAL?>

	<div id="about_us" class="reveal-modal" data-reveal>
	  <div class="row">
		<center><div class="small-12 columns"><img src="images/ss_plus.png" width="200px"></div></center>
	</div>
	<hr />
	  <p>ShowSeeker is a VAST (Visual Advertising Sales Technology) company headquartered in the Sierra Nevada Foothills of Northern California. We have one main goal:</p>
	  <p><center><h6><strong>"Our single focus is to make our customers' jobs easier" - Dave Hardy, President</strong></h6></center></p>
	  <p>ShowSeeker is about creating solutions; we're continually innovating and anticipating how to improve our products. We listen to our customers.</p>
	  <p>This patented web-based application requires zero deployment on Client servers. Available by subscription 24/7, the application allows you to easily research and print our extensive television program database from today up to fifty-six (56) days out. You can quickly merge, sort, manipulate, view and print customized television listings and program schedules in Microsoft Word, Microsoft Excel, Power Point and Adobe PDF files. You can also export search results directly into sales automation programs such as "Strata" and "adSails". </p>
	  <p>ShowSeeker was envisioned by Cable Ad Sales Account Executives, Research Staff and Managers who sensed the need for a fast and accurate way to get a handle on the growing amount of insertable television programming information that exists today.  Before ShowSeeker was created, anyone who wanted to sell fixed position programming needed to spend hours looking up programming on network affiliate websites. Today, search results are realized with lightning-quick speed along with pinpoint accuracy. </p>
	  <p>ShowSeeker provides end users the ability to take a common-sense approach to selling television advertising by focusing on programming content as the best metric for direct ad sales as well as agency buys.  A business in the Wedding planning or related field can be presented programming with wedding-related content instantly.  A sporting goods retailer can receive fishing-related programming during fishing season and hunting-related programming during hunting season with a few clicks of the AE's mouse.</p>
	  <p>All these highly-targeted choices can be sorted, priced and presented in an eye-pleasing presentation within just a couple of minutes, making the AE's job easier and more efficient.  Fixed position shows can be merged with long-term rotator lines in mere minutes to create the ultimate ROS/Fixed position proposal.</p>
	  
	  <a class="close-reveal-modal">&#215;</a>
	</div>

<?php // CONTACT US MODAL?>
	<div id="contact_us" class="reveal-modal" data-reveal>
	  <div class="row">
		<center><div class="small-12 columns"><img src="images/ss_plus.png" width="200px"></div></center>
	</div>
	<hr /><br />
		<center>
			<p><strong><u>For Current ShowSeeker Customers:</u></strong>  For Technical Support (difficulty logging in or other technical issues) or for assistance in using ShowSeeker, refer to the User Guides, Recorded Tutorials or FAQs located under the gold "Help" tab in your ShowSeeker account.<br /><br /> If your answer is not found, send an email to: <br /><a href="mailto:support@showseeker.com">support@showseeker.com</a></p>
			<p><strong><u>For information on ShowSeeker products:</u></strong> If you are interested in finding out more about any of our products, <br /><br /><a href="#" data-reveal-id="trial" class="radius round tiny success secondary button">Click Here</a></p>
			<p>If you have specific questions not covered above, call us at: <br />866-980-8278</p>
		  <a class="close-reveal-modal">&#215;</a>
		</center>
	</div>


<?php // HOW IT WORKS MODAL?>

	<div id="how_it_works" class="reveal-modal" data-reveal>
	  <div class="row">
		<center><div class="small-12 columns"><img src="images/ss_plus.png" width="200px"></div></center>
	</div>
<center><h5>Use ShowSeeker PLUS to search for relevant shows to advertise your clients' business or services.</h5></center>
<hr />
	<ul class="small-block-grid-5">
	  <li><center><img src="images/ShowSeeker_I1.png" width="129px" height="100px"><br /><h6 class="subheader">Select search criteria to locate relevant shows</h6></center></li>
	  <li><center><img src="images/ShowSeeker_I2.png" width="129px" height="100px"><br /><h6 class="subheader">Choose shows and any rotator lines to add to the proposal</h6></center></li>
	  <li><center><img src="images/ShowSeeker_I3.png" width="129px" height="100px"><br /><h6 class="subheader">Add rates and discounts associated to the proposal</h6></center></li>
	  <li><center><img src="images/ShowSeeker_I4.png" width="129px" height="100px"><br /><h6 class="subheader">Choose from multiple download format options for output</h6></center></li>
	  <li><center><img src="images/ShowSeeker_I5.png" width="129px" height="100px"><br /><h6 class="subheader">Download and send proposal to client and insertion order to traffic</h6></center></li>
	</ul>
		  <a class="close-reveal-modal">&#215;</a>
	</div>

<?php // TESTIMONIALS MODAL?>

	<div id="testimonials" class="reveal-modal" data-reveal>
	  <div class="row">
		<center><div class="small-12 columns"><img src="images/ss_plus.png" width="200px"></div></center>
	</div>
<hr />
			<p>I wanted to share a success story that ShowSeeker has made possible.  We have a client who promotes their Hockey games and their schedules are very demanding and challenging to build.  They have aggressive goals and the schedules are typically only 2 days long.  I have a hard time spending the money within these days and their schedule parameters.  However, with the aide of ShowSeeker <strong> I am able to find hockey games and hockey support programming that the client is willing to pay a premium for.  This helps ensure we do not lose any dollars and we look like heroes to the client!</strong>  Thanks ShowSeeker! <br /><br /><i class="testimonials">Melissa, Sales Services Specialist from Nebraska</i></p>
			<hr>
			<p><strong> I love ShowSeeker because it keeps me updated on new programming, as well as the return of current series, including new episodes, series premieres, and finales. </strong>  The team of sales associates I work <strong> with like the E-Z Grids feature.</strong>   I also like being able to select a specific network and seeing a list of titles of all the programs airing on that network.  It comes in handy when I'm not sure exactly what I'm looking for; I can easily pull out the most popular series titles.  <strong> I also like the monthly newsletters from ShowSeeker telling me about special programming, such as Halloween Specials and Holiday-themed programming.</strong> Keep up the good work, ShowSeeker! <br /><br /><i class="testimonials">Mikael, Research Analyst from South Carolina</i></p>
			<hr>
			<p>ShowSeeker helps us to showcase the great shows on the cable networks.  Cable network brands are big, but putting the actual program names on the proposal really lets you connect to potential clients. <strong> It really gives me an edge when trying to go up against the broadcasters.  Being able to download as an SCX file was essential in letting me land the Cake Boss shows that I sold to my client.</strong><br /><br /><i class="testimonials">Toni, Account Manager from Illinois</i></p>
			<hr>
			<p>I love ShowSeeker because it saves me a TON of time when I need to look up specific programming! The premieres and finales is great as we have so many networks it would take forever to find them all! <strong> I also love the movies and marathons option.  I have used that many times to upsell clients!</strong><br /><br /><i class="testimonials">Leigh, Account Executive from Iowa</i></p>
			<hr>
			<p>I couldn't imagine doing my job without ShowSeeker! <strong>Between my "All Inclusive Sports" package and having 53 networks to manage, there is NO better tool to help find programming than ShowSeeker! </strong> I have many clients who love premieres and first run episodes and add them each month to their buy. Thank you for all you do to help us out in the field!  We couldn't do it without you!<br /><br /><i class="testimonials">Bobbi Kaye, Media Sales Services Specialist from Louisiana</i></p>
			<hr>
			<p>ShowSeeker is a tremendous help when I do the monthly schedule for a local Bridal Shop client.  Because I use ShowSeeker, <strong>I am able to find wedding-related or women-skewed programming that is the appropriate venue for my Client's message.</strong>  Since advertising with our Company and running their fixed programming schedule, <strong>my Client has reported many weekends when they were fully booked with appointments from eager brides-to-be.</strong><br /><br /><i class="testimonials">Marissa, Media Sales Services Specialist from Virginia</i></p>
			<hr>
			<p>I love ShowSeeker simply because it saves me time!  When I need to create customized schedule packages for my Account Executives, <strong>I can get them packages in a timely and accurate fashion so we can increase our revenue!</strong> This is especially true with sports!  There can be so many games it can be frustrating entering every game on all the nets. <strong>With ShowSeeker, it makes me look like I worked long hours when I was only working smarter not harder!</strong><br /><br /><i class="testimonials">Aubrey, Administrative Assistant from North Dakota</i></p>
			<hr>
			<p>Everything at my fingertips! The best shows for my client's customers? They're all right there. Key Networks? Got 'em!  Daily/Weekly/Monthly schedules, down to the minute? <strong> Type a few keywords and "BAM"!</strong> My clients need specific programming to reach their customers, and <strong><u>nothing else</u> gets it to me as quickly or as thoroughly.</strong> I'd be lost without it. What more can I say? I LOVE SHOWSEEKER!!! <br /><br /><i class="testimonials">Brian, Media Consultant from Texas</i></p>

	  <a class="close-reveal-modal">&#215;</a>
	</div>




<?php // PRODUCTS MODAL?>

	<div id="products" class="reveal-modal" data-reveal>
	  <div class="row">
		<center><div class="small-12 columns"><img src="images/ss_plus.png" width="200px"></div></center>
	</div>
	<hr /><br / >
	  <div class="row">
		<div class="small-3 columns"><img src="images/SS_P1.png"></div>
		<div class="small-9 columns"><br /><strong><u>ShowSeeker PLUS</u></strong> makes an Ad Sales Team's jobs easier and more efficient. Targeted and relevant searches for programs on the growing amount of insertable television networks are quickly completed to create powerful and effective proposals for your clients.  Find the perfect ad placement for your customers with ShowSeeker PLUS.</div>
	</div>
	  <div class="row">
		<div class="small-3 columns"><img src="images/SS_P2.png"></div>
		<div class="small-9 columns"><br /><strong><u>E-z Grids</u></strong>   allows you to view any network's grid for up to 8 weeks and is updated daily with new programming.  Choose up to 24 hours to view.  It easily links programs to existing ShowSeeker proposals.  Quickly find all-day marathons to create a high-frequency schedule for your clients.</div>
	</div>
	  <div class="row">
		<div class="small-3 columns"><img src="images/SS_P3.png"></div>
		<div class="small-9 columns"><br /><strong><u>E-z Breaks</u></strong> - Our exclusive Break Structure product provides programming data and break information for each network instance in your insertable network lineup. Receive automated daily schedule updates for a 7-35 day rolling window and if desired, a bulk or individual network download by time zone On Demand 24/7.</div>
	</div>
	  <div class="row">
		<div class="small-3 columns"><img src="images/SS_P4.png"></div>
		<div class="small-9 columns"><br /><strong><u>iSeeker</u></strong>  -  ShowSeeker PLUS goes mobile!  Everything you get in our PLUS product available on your iPhone and iPad.  Displays full-color photos, graphics, descriptions and movie trailers.  Links to ShowSeeker PLUS for enhanced mobile proposal creation and edits.</div>
	</div>

 	  <a class="close-reveal-modal">&#215;</a>
	</div>
  

<?php // TRIAL MODAL?>

	<div id="trial" class="reveal-modal" data-reveal>
	  <div class="row">
		<center><div class="small-12 columns"><img src="images/ss_plus.png" width="200px"></div></center>
	</div>
	<hr />
	<form data-abide action="includes/emailer.php" method="post">
		<div class="row">
		<center>Thank you for your interest in our product(s), please fill out this form below and someone will contact you shortly.</center><br />
			<div class="small-5 columns">
				<input id="first" name="first" placeholder="First Name" type="text" width="25" required pattern="[a-zA-Z]+"/> 
				<small class="error">First Name Required</small>
			</div>
			<div class="small-5 columns">
				<input id="last" name="last" placeholder="Last Name" type="text" width="25" required pattern="[a-zA-Z]+"/>
				<small class="error">Last Name Required</small>
			</div>
		</div>
		<div class="row">
			<div class="small-5 columns">
				<input id="email" name="email" placeholder="Email Address" type="text" required pattern="email"/>
				<small class="error">Email Required</small>
			</div>
			<div class="small-5 columns">
				<input id="phone" name="phone" placeholder="Phone Number ( xxx-xxx-xxxx ) " type="text" required pattern="^\d{3}-\d{3}-\d{4}$"/>
				<small class="error">Phone Required</small>
			</div>
		</div>
		<div class="row">
			<div class="small-5 columns">
				<input id="title" name="company" placeholder="Company Name" type="text" required pattern="[a-zA-Z]+"/>
				<small class="error">Company Name Required</small>
			</div>
			<div class="small-5 columns">
				<input id="website" name="website" placeholder="Company Website" type="text" />
			</div>
		</div>
		<div class="row">
			<div class="small-5 columns">
				<input id="title" name="title" placeholder="Title" type="text" required pattern="[a-zA-Z]+"/>
				<small class="error">Title Required</small>
			</div>
			<div class="small-5 columns">
				<input id="source" name="source" placeholder="Where did you hear about us?" type="text" />
			</div>
		</div>

			<center><input type="submit" class="round tiny success secondary button" title="Continue" value="Continue" /></center>
	</form>
	  <a class="close-reveal-modal">&#215;</a>
	</div>


















<script src="/inc/foundation-5.4.6/js/vendor/jquery.js"></script>
<script src="/inc/foundation-5.4.6/js/foundation.min.js"></script>  
<script type="text/javascript" src="/js/jquery.timer.js"></script>
<script type="text/javascript" src="/js/slider.js"></script>
<script>
	$('body').bind('touchstart', function() {});
	$(document).foundation();
</script>

<script>
  $(document).ready(function() {
  	// set unique id to videoplayer for the Webflow video element
  	var src = $('#contest_video').children('iframe').attr('113239602');

  	// when object with class open-popup is clicked...
  	$('.open-popup').click(function(e) {
  		e.preventDefault();
  		// change the src value of the video
  		$('#contest_video').children('iframe').attr('src', src);
  		$('.popup-bg').fadeIn();
  	});

  	// when object with class close-popup is clicked...
  	$('.close-popup').click(function(e) {
  		e.preventDefault();
  		$('#contest_video').children('iframe').attr('src', '');
  		$('.popup-bg').fadeOut();
  	});
  });
</script>
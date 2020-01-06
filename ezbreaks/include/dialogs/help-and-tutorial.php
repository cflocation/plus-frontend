<?php
$tutorial = ($_SESSION['corporationid']==44)?'include/FrontierE-zBreaksGuideFinal8-2017v2.pdf':'include/EzBreaksGuide.pdf';
?>
<center>
	<p style="font-size:12px;">
		For Technical Support or for assistance in using E-z Breaks, refer to the <a href="javascript:openTutorial('<?php print $tutorial; ?>');" style="text-decoration: underline;">User Guide</a>,
		<br />If your answer is not found, email us at :<br />
		<a style="text-decoration: underline;" href="mailto:breaks@showseeker.com">breaks@showseeker.com</a>
	</p>
	<p style="font-size:12px;">
		If you have specific questions not covered above, call us at:
		<br /><span style="text-decoration: underline;">866-980-8278</span>
	</p>
	<hr>
	<p style="font-size:12px;">
		Software developed by Visual Advertising Sales Technology.<br />
		U.S. Patent No. 9,635,391<br />
		N.Z. Patent No. 537510<br />
		Copyright &copy; VAST 2003 - <?php print strftime("%Y"); ?>
	</p>
</center>
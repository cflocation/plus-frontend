<p></p>
<p>
	<ul>
	<li><a style="font-weight:700;outline: 0;font-size:12pt;text-decoration:none;cursor:pointer;" onclick="loadNewsletter('oct2018.php','840','700')"  target="_blank">October 2018</a><BR><BR></li>		
	<li><a style="font-weight:700;outline: 0;font-size:12pt;text-decoration:none;cursor:pointer;" onclick="loadNewsletter('spooky.php','840','700')"  target="_blank">The Spooky Guide</a><BR><BR></li>
	<li><a style="font-weight:700;outline: 0;font-size:12pt;text-decoration:none;cursor:pointer;" onclick="loadNewsletter('sep2018.php','840','700')"  target="_blank">September 2018</a><BR><BR></li>
	<li><a style="font-weight:700;outline: 0;font-size:12pt;text-decoration:none;cursor:pointer;" onclick="loadNewsletter('aug2018.php','840','700')"  target="_blank">August 2018</a><BR><BR></li>
	</ul>
</p>

<script>
	function loadNewsletter(filename,w,h){
		LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
		TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
		window.open('includes/_newsletters/'+filename, 'ShowSeeker Newsletter','width='+w+',top='+TopPosition+',left='+LeftPosition+', height='+h+', scrollbars=Yes').focus();
	}
</script>
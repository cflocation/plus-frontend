<?php 

if(isset($_GET['option'])){
	$thisOption = $_GET['option'];
}
else{
	$thisOption = "";
}	


if($thisOption == 'MM'){?>


<div style="font-size:10pt">
	<table align="center" width="580">
		<tr>
			<td align="center" valign="middle"></td>
			<td align="center" valign="middle">
				<br>
				<!-- img src="i/dialogs/olympics.png" style="width: 150px; border: solid 1px #000080;" -->
			</td>
			<td align="center" valign="middle"></td>
		</tr>
		<tr>
			<td colspan="3" valign="middle" style="" align="justify">
				<center>
					<h2 style="color:#184a74;">March Madness</h2>
				</center>
								
				<div style="font-size:10pt; ">
					<span>
						<center>
						The 2019 NCAA Basketball Tournament, also known as March Madness, is available to search in ShowSeeker. Here is how you can find it:
						</center>
						<br />
						<ol>
							<li>Set dates March 19th-March 30th</li>
							<li>Open up Title Search</li>
							<li>Type in 2019 NCAA Basketball tournament, once the title appears click on the title, it will now be highlighted in green and drag to the right; click Search</li>
							<li>Drag down all the events (or choose the ones you want), price and sell your package!</li>
						</ol>
						<center>
						Please note the Turner networks are airing "First Four" round (3/19/19) through the "Elite 8" round (3/30/19).
						</center>
					</span>
					<br><br>
					<center>
						If you have any questions please reach out to us at <a style="color:maroon !important;" href="mailto:support@showseeker.com">support@showseeker.com</a>. Thank you!
					</center>
				</div>
			</td>
		</tr>
	</table>
<br><br>
</div>


<?php 
}
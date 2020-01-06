<div id="showinfo-rows">
	
	<center>
		<div id="showinfo-title"></div>
		<div style="width:170px;height:255px;" id="showinfo-cover"></div>
	</center>
	
	<div id="showinfo-epititle"></div>
	
	<div id="showinfo-desc"></div>
	
	<br style="clear:both;">
	
	<div id="showinfo-genre"></div>
	
	<br style="clear:both;">
	
	<div style='float:left;' id="showinfo-premiere"></div>
	<div style='float:left;margin-left:1px;' id="showinfo-live"></div>	
	<div style='float:left;margin-left:1px;' id="showinfo-projected"></div>	
	
	<br style="clear:both;">
	
	<table style="width: 100%" cellspacing="0" cellpadding="0">
		<tr style="height: 30pt;">
			<td valign="middle"><span id="showinfo-released"></span></td>
			<td valign="middle"><span id="showinfo-tvrating"></span></td>
		</tr>
	</table>
	
</div>

<br style="clear:both;">
<br style="clear:both;">

<div class="row">
	<table  style="align-content: center; width: 100%;" align="center">
		<tr>
			<td style="width: 33%" align="center">
				<button class="btn-red" onclick="panelManager('close');" style="height:25px;" id="close-show-info"><i class="fa fa-times-circle"></i> Close</button>
			</td>
			<td style="width: 35%" align="center">
				<button class="btn-green" id="btn-more-info" style="height:25px; width: 90px; display: none;" onclick="loadShow(selectedShowId);"><i class="fa fa-info-circle"></i> More Info</button>
			</td>
			<td style="width: 32%" align="center">
				<button  class="btn-blue" style="height:25px; display: none;" id="video-launcher"><i class="fa fa-play"></i> Trailer</button>
			</td>
		</tr>
	</table>
</div>

<script>
	$('#close-show-info').button();
	function openVideoPlayer(url){
		l = (screen.width/2)-(612);
		w =	1024;
		h =	490;
		return !window.open('http://managed.showseeker.com/plus/'+url,'', 'width='+w+',height='+h+',resizable=0,scrollbars=0,toolbar=0,status=0,left='+l);
	}
</script>
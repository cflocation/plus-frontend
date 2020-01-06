<center id="loadingMessage">
	<br/><br/>
	<h3>Preparing your files please wait ....</h3>
	<br/><br/>
	<img src=i/ajax.gif>
</center>

<center>	
	<div id="ezgrids-loader"  style="height: 395px; overflow: hidden; padding: 20px; display: none;">
		
		<img src="https://showseeker.s3.amazonaws.com/public-site/assets/logo/showseeker_login.png">
		<h2>E-z Grids <sup><span style="font-size: 6pt">TM</span></sup></h2>
		
		<?php 
		date_default_timezone_set('America/Los_Angeles');	
		$dw 	= date("w");
		$t 	= date('H');
		if($dw == 2 && $t < 14){ ?>
			<div style="max-width:420px; margin-left: auto; margin-right: auto; margin: 20px;">
				<b>Note:</b> E-z Grids are processing for the current broadcast week 
				and typically finalize on Tuesday afternoon (5pm ET). 
				If you download files now you will see data for last week.				
			</div>
		<?php } ?>
		<div id="ezgrids-list" style="overflow-y:auto; overflow-x: hidden; height:270px; min-width: 380px; max-width: 400px;">
			<table class="table_usrRatings summaryTblHeader" style="width: 95%; background-color: white; border-collapse: collapse;" cellspacing="0">
				<thead>
					<tr class="tr_usrRatings divHeader">
						<th width="61%">Region</th>
						<th width="31%">Net Count</th>
						<th width="8%"><i class="fa fa-download" aria-hidden="true"></i></th>
					</tr>
				</thead>
				<tbody id="ezgridsListBody">
				</tbody>
			</table>
		</div>
		
	</div>
</center>

<script type="text/javascript">
	document.domain = "showseeker.com";	
	loadEzgridsList();

	var zipList;
	
	function loadEzgridsList(){
		
		$.ajax({
         type:'post',
			url:'services/getGrids.php?uid='+userid+'&apikey='+apiKey+'&corp='+corpid,
         dataType:"json",
         headers:{"Api-Key":apiKey,"User":userid},
         data: {"uid": userid, "apikey": apiKey, "corp":corpid},
         success:function(resp){			
				var ezgrids_line = '';
				zipList = resp;
				$.each(resp,function(i,item){
					ezgrids_line = $("<tr class=''><td class='borderBottom td_usrRatings'  style='border-left: dotted 1px #ccc; border-right: dotted 1px #ccc;'>"+item.market+"</td><td class='borderBottom td_usrRatings' style='border-right: dotted 1px #ccc;' align=center>"+item.count+"</td><td class='borderBottom td_usrRatings' align=center style='border-right: dotted 1px #ccc;'><a onclick=sendToMixPanel("+i+") href='https://easygrids.showseeker.com/zips/"+item.file+"'><i class='fa fa-arrow-circle-down'></i></a></td></tr>").appendTo($('#ezgridsListBody'));
				});
				$('#loadingMessage,#ezgrids-loader').toggle();
			},
			error: function(e){}
		})
	}
	function sendToMixPanel(i){
		var data = {};
		data.region = zipList[i].market;
		data.fileName = zipList[i].file;
		data.netCount = zipList[i].count;		
		mixTrack('EzGrids-Dowload',data);
	}
</script>

<style>
#importOverlay {position:fixed;display:none;width:100%;height:100%;top:0;left:0;right:0;bottom:0;background-color:rgba(255,255,255,.5);;z-index:2;cursor:pointer;line-height: 300px;}
</style>

<div style="width: 100%; height:100%; display:none; position: absolute; top: 0px; left: 0px;" id="importOverlay">
	<center>
		<div class="loading">
			<i class="fa fa-spinner fa-spin fa-fw fa-3x" aria-hidden="true" style="color: #444;"></i>			
		</div>		
	</center>
</div>
<p><h3><i class="fa fa-inbox"></i> Inbox</h3></p>
<div class="gridwrapper">
	<div style="height:200px;width:520px;" id="datagrid-messages"></div>
</div>

<p></p>

<center>
	<button id="message-import" onclick="messageImport();" class="btn-green"><i class="fa fa-arrow-circle-down"></i> Import Item</button> <button onclick="messageDelete();" class="btn-red"><i class="fa fa-trash-o"></i> Delete Checked</button>
</center>

<p></p>

<div id="message-body">
	<h3><i class="fa fa-clipboard"></i> Message:</h3> 
	<p id="message-message" style="font-size:9pt; border-radius: 8px; border: solid 1px #ccc; height: 70px; padding: 10px; background-color: #fff;"></p>
</div>

<script type="text/javascript">

	loadMessages(userid);

	function loadMessages(userid){
		$.ajax({
	        type:'get',
	        url: apiUrl+"share/messages",
	        dataType:"json",
	        headers:{"Api-Key":apiKey,"User":userid},
	        success:function(data){
	            $.each(data.messages,function(i,m){
	            	m.sentfrom  = m.senderFirstName+" "+m.senderLastName;
	            	m.createdat = m.createdAt;
	            	m.hasread   = m.hasRead;
	            });
	            datagridMessages = new DatagridMessages();
				datagridMessages.populateDataGrid(data.messages);
	        }
	    });
	}


	function loadMessageContent(){
		var rows 	= datagridMessages.getSelectedRows();
		if(rows.length > 1 || rows.length == 0){
			$('#message-import').css("display","none");
			$('#message-message').html('To import please select a single item');
			return;
		}
		var rowIdx 	= datagridMessages.getSelectedIndexById(rows[0].id);
		$('#message-import').css("display","inline");
		$('#message-message').html(rows[0].message);

		if(rows[0].hasread == 0){
			$.ajax({
				type:'post',
				url: apiUrl+"share/markmessageread",
				dataType:"json",
				headers:{"Api-Key":apiKey,"User":userid},
				processData: false,
				contentType: 'application/json',
				data: JSON.stringify({"messageId":rows[0].id}),
				success:function(resp){
					rows[0]['hasread'] = 1;
					rows[0]['hasRead'] = 1;	
					datagridMessages.updateRowByIndex(rowIdx);
					getUserMessages();
				}
			});
		}
	}


	function messageImport(){
		$('#importOverlay').show();
		var row  = datagridMessages.getSelectedRows()[0];
		var type = row.type;
		var data = {"messageId":row.id} //,"proposalId":row.proposalId
		$.ajax({
			type:'post',
			url: apiUrl+"share/importitem",
			dataType:"json",
			headers:{"Api-Key":apiKey,"User":userid},
			processData: false,
			contentType: 'application/json',
			data: JSON.stringify(data),
			success:function(resp){
				//RESET PROPOSAL FINDER FILTER
				clearProposalFilter();					
				closeAllDialogs();
				getUserProposals(userid, tokenid);
				loadSavedSearches();
				loadDialogWindow('messageimported', 'ShowSeeker Plus', 280, 150, 1, false);
			}
		});
	}
	

	function messageDelete(){
		var xrows      = datagridMessages.getSelectedRows();
		var messageIds = [];
		$.each(xrows,function(i,m){ messageIds.push(m.id); });

		$.ajax({
			type:'post',
			url: apiUrl+"share/deletemessages",
			dataType:"json",
			headers:{"Api-Key":apiKey,"User":userid},
			processData: false,
			contentType: 'application/json',
			data: JSON.stringify({"messageIds":messageIds}),
			success:function(resp){
				loadMessages(userid);
			}
		});
	}

	$("button").button();
	
</script>
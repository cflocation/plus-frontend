<script type="text/javascript">

	var headerid = 0;
	getHeaders();

	$('#close-custom-title-btn').click(function(){
		$("#dialog-window").empty().dialog("destroy")
	});
	$('#reset-custom-title-btn').click(function(){
		//$(this).hide();	
		$('#header-title').val('');
		datagridHeaders.unSelectAll();
	});

	function getHeaders(){
		var url = '/services/1.0/headers.load.php';	
	
		$.when(buildToken(url)).done(function(token){
	    	url = token['url'];
	
			$.getJSON(url, function(data) {
				datagridHeaders = new DatagridHeaders();
				datagridHeaders.populateDataGrid(data);
				if(headerid != 0){
					datagridHeaders.selectRows([headerid]);
				}
			});
		});		
	}

	function headerClientForm(){
		headerid = 0;
		$("#header-form-clear").css('display', 'none');
		$("#header-save-button span").text('Save New Proposal Header'); 
		$('#header-title').val('');
	}


	function loadHeaderContent(){
		var rows = datagridHeaders.getSelectedRows();

		if(rows.length > 1 || rows.length == 0){
			return;
		}
		//$("#header-save-button span").text('Save Changes'); 
		//$("#header-form-clear").css('display', 'inline');
		$("#download-remove-header").css('display', 'inline');
		$('#download-panel-header').html(rows[0].header);
		//$('#header-title-edit').val(rows[0].header);
		$('#header-title').val(rows[0].header);
		
		$("#edit-custom-title-btn,#reset-custom-title-btn").show();
		headerid = rows[0].id;
	}



	function headerSaveChanges(type){
		var header = String($('#header-title').val()).trim();
	
		if(header.length == 0)
			return
	
		if(type == 'new'){
			headerid = 0;

			if( String(header).length > 75 ){
				$('#long-name-alert').toggle();
				setTimeout(function(){
					$('#long-name-alert').toggle();					
				}, 6000)
				return;
			}			
			
		}else{
			header = $('#header-title').val();
		}
		
		var url = "/services/1.0/headers.save.php";
		
		$.when(buildToken(url)).done(function(token){
			tokenid 	= token['key'];
			userid 	= token['userid'];		
		
			$.post(url, { userid: userid, tokenid:tokenid, header: header, headerid: headerid},function(data) {
				//get the json result for the data
					getHeaders();

			});
		
		});
	}



	function headersDeleteSelected(){
		var rows = datagridHeaders.getSelectedRows();
		var json = JSON.stringify(rows);
		
		var url = "/services/1.0/headers.delete.php";

		$.when(buildToken(url)).done(function(token){
			tokenid 	= token['key'];
			userid 	= token['userid'];		
			url 		= token['url'];

			$.post(url, { userid:userid, tokenid:tokenid,  rows: json},function(data) {
				//get the json result for the data
				getHeaders();
				downloadHeaderRemove();
			});
		});		
	}

	$("button").button();
</script>


<div class="gridwrapper" style="padding:5px;">
	<b>Create/Update Proposal Title:</b> 
	<input class="input-q rounded-corners" id="header-title" type="text"/> 
	<button onclick="headerSaveChanges('new');" class="btn-green"><i class="icon-save"></i> Save New</button> 
	<button onclick="headerSaveChanges('old');" class="btn-blue" style="display:none;" id="edit-custom-title-btn">Edit Title</button>
	<button type="reset"  class="btn-red" style="display:none;" id="reset-custom-title-btn"><i class="fa fa-refresh"></i></button>
</div>

<div class="gridwrapper" style="width:450px; margin-left: auto; margin-right: auto; display:none; height:25px; line-height: 25px;" id="long-name-alert">
	<center><span style="background-color: red; color: white; padding: 5px;">Please limit the proposal name to 75 characters or less.</span></center>
</div>

<br style="clear:both;">


<div class="gridwrapper" style="width:450px; margin-left: auto; margin-right: auto;">
	<div style="height:330px;width:450px;" id="datagrid-headers"></div>
</div>


<!-- div class="gridwrapper" id="header-update" style="height:300px;padding:15px;float:left;margin-left:10px;display:none;">
		<table cellpadding="3">
				<tr>
					<td nowrap="nowrap" align="right">Title:</td>
					<td><input class="input-q rounded-corners" id="header-title-edit" type="text"/></td>
				</tr>
				<tr>
					<td colspan="2">
						<center><p><button onclick="headerSaveChanges('old');" class="btn-green">Edit Title</button></p></center>
					</td>
				</tr>
		</table>
</div -->

<br style="clear:both;">
<p></p>
<center>
	
	<!--
	<button style="display:none;" id="header-form-clear" onclick="headerClientForm();" class="btn-blue">Creat New Header</button>
-->
	<button class="btn-red" onclick='headersDeleteSelected();'><i class="icon-trash"></i> Delete Selected</button>
	<button class="btn-blue" id="close-custom-title-btn">Close</button>
	<!--
	<button class="btn-red">Archive Items</button>
-->
</center>


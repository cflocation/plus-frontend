<h3>
	<i class="fa fa-users"></i> Select Users/Groups
</h3>

<span id="filter-mode">
	<input type="radio" checked="checked" id="filter-corporation" name="filter-mode-option" onclick="getUserList('corporation');"/><label for="filter-corporation">Everyone</label>
	<input type="radio" id="filter-office" name="filter-mode-option" onclick="getUserList('office');"/><label for="filter-office">Filter by Office</label>
	<input type="radio" id="filter-market" name="filter-mode-option" onclick="getUserList('market');"/><label for="filter-market">Filter by Market</label>
</span>
<br>
<div class="gridwrapper" style="display: none; height: 25px; line-height:25px; border: none !important;  background-color: red;" id="nouserselected"><center><span style="padding: 4px; color: white;">Please select the user(s) you want to share your proposal(s) with.</span></center></div>
<br>
<select class="selectorw rounded-corners" id="group-selector">
	<option value="0">Select Group</option>
</select>

<span id="new-group-wrapper">
	<input type="text" name="group-name" id="group-name" placeholder="Group Name"> <button class="btn-green" onclick="createNewGroup();"><i class="fa fa-users"></i> Create New Group</button>
</span>

<span id="edit-group-wrapper">
	<button class="btn-green" onclick="saveGroup();"><i class="fa fa-file"></i> Save Changes</button> <button class="btn-red" onclick="removeGroup();"><i class="fa fa-trash"></i> Delete</button> <button class="btn-red2" onclick="resetSP();"><i class="fa fa-refresh"></i> Reset</button>
</span>

<br><br>

<div style="background: maroon; color: white; padding: 6px;">
Due to feeds being different, if you share with someone in a time zone other than yours, they will not see the correct times for their time zone.	
</div>
<br>
<div class="gridwrapper">
	<div style="height:175px;width:670px;" id="share-user-list"></div>
</div>
<div style="width: 100%; height: 5px;"></div>
<div class="" style="padding: 5px; border: solid 1px #ccc; border-radius: 8px; background-color: #B5E1C4;">
<div style="padding: 5px; color:#333; font-size: 8pt; font-weight: 700;">
To add more than 1 user to share with or to add a Group, use your <b>Shift key</b> to define a range, or your <b>Ctrl key</b> to add random users. Clicking more than 1 user without using one of these options will deselect all.</div>
</div>

<h3>
	<i class="fa fa-pencil"></i> Message
</h3>

<div class="gridwrapper">
	<textarea id="share-message" style="height:50px;width:665px;"></textarea>
</div>

<br style="clear:both;">

<center>
	<button class="btn-green" onclick="shareItem();" id="share-proposa-btn"><i class="fa fa-envelope"></i> Send Message/Share</button>
	<button class="btn-red" onclick='closeAllDialogs();'><i class="fa fa-times-circle fa-lg"></i> Cancel</button>
</center>


<script type="text/javascript">
	
	$("button").button();
	$("#filter-mode").buttonset();
	$('#edit-group-wrapper').css("display", "none");

	datagridUsers = new DatagridUsers();
	getUserList('corporation');
	getGroups(0);
	var selectedType = '';

	//on change slect the users
	$('#group-selector').change(function() {
		var group = $(this).val();

		if(group == 0){
			$('#new-group-wrapper').show();
			$('#edit-group-wrapper').hide();
			datagridUsers.unSelectAll();
			return;
		}

		var type  = $(this).find('option:selected').attr("class");
		var users = $(this).find('option:selected').attr("data-users");

		if(selectedType != type){			
			$('#filter-corporation, #filter-market, #filter-office').prop('checked',false).button("refresh");;
			$('#filter-'+type).prop('checked',true).button('refresh');

			$.when(getUserList(type)).then(function(data){
				if(data == true && group != 0){
					r = selectGroup(users);
				}
			});
		} else {
			selectGroup(users);
		}
		return;
	});
	
	
	function selectGroup(users){
		$('#new-group-wrapper').hide();
		$('#edit-group-wrapper').show();
		datagridUsers.selectRows(users.split(","));
		return;
	}


	function createNewGroup(){
		var name = $('#group-name').val();
		var type = 'corporation';
		
		len = name.trim().length;
		if(len == 0){
			window.alert('Please enter a valid Group Name');
			return;
		}

		len = datagridUsers.getSelectedRows().length;
		if(len == 0){
			window.alert('Please select some users');
			return;
		}
		
		var ids = datagridUsers.getSelectedRowIDs();
		if($('#filter-market').is(':checked'))
			type = 'market';
			
		if($('#filter-office').is(':checked'))
			type = 'office';
		var data = {"name":name,"type":type,"users":ids};

		$.ajax({
	        type:'post',
	        url: apiUrl+"share/creategroup",
	        dataType:"json",
	        headers:{"Api-Key":apiKey,"User":userid},
	        processData: false,
	        contentType: 'application/json',
	        data: JSON.stringify(data),
	        success:function(resp){
	        	var name = $('#group-name').val("");
	        	getGroups(parseInt(resp.groupId));
				window.alert('New Group Created');
	        }
	    });

	}

	//list the groups the user owns
	function getGroups(groupid){
		$('#group-selector')[0].options.length = 0;
		$('#group-selector').append($("<option class='corporation'></option>").attr("value", 0).text("Select Group"));
		$.ajax({
			type:'get',
			url: apiUrl+"share/groups",
			dataType:"json",
			headers:{"Api-Key":apiKey,"User":userid},
			success:function(data){
				$.each(data.groups, function(i, value) {
					$('#group-selector').append($("<option class="+value.type+"></option>").attr("value", value.id).attr("data-users",value.users.join()).text(value.name));
				});

				if(groupid != 0){
					$('#group-selector').val(groupid)
					$('#new-group-wrapper').hide();
					$('#edit-group-wrapper').show();
				}
			}
		});
	}

	//get the user list of items
	function getUserList(type){
		if($('#group-selector').find('option:selected').attr("class") != type){
			$('#group-selector').val(0);
			$('#new-group-wrapper').show();
			$('#edit-group-wrapper').hide();
		}
		datagridUsers.unSelectAll();

		$.ajax({
			type:'get',
			url: apiUrl+"share/users/"+type,
			dataType:"json",
			headers:{"Api-Key":apiKey,"User":userid},
			success:function(data){
				$.each(data.users,function(i,u){
					u.firstname = u.firstName;
					u.lastname  = u.lastName;
				});
				datagridUsers.populateDataGrid(data.users);
				selectedType = type;
			}
		});

		return true;
	}

	//save a group
	function saveGroup(){
		var len = datagridUsers.getSelectedRows().length;
		
		if(len == 0){
			window.alert('Please select some users');
			return;
		}
		var id 	  = $('#group-selector').val();
		var name  = $('option:selected','#group-selector').text();
		var users = datagridUsers.getSelectedRowIDs();
		var type  = 'corporation';
		if($('#filter-market').is(':checked'))
			type = 'market';			
		if($('#filter-office').is(':checked'))
			type = 'office';
		var data = {"groupId":id,"name":name,"type":type,"users":users};

		$.ajax({
			type:'post',
			url: apiUrl+"share/editgroup",
			dataType:"json",
			headers:{"Api-Key":apiKey,"User":userid},
			processData: false,
			contentType: 'application/json',
			data: JSON.stringify(data),
			success:function(resp){
				window.alert('Group Updated');
				getGroups();
			}
		});
	}

	//share the item with the users
	function shareItem(){
		var usersToShare = datagridUsers.getSelectedRows();
		
		if(usersToShare.length == 0){
			$('#nouserselected').show();
			$('#share-proposa-btn').prop('disabled', true);
			setTimeout(function(){
				$('#nouserselected').hide();
			$('#share-proposa-btn').prop('disabled', false);
			}, 5000)
			return;
		}

		var users           = [];
		var rows            = [];
		var proposalNames   = [];
		var userNames       = [];
			
		$.each(datagridUsers.getSelectedRows(), function(i,r){ 
    		    users.push(r.id);
    		    userNames.push(r.email);
    		});

		if(proposalShareType == 'Proposal'){
			var selectedPsls = datagridProposalManager.getSelectedRows();
			var url          = apiUrl+"share/shareproposals";
			
			
			$.each(selectedPsls,function(i,row){ 
    			    rows.push(row.id); 
    			    proposalNames.push(row.name);
    			});
			var data = {"users": users,"proposalIds":rows,"message":$("#share-message").val()};
            try{
    		    mixTrack("Proposal - Share",{"users": userNames,"proposalNames":proposalNames,"message":$("#share-message").val()});
                }
                catch(e){}

		} else {
			var selectedPsls = datagridSavedSearches.getSelectedRows();
			var url          = apiUrl+"share/sharesearch";
			$.each(selectedPsls,function(i,row){ rows.push(row.id); });
			var data = {"users": users,"search":rows,"message":$("#share-message").val()};
		}
		

		
		
		//RESET PROPOSAL FINDER FILTER
		clearProposalFilter();
				
		$.ajax({
			type:'post',
			url: url,
			dataType:"json",
			headers:{"Api-Key":apiKey,"User":userid},
			processData: false,
			contentType: 'application/json',
			data: JSON.stringify(data),
			success:function(resp){
				//window.alert('Message Sent');
				closeAllDialogs();
			}
		});
	}
	
	//remove group
	function removeGroup(){
		var id = $('#group-selector').val();
		$.ajax({
			type:'post',
			url: apiUrl+"share/deletegroup",
			dataType:"json",
			headers:{"Api-Key":apiKey,"User":userid},
			processData: false,
			contentType: 'application/json',
			data: JSON.stringify({"groupId":id}),
			success:function(resp){
				window.alert('Group Deleted');
				getGroups(0);
				$('#group-selector').val(0);
				selectGroup("");
			}
		});

	}	

	function resetSP(){
		$('#group-selector').val(0);
		$('#new-group-wrapper').css("display", "inline");
		$('#edit-group-wrapper').css("display", "none");
		datagridUsers.unSelectAll();
		getUserList('corporation');
	}
</script>









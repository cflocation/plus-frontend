	var users 				= {};
	var users2 				= [];
	var onhold 				= 0;
	var userEmail 			= '';
	var deleteusergroup 	= [];
	var selectedusergroup 	= [];

	$(document).ready(function(){
		$.ajax({
				type:'get',		
				url: 'https://apistg2.showseeker.com/user/info',
				headers:{"User":localStorage.getItem("userId"), "Api-Key":localStorage.getItem("apiKey")},
				success:function(resp){
					if(!resp.roles.UsersAdmin && !resp.roles.superAdmin){
						window.location.href = '../login.php?logout=true&app=admin';
					}
				}
		});
	});


	document.domain = "showseeker.com";

	$('.usr-accounts').change(function(){
		var usrid = selectedusergroup.indexOf($(this).val());
		if( usrid == -1 ){
			selectedusergroup.push($(this).val());
		}
		else{
			selectedusergroup.splice(usrid, 1);
		}
	});

	$('li.allUsersList i').on('click', function(e) {
		
		$(this).toggleClass('fa-square-o').toggleClass('fa-check-square-o');
		
		deleteusergroup = [];
	
		$('li.allUsersList').each(function(i,val){
			if($(this).find('i').hasClass('fa-check-square-o')){
				deleteusergroup.push(parseInt($(this).prop('id')));
			}
		});		
	
	});

	$('li.officeName').bind('click', function(e) {
	
		$('li.officeName').find('i').removeClass('fa-check-circle-o').addClass('fa-circle-o');
	    $(this).find('i').removeClass('fa-circle-o').addClass('fa-check-circle-o');
	
	});
	
	$('#defaultOfficeSelector').on('click',function(){
		$('li.officeName').each(function(i,val){
			if($(this).find('i').hasClass('fa-check-circle-o')){
				var officeId 	= String($(this).prop('id')).replace('officeId-','');
				var officeName 	= String($(this).text()).trim();	
				selectOffice(officeId,officeName);
			}	
		});	
	});


	var getUrlParameter = function getUrlParameter(sParam) {
	    var sPageURL = window.location.search.substring(1),
	        sURLVariables = sPageURL.split('&'),
	        sParameterName,
	        i;
	
	    for (i = 0; i < sURLVariables.length; i++) {
	        sParameterName = sURLVariables[i].split('=');
	
	        if (sParameterName[0] === sParam) {
	            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
	        }
	    }
	};


function selectOffice(officeId,officeName){
	$('#defaultoffice').val(officeId);
	$('#officeNameInfo').find('span.ui-btn-text').html(officeName);
	$.ajax({
			type:'get',		
			url: 'services/office.php?id='+officeId,
			success:function(data){
				$("#defaultoffice").val(officeId);
				$("#officeAddressInfo").html(data.address+', '+data.city+'<br />'+data.state+', '+data.zipcode);
				$("#both").popup("close");
			}
	});
}


function sendNotification(isNew) {
	var first 	= $("#first").val();
	var email 	= $("#email").val();
	var id 		= $("#id").val();
	var corpId  = $("#corporationId").val();
	$("#popupLogin").hide();

	var	url 		= 'services/email.php';
	var postData 	= {};
	
	if(!validateEmail(email)){
		$('#popupEmailAddress,#popupNotification').toggle();
		return;				
	}	
	
	postData.first	= first;
	postData.email 	= email;
	postData.id		= id;
	
	
	$.ajax({
            type:'post',
            url: url,
            dataType:"json",
            headers:{"Api-Key":apiKey,"User":userid},
            data: {"first": first, "email": email, "id": id},
            success:function(resp){
	         //resp.sentEmail
	         if(!isNew){
					$('#emailFormBody').hide();
					$('#emailFormConfirmation').show();
				}
				try{mixTrack("Admin - NewUserEmail",postData);}
				catch(e){}            
            }
	});
}	


function usersDelete() {

	var currentusers = parseInt($('#users-count').text());
	currentusers = currentusers - deleteusergroup.length;
		
	$.post("services/users.php", {
	
		eventtype: 'deletegroup',
		userids: deleteusergroup.join(","),
		adminid: userid

		}).done(function(data) {
			
			//confirmation message is updated and displayed
			$('#confirmation-msg-area').text(deleteusergroup.length +" deleted account(s)").toggle();
			
			//number of users in corporation, market or office is updated too
			$('#users-count').text(currentusers);

			//UNDO button is shown
			$('#user-delete-undo:hidden').toggle();
			
			//Hidding removed users from the view
			for(var j=0; j < deleteusergroup.length; j++) {
				$('#'+deleteusergroup[j]).css({'display':'none'});
			}

			//setting time out to remove the confirmation message
			setTimeout(function(){
					$('#confirmation-msg-area').text("");
					$('#confirmation-msg-area').toggle();
				}
				,5000);

			try{mixTrack("Admin - UserDelete",{'ids':deleteusergroup.join(",")});}
			catch(e){}
				
	});
}


	function undousersDelete() {
	
		//getting number of users displayed at the header of the widget
		var currentusers = parseInt($('#users-count').text());	
	
		$.post("services/users.php", {
			eventtype: 'undodelete',
			userids: deleteusergroup.join(",")
		})
		.done(function(data) {

			currentusers = currentusers + deleteusergroup.length;

			$('#user-delete-undo').toggle();
			$('#confirmation-msg-area').toggle();
			$('#confirmation-msg-area').text(deleteusergroup.length +" account(s) recovered");
			$('#users-count').text(currentusers);			
						
			for(var j=0; j < deleteusergroup.length; j++) {
				$('#'+deleteusergroup[j]).toggle();
				$('#'+deleteusergroup[j]).find('i').toggleClass('fa-check-square-o').toggleClass('fa-square-o');
			}

			//empty users array
			while(deleteusergroup.length > 0) {
				deleteusergroup.pop();
			}

			setTimeout(function(){
				$('#confirmation-msg-area').text("");
				$('#confirmation-msg-area').toggle();
			},5000);

			try{mixTrack("Admin - UserUndoDelete",{'ids':deleteusergroup.join(",")});}
			catch(e){}
		});       
	}



function preDeleteUser(){
	//gets the number of selected users to be deleted
	var cn = deleteusergroup.length;
	if(cn > 0){
		$('#deletedUsersNum').text(cn);	
		$('#popupDelete').popup('open');
	}
	else{
		console.log(0)
		$('#nonSelectedUsers').popup('open');
		$('#deletedUsersNum').text('');		
	}
}




	function userDelete(userid, corporationid,adminid) {
		deleteusergroup.push(userid);
		setTimeout(function(){
		
			$.post("services/users.php", {
				eventtype: 'delete',
				userid: userid,
				corporationid: corporationid,
				adminid: adminid
				}).done(function(data) {		
					try{mixTrack("Admin - UserDelete",{'userId':userid});}
					catch(e){}			        
					//$.mobile.back();
					//window.location = '/admin';
					$('#popupUndo').popup('open');
				});
		}, 100);			
	}


	function undoSingleUserDelete() {
		
		$.post("services/users.php", {
			eventtype: 'undodelete',
			userids: deleteusergroup.join(",")
		})
		.done(function(data) {
			//empty users array
			while(deleteusergroup.length > 0) {
				deleteusergroup.pop();
			}
			try{mixTrack("Admin - SingleUserUndoDelete",{'ids':deleteusergroup.join(",")});}
			catch(e){}
			$('#popupUndo').popup('close');
		});       
	}




function validateEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}



function openTutorial(url,appendUserId){
	var w = 1024;
	var h = 880;

	if(appendUserId){
		url  += '&userid='+userid;
	}

	LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
	TopPosition  = (screen.height) ? (screen.height-h)/2 : 0;
	window.open(url, "tutorialwindow", "location=no,status=yes,resizable=yes,scrollbars=yes,width="+w+",height="+h+",top="+TopPosition+",left="+LeftPosition);
}

function verifyServer(){    
    $.ajax({
        type:'post',
        url: "https://plusapi.showseeker.com/server",
        dataType:"json",
        processData: false,
        contentType: 'application/json',
        success:function(resp){
	        $('#frm-login,#fullwrapper').show();
	        $('#dbError').hide();	        
        },
        error:function(){
	        $('#frm-login,#fullwrapper').hide();
	        $('#dbError').show();
        }
    });
}	
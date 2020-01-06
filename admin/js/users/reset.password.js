function sendToken(id){
	var data 	= {};
	data.email 	= $('#email').val();
	data.userId = id;
	data.app 	= 'plus';
    $.ajax({
        type:'post',
        url: "https://plusapi.showseeker.com/user/passwordreset/sendlink",
        dataType:"json",
        processData: false,
        contentType: 'application/json',
        data: JSON.stringify(data),
        success:function(resp){
			if(resp.result === true){
				$('#okMessage').popup('open');
			}
			else{
				$('#errorMessage').popup('open');
			}
        }
    });
};
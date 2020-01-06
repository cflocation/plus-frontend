$(function(){
    if(window.location.href.indexOf("logout=true") !== -1){
        //its a forced logout
        localStorage.removeItem("userId");
        localStorage.removeItem("apiKey");
    } else if (localStorage.getItem("userId") !== null && localStorage.getItem("apiKey") !== null) {
        //User already logged in, redirect to app
        window.location.href = "index.php";
    }

    $('#frm-login').submit(function(){
        $("#login-error").hide();
        var data = {"email":$('#email').val(),"password":$('#password').val(),"location":1};
        $.ajax({
            type:'post',
            crossDomain: true,
            cache: false,
            url: "https://plusapi.showseeker.com/user/login",
            dataType:"json",
            processData: false,
            contentType: 'application/json',
            data: JSON.stringify(data),
            success:function(resp){
                if(resp.cnt==0){
                    $("#login-error").show();
                } else {
                    localStorage.setItem("userId", resp.id);
                    localStorage.setItem("apiKey", resp.apiKey);

					mixTrack('Plus Login');
					mixId ( resp.id );
					mixPeople({ "$email": data.email });

					window.location.href="index.php";
                }
            }
        });
        return false;
    });
});


function forgotPassword(){
    $("#success-message, #error-message").hide();

	var data = {};
	data.email = $('#email').val();

    $.ajax({
        type:'post',
        url: "https://plusapi.showseeker.com/user/forgotpassword",
        dataType:"json",
        processData: false,
        contentType: 'application/json',
        data: JSON.stringify(data),
        success:function(resp){
            if(resp.result){
                $("#success-message").show();
				$("#btn-submit,#email-address").hide();
				logEvent('Forgot Password',data);
            } else {
                $("#error-message").show();
				logEvent('Forgot Password Error',data);
            }
        }
    });
}
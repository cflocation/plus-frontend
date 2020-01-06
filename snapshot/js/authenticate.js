$(function(){
    if (localStorage.getItem("userId") !== null && localStorage.getItem("apiKey") !== null) {
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
                    window.location.href="index.php";
                }
            }
        });
        return false;
    });
});


function forgotPassword(){
    $("#success-message, #error-message").hide();

    $.ajax({
        type:'post',
        url: "https://plusapi.showseeker.com/user/forgotpassword",
        dataType:"json",
        processData: false,
        contentType: 'application/json',
        data: JSON.stringify({"email":$('#email').val()}),
        success:function(resp){
            if(resp.result){
                $("#success-message").show();
            } else {
                $("#error-message").show();
            }
        }
    });
}
var app = $('#login-session-js').data('app');
if (localStorage.getItem("userId") === null || localStorage.getItem("apiKey") === null) {
    window.location.href = "/login.php?logout=true&app="+app;
} else {
    $.ajax({
        type:'post',
        url: "/services/1.0/session.php",
        async:false,
        dataType:"json",
        data: {'userId':localStorage.getItem("userId"), 'apiKey':localStorage.getItem("apiKey")},
        success:function(resp){
            if(resp['id'] > 0){
                $('#spn-sess-usrname').text(resp.firstName);
                $('#spn-sess-corp').text(resp.corporation);

                if(app=='shows' || app=='movies'){
                    getUserMenu();
                }

                if(app=='breaks' && userIsSuperAdmin(resp['roles'])){
                    $('#menu-item-superadmin').show();
                }

            } else{
                window.location.href = "/login.php?logout=true&app="+app;
            }
        }
    });
}

function getUserMenu(){
    $.get('/services/1.0/user.menu.php',function(resp){
        $(resp).insertBefore('#li-logout-link');
    });
}

function userIsSuperAdmin(userRoles){
    for (var i = 0; i < userRoles.length; i++) {
        if(userRoles[i]['roleid']==2)
            return true;
    }

    return false;
}
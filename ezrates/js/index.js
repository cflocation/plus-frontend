	$(document).ready(function(){
    var apiUrl 		= "https://plusapi.showseeker.com/";
		$.ajax({
				type:'get',		
				url: apiUrl + 'user/info',
				headers:{"User":localStorage.getItem("userId"), "Api-Key":localStorage.getItem("apiKey")},
				success:function(resp){
					if(!resp.roles.RateCardManager){
						window.location.href = '../login.php?logout=true&app=plus';
					}
				}
		});
	});
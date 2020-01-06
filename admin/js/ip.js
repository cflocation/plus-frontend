function usrIp(e,mixData){
	$.ajax({
        	type:'get',		
			url: 'https://godownload.showseeker.com/ip.php',
			success:function(ip){
				mixData.userIp = ip;
				mixTrack(e, mixData);
			}});
}

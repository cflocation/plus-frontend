<p>
	<ul id="ssNewsLetters"></ul>
</p>

<script>
	
	var url = apiUrl +'newsletter'
	$.ajax({
        type:'get',
        url: url,
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
		success:function(resp){
			var  li;
			for(var r=0; r<resp.length; r++){
				li = $('<li>');
				li.html('<a class="newsLetterItem" onclick=loadNewsletter("'+resp[r].url+'","840","700")>'+resp[r].name+'</a>');
				li.appendTo($('#ssNewsLetters'));
			}
		}
	});

	function loadNewsletter(filename,w,h){
		LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
		TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
		window.open(filename, 'ShowSeeker Newsletter','width='+w+',top='+TopPosition+',left='+LeftPosition+', height='+h+', scrollbars=Yes').focus();
	}	
</script>
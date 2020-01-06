
<br>	
<div id="booksAvgInfo" style="font-weight: 500; line-height: 22px; padding-left: 30px;"></div>

<script>
	var monthMap 	= ['','JAN','FEB','MAR','APR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DEC'];	
	var Ls 			= {1: 'LO', 2:'LS', 3:'L1', 4:'L3', 5:'L7'};
	var bks = myEzRating.getRatings('books');

	for(var b=0; b<bks.length;b++){
		$bookContainer 	= $("<div>", {id: "book-"+bks[b].id, "class":"row"});
		$bookContainer.html('+ '+$('#dma-selector option:selected').text() +' &nbsp '+monthMap[bks[b].month]+' '+bks[b].year +'&nbsp '+bks[b].type +'&nbsp '+bks[b].serviceName +'&nbsp '+Ls[bks[b].kind]);
		$bookContainer.appendTo('#booksAvgInfo');
	}	
</script>
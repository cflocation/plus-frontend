<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script>
   <script>
	  google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Ranking', 'Title'],

			[1, WALKING DEAD],
			[2, TALKING DEAD],
			[3, ALASKAN BUSH PEOPLE],
			[4, GOLD RUSH],
			[5, WWE RAW]


        ]);

        var options = {
          chart: {
            title: 'Title',
            subtitle: 'Stuff',
          }
        };

         var chart = new google.visualization.ColumnChart(document.getElementById('ex0'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="ex0" style="width: 900px; height: 500px;"></div>
  </body>
</html>
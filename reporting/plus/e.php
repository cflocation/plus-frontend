<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1.1", {packages:["bar"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
		['Title',  { role: 'annotation' } , 'Weekday' , 'Rating'],
			["WALKING DEAD", "AMC", "Sun.", "12.3"],
			["NBA ALLSTAR SAT NIGHT", "TNT", "Sat.", "6.1"],
			["2015 NBA ALL STAR GAME", "TNT", "Sun.", "6.0"],
			["TALKING DEAD", "AMC", "Sun.", "5.1"],
			["GOLD RUSH", "DISCOVERY", "Fri.", "4.7"],
			["BAD HAIR DAY", "DISNEY", "Fri.", "4.0"],
			["ALASKAN BUSH PEOPLE", "DISCOVERY", "Fri.", "3.8"],
			["WWE RAW", "USA", "Mon.", "3.8"],
			["WWE RAW", "USA", "Mon.", "3.7"],
			["WWE RAW", "USA", "Mon.", "3.5"],
			["BETTER CALL SAUL", "AMC", "Mon.", "3.4"],
			["HAVES AND THE HAVE NOTS", "OWN", "Tue.", "3.4"],
			["NBA ALLSTAR TIP OFF", "TNT", "Sun.", "3.1"],
			["THE O'REILLY FACTOR", "FOX NEWS", "Mon.", "3.1"],
			["BIG BANG THEORY", "TBS", "Sat.", "3.1"],
			["HALL ORIGINAL MOVIE", "HALLMARK", "Sat.", "3.0"],
			["THE O'REILLY FACTOR", "FOX NEWS", "Wed.", "3.0"],
			["THE O'REILLY FACTOR", "FOX NEWS", "Tue.", "2.9"],
			["THE O'REILLY FACTOR", "FOX NEWS", "Thu.", "2.9"],
			["BAD HAIR DAY", "DISNEY", "Sat.", "2.9"],
			["TOY STORY 3", "DISNEY", "Thu.", "2.9"],
			["FAMILY GUY", "ADSM", "Wed.", "2.9"],
			["REAL HOUSEWIVES ATLANTA", "BRAV", "Sun.", "2.9"],
			["BIG BANG THEORY", "TBS", "Sat.", "2.8"],
			["FX MOVIE PRIME", "FX", "Sat.", "2.8"],
			["FX MOVIE WKND AFTERNOON", "FX", "Sun.", "2.8"]
        ]);

        var options = {
          chart: {
            title: 'Weekly Shows Rating',
            subtitle: 'Week # 5 - 2015',


			hAxis: {title: "Years" , direction:-1, slantedText:true, slantedTextAngle:90 }

			
			}
        };


        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
			{ calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },

			3]);
		data.sort([{column: 3, desc:true}]);



        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(view, options);
      }
    </script>
  </head>
  <body>
    <div id="columnchart_material" style="width: 900px; height: 500px;"></div>
  </body>
</html>
            
    <script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script>
    
<script>      
    

google.load('visualization', '1.1', {packages: ['corechart']});


    google.setOnLoadCallback(drawChart);

    function drawChart() {

var data = google.visualization.arrayToDataTable([
		['Title',  'callsign', 'Weekday' , 'Rating'],
			["WALKING DEAD", "AMC", "Sun.", 12.3],
			["NBA ALLSTAR SAT NIGHT", "TNT", "Sat.", 6.1],
			["2015 NBA ALL STAR GAME", "TNT", "Sun.", 6.0],
			["TALKING DEAD", "AMC", "Sun.",5.1],
			["GOLD RUSH", "DISCOVERY", "Fri.", 4.7],
			["BAD HAIR DAY", "DISNEY", "Fri.", 4.0],
			["ALASKAN BUSH PEOPLE", "DISCOVERY", "Fri.", 3.8],
			["WWE RAW", "USA", "Mon.", 3.8],
			["WWE RAW", "USA", "Mon.", 3.7],
			["WWE RAW", "USA", "Mon.", 3.5],
			["BETTER CALL SAUL", "AMC", "Mon.",3.4],
			["HAVES AND THE HAVE NOTS", "OWN", "Tue.", 3.4],
			["NBA ALLSTAR TIP OFF", "TNT", "Sun.", 3.1],
			["THE O'REILLY FACTOR", "FOX NEWS", "Mon.", 3.1],
			["BIG BANG THEORY", "TBS", "Sat.", 3.1],
			["HALL ORIGINAL MOVIE", "HALLMARK", "Sat.", 3.0],
			["THE O'REILLY FACTOR", "FOX NEWS", "Wed.", 3.0],
			["THE O'REILLY FACTOR", "FOX NEWS", "Tue.", 2.9],
			["THE O'REILLY FACTOR", "FOX NEWS", "Thu.", 2.9],
			["BAD HAIR DAY", "DISNEY", "Sat.", 2.9],
			["TOY STORY 3", "DISNEY", "Thu.", 2.9],
			["FAMILY GUY", "ADSM", "Wed.", 2.9],
			["REAL HOUSEWIVES ATLANTA", "BRAV", "Sun.", 2.9],
			["BIG BANG THEORY", "TBS", "Sat.", 2.8],
			["FX MOVIE PRIME", "FX", "Sat.", 2.8],
			["FX MOVIE WKND AFTERNOON", "FX", "Sun.", 2.8],
			["K.C. UNDERCOVER", "DISNEY", "Sun.", 2.8],
			["BIG BANG THEORY", "TBS", "Sat.", 2.7],
			["ALASKA: THE LAST FRONTIER", "DISCOVERY", "Sun.", 2.7],
			["FAMILY GUY", "ADSM", "Wed.", 2.7],
			["AMERICAN PICKERS", "HIST", "Wed.", 2.7],
			["AMERICAN DAD", "ADSM", "Wed.", 2.6],
			["BIG BANG THEORY", "TBS", "Sat.", 2.6],
			["FAMILY GUY", "ADSM", "Thu.", 2.6],
			["WALKING DEAD", "AMC", "Sun.", 2.6],
			["WWE SMACKDOWN", "SYFY", "Thu.", 2.6],
			["KELLY FILE", "FOX NEWS", "Thu.", 2.6],
			["BIG BANG THEORY", "TBS", "Tue.", 2.6],
			["SWAMP PEOPLE", "HIST", "Mon.", 2.6],
			["TOY STORY 3", "DISNEY", "Fri.", 2.6],
			["LOVE AND HIP HOP 5", "VH1", "Mon.",2.5],
			["LIV AND MADDIE", "DISNEY", "Sun.", 2.5],
			["THE O'REILLY FACTOR", "FOX NEWS", "Fri.", 2.5],
			["FAMILY GUY", "ADSM", "Thu.", 2.5],
			["DUCK DYNASTY", "A&E", "Wed.", 2.5],
			["BAD HAIR DAY", "DISNEY", "Sun.", 2.5],
			["FIXER UPPER", "HGTV", "Tue.", 2.5],
			["KELLY FILE", "FOX NEWS", "Mon.", 2.5],
			["I DIDNT DO IT", "DISNEY", "Sun.", 2.5],
			["BIG BANG THEORY", "TBS", "Tue.", 2.4]
        ]);

      var options = {
        annotations: {
          alwaysOutside: true,
			style: 'line',
            textStyle: {
            fontSize: 12,
            color: '#000',
            auraColor: 'none'
          }
        },
        width: 1500,
        height: 700,
        hAxis: {
          slantedText:true, 
		  slantedTextAngle:90,
		  direction:-1,
		  textStyle: {italic: false,  fontSize: 10}

        },
        vAxis: {
          title: 'Rating (scale of 1-15)'
        }

      };

        var view = new google.visualization.DataView(data);
        view.setColumns([0, 3]);
		data.sort([{column: 3, desc:true}]);


      var chart = new google.visualization.ColumnChart(document.getElementById('ex5'));
      chart.draw(view, options);
    }
</script>

<div id="ex5"></div>
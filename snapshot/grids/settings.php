<?php 
	date_default_timezone_set('America/Los_Angeles');
	
	$userid			= $_POST["userid"];
	$apiKey			= $_POST["apiKey"];
	$marketid		= $_POST["marketid"];
	$zoneid			= $_POST["zones"];
	$station		= $_POST["station"];
	$startDate		= $_POST["startDate"];
	$endDate		= $_POST["endDate"];
	$sTime			= $_POST["sTime"];
	$eTime			= $_POST["eTime"];
	$tzmapped		= $_POST["timezone"];

	include_once('services/database.php');
	include_once('classes/Grid.php');
	include_once('classes/Programming.php');
	include_once('classes/Broacastdates.php');
	
	$localdatetime = date('m/d/Y');

	//--- GRACENOTE LAST DAY OF PROGRAMMING
	$maxdate 		= ((strtotime($localdatetime)+(56*60*60*24)) - strtotime($startDate))/(60*60*24);

	//--- EZGRIDS CLASS
	$networkGrid 	= new Grid($userid,$con);
	$progamming 	= new Programming();
	$bcDates 		= new Broacastdates();	
	$uuid 			= uniqid();
		

	//--- VERIFY THAT STATIONS CORRESPOND WITH THE ZONE
	$isvalid			= 1;
	
	//--- GLOBAL DAYS OF THE WEEK VARIABLE 
	$weekDays 		= "Mon,Tue,Wed,Thu,Fri,Sat,Sun";

	//--- PROGRAM TYPES
	$programtypes 	= explode(",","Sports Events Live,Sports NonEvents Live,Premieres,Finales,Movies,Movie Premieres,Only New");
		
	
	//--- FORMATTING DATES
	$startDate 		= date("m/d/Y", strtotime($startDate));


	$tempSDate 		= date_create($startDate);
	$tempEDate 		= date_create($endDate);	
	$diff 			= date_diff($tempSDate,$tempEDate);	
	
	if($diff->days > 92){
		$endDate 	= date('m/d/Y', strtotime($startDate. ' + 92 days'));
	}	

	//--- START TIME  
	$gridSTime 		= date("H:i:s",strtotime(str_replace("24:00","00:00",$sTime)));
	
	//--- END TIME
	$gridETime 		= date("H:i:s",strtotime(str_replace("00:00","23:59",$eTime)));
	
	
	//--- SIDE HOUR BARS
	$hours 			= $networkGrid->getHours();	

	//--- CREATES THE TIME RULER/SIDE TIME LINE)
	$st 				= strtotime($sTime);
	$et 				= strtotime($eTime);
	$timeRuler 		= '';
	$rulerHeight	= '36';
	
	do {
		$timeRuler 		.= '<div class="timeCell">'.date('h:i A',$st).'</div>';
		$st 				 = $st+(30*60);
		$rulerHeight 	+= 36;
	} while ($et > $st);

	$st					= strtotime($startDate);
	$et					= strtotime($endDate);
	$iniDate				= (date('w',$st)==1)?date('Y-m-d',$st):date('Y-m-d',strtotime('previous monday',$st));
	$finalDate			= (date('w',$et)==0)?date('Y-m-d',$et):date('Y-m-d',strtotime('next sunday',$et));			
	$programsbyweek	= array();
	$allweeks 			= array();
	$begin 				= new DateTime( $iniDate );
	$end 					= new DateTime( $finalDate );
	$interval 			= DateInterval::createFromDateString('7 day');
	$period 				= new DatePeriod($begin, $interval, $end);


	//--- NETWORK SCHEDULES --->	
	foreach ( $period as $dt ){
		$fromDate			= $dt->format( "Y-m-d 00:00:00" );
		$toDate				= date('Y-m-d 23:59:59',strtotime($fromDate.' next sunday'));	
		$allweeks[]			= strtotime($fromDate);		
		$programsbyweek[]	= $progamming->getSchedFromSolr($fromDate,$toDate,$station,$gridSTime,$gridETime,$tzmapped);
	}


	$weekrangeStart 		=	$bcDates->getMonday($startDate);
	$weekrangeEnd			=	$bcDates->getSunday($endDate);
	$numberofWeeks			=   $bcDates->numberOfWeeks($weekrangeStart,$weekrangeEnd);
	$wRange 				=	array("sDate"=>$weekrangeStart,"eDate"=>$weekrangeEnd,"numwks"=>$numberofWeeks,"numberofWeeks"=>$numberofWeeks);
	$ii 					=	0;

	//--- IF NOT SCHEDULES WERE FOUND DISPLAY MESSAGE
	if(count($programsbyweek[0]) <= 0){
		include_once('lib/sqift_required.php');
	}
?>
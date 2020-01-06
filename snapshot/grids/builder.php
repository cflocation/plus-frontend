<div  id="boxBody" class="body">

<?php for($i=0; $i<=$numberofWeeks; $i++){
	$m = 0;
	$x= 0;
?>
	<!-- GRID BY WEEK -->	
	<div id="<?php print('wk'.(string)($i).'');?>"  class="parent">
	
		<!-- HEADER (DAYS OF THE WEEK) -->
		<div id="header">
			<?php include 'includes/daysofweektop.php';?>
		</div>
		
		<!-- GRID CONTENT -->
		<div id="innerbody" style="clear:both;">
		
			<!-- LEFT TIME RULER COLUMN (TIMELINE SIDE BAR) -->
			<div id="lTime" class="timeRuler">
				<?php print_r($timeRuler);?>
			</div>
			
			<!-- SCHEDULES -->
			<div id="<?php print('outerContainer'.(string)($i).'');?>" class="cellContainer">
				<div class="innerContainer"><?php 
					
					
					for($k=0; $k<=6; $k++){
						$add_days 	= 7*($i)+$k;
						$cday 		= strtotime($wRange['sDate']) + (24*3600*$add_days);
						$y			= 0;	
						$programs 	= $programsbyweek[$i];
						$cPrograms	= count($programs[$k]);
	
						foreach($programs[$k] as $key => $program){ 
							$m++;
							$tmpTime = explode(' ',$program['tz_start_'.$tzmapped])[1];
							
							$datetime1 	= strtotime('2000-01-01 '.$sTime);
							$datetime2 	= strtotime('2000-01-01 '.$tmpTime);
							$interval 	= intval($datetime2-$datetime1)/60;
							$h 			= floor(88*$interval)/60;									
							if($key == 0){
								if($interval > 0){?>
							
									<div class="programCell" style="left:<?php print($x);?>px; top:<?php print($y);?>px;  height:<?php print($h);?>px;">
										<div class="cellText"></div>
									</div><?php
									$y = $y + $h;
								}

								$h = floor(88*$program['duration'])/60;?>								

								<div class="programCell" style="left:<?php print($x);?>px; top:<?php print($y);?>px;  height:<?php print($h);?>px;">
									<div class="cellText <?php print($program['class']);?>">		
										<?php 
											print('<input class=showseekerprogram id='.$program['id'].'-'.$zoneid.' type=checkbox  value='.$program['id'].'" name="'.$program['premierefinale'].'|'.$program['isnew'].'|'.$program['genre1'].'|'.$program['live'].'">');
										 	print($progamming->programtitle($i,$k,$m,$program['premierefinale'],$program['isnew'],$program['descembed'],$program['epititle'],$program['title'],$program['genre1'],$program['live'],$x+50,$y+120,$station,$program['id']));
										?>	
									</div>
								</div>
							<?php							
							}
								
							if($cPrograms-1 > $key && $key> 0){

								if($program['tz_start_'.$tzmapped] != $programs[$k][$key-1]['tz_end_'.$tzmapped]){									
									$datetime1 	= strtotime($programs[$k][$key-1]['tz_end_'.$tzmapped]);
									$datetime2 	= strtotime($program['tz_start_'.$tzmapped]);
									$interval 	= intval($datetime2-$datetime1)/60;
									$h 			= floor(88*$interval)/60;?>
									<div class="programCell" style="left:<?php print($x);?>px; top:<?php print($y);?>px;  height:<?php print($h);?>px;">
										<div class="cellText"></div>
									</div>							
									<?php
									$y = $y + $h;									
								}
								$h = floor(88*$program['duration'])/60;?>
								<div class="programCell" style="left:<?php print($x);?>px; top:<?php print($y);?>px;  height:<?php print($h);?>px;">
									<div class="cellText <?php print($program['class']);?>">		
										<?php 
											print('<input class=showseekerprogram id='.$program['id'].'-'.$zoneid.' type=checkbox  value='.$program['id'].'" name="'.$program['premierefinale'].'|'.$program['isnew'].'|'.$program['genre1'].'|'.$program['live'].'">');
										 	print($progamming->programtitle($i,$k,$m,$program['premierefinale'],$program['isnew'],$program['descembed'],$program['epititle'],$program['title'],$program['genre1'],$program['live'],$x+50,$y+120,$station,$program['id']));
										?>	
									</div>
								</div><?php
							}
							else if( $key> 0){
								
								
								$datetime1 	= strtotime('2000-01-01 '.explode(' ',$program['tz_start_'.$tzmapped])[1]);
								$datetime2 	= strtotime('2000-01-01 '.$eTime);
								$interval 	= intval($datetime2-$datetime1)/60;
								$h 			= floor(88*$interval)/60;?>								
								<div class="programCell" style="left:<?php print($x);?>px; top:<?php print($y);?>px;  height:<?php print($h);?>px;">
									<div class="cellText <?php print($program['class']);?>">		
										<?php 
											print('<input class=showseekerprogram id='.$program['id'].'-'.$zoneid.' type=checkbox  value='.$program['id'].'" name="'.$program['premierefinale'].'|'.$program['isnew'].'|'.$program['genre1'].'|'.$program['live'].'">');
										 	print($progamming->programtitle($i,$k,$m,$program['premierefinale'],$program['isnew'],$program['descembed'],$program['epititle'],$program['title'],$program['genre1'],$program['live'],$x+50,$y+120,$station,$program['id']));
										?>	
									</div>
								</div><?php							
							}
							$y = $y + $h;
						}
						$x = $x + 110;
					}
					$y = 25; ?>
				</div>	
			</div>
		
		
			<!-- RIGHT TIME COLUMN -->
			<div id="rTime" class="timeRuler">
				<?php print_r($timeRuler);?>
			</div>
		</div>
		
		<!-- 	FOOTER --->
		<div id="gridfooter" style="clear:both;">
			<?php include 'includes/daysofweekb.php';?>
		</div>

			<BR style="clear: both;"><BR style="clear: both;">
	</div>	
<?php 
	
	}
?>
</div>
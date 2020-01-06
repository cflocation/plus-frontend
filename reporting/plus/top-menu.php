<?php
	$page = basename($_SERVER['PHP_SELF']); 
?>
<div class="fixed">
<nav class="top-bar" data-topbar="" role="navigation">
  <ul class="title-area">
    <li class="name"><h1><a href="index.php">ShowSeeker Search Trends</a></h1></li>
      <li class="divider"></li>
    <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
  </ul>
<section class="top-bar-section">
 
<?php if ($page != "trends_weekly.php" ) { ?>	
<?php if ($page != "cloud.php" ) { ?>	
<?php if ($page != "users.php" ) { ?>	
<?php if ($page != "cloud_info.php" ) { ?>	
	<ul class="left">
      <li class="has-form">
        <div class="row collapse">
          <div class="large-6 small-6 columns">
            <input type="text" name="datefrom" id="datefrom" style="width:100px;" placeholder="<?php echo $df;?>">
          </div>
          <div class="large-6 small-6 columns">
            <input type="text" name="dateto" id="dateto" style="width:100px;" placeholder="<?php echo $dt;?>"> 
          </div>
        </div>
      </li>
      <li class="has-form">
        <a class="button" href="trends_monthly.php">Generate Search Trends</a>
      </li>
    </ul>
<?php }?>
<?php }?>
<?php }?>
<?php }?>

    <ul class="right">


      <li class="has-dropdown not-click"><a href="#">Global Trends</a>
        <ul class="dropdown"><li class="title back js-generated"><h5><a href="javascript:void(0)">Back</a></h5></li><li class="parent-link show-for-small"><a class="parent-link js-generated" href="#"></a></li>
		  <li><a href="company_map.php" target="_blank">Offices & Users by location</a></li>
		  <li><a href="cloud.php">Search Cloud</a></li>
		  <li><a href="../dashboard/site_activity_2015.php" target="_blank">Site Activity - 2015</a></li>
		  <li><a href="../dashboard/site_activity_2016.php" target="_blank">Site Activity - 2016</a></li>
		</ul>
      </li>

      <li class="has-dropdown not-click"><a href="#">Trends by User</a>
        <ul class="dropdown"><li class="title back js-generated"><h5><a href="javascript:void(0)">Back</a></h5></li><li class="parent-link show-for-small"><a class="parent-link js-generated" href="#"></a></li>
		  <li><a href="users.php?s=1">Total Searches</a></li>
		  <li><a href="users.php?s=2">Logins</a></li>
		  <li><a href="users.php?s=3">Proposals Created</a></li>
		  <li><a href="users.php?s=6">Proposals Copied</a></li>
		  <li><a href="users.php?s=9">Proposals Downloaded</a></li>
		  <li><a href="users.php?s=5">Proposals Emailed</a></li>
		  <li><a href="users.php?s=8">Proposals Merged</a></li>
		  <li><a href="users.php?s=7">Proposals Renamed</a></li>  
		  <li><a href="users.php?s=4">Proposals Shared</a></li> 
		  <li><a href="users.php?s=11">Tutorials Watched</a></li> 
		</ul>
      </li>
      <li class="has-dropdown not-click"><a href="#">Searches by Month</a>
        <ul class="dropdown"><li class="title back js-generated"><h5><a href="javascript:void(0)">Back</a></h5></li><li class="parent-link show-for-small"><a class="parent-link js-generated" href="#"></a></li>
      <li class="has-dropdown not-click"><a href="#">2014</a>
        <ul class="dropdown"><li class="title back js-generated"><h5><a href="javascript:void(0)">Back</a></h5></li><li class="parent-link show-for-small"><a class="parent-link js-generated" href="#"></a></li>
		  <li><a href="trends_monthly.php?m=1&y=2014">January 2014</a></li>
		  <li><a href="trends_monthly.php?m=2&y=2014">February 2014</a></li>
		  <li><a href="trends_monthly.php?m=3&y=2014">March 2014</a></li>
		  <li><a href="trends_monthly.php?m=4&y=2014">April 2014</a></li>
		  <li><a href="trends_monthly.php?m=5&y=2014">May 2014</a></li>
		  <li><a href="trends_monthly.php?m=6&y=2014">June 2014</a></li>
		  <li><a href="trends_monthly.php?m=7&y=2014">July 2014</a></li>
		  <li><a href="trends_monthly.php?m=8&y=2014">August 2014</a></li>
		  <li><a href="trends_monthly.php?m=9&y=2014">September 2014</a></li>
		  <li><a href="trends_monthly.php?m=10&y=2014">October 2014</a></li>
		  <li><a href="trends_monthly.php?m=11&y=2014">November 2014</a></li>
		  <li><a href="trends_monthly.php?m=12&y=2014">December 2014</a></li>
		</ul>
      </li>
      <li class="has-dropdown not-click"><a href="#">2015</a>
        <ul class="dropdown"><li class="title back js-generated"><h5><a href="javascript:void(0)">Back</a></h5></li><li class="parent-link show-for-small"><a class="parent-link js-generated" href="#"></a></li>
		  <li><a href="trends_monthly.php?m=1&y=2015">January 2015</a></li>
		  <li><a href="trends_monthly.php?m=2&y=2015">Febuary 2015</a></li>
		  <li><a href="trends_monthly.php?m=3&y=2015">March 2015</a></li>
		  <li><a href="trends_monthly.php?m=4&y=2015">April 2015</a></li>
		  <li><a href="trends_monthly.php?m=5&y=2015">May 2015</a></li>
		  <li><a href="trends_monthly.php?m=6&y=2015">June 2015</a></li>
		  <li><a href="trends_monthly.php?m=7&y=2015">July 2015</a></li>
		  <li><a href="trends_monthly.php?m=8&y=2015">August 2015</a></li>
		  <li><a href="trends_monthly.php?m=9&y=2015">September 2015</a></li>
		  <li><a href="trends_monthly.php?m=10&y=2015">October 2015</a></li>
		  <li><a href="trends_monthly.php?m=11&y=2015">November 2015</a></li>
		  <li><a href="trends_monthly.php?m=12&y=2015">December 2015</a></li>
		</ul>
      </li>
		<li class="has-dropdown not-click"><a href="#">2016</a>
        <ul class="dropdown"><li class="title back js-generated"><h5><a href="javascript:void(0)">Back</a></h5></li><li class="parent-link show-for-small"><a class="parent-link js-generated" href="#"></a></li>
		  <li><a href="trends_monthly.php?m=1&y=2016">January 2016</a></li>
		  <li><a href="trends_monthly.php?m=2&y=2016">Febuary 2016</a></li>
		  <li><a href="trends_monthly.php?m=3&y=2016">March 2016</a></li>
		  <li><a href="trends_monthly.php?m=4&y=2016">April 2016</a></li>
		  <li><a href="trends_monthly.php?m=5&y=2016">May 2016</a></li>
		  <li><a href="trends_monthly.php?m=6&y=2016">June 2016</a></li>
		  <li><a href="trends_monthly.php?m=7&y=2016">July 2016</a></li>
		  <li><a href="trends_monthly.php?m=8&y=2016">August 2016</a></li>
		  <li><a href="trends_monthly.php?m=9&y=2016">September 2016</a></li>
		  <li><a href="trends_monthly.php?m=10&y=2016">October 2016</a></li>
		  <li><a href="trends_monthly.php?m=11&y=2016">November 2016</a></li>
		  <li><a href="trends_monthly.php?m=12&y=2016">December 2016</a></li>
		</ul>
      </li>	


        </ul>
      </li>
	<li class="has-dropdown not-click"><a href="#">Searches by Week</a>
        <ul class="dropdown"><li class="title back js-generated"><h5><a href="javascript:void(0)">Back</a></h5></li><li class="parent-link show-for-small"><a class="parent-link js-generated" href="#"></a></li>
			  <li class="has-dropdown"><a href="#">January</a>
							<ul class="dropdown">
							  <li><a href="trends_weekly.php?w=1">Week 1</a></li>
							  <li><a href="trends_weekly.php?w=2">Week 2</a></li>
							  <li><a href="trends_weekly.php?w=3">Week 3</a></li>
							  <li><a href="trends_weekly.php?w=4">Week 4</a></li>
							</ul>
			 </li>
			  <li class="has-dropdown"><a href="#">February</a>
							<ul class="dropdown">
							  <li><a href="trends_weekly.php?w=5">Week 5</a></li>
							  <li><a href="trends_weekly.php?w=6">Week 6</a></li>
							  <li><a href="trends_weekly.php?w=7">Week 7</a></li>
							  <li><a href="trends_weekly.php?w=8">Week 8</a></li>
							</ul>
			 </li>
			  <li class="has-dropdown"><a href="#">March</a>
							<ul class="dropdown">
							  <li><a href="trends_weekly.php?w=9">Week 9</a></li>
							  <li><a href="trends_weekly.php?w=10">Week 10</a></li>
							  <li><a href="trends_weekly.php?w=11">Week 11</a></li>
							  <li><a href="trends_weekly.php?w=12">Week 12</a></li>
							  <li><a href="trends_weekly.php?w=13">Week 13</a></li>
							</ul>
			 </li>
			  <li class="has-dropdown"><a href="#">April</a>
							<ul class="dropdown">
							  <li><a href="trends_weekly.php?w=14">Week 14</a></li>
							  <li><a href="trends_weekly.php?w=15">Week 15</a></li>
							  <li><a href="trends_weekly.php?w=16">Week 16</a></li>
							  <li><a href="trends_weekly.php?w=17">Week 17</a></li>
							</ul>
			 </li>
			  <li class="has-dropdown"><a href="#">May</a>
							<ul class="dropdown">
							  <li><a href="trends_weekly.php?w=18">Week 18</a></li>
							  <li><a href="trends_weekly.php?w=19">Week 19</a></li>
							  <li><a href="trends_weekly.php?w=20">Week 20</a></li>
							  <li><a href="trends_weekly.php?w=21">Week 21</a></li>
							  <li><a href="trends_weekly.php?w=22">Week 22</a></li>
							</ul>
			 </li>
			  <li class="has-dropdown"><a href="#">June</a>
							<ul class="dropdown">
							  <li><a href="trends_weekly.php?w=23">Week 23</a></li>
							  <li><a href="trends_weekly.php?w=24">Week 24</a></li>
							  <li><a href="trends_weekly.php?w=25">Week 25</a></li>
							  <li><a href="trends_weekly.php?w=26">Week 26</a></li>
							</ul>
			 </li>
			  <li class="has-dropdown"><a href="#">July</a>
							<ul class="dropdown">
							  <li><a href="trends_weekly.php?w=27">Week 27</a></li>
							  <li><a href="trends_weekly.php?w=28">Week 28</a></li>
							  <li><a href="trends_weekly.php?w=29">Week 29</a></li>
							  <li><a href="trends_weekly.php?w=30">Week 30</a></li>
							</ul>
			 </li>
			  <li class="has-dropdown"><a href="#">August</a>
							<ul class="dropdown">
							  <li><a href="trends_weekly.php?w=31">Week 31</a></li>
							  <li><a href="trends_weekly.php?w=32">Week 32</a></li>
							  <li><a href="trends_weekly.php?w=33">Week 33</a></li>
							  <li><a href="trends_weekly.php?w=34">Week 34</a></li>
							  <li><a href="trends_weekly.php?w=35">Week 35</a></li>
							</ul>
			 </li>
			  <li class="has-dropdown"><a href="#">September</a>
							<ul class="dropdown">
							  <li><a href="trends_weekly.php?w=36">Week 36</a></li>
							  <li><a href="trends_weekly.php?w=37">Week 37</a></li>
							  <li><a href="trends_weekly.php?w=38">Week 38</a></li>
							  <li><a href="trends_weekly.php?w=39">Week 39</a></li>
							</ul>
			 </li>
			  <li class="has-dropdown"><a href="#">October</a>
							<ul class="dropdown">
							  <li><a href="trends_weekly.php?w=40">Week 40</a></li>
							  <li><a href="trends_weekly.php?w=41">Week 41</a></li>
							  <li><a href="trends_weekly.php?w=42">Week 42</a></li>
							  <li><a href="trends_weekly.php?w=43">Week 43</a></li>
							</ul>
			 </li>
			  <li class="has-dropdown"><a href="#">November</a>
							<ul class="dropdown">
							  <li><a href="trends_weekly.php?w=44">Week 44</a></li>
							  <li><a href="trends_weekly.php?w=45">Week 45</a></li>
							  <li><a href="trends_weekly.php?w=46">Week 46</a></li>
							  <li><a href="trends_weekly.php?w=47">Week 47</a></li>
							  <li><a href="trends_weekly.php?w=48">Week 48</a></li>
							</ul>
			 </li>
			  <li class="has-dropdown"><a href="#">December</a>
							<ul class="dropdown">
							  <li><a href="trends_weekly.php?w=49">Week 49</a></li>
							  <li><a href="trends_weekly.php?w=50">Week 50</a></li>
							  <li><a href="trends_weekly.php?w=51">Week 51</a></li>
							  <li><a href="trends_weekly.php?w=52">Week 52</a></li>
							</ul>
			 </li>
        </ul>
      </li>
      <li class="divider"></li>
    </ul>
  </section></nav>
  </div>

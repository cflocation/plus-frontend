<?php
session_start();
include_once('../../../config/database.php');
$userid = $_SESSION['userid'];
$corporationid = $_SESSION['corporationid'];
$networksArr = getNetworks($corporationid,$userid);
?>
<style type="text/css">
	.c_tooltip > i {
		font-size: 0.77778rem;
	}
	.c_tooltip{
	    display: inline;
	    position: relative;
	}
	.c_tooltip:hover:after{
	    background: #333;
	    background: rgba(0,0,0,.8);
	    border-radius: 5px;
	    bottom: 26px;
	    color: #fff;
	    content: attr(tiptitle);
	    left: 20%;
	    padding: 15px 15px;
	    position: absolute;
	    z-index: 98;
	    width: 760px;
	    text-align: left;
	    font-size: 0.77778rem;
	    font-weight: normal;
	    line-height: 1;
	}
	.c_tooltip:hover:before{
	    border: solid;
	    border-color: #333 transparent;
	    border-width: 6px 6px 0 6px;
	    bottom: 20px;
	    content: "";
	    left: 50%;
	    position: absolute;
	    z-index: 99;
	}
</style>
<div id="custom-breakrule-wizard" style="">
    <section id="custom-breakrule-wizard-step1" style="display:block;">
  		<div class="custom-breakrule-wizard-stepcont">
	  		<h1 class="custom-rule-wizard-step-title" id="custom-rule-wizard-step1-title">What would you want to do? </h1>
	  		<ol id="selectable-step1">
				<li class="ui-widget-content" data-task="1">New Custom rule based on date and time <a tiptitle="Select this to build a rule based on a fixed date and time. Start and end date and time ranges are needed in this type of rule." class="c_tooltip"><i class="fa fa-info-circle fa-lg"></i></a></li>
				<li class="ui-widget-content" data-task="2">New Custom rule based on show title <a tiptitle="Select this to build a rule based on a title. Date and time ranges are not mandatory and can be left blank for rules that will be indefinite." class="c_tooltip"><i class="fa fa-info-circle fa-lg"></i></a></li>
			</ol>
		</div>
		<div class="custom-rule-wizard-nav-buttons">
			<button href="#" onclick="goToStep2();" class="button radius">Next</button>
		</div>
	</section>

	<section id="custom-breakrule-wizard-step2" style="display:none;">
		<div class="custom-breakrule-wizard-stepcont">
			<h1 class="custom-rule-wizard-step-title" id="custom-rule-wizard-step2-title">Select the network(s)</h1>
			<ol id="selectable-step2">
				<?php foreach ($networksArr as $net) { ?>
					<li class="ui-widget-content" data-networkid="<?php print $net['id']; ?>">
						<img src="<?php print $net['logofullpath']; ?>"/><br/><?php print $net['callsign']; ?>
					</li>
				<?php } ?>
			</ol>
		</div>
		<div class="custom-rule-wizard-nav-buttons">
			<button href="#" onclick="$('#custom-breakrule-wizard-step1,#custom-breakrule-wizard-step2').toggle();" class="button radius">Previous</button>
			<button href="#" onclick="showStep3();" class="button radius">Next</button>
		</div>
	</section>

	<section id="custom-breakrule-wizard-step3" style="display:none;">
		<div class="custom-breakrule-wizard-stepcont">
		<h1 class="custom-rule-wizard-step-title" id="custom-rule-wizard-step3-title">Select the network instance(s)</h1>
		<ol id="selectable-step3">
		</ol>		
		</div>
		<div class="custom-rule-wizard-nav-buttons">
			<button href="#" onclick="$('#custom-breakrule-wizard-step2,#custom-breakrule-wizard-step3').toggle();" class="button radius">Previous</button>
			<button href="#" onclick="showStep4();" class="button radius">Next</button>
		</div>
	</section>
	
	<section id="custom-breakrule-wizard-titlestep" style="display:none;">
		<div class="custom-breakrule-wizard-stepcont">
			<h1 class="custom-rule-wizard-step-title" id="custom-rule-wizard-titlestep-title">Title</h1>
			<div class="row padder">
			  <div class="small-11">
			    <div class="row collapse">
			      <div class="small-2 columns">
			        <label for="custom-rule-wizard-title" class="right inline">Title <a tiptitle="The show title to apply the custom rule to. The title is mandatory" class="c_tooltip"><i class="fa fa-info-circle fa-lg"></i></a>:</label>
			      </div>
			      <div class="row collapse">
			      <div class="small-9 columns">
			        <input id="custom-rule-wizard-title" type="text" />
			      </div>
			      </div>
			    </div>
			  </div>
			</div>
			<div class="row padder">
			  <div class="small-11">
			  	<div class="row collapse">
			      <div class="small-2 columns">
			        <label class="right inline"></label>
			      </div>
			      <div class="row collapse">
			      <div class="small-9 columns">
			  	<input type="checkbox" id="custom-rule-wizard-livesportsonly" value="1" style="height: 0.756em ! important;"/>
			    <label for="custom-rule-wizard-livesportsonly" class="right inline">Only live sporting events  <a tiptitle="Apply this custom rule only if its a live sports event with the above title?" class="c_tooltip"><i class="fa fa-info-circle fa-lg"></i></a></label>
			  </div>
			</div>		
		</div>
		</div>
		</div>
		</div>
		<div class="custom-rule-wizard-nav-buttons">
			<button href="#" onclick="$('#custom-breakrule-wizard-step3,#custom-breakrule-wizard-titlestep').toggle();" class="button radius">Previous</button>
			<button href="#" onclick="showStep4FromtitleStep();" class="button radius">Next</button>
		</div>
	</section>

	<section id="custom-breakrule-wizard-step4" style="display:none;">
		<div class="custom-breakrule-wizard-stepcont">
		<h1 class="custom-rule-wizard-step-title" id="custom-rule-wizard-step4-title">Select the dates</h1>
		<div class="row padder">
		  <div class="small-7">
		    <div class="row collapse">
		      <div class="small-3 columns">
		        <label for="custom-rule-wizard-startdate" class="right inline">From Date <a tiptitle="Select this to build a rule based on a fixed date and time. Start and end date and time ranges are needed in this type of rule." class="c_tooltip"><i class="fa fa-info-circle fa-lg"></i></a>:</label>
		      </div>
		      <div class="row collapse">
		      <div class="small-4 columns">
		        <input id="custom-rule-wizard-startdate" type="text"/>
		      </div>
		      </div>
		    </div>
		  </div>
		</div>
		<div class="row padder">
		  <div class="small-7">
		    <div class="row collapse">
		      <div class="small-3 columns">
		        <label for="custom-rule-wizard-enddate" class="right inline">To Date <a tiptitle="Select this to build a rule based on a fixed date and time. Start and end date and time ranges are needed in this type of rule." class="c_tooltip"><i class="fa fa-info-circle fa-lg"></i></a>:</label>
		      </div>

		      <div class="row collapse">
		      <div class="small-4 columns">
		        <input id="custom-rule-wizard-enddate" type="text"/>
		      </div>
		      </div>
		    </div>
		  </div>
		</div>		
		</div>
		<div class="custom-rule-wizard-nav-buttons">
			<button href="#" onclick="$('#custom-breakrule-wizard-step3,#custom-breakrule-wizard-step4').toggle();" class="button radius">Previous</button>
			<button href="#" onclick="showStep5();" class="button radius">Next</button>
		</div>
	</section>

	<section id="custom-breakrule-wizard-step5" style="display:none;">
		<div class="custom-breakrule-wizard-stepcont">
		<h1 class="custom-rule-wizard-step-title" id="custom-rule-wizard-step5-title">Select the Times</h1>
		
		<div class="row padder">
		  <div class="small-7">
		    <div class="row collapse">
		      <div class="small-3 columns">
		        <label for="custom-rule-wizard-timezone" class="right inline">Timezone:</label>
		      </div>
		      <div class="row collapse">
		      <div class="small-4 columns">
		        <select id="custom-rule-wizard-timezone">
		        	<option value="US/Eastern">Eastern</option>
		        	<option value="US/Pacific">Pacific</option>
		        </select>
		      </div>
		      </div>
		    </div>
		  </div>
		</div>


		<div class="row padder">
		  <div class="small-7">
		    <div class="row collapse">
		      <div class="small-3 columns">
		        <label for="custom-rule-wizard-starttime" class="right inline">Rule Start Time:</label>
		      </div>
		      <div class="row collapse">
		      <div class="small-4 columns">
		        <input id="custom-rule-wizard-starttime" type="text"/>
		      </div>
		      </div>
		    </div>
		  </div>
		</div>
		<div class="row padder">
		  <div class="small-7">
		    <div class="row collapse">
		      <div class="small-3 columns">
		        <label for="custom-rule-wizard-endtime" class="right inline">Rule End Time:</label>
		      </div>
		      <div class="row collapse">
		      <div class="small-4 columns">
		        <input id="custom-rule-wizard-endtime" type="text"/>
		      </div>
		      </div>
		    </div>
		  </div>
		</div>		
		</div>
		<div class="custom-rule-wizard-nav-buttons">
			<button href="#" onclick="$('#custom-breakrule-wizard-step4,#custom-breakrule-wizard-step5').toggle();" class="button radius">Previous</button>
			<button href="#" onclick="showStep6();" class="button radius">Next</button>
		</div>
	</section>

	<section id="custom-breakrule-wizard-step6" style="display:none;">
		<div class="custom-breakrule-wizard-stepcont">
		<h1 class="custom-rule-wizard-step-title" id="custom-rule-wizard-step6-title">What would you want to do?</h1>
		<ol id="selectable-step6">
			<li class="ui-widget-content ui-selected" data-choice="manual">Create rule set manually</li>
			<li class="ui-widget-content" data-choice="template">
				Use a template 
				<select disabled="disabled" id="custom-rule-wizard-step6-templateid">
				</select>
			</li>	
			<!-- <li class="ui-widget-content" data-choice="previousrule">
				Use a previously applied rule set
				<select disabled="disabled" id="custom-rule-wizard-step6-prevruleid">
				</select>
			</li> -->
		</ol>		
		</div>
		<div class="custom-rule-wizard-nav-buttons">
			<button href="#" onclick="$('#custom-breakrule-wizard-step5,#custom-breakrule-wizard-step6').toggle();" class="button radius">Previous</button>
			<button href="#" onclick="showStep7('next');" class="button radius">Next</button>
		</div>
	</section>
	
	<section id="custom-breakrule-wizard-step7" style="display:none;">
		<div class="custom-breakrule-wizard-stepcont" style="height: 407px;">
			<section class="sidebar ssforms" style="top:0;width: 270px;">
	    		<br>
	    		<div class="row padder" id="custom-breakrule-wizard-step7-buttons">
				    <div class="small-12">
						<div class="row collapse">
							<div class="small-4 columns" id="custom-breakrule-wizard-step7-add">
								<button id="" type="submit" class="button tiny green radius" onclick="showCustomBreakItemAddForm();"><i class="fa fa-plus-circle fa-lg"></i> Add Row</button>
	                  		</div>
							<div class="small-4 columns" id="custom-breakrule-wizard-step7-edit">
								<button id="" class="button tiny radius" type="submit" onclick="showCustomBreakItemEditForm();"><i class="fa fa-pencil fa-lg"></i>Edit Row</button>
							</div>
							<div class="small-4 columns" id="custom-breakrule-wizard-step7-delete">
								<button id="" class="button tiny darkred radius" type="submit" onclick="CustomBreakItemDeleteSelected();"><i class="fa fa-trash-o fa-lg"></i>Delete Row</button>
							</div>
						</div>
					</div>
				</div>
			    <div class="row padder custom-breakrule-wizard-step7-addeditform" style="display:none;">
			      <div class="small-12">
			        <div class="row collapse">
			          <div class="small-4 columns">
			            <label for="custom-breakrule-wizard-step7-length" class="right inline">Length:</label>
			          </div>
			          <div class="row collapse">
			          <div class="small-8 columns">
			              <input type="text"  placeholder="60" required id="custom-breakrule-wizard-step7-length" value="60"></input>
			          </div>
			          </div>
			        </div>
			      </div>
			    </div>
				<div class="row padder custom-breakrule-wizard-step7-addeditform" style="display:none;">
					<div class="small-12">
						<div class="row collapse">
							<div class="small-4 columns">
								<label for="custom-breakrule-wizard-step7-breaktime" class="right inline">Time:</label>
							</div>
							<div class="row collapse">
								<div class="small-8 columns">
									<input id="custom-breakrule-wizard-step7-breaktime" type="text"/>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row padder custom-breakrule-wizard-step7-addeditform" style="display:none;">
					<div class="small-12">
						<div class="row collapse">
							<div class="small-4 columns">
								<label></label>
							</div>
							<div class="row collapse">
								<div class="small-8 columns">
									<input type="hidden" id="oprtype" value=""/>
									<input type="hidden" id="editRowIndex" value=""/>
									<button id="" type="submit" class="button tiny green radius" onclick="commitCustomBreakItemUpdate();"><i class="fa fa-check fa-lg"></i> Update</button>
									<button id="" type="submit" class="button tiny darkred radius" onclick="cancelCustomBreakitemUpdate();"><i class="fa fa-o fa-lg"></i> Cancel</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row padder" id="custom-breakrule-wizard-step7-editnorowsselected" style="display:none;">
					<div class="small-12">
						<div class="alert-box red_alert radius" data-alert="">
							<i class="fa fa-exclamation-triangle fa-lg"></i> Select a row to edit.
								<br/><br/>
								<button id="" type="submit" class="button tiny red radius" onclick="$('#custom-breakrule-wizard-step7-editnorowsselected').slideUp()"><i class="fa fa-check fa-lg"></i> OK</button>
						</div>
					</div>
				</div>
				<div class="row padder" id="custom-breakrule-wizard-step7-deletenorowsselected" style="display:none;">
					<div class="small-12">
						<div class="alert-box red_alert radius" data-alert="">
							<i class="fa fa-exclamation-triangle fa-lg"></i> Select atleast one row to delete.
								<br/><br/>
								<button id="" type="submit" class="button tiny red radius" onclick="$('#custom-breakrule-wizard-step7-deletenorowsselected').slideUp()"><i class="fa fa-check fa-lg"></i> OK</button>
						</div>
					</div>
				</div>
	    	</section>
    		<section class="main" style="top:0;margin-left:275px;">			
				<div class="gridwrapper" >
					<div id="custom-breakrule-wizard-breakitems-grid" style="height:396px;"></div>
				</div>
			</section>
		</div>
		<div  class="custom-rule-wizard-nav-buttons" style="padding-right: 8px;margin-top:10px;">
			<button href="#" onclick="$('#custom-breakrule-wizard-step6,#custom-breakrule-wizard-step7').toggle();" class="button radius">Previous</button>
			<button href="#" onclick="showStep8();" class="button radius">Next</button>
		</div>
	</section>

	<section id="custom-breakrule-wizard-step8" style="display:none;">
		<div class="custom-breakrule-wizard-stepcont">
			<h1 class="custom-rule-wizard-step-title" id="custom-rule-wizard-step8-title">Rule Set Title</h1>
			<div class="row padder">
			  <div class="small-9">
			    <div class="row collapse">
			      <div class="small-3 columns">
			        <label for="custom-rule-wizard-step8-rulesettitle" class="right inline">Title for this Rule set:</label>
			      </div>
			      <div class="row collapse">
			      <div class="small-6 columns">
			        <input id="custom-rule-wizard-step8-rulesettitle" type="text" />
			      </div>
			      </div>
			    </div>
			  </div>
			</div>
		</div>
		<div class="custom-rule-wizard-nav-buttons">
			<button href="#" onclick="$('#custom-breakrule-wizard-step7,#custom-breakrule-wizard-step8').toggle();" class="button radius">Previous</button>
			<button href="#" onclick="saveRuleSet();" class="button radius">Finish</button>
		</div>
	</section>
</div>

<?php

	function getNetworks($corporationid,$userid)
	{
		$allowedNets = getUsersAllowedNetworks($userid);
		$sql = " SELECT tn.networkid AS id, tn.callsign, tn.name, logos.filename, nm.charter_mapping AS charter_callsign, CONCAT('http://ww2.showseeker.com/images/_thumbnailsW/',IFNULL(logos.filename,'default.gif')) AS logofullpath
					FROM ezbreaks.breakgroups AS bg 
					INNER JOIN ezbreaks.breakgroups_items AS bgi ON bg.id = bgi.breakgroupsid
					INNER JOIN ShowSeeker.tms_networks AS tn  ON tn.networkid = bgi.tmsid
					INNER JOIN ShowSeeker.networkmapping AS nm  ON tn.networkid = nm.id
					LEFT JOIN ShowSeeker.networklogos ON networklogos.networkid = tn.networkid
					LEFT JOIN ShowSeeker.logos ON logos.id = networklogos.logoid 
					WHERE corporationid=$corporationid AND bgi.deletedat IS NULL AND bg.deletedat IS NULL
					GROUP BY tn.networkid ORDER BY tn.callsign ";
		$result = mysql_query($sql);
		while($row = mysql_fetch_assoc($result))
		{
			if(in_array($row['id'], $allowedNets))
				$networksArr[] = $row;
		}
		return $networksArr;
	}

	function getUsersAllowedNetworks($userId)
	{
		$sql = "SELECT pb.networkinstances FROM ShowSeeker.permissionbreakuser AS pbu INNER JOIN ShowSeeker.permissionbreaks AS pb ON pb.id=pbu.groups WHERE pbu.userid = $userId";
		$res = mysql_query($sql);

		if(mysql_num_rows($res) ==0) return array();

		$obj = mysql_fetch_object($res);

		if(count(explode(',',$obj->networkinstances)) == 0) return array();

		$sql = "SELECT DISTINCT breakgroups_items.tmsid FROM ezbreaks.breakgroups_items WHERE id IN ({$obj->networkinstances}) ";
		$result = mysql_query($sql);

		$networks = array();
		while($row = mysql_fetch_object($result))
		{
			$networks[] = $row->tmsid;
		}

		return $networks;
	}
?>
<cfform id="premiefinales" action="index.cfm" method="post">

<div class="topheader" style="width:100%;">
		

		<div class="filterprograms">
			<span class="filterlabel">
			Filter by 
			</span>
			<select class="s1" id="filterSportType" multiple="multiple" size="2">			
				<option value="All"  <cfif t EQ 'all'>selected</cfif>>Show All</option>
				<option value="pNew" <cfif t EQ 'projected'>selected</cfif>>Proj New</option>				
				<option value="premiereprojected" <cfif t EQ 'projected'>selected</cfif>>Proj Premiere</option>				
				<option value="package" <cfif t EQ 'packages'>selected</cfif>>Packages</option>				
				<option value="SeriesPremiere"<cfif t EQ 'premieres'>selected</cfif>>Series Premieres</option>
				<option value="SeriesFinale"<cfif t EQ 'premieres'>selected</cfif>>Series Finales</option>
				<option value="SeasonPremiere"<cfif t EQ 'premieres'>selected</cfif>>Season Premieres</option>
				<option value="SeasonFinale"<cfif t EQ 'premieres'>selected</cfif>>Season Finales</option>
				<cfoutput query="filters">
				<cfif t EQ 'live'>
				<option value="#filters.program#" selected>#filters.program#</option>
				<cfelse>
				<option value="#filters.program#">#filters.program#</option>
				</cfif>
				</cfoutput>
				<option value="Other">Other Sports Live</option>
			</select>
		</div>
		
		<div class="separator">&nbsp;</div>

		<div class="innerparams" style="background-color:##3D003D; color:##fff;">
			Nets
			<cfselect query="zonenetworks" id="nets" name="nets" display="CALLSIGN" value="networkid" class="filters" queryPosition="below">
				<option value="0">---- ALL ----</option>
			</cfselect>
		</div>

		
		<div class="innercontrols" id="ctldetails" style="width:50px;">
			<span class="plus">
				<a title="Show More Details">
					+ More Details
				</a>
			</span>
			
			<span class="minus">
				<a title="Show Less">
					- Less Details
				</a>
			</span> 
		</div>
		
		<div class="separator">&nbsp;</div>
		
		<div class="innercontrols" id="ctlproposal" style="width:auto;">
			<span>
				Proposal: 
			</span>
			<input name="proposalnew" 	id="proposalnew"  	  class="pslinputs"  value=""	>
			<input type="button" 		id="createproposal"    class="add" 			value=" Create " >

			<span>
			or
			</span>
			
			<select class="filters"   id="proposalList" name="proposalList">
				<option value=""></option>
			</select>
		</div>
		
		<div class="separator">&nbsp;</div>
		
		<div class="innercontrols" id="ctlSelectall" style="width:auto;">
			Select All
			<input type="checkbox" id="select_all" title="Select / Deselect All" value="0">
		</div>
</div>

<cfoutput>
<div class="topheader2" style="width:100%;">

	<div class="innerparams"  style="background-color:##3D003D; color:##fff; padding: 4;">
		<input class="clearable noXButton" type="text" id="searchShow" value="" placeholder="Search Title" style="font-size: 8pt; width: 187px">
	</div>

	<div class="separator">&nbsp;</div>	
	<div class="separator">&nbsp;</div>
	<div class="innerparams" style="background-color:##3D003D; color:##fff; display: none;">
		Markets
		<select id="usrmarkets" name="usrmarkets" class="filters"></select>
	</div>  

	<div class="innerparams" style="background-color:##3D003D; color:##fff;">
		Zone
		<select  id="zones" name="zones" class="filters"></select>
	</div>  
	
	<div class="separator">&nbsp;</div>	
	<div class="separator">&nbsp;</div>
		
	<div class="innerparams"  style="background-color:##3D003D; color:##fff;">		
		Time
		<cfselect query="hours" display="HOUR_DISPLAY" value="HOUR_FIXED" name="sTime" id="sTime" selected="#sTime#" class="filters"></cfselect>
		to
		<cfselect query="hours" display="HOUR_DISPLAY" value="HOUR_FIXED" name="eTime" id="eTime" selected="#eTime#" class="filters"></cfselect>
	</div>

	
	<div class="separator">&nbsp;</div>	
	<div class="separator">&nbsp;</div>
	<span class="delete"  type="reset" id="resetegrid" style="padding-top:4px; padding-right:8px; padding-bottom:4px; padding-left:8px;">Reset</span>
	<input type="hidden" id="zoneid" 	name="zoneid">
	<input type="hidden" id="tz" 		name="tz">
	<input type="hidden" id="userid" 	name="userid" 		value="#userid#">
	<input type="hidden" id="apiKey" 	name="apiKey" 		value="#apiKey#">
	<input type="hidden" id="startdate" name="startdate"  	value="#startdate#">
	<input type="hidden" id="enddate" 	name="enddate" 		value="#enddate#">
	<input type="hidden" id="t" 		name="t"			value="#t#">		

</div>
</cfoutput>

<div style="height:10px; width:100%; clear:both;"></div>

</cfform>


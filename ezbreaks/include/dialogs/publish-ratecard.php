  <center>
  	
   	<input type="checkbox" id="checkbox-publish-ratecard" value="yes"><label for="checkbox-publish-ratecard">I want to publish this ratecard</label>
    	<br>

    <!--
   		<textarea style="height:10px;" id="form-publish-ratecard-notes2" placeholder="Notes for later identification."></textarea>
	-->



    <input type="hidden" name="form-publish-ratecard-notes" id="form-publish-ratecard-notes" value="<?php print date("F j, Y, g:i a");  ?>"> 

 	<div id="button-publish-ratecard" style="display:none;">



    <div data-alert class="alert-box red_alert radius">
      <i class="fa fa-exclamation-triangle fa-lg"></i> Clicking publish will make your ratecard live.
    </div>


    	<button onclick="publishRatecardEvent();" class="tiny green"><img src="/images/tiny-s.png"> Publish</button>

	</div>

  </center>
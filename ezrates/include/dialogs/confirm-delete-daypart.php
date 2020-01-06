  <div data-alert class="alert-box red_alert radius">
    <i class="fa fa-exclamation-triangle fa-lg"></i> Are you sure you want to delete this daypart
  </div>

  <center>
    <i><b>Notice:</b> This cannot be undone and will remove all the rates from this daypart on your ratecards in this market.</i>

      <br><br>
        <input type="checkbox" id="checkbox-delete-market-daypart" value="yes"><label for="checkbox-delete-market-daypart">I still want to delete the daypart</label>
      <br>
    <button onclick="datagridDaypartSelected.deleteSelected();closeAllDialogs();updateMarketDayparts();" id="button-delete-market-daypart" class="tiny red disabled"><i class="fa fa-trash-o fa-lg"></i> Delete</button>
  </center>
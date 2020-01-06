<?php
	
	$id     		= $_GET['proposalid'];
	$userId			= trim(urldecode($_GET['user']));
	$apiKey			= trim(urldecode($_GET['token']));
	$proposal  		= getProposalMedia($id, $userId, $apiKey);	

	print_r('<textarea id="apiJson"  rows="25" cols="60" class="apiSelected">'.json_encode($proposal, JSON_PRETTY_PRINT));
	print_r('</textarea>');
	
	print('<br><center><div id="copyApi" class="button btn-green hander" style="width:200px;">Copy All</div></center>');
	
	
	function getProposalMedia($id, $userId, $apiKey) {
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://plusapi.showseeker.com/ezratings/mediamathjson/{$id}",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_SSL_VERIFYHOST=> false,
			CURLOPT_SSL_VERIFYPEER=> false,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_POSTFIELDS => "",
			CURLOPT_HTTPHEADER => array(
			"api-key: {$apiKey}",
			"cache-control: no-cache",
			"user: {$userId}"
			),
		));
	
		$response = curl_exec($curl);
		$err      = curl_error($curl);
		curl_close($curl);

		return json_decode($response);
	}
?>

<script>
	$('#copyApi').button()
	$("#copyApi").on("click", function(){
		copyText(document.getElementById("apiJson"))
	});
	
	function copyText(element) {
		var $temp = $("<input>");
		$("body").append($temp);
		$temp.val($(element).text().replace(/  /g,'')).select();
		document.execCommand("copy");
		$temp.remove();
		$('.apiSelected').addClass('apiHighlight');
	}	
	
</script>
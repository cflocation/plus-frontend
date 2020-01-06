<style>
#popupwindow {
display:none;
position:absolute;
top:35px;
left:970px;
z-index:999999;
overflow: hidden;
}
#backgroundsetting {
width:125px;
height:105px;
position:relative;
margin:0px;
}
#imagepopup {
cursor:pointer;
background:url(close.png) repeat; 
height:40px;
width:40px;
margin-top:-250px;
margin-left:205px;
position:absolute;
}
</style>

<script src='https://code.jquery.com/jquery-2.1.3.min.js' type='text/javascript'></script>
<script src='https://code.jquery.com/ui/1.11.4/jquery-ui.min.js' type='text/javascript'></script>
<script type='text/javascript'>
jQuery.cookie = function (key, value, options) {
if (arguments.length > 1 && String(value) !== "[object Object]") {
options = jQuery.extend({}, options);
if (value === null || value === undefined) {
options.expires = -1;
}
if (typeof options.expires === 'number') {
var days = options.expires, t = options.expires = new Date();
t.setDate(t.getDate() + days);
}
value = String(value);
return (document.cookie = [
encodeURIComponent(key), '=',
options.raw ? value : encodeURIComponent(value),
options.expires ? '; expires=' + options.expires.toUTCString() : '',
options.path ? '; path=' + options.path : '',
options.domain ? '; domain=' + options.domain : '',
options.secure ? '; secure' : ''
].join(''));
}
options = value || {};
var result, decode = options.raw ? function (s) { return s; } : decodeURIComponent;
return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
};
</script>


<script type='text/javascript'>
    jQuery(document).ready(function ($) {
        //if ($.cookie('flyover') != 'yes') {
			$('#popupwindow').show('slide', {direction: 'right'}, 1800);
			$('#imagepopup, #fan-exit').click(function(){
			$('#popupwindow').stop().fadeOut('medium');
	            });
			$.cookie('flyover', 'yes', { path: '/', expires: 1 });
        //}
    });
 

var xSeconds = 35000; // 1 second
setTimeout(function() {
   $('#popupwindow').fadeOut('fast');
   $('#popupwindow').hide();
}, xSeconds);





</script>
<div id='popupwindow' style="z-index: 10" onclick="$(this).hide();">
		<div id='backgroundsetting' style="position: relative;">
			<div style="position: absolute; top:0px; right: 0px; padding: 2px 6px 2px; font-weight: 800;" onclick="$('#backgroundsetting').hide();">
				<span class="hander	"> <i class="fa fa-times-circle fa-lg" style="color: red;"></i> </span>
			</div>
			<a href="javascript:dialogDisclaimer('MM2018')"><img id='arrowRotate' src="../banner/BB2018.png" style="width:125px; height:95px;" width="125" height="95"></a>
			<!-- a href="javascript:dialogDisclaimer('olympics2018')"><img id='arrowRotate' src="../banner/olympics_2018.png" style="width:125px; height:95px;" width="125" height="95"></a -->
		</div>
</div>

<script>
$("#arrowRotate").click(function() { 
       var _this = $(this);
       var current = _this.attr("src");
       var swap = _this.attr("data-swap");     
     _this.attr('src', swap).attr("data-swap",current);   
});  

</script>


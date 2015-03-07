/*hello*/
;(function($, window, document, undefined)
{
	$(document).ready(function(){
		var banner = $("p.demo_store");
		var wrapper = $("div#wrapper div#inner-wrapper header#header");
		if(banner.length > 0 && wrapper.length > 0){
			banner.insertBefore(wrapper);
		}
	});
}(jQuery)); 
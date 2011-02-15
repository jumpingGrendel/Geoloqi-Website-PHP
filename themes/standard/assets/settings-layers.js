
$(function(){
	
	$("#subscribe_switch").click(function(){
		$(this).blur();
		$.post("/settings/layer.ajax", {
			action: $(this).hasClass("on") ? "unsubscribe" : "subscribe",
			id: $("#layer_id").val()
		}, function(data){
			if(data && data.layer_id){
				var img = $("#subscribe_switch img").attr("src");
				if(data.subscribed){
					$("#subscribe_switch").removeClass("off").addClass("on");
					$("#subscribe_switch img").attr("src", img.replace(/switch_off/, "switch_on"));
				}else{
					$("#subscribe_switch").removeClass("on").addClass("off");
					$("#subscribe_switch img").attr("src", img.replace(/switch_on/, "switch_off"));
				}
			}else{
				gb_show({
					message: "Error: " + (data ? data.error : ""),
					width: 300,
					height: 60
				});
				setTimeout(gb_hide, 1500);
			}
		}, "json");		
	});
	
	$(".layer-settings").click(function(){
		$(this).blur();
		$.post("/settings/layer.ajax", {
			id: $("#layer_id").val(),
			action: "settings",
			foursquare_autocheckin: ($("#setting-foursquare_autocheckin").hasClass("on") ? 0 : 1)
		}, function(data){
			if(data && data.layer_id){
				var img = $("#setting-foursquare_autocheckin img").attr("src");
				if(data.settings.foursquare_autocheckin == 1){
					$("#setting-foursquare_autocheckin").removeClass("off").addClass("on");
					$("#setting-foursquare_autocheckin img").attr("src", img.replace(/_off/, "_on"));
				}else{
					$("#setting-foursquare_autocheckin").removeClass("on").addClass("off");
					$("#setting-foursquare_autocheckin img").attr("src", img.replace(/_on/, "_off"));
				}
			}else{
				gb_show({
					message: "Error: " + (data ? data.error : ""),
					width: 300,
					height: 60
				});
				setTimeout(gb_hide, 1500);
			}
		}, "json");
	});

});
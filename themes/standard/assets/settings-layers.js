
$(function(){
	
	$(".save-privacy").click(function(){
		gb_show({
			message: "Working...",
			width: 300,
			height: 60
		});
		
		$.post("/settings/layer.ajax", {
			action: "save-privacy",
			token: $(this).siblings(".token").val()
		}, function(data){
			if(data.result == "ok"){
				gb_update("Ok!");
				setTimeout(function(){
					window.location = window.location;
				}, 120);
			}else{
				gb_update(data.error);
			}
		}, "json");		
	});

});
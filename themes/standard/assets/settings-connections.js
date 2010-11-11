
$(function(){
	$("#request_access_token").click(function(){
		$.post("/settings/get_permanent_token.ajax", {
		}, function(data){
			$("#permanent_access_token").val(data.access_token).show();
			$("#request_access_token").hide();
		}, "json");
	});

	$("#instamapper_create").click(function(){
		gb_show({
			message: "Creating Device Key...",
			width: 380,
			height: 60
		});
		
		$.post("/settings/connections.ajax", {
			action: "create_instamapper"
		}, function(data){
			gb_update("Ok!");
			setTimeout(gb_hide, 1500);
			$("#instamapper_create").hide();
			$("#instamapper_devicekey").val(data.device_key).show();
		}, "json");
	});
});

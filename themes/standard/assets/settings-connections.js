
$(function(){
	$("#request_access_token").click(function(){
		$.post("/settings/get_permanent_token.ajax", {
		}, function(data){
			$("#permanent_access_token").val(data.access_token).show();
			$("#request_access_token").hide();
		}, "json");
	});

	$("#instamapper_save").click(function(){
		gb_show({
			message: "Saving...",
			width: 300,
			height: 60
		});
		
		$.post("/settings/connections.ajax", {
			instamapper_key: $("#instamapper_key").val()
		}, function(data){
			gb_update("Ok!");
			setTimeout(gb_hide, 1500);
		});
	});
});

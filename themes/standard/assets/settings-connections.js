
$(function(){
	$("#request_access_token").click(function(){
		$.post("/settings/get_permanent_token.ajax", {
		}, function(data){
			$("#permanent_access_token").val(data.access_token).show();
			$("#request_access_token").hide();
		}, "json");
	});
});
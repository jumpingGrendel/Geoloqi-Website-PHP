
$(function(){
	$("#btn_save").click(function(){
		
		gb_show({
			message: "Saving...",
			width: 300,
			height: 60
		});
		
		$.post("/settings/profile.ajax", {
			name: $("#profile_name").val(),
			email: $("#profile_email").val(),
			bio: $("#profile_bio").val(),
			website: $("#profile_website").val(),
			phone: $("#profile_phone").val(),
			timezone: $("#profile_timezone").val()
		}, function(data){
			gb_update("Ok!");
			setTimeout(gb_hide, 1500);
		});
	});
});
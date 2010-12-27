
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
			if($("#profile_phone").val() != ""){
				$("#profile_phone").removeClass("highlight");
				$("#profile_phone").siblings(".description").removeClass("highlight");
			}else{
				$("#profile_phone").addClass("highlight");
				$("#profile_phone").siblings(".description").addClass("highlight");
			}
			setTimeout(gb_hide, 1200);
		});
	});
	
	$("#btn_changepassword").click(function(){
		$("#password_response").text("");
		$.post("/settings/password.ajax", {
			current_password: $("#current_password").val(),
			new_password_1: $("#new_password_1").val(),
			new_password_2: $("#new_password_2").val()
		}, function(data){
			if(typeof data.error != "undefined"){
				$("#password_response").text(data.error_description).addClass("error");
			}else{
				$("#password_response").text("Password set successfully!").removeClass("error");
			}
		}, "json");
	});	
});
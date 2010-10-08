
$(function(){
	$("#btn_save").click(function(){
		
		gb_show({
			message: "Saving...",
			width: 300,
			height: 60
		});
		
		$.post("/settings/privacy.ajax", {
			public_location: ($("#public_location:checked").val() == "on" ? 1 : 0),
			public_geonotes: ($("#public_geonotes:checked").val() == "on" ? 1 : 0),
			public_geonote_email: ($("#public_geonote_email:checked").val() == "on" ? 1 : 0),
			default_share_expiration: $("#default_share_expiration").val()
		}, function(data){
			gb_update("Ok!");
			setTimeout(gb_hide, 1500);
		});
	});
});
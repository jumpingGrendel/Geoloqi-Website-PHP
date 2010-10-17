
var map;
var last = false;
var lastPosition = false;
var thePath = false;
var marker = false; // The marker of the user's location
var autoPan = true; // pan the map when a new point is received
var polyline;		// The line showing the user's history trail

$(function(){
	var latlng = new google.maps.LatLng(45.51, -122.63);

	// Set up the map
	var myOptions = {
		zoom: 14,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControl: false
	};
	map = new google.maps.Map(document.getElementById("map"), myOptions);
	
	resize_map();

	// Dragging the map turns off auto-pan
	google.maps.event.addListener(map, 'dragstart', function(){
		autoPan = false;
	});
	
	$(window).resize(resize_map);

	if(self_map){
		get_history();
	}else{
		get_single_point();
	}

	function get_history(){
		$.getJSON("/map/history.ajax", {
			count: 100,
			thinning: thinning,
			after: (last ? last.date : 0)
		}, function(data){
			autoPan = false;
			for(var i in data){
				// Turn on auto-pan on the last point so the map is centered
				if(i == data.length - 1)
					autoPan = true;
				
				receive_location(data[i]);
			}
			setTimeout(get_history, 10000);
		});
	}
	
	function get_single_point(){
		$.getJSON("/map/last.ajax",{
			username: username,
			token: share_token
		}, function(data){
			autoPan = true;
			receive_location(data);
			setTimeout(get_single_point, 10000);
		});
	}
	
	/**
	 * Collapsible sidebar panels
	 */
	$(function(){
		$(".sidebar-panel .panel-title").hover(function(){
			$(this).addClass("hover");
		}, function(){
			$(this).removeClass("hover");
		});
		$(".sidebar-panel .panel-title").click(function(){
			if($(this).parent().hasClass("active")){
				$(this).parent().removeClass("active");
				$(this).removeClass("active");
				$(this).siblings(".panel-content").hide();
				var f = $(this).parent().attr("id") + "_end";
				eval(f + "();");
			}else{
				$(this).parent().addClass("active");
				$(this).addClass("active");
				$(this).siblings(".panel-content").show();
				var f = $(this).parent().attr("id") + "_start";
				eval(f + "();");
			}
		});
	});

	$("#share_btn").click(function(){
		$.post("/map/share_link.ajax",{
			share_expiration: $("#share_expiration").val(),
			share_with: $("#share_with").val()
		}, function(data){
			if(typeof data.error != "undefined"){
				gb_show({message: "There was a problem creating the shared link!"});
				setTimeout(gb_hide, 1000);
				return false;
			}
			$("#sidebar_sharelink .panel-title").click();
			gb_show({
				message: 'Link created!<br /><input type="text" value="' + data.shortlink + '" /><br /><input type="button" value="Ok!" onclick="gb_hide()" />',
				height: 120
			});
		}, "json");
	});
	
});

function resize_map(){
	$("#map-container").css("height", (window.innerHeight-$("#map-footer").height())+"px").css("width", (window.innerWidth-$("#sidebar").width())+"px");
	$("#map").css("height", "100%").css("width", "100%");
	google.maps.event.trigger(map, 'resize');
}

var location_error = false;

function receive_location(l){
	if(typeof l.error != "undefined"){
		if(location_error == false){  // if there isn't already a location error on the screen
			location_error = true;
			var error_message;
			if(l.error == "invalid_token"){
				gb_show({
					height: 60,
					message: "The shared link has expired!"
				});
			}else{
				gb_show({
					message: "There was an error!<br /><br />" + l.error + ": " + l.error_description
				});
			}
		}
		return false;
	}else{
		if(location_error == true){  // there was an error but there isn't anymore
			gb_hide();
			location_error = false;
		}
	}
	
	newPosition = new google.maps.LatLng(l.location.position.latitude, l.location.position.longitude);
	lastPosition = newPosition;
	
	if(autoPan) {
		map.panTo(newPosition);
	}

	if(marker == false){
		marker = new google.maps.Marker({
			position: newPosition,
			map: map
		});
	}else{
		marker.setPosition(newPosition);
	}

	if(thePath == false){
		thePath = new google.maps.Polyline({
			strokeColor: "#000000",
			strokeOpacity: 0.8,
			strokeWeight: 3
		});
		thePath.setMap(map);
	}
	thePath.getPath().push(newPosition);

	last = l;
}

function sidebar_mapoptions_start(){
	
}

function sidebar_mapoptions_end(){
	
}


function sidebar_sharelink_start(){
	
}

function sidebar_sharelink_end(){
	
}


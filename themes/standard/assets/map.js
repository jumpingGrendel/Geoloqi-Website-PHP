
var map; // The main map drawn on the screen
var hiddenMap; // An invisible map used to stash objects if we want to hide them
var lastPosition = false;
var thePath = false;
var marker = false; // The marker of the user's location
var autoPan = true; // pan the map when a new point is received
var polyline;		// The line showing the user's history trail
var updateLocation = true; // Whether to continue asking for location updates, set to false when a link expires
var updateLocationTimer;   // The return value of the setTimeout doing location updates
var location_error = false;  // Set to true when an error is received from the API
var initial_zoom = 14;

$(function(){
	var latlng;
	if(rough){
		latlng = new google.maps.LatLng(rough.latitude, rough.longitude);
		updateLocation = false;
		initial_zoom = 13;
	}else if(last){
		latlng = new google.maps.LatLng(last.location.position.latitude, last.location.position.longitude);
	}

	// Set up the map
	var myOptions = {
		zoom: initial_zoom,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControl: false
	};
	map = new google.maps.Map(document.getElementById("map"), myOptions);
	hiddenMap = new google.maps.Map(document.getElementById("hiddenMap"), myOptions);
	
	resize_map();

	// Dragging the map turns off auto-pan
	google.maps.event.addListener(map, 'dragstart', function(){
		autoPan = false;
	});
	
	$(window).resize(resize_map);

	if(self_map){
		get_realtime_history();
	}else if(last){
		get_single_point();
	}

	// Updates the timestamp in the profile section continuously by monitoring the last point received 
	setInterval(function(){
		if(updateLocation && lastPosition){
			var lastDate = new Date((last.date_ts+"000") * 1);
			var hrs = lastDate.getHours();
			hrs = (hrs < 10 ? "0" + hrs : hrs);
			var mins = lastDate.getMinutes();
			mins = (mins < 10 ? "0" + mins : mins);
			var secs = lastDate.getSeconds();
			secs = (secs < 10 ? "0" + secs : secs);
			var dateFormatted = (lastDate.getMonth()+1) + "/" + lastDate.getDate() + "/" + lastDate.getFullYear() + " " + hrs + ":" + mins + ":" + secs;
			
			var now = new Date();
			var diff = Math.round((now.getTime() - lastDate.getTime()) / 1000);
			if(diff > 86400){
				$("#profile-info .last-time .relative").hide();
				$("#profile-info .last-time .absolute").show();
			}else{
				if(diff < 60){
					diff = diff + " seconds ago";
				}else if(diff < 60*60){
					diffTxt = Math.floor(diff / 60) + "m ";
					diffTxt += (diff % 60) + "s ago";
					diff = diffTxt;
				}else if(diff < 60*60*24){
					diffTxt = Math.floor(diff / (60*60)) + "h ";
					diffTxt += (Math.floor(diff / 60) % 60) + "m ago";
					diff = diffTxt;
				}
			}
			
			$("#profile-info .last-time .relative").text(diff);
			$("#profile-info .last-time .absolute").text(dateFormatted);
		}
	}, 1000);
	
	$("#profile-info .last-time .relative").mouseover(function(){
		$("#profile-info .last-time .absolute").show();
	});
	
	function get_realtime_history(){
		$.getJSON("/map/history.ajax", {
			count: 100,
			thinning: thinning,
			date_from: (last ? last.date : 0)
		}, function(data){
			for(var i in data){
				receive_location(data[i]);
			}
			if(updateLocation){
				updateLocationTimer = setTimeout(get_realtime_history, 10000);
			}
		});
	}
	
	function get_single_point(){
		$.getJSON("/map/last.ajax",{
			username: username,
			token: share_token
		}, function(data){
			receive_location(data);
			if(updateLocation){
				updateLocationTimer = setTimeout(get_single_point, 10000);
			}
		});
	}
	
	/**
	 * Collapsible sidebar panels
	 */
	$(".sidebar-panel .panel-title").hover(function(){
		$(this).addClass("hover");
	}, function(){
		$(this).removeClass("hover");
	});
	// A sidebar title was clicked, open or close the panel and run the corresponding function
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
				message: data.html,
				height: 170
			});
		}, "json");
	});

	function resize_map(){
		$("#map-container").css("height", (window.innerHeight-$("#map-footer").height())+"px").css("width", (window.innerWidth-$("#sidebar").width())+"px");
		$("#map").css("height", "100%").css("width", "100%");
		google.maps.event.trigger(map, 'resize');
	}

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
					updateLocation = false;
				}else{
					gb_show({
						message: "There was an error!<br /><br />" + l.error + ": " + l.error_description
					});
				}
			}
			return false;
		}else{
			if(location_error == true){  // there was an error but there isn't anymore (maybe the link is valid again?)
				gb_hide();
				location_error = false;
			}
		}
		
		newPosition = new google.maps.LatLng(l.location.position.latitude, l.location.position.longitude);
		lastPosition = newPosition;
		
		if(autoPan) {
			map.panTo(newPosition);
		}

		$("#profile-info .last-lat").text(Math.round(l.location.position.latitude * 1000) / 1000);
		$("#profile-info .last-lng").text(Math.round(l.location.position.longitude * 1000) / 1000);
		
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
		$("#profile-info .last-lat").hide();
		$("#profile-info .last-lng").hide();
		$("#profile-info .last-time").hide();
		updateLocation = false;
		clearInterval(updateLocationTimer);
		thePath.setMap(hiddenMap);
		LQHistory.start();
	}

	function sidebar_mapoptions_end(){
		$("#profile-info .last-lat").show();
		$("#profile-info .last-lng").show();
		$("#profile-info .last-time").show();
		updateLocation = true;
		thePath.setMap(map);
		get_realtime_history();
		LQHistory.stop();
	}


	function sidebar_sharelink_start(){
		
	}

	function sidebar_sharelink_end(){
		
	}


});

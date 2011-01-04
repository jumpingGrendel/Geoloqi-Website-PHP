
var map; // The main map drawn on the screen
var hiddenMap; // An invisible map used to stash objects if we want to hide them
var lastPosition = false;
var thePath = false;
var marker = false; // The marker of the user's location
var autoPan = true; // pan the map when a new point is received
var polyline;		// The line showing the user's history trail
var latlng;
var updateLocation = true; // Whether to continue asking for location updates, set to false when a link expires
var updateLocationTimer;   // The return value of the setTimeout doing location updates
var location_error = false;  // Set to true when an error is received from the API
var initial_zoom = 14;
var geonote_hash_triggered = false;
var first_history = true; // Only true before any history has loaded

$(function(){
	/**
	 * Setup stage
	 */
	
	// Set up the map center/zoom based on what mode we are looking at
	if(rough && (typeof rough.error == "undefined")){
		latlng = new google.maps.LatLng(rough.latitude, rough.longitude);
		updateLocation = false;
		initial_zoom = 13;
	}else if(last && (typeof last.error == "undefined")){
		latlng = new google.maps.LatLng(last.location.position.latitude, last.location.position.longitude);
	}else{
		// There is no exact or rough location given. This can be for a number of reasons. Show a view of the earth. The error HTML will be drawn from index.php
		latlng = new google.maps.LatLng(30, -30);
		initial_zoom = 2;
	}

	// Set up the map
	var myOptions = {
		zoom: initial_zoom,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControl: false
	};
	// Create the main map
	map = new google.maps.Map(document.getElementById("map"), myOptions);
	
	if(document.getElementById("hiddenMap")) {
		// Create the hidden map in a secret div
		hiddenMap = new google.maps.Map(document.getElementById("hiddenMap"), myOptions);
	}
		
	// Dragging the map turns off auto-pan
	google.maps.event.addListener(map, 'dragstart', function(){
		autoPan = false;
	});

	// Bind to the window resize event
	$(window).resize(resize_map);

	bind_sidebar_panels();
	
	resize_map();

	if(self_map){
		get_realtime_history();
	}else if(last){
		get_single_point();
	}

	// Updates the timestamp in the profile section continuously by monitoring the last point received 
	setInterval(update_profile_timestamp, 1000);

	// Mouseover the timestamp in the profile to show the absolute timestamp
	$("#profile-info .last-time .relative").mouseover(function(){
		$("#profile-info .last-time .absolute").show();
	});

	// If there was a rough history value it means they are not looking at their own, so they are most likely
	// looking at someone's map who would allow geonotes to be sent. This will open the sidebar panel if #geonote is in the URL.
	if(rough){
		open_init_geonote_panel();
	}
	
	/** 
	 * Setup complete. Now define a bunch of functions
	 */

	function resize_map(){
		if($("#sidebar").width() > 0){
			$("#map-container").css("height", (window.innerHeight - $("#map-footer").height()) + "px").css("width", (window.innerWidth - $("#sidebar").width()) + "px");
			$("#map").css("height", "100%").css("width", "100%");
		}else{
			$("#map").css("height", (window.innerHeight - $("#map-footer").height() - $("#map-header").height()) + "px").css("width", (window.innerWidth) + "px");
		}
		google.maps.event.trigger(map, 'resize');
		resize_errormessage();
	}
	
	function resize_errormessage(){
		if($("#map-disabled").length){
			$("#map-disabled").css("height", $("#map-container").height()).css("width", $("#map-container").width());
			$("#map-disabled .message").css("margin-top", Math.round(($("#map-container").height() / 2) - ($("#map-disabled .message").height() / 2)));
			//setTimeout(function(){
				// Disable the sidebar dropdown panels. Needs to be done in a settimeout because the event hasn't been bound at this time yet.
				// Kind of hacky, but shouldn't cause any confusion since it's unlikely they would have had a chance to click one yet anyway.
				$(".sidebar-panel .panel-title").unbind("click mouseover mouseout");
			//}, 1000);
			updateLocation = false;
			location_error = true;  // prevent the other greybox from popping up
		}
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

	function get_realtime_history(){
		var params = {
			count: 100,
			thinning: thinning
		};
		if(first_history){
			params.date_to = (last ? last.date : 0);
			first_history = false;
		}else{
			params.date_from = (last ? last.date : 0);
		}
		$.getJSON("/map/history.ajax", params, 
		function(response){
			var data = response.points;
			for(var i in data){
				receive_location(data[i]);
			}
			if(updateLocation){
				updateLocationTimer = setTimeout(get_realtime_history, 10000);
			}
			open_init_geonote_panel();
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
			open_init_geonote_panel();
		});
	}
	
	function sidebar_mapoptions_start(){
		$("#profile-info .last-lat").hide();
		$("#profile-info .last-lng").hide();
		$("#profile-info .last-time").hide();
		$("#sidebar_mapoptions .help").show();
		updateLocation = false;
		clearInterval(updateLocationTimer);
		thePath.setMap(hiddenMap);
		LQHistory.start();
	}

	function sidebar_mapoptions_end(){
		$("#profile-info .last-lat").show();
		$("#profile-info .last-lng").show();
		$("#profile-info .last-time").show();
		$("#sidebar_mapoptions .help").hide();
		updateLocation = true;
		thePath.setMap(map);
		get_realtime_history();
		LQHistory.stop();
	}


	function sidebar_sharelink_start(){
		
	}

	function sidebar_sharelink_end(){
		
	}

	function open_init_geonote_panel(){
		if(geonote_hash_triggered == false){
			if(window.location.hash == "#geonote"){
				$("#sidebar_geonote .panel-title").click();
			}
			geonote_hash_triggered = true;
		}
	}

	function bind_sidebar_panels(){
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
				share_description: $("#share_description").val(),
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
	}
	
	function update_profile_timestamp(){
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
	}
});

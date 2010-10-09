
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

	$.getJSON("/map/history.ajax", {
		count: 100,
		thinning: thinning
	}, function(data){
		autoPan = false;
		for(var i in data){
			// Turn on auto-pan on the last point so the map is centered
			if(i == data.length - 1)
				autoPan = true;
			
			receive_location(data[i]);
		}
		setInterval(function(){
			$.getJSON("/map/history.ajax", {
				after: last.date
			},
			function(data){
				for(var i in data){
					var point = data[i];
					receive_location(point);
				}
			});
		}, 10000);
	});
});

function resize_map(){
	$("#map-container").css("height", (window.innerHeight-$("#map-footer").height())+"px").css("width", (window.innerWidth-$("#sidebar").width())+"px");
	$("#map").css("height", "100%").css("width", "100%");
	google.maps.event.trigger(map, 'resize');
}

function receive_location(l){
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

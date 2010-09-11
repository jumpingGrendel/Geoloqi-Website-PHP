
var map;
var last = false;
var lastPosition = false;
var marker = false; // The marker of the user's location

$(function(){
	var latlng = new google.maps.LatLng(45.51, -122.63);

	// Set up the map
	var myOptions = {
		zoom: 13,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControl: false
	};
	map = new google.maps.Map(document.getElementById("map"), myOptions);
	
	resize_map();
});

function resize_map(){
	$("#map-container").css("height", (window.innerHeight-$("#map-footer").height())+"px").css("width", (window.innerWidth-$("#sidebar").width())+"px");
	$("#map").css("height", "100%").css("width", "100%");
	google.maps.event.trigger(map, 'resize');
}

function receive_location(l){
	lastPosition = new google.maps.LatLng(last.location.position.latitude, last.location.position.longitude);
	map.setCenter(lastPosition);
	
	if(marker == false){
		marker = new google.maps.Marker({
			position: lastPosition,
			map: map
		});
	}else{
		marker.setPosition(lastPosition);
	}
}

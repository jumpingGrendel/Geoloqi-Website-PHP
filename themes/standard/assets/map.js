
$(function(){
    var latlng = new google.maps.LatLng(45.51, -122.63);

    // Set up the map
    var myOptions = {
      zoom: 13,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map"), myOptions);
    
    resize_map();
});

function resize_map(){
	$("#map").css("height", (window.innerHeight-$("#map-footer").height())+"px").css("width", (window.innerWidth-$("#sidebar").width())+"px");
	google.maps.event.trigger(map, 'resize');
}

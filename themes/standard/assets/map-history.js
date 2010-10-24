/**
 * Handles the history browsing interface of the map. This script relies on some variables that were set up in map.js
 */

var LQHistory = {

	polyline: null,
	loadingStack: [],
	
	mapDragListener: null,
	
	dots: [],
	dotImage: null,

	start: function(){
		this.dotImage = new google.maps.MarkerImage("/themes/standard/assets/images/map-dot.png",
	        new google.maps.Size(8, 8),
	        new google.maps.Point(0, 0),
	        new google.maps.Point(4, 4)
	    );
		this.mapDragListener = google.maps.event.addListener(map, 'dragend', function(){
			LQHistory.get_history();
		});
		$("#history_params input").change(function(){
			if($(this).attr("id") == "history_count"){
				if($(this).val() >= 2000){
					$(this).addClass("warning");
				}else{
					$(this).removeClass("warning");
				}
			}
			LQHistory.get_history();
		});
		this.get_history();
	},
	
	stop: function(){
		google.maps.event.removeListener(this.mapDragListener);
		$("#history_params input").unbind("change");
		LQHistory.polyline.setMap(null);
	},
	
	get_history: function(){
		
		// Prevent doing more requests while there is already a request pending
		if(this.loadingStack.length > 0){
			return true;
		}
		
		var bounds = map.getBounds();
		var sw = bounds.getSouthWest();
		var ne = bounds.getNorthEast();
		
		sw = new google.maps.LatLng(sw.lat() - 0.1, sw.lng() - 0.1);
		ne = new google.maps.LatLng(ne.lat() + 0.1, ne.lng() + 0.1);
		this.loading();
		
		var params = {
			sw: sw.toUrlValue(),
			ne: ne.toUrlValue(),
			geometry: "rectangle"
		};
		
		if($("#history_count").val()){
			params.count = $("#history_count").val();
		}
		if($("#history_accuracy").val()){
			params.accuracy = $("#history_accuracy").val();
		}
		if($("#history_from").val()){
			params.date_from = $("#history_from").val();
		}
		if($("#history_to").val()){
			params.date_to = $("#history_to").val();
		}
		if($("#history_time_from").val()){
			params.time_from = $("#history_time_from").val();
		}
		if($("#history_time_to").val()){
			params.time_to = $("#history_time_to").val();
		}
		if($("#history_thinning").val()){
			params.thinning = $("#history_thinning").val();
		}
		
		$.getJSON("/map/history.ajax", params, function(data){
			var path = new google.maps.MVCArray();

			// Remove the old polyline and make a new one
			if(LQHistory.polyline)
				LQHistory.polyline.setMap(null);

			// Remove the old dots
			if(LQHistory.dots && LQHistory.dots.length > 0){
				for(var i=0; i<LQHistory.dots.length; i++){
					LQHistory.dots[i].setMap(null);
				}
			}
			LQHistory.dots = [];
			
			if($("#history_thinning").val() > 5){
				
				for(var i=0; i<data.length; i++){
					var p = new google.maps.LatLng(data[i].location.position.latitude, data[i].location.position.longitude);
					LQHistory.dots.push(new google.maps.Marker({position: p, map: map, icon: LQHistory.dotImage}));
				}
				
			}else{
				
				for(var i=0; i<data.length; i++){
					var p = new google.maps.LatLng(data[i].location.position.latitude, data[i].location.position.longitude);
					path.push(p);
				}
				
				LQHistory.polyline = new google.maps.Polyline({
					geodesic: true,
					map: map,
					path: path,
					strokeWeight: $("#stroke_weight").val(),
					strokeOpacity: $("#stroke_opacity").val(),
					strokeColor: $("#stroke_color").val()
				});
			}
			LQHistory.loading_done();
		});
	},

	loading: function(){
		this.loadingStack.push(".");
		$("#history_loading div").addClass("loading");
	},
	
	loading_done: function(){
		this.loadingStack.pop();
		if(this.loadingStack.length == 0)
			$("#history_loading div").removeClass("loading");
	}

};

	
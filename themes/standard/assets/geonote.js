
var geonote_marker;
var geonote_circle;

	function sidebar_geonote_start()
	{
		// If the user hasn't logged any points yet, then lastPosition will be false. Use the center of the map in this case.
		var geonote_position;
		if(lastPosition){
			geonote_position = lastPosition;
		}else{
			geonote_position = map.getCenter();
		}
		
		var geonote_image = new google.maps.MarkerImage('/themes/standard/assets/images/chat.png',
			new google.maps.Size(38, 33),
			new google.maps.Point(0,0),
			new google.maps.Point(9, 31));

		var geonote_shadow = new google.maps.MarkerImage('/themes/standard/assets/images/chat-shadow.png',
			new google.maps.Size(55, 33),
			new google.maps.Point(0,0),
			new google.maps.Point(9, 33));

	    // Create the marker at the last known location
		geonote_marker = new google.maps.Marker({
			map: map,
			icon: geonote_image,
			shadow: geonote_shadow,
			position: geonote_position,
			draggable: true,
			title: 'Geonote',
		});
	
	    // Create the circle
		geonote_circle = new google.maps.Circle({
	        map: map,
	        radius: 400, // meters
            strokeColor: "#3e829b",
			fillColor: "#75caea"
	    });
	    // Since Circle and Marker both extend MVCObject, you can bind them
	    // together using MVCObject's bindTo() method.  Here, we're binding
	    // the Circle's center to the Marker's position.
	    // http://code.google.com/apis/maps/documentation/v3/reference.html#MVCObject
		geonote_circle.bindTo('center', geonote_marker, 'position');
	
	    // Clicking the map moves the marker and opens the infowindow
		google.maps.event.addListener(map, 'click', function(event){
			geonote_marker.setPosition(event.latLng);
		});
	
		$(".radius_size").unbind("click").bind("click", function(){
			var size = parseInt($(this).val());
			geonote_circle.setRadius(size);
			if(size <= 500){
				map.setZoom(15);
			}else if(size <= 1200){
				map.setZoom(13);
			}else if(size >= 6000){
				map.setZoom(11);
			}
			map.setCenter(geonote_circle.getCenter());
			loadStats();
		});
		$(".radius_expand").unbind("click").bind("click", function(){
			geonote_circle.setRadius(geonote_circle.getRadius() + 50);
			set_radius_checkboxes();
		}).css({cursor: "pointer"});
		$(".radius_shrink").unbind("click").bind("click", function(){
			geonote_circle.setRadius(geonote_circle.getRadius() - 50);
			set_radius_checkboxes();
		}).css({cursor: "pointer"});
		
		$("#geonote_create").unbind("click").bind("click", function(){
			if($("#geonote_email").val() == $("#geonote_email").attr("title")){
				$("#geonote_email").val("");
			}
			
			$.post("/map/create_geonote.ajax?username=" + username, {
				lat: geonote_marker.getPosition().lat(),
				lng: geonote_marker.getPosition().lng(),
				radius: geonote_circle.getRadius(),
				email: $("#geonote_email").val(),
				text: $("#geonote_text").val()
			}, function(data){
				if(data.result != "ok"){
					alert("There was an error!");
				}else{
					$("#geonote_success").show();
					$("#sidebar_geonote .panel-title").click();
					gb_show({message: "Your Geonote was sent!"});
					setTimeout(gb_hide, 1000);
				}
			}, "json");
		});

		$("#geonote_email").unbind("focus").bind("focus", function(){
			if($(this).val() == $(this).attr("title")){
				$(this).val("").css({color: "#000"});
			}
		}).unbind("change blur").bind("change blur", function(){
			if($(this).val() == "" || $(this).val() == $(this).attr("title")){
				$(this).val($(this).attr("title")).css({color: "#999"});
			}
		}).blur();
		
		$("#geonote_text").unbind("keyup").bind("keyup", function(){
			if($(this).val() == ""){
				$("#geonote_create").attr("disabled", "disabled");
			}else{
				$("#geonote_create").removeAttr("disabled");
			}
		});
		
		set_radius_checkboxes();

		$("#geonote_success").hide();
		$("#geonote_prompt").show();
		map.setZoom(15);
	}
	
	function sidebar_geonote_end(){
		geonote_circle.setMap();
		geonote_marker.setMap();
		$("#geonote_text").val("");
	}
	
	function set_radius_checkboxes(){
		var r = geonote_circle.getRadius();
		var s;
		if(r <= 200){
			s = 120;
		}else if(r <= 800){
			s = 400;
		}else if(r <= 2400){
			s = 1200;
		}else{
			s = 6000;
		}
		$("#radius_size_" + s).attr("checked", "checked");
	}
	
	function load_stats(){
		return true;
		
		$.getJSON("index.php", {
			query: 1,
			lat: geonote_marker.getPosition().lat(),
			lng: geonote_marker.getPosition().lng(),
			radius: geonote_circle.getRadius()
		}, function(data){
			$("#deliveryProb div").width($("#deliveryProb").width() * (data.prob.size / 100)).css({backgroundColor: data.prob.color});
			$("#dpInner").html(data.prob.textInner);
			$("#dpOuter").html(data.prob.textOuter).css({color: data.prob.color});
		});
	}
	

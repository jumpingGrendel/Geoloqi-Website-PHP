
var geonote_marker;
var geonote_circle;

	function start_geonotes()
	{
		if($(".sidebar-geonote").hasClass("active")){
			close_geonote();
			return;
		}
		
		$(".sidebar-geonote").addClass("active");

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
			position: lastPosition,
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
		
		set_radius_checkboxes();

		$("#geonote_create").unbind("click").bind("click", function(){
			$.post("/map/create_geonote.ajax", {
				lat: geonote_marker.getPosition().lat(),
				lng: geonote_marker.getPosition().lng(),
				radius: geonote_circle.getRadius(),
				text: $("#geonote_text").val()
			}, function(data){
				if(data.result != "ok"){
					alert("There was an error!");
				}else{
					$("#geonote_success").show();
					close_geonote();
				}
			}, "json");
		});
	
		$("#geonote_success").hide();
		$("#geonote_prompt").show();
		map.setZoom(15);
	}
	
	function close_geonote(){
		$("#geonote_prompt").hide();
		$(".sidebar-geonote").removeClass("active");
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
	

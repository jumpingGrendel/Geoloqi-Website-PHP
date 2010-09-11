
var GB_WIDTH = 400;
var GB_HEIGHT = 200;
	
function gb_show(opts){
	
	var message = false;
	var html = false;
	
	if(typeof opts.message != "undefined")
		message = opts.message;
	if(typeof opts.html != "undefined")
		html = opts.html;
	if(typeof opts.width != "undefined")
		GB_WIDTH = opts.width;
	if(typeof opts.height != "undefined")
		GB_HEIGHT = opts.height;
	
	$("body").append('<div id="GB_overlay"></div>');
	$("body").append('<div id="GB_window"><div id="GB_frame"><div style="position: absolute; text-align: center; font-size: 14pt; left: ' + ((GB_WIDTH / 2) - 110) + 'px; top: ' + ((GB_HEIGHT / 2) - 21) + 'px">Loading...</div></div></div>');

	$("#GB_overlay").css({ display: "block", opacity: 0 }).animate({ opacity: 0.5 }, 200);
	
	if(message != false){
		html = message;
		$("#GB_frame").addClass("notice");
	}
	if(html != false){
		$("#GB_frame").html(html).css({ display: "block" });
	}
	
	gb_position();
	
	$("#GB_window").css({display: "block"});
	
}

function gb_update(message){
	$("#GB_frame").html(message);
}

function gb_hide(){
	$("#GB_overlay").remove();
	$("#GB_window").remove();
}

function gb_position() {
	var de = document.documentElement;
	var w = self.innerWidth || (de&&de.clientWidth) || document.body.clientWidth;
	var h = self.innerHeight || (de&&de.clientHeight) || document.body.clientHeight;
	$("#GB_window").css({
		width: (GB_WIDTH + 2),
		height: (GB_HEIGHT + 27),
		left: ((w - GB_WIDTH) / 2),
		top: ((h - GB_HEIGHT) / 3)
	});
}

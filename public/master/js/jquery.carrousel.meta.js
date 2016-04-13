$(document).ready(function() {
	var $this = this;
	// main vertical scroll
	
	var api = $("#bloc_meta").scrollable({
	
		// basic settings
		vertical: true,
	
		// up/down keys will always control this scrollable
		keyboard: 'static',
		circular: true ,
		speed: 500,
		// assign left/right keys to the actively viewed scrollable
		onSeek: function(event, i) {
			horizontal.eq(i).data("scrollable").focus();
		}
	
	// main navigator (thumbnail images)
	}).navigator({
		navi :".pagination",
		naviItem : "li"
	})
	.autoscroll({ autoplay: true, interval: 1000, autopause : true });
	
	// horizontal scrollables. each one is circular and has its own navigator instance
	var horizontal = $(".scrollable").scrollable({ circular: true }).navigator(".pagination");
	
	
	// when page loads setup keyboard focus on the first horzontal scrollable
	horizontal.eq(0).data("scrollable").focus();

	$('#btn_play').toggle(function() {
		api.data("scrollable").stop();
		$(this).html('play');
	},function() {
		api.data("scrollable").play();
		$(this).html('pause');
	});

});// JavaScript Document
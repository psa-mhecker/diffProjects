var current_slide_mmd1404 = 1;
var timer_mmd1404, interval1_mmd1404, interval2_mmd1404;
var auto1_mmd1404 = 1;
var auto2_mmd1404 = 1;

$(document).ready(function(){
	window.setTimeout(function(){
		$("#content_mmd1404").css({height : ($("#content_mmd1404").width()*1.333333333)+"px"});

		var bw = $('.btn_timeline_mmd1404').width();
		$('.btn_timeline_mmd1404').css({
			'width':bw+'px',
			'height':bw+'px',
			'lineHeight':bw+'px'
		});

		var cw = $('.btn_slide4_mmd1404').width();
		$('.btn_slide4_mmd1404').css({
			'height':cw+'px'
		});

	},50);
	window.setTimeout(function(){
		var tw = $("#zone_triangles_mmd_1404").width();
		$("#zone_triangles_mmd_1404").css({
			width: tw+'px',
			height : tw+'px'
		});


		$('#turn_mmd1404').css({
			width: (tw*0.50)+'px',
			height: 'auto'
		});
	},200);
	window.setTimeout(function(){
		$("#cta_intro_mmd1404").trigger("touchstart",["auto"]);
	},3000);
});

$(window).resize(function() {
   $("#content_mmd1404").css({height : ($("#content_mmd1404").width()*1.333333333)+"px"});
 	var tw = $("#zone_triangles_mmd_1404").width();

	var bw = $('.btn_timeline_mmd1404').width();
	$('.btn_timeline_mmd1404').css({
		'width':bw+'px',
		'height':bw+'px',
		'lineHeight':bw+'px'
	});

	var cw = $('.btn_slide4_mmd1404').width();
	$('.btn_slide4_mmd1404').css({
		'height':cw+'px'
	});

	$("#zone_triangles_mmd_1404").css({
		width: tw+'px',
		height : tw+'px'
	});

	$('#turn_mmd1404').css({
		width: (tw*0.50)+'px',
		height: 'auto'
	});
});

$("#cta_intro_mmd1404").on("touchstart", function(event, auto){
	$("#intro_mmd1404").fadeOut(400);
	if(auto!=="auto"){
		dataLayer.push({
			"event": "uaevent",
			"eventCategory": "Showroom::AnimationTopProduit::Index",
			"eventAction": "Start",
			"eventLabel": "Démarrez !"
		});
	}
	interval1_mmd1404 = window.setInterval(function(){
		if(auto1_mmd1404==5){
			$("#fond_carre5_mmd1404").trigger("touchstart",["auto"]);
			auto1_mmd1404=1;
		}else{
			$("#carre"+auto1_mmd1404+"_mmd1404").trigger("touchstart",["auto"]);
			auto1_mmd1404++;
		}
	},1500);
	timer_mmd1404 = window.setInterval(function(){
		next_mmd1404();
	},10000);
});

// SLIDE 1

$("#carre1_mmd1404, #partie_carre1_mmd1404").on("touchstart", function(event, auto){

	var matrix = $("#turn_mmd1404").css('transform') ||  $("#turn_mmd1404").css('-webkit-transform') ||  $("#turn_mmd1404").css('-moz-transform') ||  $("#turn_mmd1404").css('-o-transform');
    var values = matrix.split('(')[1].split(')')[0].split(',');
    var coord = {
        a: values[0],
        b: values[1]
    };

    var angle = Math.round(Math.atan2(coord.b, coord.a) * (180/Math.PI));

	$({deg: angle}).animate(
		{deg: 76},
		{
			duration: 500,
			step: function(n, fx){
				$("#turn_mmd1404").css({
					'transform':'rotate('+n+'deg)',
					'-webkit-transform':'rotate('+n+'deg)',
					'-moz-transform':'rotate('+n+'deg)',
					'-o-transform':'rotate('+n+'deg)',
					'-mstransform':'rotate('+n+'deg)'
				});
			},
			complete: function(){
				$("#turn_mmd1404").css({
					'transform':'rotate(76deg)',
					'-webkit-transform':'rotate(76deg)',
					'-moz-transform':'rotate(76deg)',
					'-o-transform':'rotate(76deg)',
					'-ms-transform':'rotate(76deg)'
				});
			}
		}
	);

	$("#voit1_slide1_mmd1404, #voit2_slide1_mmd1404, #voit3_slide1_mmd1404, #voit4_slide1_mmd1404").animate({opacity : 0},500);
	if(auto!=="auto"){
		window.clearInterval(interval1_mmd1404);
		window.clearInterval(timer_mmd1404);
		timer_mmd1404 = window.setInterval(function(){
			next_mmd1404();
		},10000);
	}
	auto1_mmd1404=1;
});

$("#carre2_mmd1404").on("touchstart", function(event, auto){

	var matrix = $("#turn_mmd1404").css('transform') ||  $("#turn_mmd1404").css('-webkit-transform') ||  $("#turn_mmd1404").css('-moz-transform') ||  $("#turn_mmd1404").css('-o-transform');
    var values = matrix.split('(')[1].split(')')[0].split(',');
    var coord = {
        a: values[0],
        b: values[1]
    };

    var angle = Math.round(Math.atan2(coord.b, coord.a) * (180/Math.PI));

	$({deg: angle}).animate(
		{deg: 145},
		{
			duration: 500,
			step: function(n, fx){
				$("#turn_mmd1404").css({
					'transform':'rotate('+n+'deg)',
					'-webkit-transform':'rotate('+n+'deg)',
					'-moz-transform':'rotate('+n+'deg)',
					'-o-transform':'rotate('+n+'deg)',
					'-mstransform':'rotate('+n+'deg)'
				});
			},
			complete: function(){
				$("#turn_mmd1404").css({
					'transform':'rotate(145deg)',
					'-webkit-transform':'rotate(145deg)',
					'-moz-transform':'rotate(145deg)',
					'-o-transform':'rotate(145deg)',
					'-ms-transform':'rotate(145deg)'
				});
			}
		}
	);

	$("#voit1_slide1_mmd1404, #voit2_slide1_mmd1404, #voit4_slide1_mmd1404").animate({opacity : 0},500);
	$("#voit3_slide1_mmd1404").animate({opacity : 1},500);
	if(auto!=="auto"){
		window.clearInterval(interval1_mmd1404);
		window.clearInterval(timer_mmd1404);
		timer_mmd1404 = window.setInterval(function(){
			next_mmd1404();
		},10000);
	}_mmd1404=2;
});

$("#carre3_mmd1404").on("touchstart", function(event, auto){
	var matrix = $("#turn_mmd1404").css('transform') ||  $("#turn_mmd1404").css('-webkit-transform') ||  $("#turn_mmd1404").css('-moz-transform') ||  $("#turn_mmd1404").css('-o-transform');
    var values = matrix.split('(')[1].split(')')[0].split(',');
    var coord = {
        a: values[0],
        b: values[1]
    };

    var angle = Math.round(Math.atan2(coord.b, coord.a) * (180/Math.PI));

	$({deg: angle}).animate(
		{deg: 216},
		{
			duration: 500,
			step: function(n, fx){
				$("#turn_mmd1404").css({
					'transform':'rotate('+n+'deg)',
					'-webkit-transform':'rotate('+n+'deg)',
					'-moz-transform':'rotate('+n+'deg)',
					'-o-transform':'rotate('+n+'deg)',
					'-mstransform':'rotate('+n+'deg)'
				});
			},
			complete: function(){
				$("#turn_mmd1404").css({
					'transform':'rotate(216deg)',
					'-webkit-transform':'rotate(216deg)',
					'-moz-transform':'rotate(216deg)',
					'-o-transform':'rotate(216deg)',
					'-ms-transform':'rotate(216deg)'
				});
			}
		}
	);

	$("#voit1_slide1_mmd1404, #voit4_slide1_mmd1404, #voit3_slide1_mmd1404").animate({opacity : 0},500);
	$("#voit2_slide1_mmd1404").animate({opacity : 1},500);
	if(auto!=="auto"){
		window.clearInterval(interval1_mmd1404);
		window.clearInterval(timer_mmd1404);
		timer_mmd1404 = window.setInterval(function(){
			next_mmd1404();
		},10000);
	}
	auto1_mmd1404=3;
});

$("#carre4_mmd1404").on("touchstart", function(event, auto){
	var matrix = $("#turn_mmd1404").css('transform') ||  $("#turn_mmd1404").css('-webkit-transform') ||  $("#turn_mmd1404").css('-moz-transform') ||  $("#turn_mmd1404").css('-o-transform');
    var values = matrix.split('(')[1].split(')')[0].split(',');
    var coord = {
        a: values[0],
        b: values[1]
    };

    var angle = Math.round(Math.atan2(coord.b, coord.a) * (180/Math.PI));

	$({deg: (360+angle)}).animate(
		{deg: 290},
		{
			duration: 500,
			step: function(n, fx){
				$("#turn_mmd1404").css({
					'transform':'rotate('+n+'deg)',
					'-webkit-transform':'rotate('+n+'deg)',
					'-moz-transform':'rotate('+n+'deg)',
					'-o-transform':'rotate('+n+'deg)',
					'-mstransform':'rotate('+n+'deg)'
				});
			},
			complete: function(){
				$("#turn_mmd1404").css({
					'transform':'rotate(290deg)',
					'-webkit-transform':'rotate(290deg)',
					'-moz-transform':'rotate(290deg)',
					'-o-transform':'rotate(290deg)',
					'-ms-transform':'rotate(290deg)'
				});
			}
		}
	);

	$("#voit1_slide1_mmd1404, #voit2_slide1_mmd1404, #voit3_slide1_mmd1404").animate({opacity : 0},500);
	$("#voit4_slide1_mmd1404").animate({opacity : 1},500);
	if(auto!=="auto"){
		window.clearInterval(interval1_mmd1404);
		window.clearInterval(timer_mmd1404);
		timer_mmd1404 = window.setInterval(function(){
			next_mmd1404();
		},10000);
	}
	auto1_mmd1404=4;
});

$("#fond_carre5_mmd1404").on("touchstart", function(event, auto){
	var matrix = $("#turn_mmd1404").css('transform') ||  $("#turn_mmd1404").css('-webkit-transform') ||  $("#turn_mmd1404").css('-moz-transform') ||  $("#turn_mmd1404").css('-o-transform');
    var values = matrix.split('(')[1].split(')')[0].split(',');
    var coord = {
        a: values[0],
        b: values[1]
    };

    var angle = Math.round(Math.atan2(coord.b, coord.a) * (180/Math.PI));

	$({deg: angle}).animate(
		{deg: 5},
		{
			duration: 500,
			step: function(n, fx){
				$("#turn_mmd1404").css({
					'transform':'rotate('+n+'deg)',
					'-webkit-transform':'rotate('+n+'deg)',
					'-moz-transform':'rotate('+n+'deg)',
					'-o-transform':'rotate('+n+'deg)',
					'-mstransform':'rotate('+n+'deg)'
				});
			},
			complete: function(){
				$("#turn_mmd1404").css({
					'transform':'rotate(5deg)',
					'-webkit-transform':'rotate(5deg)',
					'-moz-transform':'rotate(5deg)',
					'-o-transform':'rotate(5deg)',
					'-ms-transform':'rotate(5deg)'
				});
			}
		}
	);

	$("#voit4_slide1_mmd1404, #voit2_slide1_mmd1404, #voit3_slide1_mmd1404").animate({opacity : 0},500);
	$("#voit1_slide1_mmd1404").animate({opacity : 1},500);
	if(auto!=="auto"){
		window.clearInterval(interval1_mmd1404);
		window.clearInterval(timer_mmd1404);
		timer_mmd1404 = window.setInterval(function(){
			next_mmd1404();
		},10000);
	}
	auto1_mmd1404=5;
});

$(".btn_slide4_mmd1404").on("touchstart", function(event, auto){
	var id_mmd1404 = $(this).attr('href');
	var selec_mmd1404 = parseInt(id_mmd1404.substring(7,8));

	var keyWording = null;

	$('.btn_slide4_mmd1404').css({
		'backgroundColor':'#c8c8c8'
	});

	$('.screen_mmd1404').css({
		'opacity':0
	});

	$(id_mmd1404).css({
		'opacity':1
	});

	$(this).css({
		'backgroundColor':'#c7b613'
	});

	keyWording = $(this).data('keyWording');

	if(keyWording.length > 0)
		$("#name_tablet_mmd1404").html(keyWording);


	auto2_mmd1404 = selec_mmd1404;
	if(auto!=="auto"){
		window.clearInterval(interval2_mmd1404);
		window.clearInterval(timer_mmd1404);
		timer_mmd1404 = window.setInterval(function(){
			next_mmd1404();
		},10000);
	}

	return false;
});

$(".btn_timeline_mmd1404").on("touchstart", function(){
	window.clearInterval(timer_mmd1404);
	var id_mmd1404 = $(this).attr("id");
	var selec_mmd1404 = parseInt(id_mmd1404.substring(8,9));
	$(".current_timeline_mmd1404").removeClass("current_timeline_mmd1404");
	$(this).addClass("current_timeline_mmd1404");
	if(selec_mmd1404>current_slide_mmd1404){
		$("#slide"+current_slide_mmd1404+"_mmd1404").animate({left: "-100%"},300);
		$("#slide"+selec_mmd1404+"_mmd1404").animate({left: "100%"},0).animate({left: "0%"},300);
	}else if(selec_mmd1404<current_slide_mmd1404){
		$("#slide"+current_slide_mmd1404+"_mmd1404").animate({left: "100%"},300);
		$("#slide"+selec_mmd1404+"_mmd1404").animate({left: "-100%"},0).animate({left: "0%"},300);
	}
	if(selec_mmd1404==5){
		anim_fin_mmd1404();
	}
	if(selec_mmd1404==4){
		window.clearInterval(interval2_mmd1404);
		interval2_mmd1404 = window.setInterval(function(){
			if(auto2_mmd1404==8){
				$("#dessus_btn1_slide4_mmd1404").trigger("touchstart",["auto"]);
				auto2_mmd1404=1;
			}else{
				$("#dessus_btn"+auto2_mmd1404+"_slide4_mmd1404").trigger("touchstart",["auto"]);
				auto2_mmd1404++;
			}
		},2000);
	}
	if(selec_mmd1404!==1){
		window.clearInterval(interval1_mmd1404);
	}
	if(selec_mmd1404!==4){
		window.clearInterval(interval2_mmd1404);
	}
	if(selec_mmd1404==1){
		interval1_mmd1404 = window.setInterval(function(){
		if(auto1_mmd1404==5){
				$("#fond_carre5_mmd1404").trigger("touchstart",["auto"]);
				auto1_mmd1404=1;
			}else{
				$("#carre"+auto1_mmd1404+"_mmd1404").trigger("touchstart",["auto"]);
				auto1_mmd1404++;
			}
		},1500);
	}
	current_slide_mmd1404=selec_mmd1404;
	timer_mmd1404 = window.setInterval(function(){
		next_mmd1404();
	},10000);
});

$("#content_mmd1404").swipe({

     swipeLeft: function() {
     	next_mmd1404();
     },
     swipeRight: function() {
     	prev_mmd1404();
     },
     min_move_x: 35,
     min_move_y: 35,
     preventDefaultEvents: false
});

function next_mmd1404(){
	window.clearInterval(timer_mmd1404);
	if(current_slide_mmd1404==5){

		$("#slide5_mmd1404").animate({left: "-100%"},300);
		$("#slide1_mmd1404").animate({left: "100%"},0).animate({left: "0%"},300);
		$("#timeline5_mmd1404").removeClass("current_timeline_mmd1404");
		$("#timeline1_mmd1404").addClass("current_timeline_mmd1404");

		current_slide_mmd1404=1;

		interval1_mmd1404 = window.setInterval(function(){
			if(auto1_mmd1404==5){
				$("#fond_carre5_mmd1404").trigger("touchstart",["auto"]);
				auto1_mmd1404=1;
			}else{
				$("#carre"+auto1_mmd1404+"_mmd1404").trigger("touchstart",["auto"]);
				auto1_mmd1404 = auto1_mmd1404 + 1;
			}
		},1500);
	}else{
		if(current_slide_mmd1404==1){
			window.clearInterval(interval1_mmd1404);
		}
		else if(current_slide_mmd1404==4){
			window.clearInterval(interval2_mmd1404);
			anim_fin_mmd1404();
		}
		else if(current_slide_mmd1404==3){
			interval2_mmd1404 = window.setInterval(function(){
				if(auto2_mmd1404==8){
					$("#dessus_btn1_slide4_mmd1404").trigger("touchstart",["auto"]);
					auto2_mmd1404 = 1;
				}else{
					$("#dessus_btn"+auto2_mmd1404+"_slide4_mmd1404").trigger("touchstart",["auto"]);
					auto2_mmd1404 = auto2_mmd1404 + 1;
				}
			},2000);
		}

		$("#slide"+current_slide_mmd1404+"_mmd1404").animate({left: "-100%"}, 300);
		$("#slide"+(parseInt(current_slide_mmd1404)+1)+"_mmd1404").animate({left: "100%"},0).animate({left: "0%"},300);
		$("#timeline"+current_slide_mmd1404+"_mmd1404").removeClass("current_timeline_mmd1404");
		$("#timeline"+(parseInt(current_slide_mmd1404)+1)+"_mmd1404").addClass("current_timeline_mmd1404");

		current_slide_mmd1404 = current_slide_mmd1404 + 1;
	}
	timer_mmd1404 = window.setInterval(function(){
		next_mmd1404();
	},10000);
}

function prev_mmd1404(){
	window.clearInterval(timer_mmd1404);
	if(current_slide_mmd1404==1){
		anim_fin_mmd1404();
		window.clearInterval(interval1_mmd1404);
		$("#slide1_mmd1404").animate({left: "100%"},300);
		$("#slide5_mmd1404").animate({left: "-100%"},0).animate({left: "0%"},300);
		$("#timeline1_mmd1404").removeClass("current_timeline_mmd1404");
		$("#timeline5_mmd1404").addClass("current_timeline_mmd1404");
		current_slide_mmd1404=5;
	}else{
		if(current_slide_mmd1404==2){
			interval1_mmd1404 = window.setInterval(function(){
				if(auto1_mmd1404==5){
					$("#fond_carre5_mmd1404").trigger("touchstart",["auto"]);
					auto1_mmd1404=1;
				}else{
					$("#carre"+auto1_mmd1404+"_mmd1404").trigger("touchstart",["auto"]);
					auto1_mmd1404++;
				}
			},1500);
		}
		else if(current_slide_mmd1404==5){
			interval2_mmd1404 = window.setInterval(function(){
				if(auto2_mmd1404==8){
					$("#dessus_btn1_slide4_mmd1404").trigger("touchstart",["auto"]);
					auto2_mmd1404=1;
				}else{
					$("#dessus_btn"+auto2_mmd1404+"_slide4_mmd1404").trigger("touchstart",["auto"]);
					auto2_mmd1404++;
				}
			},2000);
		}
		else if(current_slide_mmd1404==4){
			window.clearInterval(interval2_mmd1404);
		}
		$("#slide"+current_slide_mmd1404+"_mmd1404").animate({left: "100%"},300);
		$("#slide"+(current_slide_mmd1404-1)+"_mmd1404").animate({left: "-100%"},0).animate({left: "0%"},300);
		$("#timeline"+current_slide_mmd1404+"_mmd1404").removeClass("current_timeline_mmd1404");
		$("#timeline"+(current_slide_mmd1404-1)+"_mmd1404").addClass("current_timeline_mmd1404");
		current_slide_mmd1404 = current_slide_mmd1404 - 1;
	}
	timer_mmd1404 = window.setInterval(function(){
		next_mmd1404();
	},10000);
}

function anim_fin_mmd1404(){
	$("#part2_slide5_mmd1404").fadeOut(0);
	$("#part1_slide5_mmd1404").fadeIn(0);
	window.setTimeout(function(){
		$("#part1_slide5_mmd1404").fadeOut(300);
		$("#part2_slide5_mmd1404").fadeIn(300);
	},3000);
}

$("#cta_end1_mmd1404").on("touchstart", function(){
		dataLayer.push({
			"event": "uaevent",
			"eventCategory": "Showroom::AnimationTopProduit::Step5",
			"eventAction": "Configurator::c4cactus",
			"eventLabel": "Essayez-la !"
		});
});

$("#cta_end2_mmd1404").on("touchstart", function(){
		dataLayer.push({
			"event": "uaevent",
			"eventCategory": "Showroom::AnimationTopProduit::Step5",
			"eventAction": "Forms::TestDrive::c4cactus",
			"eventLabel": "Configurez-la !"
		});
});

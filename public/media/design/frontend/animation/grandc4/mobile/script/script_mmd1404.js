var position_slider_mmd1404 = 1;
var position_tablet_mmd1404 = 1;
var position_generale_mmd1404 = 1;
var timeouts_mmd1404 = [];
var first_timeout = null;
var mili_mmd1404 = 9;
var centi_mmd1404 = 9;
var num_mmd1404 = 9;
var interval1_mmd1404, interval2_mmd1404, interval3_mmd1404, intervalsky_mmd1404;
var timer1_mmd1404,timer2_mmd1404, timer3_mmd1404;
var timer_auto_mmd1404 = null;
var auto1_mmd1404 = 1;
var auto2_mmd1404 = 2;
var current360_mmd1404 = 30;
var interval360_mmd1404, interval_wheel_mmd1404, interval_city_mmd1404, interval_cligne_mmd1404 = null;
var auto_mmd1404=0;
var selected_mmd1404=0;
var video_mmd1404 = document.getElementById("video_mmd1404");
var degree_mmd1404 = 0;

var dataLayer = window['dataLayer'] || [];

$(document).ready(function(){

	if( (/Android|iPad/i).test(window.navigator.userAgent)){
		$('#content_mmd1404').css({
			'marginLeft':'-40px'
		});
	}

	anim_intro_mmd1404();
	// anim_slide_mmd1404(5);
});

(function(){

$('.editable-wording').each(function(index, value){
	$(this).html($(this).data('keyWording'));
});

$('.displayed_mmd1404').each(function(index, value){
	if($(this).data('isDisplayed')==true){
		$(this).css({display : "block"});
	}else{
		$(this).css({display : "none"});
	}
});


}).call(this);

function anim_intro_mmd1404(){

	if (isIE () && isIE () <= 9) {
		$("#wheel1_intro_mmd1404").css({display : "none"});
		$("#wheel2_intro_mmd1404").css({display : "none"});

		$('#car_intro_mmd1404').css({'display':'none'});
		$('#car_intro_mmd1404_ie').css({'display':'block'});
	}

	$("#zone_car_intro_mmd1404").transition({left : "62px", top : "49px"},2400);
		$({deg: 0}).animate(
		{deg: -1200},
			{
				duration: 2400,
				easing: "easeOutQuad",
				step: function(n){
					$("#wheel1_intro_mmd1404").css({
						'transform':'rotate('+n+'deg)'
					});
				}
			}
		);

		$({deg: 0}).animate(
		{deg: -1200},
			{
				duration: 2400,
				easing: "easeOutQuad",
				step: function(n){
					$("#wheel2_intro_mmd1404").css({
						'transform':'rotate('+n+'deg)'
					});
				}
			}
		);
	timeouts_mmd1404.push(setTimeout(function(){
		$("#line1_intro_mmd1404").animate({opacity : 1},400);
	},1400));
	timeouts_mmd1404.push(setTimeout(function(){
		$("#line2_intro_mmd1404").animate({opacity : 1},400);
	},1600));
	timeouts_mmd1404.push(setTimeout(function(){
		$("#line3_intro_mmd1404").animate({opacity : 1},400);
	},1800));
	timeouts_mmd1404.push(setTimeout(function(){
		$("#line4_intro_mmd1404").animate({opacity : 1},400);
	},2000));
	timeouts_mmd1404.push(setTimeout(function(){
		$("#cta_begin_mmd1404").fadeIn(400);
	},2800));

	first_timeout = setTimeout(function(){
		$("#cta_begin_mmd1404").trigger("click");
	},7800);

}


function anim_slide_mmd1404(next_mmd1404){
	if(next_mmd1404==1){
		auto1_mmd1404 = 1;
		clearTimeout(timer_auto_mmd1404);
		$("#zone_slide1_mmd1404").fadeIn();
		$("#zone_slide2_mmd1404, #zone_slide3_mmd1404, #zone_slide4_mmd1404, #zone_slide5_mmd1404").fadeOut();

		//initial
		if (isIE () && isIE () <= 9) {
			$("#wheel1_slide1_mmd1404").css({display : "none"});
			$("#wheel2_slide1_mmd1404").css({display : "none"});

			$('#car_slide1_mmd1404').css({'display':'none'});
			$('#car_slide1_mmd1404_ie').css({'display':'block'});
		}

		$("#line1_slide1_mmd1404, #line2_slide1_mmd1404, #line3_slide1_mmd1404").animate({opacity : 0, "margin-left" : "0px"},0);
		$("#stuff1_mmd1404, #stuff2_mmd1404, #stuff3_mmd1404, #stuff4_mmd1404, #stuff5_mmd1404, #stuff6_mmd1404, #stuff7_mmd1404").animate({opacity : 0, "margin-left" : "0px", left : "0px", top : "0px"},0);
		$("#zone_car_slide1_mmd1404").animate({left: "1480px", opacity : 1},0);
		$("#coffre_mmd1404").animate({rotate : "-92deg"},0);
		$("#wheel1_slide1_mmd1404, #wheel2_slide1_mmd1404").animate({rotate : "0deg"},0);

		//anim
		timeouts_mmd1404.push(setTimeout(function(){
			$("#line1_slide1_mmd1404").animate({opacity : 1},400);
		},200));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#line2_slide1_mmd1404").animate({opacity : 1},400);
		},400));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#line3_slide1_mmd1404").animate({opacity : 1},400);
		},600));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#stuff1_mmd1404, #stuff2_mmd1404, #stuff3_mmd1404, #stuff4_mmd1404, #stuff5_mmd1404, #stuff6_mmd1404, #stuff7_mmd1404").animate({opacity : 1},400);
			$("#zone_car_slide1_mmd1404").transition({left : "565px"},2200, "easeOutQuad");
			$({deg: 0}).animate(
			{deg: -1000},
				{
					duration: 2200,
					easing: "easeOutQuad",
					step: function(n){
						$("#wheel1_slide1_mmd1404").css({
							'transform':'rotate('+n+'deg)'
						});
					}
				}
			);

			$({deg: 0}).animate(
			{deg: -1000},
				{
					duration: 2200,
					easing: "easeOutQuad",
					step: function(n){
						$("#wheel2_slide1_mmd1404").css({
							'transform':'rotate('+n+'deg)'
						});
					}
				}
			);
		},800));

		timeouts_mmd1404.push(setTimeout(function(){
			$({deg: -92}).animate(
			{deg: 0},
				{
					duration: 600,
					easing: "easeOutQuad",
					step: function(n){
						$("#coffre_mmd1404").css({
							'transform':'rotate('+n+'deg)'
						});
					}
				}
			);
		},3300));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#stuff1_mmd1404").animate({left : "275px", top: "30px"},500);
		},3800));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#stuff2_mmd1404").animate({left : "290px", top: "35px"},500);
			$("#stuff1_mmd1404").animate({opacity : 0},0);
		},4000));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#stuff3_mmd1404").animate({left : "310px", top: "20px"},500);
			$("#stuff2_mmd1404").animate({opacity : 0},0);
		},4200));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#stuff4_mmd1404").animate({left : "315px", top: "0px"},500);
			$("#stuff3_mmd1404").animate({opacity : 0},0);
		},4400));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#stuff5_mmd1404").animate({left : "320px", top: "-35px"},500);
			$("#stuff4_mmd1404").animate({opacity : 0},0);
		},4600));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#stuff6_mmd1404").animate({left : "325px", top: "50px"},500);
			$("#stuff5_mmd1404").animate({opacity : 0},0);
		},4800));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#stuff7_mmd1404").animate({left : "320px", top: "32px"},500);
			$("#stuff6_mmd1404").animate({opacity : 0},0);
		},5000));

		timeouts_mmd1404.push(setTimeout(function(){
			$({deg: 0}).animate(
			{deg: -92},
				{
					duration: 600,
					easing: "easeOutQuad",
					step: function(n){
						$("#coffre_mmd1404").css({
							'transform':'rotate('+n+'deg)'
						});
					}
				}
			);
			$("#stuff7_mmd1404").animate({opacity : 0},0);
		},5600));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#zone_car_slide1_mmd1404").animate({left : "1480px"},2200, "easeInQuad");
			$({deg: -1000}).animate(
				{deg: 0},
					{
						duration: 2200,
						easing: "easeInQuad",
						step: function(n){
							$("#wheel1_slide1_mmd1404").css({
								'transform':'rotate('+n+'deg)'
							});
						}
					}
				);

				$({deg: -1000}).animate(
				{deg: 0},
					{
						duration: 2200,
						easing: "easeInQuad",
						step: function(n){
							$("#wheel2_slide1_mmd1404").css({
								'transform':'rotate('+n+'deg)'
							});
						}
					}
				);

		},6300));


		timer_auto_mmd1404 = setTimeout(function(){
			anim_auto_mmd1404();
		},8500);
	}
	else if(next_mmd1404==2){
		clearTimeout(timer_auto_mmd1404);
		$("#zone_slide2_mmd1404").fadeIn();
		$("#zone_slide1_mmd1404, #zone_slide3_mmd1404, #zone_slide4_mmd1404, #zone_slide5_mmd1404").fadeOut();

		//initial
		$("#line1_slide2_mmd1404, #line2_slide2_mmd1404, #line3_slide2_mmd1404").animate({opacity : 0, "margin-left" : "0px"},0);
		$("#contain_picto_siege_mmd1404").animate({opacity : 0, "margin-left" : "0px"},0);
		$("#picto_siege2_mmd1404, #picto_siege3_mmd1404").animate({opacity : 0},0);
		$("#img_siege2_mmd1404, #img_siege3_mmd1404").animate({opacity : 0},0);

		//anim
		timeouts_mmd1404.push(setTimeout(function(){
			$("#line1_slide2_mmd1404").animate({opacity : 1},400);
		},200));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#line2_slide2_mmd1404").animate({opacity : 1},400);
		},400));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#line3_slide2_mmd1404").animate({opacity : 1},400);
		},600));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#contain_picto_siege_mmd1404").animate({opacity : 1},400);
		},800));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#img_siege2_mmd1404").animate({opacity : 1},400);
		},1200));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#img_siege3_mmd1404").animate({opacity : 1},400);
		},1800));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#picto_siege2_mmd1404").animate({opacity : 1},400);
		},2200));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#picto_siege3_mmd1404").animate({opacity : 1},400);
		},2600));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#img_siege2_mmd1404").animate({opacity : 0},400);
			$("#img_siege3_mmd1404").animate({opacity : 0},400);
		},3500));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#img_siege2_mmd1404").animate({opacity : 1},400);
		},4100));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#img_siege3_mmd1404").animate({opacity : 1},400);
		},4700));

		timer_auto_mmd1404 = setTimeout(function(){
			anim_auto_mmd1404();
		},9700);
	}
	else if(next_mmd1404==3){
    clearTimeout(timer_auto_mmd1404);
		$("#zone_slide3_mmd1404").fadeIn();
		$("#zone_slide2_mmd1404, #zone_slide1_mmd1404, #zone_slide4_mmd1404, #zone_slide5_mmd1404").fadeOut();

		//initial
		$("#line1_slide3_mmd1404, #line2_slide3_mmd1404, #line3_slide3_mmd1404, #line4_slide3_mmd1404").animate({opacity : 0, "margin-left" : "0px"},0);
		$("#pic_slide3_mmd1404").animate({opacity : 0},0);
		$("#plus_small_mmd1404").animate({opacity : 0},0);
		$("#plus_big_mmd1404").css({display : "none"});

		//anim
		timeouts_mmd1404.push(setTimeout(function(){
			$("#line1_slide3_mmd1404").animate({opacity : 1},400);
		},200));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#line2_slide3_mmd1404").animate({opacity : 1},400);
		},400));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#line3_slide3_mmd1404").animate({opacity : 1},400);
		},600));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#line4_slide3_mmd1404").animate({opacity : 1},400);
		},800));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#pic_slide3_mmd1404").animate({opacity : 1},600);
			$("#plus_small_mmd1404").animate({opacity : 1},600);
		},1200));

		timer_auto_mmd1404 = setTimeout(function(){
			anim_auto_mmd1404();
		},6800);
	}
	else if(next_mmd1404==4){
        clearTimeout(timer_auto_mmd1404);
		$("#zone_slide4_mmd1404").fadeIn();
		$("#zone_slide2_mmd1404, #zone_slide3_mmd1404, #zone_slide1_mmd1404, #zone_slide5_mmd1404").fadeOut();

		//initial
		$("#zone_car_slide4_mmd1404").animate({left: "-480px", "margin-left" : "0px"},0);
		$("#line1_slide4_mmd1404, #line2_slide4_mmd1404").animate({"margin-left" : "0px"},0);
		$("#cache_txt_slide4_mmd1404").animate({width : "688px", left : "120px"},0);
		$("#waves_mmd1404").css({display : "none"});
		$("#car_slide4_mmd1404").animate({top: "0px"},0);
		$("#waves_mmd1404").animate({top: "-6px"},0);


		//anim
		$("#zone_car_slide4_mmd1404").animate({left : "850px"},5000, "easeOutQuad");

		timeouts_mmd1404.push(setTimeout(function(){
			$("#waves_mmd1404").fadeIn(90);
		},300));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#car_slide4_mmd1404").animate({top : "-39px"},1100, "easeOutQuad");
			$("#waves_mmd1404").animate({top : "-45px"},1100, "easeOutQuad");
		},700));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#cache_txt_slide4_mmd1404").animate({left : "780px"},2700, "easeOutQuad");
		},1100));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#waves_mmd1404").fadeOut(300);
		},3000));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#car_slide4_mmd1404").animate({top : "0px"},1500, "easeOutQuad");
			$("#waves_mmd1404").animate({top : "-6px"},1500, "easeOutQuad");
		},3200));


		timer_auto_mmd1404 = setTimeout(function(){
			anim_auto_mmd1404();
		},10300);


	}
	else if(next_mmd1404==5){
        clearTimeout(timer_auto_mmd1404);
		$("#zone_slide5_mmd1404").fadeIn();
		$("#zone_slide2_mmd1404, #zone_slide3_mmd1404, #zone_slide4_mmd1404, #zone_slide1_mmd1404").fadeOut();

		//initial
		$("#car_slide5_mmd1404").animate({opacity : 0, "margin-left" : "0px"},0);
		$("#line1_slide5_mmd1404, #line2_slide5_mmd1404, #line3_slide5_mmd1404, #line4_slide5_mmd1404, #zone_gouttes_mmd1404, #line1bis_slide5_mmd1404, #line2bis_slide5_mmd1404, #line3bis_slide5_mmd1404, #line4bis_slide5_mmd1404").animate({opacity : 0, "margin-left" : "0px"},0);
		$("#zone1_end_mmd1404").css({display : "block"});
		$("#zone2_end_mmd1404").css({display : "none"});
		$("#zone_cta_fin_mmd1404").css({display : "none"});
		$(".back_goutte_mmd1404").animate({top : "28px"},0);
		$("#cta_fin1_mmd1404, #cta_fin2_mmd1404").animate({"margin-left" : "0px"},0);

		//anim
		timeouts_mmd1404.push(setTimeout(function(){
			$("#car_slide5_mmd1404").animate({opacity : 1},800);
			$("#line1_slide5_mmd1404").animate({opacity : 1},400);
		},200));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#line2_slide5_mmd1404").animate({opacity : 1},400);
		},400));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#line3_slide5_mmd1404").animate({opacity : 1},400);
		},600));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#line4_slide5_mmd1404").animate({opacity : 1},400);
		},800));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#zone_gouttes_mmd1404").animate({opacity : 1},400);
		},1000));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#zone_goutte1>.back_goutte_mmd1404, #zone_goutte12>.back_goutte_mmd1404").animate({top : "1px"},1000);
		},1200));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#zone_goutte1>.back_goutte_mmd1404, #zone_goutte12>.back_goutte_mmd1404").animate({top : "1px"},1000);
		},1300));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#zone_goutte2>.back_goutte_mmd1404, #zone_goutte11>.back_goutte_mmd1404").animate({top : "1px"},1000);
		},1400));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#zone_goutte3>.back_goutte_mmd1404, #zone_goutte10>.back_goutte_mmd1404").animate({top : "1px"},1000);
		},1500));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#zone_goutte4>.back_goutte_mmd1404, #zone_goutte9>.back_goutte_mmd1404").animate({top : "1px"},1000);
		},1600));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#zone_goutte5>.back_goutte_mmd1404, #zone_goutte8>.back_goutte_mmd1404").animate({top : "1px"},1000);
		},1700));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#zone_goutte6>.back_goutte_mmd1404, #zone_goutte7>.back_goutte_mmd1404").animate({top : "1px"},1000);
		},1800));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#zone1_end_mmd1404").fadeOut(300);
			$("#zone2_end_mmd1404").fadeIn(300);
		},4000));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#line1bis_slide5_mmd1404").animate({opacity : 1},400);
		},4200));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#line2bis_slide5_mmd1404").animate({opacity : 1},400);
		},4400));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#line3bis_slide5_mmd1404").animate({opacity : 1},400);
		},4600));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#line4bis_slide5_mmd1404").animate({opacity : 1},400);
		},4800));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#zone2_end_mmd1404").fadeOut(300);
		},8600));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#zone_cta_fin_mmd1404").fadeIn(300);
		},8800));

		timer_auto_mmd1404 = setTimeout(function(){
			anim_auto_mmd1404();
		},20000);

	}
}

function leave_slide_mmd1404(current_mmd1404){
	if(current_mmd1404==1){
		 $("#line1_slide1_mmd1404").clearQueue().animate({"margin-left" : "-1500px"},600,"easeInSine");
		 setTimeout(function(){
		 	$("#line2_slide1_mmd1404").clearQueue().animate({"margin-left" : "-1500px"},600,"easeInSine");
		 },100);
		 setTimeout(function(){
		 	$("#line3_slide1_mmd1404").clearQueue().animate({"margin-left" : "-1500px"},600,"easeInSine");
		 },200);
		 setTimeout(function(){
		 	$("#stuff1_mmd1404, #stuff2_mmd1404, #stuff3_mmd1404, #stuff4_mmd1404, #stuff5_mmd1404, #stuff6_mmd1404, #stuff7_mmd1404").clearQueue().animate({"margin-left" : "-1500px"},600,"easeInSine");
		 },300);
		 setTimeout(function(){
	 		$("#zone_car_slide1_mmd1404").clearQueue().animate({opacity : 0},600,"easeInSine");
	 	},400);
	}
	else if(current_mmd1404==2){
		$("#line1_slide2_mmd1404").clearQueue().animate({"margin-left" : "-1500px"},600,"easeInSine");
		 setTimeout(function(){
		 	$("#line2_slide2_mmd1404").clearQueue().animate({"margin-left" : "-1500px"},600,"easeInSine");
		 },100);
		 setTimeout(function(){
		 	$("#line3_slide2_mmd1404").clearQueue().animate({"margin-left" : "-1500px"},600,"easeInSine");
		 },200);
		 setTimeout(function(){
		 	$("#contain_picto_siege_mmd1404").clearQueue().animate({"margin-left" : "-1500px"},600,"easeInSine");
		 },300);
	}
	else if(current_mmd1404==3){
		$("#line1_slide3_mmd1404").clearQueue().animate({"margin-left" : "-1500px"},600,"easeInSine");
		setTimeout(function(){
		 	$("#line2_slide3_mmd1404").clearQueue().animate({"margin-left" : "-1500px"},600,"easeInSine");
		 },100);
		 setTimeout(function(){
		 	$("#line3_slide3_mmd1404").clearQueue().animate({"margin-left" : "-1500px"},600,"easeInSine");
		 },200);
		 setTimeout(function(){
		 	$("#line4_slide3_mmd1404").clearQueue().animate({"margin-left" : "-1500px"},600,"easeInSine");
		 },300);
		 setTimeout(function(){
	 		$("#pic_slide3_mmd1404").clearQueue().animate({opacity : 0},600,"easeInSine");
	 		$("#plus_small_mmd1404").clearQueue().animate({opacity : 0},600,"easeInSine");
	 		$("#plus_big_mmd1404").css({display : "none"});
	 	},400);

	}
	else if(current_mmd1404==4){
		$("#line1_slide4_mmd1404").clearQueue().animate({"margin-left" : "-2000px"},600,"easeInSine");
	 	setTimeout(function(){
	 		$("#line2_slide4_mmd1404").clearQueue().animate({"margin-left" : "-2000px"},600,"easeInSine");
	 	},200);
	 	setTimeout(function(){
	 		$("#zone_car_slide4_mmd1404").clearQueue().animate({"margin-left" : "-2500px"},600,"easeInSine");
	 	},300);
	}
	else if(current_mmd1404==5){
		$("#car_slide5_mmd1404").clearQueue().animate({"margin-left" : "-2500px"},600,"easeInSine");
		$("#line1_slide5_mmd1404").clearQueue().animate({"margin-left" : "-2500px"},600,"easeInSine");
		$("#line1bis_slide5_mmd1404").clearQueue().animate({"margin-left" : "-2500px"},600,"easeInSine");
		$("#cta_fin1_mmd1404").clearQueue().animate({"margin-left" : "-2500px"},600,"easeInSine");
		setTimeout(function(){
		 	$("#line2_slide5_mmd1404").clearQueue().animate({"margin-left" : "-2500px"},600,"easeInSine");
		 	$("#line2bis_slide5_mmd1404").clearQueue().animate({"margin-left" : "-2500px"},600,"easeInSine");
		 	$("#cta_fin2_mmd1404").clearQueue().animate({"margin-left" : "-2500px"},600,"easeInSine");
		 },100);
		 setTimeout(function(){
		 	$("#line3_slide5_mmd1404").clearQueue().animate({"margin-left" : "-2500px"},600,"easeInSine");
		 	$("#line3bis_slide5_mmd1404").clearQueue().animate({"margin-left" : "-2500px"},600,"easeInSine");
		 },200);
		 setTimeout(function(){
		 	$("#line4_slide5_mmd1404").clearQueue().animate({"margin-left" : "-2500px"},600,"easeInSine");
		 	$("#line4bis_slide5_mmd1404").clearQueue().animate({"margin-left" : "-2500px"},600,"easeInSine");
		 },300);
		 setTimeout(function(){
		 	$("#zone_gouttes_mmd1404").clearQueue().animate({"margin-left" : "-2500px"},600,"easeInSine");
		 },400);
	}
}

//NAV
$(".selec_nav_mmd1404").click(function(){
	var current_id_mmd1404 = $(this).attr("id");
	var number_id_mmd1404 = parseInt(current_id_mmd1404.substring(5,6));

	clearTimeout(timer_auto_mmd1404);
	timer_auto_mmd1404 = setTimeout(function(){
			anim_auto_mmd1404();
		},13000);

	$(".current_selec_nav_mmd1404").removeClass("current_selec_nav_mmd1404");
	$(".current_title_mmd1404").removeClass("current_title_mmd1404");
	$(this).addClass("current_selec_nav_mmd1404");
	$("#titre"+number_id_mmd1404+"_nav_mmd1404").addClass("current_title_mmd1404");

	if(number_id_mmd1404!=position_generale_mmd1404){
		for (var i = 0; i < timeouts_mmd1404.length; i++) {
		    clearTimeout(timeouts_mmd1404[i]);
		}
		timeouts_mmd1404 = [];
		leave_slide_mmd1404(position_generale_mmd1404);
		setTimeout(function(){
			anim_slide_mmd1404(number_id_mmd1404);
			position_generale_mmd1404=number_id_mmd1404;
		},800);
	}
});

$("#nav_left_mmd1404").click(function(){
	clearTimeout(timer_auto_mmd1404);

	if(position_generale_mmd1404==1){
		$("#selec5_nav_mmd1404").trigger("click");
	}else{
		$("#selec"+(position_generale_mmd1404-1)+"_nav_mmd1404").trigger("click");
	}
});

$("#nav_right_mmd1404").click(function(){
	clearTimeout(timer_auto_mmd1404);

	if(position_generale_mmd1404==5){
		$("#selec1_nav_mmd1404").trigger("click");
	}else{
		$("#selec"+(position_generale_mmd1404+1)+"_nav_mmd1404").trigger("click");
	}
});



//BEGIN

$("#cta_begin_mmd1404").click(function(event, auto){
	$("#zone_intro_mmd1404").fadeOut(300);
	clearTimeout(first_timeout);
	setTimeout(function(){
		anim_slide_mmd1404(1);
		$("#zone_nav_mmd1404").fadeIn();
	},200);

	dataLayer.push({
		"event": "uaevent",
		"eventCategory": "Showroom::AnimationTopProduit::Index",
		"eventAction": "Start",
		"eventLabel": "Démarrez !"
	});
});

$("#cta_end1_mmd1404").click(function(){
	dataLayer.push({
		"event": "uaevent",
		"eventCategory": "Showroom::AnimationTopProduit::Step5",
		"eventAction": "Forms::TestDrive::C4GrandPicasso",
		"eventLabel": "Essayez-la !"
	});
});

$("#cta_end2_mmd1404").click(function(){
	dataLayer.push({
		"event": "uaevent",
		"eventCategory": "Showroom::AnimationTopProduit::Step5",
		"eventAction": "Configurator::TestDrive::C4GrandPicasso",
		"eventLabel": "Configurez-la !"
	});
});

function anim_auto_mmd1404(){
	if(position_generale_mmd1404==5){
		$("#selec1_nav_mmd1404").trigger("click");
	}else{
		$("#selec"+(position_generale_mmd1404+1)+"_nav_mmd1404").trigger("click");
	}
}

//sieges

$("#plus_small_mmd1404").mouseover(function(){
	$("#plus_big_mmd1404").fadeIn(300);
});

$("#plus_big_mmd1404").mouseleave(function(){
	$("#plus_big_mmd1404").fadeOut(300);
});


//slide tablette

var position_drag_mmd1404 = null;

$('#draggable_mmd1404').draggable({
    axis: 'y',
    containment: "parent",
    scroll: false,
    drag: function(){
        position_drag_mmd1404 = $("#draggable_mmd1404").position().top;
        $("#image_drag_mmd1404").css({height : (position_drag_mmd1404+27)+"px"});
    }
});

function isIE () {
  var myNav = navigator.userAgent.toLowerCase();
  return (myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false;
}

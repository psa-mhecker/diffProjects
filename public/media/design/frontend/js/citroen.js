/*
 * Media helper for fancyBox
 * version: 1.0.5 (Tue, 23 Oct 2012)
 * @requires fancyBox v2.0 or later
 *
 * Usage:
 *     $(".fancybox").fancybox({
 *         helpers : {
 *             media: true
 *         }
 *     });
 *
 * Set custom URL parameters:
 *     $(".fancybox").fancybox({
 *         helpers : {
 *             media: {
 *                 youtube : {
 *                     params : {
 *                         autoplay : 0
 *                     }
 *                 }
 *             }
 *         }
 *     });
 *
 * Or:
 *     $(".fancybox").fancybox({,
 *	       helpers : {
 *             media: true
 *         },
 *         youtube : {
 *             autoplay: 0
 *         }
 *     });
 *
 *  Supports:
 *
 *      Youtube
 *          http://www.youtube.com/watch?v=opj24KnzrWo
 *          http://www.youtube.com/embed/opj24KnzrWo
 *          http://youtu.be/opj24KnzrWo
 *      Vimeo
 *          http://vimeo.com/40648169
 *          http://vimeo.com/channels/staffpicks/38843628
 *          http://vimeo.com/groups/surrealism/videos/36516384
 *          http://player.vimeo.com/video/45074303
 *      Metacafe
 *          http://www.metacafe.com/watch/7635964/dr_seuss_the_lorax_movie_trailer/
 *          http://www.metacafe.com/watch/7635964/
 *      Dailymotion
 *          http://www.dailymotion.com/video/xoytqh_dr-seuss-the-lorax-premiere_people
 *      Twitvid
 *          http://twitvid.com/QY7MD
 *      Twitpic
 *          http://twitpic.com/7p93st
 *      Instagram
 *          http://instagr.am/p/IejkuUGxQn/
 *          http://instagram.com/p/IejkuUGxQn/
 *      Google maps
 *          http://maps.google.com/maps?q=Eiffel+Tower,+Avenue+Gustave+Eiffel,+Paris,+France&t=h&z=17
 *          http://maps.google.com/?ll=48.857995,2.294297&spn=0.007666,0.021136&t=m&z=16
 *          http://maps.google.com/?ll=48.859463,2.292626&spn=0.000965,0.002642&t=m&z=19&layer=c&cbll=48.859524,2.292532&panoid=YJ0lq28OOy3VT2IqIuVY0g&cbp=12,151.58,,0,-15.56
 */
(function ($) {
	"use strict";

	//Shortcut for fancyBox object
	var F = $.fancybox,
		format = function( url, rez, params ) {
			params = params || '';

			if ( $.type( params ) === "object" ) {
				params = $.param(params, true);
			}

			$.each(rez, function(key, value) {
				url = url.replace( '$' + key, value || '' );
			});

			if (params.length) {
				url += ( url.indexOf('?') > 0 ? '&' : '?' ) + params;
			}

			return url;
		};

	//Add helper object
	F.helpers.media = {
		defaults : {
			youtube : {
				matcher : /(youtube\.com|youtu\.be)\/(watch\?v=|v\/|u\/|embed\/?)?(videoseries\?list=(.*)|[\w-]{11}|\?listType=(.*)&list=(.*)).*/i,
				params  : {
					autoplay    : 1,
					autohide    : 1,
					fs          : 1,
					rel         : 0,
					hd          : 1,
					wmode       : 'opaque',
					enablejsapi : 1
				},
				type : 'iframe',
				url  : '//www.youtube.com/embed/$3'
			},
			vimeo : {
				matcher : /(?:vimeo(?:pro)?.com)\/(?:[^\d]+)?(\d+)(?:.*)/,
				params  : {
					autoplay      : 1,
					hd            : 1,
					show_title    : 1,
					show_byline   : 1,
					show_portrait : 0,
					fullscreen    : 1
				},
				type : 'iframe',
				url  : '//player.vimeo.com/video/$1'
			},
			metacafe : {
				matcher : /metacafe.com\/(?:watch|fplayer)\/([\w\-]{1,10})/,
				params  : {
					autoPlay : 'yes'
				},
				type : 'swf',
				url  : function( rez, params, obj ) {
					obj.swf.flashVars = 'playerVars=' + $.param( params, true );

					return '//www.metacafe.com/fplayer/' + rez[1] + '/.swf';
				}
			},
			dailymotion : {
				matcher : /dailymotion.com\/video\/(.*)\/?(.*)/,
				params  : {
					additionalInfos : 0,
					autoStart : 1
				},
				type : 'swf',
				url  : '//www.dailymotion.com/swf/video/$1'
			},
			twitvid : {
				matcher : /twitvid\.com\/([a-zA-Z0-9_\-\?\=]+)/i,
				params  : {
					autoplay : 0
				},
				type : 'iframe',
				url  : '//www.twitvid.com/embed.php?guid=$1'
			},
			twitpic : {
				matcher : /twitpic\.com\/(?!(?:place|photos|events)\/)([a-zA-Z0-9\?\=\-]+)/i,
				type : 'image',
				url  : '//twitpic.com/show/full/$1/'
			},
			instagram : {
				matcher : /(instagr\.am|instagram\.com)\/p\/([a-zA-Z0-9_\-]+)\/?/i,
				type : 'image',
				url  : '//$1/p/$2/media/'
			},
			google_maps : {
				matcher : /maps\.google\.([a-z]{2,3}(\.[a-z]{2})?)\/(\?ll=|maps\?)(.*)/i,
				type : 'iframe',
				url  : function( rez ) {
					return '//maps.google.' + rez[1] + '/' + rez[3] + '' + rez[4] + '&output=' + (rez[4].indexOf('layer=c') > 0 ? 'svembed' : 'embed');
				}
			}
		},

		beforeLoad : function(opts, obj) {
			var url   = obj.href || '',
				type  = false,
				what,
				item,
				rez,
				params;

			for (what in opts) {
				item = opts[ what ];
				rez  = url.match( item.matcher );

				if (rez) {
					type   = item.type;
					params = $.extend(true, {}, item.params, obj[ what ] || ($.isPlainObject(opts[ what ]) ? opts[ what ].params : null));

					url = $.type( item.url ) === "function" ? item.url.call( this, rez, params, obj ) : format( item.url, rez, params );

					break;
				}
			}

			if (type) {
				obj.href = url;
				obj.type = type;

				obj.autoHeight = false;
			}
		}
	};

}(jQuery));;// Mettre la date � jour : 
var lastRelease = '2015 11 03';
/*
 * JQuery CSS Rotate property using CSS3 Transformations
 * Copyright (c) 2011 Jakub Jankiewicz <http://jcubic.pl>
 * licensed under the LGPL Version 3 license.
 * http://www.gnu.org/licenses/lgpl.html
 */
(function ($) {

    // INTERNET EXPLORER 10 & 11 DETECTION

    if (navigator.appVersion.indexOf("MSIE 10") !== -1) {
        $('html').addClass('ie ie10');
    }

    // IF THE BROWSER IS INTERNET EXPLORER 11
    var UAString = navigator.userAgent;
    if (UAString.indexOf("Trident") !== -1 && UAString.indexOf("rv:11") !== -1) {
        $('html').addClass('ie ie11');
    }


    function getTransformProperty(element) {
        var properties = ['transform', 'WebkitTransform',
            'MozTransform', 'msTransform',
            'OTransform'
        ];
        var p;
        while (p = properties.shift()) {
            if (element.style[p] !== undefined) {
                return p;
            }
        }
        return false;
    }

    $.cssHooks['rotate'] = {
        get: function (elem, computed, extra) {
            var property = getTransformProperty(elem);
            if (property) {
                return elem.style[property].replace(/.*rotate\((.*)deg\).*/, '$1');
            } else {
                return '';
            }
        },
        set: function (elem, value) {
            var property = getTransformProperty(elem);
            if (property) {
                value = parseInt(value);
                $(elem).data('rotatation', value);
                if (value == 0) {
                    elem.style[property] = '';
                } else {
                    elem.style[property] = 'rotate(' + value % 360 + 'deg)';
                }
            } else {
                return '';
            }
        }
    };
    $.fx.step['rotate'] = function (fx) {
        $.cssHooks['rotate'].set(fx.elem, fx.now);
    };

})(jQuery);


/* ############################## Citroen - js/main.js ############################## */

var ISTOUCH = 'ontouchstart' in window,
        STARTEVENT = (ISTOUCH) ? 'touchstart' : 'mousedown',
        MOVEEVENT = (ISTOUCH) ? 'touchmove' : 'mousemove',
        ENDEVENT = (ISTOUCH) ? 'touchend' : 'mouseup';

var testMsGesture = (window.navigator.pointerEnabled && (window.MSGesture !== undefined));

$('html').removeClass('no-js').addClass((ISTOUCH) ? 'js touch' : 'js');


$(document).ready(function () {

    function ctaPos() {
        $('.sliceSlideShowDesktop .banner.slider .texts').each(function () {
            //console.log($(this).closest('.col').attr('id'));
            //console.log($(this).attr('class').indexOf("centre"));
            if($(this).attr('class').indexOf("centre") > 0 && $(this).attr('class').indexOf("middle") > 0){
                var leftvalue = 0;
                leftvalue = $(this).closest('.col').outerWidth() / 2 + 15 - $(this).width() + $(this).width() / 2;
                var topvalue = 0;
                topvalue = $(this).closest('.col').outerHeight() / 2 - 15 - $(this).height() + $(this).height() / 2;
                $(this).attr('style', 'top:' + topvalue + 'px!important;left:' + leftvalue + 'px!important');
            }else if($(this).attr('class').indexOf("centre") > 0) {
                var leftvalue = 0;
                leftvalue = $(this).closest('.col').outerWidth() / 2 + 15 - $(this).width() + $(this).width() / 2;
                var topvalue = 0;
                topvalue = $(this).closest('.col').outerHeight() / 2 - 15 - $(this).height() + $(this).height() / 2;
                $(this).attr('style', 'left:' + leftvalue + 'px!important'+ ';top:' + topvalue + 'px!important');
            }else if($(this).attr('class').indexOf("middle") > 0){
                var topvalue = 0;
                topvalue = $(this).closest('.col').outerHeight() / 2 - 15 - $(this).height() + $(this).height() / 2;
                $(this).attr('style', 'left:'+$(this).css('left') + '!important' + ';right:'+$(this).css('right') + '!important' + ';top:' + topvalue + 'px!important');
            }
        });
    }
    $(window).resize(function() {
        ctaPos();
    });

    $(window).resize(function() {
        $(".superposition-right, .superposition-left").each(function() {
            var backgroundHeight = $(this).height();
            var SuperpositionBoxHeight = $(this).find('.superposition-box').height();
            var globalHeight = SuperpositionBoxHeight + 120; // 120 is margin-top of .cls2colonnemixte.superposition-right div.span3
            $(this).css("margin-bottom",  (globalHeight-backgroundHeight) + "px");
            if (globalHeight < backgroundHeight) {
                $(this).css("margin-bottom", "0px");
            }
        });
    });
    $(window).trigger('resize');

    if($('div.cookieBarReviewDesktop').length >0){
			$('div.arrowBottom').attr('style','bottom: 90px;');
	}
    //CPW-4168 - FixTmp pb du calage de la hauteur car les image en lazyLoading ne sont pas encore chargée sur l'événement Load.
    $(window).scroll(function() {
        $(".superposition-right").each(function() {
            var backgroundHeight = $(this).height();
            var SuperpositionBoxHeight = $(this).find('.superposition-box').height();
            var globalHeight = SuperpositionBoxHeight + 120; // 120 is margin-top of .cls2colonnemixte.superposition-right div.span3
            $(this).css("margin-bottom",  (globalHeight-backgroundHeight) + "px");
            if (globalHeight < backgroundHeight) {
                $(this).css("margin-bottom", "0px");
            }
        });
    });

    $.fn.extend({
        setActivateGTM : function(activeGTM)
        {
            this.activeGTM = activeGTM;
        }
    });

    // BULLE SCROLL SHOWROOM NEW DESIGN
    if ($('.showroom').length > 0) {
    }

    // INTERSTITIEL HANDLER
    $('.intersticiel-popin').each(function () {
        var oThis = this;
        var timer = $(this).find('#InterImageDuration').val();
        if (timer !== undefined) {
            var closeTimer = setTimeout(function () {
                $(oThis).hide().delay(50).queue(function () {
                    $(oThis).remove();
                });


                $('.slider').each(function () {
                    if ($(this).data('auto') && ($(this).data('sliderObject') != undefined)) {
                        $(this).data('sliderObject').startAuto()
                    }
                });
            }, timer)
        }
        $(oThis).find('.closer, .popClose, .intersticielpopCloseEvent').on('click', function (e) {
            $(oThis).hide().delay(50).queue(function () {
                $(oThis).remove();
            });
            $('.slider').each(function() {

                if($(this).data('auto') && ( $(this).data('sliderObject')!=undefined)){
                    $(this).data('sliderObject').startAuto()
                }
            });
        });
    });

    // LAYER NAV VEHICULES CPW-3301
    $('.layer .box .vehicle').each(function () {
        var pack = this,
            collection = $(pack).find('.zoner'),
                active = null;
        $(collection).each(function () {
            var zone = this,
                    bundle = $(zone).find('.bundle'),
                    mainLink = $(bundle).find('> a'),
                    menu = $(bundle).find('.menu');

            if (ISTOUCH == true) {
                var urlLink = $(mainLink).attr('href');
                $(mainLink).removeAttr('href');
                $(mainLink).on('click', function (e) {
                    e.preventDefault();
                    if (active == zone) {
                        document.location.replace(urlLink);
                    } else {
                        $(active).removeClass('opened');
                        $(zone).addClass('opened');
                        active = zone;
                    }
                })
            } else {
                $(zone).on('mouseenter', function () {
                    $(zone).addClass('opened');

                });
                $(zone).on('mouseleave', function () {
                    $(zone).removeClass('opened');
                });

            }
        });
    });

    // AFFICHAGE AVEC OU SANS 'DS' CPW-4315
    var vehicleDSElement = $('div.cars.vehicles');

    if(!vehicleDSElement.find('div.ds.vehicle .row div').length){

        vehicleDSElement.find('div.ds.vehicle').each(function(){
            this.style.setProperty('display','none');
        });
        vehicleDSElement.find('div.vehicle').each(function(){
            this.style.setProperty('width','100%');
        });
        vehicleDSElement.find('div.vehicle').find('.new').each(function(){
            this.style.setProperty('clear','none','important');
        });
        vehicleDSElement.find('div.vehicle > div.new > div.bg.nocategory').each(function(){
            this.style.setProperty('width','20%');
        });
    }


    /**** LISTE FORFAIT SCROLLTO ****/
    // $('.clsmenuforfait a').click(function (e) {
    //  e.preventDefault();
    //  var offsetforfait = $('.clslisteforfait').offset().top-90;
    //  $('html, body').animate({scrollTop: offsetforfait}, 300);
    // });

    $(".footfold a").click(function () {
        $("#footerMap").slideToggle("slow", function () {
            // Animation complete.
        });
    });

    /* TEST MARGES */
    $(".tmtl").each(function () {
        $(this).find("p").last().css("margin-bottom", "20px");
    });

    $('section.cls2colonnemixte .col, section .col .zonetexte2colmixte').each(function () {
        if (!$(this).children().last().hasClass('thumbs')) {
            $(this).children().last().css("margin-bottom", 0);
        }
    });

    var getPrevSection = function ($el) {
        var arr = $el.prevAll("section");
        var prev = arr[0];
        return prev;
    }
    // $("section").first().css("padding-top", "0").find(".sep").remove();

    //adapte section
    $('section').each(function () {
        $section = $(this);

        if ($section.hasClass("col")) {
            $elmt = $section.children().last().css("margin-bottom", 0);
        } else {
            //Si le dernier �l�ment de la section est un div avec l'id trancheParent
            if ($section.children().last().hasClass("parent")) {
                $elmt = $section.children().last().prev();
            } else {
                $elmt = $section.children().last();
            }
            $elmt.css("margin-bottom", 0);
        }

        //Si 2 colonne ex: clscontenutextecta => http://cppv2-dev-frontend.interakting.com/pid52-services.html
        if ($elmt.hasClass("col")) {
            $elmt.prev().css("margin-bottom", 0);
        }

        $title = $section.find(".subtitle");
        $maintitle = $section.find(".title");

        if ($section.prev().hasClass("clsgrandvisuel") && (!$title.length)) {
            $section.css("padding-top", "30px");
        }

        //Si pas de titre
        if (!$title.length) {
            $section.first().css("padding-top", "50px");
            $section.find(".sep").remove();


            if (!$title.length) {
                $section.find(".sep").remove();

                var $prevSection = getPrevSection($section);

                if ($section.hasClass("c-skin") && $($prevSection).hasClass("ds")) {
                    $section.css("padding-top", "50px");
                } else if ($section.hasClass("ds") && $($prevSection).hasClass("c-skin")) {
                    $section.css("padding-top", "50px");
                } else {
                    if (!$section.hasClass("clslanguette")) {
                        $section.css("padding-top", "0");
                    }
                }
            }

        }

        //Calage barre d'outils, slideshow et toggle
        if ($section.prev().hasClass("banner") && $section.hasClass("clsoutil") && !$section.find('.tools').hasClass("vertical")) {
            $section.find(".sep").remove();
            if (!$section.prev().hasClass("clsselecteurteinte")) {
                $section.css("padding-top", "30px");
            } else {
                var toolsSection = $section;
                var toolsSectionPrev = $section.prev();
                var resizeTools = function () {
                    //console.log($section);
                    var bannerW = toolsSectionPrev.find(' > figure img').width();
                    var marginH = Math.round((bannerW - $('.body').width()) / 2);
                    //console.log("body width : "+$('.body').width()+" / bannerW : "+bannerW+" / marginH : "+marginH);
                    toolsSection.css({
                        width: bannerW,
                        marginLeft: -marginH,
                        paddingLeft: 0,
                        paddingRight: 0
                    });
                    toolsSection.find('.tools').css({
                        position: 'relative',
                        height: toolsSection.find('.tools').outerHeight()
                    });
                    toolsSection.find('.tools .cont').css({
                        position: 'absolute',
                        'left': '50%',
                        marginLeft: -toolsSection.find('.tools ul').outerWidth() / 2,
                        width: toolsSection.find('.tools ul').outerWidth()
                    });
                }
                setTimeout(function () {
                    resizeTools();
                }, 2000);

                $(window).on('resize', function () {
                    resizeTools();
                })
            }
        }

        //Calage barre d'outils, vertical OLIVIER
        if ($section.hasClass("clsoutil") && $section.find('.tools').hasClass("vertical")) {

            var $toolsvertical = $(this).find('.tools.vertical'),
                    $toolsverticalpicto = $toolsvertical.find('.picto'),
                    $item = $toolsvertical.find('li'),
                    animationtime = parseInt($toolsvertical.attr('data-animation')) * 1000;

            $item.each(function () {
                $(this).addClass('hover');
            });

            setTimeout(function () {
                $item.each(function () {
                    $(this).removeClass('hover');
                    $(this).hover(function () {
                        $(this).addClass('hover')
                    }, function () {

                        $(this).removeClass('hover')
                    })
                });
            }, animationtime);

            var animPictoTimer = 125,
                animPictosLaunchTimer = 20000,
                    animSTO = 0;

            var animPicto = function (el) {
                $(el).animate({
                    'opacity': 0.2
                }, animPictoTimer, function () {
                    $(el).animate({
                        'opacity': 1
                    });
                });
            };

            var animPictosLaunch = function () {
                var total = $toolsverticalpicto.length;
                $toolsverticalpicto.each(function (index, el) {
                    var thisIndex = index;
                    setTimeout(function () {
                        animPicto(el);
                        /*
                         if(thisIndex===(total-1)){
                         clearTimeout(animSTO);
                         }
                         */

                    }, index * (animPictoTimer * 2));
                });
            };

            $toolsvertical.on('mouseenter', function (e) {
                clearInterval(animSTO);
            });

            animSTO = setInterval(function () {
                animPictosLaunch();
            }, animPictosLaunchTimer);

        }

        // Calage title strike
        if ($section.prev().hasClass("strike")) {
            $section.find(".sep").remove();
            if (!$section.prev().not(".lite")) {
                $section.css("padding-top", "28px");
            }
            $section.prev().css("margin-bottom", "36px");

        }

        //Calage sticky
        if ($section.prev().hasClass("stickyplaceholder")) {
            $section.find(".sep").remove();
            if (!$section.prev().not(".lite")) {
                $section.css("padding-top", "28px");
            }
            if (!$section.hasClass("ds")) {
                $section.prev().css("margin-bottom", "0px");
            }
        }

        //Calage onglet
        if ($section.parent().hasClass("tab")) {
            $section.css("padding", "50px 20px");
            $section.find(".sep").remove();
            if (!$section.hasClass("ds")) {
                $section.prev().css("margin-bottom", "50px");
            }
        }

        $(".clsgamme").css("padding-top", "50px");

    });

    //$("input[placeholder]").each(placeholder.create);

    lazy.set($('img.lazy').not('.slider img.lazy, .clshistoire img.lazy'));

    $('.timeline').each(timeline.build);

    /* #2024 We have remove .sticker (the to top button) from this function */
    $('.stickyplaceholder , .listickholder , .stripholder, .storynav').each(sticky.build);
    /*
     $('.tabbed').each(tabs.build);
     */
    $('.tabbed').each(function () {
        new Tabbs($(this));
    });

    $('.colors').jColors();

    $('.scroll').each(function () {
        if (!$(this).parents('.layertip:first').length) {
            $(this).jScrollPane({
                autoReinitialise: true,
                autoReinitialiseDelay: 10,
                verticalGutter: 20,
                mouseWheelSpeed: 50
            }).bind('mousewheel', function (e) {
                e.preventDefault();
            });
        }
    });

    $('.zoner').each(zoner.build);

    $('video:not(.vjs-tech)').not('.slider video').each(function (ind, el) {
        $(el).addClass('video-js vjs-default-skin');
        if ($(el).parents().hasClass('intersticiel-content')) {
            var paramAuto = true;
        } else {
            el.removeAttribute('autoplay');
            var paramAuto = false;
        }
        videojs(el, {
            height: $(el).height(),
            width: $(el).width(),
            autoplay: paramAuto,
            controls: true
        });
        gtmCit.initVideo($(el));
    });

    /**** LAYER POSITION IF COOKIES BLOCK ACTIVE ****/
    $('header .folder').on('click', function () {
        var height = $('body > .container > header').height();

        if ($('#cookies').is(':visible')) {
            height += $('#cookies').height();
        }

        $('.layer, .overlay').css('top', height);
    });

    $('#cookies .actions .blue a').on('click', function () {

        $('.layer .box').each(function () {
            if ($(this).hasClass('open')) {

                $(this).parents('.layer').css({
                    'top': parseInt($(this).parents('.layer').position().top) - parseInt($('#cookies').height())
                });
                $('.overlay').css({
                    'top': parseInt($(this).position().top) - parseInt($('#cookies').height())
                });

            }
        });
    });

    $('#scrolltop').click(scrollToTop);


    /* 1926 correction du flash li� � la fermeture trop tardive des
     filtre du car selector */
    $(window).on('load', function () {
        var displayCur = $('#selector').css('display');
        $('#selector').removeAttr('style').css('display', displayCur);
    });

    $('.slider').each(function (i) {
        var $this = $(this);

        /* Vars */
        var $row = $this.find('.row').eq(0),
                row = $row.get(0),
                $slides = $row.find('> *'),
                $lazy = $row.find('img.lazy'),
                css = $row.attr('class'),
                count = (typeof css == "undefined") ? 1 : parseInt(css.substr(css.indexOf('of') + 2, 1)), // Si la cible n'existe pas on donne la valeur 1, et Si vous voulez pas donner zero a vous le choix !
                step = ($this.hasClass('one')) ? 1 : count,
                loop = $this.hasClass('loop'),
                pager = ($this.hasClass('nopager')) ? 0 : 1,
                margin = null,
                auto = (null != this.getAttribute('data-auto') && (!$('.intersticiel-popin:visible').length)),
                pause = this.getAttribute('data-auto') || 0,
                speed = this.getAttribute('data-speed') || 750,
                conf = this.getAttribute('data-objs'),
                bindImgs = 0;

        if($this.parents().hasClass('showroom')){
            $this.pagerOff = $this.attr('data-pageroff');
            $this.pagerOn = $this.attr('data-pageron');

        }

        if (1 == count || $row.hasClass('collapse')) {
            // IF ONE BY ONE
            margin = 0;
        } else {
            if ($slides.length > count) {
                // IF ENOUGH SLIDES TO BUILD THE SLIDER
                margin = 40;
            } else {
                // IF NOT ENOUGH SLIDES TO BUILD THE SLIDER
                margin = 10;
            }

        }

        $('video:not(.vjs-tech)', $row).each(function (ind, el) {
            $(el).addClass('video-js vjs-default-skin vjs-only-big-play');
            if ($(el).parents().hasClass('intersticiel-content')) {
                var paramAuto = true;
            } else {
                el.removeAttribute('autoplay');
                var paramAuto = false;
            }
            videojs(el, {
                height: $row.parents('.slider:first').height(),
                width: $row.parents('.slider:first').width(),
                autoplay: paramAuto,
                controls: true
            });
            gtmCit.initVideo($(el));
        });

        if ($row.hasClass('withsep'))
            margin = 80;
        /* Build only if has enough slides */
        if ($slides.length <= count) {
            /* Set lazy laoding for first page images */
            lazy.set($lazy.slice(0, count), function () {
                $row.trigger('redrawSlider');
            });

            $this.addClass('not-built');
            return;
        } else {
            $this.addClass('built');
        }
        /* Set lazy laoding for first page images */
        lazy.set($lazy.slice(0, count));
        /* Lazy load clones */
        var $clones = $row.find('.bx-clone img.lazy');
        $lazy.on('load', function () {
            bindImgs++;
            if (bindImgs === $lazy.length) {
                //console.log('collection loaded')
                addjustArrow($slides);
                /*
                 setTimeout(function() {
                 $row.trigger('redrawSlider');
                 }, 8000);
                 */
            }
        });

        /* bxSlider */
        if ($(this).find('.col').length > 1) {
            row._slider = $row.bxSlider({
                mode: 'horizontal',
                auto: auto,
                pause: pause,
                useCSS: false, // Option useCSS pour �viter les typos flou sur ipad ios7
                autoHover: true,
                slideWidth: 5000,
                speed: speed,
                pager: pager,
                infiniteLoop: loop,
                hideControlOnEnd: !loop,
                minSlides: count,
                maxSlides: count,
                moveSlides: step,
                oneToOneTouch: /*(count === 1) ?*/ true /* : false*/ ,
                // adaptiveHeight:true,
                slideMargin: margin,
                onSliderLoad: function() {
                    var slider = this;

                    /*
                     setTimeout(function() {
                     addjustArrow($slides);
                     }, 8000);
                     */
                    setTimeout(function () {
                        sync.set();
                    }, 2000);
                    /*
                     lazy.load($clones, function() {
                     addjustArrow($slides);
                     });
                     */
                    if($this.pagerOff != undefined){
                        $this.find('.bx-pager-link').each(function(){
                            ($(this).hasClass('active'))? $(this).attr('style',$this.pagerOn) : $(this).attr('style',$this.pagerOff);
                        });
                    }

                    this.checkVideo($($slides[0]));

                    // For each clones, add the suffix "_clone" to the video IDs
                    $row.find('.bx-clone .video-js, .bx-clone .vjs-tech').each(function(ind, el) {
                        $(el).attr('id', $(el).attr('id') + '_clone');
                    });

                    //CPW-4296
                    $row.find('.bx-clone').each(function(ind, el) {
                        $(el).attr('id', $(el).attr('id') + '_transition');
                    });
                },
                onSlideBefore: function(dom, oldi, newi) {
                    /* Lazy load before transition */
                    // lazy.load($lazy.slice(newi*count,newi*count+count));
                    lazy.load($(dom).parents('.slider').find('img.lazy'));

                    // if(navigator.userAgent.toLowerCase().indexOf("ipad") !== -1){
                    //  if(dom.find('video').length > 0) {
                    //      dom.find('.videoWrapper').html(dom.find('.videoWrapper').html());
                    //  }
                    // }

                    this.checkVideo(dom);
                },
                checkVideo: function(dom) {
                    // PAUSE ALL VIDEOS
                    $row.find('video').each(function(ind, el) {
                        if (el.id && el.currentTime) {
                            videojs(el.id).pause();
                            videojs(el.id).currentTime(0);
                        }

                        $(el).off('ended');
                    });

                    // START THE CURRENT SLIDE VIDEO(S)
                    dom.find('video').each(function(ind, el) {
                        // if (!row._stopped){
                        $(el).bind('ended', function() {
                            row._slider.startAuto();
                            row._slider.goToNextSlide();
                        });
                        // }

                        if (el.id) {
                            if($('.showroom').length==0){
                                videojs(el.id).play();
                            }
                        }
                    });
                },
                onSlideAfter: function(dom, oldi, newi) {
                    if (dom.find('video').length) {
                        row._slider.stopAuto();
                    }
                }
            });
        }
        $(this).bind(STARTEVENT, function () {
            row._stopped = true;
        });

        function adjustHeight() {
            $('[data-sync]').each(sync.build);
        }

        function addjustArrow(slide) {
            var maxHeight = 0;


            slide.each(function (index, el) {
                var imgHeight = $(el).find('figure img').height();
                if (maxHeight < imgHeight) {
                    maxHeight = imgHeight
                }
            });


            var $imgSlide = slide.eq(0).find('figure img');

            // console.log(slide.eq(0).find('img').parent('figure').prev());

            // The offset top of the slides containers            
            var imgOffsetTop = ($imgSlide.length > 0) ? $imgSlide.offset().top : 0;
            var slideOffsetTop = slide.eq(0).offset().top;
            var rowOffsetTop = (slide.eq(0).parent('.row').length > 0) ? slide.eq(0).parent('.row').offset().top : 0;
            var viewportOffsetTop = (slide.eq(0).parents('.bx-viewport').length > 0) ? slide.eq(0).parents('.bx-viewport').offset().top : 0;
            var wrapperOffsetTop = (slide.eq(0).parents('.bx-wrapper').length > 0) ? slide.eq(0).parents('.bx-wrapper').offset().top : 0;
            var sliderOffsetTop = (slide.eq(0).parents('.slider').length > 0) ? slide.eq(0).parents('.slider').offset().top : 0;
            var sliderHeight = slide.eq(0).parent().height();
            var prevElemHeight = (slide.eq(0).find('img').parent('figure').prev().length > 0) ? slide.eq(0).find('img').parent('figure').prev().outerHeight() : 0;
            var prevElemMTop = (slide.eq(0).find('img').parent('figure').prev().length > 0) ? slide.eq(0).find('img').parent('figure').prev().css('marginTop') : 0;
            var prevElemMBottom = (slide.eq(0).find('img').parent('figure').prev().length > 0) ? slide.eq(0).find('img').parent('figure').prev().css('marginBottom') : 0;

            //console.log("total  : " + Math.round(imgOffsetTop - slideOffsetTop));

            //console.log('MOMENT 1 /// prevElemHeight : ' + prevElemHeight + ' / prevElemMTop : ' + prevElemMTop + ' / prevElemMBottom : ' + prevElemMBottom + ' / ');

            if (conf === null || conf.indexOf('arrospos:center') === -1) {
                $this.find('.bx-prev, .bx-next').each(function () {
                    var $obj = $(this),
                            sliderBtnOffset = 0;
                    //console.log('onenenen')
                    // console.log(' TOTAL : ' + Math.round($imgSlide.height() / 2) - Math.round($obj.height() / 2) + parseInt(slide.eq(0).parent('.row').css('paddingTop') + parseInt(slide.eq(0).parent('.slider').css('paddingTop')) + parseInt(prevElemHeight) + parseInt(prevElemMTop) + parseInt(prevElemMBottom / 2)));
                    if ($imgSlide.length > 0) {

                        //sliderBtnOffset = (imgOffsetTop - sliderOffsetTop) + (maxHeight / 2 ) - ($this.height() / 2);
                        //sliderBtnOffset = -(maxHeight / 2 );
                        sliderBtnOffset = (Math.round($imgSlide.height() / 2) - Math.round($obj.height() / 2)) + (Math.round(imgOffsetTop - slideOffsetTop)) + parseInt(slide.eq(0).parent('.row').css('paddingTop'));
                    } else {
                        //sliderBtnOffset = -(Math.round(sliderHeight/2 - $this.height() / 2));
                        sliderBtnOffset = Math.round(sliderHeight / 2) - Math.round($obj.height() / 2) + parseInt(slide.eq(0).parent('.row').css('paddingTop') + parseInt(slide.eq(0).parent('.slider').css('paddingTop')) + parseInt(prevElemHeight) + parseInt(prevElemMTop) + parseInt(prevElemMBottom / 2));
                    }

                    //sliderBtnOffset = (wrapperOffsetTop + viewportOffsetTop + rowOffsetTop + slideOffsetTop + imgOffsetTop) + Math.round((maxHeight - $this.height()) / 2);
                    //console.log(slideBtnOffset);
                    if ($this.hasClass('cars')) {
                        sliderBtnOffset = sliderBtnOffset + 3;
                    }

                    //console.log("imgOffsetTop : " + imgOffsetTop + "\n slideOffsetTop : " + slideOffsetTop + "\n rowOffsetTop : " + rowOffsetTop + " \n viewportOffsetTop : " + viewportOffsetTop + "\n wrapperOffsetTop : " + wrapperOffsetTop + "\n sliderOffsetTop : " + sliderOffsetTop + "\n sliderBtnOffset : " + sliderBtnOffset + "\n prevElemHeight : " + prevElemHeight + "\n  prevElemMTop : " + prevElemMTop + "\n prevElemMBottom : " + prevElemMBottom);


                    $obj.css({
                        'top': sliderBtnOffset + 'px',
                        'margin-top': 0
                    });
                });
            }
        }

        $this.find('.row').on('redrawSlider', function () {
            //addjustArrow($(this).parents('.slider'));
            sync.set();
        });

        if($(this).find('.col').length > 1){
            gtmCit.initSlider(row._slider);
        }

        $(this).data('sliderObject', row._slider);
    });

    $('.dragnchange,.range').each(dragnchange.build);

    popInit();
    simplePopInit();

    // $('.popinfos').each(function (ind, el) {
    //  $(el).fancybox({
    //      maxWidth:900,
    //      minWidth:900,
    //      scrolling:'no',
    //      fitToView:false,
    //      padding:40,
    //      margin:40,
    //      beforeLoad:function(){
    //          $('.sticker.top').css('display','none');
    //      },
    //      afterLoad:function(){

    //          var that = this,
    //              closeTpl = $('#closeTpl').html();
    //          this.outer.append(closeTpl);


    //          /* Events */
    //          this.outer.find('.popClose span').click(function(){
    //              $.fancybox.close();
    //          });

    //          setTimeout(function(){
    //              popInit(that);
    //          },250);

    //      },
    //      afterClose:function(){
    //          setTimeout(function(){
    //              $('.sticker.top').css('display','block');
    //          },350);

    //      }

    //  });
    // });

    // TEST ITK FAIL AJAX
    $('.popinfos').each(function (ind, el) {
        var params = {
            maxWidth: 900,
            minWidth: 900,
            scrolling: 'no',
            fitToView: false,
            padding: 40,
            margin: 40,
            beforeLoad: function () {
                $('.sticker.top').css('display', 'none');
            },
            afterLoad: function () {
                var
                        that = this,
                        closeTpl = $('#closeTpl').html();

                this.outer.append(closeTpl);

                /* Events */
                this.outer.find('.popClose span').click(function () {
                    $.fancybox.close();
                });

                setTimeout(function () {
                    if ($('img.lazy', that.outer).length) {
                        lazy.load($('img.lazy', that.outer));
                    }

                    if ($('.tooltip,.texttip', that.outer).length) {
                        $('.tooltip,.texttip', that.outer).each(tooltip.build);
                    }

                    popInit(that);
                }, 250);

            },
            afterClose: function () {
                setTimeout(function () {
                    $('.sticker.top').css('display', 'block');
                }, 350);
            }
        };

        if ($(el).attr('href').substr(0, 1) != "#") {
            $(el).on('click', function (event) {
                event.preventDefault();
                var $div = $('<div></div>');
                $div.load($(el).attr('href') + " .container-popin", function () {
                    $.fancybox($.extend(params, {
                        content: $div.html()
                    }));
                });
            });
        } else {
            $(el).fancybox(params);
        }
    });

    // Pop up connexion
    $('.connexion').click(function () {
        var html = $('#layerregister').html();
        promptPop(html);
    });

    $('.confirmAdd').click(function () {
        var html = $('#layerconfirmadd').html();
        promptPop(html);
    });

    $('.connect').click(function () {
        var html = $('#layerconnexion').html();
        promptPop(html);
    });

    $('.btnfavoris').click(function () {
        var html = $('#layerfavoris').html();
        promptPop(html);
    });

    $('.modifypass').click(function () {
        var html = $('#modifypass').html();
        promptPop(html);
    });


    $('.alert .closer').click(function () {
        $('.alert').hide();
        $('.formproject').addClass('withOutBorder');
    });

    $('.folder').each(folder.build);

    $('.overall').each(overall.build);

    $('.foldbyrow > .mosaiqueRow').each(foldbyrow.build);

    $('[data-close]').click(function () {
        var item = this.getAttribute('data-close');
        $(item).slideUp();
    });

    $('.tooltip, .texttip').each(tooltip.build);

    $('.shareable').each(shareable.build);

    // CPW-4272
    var hpSocialLoaded = 0;
    if ($('.clshomers').length > 0) {
        $(window).on('scroll', function () {
            if (hpSocialLoaded == 0) {
                if ($(window).scrollTop() >= ($('.clshomers').offset().top - $(window).height())) {
                    (function(d, s, id) {
                        var facebook_culture = $('#fb-locale').val();
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id)) return;
                        js = d.createElement(s); js.id = id;
                        js.src = "//connect.facebook.net/"+facebook_culture+"/all.js#xfbml=1";
                        fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));
                    social_wait();
                    hpSocialLoaded = 1;
                }
            }
        });
    }

    function social_ready() {
        // Facebook
        $('.fb-like-box-waiting').addClass('fb-like-box').removeClass('fb-like-box-waiting');
        FB.XFBML.parse();
        // Twitter
        $('.twitter-timeline-waiting').addClass('twitter-timeline').removeClass('twitter-timeline-waiting');
        twttr.widgets.load();
        // Youtube
        $('[data-feed]').each(feeder.build);
        // Instagram
        launchInstagram();

        $('.fb-like-box').bind('DOMSubtreeModified', function() {
            var $iframe = $(this).find('iframe');
            if ($iframe.length) {
                $(this).unbind('DOMSubtreeModified');
                $iframe.load(function() {
                    setTimeout(setlikeboxwidth, 500);
                });
                likeboxes.push($iframe);
            }
        });
        $(window).resize(setlikeboxwidth);
    }
    ;

    function social_wait() {
        if (typeof FB === 'undefined' || typeof twttr === 'undefined')
        {
            window.setTimeout(social_wait, 100);
        }
        else
        {
            social_ready();
        }
    }


    $('[data-sync]').each(sync.build);

    if ($("#lcdv6_prerempli").length && $("#lcdv6_prerempli").val() !== "") {
        var value = $("#lcdv6_prerempli").val(),
                element = $("#model");

        element.find('a.on').removeClass('on');
        element.find('[data-value="' + value + '"]').addClass('on');
        $("#sim_fin_select0").val(value);
    }

    $selectZones.cSelector();


    $('form[id*=fFormType]').each(function () {
        new CarSelector(this);
    });


    if ($('.funding').length) {
        $('.funding').each(dropdownGroup.built);
    }

    $('.listeVehicules form .content').each(function () {
        new CompareItem(this);
    })

    $('data-trigger').each(function () {
        var $this = $(this);
        $this.click(function () {
            $this.trigger($this.attr('data-trigger'));
        });
    });


    /* AJOUT CPW-4097 */
    //  Dynamisation Comparateur
    var setPointsFortsLight = function (root, index) {
        var root = root,
                arrPagersLabel = [];
        $(root).find('.slide').each(function () {
            arrPagersLabel.push($(this).data('label'));
        });

        $('<ul class="pager" id="cls-pfl-' + index + '" ></ul>').appendTo($(root).find('.slide-bx'));

        for (i = 0; i < arrPagersLabel.length; i++) {
            $('<li><a href="" data-slide-index="' + (i) + '"><span class="numb">' + (i + 1) + '</span><span class="label">' + arrPagersLabel[i] + '</span></a></li>').appendTo($('#cls-pfl-' + index));
        }

        var $sliders = $(root).find('.slide').not('.bx-clone');
        var $pagers = $(root).find('.pager a');
        var gtmArrow = function (currentIndex) {
            var $left = $(root).find('a.bx-prev');
            var $right = $(root).find('a.bx-next');
            var step_number = parseInt(currentIndex) + 1;
            var left_slide = $sliders.get((currentIndex - 1 + $sliders.length) % $sliders.length);
            var right_slide = $sliders.get((currentIndex + 1) % $sliders.length);
            $left.attr('data-gtm-js', '{"type":"clickableJS","0":"eventGTM|Showroom::' + page_vehicule_label + '::Strengths::' + step_number + '|Navigation::Arrow::left|' + $(left_slide).attr('data-label') + '||"}');
            $right.attr('data-gtm-js', '{"type":"clickableJS","0":"eventGTM|Showroom::' + page_vehicule_label + '::Strengths::' + step_number + '|Navigation::Arrow::right|' + $(right_slide).attr('data-label') + '||"}');
        };
        var gtmPager = function () {
            $pagers.each(function () {
                var index = $(this).attr('data-slide-index');
                var step_number = parseInt(index) + 1;
                var slide = $sliders.get(index);
                $(this).attr('data-gtm', 'eventGTM|Showroom::' + page_vehicule_label + '::Strengths::' + step_number + '|Navigation::Click::' + step_number + '|' + $(slide).attr('data-label') + '||');
            });
        };
        $(root).find('.slides').bxSlider({
            infiniteLoop: true,
            auto: true,
            slideSelector: 'div.slide',
            pagerCustom: '#cls-pfl-' + index,
            onSlideAfter: function ($slider, oldIndex, newIndex) {
                gtmArrow(newIndex);
            },
            onSliderLoad: function (currentIndex) {

            }
        });
        gtmArrow(0);
        gtmPager();
    }
	
    $('.clspointsfortslight').each(function (index, el) {
        new setPointsFortsLight(el, index);
    });

    gtmCit.initNewGTM();
});

var $selectZones = $('.services .languages, .selectZone .select');

//my project
var tplCompareItem = '<div class="closer"></div>';

var CompareItem = function (root) {
    this.init(root);
}
CompareItem.prototype = {
    init: function (root) {
        this.root = $(root);
        this.fisrtSelect = this.root.find('.fakehidden:first');
        this.closeButton = $(tplCompareItem);

        this.setHandlers();
    },
    setHandlers: function () {
        var oThis = this;
        this.fisrtSelect.on('change', function () {
            if ($(this).val() != 0) {
                oThis.addClose();
            } else {
                oThis.removeClose();
            }
        });

        this.root.on('click', '.closer', function () {
            oThis.resetSelect();
        });
    },
    addClose: function () {
        this.root.append(this.closeButton);
    },
    removeClose: function () {
        this.closeButton.remove();
    },
    resetSelect: function () {
        this.fisrtSelect.val(0).change();
        this.removeClose();
    }
}

var setTools = function () {

    $('.tools a').each(function () {
        $(this).click(function () {
            $('.tools a').each(function () {
                $(this).removeClass('on')
            });
            $(this).addClass('on');
        });
    });

    $('.tools').each(function () {
        var dftStyles = $(this).attr('data-style');
        //console.log(dftStyles)
        var hvrStyles = $(this).attr('data-style-hover');

        $(this).find('a').each(function () {
            $(this).attr('style', dftStyles);
            var picto = $(this).find('.picto');
            if (picto.length > 0) {
                var pictoSrc = picto.attr('src');
                var pictoHover = picto.attr('data-hover');
            }
            $(this).on('mouseenter', function () {
                $(this).attr('style', hvrStyles);
                picto.attr('src', pictoHover);
            }).on('mouseleave', function () {
                $(this).attr('style', dftStyles);
                picto.attr('src', pictoSrc);
            });

        });
    });
}
setTools();

//  Dynamisation CTA
var setCta = function () {

    $('.showroom .actions').each(function () {
        var dftStyles = $(this).attr('data-off');
        var hvrStyles = $(this).attr('data-hover');
        if ($(this).attr('data-firstoff') && $(this).attr('data-firsthover')) {
            var dftFirstStyles = $(this).attr('data-firstoff');
            var hvrFirstStyles = $(this).attr('data-firsthover');
        }

        $(this).find('a').each(function (index) {
            if (dftFirstStyles != undefined && index == 0) {
                $(this).attr('style', dftFirstStyles);

                $(this).on('mouseenter', function (e) {
                    e.stopPropagation();
                    $(this).attr('style', hvrFirstStyles);

                }).on('mouseleave', function (e) {
                    e.stopPropagation();
                    $(this).attr('style', dftFirstStyles);
                });

            } else {
                $(this).attr('style', dftStyles);

                $(this).on('mouseenter', function (e) {
                    e.stopPropagation();
                    $(this).attr('style', hvrStyles);

                }).on('mouseleave', function (e) {
                    e.stopPropagation();
                    $(this).attr('style', dftStyles);
                });
            }

        });
    });
}
setCta();

//  Dynamisation CTA
var setAccessoires = function () {

    $('.showroom.clsaccessoires .folder.mosaic').each(function () {
        var fontStyles = $(this).attr('data-fontroll');
        var hvrStyles = $(this).attr('data-roll');

        $(this).find('a').each(function (index) {

            var span = $(this).find('span'),
                    figcaption = $(this).find('figcaption');
            $(this).on('mouseenter', function (e) {
                e.stopPropagation();
                $(span).attr('style', hvrStyles);
                $(figcaption).attr('style', fontStyles);

            }).on('mouseleave', function (e) {
                e.stopPropagation();
                $(span).attr('style', '');
                $(figcaption).attr('style', '');
            }).on('closeToggle', function (e) {
                if ($(this).parent().hasClass('open')) {
                    $(span).attr('style', hvrStyles);
                    $(figcaption).attr('style', fontStyles);
                } else {
                    $(span).attr('style', '');
                    $(figcaption).attr('style', '');
                }
            });
        });
    });
}
setAccessoires();

//  Dynamisation Languette
var setLanguette = function () {

    $('.showroom.clslanguetteshowroom div.folder').each(function () {
        var dftStyles = $(this).attr('data-off');
        var hvrStyles = $(this).attr('data-hover');

        $(this).find('a').each(function () {
            $(this).attr('style', dftStyles);

            $(this).on('mouseenter', function (e) {
                e.stopPropagation();
                $(this).attr('style', hvrStyles);

            }).on('mouseleave', function (e) {
                e.stopPropagation();
                $(this).attr('style', dftStyles);
            });

        });
    });
}
setLanguette();

//  Dynamisation Sticky
var setSticky = function () {

    $('.stickyplaceholder').each(function () {

        if ($(this).hasClass('showroom')) {
            var oThis = this,
                    stickyBg = $(oThis).data('bg')
            sticky = $(oThis).find('.sticky'),
                stickyLinks = $(sticky).find('a');

            oThis.dftStyles = '';
            oThis.hvrStyles = $(oThis).data('hover');
            oThis.onStyles = $(oThis).data('on');

            $(sticky).attr('style', stickyBg);

            $(sticky).on('stickyoff', function () {
                $(sticky).attr('style', stickyBg);
            });

            $(stickyLinks).each(function () {
                var dftStyles = '',
                        hvrStyles = '',
                        onStyles = '';

                ($(this).parent().hasClass('on')) ? dftStyles = oThis.onStyles : dftStyles = oThis.dftStyles;
                ($(this).parent().hasClass('on')) ? hvrStyles = oThis.onStyles : hvrStyles = oThis.hvrStyles;
                $(this).attr('style', dftStyles);
                $(this).on('mouseover', function (e) {
                    e.stopPropagation();
                    $(this).attr('style', hvrStyles);

                }).on('mouseout', function (e) {
                    e.stopPropagation();
                    $(this).attr('style', dftStyles);
                });
            });
        }

    });

}
setSticky();

//  Dynamisation PagerShowroom
var setPagerShowroom = function () {

    $('.showroom.clspagershowroom ul.navigate').each(function () {
        var dftStyles = $(this).attr('data-on');

        $(this).find('li a').each(function () {
            $(this).attr('style', dftStyles);

        });
    });
}
setPagerShowroom();

//  Dynamisation Comparateur
var setComparateur = function () {

    $('#form_comparateur').each(function () {

        if ($(this).parents().hasClass('showroom')) {
            var oThis = this,
                close = $(oThis).find('.closer');

            oThis.bg = $(oThis).data('bg');
            oThis.brdr = $(oThis).data('border');
            oThis.brdrL = $(oThis).data('borderLeft');

            $(close).each(function () {
                $(this).attr('style', oThis.bg);
            });


            $(window).on('comparUpdated', function (e) {
                var toggle = $(oThis).find('.folder'),
                        datasTBth = $(oThis).find('.datas tbody th, .datas tbody td'),
                    datasOdd = $(oThis).find('.datas .odd'),
                    circle = $(oThis).find('.circle'),
                        sqLbl = $(oThis).find('.squarelabel');
                $(circle).each(function () {
                    $(this).attr('style', oThis.bg)

                });
                $(sqLbl).each(function () {
                    $(this).attr('style', oThis.brdr)

                });
                $(toggle).each(function () {
                    var square = $(this).find('.square');
                    $(square).attr('style', oThis.bg);
                    //$(this).attr('style',oThis.brdr);
                    $(this).on('click', function () {
                    });

                    $(this).on('closeToggle', function () {
                        //$(this).attr('style',oThis.brdr);
                    }).on('openToggle', function () {
                        //$(this).attr('style','border-bottom:0px;');
                    });
                })
                /*
                 $(datasTBth).each(function(){
                 if(!$(this).attr('colspan')){
                 $(this).attr('style',oThis.brdr);
                 }
                 });
                 $(datasOdd).each(function(){
                 var setStyle = $(this).attr('style');
                 $(this).attr('style',setStyle+oThis.bg);
                 });
                 */

            });
        }

    });

}
setComparateur();


var Loader = function (target) {
    this.init(target);
};
Loader.prototype = {
    init: function (target) {
        this.interval = 0;

        this.$tpl = $('<div class="loading"><div class="circ"></div></div>');
        this.$target = $(target) || $('body');
        this.$circ = this.$tpl.find('.circ');

        if (!this.$target.is('body')) {
            this.$target.css('position', 'relative');
            this.$tpl.css({
                'width': this.$target.innerWidth(),
                'height': this.$target.innerHeight(),
                'min-height': 0,
                'position': 'absolute'
            });
        }

        return this;
    },
    show: function (text, background) {
        var oThis = this;
        this.$tpl.appendTo(this.$target);

        if (typeof text !== 'undefined' && text.length) {
            this.$tpl.append('<div class="loading-text">' + text + '</div>');
            this.$tpl.find('div').wrapAll("<div class='loading_content wtxt'/>");
        } else {
            if ($(oThis.$target).attr('data-loadtext')) {
                text = $(oThis.$target).attr('data-loadtext');
                this.$tpl.append('<div class="loading-text">' + text + '</div>');
                this.$tpl.find('div').wrapAll("<div class='loading_content wtxt'/>");
            } else {
                this.$tpl.find('div').wrapAll("<div class='loading_content'/>");
            }
        }

        this.$tpl.find('.loading_content').not('.wtxt').css('margin-top', (this.$tpl.height() / 2) - 37);

        if (!background) {
            this.$tpl.css('background', 'none');
        }
    },
    hide: function () {
        clearInterval(this.interval);
        this.$tpl.find('.loading_content div').unwrap();
        this.$tpl.find('.loading_content').removeClass('wtxt');
        this.$tpl.remove();
        this.$tpl.find('.loading-text').remove();
        this.$tpl.css('background', '');
    }
};

/**
 * CAR SHAPE SELECTOR
 **/
function openCarShape() {
    $('.carshape-popin').css({
        'display': 'block'
    });
    $('.carshape-popin').find('.popClose, .closer').on('click', function () {
        $('.carshape-popin').css({
            'display': 'none'
        });
        $(this).off('click');
    });
}


/**
 * CAR SELECTOR
 **/

var CarSelector = function (root) {
    this.init(root);
};
CarSelector.prototype = {
    init: function (root) {
        this.root = $(root);
        this.filterRoot = this.root.parents('#selector');
        this.inputs = this.root.find('input');
        this.mastercarsGroup = $('.mastercars-group');
        this.resetButton = this.filterRoot.find('.button');
        this.tab = (this.root.attr('id') === 'fFormType1') ? this.filterRoot.find('.tabs li:first') : this.filterRoot.find('.tabs li:last');
        this.setHandlers();
    },
    setHandlers: function () {
        var oThis = this;
        this.inputs.click(function () {
            oThis.ajaxPost();
        });

        /*
         this.inputs.next('.drag').on(STARTEVENT, function(){
         $('#body').bind('mouseup', function(e){
         e.stopPropagation();
         oThis.ajaxPost();
         });
         });
         */

        this.inputs.next('.drag').on(ENDEVENT, function () {
            oThis.ajaxPost();
        });

        this.resetButton.click(function (e) {
            e.preventDefault();
            oThis.resetForm();
        });

        this.tab.click(function () {
            oThis.ajaxPost();
        });
    },
    ajaxPost: function () {
        var oThis = this;



        if (this.root.parents('.tab').hasClass('opened')) {
            var request = $.ajax({
                url: oThis.root.attr('action'),
                type: "POST",
                data: oThis.root.serialize(),
                dataType: "html",
                beforeSend: function (jqXHR, textStatus) {
                    oThis.loader = new Loader($('#selector'));
                    oThis.loader.show('', false);
                }
            });

            request.done(function (response) {
                oThis.loader.hide();
                oThis.mastercarsGroup.html(response);
                lazy.set(oThis.mastercarsGroup.find('img.lazy'));
            });

            request.success(function () {
                oThis.filterRoot.find('.count').html($('#nbcars').val());
            });

            request.fail(function (jqXHR, textStatus) {
            });
        }
    },
    resetForm: function () {
        this.root[0].reset();
        this.root.find('input[type="checkbox"], input[type="radio"]').removeAttr('checked');
        this.root.find('.range').each(function () {
            var input = this.getElementsByTagName('input')[0],
                    defaultValue = input.getAttribute('data-to');

            input.value = defaultValue;
            if (input.name === 'passengers') {
                input.value = input.getAttribute('data-from');
            }

            $(input).trigger('change');
        });

        this.root.find('input[value*=TOUT]').each(function () {
            this.checked = true;
        });

        this.ajaxPost();

    }
};
/**
 * END CAR SELECTOR
 **/

// Placeholder
var placeholder = {
    // Create
    create: function () {
        this._placeholder = $(this).attr("placeholder");

        if (this.id != "address") {
            this.removeAttribute('placeholder');
        }
        // Default
        placeholder.blur.apply(this);
        // Event
        $(this).focus(placeholder.focus).blur(placeholder.blur);
    },
    // Focus
    focus: function () {
        // Not filled yet
        if (this.value == this._placeholder) {
            this.value = "";
        }
    },
    // Blur
    blur: function () {
        // Not filled
        if (this.value === "") {
            this.value = this._placeholder;
        }
    }
};



// SCROLL INICITE
function scrollIncite() {
    var wHeight = $(window).height(),
            ctHeight = $(document).height(),
            obj = $(document).find('.scroll-incite'),
            objArrow = $(obj).find('span'),
            objBg = $(obj).attr('data-bg');
    if (ctHeight > wHeight) {
        $(obj).show();
        $(obj).css({
            marginLeft: -$(obj).width() / 2,
            background: objBg
        })
    }

    var animArrow = function () {
        $(objArrow).animate({
            top: 5
        }, 250, function () {
            $(objArrow).animate({
                top: 11
            }, 250);
        })
        $(obj).animate({
            opacity: 0.8
        }, 250, function () {
            $(obj).animate({
                opacity: 1
            }, 250);
        })
    }

    var animInterval = setInterval(function () {
        animArrow();
    }, 500);
    var clearAnim = setTimeout(function () {
        clearInterval(animInterval);
    }, 2500);

    $(obj).on('click', function () {
        $('html, body').animate({
            scrollTop: wHeight
        }, 500);
    });

    $(document).on('scroll', function () {

        if (ctHeight <= 2 * wHeight) {
            if ($(window).scrollTop() >= 200) {
                $(obj).fadeOut();
            }
        } else {
            if ($(window).scrollTop() >= wHeight) {
                $(obj).fadeOut();
            }
        }
    });

}


/*-------------------- Lazy loading  --------------------*/

var lazy = {
    // File queue
    queue: []
    // Parallel queue files
    ,
    parallelQueue: 5
    // Parallel slots
    ,
    slots: 0
    /**
     * Force teh lazy loading of an image
     * @param  {ImageElement} el DOM image to load
     */
    ,
    forceLazyLoad: function (el) {
        // If slots are occupied
        if (lazy.slots > lazy.parallelQueue || lazy.slots < 0) {
            // Cancel
            return;
        }

        // If the el is in the queue
        if (lazy.queue.indexOf(el) !== -1) {
            // Remove it
            lazy.queue.splice(lazy.queue.indexOf(el), 1);
        }

        $(el)
                // On image load
            .on('load', function() {
                    // Free last slot queue
                    lazy.slots--;

                    // Call the clean method
                    lazy.clean.call(this);

                    // Load the next image in the queue
                    lazy.nextQueue();
                })
                // Set image lazyload
                .lazyload({
                    event: 'show'
                })
                // Trigger lazyload
                .trigger('show');
        // Add to available slot
        lazy.slots++;
    }
    /**
     * Cancel the current queue
     */
    ,
    nextQueue: function () {
        // Check empty/negative queue with empty/negative slot count
        if (lazy.queue.length < 1 && lazy.slots < 1) {
            lazy.cancelQueue();
        }

        // If all queue slots are occupied
        if (lazy.parallelQueue - lazy.slots < 1) {
            // Cancel next queue load
            return;
        }

        // Load empty slots
        for (var i = 0; i < lazy.parallelQueue - lazy.slots; i++) {
            if (lazy.queue[i]) {
                lazy.forceLazyLoad(lazy.queue[i]);
            }
        }
    }
    /**
     * Cancel the current queue
     */
    ,
    cancelQueue: function () {
        lazy.queue = [];
        lazy.slots = 0;
    }
    /* Set lazyloading on images provided - jQuery collection */
    ,
    set: function ($imgs, callback) {

        $imgs.lazyload({
            threshold: 200,
            load: lazy.clean
        });

        if (callback !== undefined && typeof callback === 'function') {
            setTimeout(function () {
                callback();
            }, 300);
        }
    },
    /* Force image loading on provided list - jQuery collection */
    load: function ($imgs, callback) {
        $imgs.each(function (ind, el) {
            // If src equals lazy image src
            if (el.getAttribute('src') == el.getAttribute('data-original')) {
                // Cancel its lazy loading
                return;
            }

            // Add to queue if not yet added
            if (lazy.queue.indexOf(el) === -1) {
                lazy.queue.push(el);
            }
        });

        // Launch next queue load
        lazy.nextQueue();

        if (callback !== undefined && typeof callback === 'function') {
            setTimeout(function () {
                callback();
            }, 300);
        }
    },
    /* Remove lasy CSS class */
    clean: function () {
        var
                $this = $(this),
                $slider = $this.parents('.slider');

        $this.removeClass('lazy');

        if ($slider.length) {
            $slider.find('.row').trigger('redrawSlider');
        }

        lazy.applyheight();
    },
    /* Apply height of two panorama img to portrait img beside in galery */
    applyheight: function () {
        if (sync)
            sync.set();

        $('.gallery .row.of2 .col').each(function (index) {
            var
                    $this = $(this),
                    imgs = $this.find('img');

            if (imgs.length > 1) {
                $this.addClass('blockpano');
            } else if (imgs.length === 1) {
                $this.addClass('blockportrait');
            }
            /*
             var hauteurpano = $('.blockpano').height();
             $('.blockportrait img').height(hauteurpano - 19);
             
             $(window).resize(function () {
             setTimeout(function () {
             hauteurpano = $('.blockpano').height();
             $('.blockportrait img').height(hauteurpano - 19);
             }, 300);
             });
             */
        });
    }
};

/*-------------------- Timeline --- HISTOIRE -----------------*/

var timeline = {
    ws: null,
    current: null,
    next: null,
    loading: false,
    latest: 0,
    build: function () {

        /* Vars */
        var me = this;

        me.$links = $(me).find('.dates li');
        timeline.ws = me.getAttribute('data-ws');

        /* Init each link and set event */
        me.$links
                .each(function (i) {
                    /* Vars */
                    var
                            link = this,
                            part = $(link).find('a').attr('data-part'),
                            date = $(link).find('a').html(),
                            exist = $('#' + part).get(0);

                    /* Append new content and order */
                    if (!exist) {
                        /* Create placeholders */
                        var
                                tpl = $('#holderTpl').html(),
                                compiledTemplate = _.template(tpl, {
                                    part: part,
                                    date: date
                                });
                        $(me).append(compiledTemplate);
                    } else {
                        $(me).append(exist);
                    }

                    /* Link */
                    link._holder = $('#' + part).get(0);
                    link._holder._link = this;

                    /* Placeholders events */
                    $(link._holder).click(function (e) {
                        timeline.load.call(link, e);
                    });

                    /* Laod first */
                    if (0 == i)
                        timeline.load.call(this, null, true);
                    window.scrollTo(0, 1);

                })
                .click(timeline.load);

        /* Check on scroll */
        $(window).scroll(function () {
            timeline.check(me);
        });

    },
    check: function (me) {

        /* Prevent multiple calls */
        if (timeline.loading)
            return;

        /* Vars */
        var
                $holders = $(me).find('.wait'),
                scroll = $(window).scrollTop(),
                bottom = scroll + $(window).height();

        /* If upway scroll, reset next */
        if (bottom < timeline.latest) {
            timeline.next = null;
        }
        timeline.latest = bottom;

        /* Set next portion */
        if (!timeline.next) {
            $holders.each(function (ind, el) {
                if ($(el).offset().top > bottom && !timeline.next) {
                    timeline.next = el;
                }
            });
        }
        /* Or check if it needs to load */
        else {
            if ($(timeline.next).offset().top < bottom) {
                timeline.load.call(timeline.next._link);
            }
        }

        // loop each sections
        var currentSectionId;
        $(me).find('[data-date]').each(function (ind, el) {
            // check if the section is in the view
            if ($(el).offset().top < bottom) {
                currentSectionId = el.id;
            }
        });

        if (!timeline.nextImgLoad) {
            timeline.nextImgLoad = currentSectionId;
        } else if (timeline.nextImgLoad == currentSectionId) {
            // check if the section is in the view
            if ($('#' + currentSectionId).offset().top < bottom) {
                // Lazy loading the images from the section
                if ($('img.lazy', $('#' + currentSectionId)).length) {
                    lazy.cancelQueue();
                    lazy.load($('img.lazy', $('#' + currentSectionId)));
                }
            }

        } else {
            timeline.nextImgLoad = null;
        }
    },
    load: function (event, avoidCurrentQueueCancelling) {
        /* Prevent default event if present */
        if (event)
            event.preventDefault();

        /* Prevent multiple calls */
        if (timeline.loading)
            return;
        timeline.loading = true;

        /* Vars */
        var
                me = this,
                dom = this._holder,
                $dom = $(dom);

        /* Already loaded */
        if (!$dom.hasClass('wait')) {
            timeline.loading = false;

            /* Cancel the previous loading by default */
            if (!avoidCurrentQueueCancelling)
                lazy.cancelQueue();

            /* Set lazy loading events */
            lazy.load($dom.find('img.lazy'));

            return;
        }

        /* Unbind holder */
        $(dom).unbind('click').addClass('loading');

        /* Service call */
        $.ajax({
            url: timeline.ws,
            type: 'GET',
            data: {
                date: $(me).find('a').attr('data-decade')
            },
            dataType: 'json',
            success: function (response) {
                /* Compile and append */
                var
                        tpl = $('#decadeTpl').html(),
                        compiledTemplate = _.template(tpl, {
                            data: response
                        });

                $dom.removeClass('wait loading').html(compiledTemplate);

                /* Cancel the previous loading by default */
                if (!avoidCurrentQueueCancelling)
                    lazy.cancelQueue();

                /* Set lazy loading events */
                lazy.load($dom.find('img.lazy'));

                popInit();

                /* Scroll to dom */
                timeline.next = null;

                /* Little delay while sticky scroll managment */
                setTimeout(function () {
                    timeline.loading = false;
                }, 1000);
            }
        });

    }
};

/*-------------------- Sticky --------------------*/

/* Build sticky, store DOM references */
var sticky = {
    // Offset
    offset: 0
    // Current sticky DOM elements
    ,
    stack: []
    // Current checking status
    ,
    loading: false
    // Useful selectors
    ,
    selectors: {
        logo: '.sticky .logo',
        top: '.sticky .top',
        closer: '.cont .closer',
        links: 'ul li a'
    }

    /**
     * Build the sticky behavior
     */
    ,
    build: function () {
        var
                // This element
                me = this
        // Get the scroll width
                ,
                scrollWidth = (function () {
                    var a = $('<div style="width:50px;height:50px;overflow:auto"><div/></div>').appendTo("body"),
                            b = a.children(),
                            b = b.innerWidth() - b.height(99).innerWidth();
                    a.remove();
                    return b;
                })();

        // Set a data-sticky to the sticky element
        $(me).children(':first').data('sticky', 'sticky-' + Date.now());

        // Save the status as unsticked
        me.sticked = false;

        // Update the height
        me.height = $(me).children(':first').outerHeight();

        // Adjust the position
        $(me).children(':first').find('> .inner').css('right', scrollWidth + 'px');

        // Save the reference of each part in a new array
        me.parts = [];
        $(me).find(sticky.selectors.links).each(function (ind, el) {
            // If there is no hash or data-part attribute, we don't store the link
            if ('#' != el.getAttribute('href').charAt(0) && null == el.getAttribute('data-part')) {
                return;
            }

            // We get the ID of the referenced element
            var id = el.getAttribute('data-part') || el.getAttribute('href').substr(1);

            // If the referenced element exists
            if ($('#' + id).length) {
                // We store the ID
                me.parts.push(id);

                // On click on the link
                $(el).click(function (e) {
                    // We cancel the event
                    e.preventDefault();

                    // We navigate to the referenced element
                    sticky.go(id);
                });

            }
        });

        // Hide when the closer is clicked
        $(me).find(sticky.selectors.closer).click(function () {
            $(me).addClass('hidden');
        });

        // Call the manage method right now
        sticky.manage.apply(me);

        // Call the manage method on window scroll
        $(window).scroll(function () {
            sticky.manage.apply(me);
        });

        var url = document.location;
        if (url.hash) {
            var urlId = url.hash.substr(1, url.hash.length);
            //console.log(urlId);
            if (urlId != "sticky") {
                setTimeout(function () {
                    sticky.go(urlId);
                }, 5000);

            }
        }
    },
    /* Manage position static / fixed */
    manage: function () {
        // If we already check the position, don't do it twice
        if (this.loading)
            return;

        // Save the current checking status
        this.loading = true;

        // Vars
        var
                // Current object
                me = this
        // The current window scroll position
            ,
            current = $(window).scrollTop()
        // The top position of the parent element
            ,
            limit = $(me).offset().top
        // Is the last sticky element ?
            ,
            islast = sticky.stack[sticky.stack.length - 1] == $(me).children(':first').data('sticky')
        // Is the sticky fixed ?
            ,
            fixed = islast ? current >= limit - sticky.offset + me.height - 5 // 5px round
                : current >= limit - sticky.offset - 5 // 5px round
        // Is the sticky a stacky element ?
            ,
            stackit = !$(me).hasClass('top') && !$(me).hasClass('stripper')
        // ??
                ,
                keep = $(me).hasClass('top') || $(me).hasClass('keep');

        // If has parts
        if (me.parts.length) {
            // Vars
            var active = 0;

            // Remove existing activation
            $(me).find(sticky.selectors.links).parent().removeClass('on');

            // We find the active part
            for (var i = me.parts.length - 1; i > -1; i--) {
                // Compare the current window scroll to the top of the referenced elements minus 101 (?)
                if (current > $('#' + me.parts[i]).offset().top - 101) {
                    // If it's higher, we set the element as active
                    active = i;

                    // And break the loop
                    break;
                }
            }

            // Update the activated status
            $(me).find(sticky.selectors.links).eq(active).parent().addClass('on');
        }

        // If the sticky need to be fixed
        if (fixed) {
            // Manage bounds
            var
                    $parent = $(me).parent(),
                    $child = $(me).children(),
                    max = Math.round($parent.offset().top) + $parent.height() - $child.outerHeight() - $child.position().top,
                    offset = max - current;

            // if offset is positive, set it to 0
            if (offset > 0) {
                offset = 0;
            }

            // If ??, update the top margin of the children
            if (!keep) {
                $child.css({
                    marginTop: offset
                });
            }

            // If already fixed, don't do anything more
            if (me.sticked) {
                this.loading = false;
                return;
            }

            // Set the status of the element as sticked
            me.sticked = true;

            // Set the element as fixed
            $(me).children(':first').addClass('fixed');
            if ($(me).hasClass("listickholder")) {
                $(me).css({
                    height: $(me).children(':first').height()
                });
            }
            $(me).find(sticky.selectors.logo).fadeIn();
            $(me).find(sticky.selectors.top).fadeIn();

            // Update the top position
            $(me).children(':first').css({
                top: sticky.offset
            });

            // Sticky stack
            if (stackit) {
                // Update the saved offset
                sticky.offset += me.height;

                // Save the sticky to the stack
                sticky.stack.push($(me).children(':first').data('sticky'));
            }

            // If .sticker exists
            if ($('.sticker').length > 0) {
                // Set the fixed sticky to the maximal top position
                $('.sticky.fixed').css('top', '0');
            }
        }
        // If the sticky need to be static
        else {
            // If already static, don't do anything more
            if (!me.sticked) {
                this.loading = false;
                return;
            }

            // Set the status of the element as not sticked
            me.sticked = false;

            // Set the element as static
            $(me).children(':first').removeClass('fixed');
            if ($(me).hasClass("listickholder")) {
                $(me).css({
                    height: 'auto'
                });
            }
            $(me).find(sticky.selectors.logo).hide();
            $(me).find(sticky.selectors.top).hide();

            // Cleanup the styles
            $(me).children(':first').trigger('stickyoff');

            /*
             // CPW-4276 Set the .listick to the maximal top position +1 to bind correctly with next element (table) 
             $(me).children(':first').each(function(){
             this.style.setProperty('top','1px');
             });
             */

            // Sticky unstack
            if (stackit) {
                // Update the saved offset
                sticky.offset -= me.height;

                // Remove the sticky from the stack
                sticky.stack.pop();
            }
        }

        // Revert the status indicator
        this.loading = false;
    }

    /**
     * Navigate to an element
     * @param  {String} id The ID of the element to navigate to
     */
    ,
    go: function (id) {
        var
                // jQuery element
                $target = $('#' + id)
        // Top offset of the element minus 80 (?) rounded to the nearest integer
                ,
                scroll = Math.round($target.offset().top) - 80;
        //console.log("$target.offset().top : "+ $target.offset().top + " / TOTAL : " +scroll);
        // Navigate to the calculated scroll
        $('html, body').scrollTop(scroll);
    }
};

function scrollToTop() {
    // $('html, body').scrollTop(0);
    $('html, body').animate({
        scrollTop: 0
    }, 500);
}

/*-------------------- Tabs --------------------*/

var synctab = 0;

var Tabbs = function (root) {
    this.init(root);
}
Tabbs.prototype = {
    init: function (root) {
        this.root = root;
        this.$tabs = $(this.root).find('.tab');
        this.opened = null;
        this.openTab = 0;
        this.ul = $('<ul></ul>');

        this.buildTabs();
    },
    buildTabs: function () {
        var oThis = this;
        this.$tabs.each(function (index) {
            var title = $(this).find('> .tabtitle, > * > .tabtitle');
            var selected = $(this).find('> .selectedTab, > * > .selectedTab');
             var masterVn = $(this).find('> .masterVn, > * > .masterVn');
             if(selected.length == 1){
             oThis.openTab = index;
             }
             if(masterVn.length == 1){
             oThis.masterVn = masterVn.length;
             }
            if (!title) return;
            var li = $('<li></li>');
            li.root = oThis.root;
            li.tab = this;
            li.index = index;
            li.sync = li.tab.getAttribute('data-sync') || false;
            li.click(function () {
                oThis.show(li);
            });
            /* Append tab */
            title.clone().removeClass().appendTo(li);
            li.appendTo(oThis.ul);
        });
        this.ul.appendTo($(this.root).find('> .tabs, > * > .tabs'));
        $(this.root).find('> .tabs li, > * > .tabs li').eq(this.openTab).trigger('click');

        $(".tabs.vndetailsmenu li").hide();
        $(".tabs.vndetailsmenu li:lt(2)").show();


        //skinTabs();

    },
    skinTabs: function () {
        var oThis = this;
        //  Dynamisation Onglet
        var tab = $(oThis.root).find('.tabs');
        oThis.offStyles = $(tab).attr('data-off'),
            oThis.hvrStyles = $(tab).attr('data-hover'),
            oThis.onStyles = $(tab).attr('data-on');
        $(tab).find('h4').each(function (index) {
            var dftStyles = oThis.offStyles,
                hvrStyles = oThis.hvrStyles;
            ($(this).parent().hasClass('on')) ? dftStyles = oThis.onStyles : dftStyles = oThis.offStyles;
            ($(this).parent().hasClass('on')) ? hvrStyles = oThis.onStyles : hvrStyles = oThis.hvrStyles;
            $(this).attr('style', dftStyles);
            $(this).on('mouseover', function (e) {
                e.stopPropagation();
                $(this).attr('style', hvrStyles);

            }).on('mouseout', function (e) {
                e.stopPropagation();
                $(this).attr('style', dftStyles);
            });

        });

    },
    show: function (li) {
        var liOpened = li,
                linked = $(liOpened).find('a').attr('href'),
                oThis = this;

        if (linked && oThis.masterVn != 1){
         return;
         }else if(oThis.masterVn == 1 && linked &&  linked!= "undefined" && linked != "#")
         {
         document.location.href = linked;
         return;
         }
        //if (linked) return;
        if (this.opened && this.opened != liOpened)
            this.hide(this.opened);

        /* Show */
        $(liOpened).addClass('on');
        $(liOpened.tab).addClass('opened');
        if (liOpened.sync)
            $('#' + liOpened.sync).removeClass('hidden');
        /* Store */
        this.opened = liOpened;

        /* Lazy load */
        var $lazy = $(liOpened.tab).find('img.lazy');
        lazy.load($lazy);

        var getSlider = $(liOpened.tab).find('.slider .row');
        getSlider.trigger('redrawSlider');

        sync.set();

        function redimensionner(selecteur) {
            var hauteur = 0;
            $(selecteur).each(function () {
                if ($(this).height() > hauteur) {
                    hauteur = $(this).height();
                }
            });

            $(selecteur).each(function () {
                $(this).height(hauteur);
            });
        }

        redimensionner('h4.boxtitle');

        var marginvalue = $('h4.boxtitle').css('height');
        var finalmargin = parseInt(marginvalue) + 12;

        $('h4.boxtitle').css('margin-bottom', finalmargin);
        if ($(oThis.root).parents('.showroom')) {
            oThis.skinTabs();
        }

    },
    hide: function () {
        if ($('.opened h4').length) {
            $(this).removeAttr('style');
        }

        $(this.root).find('> .tabs li, > * > .tabs li').removeClass('on');
        $(this.opened.tab).removeClass('opened');
        if (this.opened.sync)
            $('#' + this.opened.sync).addClass('hidden');
    }
}



/*-------------------- Colors --------------------*/

// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
;
(function ($, window, document, undefined) {

    // undefined is used here as the undefined global variable in ECMAScript 3 is
    // mutable (ie. it can be changed by someone else). undefined isn't really being
    // passed in so we can ensure the value of it is truly undefined. In ES5, undefined
    // can no longer be modified.

    // window and document are passed through as local variable rather than global
    // as this (slightly) quickens the resolution process and can be more efficiently
    // minified (especially when both are regularly referenced in your plugin).

    // Create the defaults once
    var pluginName = 'jColors',
    /* Defaults */
            defaults = {
                /*field:null*/

                /* Callbacks */
                /* onLoad:function(){ } */

            };

    // The actual plugin constructor
    function Plugin(element, options) {

        // jQuery has an extend method which merges the contents of two or
        // more objects, storing the result in the first object. The first object
        // is generally empty as we don't want to alter the default options for
        // future instances of the plugin
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this._element = element;
        this._slider = null;
        this.init();
    }

    Plugin.prototype = {
        /* Initalisation */
        init: function () {

            /* Vars */
            var me = this,
                    el = me._element,
                    $el = $(el),
                    $bg = $el.find('figure .lazy'),
                    $list = $el.find('> ul'),
                    $colorsAvailable = this.$colorsAvailable = ($list !== undefined && $list.attr('data-text') === undefined) ? false : true;
            $colors = $list.find('a');
            this.$btnColors = $list.find('li');

            me.tabImages = window.tabImages;


            if ($el.hasClass('showroom')) {
                // GESTION DU DE L'ENCART TEXTE
                var extraH = ($el.find('.texts .more small').length > 0) ? parseInt($el.find('.texts .more small').height()) : 0;
                var extraMT = ($el.find('.texts .more small').length > 0) ? parseInt($el.find('.texts .more small').css('margin-top')) : 0;
                $el.append('<div class="texts-bg"></div>');
                var colpickShowroomTextH = parseInt($el.find('.texts').height()) - extraH - extraMT + 40;
                me.textBg = $el.attr('data-textBg');
                $(el).find('.texts-bg').attr('style', me.textBg + 'height:' + colpickShowroomTextH + 'px');
                var colSmallTextH = colpickShowroomTextH + 50;
                $el.find('.smallText').attr('style','top:'+colSmallTextH+'px');

                //DEFINITION DES COULEURS DU SLIDER
                me.arrowsColor = $el.attr('data-arrows');
                me.pagerColor = $el.attr('data-pagerBg');

                //GESTION DES BOUTONS DU COLORPICKER 
                me.colorHvr = $el.attr('data-colorHover');


                this.$btnColors.each(function () {
                    var dftStyles = '',
                            hvrStyles = me.colorHvr;

                    $(this).on('mouseenter', function (e) {
                        $(this).attr('style', hvrStyles);
                    });

                    $(this).on('mouseleave', function (e) {
                        $(this).attr('style','');
                    });
                });


            }


            /* Const */
            me._tpl = $el.find('.viewTpl').html();
            me.$bg = $bg;

            /* Hide color picker if there's one color */
            if ($colors.length < 1 || !$colorsAvailable) {
                $list.hide();
            }

            /* Append tools if more than twelve colors */
            if (12 < $colors.length && $colorsAvailable) {
                $list.addClass('plus').prepend('<li class="p">&lt;</li>').append('<li class="n">&gt;</li>');
                /* Prev */
                $el.find('.p').click(function () {
                    var $c = $(this).parent().find('li').not('.p,.n');
                    $c.last().insertBefore($c.first());
                });
                /* Next */
                $el.find('.n').click(function () {
                    var $c = $(this).parent().find('li').not('.p,.n');
                    $c.first().insertAfter($c.last());
                });
            }

            /* Delay init */
            if ($colors.length && $colorsAvailable) {
                if ($bg.length) {
                    $bg.one('load', function () {
                        me.change.call(me, null, $colors.get(0));
                    });
                } else {
                    me.change.call(me, null, $colors.get(0));
                }

                /* Events */
                $colors.each(function () {
                    this._refs = (this.getAttribute('data-refs') || '').split('|');
                }).click(function (e) {
                    me.change.call(me, e, this);
                });
            } else if ($colors.length) {
                me.change.call(me, null, $colors);
            }

            $list.css('visibility', ($colors.length <= 1) ? 'hidden' : 'visible');

            if (me.tabImages != null && me.tabImages.length > 0) {

                /* SWITCH VIEWS */
                if ($(me._element).find('.view-selector').length > 0) {

                    var canvas = document.createElement('canvas'), context;
                    //Internet Explorer browser detector :
                    var isIE = {
                        browser:/*@cc_on!@*/false,
                        detectedVersion: function () {
                            return (typeof window.atob !== "undefined") ? 10 :
                                (typeof document.addEventListener !== "undefined") ? 9 :
                                    (typeof document.querySelector !== "undefined") ? 8 :
                                        (typeof window.XMLHttpRequest !== "undefined") ? 7 :
                                            (typeof document.compatMode !== "undefined") ? 6 : 5;
                        }
                    };

                    //iOS browser dectector :
                    var isiOS = {
                        browser: (/iPad|iPhone|iPod/.test(navigator.platform)),
                        detectedVersion: function () {
                            if (!!window.indexedDB) {
                                return 8;
                            } 					// iOS 8
                            if (!!window.SpeechSynthesisUtterance) {
                                return 7;
                            } 	// iOS 7
                            if (!!window.webkitAudioContext) {
                                return 6;
                            }  		// iOS 6
                            if (!!window.matchMedia) {
                                return 5;
                            } 					// iOS 5
                            if (!!window.history && 'pushState' in window.history) {
                                return 4;
                            }  // iOS 4
                            return 100; // Si non trouvé, on considère que la version est la plus récente.
                        }
                    };

                    if ( canvas.getContext && !(isIE.browser && isIE.detectedVersion() <= 9) && !(isiOS.browser && isiOS.detectedVersion() <= 7)   ) {
                        $(me._element).find('.view-selector').css({'display': 'inline-block'});
                        me.scriptsLoaded = 0;
                    }

                    $(me._element).find('.view-selector').find('> div').each(function () {
                        if ($(this).hasClass('outside')) {
                            $(this).find('a').on('click', function (e) {
                                e.preventDefault();
                                $(me._element).find('.extraCtas').hide();
                                me._countDispView = 0;
                                me._countDispViewArr = [];
                                me._countDispViewArr.push(me._current);
                                me._countDispView = me._countDispView + 1;
                                $(me._element).find('.view-360').hide();
                                $(me._element).find('figure').show();
                                $(me._element).find('.larger').show();
                                $(me._element).find('.texts').show();
                                if ($(me._element).find('.texts-bg').length > 0) {
                                    $(me._element).find('.texts-bg').show();
                                }
                                $(me._element).find('> ul').show();
                                $(me._element).find('.view-selector .active').removeClass('active');
                                $(this).addClass('active');
                                me._slider.trigger('redrawSlider');
                            });
                        } else {
                            $(this).find('a').on('click', function (e) {
                                e.preventDefault();
                                $(me._element).find('.extraCtas').hide();
                                me._countDispView = 0;
                                me._countDispViewArr = [];
                                $(me._element).find('figure').hide();
                                $(me._element).find('.larger').hide();
                                $(me._element).find('.view-360').show();
                                $(me._element).find('.texts').hide();
                                if ($(me._element).find('.texts-bg').length > 0) {
                                    $(me._element).find('.texts-bg').hide();
                                }
                                $(me._element).find('> ul').hide();
                                $(me._element).find('.view-selector .active').removeClass('active');
                                $(this).addClass('active');

                                if (me.scriptsLoaded == 0) {
                                    me.loader = new Loader($(me._element));
                                    me.loader.show('', true);
                                    me.loadScript();
                                }
                            });
                        }
                    });
                }
            }

        },
        /* Change */
        change: function (e, items) {
            var oThis = this;
            if (e)
                e.preventDefault();

            if (!$.isArray(items)) {
                items = $(items);
            }
            /* Vars */
            var me = this,
                    el = me._element,
                    $el = $(el),
                    imgs = [],
                    bgs = [],
                    views;

            items.each(function () {
                imgs = imgs.concat(this.getAttribute('data-views').split('|'));
                bgs = bgs.concat(this.getAttribute('data-bgs').split('|'));
            });

            views += _.template(me._tpl, {
                imgs: imgs,
                bgs: bgs
            });

            views = views.replace(/undefined/g, '');

            /* Remove previous slider if has one */
            if (me._slider) {
                var old = me._slider;
                old.animate({
                    opacity: 0
                }, 500, function () {
                    old.destroySlider();
                    old.remove();
                });
            }

            $el.attr('data-gtm-init', '0')

            /* Append built views */
            var multiple = 1 < imgs.length || 1 < bgs.length;
            me._slider = $el.find('.viewTpl').after(views).next().bxSlider({
                mode: 'fade',
                startSlide: (imgs.length > me._current) ? me._current : 0,
                pager: multiple,
                infiniteLoop: multiple,
                touchEnabled: false,
                controls: multiple,
                onSliderLoad: function (currentIndex) {
                    me._countDispView = 0;
                    me._countDispViewArr = new Array();
                    if ($(me._element).find('.extraCtas').length > 0) {
                        $(me._element).find('.extraCtas').css({'display': 'none'});
                    }
                    $el.find('.bx-wrapper').first().addClass('built');
                    me._current = (multiple) ? currentIndex : 0;
                    if (oThis.$colorsAvailable) {
                        me.setRef(items[0], currentIndex);
                    }
                    me._countDispViewArr.push(currentIndex);
                    me._countDispView = me._countDispView + 1;
                    if (me.arrowsColor) {
                        $(me._element).find('.bx-controls-direction a').attr('style', me.arrowsColor);
                        $(me._element).find('.bx-pager-link.active').attr('style', me.pagerColor);
                    }

                    /*$(window).resize(function() {
                     me.setArrows();
                     });*/
                },
                onSlideBefore: function ($slider, oldIndex, newIndex) {
                    if (me.arrowsColor) {
                        $(me._element).find('.bx-pager-link').attr('style', '');
                        $(me._element).find('.bx-pager-link.active').attr('style', me.pagerColor);
                    }
                },
                onSlideAfter: function ($slider, oldIndex, newIndex) {
                    if (oThis.$colorsAvailable) {
                        me._current = newIndex;
                        me.setRef(items[0], newIndex);
                    }
                    if (me._countDispViewArr.length == 3) {
                        if ($(me._element).find('.extraCtas').length > 0) {
                            $(me._element).find('.extraCtas').css({'display': 'table'});
                            sync.set();
                            $(me._element).find('.extraCtas').css({
                                //'margin-left':-225
                                'width': (parseInt($(me._element).find('.extraCtas').outerWidth()) + 1),
                            });
                            if ($(me._element).hasClass('showroom')) {
                                $(me._element).find('.extraCtas').css({
                                    //'margin-left':-225
                                    'margin-left': -($(me._element).find('.extraCtas').width() / 2)
                                });
                            } else {
                                $(me._element).find('.extraCtas').css({
                                    //'margin-left':-225
                                    'margin-left': -(($(me._element).find('.extraCtas').width() / 2) - 80)
                                });
                            }
                            $(me._element).find('.extraCtas .close').on('click', function(){
                                $(me._element).find('.extraCtas').css({'display': 'none'});
                                me._countDispView = 0;
                                me._countDispViewArr = [];
                                me._countDispViewArr.push(newIndex);
                                $(me._element).find('.extraCtas .close').on('click', function () {
                                });
                            })
                        }
                    }
                    if (me._countDispViewArr.indexOf(newIndex) === -1) {
                        me._countDispViewArr.push(newIndex);
                    }
                    me.$bg.attr('src', $el.find('.bx-wrapper:first [data-img]:eq(' + newIndex + ')').data('img'));
                    if (me.arrowsColor) {
                        $(me._element).find('.bx-pager-link').attr('style', '');
                        $(me._element).find('.bx-pager-link.active').attr('style', me.pagerColor);
                    }
                }
            });
            gtmCit.initjColors(me._slider);
            /*if (multiple) {
             me.setArrows();
             }*/

            /* Activate navigation */
            this.$btnColors.each(function () {
                if ($(this).hasClass('on')) {
                    $(this).removeClass('on').attr('style', '').on('mouseleave', function (e) {
                        $(this).attr('style', '');
                    });
                }
            });
            $(items[0]).parent().addClass('on').attr('style', me.colorHvr).on('mouseleave', function (e) {
                $(this).attr('style', me.colorHvr);
            });
        },
        loadScript: function () {
            var me = this;
            var t = $.getScript( tabScripts[me.scriptsLoaded] )
                    .done(function (script, textStatus) {
                        if (me.scriptsLoaded < tabScripts.length - 1) {
                            me.scriptsLoaded = me.scriptsLoaded + 1;
                            me.loadScript();
                        } else {
                            me.callBack360()
                        }
                    })
                    .fail(function (jqxhr, settings, exception) {
                        //console.log( tabScripts[me.scriptsLoaded] );
                        //console.log( exception );
                        //console.log('error loading JS')
                    });
        },
        callBack360: function (inc) {
            var me = this;
            //console.log('All scripts loaded');
            (function ($, Inside, PointOfInterest) {
                "use strict";
                me.inside = new Inside(document.getElementById('canvas'), $('.view-360'));
                me.inside.cubeSize = 100;
                me.inside.init(me.tabImages);
                me.inside.start();
                me.loader.hide();
            }(window.jQuery, NameSpace('inside.Inside'), NameSpace('inside.object3D.PointOfInterest')));

            gtmCit.initVue360($('.view-360'));
            $('.view-360').on('mousedown touchstart', function() {

                if ($(this).hasClass('init')) {
                    $(this).removeClass('init');
                }

            })
        },
        /* Update with current REF */
        setRef: function (item, index) {
            //var test = $('.add2selection').attr('data-ref', item._refs[index]);
        },
        /* Set arrows */
        setArrows: function () {
            var me = this,
                    el = me._element,
                    $el = $(el),
                    sliderWidth = me._slider.parent().width(),
                    contentWidth = $el.width(),
                    newRight = Math.round((sliderWidth - contentWidth) / 2);

            $el.find('.bx-prev,.bx-next').css({
                right: newRight
            });

        }

    };

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName, new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);


var colors = {
    build: function () {
        return;
        /* Vars */
        var me = this,
                $me = $(me),
                $bg = $me.find('figure .lazy'),
                $colors = $me.find('ul a');

        me.current = null;

        /* Hide color picker if there's one color */
        if (1 == $colors.length)
            $me.find('ul').hide();

        /* Append tools if more than twelve colors */
        if (12 < $colors.length) {
            $me.find('ul').addClass('plus').prepend('<li class="p">&lt;</li>').append('<li class="n">&gt;</li>');
            /* Prev */
            $me.find('.p').click(function () {
                var $c = $(this).parent().find('li').not('.p,.n');
                $c.last().insertBefore($c.first());
            });
            /* Next */
            $me.find('.n').click(function () {
                var $c = $(this).parent().find('li').not('.p,.n');
                $c.first().insertAfter($c.last());
            });
        }

        /* Delay init */
        if ($bg.length) {
            $bg.load(function () {
                if ($(this).hasClass('lazy'))
                    return false;
                colors.change.call(me, null, $colors.get(0));

            });
        } else {
            colors.change.call(me, null, $colors.get(0));
        }

        /* Events */
        $colors.click(function (e) {
            colors.change.call(me, e, this);

        });

    },
    change: function (e, link) {

        /* Vars */
        var me = this;

        if (e)
            e.preventDefault();

        /* First click create image */
        if (!link.img) {

            var src = link.getAttribute('href');

            link.img = document.createElement('img');
            link.img.className = 'car';
            link.img.src = src;
            $(link.img).load(function () {
                colors.show.call(me, link);
            });
            me.appendChild(link.img);

        } else {

            colors.show.call(me, link);

        }

    },
    show: function (link) {

        /* Vars */
        var me = this;

        if (me.current && link != me.current) {
            colors.hide.call(me, me.current);
        }
        $(link).parent().addClass('on');
        $(link.img).appendTo(me).fadeIn();
        me.current = link;


    },
    hide: function (link) {

        /* Vars */
        var me = this;

        $(link).parent().removeClass('on');
        $(link.img).delay(50).fadeOut();
        me.current = null;

    }
};


/**
 * ZONER
 * @updated 2014-03-27 Removed some things already handled by the CSS and let the browser use the
 * native link instead of some popup/window methods.
 * @type {Object}
 */
var zoner = {
    build: function () {
        var
                $this = $(this),
                $link = $this.find('a:first');

        if (this.built)
            return;
        this.built = true;

        if (ISTOUCH && $this.is('figure')) {
            $this
                    .data('tap1', false)
                    .on('click', function (e) {
                        // if click on link, let the browser do its thing
                        if (e.target && e.target.tagName === 'A')
                            return;

                        // if the first tap, don't go to the link but store the state
                        if (!$this.data('tap1')) {
                            e.preventDefault();
                            e.stopPropagation();
                            $this.data('tap1', true);

                            var $othercaption = $this.siblings().children('figcaption');
                            $othercaption.each(function (index) {
                                $(this).parent().data('tap1', false);
                            });
                        }
                        // if not the first tap, go to the link
                        else {
                            $link[0].click();
                            $this.data('tap1', false);
                        }
                    });
        } else if (!ISTOUCH && $link.length) {
            $this.on('click', function (e) {
                // on click on the element, go to the link
                if (e.target && e.target.tagName !== 'A' && $(e.target).parents('.layer').length < 1) {

                    $link[0].click();
                }
            });
        }
    }
};

/*-------------------- Drag'n Change / Range input --------------------*/
/* modif RM#1717 le 26/11/2013 */
var dragnchange = {
    built: false,
    current: null,
    build: function () {

        /* Vars */
        var me = this,
                $me = $(me),
                tooltip = '';

        me._isRange = $me.hasClass('range');
        me.$mask = $me.find('figure:first-child,.bar');
        me.$figcaption = $me.find('figcaption');
        me.$modelFigure = $me.find('figure');
        me.$modelFig = $me.find('figure:last-child');
        me.$modelImg = me.$modelFig.find('img');
        me.$modelWidth = me.$modelImg.width();
        me.$modelHeight = me.$modelImg.height();

        me.$zone = $me.find('.zone');
        me.$zone.css({
            width: me.$modelWidth,
            height: me.$modelHeight
        });
        //me.$figcaption.css('display','none');

        // me.$modelFigure.find('img.lazy').lazyload({
        //      threshold:200,
        //            load : function()
        //            {
        //          me.$zone.css({ width:me.$modelImg.not('.lazy').width(), height:me.$modelImg.not('.lazy').height() });
        // //       me.$figcaption.css('display','block');
        //            }
        //       });

        $(window).resize(function () {
            setTimeout(function () {
                me.$zone.css({
                    width: me.$modelFig.width(),
                    height: $('.dragnchange').find('img').height()
                });
            }, 200);
        });



        if (me._isRange) {

            me._field = $me.find('input').get(0);
            me._field._from = parseFloat(me._field.getAttribute('data-from'));
            me._field._to = parseFloat(me._field.getAttribute('data-to'));
            me._field._step = parseFloat(me._field.getAttribute('data-step')) || 1;
            me._field._unit = me._field.getAttribute('data-unit') || '_';
            var values = me._field.getAttribute('data-values');
            me._values = (values) ? values.split('|') : null;
            tooltip = '<div class="value"></div>';
            var format = me._field.getAttribute('data-step') || '1';
            me._field._float = (-1 != format.indexOf('.')) ? format.length - format.indexOf('.') - 1 : 0;

            $(me._field).bind('change', function () {
                dragnchange.set(me);
            });

        }
        var handlerStyle = '';
        if ($(me).data('handler')) {
            handlerStyle = $(me).data('handler');
        }
        /* Append button and set events */
        me.$drag = $me.append('<div class="drag" style="' + handlerStyle + '">' + tooltip + '</div>').find('.drag');

        if (testMsGesture) {
            var gesture = new MSGesture();
            gesture.target = me.$drag[0];
            me.$drag.addClass('slider-no-touch-action');
        }
        me.$drag.bind(STARTEVENT, dragnchange.start);

        makeUnselectable(me);

        /* Overall end */
        if (!dragnchange.built) {
            dragnchange.built = true;
            me.$drag.bind(ENDEVENT, dragnchange.end);
        }

        /* Default value */
        if (me._isRange) {
            if (null != me._field.value && '' != me._field.value)
                dragnchange.set(me);
        }

    },
    start: function (e) {

        if (testMsGesture) {
            var gesture = new MSGesture();
            gesture.target = document.body;
            $(document.body).addClass('slider-no-touch-action');
        }
        /* Vars */
        var me = this.parentNode,
                e = e.originalEvent || e,
                pointer = (e.touches) ? e.touches[0] : e;

        $(document.body).bind(MOVEEVENT, dragnchange.move);

        /* Set */
        dragnchange.xref = pointer.pageX;
        dragnchange.current = me;

    },
    move: function (e) {
        if (!dragnchange.current)
            return;

        var me = dragnchange.current,
                drag = me.$drag.get(0),
                e = e.originalEvent || e,
                pointer = (e.touches) ? e.touches[0] : e,
                pos = ((pointer.pageX - $(me).offset().left) / me.offsetWidth) * 100,
                bounds = {
                    lower: (me._isRange) ? 0 : 5,
                    upper: (me._isRange) ? 100 : 95,
                };

        /* Limits */
        if (bounds.lower > pos) {
            pos = bounds.lower;
        }
        if (bounds.upper < pos) {
            pos = bounds.upper;
        }

        /* Update */
        me.$drag.css({
            left: pos + '%'
        });
        me.$mask.css({
            right: (100 - pos) + '%'
        });

        /* If range update field */
        if (me._isRange)
            dragnchange.set(me, pos);

        e.preventDefault();
        e.stopPropagation();

    },
    end: function () {
        var me = dragnchange.current;
        if (me && me._field)
            $(me._field).change();
        //console.log(me)

        $(document.body).unbind(MOVEEVENT, dragnchange.move).removeClass('slider-no-touch-action');

        /* Reset */
        dragnchange.current = null;
        var gtmAttr = $.parseJSON($(me).attr('data-gtm-js')),
            oThis = this,
            drag = me.$drag.get(0),
                limit = Math.floor(parseInt($(me).width() / 2));
        oThis.data = gtmAttr[0];

        //console.log(limit);
        if (oThis.data == undefined) {
            return false;
        } else {
            //console.log('in dragnchange')
            oThis.sliced = gtmAttr[0].split('|');
            //this.sliced[1] = "toggle";
            oThis.$root = me;


            if (parseInt($(drag).css('left')) < limit) {
                oThis.imageName = oThis.sliced[3];
                oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.imageName, '|', '|', oThis.sliced[5], '|', oThis.sliced[6]);

                $(document).trigger({
                    type: 'gtm',
                    dataGtm: oThis.data
                });

            } else {
                oThis.eventName = oThis.sliced[4]
                oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.imageName, '|', '|', oThis.sliced[5], '|', oThis.sliced[6]);

                $(document).trigger({
                    type: 'gtm',
                    dataGtm: oThis.data
                });

            }
        }

    },
    set: function (me, newPos) {
        http://cppv2-dev-media.interakting.com/image/65/7/1500x646-concept-car-citroen-revolte-heritage-transgressif.2657.76.jpg

                /* Vars */
                var min = me._field._from,
                max = me._field._to,
                step = me._field._step,
                delta = max - min,
                current = me._field.value,
                pos = newPos;

        /* Set default value */
        if (!newPos) {
            pos = Math.round(((current - min) / delta) * 100);
            me.$drag.css({
                left: pos + '%'
            });
            me.$mask.css({
                right: (100 - pos) + '%'
            });
        }

        var output = Math.round((Math.round((min + (pos / 100) * delta) / step) * step) * 10000) / 10000;

        /* If has change */
        if (current != output || !newPos) {
            me._field.value = output;

            /* Add "0" to keep float format */
            var str = output.toString(),
                    current = (-1 != str.indexOf('.')) ? str.length - str.indexOf('.') - 1 : 0;
            if (current < me._field._float) {
                for (var i = current; i < me._field._float; i++) {
                    if (0 === i)
                        output += '.';
                    output += '0';
                }
            }

            var value = (me._values) ? me._values[output] : me._field._unit.replace(/^(.*)_(.*)$/, '$1' + output + '$2');
            me.$drag.find('.value').html(value);
        }

        /* Value position */
        var $value = me.$drag.find('.value'),
                w = $value.outerWidth(),
                lp = Math.round($(me).find('.bar').width()),
                rp = Math.round($(me).width() - $(me).find('.bar').width() + 30),
                correction = -w / 2;

        /* Bounds */
        if (lp < w / 2)
            correction = -(w / 2) + ((w / 2) - lp);
        if (rp < w / 2)
            correction = -(w / 2) - ((w / 2) - rp);

        $value.css({
            marginLeft: correction
        });

    }
};

/*-------------------- Popit --------------------*/

/* Gallery viewer based on bxSlider */
var popInitCount = 0,
        popInit = function (pop) {
            /* Re open */
            var callback = (pop) ? function () {
                $.fancybox(pop);
            } : function () {
            };

            /* Functions */
            var tpl = $('#tplSneezy').html(),
                    slider = null,
                    sneezit = function (e, group) {
                        e.preventDefault();

                        var dom = $('#sneezy_' + group);
                        if (!dom.length) {
                            build.call(this, group);
                        } else {
                            show.call(dom, this._sneezy_index);
                        }

                    },
                    build = function (group) {
                        /* Gathering informations */
                        var items = []
                /* REMOVE THIS CONDITION TO PREVENT GROUPING VIDEO ON TH MEDIA WALL - XAVIER */

                        $(this._sneezy_group).each(function () {

                            if (this.getAttribute('data-video') !== null) {
                                items.push(this.getAttribute('data-video'));
                            } else {
                                items.push({
                                    "src": this.getAttribute('href'),
                                    "alt": $(this).find('img:first').attr('alt')
                                });
                            }
                        });

                        /* Compile HTML and datas with underscore */
                        var compiledTemplate = _.template(tpl, {
                            id: group,
                            items: items
                        });
                        $('body').append(compiledTemplate);

                        var $dom = $('#sneezy_' + group);

                        /* Add share actions to each item */
                        $('.shareable', $dom).each(shareable.build);

                        /* variable pour GTM */
                        var items = $dom.find('.inner').find('.item').not('.bx-clone');
                        var gtmCloser = function ($element) {
                            /* GTM closers sur Galerie Média*/
                            var close_logo = $element.find('.closer');
                            var close_text = $element.find('.popClose');
                            close_logo.attr('data-gtm', 'eventGTM|Showroom::' + page_vehicule_label + '::MediaGallery|Close::Logo|' + close_logo.text() + '||');
                            close_text.attr('data-gtm', 'eventGTM|Showroom::' + page_vehicule_label + '::MediaGallery|Close::TextButton|' + close_text.text() + '||');
                        }
                        var gtmArrow = function (currentIndex) {
                            /* GTM arrows sur Galerie Média*/
                            var left = $dom.find('a.bx-prev');
                            var right = $dom.find('a.bx-next');
                            var left_file = '';
                            if ($(items[(currentIndex - 1 + items.length) % items.length]).find('img').attr('src')) {
                                left_file = $(items[(currentIndex - 1 + items.length) % items.length]).find('img').attr('src').split('/').pop();
                            }
                            var right_file = '';
                            if ($(items[(currentIndex + 1) % items.length]).find('img').attr('src')) {
                                right_file = $(items[(currentIndex + 1) % items.length]).find('img').attr('src').split('/').pop();
                            }
                            left.attr('data-gtm', 'eventGTM|Showroom::' + page_vehicule_label + '::MediaGallery|Navigation::Arrow::left|' + left_file + '||');
                            right.attr('data-gtm', 'eventGTM|Showroom::' + page_vehicule_label + '::MediaGallery|Navigation::Arrow::right|' + right_file + '||');
                        }
                        var gtmPager = function () {
                            /* GTM pagers sur Galerie Média*/
                            $dom.find('.bx-pager .bx-pager-item').each(function () {
                                var pager_link = $(this).find('a');
                                var slide_index = pager_link.attr('data-slide-index');
								var current_file ='';
                                if($(items[slide_index]).find('img').attr('src')){
									var current_file = $(items[slide_index]).find('img').attr('src').split('/').pop();
									}
                                pager_link.attr('data-gtm', 'eventGTM|Showroom::' + page_vehicule_label + '::MediaGallery|Navigation::Pagers::' + slide_index + '|' + current_file + '||');
                            });
                        }

                        /* Init slider if multiple */
                        if (1 < items.length) {
                            slider = $dom.find('.inner').bxSlider({
                                startSlide: this._sneezy_index,
                                onSlideBefore: function ($slideElement, currentIndex) {
                                    new setImgDimensions($slideElement);

                                    gtmCloser($slideElement);
                                },
                                onSlideAfter: function (currentIndex, oldIndex) {
                                    /* force iframe rendering on Chrome */
                                    window.scrollBy(0, 1);
                                    window.scrollBy(0, -1);

                                    gtmArrow(oldIndex + 1);
                                },
                                onSliderLoad: function (currentIndex) {

                                    /* Once built show it! */
                                    show.call($dom, this._sneezy_index);

                                    new setImgDimensions($dom.find('.item').eq(currentIndex + 1));
                                }
                            });
                            gtmCloser($(items[this._sneezy_index]));
                            gtmArrow(this._sneezy_index);
                            gtmPager();
                        } else {
                            /* Once built show it! */
                            show.call($dom, this._sneezy_index);
                        }

                        /* Events */
                        $dom.find('img').load(setToolsPosition).removeClass('lazy');
                        $dom.find('video').bind('play', setToolsPosition);
                        $dom.find('.closer, .popClose').click(function () {
                            hide.call($dom);
                        });
                        $dom.find('.item').click(function (e) {
                            if (e.target != this)
                                return;
                            hide.call($dom);
                        });


                    },
                    show = function () {
                        /* Force close fancybox */
                        $.fancybox.close();

                        var $me = this;
                        $me.stop(true, false).animate({
                            opacity: 1
                        }, 250);
                        setToolsPosition();
                        $('body').addClass('sneezies-lock');

                        $('video:not(.vjs-tech)', $me).each(function (ind, el) {
                            $(el).addClass('video-js vjs-default-skin');
                            if ($(el).parents().hasClass('intersticiel-content')) {
                                var paramAuto = true;
                            } else {
                                el.removeAttribute('autoplay');
                                var paramAuto = false;
                            }
                            videojs(el, {
                                height: $('.col:first', $me).height(),
                                width: $('.col:first', $me).width(),
                                autoplay: paramAuto,
                                controls: true
                            }, function () {
                                setToolsPosition();
                            });
                            gtmCit.initVideo($(el));
                        });
                    },
                    hide = function () {

                        var $me = this;
                        $me.stop(true, false).fadeOut(250, function () {
                            $me.remove();
                        });
                        $('body').removeClass('sneezies-lock');
                        callback();

                    },
                    setImgDimensions = function (slide) {
                        var isiPad = /ipad/i.test(navigator.userAgent.toLowerCase()),
                                img = $(slide).find('img');

                        $(img).attr('style', '');

                        var imgH = $(img).height(),
                                imgW = $(img).width();


                        //console.log("imgH : " + imgH + " - imgW : " + imgW);

                        if ((($(window).height() * 85) / 100) < imgH) {
                            //console.log('smaller screen')
                            var newImgH = parseInt(($(window).height() * 85) / 100);
                        } else {
                            var newImgH = imgH;
                        }
                        var newImgW = Math.ceil((imgW * newImgH) / imgH);

                        //console.log("newImgH : " + newImgH + " - newImgW : " + newImgW);


                        $(img).height(newImgH);
                        $(img).width(newImgW);

                        setToolsPosition();

                        $(slide).find('.roll').outerWidth(newImgW);
                        $(slide).find('.roll').css('margin', '0 auto');
                        $(slide).find('.roll').css('display', 'block');

                        if ($(slide).hasClass('videoTpl')) {
                            $(slide).find('.closer').css('margin-left', parseInt(($('.content-video').width() / 2) - 20));
                        } else {
                            $(slide).find('.closer').css('margin-left', parseInt((newImgW / 2) - 20));
                        }


                    },
                    /* setToolsPosition */
                    setToolsPosition = function () {

                        $('.sneezies:visible').each(function () {
                            var $me = $(this);

                            $me.find('.content').each(function () {
                                var $closer = $(this).parent().find('.closer'),
                                        top = this.offsetTop + parseInt($(this).css('paddingTop')),
                                        bottom = this.offsetTop - parseInt($(this).css('paddingTop')) + this.offsetHeight,
                                        left = (this.offsetWidth / 2) - parseInt($(this).css('paddingRight')) - $closer.width() / 2;
                                $closer.css({
                                    top: top,
                                    marginLeft: left
                                });
                                $(this).parent().find('.popClose').css({
                                    top: bottom
                                });
                            });
                        });

                    },
                    /**
                     * Reset tap and active status in ISTOUCH mode
                     */
                    resetTap = function () {
                        $('.shareable.active')
                                .removeClass('active')
                                .find('.active')
                                .removeClass('.active')
                                .data('tap', false);
                    };

            /* Find and store sneezy items by group */
            if (!window._sneezies)
                window._sneezies = {};

            $('[data-sneezy]').each(function (i) {

                popInitCount++;

                /* One build per DOM */
                if (this._sneezy_group)
                    return;

                /* Check if has group and if group already created */
                var group = this.getAttribute('data-sneezy') || 'sneezy' + popInitCount + i;

                /* Exist? */
                if (!window._sneezies[group]) {
                    window._sneezies[group] = [];
                }
                ;

                /* Store and associate */
                this._sneezy_index = window._sneezies[group].length;
                window._sneezies[group].push(this);
                this._sneezy_group = window._sneezies[group];

                // Does this need double-tap ?
                if ($(this).parents('.tiles, .gallery').length) {
                    this._doubletap = true;
                }

                /* Events */
                $(this).click(function (e) {
                    if (ISTOUCH && !$(this).data('tap') && this._doubletap) {
                        resetTap();

                        $(this)
                                .data('tap', true)
                                .addClass('active')
                                .parents('.shareable:first')
                                .addClass('active');

                        e.preventDefault();
                    } else {
                        $(this)
                                .data('tap', false)
                                .removeClass('active');

                        resetTap();

                        sneezit.call(this, e, group);
                    }
                });

            });

            /* Overall events */
            $(window).unbind('resize', setImgDimensions);
            $(window).resize(function () {
                //console.log(slider.getCurrentSlide())
                if ($('.sneezies').find('.inner .item').length > 0) {
                    new setImgDimensions($('.sneezies').find('.inner .item').eq(slider.getCurrentSlide() + 1))
                }
            });

        },
        promptPop = function (html, callback) {
            var viewport;
            $.fancybox({
                content: html,
                fitToView: false,
                minWidth: 200,
                maxWidth: 450,
                padding: 40,
                wrapCSS: 'prompt-wrap',
                helpers: {
                    overlay: {
                        css: {
                            'background': 'rgba(255,255,255,0.1)'
                        }
                    }
                },
                beforeShow: function () {

                    /* Tooltip */
                    this.inner.click(function () {
                        tooltip.close();
                    }).find('.tooltip,.texttip').each(tooltip.build);

                    /* jScrollPane */
                    this.inner.find('.scroll').jScrollPane({
                        autoReinitialise: true,
                        autoReinitialiseDelay: 10,
                        verticalGutter: 20
                    }).bind('mousewheel', function (e) {
                        e.preventDefault();
                    });

                    // If is a touch device, block the viewport
                    if (ISTOUCH) {


                        viewport = $('meta[name="viewport"]').attr('content');
                        $('meta[name="viewport"]').attr('content', 'width=device-width,user-scalable=no,minimum-scale=1.0,maximum-scale=1.0');
                    }

                    if (callback)
                        callback.call(this.inner);
                },
                afterShow: function () {
                    // If is a touch device, reset the viewport as it was
                    // if (ISTOUCH) {
                    //  $('meta[name="viewport"]').attr('content', viewport);
                    // }
                }
            });
        },
        simplePopInit = function () {

            $('.simplepop').unbind('click', simplePop).click(simplePop);

        },
        simplePop = function () {

            var html = $('#' + this.getAttribute('href').substr(1)).html();

            if (!html || '' == html)
                return;

            $.fancybox({
                content: html,
                fitToView: false,
                minWidth: 200,
                maxWidth: 450,
                padding: 40,
                beforeShow: function () {

                    /* Tooltip */
                    this.inner.click(function () {
                        tooltip.close();
                    }).find('.tooltip,.texttip').each(tooltip.build);

                    /* jScrollPane */
                    this.inner.find('.scroll').jScrollPane({
                        autoReinitialise: true,
                        autoReinitialiseDelay: 10,
                        verticalGutter: 20
                    }).bind('mousewheel', function (e) {
                        e.preventDefault();
                    });

                }
            });

        };



/*-------------------- Folder --------------------*/

var folder = {
    set: false,
    current: null,
    groups: {},
    progress: false,
    build: function () {

        /* Vars */
        var me = this,
                attr = me.getAttribute('href') || $(me).find('a').attr('href');
        contentID = (attr) ? attr.substr(1) : null;

        /* Store content jQuery DOM */
        me.$content = (contentID) ? $('#' + contentID + ', *[data-folder~="' + contentID + '"]') : $(me).next();
        if (!me.$content.get(0))
            return;

        me._isRow = ('tr' == me.$content.get(0).tagName.toLowerCase());
        me._$zone = $(me).parents('[data-overall]');

        // if (me.$content.parent().parent().hasClass("togglebloc")) {
        //  me.$content.parent().parent();
        // }

        /* Default hidden */
        if (!$(me).hasClass('open')) {
            me.$content.hide();
        }

        /* Events */
        me.$content.find('.inner').click(function (e) {
            e.stopPropagation();
        });
        me.$content.find('.closer, .popClose').click(function () {
            /* Toggle */
            folder.toggle.call(me);

            if ($('.tools .on').length) {

                if ($("div[id*='deployable_']:hidden").length) {
                    $('.tools a.on').removeClass('on');
                }

            }

            if ($('.clsrecapitulatifmodele .open').length) {
                if ($("div[id*='deployable_']:hidden").length) {
                    $('.tools a.on').removeClass('on');
                }
            }

        });

        /* Overall closer */
        if (!folder.set) {
            folder.set = true;
            $('.overlay, .layer, body .container > header').bind('click', function () {
                folder.current && folder.toggle.call(folder.current);
            });
        }

        /* If is inside a slider */
        var inSlide = $(me).parents('.slider').find('.row').get(0);
        if (inSlide && inSlide._slider)
            inSlide._slider.redrawSlider();


        $(me).click(function (e) {
            var tools = $(e.currentTarget).parents('.tools');
            var recap = $(e.currentTarget).parents('.clsrecapitulatifmodele');
            var compa = $(e.currentTarget).parents('.clscomparateur'),
                    isSticky = compa.find('.listick'),
                    stickyH = 0;
            if (isSticky.length > 0) {
                stickyH = $(isSticky).height()
            }

            var linkhref;
            if (e.currentTarget.tagName === 'A') {
                offset = $(e.currentTarget).offset();

                window.scrollTo(0, offset.top - 20 - stickyH);
            } else if ($(e.currentTarget).find('a:first').length) {
                linkhref = $(e.currentTarget).find('a:first')[0].hash;
            }

            if (compa.length > 0 || tools.length > 0 || recap.length > 0 && linkhref) {
                var currentTab = $(linkhref + ' .currentTab');


                if ($(me).parents("div[id*='deployable_']").length <= 0) {
                    $("div[id*='deployable_']").hide();
                    $("div[id*='deployable_']").removeClass('currentTab');
                    console.log('DEPLOYABLE')
                } else if ($(me).parents("div[id*='deployable_']").length > 0) {
                    var parent = $(me).parents("div[id*='deployable_']").attr('id');
                    $("div[id*='deployable_']").not('#' + parent + '').hide();
                    $("div[id*='deployable_']").not('#' + parent + '').removeClass('currentTab');
                }

                $('' + linkhref + '').addClass('currentTab');

                $(currentTab).show();
                setTimeout(function () {
                    if ($('.currentTab').is(":visible")) {
                        if ($(me).parents("div[id*='deployable_']").length <= 0) {

                            var offsetcurrenttab = $('.currentTab').offset().top - 40;
                            $('html, body').animate({
                                scrollTop: offsetcurrenttab
                            }, 300);
                        } else if ($(me).parents("div[id*='deployable_']").length > 0) {

                            var offsetcurrenttab = $('' + linkhref + '').offset().top - 500;
                            $('html, body').animate({
                                scrollTop: offsetcurrenttab
                            }, 300);
                        }
                    }
                }, 300);
            } else if (linkhref) {
                setTimeout(function () {
                    var offsetcurrenttab = $(linkhref).offset().top - 40;
                    $('html, body').animate({
                        scrollTop: offsetcurrenttab
                    }, 300);
                }, 300);
            }

            /* Prevent Hash change */
            e.preventDefault();
            e.stopPropagation();
            /* One at once */
            if (folder.progress)
                return;

            /* Vars */
            var link = this,
                    group = link.getAttribute('data-group');


            /* Check if one already open */
            if (group && folder.groups[group] && folder.groups[group] != link) {
                /* Close current */
                folder.toggle.call(folder.groups[group], function () {
                    /* And then open the new one */
                    folder.toggle.call(link);
                });
            } else {
                /* Toggle */
                folder.toggle.call(link);
            }
        });



    },
    toggle: function (callback) {
        if ($(this).hasClass('prevent'))
            return;
        folder.progress = true;
        /* Vars */
        var me = this,
                $me = $(me),
                holder = $me.find('a span') || $me,
                group = me.getAttribute('data-group');
        overlay = me.getAttribute('data-overlay');
        speed = me.getAttribute('data-folder-speed') || 'slow',
                closing = $me.hasClass('open'),
                toggleText = function () {
                    var toggle = me.getAttribute('data-toggle');
                    if (toggle) {
                        me.setAttribute('data-toggle', holder.html());
                        holder.html(toggle);
                    }
                };

        /* reload slider if hidden by default */
        var $rows = me.$content.find('.row').add(me.$content.filter('.row'));

        var getSlider = $(me).find('.slider .row');
        getSlider.trigger('redrawSlider');
        sync.set();

        $rows.each(function () {
            if (this._slider)
                this._slider.reloadSlider();
        });

        /* Open / Close */
        /* $me.toggleClass('open'); */
        if (!closing) {
            $me.addClass('open');
            toggleText();
            $me.trigger('openToggle');
        }
        ;
        if (overlay && !closing) {
            $('.overlay').stop(true, false).fadeIn();
            $('body').addClass('overed');
        }
        ;

        if (me._$zone.length) {
            me._$zone.find('.overall').removeClass('active');
        }
        ;

        var ending = function () {
            /* Set / Reset opened */
            if (group) {
                if (closing) {
                    $('body').removeClass('overed');
                    if (overlay && closing) {
                        $('.overlay').stop(true, false).fadeOut();
                    }
                    ;
                    folder.groups[group] = null;
                    folder.current = null;
                } else {
                    folder.groups[group] = me;
                    folder.current = me;
                }
                ;
            }
            ;

            if (closing) {
                $me.removeClass('open');
                $me.trigger('closeToggle');
                me._open = false;
                toggleText();
            } else {
                /* Lazy load */
                var $lazy = me.$content.find('img.lazy');
                lazy.load($lazy);
                if ($('.foldbyrow .slider').length < 1) {
                    sync.set();
                } else {
                    tooltip.timer = setTimeout(function () {
                        $('.foldbyrow .slider .row').trigger('redrawSlider');
                    }, 500);
                }

                //call metode from demo.js namespace Cit
                Cit._checkLocatorMap(me.$content);

                var $gmap = me.$content.find('.map-canvas');
                //console.log($gmap.length)
                if ($gmap.length > 0) {
                    $('body').trigger('gmapBuild')
                    //$gmap.gLocator.init();
                }
            }
            ;

            folder.progress = false;
            if (callback)
                callback();

        };

        /* Toggle action */
        if (!$me.hasClass('move')) {
            // @todo Toggle() is very slow
            me.$content.toggle();
            ending();
            if (me.$content.parent().hasClass('layer')) {
                me.$content.toggleClass('open');
            }

        } else {
            /* Scroll on open */
            if (!me._open) {

                /* Toggle action */
                me._open = true;
                me.$content.show();
                ending();

                /* animation */
                var coord = $me.offset().top - 100;
                $('html,body').stop(true, false).animate({
                    scrollTop: coord
                }, 'slow');


            } else {

                /* animation */
                var coord = $(window).scrollTop() - me.$content.height() - 25;
                me._open = false;
                $('html,body').stop(true, false).animate({
                    scrollTop: coord
                }, 'slow', function () {
                    /* Toggle action */
                    me.$content.hide();
                    ending();
                });

            }
            ;
        }
        ;

        /* If is inside a slider */
        var inSlide = $(me).parents('.slider').find('.row').get(0);
        if (inSlide && inSlide._slider)
            inSlide._slider.redrawSlider();

        /* CPW-3887 If is row of 4 cols */
        var isRowOf4 = $(me).parents().find('.slider').find('.row.of4');
        if (isRowOf4) {
            isRowOf4.each(function(){
                if($(this).get(0)._slider) $(this).get(0)._slider.reloadSlider();
            });
        }

        me.$content.trigger('content_toggle',{
            type: 'currentTab',
            isOpen: (me.$content.css('display') === 'none') ? false : true
        });

        $('.togbody .addmore').on('click', function (e) {
            var linkhref = $(e.currentTarget).children('a').attr('href');
            $('' + linkhref + '').toggleClass('open');
            var toggleOpen = $(e.currentTarget).attr('data-toggle-open');
            var toggleClose = $(e.currentTarget).attr('data-toggle-close');
            if ($('' + linkhref + '.parentActif').is(":visible")) {
                $(this).children('a').text(toggleClose);
            } else {
                $(this).children('a').text(toggleOpen);
            }
        });

        $('.clslanguette .addmore').on('click', function (e) {
            var linkhref = $(e.currentTarget).children('a').attr('href');
            $('' + linkhref + '').toggleClass('open');
            var toggleOpen = $(e.currentTarget).attr('data-toggle-open');
            var toggleClose = $(e.currentTarget).attr('data-toggle-close');
            if ($('' + linkhref + '.parentActif').is(":visible")) {
                $(this).find('a span').text(toggleClose);
            } else {
                $(this).find('a span').text(toggleOpen);
            }
        });

    }
};

/* ADDITIONAL FUNCTION FOR URLs THAT HAVE TOKENS OPENING A FOLDER */
$(document).on('ready', function (e) {
    var url = window.location.href;
    if (url.indexOf('?') != -1) {

        var token = url.substring(url.indexOf('?') + 1, url.indexOf('_')),
            id = url.substring(url.indexOf('=')+1),
            selector = token+'_'+id;
        $('.folder').each(function () {
            var a = $(this).find('a');
            if (a.length > 0) {
                var aHref= $(a).attr('href'),
                    aId = aHref.substring(aHref.indexOf('#')+1);

                if (aId == selector) {
                    //$(this).trigger('click');
                    console.log("trigger this A" + selector);
                    console.log(a);
                    console.log(aId);
					
					var sSelectorForm = selector.split('_');
					var iIdForm = sSelectorForm[1];

                    $(this).trigger('click');
                    chargeIframeDeploy('FormulaireDeploy' + iIdForm);
                }
            }
        });
    }
});

var overall = {
    build: function () {

        /* Vars */
        var me = this,
                $me = $(me),
                isOpen = $me.hasClass('openall');

        me._open = isOpen;
        me._$zone = $me.parents('[data-overall]');

        /* Events */
        $me.click(overall.manage);

    },
    manage: function () {

        /* Vars */
        var me = this;

        /* Manage links */
        me._$zone.find('.overall').removeClass('active');
        $(me).addClass('active');

        /* Open / Close */
        me._$zone.find('.folder').not('.prevent').each(function () {
            if (me._open) {
                $(this).addClass('open');
                $(this).trigger('openToggle');
                this.$content.show();
            } else {
                $(this).removeClass('open');
                $(this).trigger('closeToggle');
                this.$content.hide();
            }
            ;
        });

    }
};

var foldbyrow = {
    build: function () {

        /* Vars */
        var rowof = /.* ?of(\d+) ?.*/,
                colspan = /.* ?span(\d+) ?.*/,
                me = this,
                $me = $(me),
                css = $me.attr('class'),
                $folders = $me.find('> .folder'),
                css2 = $folders.attr('class'),
                row = (rowof.test(css)) ? css.replace(rowof, '$1') : 1,
                span = (colspan.test(css2)) ? css2.replace(colspan, '$1') : 1,
                count = parseInt(row / span);

        /* Position folder in the good row */
        $folders.each(function (i) {

            /* Vars */
            var link = this,
                    $link = $(link),
                    attr = link.getAttribute('href') || $(link).find('a').attr('href');
            contentID = (attr) ? attr.substr(1) : null,
                    target = Math.floor(i / count) * count + count - 1,
                    corrected = (target > $folders.length) ? $folders.length - 1 : target;


            if (contentID) {
                var $cont = $('#' + contentID);
                /* Alternative class */
                if (i % count >= count / 2)
                    $cont.addClass('alt');
                $folders.eq(corrected).after($cont);

                /* Events */
                $cont.append('<div class="closer"></div>').find('.closer').click(function () {
                    /* Toggle */
                    folder.toggle.call(link);
                });
            }
            ;
        });

    }
};

// Link My Car
var linkMyCar = function () {
    var $tactileName = $('input[name="tactileName"]'),
            $edgeModal = $('.edge-modal'),
            $secondStep = $("#eligibilite-form"),
            $notlinkedCar = $("#notlinkedCar"),
            $isOklinkedCar = $("#isOklinkedCar"),
            $verifyNumber = $("#verify-number"),
            $edgeNotice = $(".edge-notice"),
            $continued = $(".continued"),
            $inputVerifyNumber = $("input[name='verify-number']"),
            $modalContent = $('#edge-modal');

    /* CPW-3706. Old behavior, DOM has changed and new handlers are in applications.js
     if ($tactileName.length) {
     $tactileName.on("change", function() {
     var _this = $(this),
     _val = _this.val();
     
     if (_val) {
     if (_val == 1) {
     $secondStep.fadeIn("slow");
     $notlinkedCar.fadeOut("slow");
     };
     if (_val == 0) {
     $secondStep.fadeOut("slow");
     $notlinkedCar.fadeIn("slow");
     };
     };
     });
     };
     */
    if ($edgeModal.length) {
        var i = 0;
        $edgeModal.on("click", function (e) {
            var _this = $(this),
                    $offset = _this.offset();
            e.preventDefault();
            $modalContent.css({
                "top": $offset.top,
                "left": $offset.left - 5
            });
            $modalContent.fadeIn(300, function () {
                $(this).focus();
            });
        });
        $inputVerifyNumber.on("blur focus", function (e) {
            $modalContent.fadeOut("slow");
        });
        $inputVerifyNumber.on("keypress", function (e) {
            $modalContent.fadeOut("slow");
            if (i > 17)
                $edgeNotice.fadeIn("slow");
            i++
        });
    }
    ;
    if ($verifyNumber.length) {
        $verifyNumber.on("click", function (e) {
            var _this = $(this),
                    $offset = _this.offset();
            e.preventDefault();
            $isOklinkedCar.fadeIn("slow");
            $edgeNotice.fadeIn("slow");
        });
        $inputVerifyNumber.on("blur", function (e) {
            $modalContent.fadeOut("slow");
        });
    }
    ;
    if ($continued.length) {
        $continued.on("click", function (e) {
            var _this = $(this);
            $secondStep.find("input[type='text']").focus();
            $edgeNotice.fadeOut("slow");
        });
    }
    ;
}

// V2.4 - CPW-3498 // Eligibilit� Link My Car
linkMyCar();

var dropdownGroup = {
        built: function() {
            var me = this,
                $me = $(me);

            $me.find('.actions a[href=#step2]').click(function(e) {
                e.preventDefault();
                dropdownGroup.getResultField($me);
            });

            $me.find('.modify').click(function() {
                dropdownGroup.backToFields($(this))
            });

        },
        backToFields: function(el) {
        var fieldset = el.parents('fieldset');
        fieldset.find('.fields').slideDown();
        dropdownGroup.scrollPage(fieldset);
        $('#step2').addClass('disabled').next().hide();
    },
    // getResultField:function(root){
    //  var $form = root.find('form');
    //  $.ajax({
    //      data:$form.serialize(),
    //            type: 'post',
    //      url:$form.attr('action'),
    //      dataType:'html',
    //      success:function(response){
    //          dropdownGroup.htmlRender($form,response);
    //      }
    //  });
    // },
        htmlRender: function(form, response) {
            var $wrapper = form.find('.result-wrapper'),
                $fields = form.find('.fields');

            $wrapper.html(response);
            $fields.slideUp();
            $wrapper.next().addClass('open');
            dropdownGroup.goNext($fields);
        },
        goNext: function(target) {
            var next = target.parents('fieldset').next().find('h3');
            next.removeClass('disabled').next().show();
            dropdownGroup.scrollPage(target.parents('form'));
        },
        scrollPage: function(target) {
        var sticky = $('.stickyplaceholder .sticky');
        var stickyHeight = (sticky.hasClass('fixed')) ? sticky.height() : 0;
        $('html, body').animate({
            scrollTop: target.offset().top - stickyHeight - 20
        }, 'slow');
    }
}

/*-------------------- SelectZone --------------------*/

// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
;
(function ($, window, document, undefined) {

    // undefined is used here as the undefined global variable in ECMAScript 3 is
    // mutable (ie. it can be changed by someone else). undefined isn't really being
    // passed in so we can ensure the value of it is truly undefined. In ES5, undefined
    // can no longer be modified.

    // window and document are passed through as local variable rather than global
    // as this (slightly) quickens the resolution process and can be more efficiently
    // minified (especially when both are regularly referenced in your plugin).

    // Create the defaults once
    var pluginName = 'cSelector',
    /* Defaults */
            defaults = {
                /*field:null*/

                /* Callbacks */
                /*onLoad:function(){}*/

            };

    // The actual plugin constructor
    function Plugin(element, options) {

        // jQuery has an extend method which merges the contents of two or
        // more objects, storing the result in the first object. The first object
        // is generally empty as we don't want to alter the default options for
        // future instances of the plugin
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this._field = null;
        this._element = element;
        this.init();
    }
    ;

    function overall() {
        $('.languages,.select').removeClass('hover');
    }
    ;

    Plugin.prototype = {
        /* Initalisation */
        init: function () {
            var me = this,
                    field = $(me._element).parents('.selectZone').prev().get(0);
            me._field = field,
                    $me_field = $(me._field),
                    $me_element = $(me._element),
                    togOver = 0;
            $items = $me_element.find('span, a');

            if (!$me_element.find('.on,.off').length) {
                $items.addClass('on');
            }

            $items.each(function () {
                if (field) {
                    me._field = field;
                    if ($(this).hasClass('on')) {
                        $me_field.val(this.getAttribute('data-value')).change();
                    }
                    ;
                }
                ;

            });

            $('body').on('click touch', function () {
                if (togOver == 1) {
                    overall();
                    togOver = 0;
                }
            });

            $me_element.on('click', 'a, .on', function (e) {
                me.choose(e);
                e.stopPropagation();
                togOver = 1;

            });
            /* Events */

            //$('body').unbind('click',overall).bind('click',overall);
            overall();

            /* Look for updates */
            $me_field.on('change', function () {
                me.active(this.value);
            });
            $me_element.on('append', function () {
                me.update('append');
            });
            $me_element.on('html', function () {
                me.update('html');
            });


        },
        /* Updated list */
        update: function (type) {
            var me = this;

            // $(me._element).find('span, a').each(function(){
            //  this._field = me._field;
            // }).unbind('click',me.choose).click(me.choose);

            /* If active has been stripped */
            var active = $(me._element).find('.on').attr('data-value');
            $(me._field).val(active);

        },
        /* Click action */
        choose: function (e) {

            //e.stopPropagation();
            var $parents = $(e.currentTarget).parents('.languages,.select');

            if (this._field && this._field.disabled)
                return;

            $selectZones.not($parents).removeClass('hover');
            $parents.toggleClass('hover').find('a.on').removeClass('on');

            $(e.target).addClass('on');

            /* update field if has */
            if (this._field)
                $(this._field).val(e.currentTarget.getAttribute('data-value')).change();
        },
        /* Active */
        active: function (value) {
            var me = this;
            //$(me._element).find('span,a').removeClass('on');
            $(me._element).find('[data-value="' + value + '"]').addClass('on');
        }

    };

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName, new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);


/* Custom events */
(function ($) {
    var origAppend = $.fn.append;
    $.fn.append = function () {
        var orig = origAppend.apply(this, arguments);
        this.trigger('append');
        return orig;
    };
})(jQuery);
(function ($) {
    var origHtml = $.fn.html;
    $.fn.html = function () {
        var orig = origHtml.apply(this, arguments);
        this.trigger('html');
        return orig;
    };
})(jQuery);


//check if click event firing twice on same position.
var lastTapTime;

function isJQMGhostClick(event) {
    var currTapTime = new Date().getTime();

    if (lastTapTime == null || currTapTime > (lastTapTime + 800)) {
        lastTapTime = currTapTime;
        return false;
    } else {
        return true;
    }
}

var tooltip = {
    set: false,
    timer: null,
    $opener: null,
    $opened: null,
    build: function () {

        /* Vars */
        var me = this,
                $me = $(me),
                href = $me.attr('href'),
                isPop = $me.hasClass('pop');

        //me.timer = null;
        me._$layer = $(href);


        console.log(me);
        console.log('isPop : ' + isPop);


        if (!isPop) {

            $me.click(function (e) {
                e.preventDefault();
                e.stopPropagation();
            });

            /* Events */
            if (ISTOUCH) {
                me._$closer = $('<div class="closer" />');
                me._$layer.append(me._$closer);
                $me.on('touchstart', tooltip.open);
            } else {

                $me.mouseenter(tooltip.open).mouseleave(function () {
                    console.log(me);
                    clearTimeout(tooltip.timer);
                    tooltip.timer = setTimeout(function () {
                        $me.removeClass('hover');
                        tooltip.close();
                    }, 1);
                });

            }

            if (ISTOUCH != 1) {
                $me.mouseenter(tooltip.open).mouseleave(function () {
                    clearTimeout(tooltip.timer);
                    tooltip.timer = setTimeout(function () {
                        $me.removeClass('hover');
                        tooltip.close();
                    }, 1);
                });
            }
        } else {

            $me.click(function (e) {
                e.preventDefault();
                e.stopPropagation();
                promptPop(this._$layer.html());
            });

        }


        /* Overall closer & resize*/
        if (!tooltip.set) {
            tooltip.set = true;
            if (isPop) {
                $(document).click(function (event) {
                    if (tooltip.$opened && $(event.relatedTarget).parents('.clone:first').data('id') !== tooltip.$opened.attr('id')) {
                        tooltip.close();
                    }
                });
            }

            $(window).resize(function () {
                if (null != tooltip.$opener && null != tooltip.$opened)
                    tooltip.position(tooltip.$opener, tooltip.$opened);
            });
        }
    },
    open: function (e) {

        e.stopPropagation();
        e.preventDefault();


        console.log(me);
        console.log('OPEN');


        /* Vars */
        var me = this,
                $me = $(me);

        if (tooltip.$opened == me._$layer)
            return;
        if (tooltip.$opened)
            tooltip.close();

        $me.addClass('hover');

        tooltip.$opener = $me;
        tooltip.$opened = me._$layer;

        /* Set layer position */
        tooltip.position($me, me._$layer, e);
    },
    position: function ($link, $layer, e) {

        var root = $layer.parent().hasClass('js'),
                xref = Math.floor($link.offset().left + $link.width()),
                yref = Math.floor($link.offset().top),
                xmax = Math.floor($('.body').width() - $layer.width());


        if (!root) {
            tooltip.$opened = $layer
                    .clone(true, true)
                    .attr('data-id', $layer.attr('id'))
                    .removeAttr('id')
                    .addClass('clone');
            $(document.body).append(tooltip.$opened);
        }

        /* Bounds */
        if (xref > xmax) {
            tooltip.$opened.addClass('boundright');
        } else {
            tooltip.$opened.removeClass('boundright');
        }

        lazyImgs = tooltip.$opened.find('.lazy');
        if (lazyImgs.length) {
            lazy.load(lazyImgs, function (a) {
                lazyImgs.removeClass('lazy');
                tooltip.$opened.css({
                    top: yref,
                    left: xref
                });
            });
        } else {
            tooltip.$opened.css({
                top: yref,
                left: xref
            });
        }

        tooltip.$opened.mouseenter(function () {
            clearTimeout(tooltip.timer);
        }).mouseleave(tooltip.close);

        if (ISTOUCH) {
            tooltip.$opened.on('click touchstart', '.closer', function () {
                tooltip.close();
            });
        }

        /* jScrollPane */
        tooltip.$opened.find('.scroll').jScrollPane({
            autoReinitialise: true,
            autoReinitialiseDelay: 10,
            verticalGutter: 20
        }).bind('mousewheel', function (e) {
            e.preventDefault();
        });
    },
    close: function () {
        if (!tooltip.$opened)
            return;

        tooltip.$opened.off();
        if (tooltip.$opened.hasClass('clone')) {
            tooltip.$opened.remove();
            tooltip.$opener.removeClass('hover');
        } else {
            tooltip.$opened.attr('style', '');
            tooltip.$opener.removeClass('hover');
        }

        tooltip.$opener = null;
        tooltip.$opened = null;
    }
};

/*-------------------- Shareable --------------------*/

var shareable = {
    tpl: $('#shareTpl').html(),
    build: function () {

        /* Vars */
        var me = this,
                $me = $(me),
                url = $me.find('a').attr('href'),
                title = $me.find('img').attr('alt'),
                mediashare = $me.find('img').attr('data-mediashare'),
                noshare = $me.find('a').hasClass('video'),
                noMediaSharer = $me.find('a').hasClass('noMediaSharer');

        /* Append tools */
        me._$tools = $me.find('span.roll').length ? $me.find('span.roll') : $me.prepend('<span class="roll"></span>').find('.roll');
        me._$tools = $me.find('span.noroll').length?$me.find('span.roll').remove():$me.prepend('<span class="roll"></span>').find('.roll');

        //  Dynamisation MurMedia
        if ($me.parents('.showroom.clsmurmedia')) {

            var hvrLpStyles = $(this).attr('data-dgloupe');
            var hvrStyles = $(this).attr('data-hover');

            $me.find('span.roll').each(function () {
                $(this).attr('style', hvrStyles);
                //console.log('je suis la')
                $(this).on('mouseenter', function (e) {
                    e.stopPropagation();
                    $(this).attr('style', hvrStyles);
                });
            });

            $me.find('.popit').append('<div class="backloupe" />').find('.backloupe').each(function () {
                $(this).attr('style', hvrLpStyles);
                //console.log('je suis la')
                $(this).on('mouseenter', function (e) {
                    e.stopPropagation();
                    $(this).attr('style', hvrLpStyles);
                });
            });
        }

        if (url && title && !noshare && !noMediaSharer) {

            if (mediashare) {
                var compiledTemplate = _.template(shareable.tpl, {
                    url: mediashare,
                    title: title
                });
                me._$tools.append(compiledTemplate);
            } else {
                var compiledTemplate = _.template(shareable.tpl, {
                    url: url,
                    title: title
                });
                me._$tools.append(compiledTemplate);
            }

            if (ReinitializeAddThis && typeof ReinitializeAddThis === 'function') {
                ReinitializeAddThis();
            }
        }
        ;

    }
};

/*-------------------- Feeder --------------------*/

var feeder = {
    build: function () {

        var me = this,
                $me = $(me),
            $ul = $me.find('ul'),
                feed = me.getAttribute('data-feed'),
                headerTpl = $me.find('script.headerTpl').html(),
                loopTpl = $me.find('script.loopTpl').html();
        this.loader = new Loader();
        if (!feed || !loopTpl)
            return;


        this.loader.show();
        $.ajax({
            url: feed,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                /*
                 console.log('SocialNetFeeds - Youtube - Feed is ok - templating datas');
                 HACK //
                 var response = [{"kind":"youtube#activity", "etag":"\"iDqJ1j7zKs4x3o3ZsFlBOwgWAHU\/y81JC5KJQcwctkoZQbc-wjy6RXE\"", "id":"VTE0MzI5MTIwNzUxMzk5MjAyNDAyODAzMzY=", "snippet":{"publishedAt":"2015-05-29T15:07:55.000Z", "channelId":"UCLf9Uuyli2HDBv2Jrsm_d-Q", "title":"Citro\u00ebn C4 Cactus - Avis clients", "description":"",  "thumbnails": { "default": {"url":"https:\/\/i.ytimg.com\/vi\/4kPDVaCzLHE\/default.jpg","width":120,"height":90},"medium":{"url":"https:\/\/i.ytimg.com\/vi\/4kPDVaCzLHE\/mqdefault.jpg","width":320,"height":180},"high":{"url":"https:\/\/i.ytimg.com\/vi\/4kPDVaCzLHE\/hqdefault.jpg","width":480,"height":360},"standard":{"url":"https:\/\/i.ytimg.com\/vi\/4kPDVaCzLHE\/sddefault.jpg","width":640,"height":480},"maxres":{"url":"https:\/\/i.ytimg.com\/vi\/4kPDVaCzLHE\/maxresdefault.jpg","width":1280,"height":720}},"channelTitle":"Citro\u00ebn France","type":"upload","groupId":"VTE0MzI5MTIwNzUxMzk5MjAyNDAyODAzMzY="},"contentDetails":{"upload":{"videoId":"4kPDVaCzLHE"}}},{"kind":"youtube#activity","etag":"\"iDqJ1j7zKs4x3o3ZsFlBOwgWAHU\/Zy1Adng6VbFv0UbaFhBDawI1MdQ\"","id":"QTE0MzI4MTMzNDcxMzk5MjM1NDA5MDUxNjg=","snippet":{"publishedAt":"2015-05-28T11:42:27.000Z","channelId":"UCLf9Uuyli2HDBv2Jrsm_d-Q","title":"Citro\u00ebn C4 Cactus - Avis clients","description":"","thumbnails":{"default":{"url":"https:\/\/i.ytimg.com\/vi\/4kPDVaCzLHE\/default.jpg","width":120,"height":90},"medium":{"url":"https:\/\/i.ytimg.com\/vi\/4kPDVaCzLHE\/mqdefault.jpg","width":320,"height":180},"high":{"url":"https:\/\/i.ytimg.com\/vi\/4kPDVaCzLHE\/hqdefault.jpg","width":480,"height":360},"standard":{"url":"https:\/\/i.ytimg.com\/vi\/4kPDVaCzLHE\/sddefault.jpg","width":640,"height":480},"maxres":{"url":"https:\/\/i.ytimg.com\/vi\/4kPDVaCzLHE\/maxresdefault.jpg","width":1280,"height":720}},"channelTitle":"Citro\u00ebn France","type":"playlistItem","groupId":"VTE0MzI5MTIwNzUxMzk5MjAyNDAyODAzMzY="},"contentDetails":{"playlistItem":{"resourceId":{"kind":"youtube#video","videoId":"4kPDVaCzLHE"},"playlistId":"PLL-i7w3LAKoD1j-RQPyC1bGR1gs1XasDK","playlistItemId":"PLJzYXefhQ8fvcZy0jeb0837W4U7qyEyrQ0lCbqVnMNmk"}}},{"kind":"youtube#activity","etag":"\"iDqJ1j7zKs4x3o3ZsFlBOwgWAHU\/8pAtAldyHMkF-1W7TQv7LaloEEc\"","id":"VTE0MzI3MzY4MjgxMzk5MjM1NDA5MDIxNjA=","snippet":{"publishedAt":"2015-05-27T14:27:08.000Z","channelId":"UCLf9Uuyli2HDBv2Jrsm_d-Q","title":"Citro\u00ebn Happy Movie !","description":"Nous partageons notre vision du bonheur depuis 1919 et nous continuerons encore et encore ! De la 2CV au C4 Cactus, retrouvez tout l'ADN Citro\u00ebn dans ce film !\nPlus d'informations ici : http:\/\/www.citroen.fr","thumbnails":{"default":{"url":"https:\/\/i.ytimg.com\/vi\/Rxw8lfroceE\/default.jpg","width":120,"height":90},"medium":{"url":"https:\/\/i.ytimg.com\/vi\/Rxw8lfroceE\/mqdefault.jpg","width":320,"height":180},"high":{"url":"https:\/\/i.ytimg.com\/vi\/Rxw8lfroceE\/hqdefault.jpg","width":480,"height":360},"standard":{"url":"https:\/\/i.ytimg.com\/vi\/Rxw8lfroceE\/sddefault.jpg","width":640,"height":480},"maxres":{"url":"https:\/\/i.ytimg.com\/vi\/Rxw8lfroceE\/maxresdefault.jpg","width":1280,"height":720}},"channelTitle":"Citro\u00ebn France","type":"upload"},"contentDetails":{"upload":{"videoId":"Rxw8lfroceE"}}},{"kind":"youtube#activity","etag":"\"iDqJ1j7zKs4x3o3ZsFlBOwgWAHU\/fLBfuvGs72P2Tk_NztaqePS7bII\"","id":"VTE0Mjk4MDA1ODExMzk5MjM1NjUwMjI4MDA=","snippet":{"publishedAt":"2015-04-23T14:49:41.000Z","channelId":"UCLf9Uuyli2HDBv2Jrsm_d-Q","title":"Pub TV Nouvelle Citro\u00ebn C1","description":"Avec la nouvelle Citro\u00ebn C1, prenez la ville du bon c\u00f4t\u00e9, plus de design, plus de confort, plus de technologie utile et un budget ma\u00eetris\u00e9.\nD\u00e9couvrez la sur : http:\/\/www.citroen.fr\/vehicules\/citroen\/nouvelle-citroen-c1.html\nConfigurez la sur : http:\/\/configurer.citroen.fr\/Configurator\/Index\/1CB1A3\nDemandez une offre : lp.citroen.fr\/fr\/landing-page\/multi-vehicules-citroen-505?TESTDRIVE_CAR_LCDV=1CB1A3","thumbnails":{"default":{"url":"https:\/\/i.ytimg.com\/vi\/7OuvgsUOngM\/default.jpg","width":120,"height":90},"medium":{"url":"https:\/\/i.ytimg.com\/vi\/7OuvgsUOngM\/mqdefault.jpg","width":320,"height":180},"high":{"url":"https:\/\/i.ytimg.com\/vi\/7OuvgsUOngM\/hqdefault.jpg","width":480,"height":360},"standard":{"url":"https:\/\/i.ytimg.com\/vi\/7OuvgsUOngM\/sddefault.jpg","width":640,"height":480},"maxres":{"url":"https:\/\/i.ytimg.com\/vi\/7OuvgsUOngM\/maxresdefault.jpg","width":1280,"height":720}},"channelTitle":"Citro\u00ebn France","type":"upload"},"contentDetails":{"upload":{"videoId":"7OuvgsUOngM"}}},{"kind":"youtube#activity","etag":"\"iDqJ1j7zKs4x3o3ZsFlBOwgWAHU\/AE_BxLbKu0oYf7xp7iHth1rQAvk\"","id":"VTE0MjkxMTI3MTIxMzk5MjM1NDk4MTIwNDg=","snippet":{"publishedAt":"2015-04-15T15:45:12.000Z","channelId":"UCLf9Uuyli2HDBv2Jrsm_d-Q","title":"Nouveau Concept Car Citro\u00ebn Aircross","description":"Plongez dans les coulisses de la cr\u00e9ation de notre concept car Citro\u00ebn Aircross !","thumbnails":{"default":{"url":"https:\/\/i.ytimg.com\/vi\/F9KbrgkdQow\/default.jpg","width":120,"height":90},"medium":{"url":"https:\/\/i.ytimg.com\/vi\/F9KbrgkdQow\/mqdefault.jpg","width":320,"height":180},"high":{"url":"https:\/\/i.ytimg.com\/vi\/F9KbrgkdQow\/hqdefault.jpg","width":480,"height":360},"standard":{"url":"https:\/\/i.ytimg.com\/vi\/F9KbrgkdQow\/sddefault.jpg","width":640,"height":480},"maxres":{"url":"https:\/\/i.ytimg.com\/vi\/F9KbrgkdQow\/maxresdefault.jpg","width":1280,"height":720}},"channelTitle":"Citro\u00ebn France","type":"upload","groupId":"VTE0MjkxMTI3MTIxMzk5MjM1NDk4MTIwNDg="},"contentDetails":{"upload":{"videoId":"F9KbrgkdQow"}}},{"kind":"youtube#activity","etag":"\"iDqJ1j7zKs4x3o3ZsFlBOwgWAHU\/zEf2Zfy-JjqdUbW7lQogsSh9yMo\"","id":"QTE0MjkxMTI3MTMxMzk5MjM1NDk4MTE1MzY=","snippet":{"publishedAt":"2015-04-15T15:45:13.000Z","channelId":"UCLf9Uuyli2HDBv2Jrsm_d-Q","title":"Nouveau Concept Car Citro\u00ebn Aircross","description":"Plongez dans les coulisses de la cr\u00e9ation de notre concept car Citro\u00ebn Aircross !","thumbnails":{"default":{"url":"https:\/\/i.ytimg.com\/vi\/F9KbrgkdQow\/default.jpg","width":120,"height":90},"medium":{"url":"https:\/\/i.ytimg.com\/vi\/F9KbrgkdQow\/mqdefault.jpg","width":320,"height":180},"high":{"url":"https:\/\/i.ytimg.com\/vi\/F9KbrgkdQow\/hqdefault.jpg","width":480,"height":360},"standard":{"url":"https:\/\/i.ytimg.com\/vi\/F9KbrgkdQow\/sddefault.jpg","width":640,"height":480},"maxres":{"url":"https:\/\/i.ytimg.com\/vi\/F9KbrgkdQow\/maxresdefault.jpg","width":1280,"height":720}},"channelTitle":"Citro\u00ebn France","type":"playlistItem","groupId":"VTE0MjkxMTI3MTIxMzk5MjM1NDk4MTIwNDg="},"contentDetails":{"playlistItem":{"resourceId":{"kind":"youtube#video","videoId":"F9KbrgkdQow"},"playlistId":"PL528FD2A2183FA3B0","playlistItemId":"PLJEU4HV2qyEosgrd6tnE77QZX0RtHGHH3"}}},{"kind":"youtube#activity","etag":"\"iDqJ1j7zKs4x3o3ZsFlBOwgWAHU\/mNmBVxOlQSmd6UUZLEr7EDTuX9A\"","id":"VTE0MjkxMDI1MjExMzk5MjM1NDk4MTE3Mjg=","snippet":{"publishedAt":"2015-04-15T12:55:21.000Z","channelId":"UCLf9Uuyli2HDBv2Jrsm_d-Q","title":"Larguez les amarres ! Gagnez votre week-end en C4 Cactus - #LoveC4Cactus","description":"Citro\u00ebn vous donne les cl\u00e9s de son nouveau Crossover C4 Cactus durant un week-end ! \nPour participer, c\u2019est tr\u00e8s simple : racontez-nous votre projet de week-end sur : http:\/\/lovec4cactus.fr\/ avec le hashtag #LoveC4Catus\n\nPr\u00eat \u00e0 faire vos valises ?","thumbnails":{"default":{"url":"https:\/\/i.ytimg.com\/vi\/j1tiUr7-Y28\/default.jpg","width":120,"height":90},"medium":{"url":"https:\/\/i.ytimg.com\/vi\/j1tiUr7-Y28\/mqdefault.jpg","width":320,"height":180},"high":{"url":"https:\/\/i.ytimg.com\/vi\/j1tiUr7-Y28\/hqdefault.jpg","width":480,"height":360},"standard":{"url":"https:\/\/i.ytimg.com\/vi\/j1tiUr7-Y28\/sddefault.jpg","width":640,"height":480},"maxres":{"url":"https:\/\/i.ytimg.com\/vi\/j1tiUr7-Y28\/maxresdefault.jpg","width":1280,"height":720}},"channelTitle":"Citro\u00ebn France","type":"upload"},"contentDetails":{"upload":{"videoId":"j1tiUr7-Y28"}}},{"kind":"youtube#activity","etag":"\"iDqJ1j7zKs4x3o3ZsFlBOwgWAHU\/C5MOpBAIhBh-1W84L_wTsOgy2kA\"","id":"VTE0Mjg2NTY0MjQxMzk5MjM1NDk4MTIzNjg=","snippet":{"publishedAt":"2015-04-10T09:00:24.000Z","channelId":"UCLf9Uuyli2HDBv2Jrsm_d-Q","title":"Nouvelle Citro\u00ebn C4 Cactus : Plus de vie dans votre vie !","description":"Nouvelle Citro\u00ebn C4 Cactus : Plus de vie dans votre vie !\nD\u00e9couvrez la : http:\/\/bit.ly\/1CZZIih\nConfigurez la : http:\/\/bit.ly\/1PrlPlE\nEssayez la : http:\/\/bit.ly\/1GXlwcl","thumbnails":{"default":{"url":"https:\/\/i.ytimg.com\/vi\/MgGJyPhsozA\/default.jpg","width":120,"height":90},"medium":{"url":"https:\/\/i.ytimg.com\/vi\/MgGJyPhsozA\/mqdefault.jpg","width":320,"height":180},"high":{"url":"https:\/\/i.ytimg.com\/vi\/MgGJyPhsozA\/hqdefault.jpg","width":480,"height":360},"standard":{"url":"https:\/\/i.ytimg.com\/vi\/MgGJyPhsozA\/sddefault.jpg","width":640,"height":480},"maxres":{"url":"https:\/\/i.ytimg.com\/vi\/MgGJyPhsozA\/maxresdefault.jpg","width":1280,"height":720}},"channelTitle":"Citro\u00ebn France","type":"upload","groupId":"VTE0Mjg2NTY0MjQxMzk5MjM1NDk4MTIzNjg="},"contentDetails":{"upload":{"videoId":"MgGJyPhsozA"}}},{"kind":"youtube#activity","etag":"\"iDqJ1j7zKs4x3o3ZsFlBOwgWAHU\/KvR_81qYWtbW8nSL70yD-9YsAt8\"","id":"QTE0Mjg2NTY0MjUxMzk5MjM1ODgzMDY4OTY=","snippet":{"publishedAt":"2015-04-10T09:00:25.000Z","channelId":"UCLf9Uuyli2HDBv2Jrsm_d-Q","title":"Nouvelle Citro\u00ebn C4 Cactus : Plus de vie dans votre vie !","description":"Nouvelle Citro\u00ebn C4 Cactus : Plus de vie dans votre vie !\nD\u00e9couvrez la : http:\/\/bit.ly\/1CZZIih\nConfigurez la : http:\/\/bit.ly\/1PrlPlE\nEssayez la : http:\/\/bit.ly\/1GXlwcl","thumbnails":{"default":{"url":"https:\/\/i.ytimg.com\/vi\/MgGJyPhsozA\/default.jpg","width":120,"height":90},"medium":{"url":"https:\/\/i.ytimg.com\/vi\/MgGJyPhsozA\/mqdefault.jpg","width":320,"height":180},"high":{"url":"https:\/\/i.ytimg.com\/vi\/MgGJyPhsozA\/hqdefault.jpg","width":480,"height":360},"standard":{"url":"https:\/\/i.ytimg.com\/vi\/MgGJyPhsozA\/sddefault.jpg","width":640,"height":480},"maxres":{"url":"https:\/\/i.ytimg.com\/vi\/MgGJyPhsozA\/maxresdefault.jpg","width":1280,"height":720}},"channelTitle":"Citro\u00ebn France","type":"playlistItem","groupId":"VTE0Mjg2NTY0MjQxMzk5MjM1NDk4MTIzNjg="},"contentDetails":{"playlistItem":{"resourceId":{"kind":"youtube#video","videoId":"MgGJyPhsozA"},"playlistId":"PL02A53EABD9EA4715","playlistItemId":"PL46drYnpO_LHhkXYB7U-kpCSKWd6dPSFI"}}},{"kind":"youtube#activity","etag":"\"iDqJ1j7zKs4x3o3ZsFlBOwgWAHU\/XYFBoFKdhxtO9wiXAVH5uq4gz5s\"","id":"VTE0MjgwNjY2NDkxMzk5MjM1NTM5OTk2MzI=","snippet":{"publishedAt":"2015-04-03T13:10:49.000Z","channelId":"UCLf9Uuyli2HDBv2Jrsm_d-Q","title":"Nouvelle gamme Citro\u00ebn C4 : ne sentez plus les kilom\u00e8tres passer !","description":"Nouvelle gamme Citro\u00ebn C4 : ne sentez plus les kilom\u00e8tres passer ! \nD\u00e9couvrez la : http:\/\/www.citroen.fr\/vehicules-neufs\/citroen\/citroen-c4.html\nConfigurez la : http:\/\/configurer.citroen.fr\/Configurator\/Index\/1CB7A5\/?page_skin%20=c-skin&_ga=1.258992778.1402600981.1427272083\nEssayez la : http:\/\/lp.citroen.fr\/fr\/landing-page\/multi-vehicules-citroen-505?TESTDRIVE_CAR_LCDV=1CB7A5","thumbnails":{"default":{"url":"https:\/\/i.ytimg.com\/vi\/FyMqxzZqUEw\/default.jpg","width":120,"height":90},"medium":{"url":"https:\/\/i.ytimg.com\/vi\/FyMqxzZqUEw\/mqdefault.jpg","width":320,"height":180},"high":{"url":"https:\/\/i.ytimg.com\/vi\/FyMqxzZqUEw\/hqdefault.jpg","width":480,"height":360},"standard":{"url":"https:\/\/i.ytimg.com\/vi\/FyMqxzZqUEw\/sddefault.jpg","width":640,"height":480},"maxres":{"url":"https:\/\/i.ytimg.com\/vi\/FyMqxzZqUEw\/maxresdefault.jpg","width":1280,"height":720}},"channelTitle":"Citro\u00ebn France","type":"upload","groupId":"VTE0MjgwNjY2NDkxMzk5MjM1NTM5OTk2MzI="},"contentDetails":{"upload":{"videoId":"FyMqxzZqUEw"}}}];
                 */
                var templateLoop = '';
                $(response).each(function (i) {
                    if ($(this)[0].contentDetails.upload) {
                        templateLoop += _.template(loopTpl, $(this)[0]);
                    }
                });
                $ul.html(templateLoop);
                $ul.after(_.template(headerTpl, response[0]));

                popInit();
                me.loader.hide();

            },
            error: function (x, e) {
                /*
                 console.log('SocialNetFeeds - Youtube - Feed is ko - reason : '+x.status+' / '+e);
                 */
            }
        });

        /* End Comment for active Hack */
    }
};


/* COMPARATEUR SKIN NEW SHOWROOM */

/**
 * Update the "caracteristiques-equipements" table and listen to change events
 */
(function () {
    window.updateComparisonTable = function () {
        var $selectInputs = $('#form_comparateur .listickholder input');
        var $diffTable = $('#form_comparateur #caracteristiques-equipements').parents('table:first');
        var $input = $('.showdifcheck');

        //console.log('updateComparisonTable');

        $(window).trigger('comparUpdated');

        function inputChangeListener(event) {
            if ($(this).is(':checked')) {
                $diffTable.addClass('showdif');
            } else {
                $diffTable.removeClass('showdif');
            }
        }

        function selectInputsChangeListener(event) {
            $input.trigger('change');
        }

        $input.bind('change', inputChangeListener);
        $input.trigger('change');

        $selectInputs.bind('change', selectInputsChangeListener);
    };
})();







/* Sync height */
var sync = {
    // Status indicator
    init: false
    // Process indicator
    ,
    processing: false
    // Iteration counter
    ,
    iteration: 0
    // Registered groups
    ,
    groups: []
    // Excluded groups
    ,
    excluded: []

            /**
             * Build sync group
             */
    ,
    build: function () {

        // If not yet synced
        if (!sync.init) {
            // Load events
            $(document).ready(sync.set);
            $(window).resize(sync.set);

            // Set the status indicator to true
            sync.init = true;
        }
        ;

        // Register the group if not yet registered
        if (sync.groups.indexOf(this.getAttribute('data-sync')) == -1) {
            sync.groups.push(this.getAttribute('data-sync'));
        }

    }
    /**
     * Set up the sync
     */
    ,
    set: function () {
        // If processing, cancel
        if (sync.processing)
            return;
        sync.processing = true;

        // Iterate the counter
        sync.iteration++;

        // Loop through the groups
        groupsLoop:
                for (var i in sync.groups) {
            var
                    // Get the group elements
                    $titles = $('[data-sync="' + sync.groups[i] + '"]')
                    // Count the group elements
                    ,
                    titlesLgth = $titles.length
                    // Type of the sync group
                    ,
                    syncType = sync.groups[i];

            // if the group has more than one element
            if (titlesLgth > 1) {
                var
                        minHeight = $titles.eq(0).css('minHeight'),
                        min = minHeight === undefined ? 0 : parseInt(minHeight.replace('px', ''), 10) || 0,
                        indexMaxCtaA = -1,
                        indexMaxCtaB = -1,
                        globalmax = 0,
                        globalmin = 0;

                // indexMaxCtaA et indexMaxCtaB - Ce n'est pas tr�s propre, mais il ne peut y avoir que 3 CTA max.
                // Ce sont les index des CTA ou il n'y a aucune action � faire en cas de diff�rence de hauteur, donc max. 2/3

                // If the group has at least one element
                if (titlesLgth > 0) {
                    globalmax = $titles.eq(0).outerHeight();
                    globalmin = $titles.eq(0).outerHeight();
                }

                // For each group elements
                groupElementsLoop:
                        for (var j = 0; j < titlesLgth; j++) {
                    var
                            $title = $titles.eq(j),
                            h = $title.outerHeight();

                    // If the height is null, cancel
                    if (h == 0 || $title[0].offsetHeight == 0 || $title[0].offsetWidth == 0) {
                        sync.processing = false;
                        continue groupsLoop;
                    }

                    // If the height is lower than the height
                    if (globalmax <= h) {
                        // Set the global max equal the height
                        globalmax = h;

                        if (h > 50) {
                            if (indexMaxCtaA == -1)
                                indexMaxCtaA = j; // Ne fait rien // La premi�re fois on renseigne l'index du CtaA
                        }
                        if (indexMaxCtaA != -1)
                            indexMaxCtaB = j; // A la deuxi�me passe on renseigne l'index du CtaA

                    }
                    // Otherwise, the global min equals the current height
                    else {
                        globalmin = h;
                    }
                }

                globalmax = globalmax <= min ? min : globalmax;


                // On �galise les hauteurs si il y a une diff�rence ou si l'on doit forcer...
                if ((globalmax != globalmin) || ((syncType.substr(syncType, 9) === 'forcesync'))) {

                    for (var k = 0; k < titlesLgth; k++) {

                        var $title = $titles.eq(k);

                        // que pour les �l�ments n�cessaire.
                        if ($title.parent().hasClass("cta")) {
                            if ((k != indexMaxCtaA) && (k != indexMaxCtaB)) {
                                $title.css('min-height', globalmax);
                                /*  $title.css("border-color","#00ff00");
                                     $title.css("border-style","solid");
                                     $title.css("border-width","1px"); */
                                    var h = $title.parent().height() - $title.height();
                                    $title.css("padding-top", ((Math.round(h / 2)) + 4) + "px"); // si je r��quilibre c'est que je suis sur une ligne...
                                    sync.excluded.push(sync.groups[i]); // Ne plus refaire de synchro dessus synchro en cours.
                            }
                        } else {
                            if (syncType.substr(syncType, 9) === 'forcesync') {
                                globalmax = globalmax + 5;
                            }

                            $title.css('min-height', globalmax);
                            var h = $title.outerHeight();
                            /*  $title.css("border-color","#0000FF");
                                 $title.css("border-style","solid");
                                 $title.css("border-width","1px");   */
                                if (h < globalmax) {
                                    $title.css('padding', Math.round(h / 2) + 'px 0');
                                }
                                sync.excluded.push(sync.groups[i]); // Ne plus refaire de synchro dessus synchro en cours.
                        }
                    }

                    if ($titles.eq(0).parents('.layer').length) {
                        for (var m = 0; m < titlesLgth; m++) {
                            var $title = $titles.eq(m);

                            if (!$title[0].getElementsByTagName('span').length) {
                                $title.wrapInner('<span class="center" />');
                            }
                        }
                    }
                }
            }

            // End of the FOR loop through groups
        }

        // For each excluded groups
        for (var i in sync.excluded) {
            // Remove it from the sync groups
            sync.groups.splice(sync.groups.indexOf(sync.excluded[i]), 1);

            // Remove it from the excluded groups
            sync.excluded.splice(i, 1);
        }

        // Done, ready to process
        sync.processing = false;
    }
};

/* Facebook fluid */
var likeboxes = [];

function setlikeboxwidth() {
    $(likeboxes).each(function () {
        var w = this.parents('.fb-like-box').parent().width();
        this.css('width', w);
        this.parent().css('width', w);
    });
}
;

function makeUnselectable(node) {
    if (node.nodeType == 1) {
        node.setAttribute("unselectable", "on");
    }
    var child = node.firstChild;
    while (child) {
        makeUnselectable(child);
        child = child.nextSibling;
    }
}
;


/* scrolltop tools */
/* 2024 an ID to the button to make it independant of other sticky features */

$(window).scroll(function () {
    var bnt2top = $('#btn2top'),
            btn2topLimit = 1400;
    if (($(window).scrollTop() > btn2topLimit) && !bnt2top.is(':visible')) {
        bnt2top.show();
    } else if (($(window).scrollTop() < btn2topLimit) && bnt2top.is(':visible')) {
        bnt2top.hide();
    }
});

/* SElected car */
$('.selectedCar').click(function () {
    $('.selectedCar').removeClass('active');
    $(this).addClass('active');
});

/* */
// http://masonry.desandro.com/masonry.pkgd.js added as external resource

// create <div class="item"></div>
try {
    var $container = $('.masonry');

    if (0 < $container.length) {
        var url = $container.attr('data-ws'),
                addItems = function (start) {
                    var loader = new Loader($('.masonry'));
                    loader.show('', false);
                    $.ajax({
                        url: url,
                        data: {
                            start: start || 0
                        },
                        success: function (response) {

                            loader.hide();
                            var tpl = $('#masonryTpl').html(),
                                    compiledTemplate = _.template(tpl, {
                                        obj: response
                                    });

                            $container.append(compiledTemplate);
                            $container.find('.added').each(function () {
                                $(this).removeClass('added').find('img').load(function () {
                                    $container.masonry();
                                });
                                $container.masonry('appended', this);
                            });

                            $container.masonry();

                            popInit();
                            $container.find('.zoner').each(zoner.build);


                            if (response.nextstart) {
                                $('.masonry + .addmore a').show().unbind('click').click(function () {
                                    addItems(response.nextstart);
                                });
                            } else {
                                $('.masonry + .addmore a').remove();
                            }
                            ;

                        }

                    });

                };

        if (url) {

            /* Initialize masonry */
            $container.masonry({
                singleMode: false,
                resizeable: true,
                columnWidth: '.item',
                itemSelector: '.item',
                isAnimated: true
            });

            /* Initialize updater */
            $('.masonry + .addmore a').hide();

            /* Get first set */
            addItems();

        }
        ;

    }
    ;



} catch (e) {
}
;

/*
 * @fileOverview TouchSwipe - jQuery Plugin
 * @version 1.6.3
 *
 * @author Matt Bryson http://www.github.com/mattbryson
 * @see https://github.com/mattbryson/TouchSwipe-Jquery-Plugin
 * @see http://labs.skinkers.com/touchSwipe/
 * @see http://plugins.jquery.com/project/touchSwipe
 *
 * Copyright (c) 2010 Matt Bryson
 * Dual licensed under the MIT or GPL Version 2 licenses.
 */
;
(function (e) {
    var o = "left",
            n = "right",
            d = "up",
            v = "down",
            c = "in",
            w = "out",
            l = "none",
            r = "auto",
            k = "swipe",
            s = "pinch",
            x = "tap",
            i = "doubletap",
            b = "longtap",
            A = "horizontal",
            t = "vertical",
            h = "all",
            q = 10,
            f = "start",
            j = "move",
            g = "end",
            p = "cancel",
            a = "ontouchstart" in window,
            y = "TouchSwipe";
    var m = {
        fingers: 1,
        threshold: 75,
        cancelThreshold: null,
        pinchThreshold: 20,
        maxTimeThreshold: null,
        fingerReleaseThreshold: 250,
        longTapThreshold: 500,
        doubleTapThreshold: 200,
        swipe: null,
        swipeLeft: null,
        swipeRight: null,
        swipeUp: null,
        swipeDown: null,
        swipeStatus: null,
        pinchIn: null,
        pinchOut: null,
        pinchStatus: null,
        click: null,
        tap: null,
        doubleTap: null,
        longTap: null,
        triggerOnTouchEnd: true,
        triggerOnTouchLeave: false,
        allowPageScroll: "auto",
        fallbackToMouseEvents: true,
        excludedElements: "button, input, select, textarea, a, .noSwipe"
    };
    e.fn.swipe = function (D) {
        var C = e(this),
                B = C.data(y);
        if (B && typeof D === "string") {
            if (B[D]) {
                return B[D].apply(this, Array.prototype.slice.call(arguments, 1))
            } else {
                e.error("Method " + D + " does not exist on jQuery.swipe")
            }
        } else {
            if (!B && (typeof D === "object" || !D)) {
                return u.apply(this, arguments)
            }
        }
        return C
    };
    e.fn.swipe.defaults = m;
    e.fn.swipe.phases = {
        PHASE_START: f,
        PHASE_MOVE: j,
        PHASE_END: g,
        PHASE_CANCEL: p
    };
    e.fn.swipe.directions = {
        LEFT: o,
        RIGHT: n,
        UP: d,
        DOWN: v,
        IN: c,
        OUT: w
    };
    e.fn.swipe.pageScroll = {
        NONE: l,
        HORIZONTAL: A,
        VERTICAL: t,
        AUTO: r
    };
    e.fn.swipe.fingers = {
        ONE: 1,
        TWO: 2,
        THREE: 3,
        ALL: h
    };

    function u(B) {
        if (B && (B.allowPageScroll === undefined && (B.swipe !== undefined || B.swipeStatus !== undefined))) {
            B.allowPageScroll = l
        }
        if (B.click !== undefined && B.tap === undefined) {
            B.tap = B.click
        }
        if (!B) {
            B = {}
        }
        B = e.extend({}, e.fn.swipe.defaults, B);
        return this.each(function () {
            var D = e(this);
            var C = D.data(y);
            if (!C) {
                C = new z(this, B);
                D.data(y, C)
            }
        })
    }

    function z(a0, aq) {
        var av = (a || !aq.fallbackToMouseEvents),
                G = av ? "touchstart" : "mousedown",
                au = av ? "touchmove" : "mousemove",
                R = av ? "touchend" : "mouseup",
                P = av ? null : "mouseleave",
                az = "touchcancel";
        var ac = 0,
                aL = null,
                Y = 0,
                aX = 0,
                aV = 0,
                D = 1,
                am = 0,
                aF = 0,
                J = null;
        var aN = e(a0);
        var W = "start";
        var T = 0;
        var aM = null;
        var Q = 0,
                aY = 0,
                a1 = 0,
                aa = 0,
                K = 0;
        var aS = null;
        try {
            aN.bind(G, aJ);
            aN.bind(az, a5)
        } catch (ag) {
            e.error("events not supported " + G + "," + az + " on jQuery.swipe")
        }
        this.enable = function () {
            aN.bind(G, aJ);
            aN.bind(az, a5);
            return aN
        };
        this.disable = function () {
            aG();
            return aN
        };
        this.destroy = function () {
            aG();
            aN.data(y, null);
            return aN
        };
        this.option = function (a8, a7) {
            if (aq[a8] !== undefined) {
                if (a7 === undefined) {
                    return aq[a8]
                } else {
                    aq[a8] = a7
                }
            } else {
                e.error("Option " + a8 + " does not exist on jQuery.swipe.options")
            }
        };

        function aJ(a9) {
            if (ax()) {
                return
            }
            if (e(a9.target).closest(aq.excludedElements, aN).length > 0) {
                return
            }
            var ba = a9.originalEvent ? a9.originalEvent : a9;
            var a8, a7 = a ? ba.touches[0] : ba;
            W = f;
            if (a) {
                T = ba.touches.length
            } else {
                a9.preventDefault()
            }
            ac = 0;
            aL = null;
            aF = null;
            Y = 0;
            aX = 0;
            aV = 0;
            D = 1;
            am = 0;
            aM = af();
            J = X();
            O();
            if (!a || (T === aq.fingers || aq.fingers === h) || aT()) {
                ae(0, a7);
                Q = ao();
                if (T == 2) {
                    ae(1, ba.touches[1]);
                    aX = aV = ap(aM[0].start, aM[1].start)
                }
                if (aq.swipeStatus || aq.pinchStatus) {
                    a8 = L(ba, W)
                }
            } else {
                a8 = false
            }
            if (a8 === false) {
                W = p;
                L(ba, W);
                return a8
            } else {
                ak(true)
            }
        }

        function aZ(ba) {
            var bd = ba.originalEvent ? ba.originalEvent : ba;
            if (W === g || W === p || ai()) {
                return
            }
            var a9, a8 = a ? bd.touches[0] : bd;
            var bb = aD(a8);
            aY = ao();
            if (a) {
                T = bd.touches.length
            }
            W = j;
            if (T == 2) {
                if (aX == 0) {
                    ae(1, bd.touches[1]);
                    aX = aV = ap(aM[0].start, aM[1].start)
                } else {
                    aD(bd.touches[1]);
                    aV = ap(aM[0].end, aM[1].end);
                    aF = an(aM[0].end, aM[1].end)
                }
                D = a3(aX, aV);
                am = Math.abs(aX - aV)
            }
            if ((T === aq.fingers || aq.fingers === h) || !a || aT()) {
                aL = aH(bb.start, bb.end);
                ah(ba, aL);
                ac = aO(bb.start, bb.end);
                Y = aI();
                aE(aL, ac);
                if (aq.swipeStatus || aq.pinchStatus) {
                    a9 = L(bd, W)
                }
                if (!aq.triggerOnTouchEnd || aq.triggerOnTouchLeave) {
                    var a7 = true;
                    if (aq.triggerOnTouchLeave) {
                        var bc = aU(this);
                        a7 = B(bb.end, bc)
                    }
                    if (!aq.triggerOnTouchEnd && a7) {
                        W = ay(j)
                    } else {
                        if (aq.triggerOnTouchLeave && !a7) {
                            W = ay(g)
                        }
                    }
                    if (W == p || W == g) {
                        L(bd, W)
                    }
                }
            } else {
                W = p;
                L(bd, W)
            }
            if (a9 === false) {
                W = p;
                L(bd, W)
            }
        }

        function I(a7) {
            var a8 = a7.originalEvent;
            if (a) {
                if (a8.touches.length > 0) {
                    C();
                    return true
                }
            }
            if (ai()) {
                T = aa
            }
            a7.preventDefault();
            aY = ao();
            Y = aI();
            if (a6()) {
                W = p;
                L(a8, W)
            } else {
                if (aq.triggerOnTouchEnd || (aq.triggerOnTouchEnd == false && W === j)) {
                    W = g;
                    L(a8, W)
                } else {
                    if (!aq.triggerOnTouchEnd && a2()) {
                        W = g;
                        aB(a8, W, x)
                    } else {
                        if (W === j) {
                            W = p;
                            L(a8, W)
                        }
                    }
                }
            }
            ak(false)
        }

        function a5() {
            T = 0;
            aY = 0;
            Q = 0;
            aX = 0;
            aV = 0;
            D = 1;
            O();
            ak(false)
        }

        function H(a7) {
            var a8 = a7.originalEvent;
            if (aq.triggerOnTouchLeave) {
                W = ay(g);
                L(a8, W)
            }
        }

        function aG() {
            aN.unbind(G, aJ);
            aN.unbind(az, a5);
            aN.unbind(au, aZ);
            aN.unbind(R, I);
            if (P) {
                aN.unbind(P, H)
            }
            ak(false)
        }

        function ay(bb) {
            var ba = bb;
            var a9 = aw();
            var a8 = aj();
            var a7 = a6();
            if (!a9 || a7) {
                ba = p
            } else {
                if (a8 && bb == j && (!aq.triggerOnTouchEnd || aq.triggerOnTouchLeave)) {
                    ba = g
                } else {
                    if (!a8 && bb == g && aq.triggerOnTouchLeave) {
                        ba = p
                    }
                }
            }
            return ba
        }

        function L(a9, a7) {
            var a8 = undefined;
            if (F() || S()) {
                a8 = aB(a9, a7, k)
            } else {
                if ((M() || aT()) && a8 !== false) {
                    a8 = aB(a9, a7, s)
                }
            }
            if (aC() && a8 !== false) {
                a8 = aB(a9, a7, i)
            } else {
                if (al() && a8 !== false) {
                    a8 = aB(a9, a7, b)
                } else {
                    if (ad() && a8 !== false) {
                        a8 = aB(a9, a7, x)
                    }
                }
            }
            if (a7 === p) {
                a5(a9)
            }
            if (a7 === g) {
                if (a) {
                    if (a9.touches.length == 0) {
                        a5(a9)
                    }
                } else {
                    a5(a9)
                }
            }
            return a8
        }

        function aB(ba, a7, a9) {
            var a8 = undefined;
            if (a9 == k) {
                aN.trigger("swipeStatus", [a7, aL || null, ac || 0, Y || 0, T]);
                if (aq.swipeStatus) {
                    a8 = aq.swipeStatus.call(aN, ba, a7, aL || null, ac || 0, Y || 0, T);
                    if (a8 === false) {
                        return false
                    }
                }
                if (a7 == g && aR()) {
                    aN.trigger("swipe", [aL, ac, Y, T]);
                    if (aq.swipe) {
                        a8 = aq.swipe.call(aN, ba, aL, ac, Y, T);
                        if (a8 === false) {
                            return false
                        }
                    }
                    switch (aL) {
                        case o:
                            aN.trigger("swipeLeft", [aL, ac, Y, T]);
                            if (aq.swipeLeft) {
                                a8 = aq.swipeLeft.call(aN, ba, aL, ac, Y, T)
                            }
                            break;
                        case n:
                            aN.trigger("swipeRight", [aL, ac, Y, T]);
                            if (aq.swipeRight) {
                                a8 = aq.swipeRight.call(aN, ba, aL, ac, Y, T)
                            }
                            break;
                        case d:
                            aN.trigger("swipeUp", [aL, ac, Y, T]);
                            if (aq.swipeUp) {
                                a8 = aq.swipeUp.call(aN, ba, aL, ac, Y, T)
                            }
                            break;
                        case v:
                            aN.trigger("swipeDown", [aL, ac, Y, T]);
                            if (aq.swipeDown) {
                                a8 = aq.swipeDown.call(aN, ba, aL, ac, Y, T)
                            }
                            break
                    }
                }
            }
            if (a9 == s) {
                aN.trigger("pinchStatus", [a7, aF || null, am || 0, Y || 0, T, D]);
                if (aq.pinchStatus) {
                    a8 = aq.pinchStatus.call(aN, ba, a7, aF || null, am || 0, Y || 0, T, D);
                    if (a8 === false) {
                        return false
                    }
                }
                if (a7 == g && a4()) {
                    switch (aF) {
                        case c:
                            aN.trigger("pinchIn", [aF || null, am || 0, Y || 0, T, D]);
                            if (aq.pinchIn) {
                                a8 = aq.pinchIn.call(aN, ba, aF || null, am || 0, Y || 0, T, D)
                            }
                            break;
                        case w:
                            aN.trigger("pinchOut", [aF || null, am || 0, Y || 0, T, D]);
                            if (aq.pinchOut) {
                                a8 = aq.pinchOut.call(aN, ba, aF || null, am || 0, Y || 0, T, D)
                            }
                            break
                    }
                }
            }
            if (a9 == x) {
                if (a7 === p || a7 === g) {
                    clearTimeout(aS);
                    if (V() && !E()) {
                        K = ao();
                        aS = setTimeout(e.proxy(function () {
                            K = null;
                            aN.trigger("tap", [ba.target]);
                            if (aq.tap) {
                                a8 = aq.tap.call(aN, ba, ba.target)
                            }
                        }, this), aq.doubleTapThreshold)
                    } else {
                        K = null;
                        aN.trigger("tap", [ba.target]);
                        if (aq.tap) {
                            a8 = aq.tap.call(aN, ba, ba.target)
                        }
                    }
                }
            } else {
                if (a9 == i) {
                    if (a7 === p || a7 === g) {
                        clearTimeout(aS);
                        K = null;
                        aN.trigger("doubletap", [ba.target]);
                        if (aq.doubleTap) {
                            a8 = aq.doubleTap.call(aN, ba, ba.target)
                        }
                    }
                } else {
                    if (a9 == b) {
                        if (a7 === p || a7 === g) {
                            clearTimeout(aS);
                            K = null;
                            aN.trigger("longtap", [ba.target]);
                            if (aq.longTap) {
                                a8 = aq.longTap.call(aN, ba, ba.target)
                            }
                        }
                    }
                }
            }
            return a8
        }

        function aj() {
            var a7 = true;
            if (aq.threshold !== null) {
                a7 = ac >= aq.threshold
            }
            return a7
        }

        function a6() {
            var a7 = false;
            if (aq.cancelThreshold !== null && aL !== null) {
                a7 = (aP(aL) - ac) >= aq.cancelThreshold
            }
            return a7
        }

        function ab() {
            if (aq.pinchThreshold !== null) {
                return am >= aq.pinchThreshold
            }
            return true
        }

        function aw() {
            var a7;
            if (aq.maxTimeThreshold) {
                if (Y >= aq.maxTimeThreshold) {
                    a7 = false
                } else {
                    a7 = true
                }
            } else {
                a7 = true
            }
            return a7
        }

        function ah(a7, a8) {
            if (aq.allowPageScroll === l || aT()) {
                a7.preventDefault()
            } else {
                var a9 = aq.allowPageScroll === r;
                switch (a8) {
                    case o:
                        if ((aq.swipeLeft && a9) || (!a9 && aq.allowPageScroll != A)) {
                            a7.preventDefault()
                        }
                        break;
                    case n:
                        if ((aq.swipeRight && a9) || (!a9 && aq.allowPageScroll != A)) {
                            a7.preventDefault()
                        }
                        break;
                    case d:
                        if ((aq.swipeUp && a9) || (!a9 && aq.allowPageScroll != t)) {
                            a7.preventDefault()
                        }
                        break;
                    case v:
                        if ((aq.swipeDown && a9) || (!a9 && aq.allowPageScroll != t)) {
                            a7.preventDefault()
                        }
                        break
                }
            }
        }

        function a4() {
            var a8 = aK();
            var a7 = U();
            var a9 = ab();
            return a8 && a7 && a9
        }

        function aT() {
            return !!(aq.pinchStatus || aq.pinchIn || aq.pinchOut)
        }

        function M() {
            return !!(a4() && aT())
        }

        function aR() {
            var ba = aw();
            var bc = aj();
            var a9 = aK();
            var a7 = U();
            var a8 = a6();
            var bb = !a8 && a7 && a9 && bc && ba;
            return bb
        }

        function S() {
            return !!(aq.swipe || aq.swipeStatus || aq.swipeLeft || aq.swipeRight || aq.swipeUp || aq.swipeDown)
        }

        function F() {
            return !!(aR() && S())
        }

        function aK() {
            return ((T === aq.fingers || aq.fingers === h) || !a)
        }

        function U() {
            return aM[0].end.x !== 0
        }

        function a2() {
            return !!(aq.tap)
        }

        function V() {
            return !!(aq.doubleTap)
        }

        function aQ() {
            return !!(aq.longTap)
        }

        function N() {
            if (K == null) {
                return false
            }
            var a7 = ao();
            return (V() && ((a7 - K) <= aq.doubleTapThreshold))
        }

        function E() {
            return N()
        }

        function at() {
            return ((T === 1 || !a) && (isNaN(ac) || ac === 0))
        }

        function aW() {
            return ((Y > aq.longTapThreshold) && (ac < q))
        }

        function ad() {
            return !!(at() && a2())
        }

        function aC() {
            return !!(N() && V())
        }

        function al() {
            return !!(aW() && aQ())
        }

        function C() {
            a1 = ao();
            aa = event.touches.length + 1
        }

        function O() {
            a1 = 0;
            aa = 0
        }

        function ai() {
            var a7 = false;
            if (a1) {
                var a8 = ao() - a1;
                if (a8 <= aq.fingerReleaseThreshold) {
                    a7 = true
                }
            }
            return a7
        }

        function ax() {
            return !!(aN.data(y + "_intouch") === true)
        }

        function ak(a7) {
            if (a7 === true) {
                aN.bind(au, aZ);
                aN.bind(R, I);
                if (P) {
                    aN.bind(P, H)
                }
            } else {
                aN.unbind(au, aZ, false);
                aN.unbind(R, I, false);
                if (P) {
                    aN.unbind(P, H, false)
                }
            }
            aN.data(y + "_intouch", a7 === true)
        }

        function ae(a8, a7) {
            var a9 = a7.identifier !== undefined ? a7.identifier : 0;
            aM[a8].identifier = a9;
            aM[a8].start.x = aM[a8].end.x = a7.pageX || a7.clientX;
            aM[a8].start.y = aM[a8].end.y = a7.pageY || a7.clientY;
            return aM[a8]
        }

        function aD(a7) {
            var a9 = a7.identifier !== undefined ? a7.identifier : 0;
            var a8 = Z(a9);
            a8.end.x = a7.pageX || a7.clientX;
            a8.end.y = a7.pageY || a7.clientY;
            return a8
        }

        function Z(a8) {
            for (var a7 = 0; a7 < aM.length; a7++) {
                if (aM[a7].identifier == a8) {
                    return aM[a7]
                }
            }
        }

        function af() {
            var a7 = [];
            for (var a8 = 0; a8 <= 5; a8++) {
                a7.push({
                    start: {
                        x: 0,
                        y: 0
                    },
                    end: {
                        x: 0,
                        y: 0
                    },
                    identifier: 0
                })
            }
            return a7
        }

        function aE(a7, a8) {
            a8 = Math.max(a8, aP(a7));
            J[a7].distance = a8
        }

        function aP(a7) {
            return J[a7].distance
        }

        function X() {
            var a7 = {};
            a7[o] = ar(o);
            a7[n] = ar(n);
            a7[d] = ar(d);
            a7[v] = ar(v);
            return a7
        }

        function ar(a7) {
            return {
                direction: a7,
                distance: 0
            }
        }

        function aI() {
            return aY - Q
        }

        function ap(ba, a9) {
            var a8 = Math.abs(ba.x - a9.x);
            var a7 = Math.abs(ba.y - a9.y);
            return Math.round(Math.sqrt(a8 * a8 + a7 * a7))
        }

        function a3(a7, a8) {
            var a9 = (a8 / a7) * 1;
            return a9.toFixed(2)
        }

        function an() {
            if (D < 1) {
                return w
            } else {
                return c
            }
        }

        function aO(a8, a7) {
            return Math.round(Math.sqrt(Math.pow(a7.x - a8.x, 2) + Math.pow(a7.y - a8.y, 2)))
        }

        function aA(ba, a8) {
            var a7 = ba.x - a8.x;
            var bc = a8.y - ba.y;
            var a9 = Math.atan2(bc, a7);
            var bb = Math.round(a9 * 180 / Math.PI);
            if (bb < 0) {
                bb = 360 - Math.abs(bb)
            }
            return bb
        }

        function aH(a8, a7) {
            var a9 = aA(a8, a7);
            if ((a9 <= 45) && (a9 >= 0)) {
                return o
            } else {
                if ((a9 <= 360) && (a9 >= 315)) {
                    return o
                } else {
                    if ((a9 >= 135) && (a9 <= 225)) {
                        return n
                    } else {
                        if ((a9 > 45) && (a9 < 135)) {
                            return v
                        } else {
                            return d
                        }
                    }
                }
            }
        }

        function ao() {
            var a7 = new Date();
            return a7.getTime()
        }

        function aU(a7) {
            a7 = e(a7);
            var a9 = a7.offset();
            var a8 = {
                left: a9.left,
                right: a9.left + a7.outerWidth(),
                top: a9.top,
                bottom: a9.top + a7.outerHeight()
            };
            return a8
        }

        function B(a7, a8) {
            return (a7.x > a8.left && a7.x < a8.right && a7.y > a8.top && a7.y < a8.bottom)
        }
    }
})(jQuery);

;
(function ($, win, doc, gtmCit) {
    /* GTM Functions */
    var $doc = $(doc),
            gtmModule = {};



    $(document).on('gtm', function(e) {
        var splitter = (e.dataGtm).split('|');
        var eventCategory = splitter[1];
        var eventAction = splitter[2];
        var eventLabel = splitter[3]+' '+splitter[4];
        if (splitter[5] != '')

        {
            eventLabel += ' - position '+splitter[5];
        }
        dataLayer.push({ 'event' : 'uaevent', 'eventCategory' : eventCategory, 'eventAction' : eventAction, 'eventLabel' : eventLabel }); });



    var setTrigger = function (value) {
        console.log("setTrigger : " + value);
        $doc.trigger({
            type: 'gtm',
            dataGtm: value
        });
    };

    gtmModule.tabs = function (data) {
        this.init(data)
    };
    gtmModule.tabs.prototype = {
        init: function (data) {
            var oThis = this;
            this.data = data[0];

            if (this.data == undefined) {
                return false;
            } else {
                this.sliced = this.data.split('|');
                this.$root = data.root;

                this.$root.find('li').each(function () {

                    $(this).on('click', function () {
                        var status = 'close';
                        if (!$(this).parent().hasClass('on')) {
                            status = 'open';
                        }
                        if (status == 'close')
                            return;

                        oThis.sliced[3] = $(this).text();
                        oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', oThis.sliced[5]);
                        setTrigger(oThis.data);
                    });
                });
            }
        }
    }

    gtmModule.expandBar = function (data) {
        this.init(data)
    };
    gtmModule.expandBar.prototype = {
        init: function (data) {
            var oThis = this;
            this.data = data[0];

            this.sliced = data[0].split('|');
            //this.sliced[1] = "toggle";
            this.$root = data.root;

            this.$root.on('click', function () {
                var status = 'close';


                var eventLabel = oThis.sliced[3];
                if ($(this).hasClass('open')) {
                    status = 'open';
                    eventLabel = oThis.sliced[2];
                }

                if (!$(this).hasClass('folder')) {
                    status = 'url';
                    eventLabel = oThis.sliced[2];
                }

                if (oThis.sliced[3] == '' && status == 'close')
                    return;


                oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', eventLabel, '|', oThis.sliced[4], '|', oThis.sliced[5], '|', oThis.sliced[6]);
                setTrigger(oThis.data);
            });
        }
    }
    /* DOUBLON / RESIDU
     gtmModule.searchText = function(data) {
     this.init(data)
     };
     gtmModule.searchText.prototype = {
     init: function(data) {
     var oThis = this;
     
     this.data = data[0];
     
     this.sliced = data[0].split('|');
     //this.sliced[1] = "toggle";
     this.$root = data.root;
     var oText = this.$root.find('#searchText');
     
     this.$root.find('#searchSubmit').on('click', function() {
     var searchText =oText.val();
     oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|',  oThis.sliced[2], '|', searchText, '|', oThis.sliced[4], '|', oThis.sliced[5]);
     setTrigger(oThis.data);
     
     });
     }
     }
     */
    gtmModule.toggle = function (data) {
        this.init(data)
    };
    gtmModule.toggle.prototype = {
        init: function (data) {
            var oThis = this;
            var eventGTM;
            this.data = data[0];

            this.sliced = data[0].split('|');
            //this.sliced[1] = "toggle";
            this.$root = data.root;

            if (data['eventGTM'] == 'over') {
                eventGTM = 'mouseover';
            } else {
                eventGTM = 'click';
            }
            this.$root.on(eventGTM, function () {
                var status = 'close';

                var eventLabel = oThis.sliced[3];
                if (!$(this).hasClass('open') && !$(this).hasClass('on')) {
                    status = 'open';
                    eventLabel = oThis.sliced[2];
                }
                if (oThis.sliced[3] == '' && status == 'close')
                    return;

                oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', eventLabel, '|', oThis.sliced[4], '|', oThis.sliced[5], '|', oThis.sliced[6]);
                setTrigger(oThis.data);
            });
        }
    }


    gtmModule.searchText = function (data) {
        this.init(data)
    };
    gtmModule.searchText.prototype = {
        init: function (data) {
            var oThis = this;
            var url = document.location.href;
            if (url.indexOf('?search=') !== -1) {
                this.data = data[0];
                this.sliced = data[0].split('|');
                this.$root = data.root;
                var keywordInUrl = url.slice(url.indexOf('=') + 1, url.indexOf('&'));
                var keywordInput = decodeURIComponent(keywordInUrl.replace(/\+/g, ' '));
                console.log($(keywordInput).text());
                oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', keywordInput);
                setTrigger(oThis.data);
            }
        }
    }


    gtmModule.clickable = function (data) {
        this.init(data)
    };

    gtmModule.clickable.prototype = {
        init: function (data) {
            var oThis = this;
            this.activeGTM = true;
            this.data = data[0];
            this.$root = data.root;
            this.$root.on('click', function () {
                if (oThis.activeGTM) {
                    setTrigger(oThis.data);
                }
            });
        }
    }
    
    gtmModule.clickableJS = function (data) {
        this.init(data)
    };

    gtmModule.clickableJS.prototype = {
        init: function (data) {
            var oThis = this;
            this.activeGTM = true;
            this.$root = data.root;
            this.data = $.parseJSON(this.$root.attr('data-gtm-js'))[0];
            this.$root.on('click', function () {
                if (oThis.activeGTM) {
                    var data = $.parseJSON(oThis.$root.attr('data-gtm-js'))[0];
                    setTrigger(data);
                }
            });
        }
    }

    gtmModule.jColors = function (data) {
        this.init(data)
    };
    gtmModule.jColors.prototype = {
        init: function (data) {
            var oThis = this;
            this.data = data[0];

            if (this.data == undefined) {
                return false;
            } else {
                this.sliced = data[0].split('|');
                this.$root = data.root;
                this.$next = this.$root.find('.bx-next');
                this.$prev = this.$root.find('.bx-prev');
                this.$pager = this.$root.find('.bx-pager');
                this.$bxSlider = data.bxSlider;
                this.currentSlide = 0;
                //this.sliced[1] = "slideShow";
                oThis.setHandlers();
            }
        },
        setHandlers: function () {
            var oThis = this;
            this.$next.on('click', function () {
                if (!$(this).hasClass('.disabled')) {
                    oThis.gtmNextPrev('Next');
                }
            });

            this.$prev.on('click', function () {
                if (!$(this).hasClass('.disabled')) {
                    oThis.gtmNextPrev('Previous');
                }
            });

            this.$pager.on('click', function () {
                if (!$(this).hasClass('.active')) {
                    oThis.gtmPager();
                }
            });

        },
        getMainSlide: function () {
            this.currentSlide = this.$bxSlider.getCurrentSlide();
        },
        gtmNextPrev: function (dir) {
            var oThis = this;
            this.getMainSlide();

            //console.log(this.currentSlide);
            oThis.sliced[3] = dir;
            oThis.sliced[5] = this.currentSlide + 1;
            oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', oThis.sliced[5]);
            setTrigger(oThis.data);

        },
        gtmPager: function () {
            var oThis = this;
            this.getMainSlide();
            oThis.sliced[3] = 'Pager';
            oThis.sliced[5] = this.currentSlide + 1;
            oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', oThis.sliced[5]);
            setTrigger(oThis.data);
        }

    }

    gtmModule.slider = function (data) {
        this.init(data)
    };
    gtmModule.slider.prototype = {
        init: function (data) {
            var oThis = this;
            this.data = data[0];
            if (this.data == undefined) {
                return false;
            } else {
                this.sliced = data[0].split('|');
                this.$root = data.root;
                this.$next = this.$root.find('.bx-next');
                this.$prev = this.$root.find('.bx-prev');
                this.$pager = this.$root.find('.bx-pager');
                this.$bxSlider = data.bxSlider;

                this.currentSlide = 0;
                //this.sliced[1] = "slideShow";
                oThis.setHandlers();
            }
        },
        setHandlers: function () {
            var oThis = this;
            this.$next.on('click', function () {
                if (!$(this).hasClass('.disabled')) {
                    oThis.gtmNextPrev('Next');
                }
            });

            this.$prev.on('click', function () {
                if (!$(this).hasClass('.disabled')) {
                    oThis.gtmNextPrev('Previous');
                }
            });

            this.$pager.on('click', function () {
                if (!$(this).hasClass('.active')) {
                    oThis.gtmPager();
                }
            });

            this.$root.on('onTouchMove', function () {
                oThis.gtmTouch();
            });
        },
        getMainSlide: function () {
            this.currentSlide = this.$bxSlider.getCurrentSlide();
            if (this.$root.find('.col').eq(this.currentSlide + 1).find('.title').attr('data-text') !== undefined) {
                this.contentText = this.$root.find('.col').eq(this.currentSlide + 1).find('.title').attr('data-text');
            } else {
                this.contentText = "";
            }
        },
        gtmNextPrev: function (dir) {
            var oThis = this;
            this.getMainSlide();
            this.data.dir = dir;
            oThis.sliced[2] = dir;
            oThis.sliced[3] = this.contentText;
            oThis.sliced[5] = this.currentSlide + 1;
            oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', oThis.sliced[5]);
            setTrigger(oThis.data);

        },
        gtmPager: function () {
            var oThis = this;
            this.getMainSlide();
            oThis.sliced[2] = "Pager";
            oThis.sliced[3] = this.contentText;
            oThis.sliced[5] = this.currentSlide + 1;
            oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', oThis.sliced[5]);
            setTrigger(oThis.data);
        },
        gtmTouch: function () {
            var oThis = this;
            this.getMainSlide();
            oThis.sliced[2] = "Touch";
            oThis.sliced[3] = this.contentText;
            oThis.sliced[5] = this.currentSlide + 1;
            oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', oThis.sliced[5]);
            setTrigger(oThis.data);
        }
    }


    gtmModule.video = function (data) {
        console.log(data);
        this.init(data)
    };

    gtmModule.video.prototype = {
        init: function (data) {
            var oThis = this;
            this.data = data[0];
            if (this.data == undefined) {
                return false;
            } else {
                this.sliced = data[0].split('|');
                this.$root = data.root;
                this.idVideo = this.$root.attr('id');
                this.$bigplay = this.$root.parents('.video-js').find('.vjs-big-play-button');
                this.$switchbtn = this.$root.parents('.video-js').find('.vjs-play-control');

                //this.sliced[1] = "slideShow";
                oThis.setHandlers();
            }
        },
        setHandlers: function () {
            var oThis = this;
            this.$bigplay.on('click', function () {
                oThis.gtmPlayStop('Play');
            });

            this.$switchbtn.on('click', function () {
                if (!$(this).hasClass('vjs-paused')) {
                    oThis.gtmPlayStop('Pause');
                } else {
                    oThis.gtmPlayStop('Play');
                }
            });
        },
        gtmPlayStop: function (dir) {
            var oThis = this;
            this.data.dir = dir;
            oThis.sliced[2] = 'Video::' + dir;
            var videoName = oThis.sliced[3];
            var view = Math.round((oThis.$root.get(0).currentTime * 100) / oThis.$root.get(0).duration);

            if (dir == "Pause") {
                oThis.sliced[3] = videoName + ':' + view.toString() + '%';
            } else {
                oThis.sliced[3] = videoName;
            }
            oThis.sliced[4] = '';
            oThis.sliced[5] = '';
            oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', oThis.sliced[5]);

            setTrigger(oThis.data);

        }
    }


    gtmModule.vue360 = function (data) {
        console.log(data);
        this.init(data)
    };

    gtmModule.vue360.prototype = {
        init: function (data) {
            var oThis = this;
            this.data = data[0];
            if (this.data == undefined) {
                return false;
            } else {
                this.sliced = data[0].split('|');
                this.$root = data.root;

                //this.sliced[1] = "slideShow";
                oThis.setHandlers();
            }
        },
        setHandlers: function () {
            var oThis = this;
            this.$root.on('click', function () {
                oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', oThis.sliced[5]);

                setTrigger(oThis.data);

                oThis.$root.unbind('click');
            });

        }
    }



    /*gtmModule.dragnchange = function(data) {
     this.init(data)
     };
     gtmModule.dragnchange.prototype = {
     init: function(data) {
     var oThis = this;
     oThis.data = data[0];
     
     if (this.data == undefined) {
     return false;
     } else {
     oThis.sliced = data[0].split('|');
     
     oThis.$root = data.root;
     
     
     oThis.$root.find('.drag').on('mouseup', function() {
     var leftPercent = parseInt(this.style.left.substr(0, (this.style.left.length-1) ) );
     
     if (leftPercent < 50) {
     oThis.eventName = oThis.sliced[2];
     oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[4], '|', oThis.sliced[5], '|', oThis.sliced[6]);
     setTrigger(oThis.data);
     
     } else if ( leftPercent > 50 ) {
     oThis.eventName = oThis.sliced[3]
     oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|',oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[5], '|', oThis.sliced[6]);
     setTrigger(oThis.data);
     
     }
     });
     }
     }
     }*/

    var newgtmattrListener = function (e) {
        var $this = $(this);
        if ($this.attr('data-gtm-init') != '1') {
            if ($this.attr('data-gtm') != undefined) {
                try {
                    var data = $this.attr('data-gtm').replace(/\'/g, '"');
                    $this.attr('data-gtm-init', '1');
                    var oData = {
                        0: data,
                        'root': $this
                    }

                    new gtmModule.clickable(oData);

                } catch (err) {
                }

                // console.log('data-gtm')   


            }

            if ($this.attr('data-gtm-js') != undefined) {
                //  console.log('data-gtm-js')   
                try {
                    var data = $this.attr('data-gtm-js');

                    oData = $.parseJSON(data);
                    oData.root = $this;



                    if (oData.type !== 'slider' && oData.type !== 'jColors' && gtmModule[oData.type] !== undefined) {


                        $this.attr('data-gtm-init', '1');

                        new gtmModule[oData.type](oData);
                    }
                } catch (err) {
                }
            }
        }
    };

    $(document).on('newgtmattr', '[data-gtm], [data-gtm-js]', newgtmattrListener);

    gtmCit.initNewGTM = function () {
        $('[data-gtm], [data-gtm-js]').each(function () {
            $(this).trigger('newgtmattr');
        });
    };

    gtmCit.initjColors = function (obj) {
        var $root = $(obj.context),
                data = $root.attr('data-gtm-js');

        if (data !== undefined && $root.attr('data-gtm-init') != '1') {
            data = data.replace(/\'/g, '"'),
                    oData = $.parseJSON(data);
            oData.root = $root;
            oData.bxSlider = obj;

            $root.attr('data-gtm-init', '1');
            new gtmModule[oData.type](oData);
        }
    }


    gtmCit.initSlider = function (obj) {
        var $root = $(obj.context),
                data = $root.attr('data-gtm-js');

        if (data !== undefined && $root.attr('data-gtm-init') != '1') {
            data = data.replace(/\'/g, '"'),
                    oData = $.parseJSON(data);
            oData.root = $root;
            oData.bxSlider = obj;

            $root.attr('data-gtm-init', '1');
            new gtmModule[oData.type](oData);
        }
    }

    gtmCit.initVideo = function (obj) {

        var $root = $(obj.context),
                data = $root.attr('data-gtm-js');

        if (data !== undefined && $root.attr('data-gtm-init') != '1') {
            data = data.replace(/\'/g, '"');
            console.log(data),
                    oData = $.parseJSON(data);
            console.log(oData);
            oData.root = $root;

            $root.attr('data-gtm-init', '1');
            new gtmModule[oData.type](oData);
        }

    }

    gtmCit.initVue360 = function (obj) {

        var $root = $(obj.context),
                data = $root.attr('data-gtm-js');

        if (data !== undefined && $root.attr('data-gtm-init') != '1') {
            data = data.replace(/\'/g, '"');
            console.log(data),
                    oData = $.parseJSON(data);
            console.log(oData);
            oData.root = $root;

            $root.attr('data-gtm-init', '1');
            new gtmModule[oData.type](oData);
        }

    }

    window.addEventListener('load', function () {

        new gtm_listener.viewed(document.querySelector('#gtm-visibility-test'), function () {
        }, 100);

        new gtm_listener.dragged(document.querySelector('.dragnchange .drag'), function () {
        }, 100);

    }, false);


    /* iPad dummy events to activate :hover on all elements */
    // $('body').bind(STARTEVENT,function(){  });

    /* Google Tag Manager Functions  */
    var gtm_listener = {
        start: ('ontouchstart' in window) ? 'touchstart' : 'mousedown',
        move: ('ontouchstart' in window) ? 'touchmove' : 'mousemove',
        end: ('ontouchstart' in window) ? 'touchend' : 'mouseup',
        viewed: function (element, callback, degree) {

            if (!element)
                return;

            if ($)
                $(element).unbind('click').parents('[data-gtm]').unbind('click');

            /* Vars */
            var that = this,
                    check = function (e) {

                        /* Get viewport and element offsets */
                        var st = window.pageYOffset || document.body.scrollTop,
                                vh = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight,
                                et = Math.round(gtm_listener.top(that._el)) - Math.round(vh * (100 - that._degree) / 100);

                        /* Callback if eligible and remove event listener */
                        if (st > et) {
                            that._callback.call(element);
                            window.removeEventListener('scroll', check, false)
                        }
                        ;

                    };

            /* Properties */
            that._el = element;
            that._degree = degree || 0;
            that._callback = callback || function () {
            };

            /* Events */
            window.addEventListener('scroll', check, false)

        },
        top: function (obj) {
            var top = 0;
            if (obj.offsetParent) {
                while (obj.offsetParent) {
                    top += obj.offsetTop;
                    obj = obj.offsetParent;
                }
                ;
            } else if (obj.y) {
                top += obj.y;
            }
            ;
            return top;
        },
        dragged: function (element, callback, x, y) {

            if (!element)
                return;

            if ($)
                $(element).unbind('click').parents('[data-gtm]').unbind('click');

            /* Vars */
            var that = this,
                    set = function (e) {

                        /* Events */
                        document.addEventListener(gtm_listener.move, check, false);
                        document.addEventListener(gtm_listener.end, stop, false);

                    },
                    check = function (e) {

                        /* Current */
                        var dx = Math.abs(that._x - that._el.offsetLeft),
                                dy = Math.abs(that._y - that._el.offsetTop);

                        if ((dx > that._dx && 0 < that._dx) || (dy > that._dy && 0 < that._dy))
                            stop(null, true);

                    },
                    stop = function (e, kill) {

                        document.removeEventListener(gtm_listener.move, check, false);
                        document.removeEventListener(gtm_listener.end, stop, false);

                        if (!kill)
                            return;

                        that._el.removeEventListener(gtm_listener.start, set, false);
                        that._callback.call(element);

                    };

            /* Properties */
            that._el = element;
            that._dx = x || 0;
            that._dy = y || 0;
            that._callback = callback || function () {
            };

            /* Origin */
            that._x = that._el.offsetLeft;
            that._y = that._el.offsetTop;

            /* Events */
            that._el.addEventListener(gtm_listener.start, set, false)

        }

    };

})(jQuery, window, document, window.gtmCit = window.gtmCit || {});
;var indiceConfigurateur;
var selectionConfigurateur;
var loader = new Loader();
var lastRelease = '20141203'; //claire
var IframeId;

/*
 * Init carte Point de vente 
 * 
 */

//var googlemapAPI = 'https://maps.googleapis.com/maps/api/js?client=&sensor=true&libraries=places';

(function($,win,doc,Cit){

    var $doc = $(document);
    Cit.$globalLocators;

    Cit.SetPDV = function ($root){ this.init($root); };
    Cit.SetPDV.prototype = {
        init:function($root){
            this.$root = $root;
            this.$mapLayer = $root.next('.locations');
            this.loader = new Loader($root);
            this.loaderMap = new Loader($root.parent().find('.locations'));
            this.pos = null;

            this.setHandlers();
            this.setLocator();
        },
        setHandlers:function(){
            var oThis = this;
            this.$root.on('busy',function(){
                oThis.loader.show();
            });
            this.$root.on('notbusy',function(){
                oThis.loader.hide();
            });
        },
        showVnResult:function(xhr){
            if(xhr.responseJSON !== undefined){

                var $content = $('#'+xhr.responseJSON[0].id);

                $('html, body').animate({
                    scrollTop:$content.offset().top - 100
                }, 'slow');
            }
            this.loaderMap.hide();
            this.loader.hide();
        },
        setLocator:function(){
            var oThis = this;
            this.$root.gLocator({
                latest:{
                    lat:(oThis.pos) ? oThis.pos.latitude : null,
                    lng:(oThis.pos) ? oThis.pos.longitude : null,
                    zoom:12,
                    filters:null,
                    type:'geo'
                },
                onLoad:function(){
                    var that = this;

                    if(oThis.$root.hasClass('locatorPDV')){
                        oThis.$mapLayer.show();
                    }
                    //oThis.$root.addClass('initDone');
                    /* if has bookmark button */
                    $(that.element).find('.bookmarks a').click(function(e){
                        e.preventDefault();
                        that._booked = this;

                        // RÃƒÂ©cupÃƒÂ©ration des points de vente favoris
                        $.ajax({
                            url: '/_/Layout_Citroen_PointsDeVente/ajaxPdvBookmarkGet',
                            dataType: 'json',
                            success: function(data){
                                var source = null;

                                // User connectÃƒÂ© => on utilise les favoris de son compte
                                if( data.loggedin == true  && data.favoris_db.favoris_vn != null && typeof data.favoris_db.favoris_vn != 'undefined' ){
                                    source = data.favoris_db;
                                }

                                // User non connectÃƒÂ© => on utilise les favoris dÃƒÂ©fini en cookie
                                else if( data.loggedin == false && data.favoris_cookie.favoris_vn != null && typeof data.favoris_cookie.favoris_vn != 'undefined' ){
                                    source = data.favoris_cookie;
                                }

                                // Parcours
                                var pdv_favori = [];
                                for(var i in source){
                                    if( source[i] != '' && source[i] != NaN && source[i] != undefined && source[i] != null ){
                                        pdv_favori.push(source[i]);
                                    }
                                }
                                that.list(pdv_favori);
                            }
                        });
                    });

                },
                onFilter:function(){},
                onList:function(){

                    /* If has custom origin */
                    if(this._booked){
                        this.$locations.find('.stores-results').html(t('VOS_FAVORIS'));
                    }
                    this._booked = null;

                },
                onItemClick:function(storeId, storeRRDI, lat, lng){
                    if(oThis.$root.hasClass('locatorVN')){
                        try{
                            $(document).on('ajaxComplete ',function(event, xhr, settings){
                                oThis.showVnResult(xhr);
                                $(document).off('ajaxComplete');
                            });
                            oThis.loader.show();
                            oThis.loaderMap.show();
                            getCarStock(storeId, storeRRDI, lat, lng);
                        } catch(e){ }
                    }
                },
                onDetails:function(){
                    $(this.settings.dom).parent().find('.store .folder').each(folder.build);
                },
                onGeoloc:function(){},
                onGeolocError:function(){
                    /* Display prompt, should use an HTML template */
                    if(promptPop){
                        promptPop(t('GEOLOCALISATION_IMPOSSIBLE_VEUILLEZ_VERIFIER_QUE_VOTRE_NAVIGATEUR_ACCEPTE_CETTE_FONCTIONNALITE_ET_SI_ELLE_EST_ACTIVEE'));
                    }

                }
            });
        }
    }

    Cit.initLocator = function($target){

        var $locators;
        if($target !== undefined) {
            $locators = $target;
        } else {
            $locators = $('.locatorPDV, .locatorVN, .locatorMesCS');
        }

        Cit.$globalLocators = $locators;

        if($locators !== undefined){
            // Charge l'API que si il y a un contenu de carte dans le DOM qui n'est (ni en classe secret / locator).
            if ($locators.length>0)
            {
                try
                {
                    if (google) { Cit.initLocatorPDV(); }
                }
                catch(e)
                {
                    // injecte le script que si celui-ci n'est dÃƒÂ©jÃƒ  pas chargÃƒÂ©.
                    var script = document.createElement('script');
                    script.type = 'text/javascript';
                    script.src = googlemapAPI + '&callback=Cit.initLocatorPDV';
                    document.body.appendChild(script);
                }
            }
        }
    }

    Cit.initLocatorPDV = function() {
        if(Cit.$globalLocators!== undefined){
            Cit.$globalLocators.each(
                function(){
                    new Cit.SetPDV($(this));
                }
            );
        }
    }

    Cit._checkLocatorMap = function($content){
        Cit.initLocator($content.find('.locator'));
    }



    /* Google map dealers details*/
    if(typeof $('#map_dealers_details').gLocator === 'function'){
        $('#map_dealers_details').gLocator({
            onLoad:function(instance){},
            onFilter:function(){},
            onList:function(){},
            onItemClick:function(storeId){},
            onDetails:function(){}
        });
    }

})( jQuery, window, document, window.Cit = window.Cit || {} );


/**
 * PATCH PELICAN AJAX
 */
Pelican.ajax.prototype.call = function() {
    // PATCH START
    var self = this,
        elem = $('#caracteristiques-equipements'),
        obj ={ '>Standard<': '>'+elem.data('localise-standard')+'<', '>Option<' : '>'+elem.data('localise-option')+'<', '>Non disponible<': '>' + elem.data('localise-nondispo') + '<' };
    // PATCH END
    $.ajax({
        data: this.getData(this.data) ? this.getData(this.data) : "&values[]=null",
        url: "/library/Pelican/Ajax/Adapter/Jquery/public/?route=" + this.url,
        type: this.type ? this.type : "GET",
        dataType: this.dataType ? this.dataType : 'json',
        timeout: this.timeout ? this.timeout : 60000,
        error: function(xhr, ajaxOptions, thrownError) {
            if (this.debug == true) {
                alert('Error processing request: '
                    + '/library/Pelican/Ajax/Adapter/Jquery/public/?route=' + func,
                    10000);
                alert(xhr.responseText);
            }
            self.error(xhr, ajaxOptions, thrownError);
        },
        success: function(data, textStatus, jqXHR) {
            self.beforeAction(data, textStatus, jqXHR);
            if (data) {
                $.each(data, function() {
                    if(typeof this.id != "undefined" && this.id === 'caracteristiques-equipements') {
                        var str = this.value;
                        if( obj[x] !== "" )
                            for (var x in obj) { str = str.replace(new RegExp(x, 'g'), obj[x])}
                    }else{
                        var str = this.value;
                    }
                    switch (this.cmd) {
                        case 'assign':
                        {
                            if (this.attr.toLowerCase() == 'innerhtml') {
                                $('#' + this.id).html(str);
                            } else {
                                $('#' + this.id).attr(this.attr, this.value);
                            }
                            break;
                        }
                        case 'append':
                        {
                            $('#' + this.id).append(this.value);
                            break;
                        }
                        case 'prepend':
                        {
                            $('#' + this.id).prepend(this.value);
                            break;
                        }
                        case 'replace':
                        {
                            var ori = $('#' + this.id).attr(this.attr);
                            $('#' + this.id).attr(this.attr,
                                ori.replace(this.search, this.value));
                            break;
                        }
                        case 'clear':
                        {
                            $('#' + this.id).removeAttr(this.attr);
                            break;
                        }
                        case 'remove':
                        {
                            $('#' + this.id).remove();
                            break;
                        }
                        case 'redirect':
                        {
                            document.location.href = this.url;
                            /*
                             * this.'delay';
                             */
                            break;
                        }
                        case 'reload':
                        {
                            document.location.reload();
                            break;
                        }
                        case 'alert':
                        {
                            alert(this.value);
                            break;
                        }
                        case 'script':
                        {
                            delegate(this.value);
                            break;
                        }
                    }
                });
            }
            self.afterAction(data, textStatus, jqXHR);
            self.success(data, textStatus, jqXHR);

            gtmCit.initNewGTM();
        }

    });
};


var loadGoogle_API = false;

$(document).ready(function()
{



       // Chargement Points de vente.
    var  checklocator= $('.locatorPDV, .locatorVN, .locatorMesCS');
    if (checklocator !== undefined) {  if (checklocator.length>0)  loadGoogle_API = true; }

    // Chargement si StockBE
    if ($("form[name=newCarBelgium]").find('input[name="address"]').length ){ loadGoogle_API = true; };
    
    if (loadGoogle_API)
    {
        if(typeof google === 'undefined'){
            var script = document.createElement( 'script' );
            script.type = 'text/javascript';
            script.src = googlemapAPI+"&callback=DomReadyLoad";
            document.body.appendChild(script);  
        }
    }       
    else    
    {
        DomReadyLoad();
		GOOGLE_MAPS_LOADED =false;
    }


    if($('#iframeContainer').attr('data-iframe') != ""){
        var loaderIframe = new Loader('.iframeClear');
        //on masque les iframes
        $('#iframeContainer').hide('0', function(){
            loaderIframe.show(LoadingKey, false);
        });

        //on affiche l'iframe avec filtre sur class et id
        $('#iframeContainer').load(function() {
            var hideData = '';
            if($('#iframeContainer').attr('data-iframe')){
                var hideData = $('#iframeContainer').attr('data-iframe');
            }
            $('#iframeContainer').contents().find(hideData).hide("0", function(){
                $('#iframeContainer').show();
                loaderIframe.hide();
            });

        });
    }
	$('iframe').each(function() { 
		var src= $(this).attr('src');
		if (typeof(src) != "undefined" && src.indexOf("youtube") !== -1  ) {
			if(src.indexOf("?") !== -1){
				var Newsrc = src+'&wmode=transparent';
				$(this).attr('src',Newsrc);
		   }else{
			   var NewsrcWithoutParam = src+'?wmode=transparent';
			  $(this).attr('src',NewsrcWithoutParam);
		   }
		}
	}); 
	
});



function DomReadyLoad()
{
    // Affichage de la bulle de scroll (si elle est présente dans le DOM)
    scrollIncite();

    //ActualitÃƒÂ©
    seeMoreNews();
    filterNews();
    //Car selector
    addToCompare();
    maskBtnComparateur();
    //Vehicules neufs
    seeMoreCars();

    if($.fn.gLocator){  Cit.initLocator(); }

    getCarStockBE();
    //RÃƒÂ©sultats de recherche
    autoCompleteSearch();
    seeMoreResults();
    //Accessoires
    seeMoreAccessories();
    //Home
    // launchInstagram(); CPW-4272 - Trigger in main.js
    //Comparateur
    reinitComparateur();
    replaceToCompare();
    onMyProjectPage();
    //Iframe
    loadIframe();
    gtmFormPush();
    // Gestion des onglets    
    $('.data-onglet').each(function() {
        onglet = $(this).data('onglet');
        valeur = $(this).children().detach();
        valeur.appendTo('.onglet-' + onglet);
        $(this).remove();
    });
    // Gestion des Accordeon Web et Mobile
    $('.tog').each(function() {
        valeur = $(this).children().detach();
        toggle = $(this).data('group');
        if (toggle) {
            $('#toggle' + toggle).append(valeur);
        }
        $(this).remove();
    });
    //Languette pro - client
    if ($('.languettePro').length > 0) {
        var languettePro = $('.languettePro').children().detach();
        languettePro.prependTo('.body');
        $('.stickyplaceholder,.sticker,.listickholder,.stripholder').each(sticky.build);
        $('.languettePro').remove();
    }
    if ($('.languetteClient').length > 0) {
        var languetteClient = $('.languetteClient').children().detach();
        languetteClient.prependTo('.body');
        $('.stickyplaceholder,.sticker,.listickholder,.stripholder').each(sticky.build);
        $('.languetteClient').remove();
    }
    languettePerso();
    // Gestion des tranches Parentes/Enfants
    // on rÃƒÂ©cupÃƒÂ©re toutes les tranches actives c'est Ãƒ  dire toutes les tranches parentes
    $('.parentActif').each(function() {
        var idParent = this.id;
        var idTrancheParent = '#' + this.id;
        var idEnfant = idParent.split('_');
        classeTrancheEnfant = '.trancheEnfant' + idEnfant[1];
        // on rÃƒÂ©cupÃƒÂ©re toutes les tranches enfants associÃƒÂ©es Ãƒ  une tranche parente
        $(classeTrancheEnfant).each(function() {
            var idTrancheEnfant = '#' + this.id;
            valeur = $(idTrancheEnfant).children().detach();
            valeur.appendTo(idTrancheParent);
            $(this).remove();
            //On remplit la div parent avec tous ses enfants
            //$(idTrancheParent).html($(idTrancheParent).html() + $(idTrancheEnfant).html());
        });
    });
    $('footer .site-version').bind('click', function(e) {
        e.preventDefault();
        _version = $(this).data('version');
        if (_version == 'mobile') {
            callAjax({
                url: "Layout_Citroen_Global_Footer/versionMobile"
            });

        } else {
            callAjax({
                url: "Layout_Citroen_Global_Footer/versionDesktop"
            });
        }
    });
    //Formulaires
    initFormulaire();

    // include fichier demo.js

    /* Sortable */
    if($.fn.sortable){
        $('.listeVehicules').sortable({
            items:'.selectedCar',
            toleranceType:'pointer',
            revert:true,
            update:function(e,ui){
                var $root = ui.item.parents('.listeVehicules'),
                    $field = $root.prev(),
                    values = [];

                $root.find('input.vid').each(function(){
                    values.push(this.value);
                });

                //$field.val(JSON.stringify(values)).trigger('change');

                /* Update wordings */
                var original = null;
                var target = null;
                var selection =new Array();
                $(this).find('.selectedCar').each(function(i,item){

                    item = $(item);
                    selection.push($(item).find('input.vid').val());

                    var index = i,
                        $label = $(this).find('.sortLabel'),
                        current = $label.html(),
                        rplc = current.replace(/^(.*)([0-9]{2})(.*)$/gi,'$10'+index+'$3');

                    $label.html(rplc);
                });



                callAjax({
                    url:'Layout_Citroen_MonProjet_SelectionVehicules/changeOrderAjax',
                    data:{
                        items:selection,
                    },
                    success: function(){
                        location.reload();
                    }
                });

            }
        });
    };

    var search = $('input[name="search"]'),
        placeholder = search.val();


    search.focus(function(){
        search.removeClass('placeholder');
    });
    search.blur(function(){
        if(search.val() !== placeholder){
            search.removeClass('placeholder');
        } else {
            search.addClass('placeholder');
        }
    });

    search.on('typeahead:initialized',function(){
        search.addClass('placeholder');
    });

    /* Add to selection effect */
    $('.add2selection').click(function(){

        if(0 < $('.projectAdd').length) return;

        var $o = $(this),
            $d = $('header .projects');

        /* Get buttons positions */
        var origin = $o.offset(),
            dest = $d.offset(),
            time = (origin.top - dest.top)*1.5;

        /* Update to center */
        origin.left += $o.width()/2;
        dest.top += $d.height()/2;
        dest.left += $d.width()/2;

        /* Append */
        $('body').append('<div class="projectAdd"></div>').find('.projectAdd').fadeIn(125,function(){
            var $t = $(this);
            $t.css(origin).animate(dest,time,'linear',function(){
                $t.fadeOut(125,function(){
                    $t.remove();
                });
            });
        });

    });dropdownstack
}

/**
 * ACTUALITES
 **/
//MÃƒÂ©thode servant au binding le clic sur le bouton permettant d'afficher plus de news
function seeMoreNews() {
    $("#seeMoreNews a").bind('click', function(e) {
        e.preventDefault();
        displayMoreNews('more');
    });
}

function displayMoreNews(typeAff) {
    var iMin = parseInt($('#iCount').val());
    var iPid = parseInt($('#pid').val());
    callAjax({
        url: "Layout_Citroen_Actualites_Galerie/moreNews",
        async: false,
        data: {
            'iMin': iMin,
            'typeAff': typeAff,
            'iPid': iPid
        },
        success: function(e) {
            ReinitializeAddThis();

            lazy.set($('#allActu img.lazy'));
            gtmCit.initNewGTM();
        }
    });
}

//MÃƒÂ©thode permettant de submitter le formulaire de filtre au changement d'un des filtres
function filterNews() {
    $('input[name="themeId"]').click(function() {
        $('#allActu').html('<div class="row of2 item zoner"></div>');
        $('#seeMoreNews').hide();
        var loader = new Loader($('#allActu'));
        loader.show(LoadingKey, false);
        var sFormName = $(this).parents("form").attr('id');
        var pid = $('input[name="pid"]').val();
        var iTheme = $(this).val();
        if (iTheme == '0') {
            /*var urlPage = document.URL;
             var urlNoParams = urlPage.split('?');
             window.location.href = urlNoParams[0];*/
            callAjax({
                url: "Layout_Citroen_Actualites_Galerie/filterNews",
                async: false,
                data: {
                    'iPid': pid,
                    'iTheme': iTheme,
                    'iMin': 1
                }
            });
        } else {
            //$('#'+sFormName).submit();

            callAjax({
                url: "Layout_Citroen_Actualites_Galerie/filterNews",
                async: false,
                data: {
                    'iPid': pid,
                    'iTheme': iTheme,
                    'iMin': 1
                }
            });
        }
        lazy.set($('#allActu img.lazy'));
        gtmCit.initNewGTM();
    });
}
/**
 * VEHICULES NEUFS
 **/
//MÃƒÂ©thode servant au binding le clic sur le bouton permettant d'afficher plus de vehicules neufs
function seeMoreCars() {
    $("#seeMoreCars a").on('click', function(e) {
        e.preventDefault();
        displayMoreCars('more');
    });
}
//MÃƒÂ©thode affichant les vehicules supplÃƒÂ©mentaires via un appel ajax, on rÃƒÂ©cupÃƒÂ¨re un compteur dans un champs cachÃƒÂ© sur la page
function displayMoreCars(typeAff) {
    var iMin = parseInt($('#iCount').val());
    var iZid = parseInt($('#zidVN').val());
    var iZorder = parseInt($('#zorderVN').val());
    var iAreaId = parseInt($('#zareaVN').val());
    var zType = String($('#zType').val());
    var lng = ($('#lng').length > 0) ? $('#lng').val() : 0;
    var lat = ($('#lng').length > 0) ? $('#lat').val() : 0;
    var storeId = ($('#storeId').length > 0) ? $('#storeId').val() : 0;
    var storeRRDI = ($('#storeRRDI').length > 0) ? $('#storeRRDI').val() : '';
    var iPageId = $('#form_page_id').val();
    callAjax({
        url: "Layout_Citroen_VehiculesNeufs/moreCars",
        async: true,
        data: {
            'iMin': iMin,
            'typeAff': typeAff,
            'iZid': iZid,
            'iZorder': iZorder,
            'iAreaId': iAreaId,
            'zType': zType,
            'iLng': lng,
            'iLat': lat,
            'storeId': storeId,
            'storeRRDI': storeRRDI,
            'form_page_id':iPageId
        },
        success: function(){
            loader.hide();
            gtmCit.initNewGTM();
        }
    });

}
//MÃƒÂ©thode affichant les vehicules liÃƒÂ© Ãƒ  un point de vente (FRANCE)
function getCarStock(id, rrdi, lat, lng) {
    var iZid = parseInt($('#zidVN').val());
    var iZorder = parseInt($('#zorderVN').val());
    var iAreaId = parseInt($('#zareaVN').val());
    var sLcdv = $('#lcdvVN').val();
    var sSkin = $('#ZONE_SKIN').val();
    var iPageId = $('#form_page_id').val();
    $('#storeId').val(id);
    $('#storeRRDI').val(rrdi);
    $('#lat').val(lat);
    $('#lng').val(lng);
    var loader = new Loader($('#resultVN')); // Instantiation de lÃ¢â‚¬â„¢objet
    loader.show(LoadingKey, false);
    callAjax({
        url: "Layout_Citroen_VehiculesNeufs/france",
        async: true,
        data: {
            'storeId': id,
            'storeRRDI': rrdi,
            'iZid': iZid,
            'iZorder': iZorder,
            'iAreaId': iAreaId,
            'lcdv': sLcdv,
            'lat': Math.round(lat * 100) / 100,
            'long': Math.round(lng * 100) / 100,
            'ZONE_SKIN': sSkin,
            'form_page_id':iPageId
        },
        success: function(){
            loader.hide();
            gtmCit.initNewGTM();
            // HERITAGE TOOLTIP POUR CARSTORE CP/VILLE

            $(".tooltip").each(tooltip.build);
        }
    });
}
//MÃƒÂ©thode affichant les vehicules liÃƒÂ© Ãƒ  une ville/dÃƒÂ©partement (BELGIQUE)
function getCarStockBE() {
    //console.log('getCarStockBe');
    var iZid = parseInt($('#zidVN').val());
    var iZorder = parseInt($('#zorderVN').val());
    var iAreaId = parseInt($('#zareaVN').val());
    var sCountryCode = $('#countryCode').val();
    var iMaxDistance = $('#maxDistance').val();
    var iPageId = $('#form_page_id').val();
    var groupvnlowkm = $('#groupvnlowkm').val();
    var sLcdv = $('#lcdvVN').val();
    var sSkin = $('#ZONE_SKIN').val();
    var input = $("form[name=newCarBelgium]").find('input[name="address"]').get(0);
    var loader = new Loader($('#resultVN')); // Instantiation de lÃ¢â‚¬â„¢objet
    if ($("form[name=newCarBelgium]").find('input[name="address"]').length ){
        var autocomplete = new google.maps.places.Autocomplete(input, {
            componentRestrictions: {
                country: sCountryCode
            }
        });
        google.maps.event.addListener(autocomplete, 'place_changed', function() {

            var place = autocomplete.getPlace();
            //me.fitSearch(place);

        });
    }
    $("form[name=newCarBelgium]").submit(function(e) {
        e.preventDefault();
        loader.show(LoadingKey, false);
        $("form[name=newCarBelgium]").trigger('busy');
        var geocoder = new google.maps.Geocoder();
        var string = $(this).find("input[name=address]").val();
        var sSkin = $('#ZONE_SKIN').val();
        geocoder.geocode({'address': string}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                var location = results[0].geometry.location
                var lat = location.lat();
                var lng = location.lng();
                $('#lat').val(lat);
                $('#lng').val(lng);
                callAjax({
                    url: "Layout_Citroen_VehiculesNeufs/belgique",
                    async: false,
                    data: {
                        'lat': Math.round(lat * 100) / 100,
                        'long': Math.round(lng * 100) / 100,
                        'iZid': iZid,
                        'iZorder': iZorder,
                        'iAreaId': iAreaId,
                        'lcdv': sLcdv,
                        'ZONE_SKIN': sSkin,
                        'iMaxDistance': iMaxDistance,
                        'form_page_id':iPageId,
                        'groupvnlowkm':groupvnlowkm
                    },
                    success: function(){
                        loader.hide();
                        gtmCit.initNewGTM();
                        // HERITAGE TOOLTIP POUR CARSTORE CP/VILLE
                        $(".tooltip").each(tooltip.build);
                    }
                });
            }
            ;
        });
    });
    /* Enable geolocation if enabled */
    if (navigator.geolocation) {

        /* Backup timer because browser doesen't trigger error when prompt is simply closed */
        var geolocTimerVn = 0,
            geolocBackupVn = function() {

                clearTimeout(geolocTimerVn);
                $("form[name=newCarBelgium]").trigger('notbusy');
                if (promptPop) {
                    promptPop(t('GEOLOCALISATION_IMPOSSIBLE_VEUILLEZ_VERIFIER_QUE_VOTRE_NAVIGATEUR_ACCEPTE_CETTE_FONCTIONNALITE_ET_SI_ELLE_EST_ACTIVEE'));
                }
            };
        $("form[name=newCarBelgium]").find('.geoloc').unbind('click');
        $("form[name=newCarBelgium]").find('.geoloc').click(function(e) {
            $("form[name=newCarBelgium]").trigger('busy');

            geolocTimerVn = window.setTimeout(function() {
                geolocBackupVn();
            }, 10000);

            navigator.geolocation.getCurrentPosition(function(pos) {
                loader.show(LoadingKey, false);
                $("form[name=newCarBelgium]").trigger('busy');
                clearTimeout(geolocTimerVn);
                $('#lat').val(pos.coords.latitude);
                $('#lng').val(pos.coords.longitude);
                var sSkin = $('#ZONE_SKIN').val();
                callAjax({
                    url: "Layout_Citroen_VehiculesNeufs/belgique",
                    async: false,
                    data: {
                        'lat': Math.round(pos.coords.latitude * 100) / 100,
                        'long': Math.round(pos.coords.longitude * 100) / 100,
                        'iZid': iZid,
                        'iZorder': iZorder,
                        'iAreaId': iAreaId,
                        'ZONE_SKIN': sSkin,
                        'form_page_id':iPageId
                    },
                    success: function(e) {
                        $("form[name=newCarBelgium]").trigger('notbusy');
                        clearTimeout(geolocTimerVn);
                        gtmCit.initNewGTM();
                        // HERITAGE TOOLTIP POUR CARSTORE CP/VILLE
                        $(".tooltip").each(tooltip.build);
                    }
                });

            }, geolocBackupVn);

        }).css({cursor: 'pointer'});
    } else {
        $("form[name=newCarBelgium]").find('.geoloc').css({opacity: 0.25});
    }
}
function timeOutCars() {
}

//MÃƒÂ©thode permettant d'ajouter des vehicules en session pour le comparateur
function addToCompare() {
    $('a.addtoCompare').on('click', function(e) {

        e.preventDefault();
        var invoker = $(this).attr('data-source')
        var vehiculeId = $(this).attr('id');
        var finitionId = $(this).attr('rel');
        var nomFinition = $(this).attr('data-value');
        var urlAjax = "";
        var _vehicule = null;


        if (invoker != '' && invoker != null) {

            switch (invoker) {
                case 'CARSELECTOR':
                    urlAjax = "Layout_Citroen_CarSelector_Resultats/addToCompare";
                    callAjax({
                        url: urlAjax,
                        async: false,
                        data: {
                            'invoker': invoker,
                            'vehiculeId': vehiculeId,
                        },
                    });
                    break;
            }


        } else {

            if (showRoomComparateur.length >= 3) {
                promptPop(_addToComparator_KO);
            }
            else if (typeof (finitionId) != 'undefined') {
                for (i = 0; i < 3; i++) {
                    if ($('#select' + i + 'b').val() == 0) {
                        var ulSelect = $('#select' + i + 'b').next().find('ul.select');
                        var t = false;
                        $('#select' + i + 'b').next().find('ul.select li a').each(function(e) {

                            if ($(this).attr('data-value') == finitionId + '#' + vehiculeId) {
                                t = true;
                                $('#select' + i + 'b').val('1');

                                if (showRoomComparateur.length < 3) {
                                    $(this).click();
                                    $(this).click();
                                    promptPop(_addToComparator_OK);
                                }
                            }
                        });
                        if (t == false)
                        {

                            $('#select' + i + 'b').val('1');
                            ulSelect.append('<li><a data-value="' + finitionId + '#' + vehiculeId + '" href="#0">' + nomFinition + '</a></li>');
                            $('#select' + i + 'b').next().find('ul.select li a').each(function(e) {
                                if ($(this).attr('data-value') == finitionId + '#' + vehiculeId) {
                                    t = true;
                                    $('#select' + i + 'b').val('1');
                                    //selection
                                    $(this).click();
                                    //validation
                                    $(this).click();
                                    if (showRoomComparateur.length < 3) {
                                        $(this).click();
                                        $(this).click();
                                        promptPop(_addToComparator_OK);
                                    }
                                }
                            });
                        }
                        break;
                    }
                }

            }

        }





        // Marquage GTM
        dataLayer.push({
            vehicleFinition: finitionId,
            vehicleFinitionLabel: nomFinition,
            event: 'click'
        });
    });
}

//MÃƒÂ©thode servant au binding le clic sur le bouton permettant d'afficher plus de vehicules neufs
function maskBtnComparateur() {
    $(".compareBtn").each(function(e) {
        if ($("input[name=trancheComparateur]").length == 0) {
            $(this).css('display', 'none');
        }
    });
}

/**
 * RESULTATS DE RECHERCHE
 **/
//MÃƒÂ©thode gÃƒÂ©rant la fonction d'autocomplÃƒÂ©tion de la recherche
function autoCompleteSearch() {

    $("input[name=search]").not('.autocomplete-off').typeahead({
        remote: '/_/Layout_Citroen_ResultatsRecherche/suggest?term=%QUERY',
        minLength: 3,
        name: 'rechercher'
    });

    $("input[name=search]").not('.autocomplete-off').on("typeahead:selected typeahead:autocompleted",
        function(e, datum) {
            var sFormName = $(this).parents("form").attr('id');
            $('#' + sFormName).submit();
        });
}
//MÃƒÂ©thode servant au binding le clic sur le bouton permettant d'afficher plus de rÃƒÂ©sultats de recherche
function seeMoreResults() {
    $("#seeMoreResults a").bind('click', function(e) {
        e.preventDefault();
        displayMoreResults('more');
    });
}
//MÃƒÂ©thode affichant les rÃƒÂ©sultats supplÃƒÂ©mentaires via un appel ajax, on rÃƒÂ©cupÃƒÂ¨re un compteur dans un champs cachÃƒÂ© sur la page
function displayMoreResults(typeAff) {
    var iStart = parseInt($('#iCount').val());
    var sSearch = $('#searchField').val();
    callAjax({
        url: "Layout_Citroen_ResultatsRecherche/moreResults",
        async: false,
        data: {
            'iStart': iStart,
            'search': sSearch,
            'typeAff': typeAff
        },
        success: function() {

            gtmCit.initNewGTM();
        }
    });
}

/**
 * ACCESSOIRES
 **/
//MÃƒÂ©thode servant au binding le clic sur le bouton permettant d'afficher plus d'accessoires
function seeMoreAccessories() {
    $(".seeMoreAccessories a").bind('click', function(e) {
        e.preventDefault();
        var sContext = $(this).attr('rel');
        displayMoreAccessories(sContext);
    });
}
//MÃƒÂ©thode affichant les accessoires supplÃƒÂ©mentaires via un appel ajax, on rÃƒÂ©cupÃƒÂ¨re un compteur dans un champs cachÃƒÂ© sur la page
function displayMoreAccessories(sContext) {
    var elem = sContext.split('_');
    var iStart = parseInt($('#iCount_' + elem[0] + '_' + elem[1] + '_' + elem[2]).val());
    callAjax({
        url: "Layout_Citroen_Accessoires/moreAccessories",
        async: false,
        data: {
            'iPosition': elem[0],
            'univ': elem[1],
            'ssUniv': elem[2],
            'lcdv6': elem[3],
            'iStart': iStart
        },
        success: function() {
            lazy.load($('img.lazy'));
            gtmCit.initNewGTM();
        }
    });
}
//Fonction permettant d'accepter les cookies sur le site, l'Ajax modifie des
//donnÃƒÂ©es de session indiquant que l'utilisateur Ãƒ  accepter les cookies et qu'il
//n'est plus nÃƒÂ©cessaires d'afficher le bandeau d'information
function acceptCookies(redirectUrl) {
    $.ajax({
        url: '/_/Layout_Citroen_Global_Header/acceptcookies',
        async: true,
        data: {
        },
        success: function(data) {
            if (typeof redirectUrl !== 'undefined') {
                document.location.href = redirectUrl;
            }

        }
    });
    return false;
}

function loadIframe() {
    $('section.clsiframe').each(function() {
        var loader = new Loader($(this).find('.iframeClear')),
            $iframe = $(this).find('iframe#iframeContainer');
        loader.show(LoadingKey, true);
        $iframe.load();
        loader.hide();
    });
}

/** RESEAUX SOCIAUX HOME**/
//MÃƒÂ©thode affichant les feed instagram sur la home
function launchInstagram() {
    var instaFeedId = $('input[name=instaFeedId]').val();
    if (typeof (instaFeedId) != "undefined" && instaFeedId != '') {
        callAjax({
            url: "Layout_Citroen_Home_RemonteesReseauxSociaux/instagram",
            async: true,
            data: {
                "instaFeedId": instaFeedId
            },
        });
    }
}

var selectMotorisation = {
    manage: function() {
        var me = this,
            nextSelector = me.getAttribute('data-value');
        if (0 != me.value) {
            if ('motorisation' == me.getAttribute('data-value')) {
            }
        }
    }
};


/*Simulateur de Finacement*/
/**
 * Pour le simulateur de financement, ajoute la class "off" pour le data-step = 1
 **/
function step1Off()
{
    $("#step1").attr('class', 'parttitle off');
}

var simulateurFinancement = {
    nextStep: function() {
        var aParams = {};
        $($('form#sim-fin').serializeArray()).each(function(index, item) {
            aParams[item.name] = item.value;
        });
        //var iframe = $('#sim_fin_step2_iframe');
        callAjax({
            url: "Layout_Citroen_SimulateurFinancement/step2Ajax",
            data: aParams,
            type: 'post',
            success: function(data) {
                $('#sim_fin_step2_iframe').attr('src', data);

            }
        });

        return; //$('form#sim-fin').submit();
    }
};
/*Outil choix financement*/

var outilChoixFinancement = {
    manage: function() {
        //e.preventDefault();
        var me = $(this);
        data = jQuery.parseJSON(me.attr('data'));
        var loader = new Loader($('#choix_financement'));
        loader.show(LoadingKey, false);
        if (data.p != null) {
            callAjax({
                url: "Layout_Citroen_OutilChoixFinancement/getProduitFinancierAjax",
                data: {
                    "qpid": data.p,
                    "zo": data.zo
                },
                success: function() {
                    loader.hide();
                    gtmCit.initNewGTM();
                }
            });
        } else {
            if (data.q != null) {
                callAjax({
                    url: "Layout_Citroen_OutilChoixFinancement/getQuestionAjax",
                    data: {
                        "id": data.id,
                        "qpid": data.pid
                    },
                    success: function() {
                        $('div#choix_financement div.reponse input').on('click', outilChoixFinancement.manage);
                        gtmCit.initNewGTM();
                        loader.hide();
                    }

                });
            }
        }


    },
    reload: function() {
        var loader = new Loader($('#choix_financement'));
        loader.show(LoadingKey, false);
        callAjax({
            url: "Layout_Citroen_OutilChoixFinancement/getQuestionAjax",
            success: function() {
                $('div#choix_financement div.reponse input').on('click', outilChoixFinancement.manage);
                loader.hide();
            }
        });
    }
};

var onMyProjectPage = function() {

    var elements = $('.vehiculesProjets');
    //verifie si on est bien sur la page mon projet

    if (Boolean($('.vehiculesProjets').length)) {
        var selectCar = jQuery('.selectedCar.active input');
        if (typeof selectCar.val() != 'undefined' && selectCar.val() != 0) {
            var lcdv6 = selectCar.val().split('|')[0];
            if (lcdv6 != '') {
                aParams = {'lcdv6': lcdv6}
                callAjax({
                    url: 'Layout_Citroen_MonProjet_SelectionVehicules/onMyProjectAjax',
                    data: aParams,
                    type: 'post',
                    success: function() {
                    }
                });
            }
        }
    }
}


/*Ma sÃƒÂ©lÃƒÂ©ction de vÃƒÂ©hicules*/
var selectionVehicule = {
    manage: function() {
        var me = $(this);
    },
    save: function(invoker, sr_order, fromShowRoom) {
        var aParams = {};
        if (typeof fromShowRoom != 'undefined' && fromShowRoom != null) {
            aParams['order'] = sr_order;
            aParams['lcdv6_code'] = invoker;
            aParams['lcdv6'] = invoker;

        }
        else {
            var order = $(invoker).attr('id').split('_')[3];
            aParams['order'] = order;
            $($('form#car_selection_' + order).serializeArray()).each(function(index, item) {
                //fetch premier parametre sur le formulaire de selecteur de vehicule
                // doit forcement etre le code lcdv6
                //on passe ce parametre pour generer cet ajax dans la perso

                if (index == 0) {
                    aParams['lcdv6'] = item.value;
                }
                if (item.value == 0) {
                    item.value = null;
                }
                aParams[item.name] = item.value;
            });
        }


        callAjax({
            url: 'Layout_Citroen_MonProjet_SelectionVehicules/addToSelectionAjax',
            data: aParams,
            type: 'post',
            success: function() {
                old_url = location.href;
                var new_url = old_url.substring(0, old_url.indexOf('?'));
                location.href = new_url;
            }
        });
        /*$.event.trigger({
         type: "selection_vehicule.save",
         time: new Date(),
         invoker: invoker
         });*/
    },
    remove: function() {
        var invoker = $(this);
        var order = invoker.parent().parent().attr('id').split('_')[2];
        callAjax({
            url: "Layout_Citroen_MonProjet_SelectionVehicules/removeFromSelectionAjax",
            data: {order: order},
            success: function() {
                old_url = location.href;
                var new_url = old_url.substring(0, old_url.indexOf('?'));
                location.href = new_url;
            }
        });
    }
};

$('div#choix_financement div.reponse input').on('click', outilChoixFinancement.manage);
//$('form#sim-fin div.actions a#next-step').bind('click',simulateurFinancement.nextStep);
$('div.selectedCar div.closer').bind('click', selectionVehicule.remove);



$('.selectZone').prev('.fakehidden').bind('change', selectMotorisation.manage);
/* Inter-dependent lists */
var targetDropDown0b = 'first';
var targetDropDown1b = 'first';
var targetDropDown2b = 'first';
var targetDropDown0c = 'first';
var targetDropDown1c = 'first';
var targetDropDown2c = 'first';

var dropdownstack = {
    manage: function(event) {
        var me = this,
            nextSelector = me.getAttribute('data-next'),
            module = me.getAttribute('data-module'),
            a = me.getAttribute('data-ws');
        var params = null;

        var state = $(me).parent().find('.selectZone .hover').length;

        if (module == 'select_vehicule' || module == 'sim_fin') {
            params = me.value;
            params_0 = me.value;
        } else {
            params = me.value.split("#");
            params_0 = params[0];
        }
        var _complement = '';
        if (typeof params != 'string' && params.length > 1) {
            _complement = '&lcdv6=' + params[1];
        }
        var $nextfield = $(nextSelector),
            $button = $(me).parent().find('.button'),
            $figure = $(me).prev('figure');


        /*Comparateur*/
        var unique_finition = Array();

        if (module == 'comparator') {
            var finition_ids = Array('select0b', 'select1b', 'select2b');

            if ($.inArray($(me).attr('id'), finition_ids) != -1) {
                var finition_values = Array();
                $.each(finition_ids, function(index, value) {
                    field_id = '#' + value;
                    field_val = $(field_id).val();
                    if (typeof field_val != 'undefined' && field_val) {
                        fin = field_val.split('#')[0];
                        finition_values.push(fin);
                    }
                });

                if (finition_values.length > 0) {
                    unique_finition = Array();
                    $.each(finition_values, function(index, value) {
                        if ($.inArray(value, unique_finition) == -1) {
                            unique_finition.push(value);
                        }
                    });

                    if (unique_finition.length > 0) {
                        showRoomComparateur = [];
                        var aParams = {};
                        $($('form#form_comparateur').serializeArray()).each(function(index, item) {
                            aParams[item.name] = item.value;
                        });



                        /*callAjax({
                         url:  "Layout_Citroen_Comparateur/updateComparateurSessionAjax",
                         async: false,
                         type: 'post',
                         data: aParams
                         });*/


                        //alert(aParams.length);


                        $('ul.actions.compareBtn li a').show();
                        var _vehicule = null;
                        $.each(unique_finition, function(index, value) {
                            if (parseInt(value) != 0 && showRoomComparateur.length < 3) {
                                _vehicule = {'finition_code': value};
                                showRoomComparateur.push(_vehicule);
                                $(this).trigger("added_to_showroom");
                            }

                            $('li a[rel="' + value + '"]').hide();
                        });
                    }

                }

            }

        }
        //end if(module=='comparator')


        /* Selecteur de VÃƒÂ©hicules */
        if (module == 'select_vehicule' && me.value != 0) {
            var order = $(me).attr('id').split('_')[2];
            var lvl = $(me).attr('id').split('_')[3];
            if (
                $(me).attr('id') == 'sv_select_0_a' ||
                $(me).attr('id') == 'sv_select_1_a' ||
                $(me).attr('id') == 'sv_select_2_a' ||
                $(me).attr('id') == 'sv_select_0_b' ||
                $(me).attr('id') == 'sv_select_1_b' ||
                $(me).attr('id') == 'sv_select_2_b' ||
                $(me).attr('id') == 'sv_select_0_c' ||
                $(me).attr('id') == 'sv_select_1_c' ||
                $(me).attr('id') == 'sv_select_2_c'
            ) {
                $('#add_to_selection_' + order).removeClass('hidden');
                finition = $('#sv_select_' + order + '_b').data('save');
                engine = $('#sv_select_' + order + '_c').data('save');
                if ((lvl == 'a' && finition == '' && engine == '') || (lvl == 'b' && engine == '') || lvl == 'c') {
                    callAjax({
                        url: "Layout_Citroen_MonProjet_SelectionVehicules/getVehiculeImagePrixAjax",
                        async: true,
                        data: {
                            v: me.value,
                            order: order
                        },
                        success: function() {
                            gtmCit.initNewGTM();
                        }
                    });

                    $('#sv_select_' + order + '_a').data('save', '');
                    $('#sv_select_' + order + '_b').data('save', '');
                    $('#sv_select_' + order + '_c').data('save', '');
                }
            }
            if (a) {
                _url = a + '?v=' + params_0;
                if (module == 'select_vehicule') {
                    if (finition != undefined && finition != '') {
                        _url += '&f=' + finition;
                    }
                    if (engine != undefined && engine != '') {
                        _url += '&e=' + engine;
                    }
                }
                $.ajax({
                    url: _url,
                    success: function(response) {
                        //fillout next select field
                        $nextfield.next().find('.select').html(response);
                        //reable next select field
                        $nextfield.removeAttr('disabled').unbind('change', dropdownstack.manage).bind('change', dropdownstack.manage);
                        //
                        $nextfield.trigger('change');
                        gtmCit.initNewGTM();
                    }
                });
            }
            //no need to do more for vehicule selection module
            return;
        }



        if (module == 'sim_fin' && me.value != 0) {

            if ($(me).attr('id') == 'sim_fin_select0') {
                $('#car_figure').html('');
            }

            if ($(me).attr('id') == 'sim_fin_select1' || $(me).attr('id') == 'sim_fin_select2') {
                callAjax({
                    url: "Layout_Citroen_SimulateurFinancement/getVehiculeImagePrixAjax",
                    async: true,
                    data: {
                        v: me.value
                    },
                    success: function() {
                        gtmCit.initNewGTM();
                    }
                });
            }
            if ($(me).attr('id') == 'sim_fin_select1' || $(me).attr('id') == 'sim_fin_select2') {

                $('#next-step').removeClass('disabled').bind('click', simulateurFinancement.manage);

            }

            if (a) {
                $.ajax({
                    url: a + '?v=' + params_0,
                    success: function(response) {
                        //fillout next select field
                        $nextfield.next().find('.select').html(response);
                        //reable next select field
                        $nextfield.removeAttr('disabled').unbind('change', dropdownstack.manage).bind('change', dropdownstack.manage);
                        gtmCit.initNewGTM();
                    }
                });
            }

            return;
        }

        /*Simulateur de Financemenr*/



        //if(!nextSelector) return;
        if (0 != me.value) {
            /* Enable next */
            $nextfield.removeAttr('disabled').unbind('change', dropdownstack.manage).bind('change', dropdownstack.manage);


            /* Show media if has */
            if ($figure.length) {

                var $img = $figure.find('img'),
                    src = $img.attr('src');

                /* Backup if hasn't */
                if (!$img.data('backup'))
                    $img.data('backup', src);

                /* Static example */
                $img.attr('src', 'design/frontend/images/car/monprojet-selection01-visuel.png');

                /* Show button if has */
                $button.removeClass('hidden');
            }
            ;

            /* Has ajax */
            var _equipement = me.getAttribute('data-equipement');
            var _tpid = $('input[name=tpid]').val();
            var _zid = $('input[name=zid]').val();
            var _gamme = $('#form_comparateur input[name=filterComparator]').val();
            if ((a || _equipement)) {
                var id = me.getAttribute('id');
                var comparateurMonProjet = $('input.comparateurMonProjet').length > 0 ? 1 : 0;
                if (me.getAttribute('data-save') != '' && me.getAttribute('data-save') != null) {
                    a = null;
                }

                //Select an Item in The Dropdown, not jst click on Item selected for open DropDown 
                if (state == 0 ){



                    /** set des targets **/
                    if (a && (getTargetDropdown(id) == "" || (getTargetDropdown(id) != $(event.target).text()))) {

                        if(getTargetDropdown(id) != "first") {
                            setTargetDropdown(id, $(event.target).text());
                        }
                        else
                        {
                            setTargetDropdown(id, $(this).next('div').children().find('a.on').text());
                        }
                        _complement  = _complement +  "&TEMPLATE_PAGE_ID="+_tpid+"&ZONE_ID="+ _zid+'&gamme='+_gamme;


                        $.ajax({
                            url: a + '?v=' + params[0] + _complement,
                            success: function(response) {

                                $nextfield.next().find('.select').html(response);

                                var  first_engine = $nextfield.next().find('.select li:nth-child(2) a').attr('data-value').split('#')[0];

                                if(   (a == '/_/Layout_Citroen_Comparateur/getEngineByFinitionAjax' || a == '/_/Layout_Citroen_MonProjet_Comparateur/getEngineByFinitionAjax') ){

                                    callEquipement(false, true);
                                    callAjax({
                                        url: "Layout_Citroen_Comparateur/getOutilsAjax",
                                        async: true,
                                        data: {
                                            "LCDV6": params[1], "ID": id, "FINITION": params[0], "TEMPLATE_PAGE_ID": _tpid,"ZONE_ID": _zid, 'ENGINE': first_engine ,'GAMME':_gamme
                                        }

                                    });
                                    callAjax({
                                        url: "Layout_Citroen_Comparateur/getImageEtPrixVehiculeByVersionAjax",
                                        async: true,
                                        data: {
                                            "LCDV6": params[1], "ID": id, "FINITION": params[0], "TEMPLATE_PAGE_ID": _tpid,"ZONE_ID": _zid, 'ENGINE': first_engine,'GAMME':_gamme
                                        },
                                        success: function() {

                                            $('.tooltip,.texttip').each(tooltip.build);
                                            lazy.load($('.datas img.lazy'));
                                        }
                                    });
                                }
                                gtmCit.initNewGTM();
                            }
                        });
                    }
                }

                if (_equipement && (getTargetDropdown(id) == "" || (getTargetDropdown(id) != $(event.target).text()))) {

                    if(getTargetDropdown(id) != "first") {
                        setTargetDropdown(id, $(event.target).text());
                    }
                    else
                    {
                        setTargetDropdown(id, $(this).next('div').children().find('a.on').text());
                    }

                    callEquipement(false, false);
                    callAjax({
                        url: "Layout_Citroen_Comparateur/getOutilsAjax",
                        async: true,
                        data: {
                            "LCDV6": params[1], "ID": id, "FINITION": params[2], "TEMPLATE_PAGE_ID": _tpid,"ZONE_ID": _zid, "ENGINE": params[0]   ,'GAMME':_gamme
                        },
                        success: function() {
                            gtmCit.initNewGTM();
                        }
                    });
                    callAjax({
                        url: "Layout_Citroen_Comparateur/getImageEtPrixVehiculeByVersionAjax",
                        async: true,
                        data: {
                            "LCDV6": params[1], "ID": id, "FINITION": params[2], "TEMPLATE_PAGE_ID": _tpid,"ZONE_ID": _zid, "ENGINE": params[0],'GAMME':_gamme
                        },
                        success: function() {
                            $('.tooltip,.texttip').each(tooltip.build);
                            lazy.load($('.datas img.lazy'));
                            gtmCit.initNewGTM();
                        }
                    });


                }
            } else {
                if (
                    $(me).attr('id') == 'select0a'
                    ||
                    $(me).attr('id') == 'select1a' ||
                    $(me).attr('id') == 'select2a'
                )
                {

                    $(me).attr('data-ws', '/_/Layout_Citroen_Comparateur/getFinitionsByModelAjax');
                } else {
                    var _params = me.value.split("#");
                    //limiter ces appels aux controlleurs adequats

                    callAjax({
                        url: "Layout_Citroen_Comparateur/getImageEtPrixVehiculeByVersionAjax",
                        async: true,
                        data: {
                            "ENGINE": _params[0], "LCDV6": _params[1], "ID": $(me).attr('id'), "FINITION": _params[2],'GAMME':_gamme
                        },
                        success: function() {
                            gtmCit.initNewGTM();
                        }
                    });

                }
            }
            //



        } else {


            /* Disabled field */
            $nextfield.attr('disabled', 'disabled');

            /* Reset media if has */
            if ($figure.length) {

                var $img = $figure.find('img'),
                    src = $img.data('backup');

                /* Static example */
                $img.attr('src', src);

                /* Hide button if has */
                $button.addClass('hidden');

            }
            ;

            /* Reset field value */
            $nextfield.val(0).trigger('change');

        }
        ;
        me.setAttribute('data-save', '');
        /* If content is added/updated, keep synchronized height on concerned elements */
        if (sync)
            sync.set();


        return;
    }
};



/* funding page to manage Ajax */
dropdownGroup.getResultField = function(root){
    var $form = root.find('form');
    $.ajax({
        data:$form.serialize(),
        type: 'post',
        url:$form.attr('action'),
        dataType:'html',
        success:function(response){
            dropdownGroup.htmlRender($form,response);
            gtmCit.initNewGTM();
        }
    });
}

function setTargetDropdown(id, value)
{
    switch(id)
    {
        case "select0b":
            targetDropDown0b = value;
            break;
        case "select1b":
            targetDropDown1b = value;
            break;
        case "select2b":
            targetDropDown2b = value;
            break;
        case "select0c":
            targetDropDown0c = value;
            break;
        case "select1c":
            targetDropDown1c = value;
            break;
        case "select2c":
            targetDropDown2c = value;
            break;
    }
}
function getTargetDropdown(id)
{
    var toReturn = "";
    switch(id)
    {
        case "select0b":
            toReturn = targetDropDown0b;
            break;
        case "select1b":
            toReturn = targetDropDown1b ;
            break;
        case "select2b":
            toReturn = targetDropDown2b ;
            break;
        case "select0c":
            toReturn = targetDropDown0c ;
            break;
        case "select1c":
            toReturn = targetDropDown1c ;
            break;
        case "select2c":
            toReturn = targetDropDown2c ;
            break;
    }

    return toReturn;
}
function reinitComparateur() {
    $('.reinitComparateur0, .reinitComparateur1, .reinitComparateur2').on('click', function(e) {

        var selectToReinit = $(this).attr('data-values');
        var select_val = $('#select' + selectToReinit + 'b').val();
        var _finition = select_val.split('#')[0];
        if ($('a[rel=\"' + _finition + '"]').length) {
            $('a[rel=\"' + _finition + '"]').show();
        }
        var _figure = $('#car' + selectToReinit).find('figure');
        var _img = _figure.find('img');
        var _src = _img.attr('data-backup');
        _img.attr('src', _src);
        $('#outils' + selectToReinit).html('');
        $('#car' + selectToReinit + ' .prices').html('');
        if ($(this).attr('data-info') == 'comparateur') {
            $('#select' + selectToReinit + 'a').val(0).trigger('change');
        }
        $('#select' + selectToReinit + 'b').val(0).trigger('change');
        $('#select' + selectToReinit + 'c').val(0).trigger('change');
        $('.disclaimer').css('display', 'none');
        callEquipement(true, true);
    });


    var lcdv6Preset = $('#lcdv6Preset').val();

    try {
        if(lcdv6Preset.length){
            $('#select0a').next().find('.on').removeClass('on');
            $('#select0a').val(lcdv6Preset).trigger('change');
        }
    } catch(ex) {}
}
function callEquipement(isReinit, forceLoad) {
    //var target = $( event.target );
    if (($('#select0a').length > 0 || $('#select1a').length > 0 || $('#select2a').length > 0)) {
        //target.parents('ul').removeClass('open');
        var model_1 = 0;
        var model_2 = 0;
        var model_3 = 0;
        var finition_1 = 0;
        var finition_2 = 0;
        var finition_3 = 0;
        var engine_1 = 0;
        var engine_2 = 0;
        var engine_3 = 0;
        if ($('#select0a').length > 0) {
            var selectedmodel1 = $('#select0a').val();
            model_1 = selectedmodel1.split("_");
        }
        if ($('#select1a').length > 0) {
            var selectedmodel2 = $('#select1a').val();
            model_2 = selectedmodel2.split("_");
        }
        if ($('#select2a').length > 0) {
            var selectedmodel3 = $('#select2a').val();
            model_3 = selectedmodel3.split("_");
        }
        if ($('#select0b').length > 0) {

            var selectedfinition1 = $('#select0b').val();
            finition_1 = selectedfinition1.split("#");

        }
        if ($('#select1b').length > 0) {
            var selectedfinition2 = $('#select1b').val();
            finition_2 = selectedfinition2.split("#");
        }
        if ($('#select2b').length > 0) {
            var selectedfinition3 = $('#select2b').val();
            finition_3 = selectedfinition3.split("#");
        }

        if ($('#select0c').length > 0) {
            var selectedengine1 = $('#select0c').val();
            engine_1 = selectedengine1.split("#");
        }
        if ($('#select1c').length > 0) {
            var selectedengine2 = $('#select1c').val();
            engine_2 = selectedengine2.split("#");
        }
        if ($('#select2c').length > 0) {
            var selectedegine3 = $('#select2c').val();
            engine_3 = selectedegine3.split("#");
        }

        if ((model_1 != 0 && finition_1[0] != 0) || (model_2 != 0 && finition_2[0] != 0) || (model_3 != 0 && finition_3[0] != 0) || isReinit == true) {
            var _tpid = $('input[name=tpid]').val();
            var _zid = $('input[name=zid]').val();
            var loader = new Loader($('#form_comparateur'));
            loader.show(LoadingKey, false);
            callAjax({
                url: "Layout_Citroen_Comparateur/getEquipementsCaracteristiques",
                async: true,
                data: {
                    "model_1": model_1[0],
                    "model_2": model_2[0],
                    "model_3": model_3[0],
                    "finition_1": finition_1[0],
                    "finition_2": finition_2[0],
                    "finition_3": finition_3[0],
                    "engine_1": engine_1[0],
                    "engine_2": engine_2[0],
                    "engine_3": engine_3[0],
                    "TEMPLATE_PAGE_ID": _tpid,
                    "ZONE_ID": _zid
                },
                success: function(e) {
                    if (finition_1[0] == 0 && finition_2[0] == 0 && finition_3[0] == 0) {
                        $('.disclaimer').css('display', 'none');
                    }

                    if (typeof window.updateComparisonTable === 'function') {
                        updateComparisonTable();
                    }

                    loader.hide();
                    gtmCit.initNewGTM();
                }
            });
        }
    } else {
        if (target.parents('ul').attr('class') != "undefined") {
            target.parents('ul').addClass('open');
        }

    }
}
//MÃƒÂ©thode permettant d'ajouter des vehicules en session pour le comparateur
function replaceToCompare() {
    $('a.replaceToCompare').bind('click', function(e) {
        e.preventDefault();
        var lcdv6 = $(this).attr('data-value');
        callAjax({
            url: "Layout_Citroen_Comparateur/addToCompare",
            async: false,
            data: {
                'lcdv6': lcdv6
            },
        });
    });
}
/* Bind "fakehidden" to create inter-dependent lists */
$('.selectZone').prev('.fakehidden').bind('change', dropdownstack.manage);



/* Bind "fakehidden" to listen sortable change event */
$('.fakehidden[name="listorder"]').bind('change', function() {

});

/*ConcessionVNAV*/

var concession = {
    addToFavs: function(invoker) {
        var invoker = $(invoker);
        var invoker_args = invoker.attr('href').split('#');
        if (invoker_args != '') {
            callAjax({
                url: 'Layout_Citroen_MonProjet_ConcessionVNAV/addToFavorisAjax',
                async: false,
                data: {
                    'sid': invoker_args[1],
                    'type': invoker_args[2]
                },
            });

        }

    },
    showDetails: function(invoker) {
        var invoker = $(invoker);
        var invoker_args = invoker.attr('href').split('#');

        if (invoker_args.length > 0) {
            callAjax({
                url: 'Layout_Citroen_PointsDeVente/getDealer',
                async: false,
                data: {
                    'id': invoker_args[1]
                },
                success: function(e) {
                    gtmCit.initNewGTM();
                }
            });
        }
        return;
    },
    deleteFromFavs: function(invoker) {
        var invoker = $(invoker);
        var invoker_args = invoker.attr('href').split('#');
        if (invoker_args.length > 0) {
            callAjax({
                url: 'Layout_Citroen_MonProjet_ConcessionVNAV/deleteFromFavsAjax',
                async: false,
                data: {
                    sid: invoker_args[1],
                    type: invoker_args[2]
                }
            });
        }
        return;
    }

};
$('.alert .closer').bind('click', function() {
    callAjax({
        url: 'Layout_Citroen_MonProjet_MessageInformatif/closeMessage',
        async: false
    });
});

function languettePerso() {
    $('form[name=fFormLanguettePro] input[name=isPro],form[name=fFormLanguetteClient] input[name=isClient]').on('click', function(e) {
        $(this).parent('form').submit();
    });
}
$('input.radiotypeform').on('click', function(e) {
    $('#parcours').val($(this).val());
});
//charger les donnÃƒÂ©es du formulaire depuis l'onglet pour faire le dataLayer.push au clic
function gtmFormPush(element){
    $('.folder a[href^="#deployable_"]').on('click', function(e) {
        var href = $(this).attr('href');
        var aHref  = href.split('_');
        var idform = aHref[1];

        var gtm_form_data = $(this).attr('gtm-form-data');
        if (gtm_form_data) {
            gtm_form_data = $.parseJSON(gtm_form_data);
        }
        var formContext = $($(this).attr('href')).find('input[name=FORM_CONTEXT_CODE]').val();
        var formtypelabel =  $($(this).attr('href')).find('input[name=formTypeGTM]').val();

        if($('input#formtypelabelselected').length ==0){
            $("<input>", {class: "formtypelabelselected",id:"formtypelabelselected",type:"hidden"}).insertBefore('.footerReviewDesk').val(formtypelabel);
        }

        if($('input#formtypelabelselected').length > 0){
            $('input#formtypelabelselected').val(formtypelabel);
        }

        switch(formContext){
            case "CAR":
                context =  "context-car";
                break;
            case "RTO":
                context =  "context-dealer";
                break;
            default:
                context =  "context-none";
                break;
        }



        // if($('#nextstepformdeploy'+idform+'').length > 0){
            // var formActivation = $($(this).attr('href')).find('input[name=formActivation]').val();
            // var formActivationtype = $($(this).attr('href')).find('input[name=formActivationType]').val();

            // if (gtm_form_data) {
                // dataLayer.push({
                    // event: 'updatevirtualpath',
                    // pageName: 'Intro/Request_' + gtm_form_data.form_gtm.FORM_TYPE_GTM_ID,
                    // virtualPageURL: '/Request_' + gtm_form_data.form_gtm.FORM_TYPE_GTM_ID + '/Intro',
                    // pageVariant:context

                // });
            // }

            // if(e.preventDefault){
                // e.preventDefault();
            // }else {
                // e.returnValue = false;
            // }
        // }
    });
}
/**Formulaires*/
function initFormulaire() {
    $("[id^='deployable_']").each(function(){
        var $deploye = $(this);
        $deploye.on('content_toggle',function(e,param){
            var input_formTypeGTM = $deploye.find('input[name="formTypeGTM"]').get(0);
            var formTypeGTM = input_formTypeGTM?input_formTypeGTM.value:0;console.log(formTypeGTM);
			var cpw =new Array();
            if(formTypeGTM && param.isOpen){
                if(cpw[formTypeGTM] && cpw[formTypeGTM].form_gtm_data && cpw[formTypeGTM].form_gtm_data.to_push){
                    // pop array to_push/del remove/free
                    delete cpw[formTypeGTM].form_gtm_data.to_push;
                    dataLayer.push(cpw[formTypeGTM].form_gtm_data);
                }
                $(this).off('content_toggle');
            }
        });
    });


    $('div.sliceDeployableFormDesk section.formulaireCitroen').each(function() {


        var formActivation = $(this).find('input[name=formActivation]').val();
        var typeFormulaire = $(this).find('input[name=typeFormulaire]').val();
        var typeDevice = $(this).find('input[name=typeDevice]').val();
        var InceCode = $(this).find('input[name=InceCode]').val();
        var lcdvForm = $(this).find('input[name=lcdv6Form]').val();
        var iPageId = $(this).find('input[name=form_page_pid]').val();
        var idSection = $(this).attr('id');
        var email = $(this).find('input[name=email]').val();
        var formTypeLabel = $(this).find('input[name=formTypeLabel]').val();
        var isDeployed = $(this).find('input[name=deployed]').val();
        var typeOfForm =  $(this).find('input[name=FORM_CONTEXT_CODE]').val();
        var isRTO = $("#isPDV").val();
		var gdoMarketingCode = $(this).find('input[name=gdoMarketingCode]').val();

        var formEquipCode =  $(this).find('input[name=EQUIPEMENT_CODE]').val();
        var formIDType =  $(this).find('input[name=TYPE_ID]').val();
        var formUserCode =  $(this).find('input[name=USER_TYPE_CODE]').val();

        var contextForm = "";



        if($(this).parent().parent().attr('class')=='sliceNew sliceDeployableFormDesk'){
            if (formActivation != 'CHOIX' && ( !$(this).parent().hasClass('secret') ||  typeOfForm == 'RTO' )) {

                if(lcdvForm)
                {
                    contextForm = "CAR";
                }
                if(typeOfForm == 'RTO')
                {
                    contextForm =  'RTO' ;
                    InceCode = getINCEForm(formIDType, formUserCode, formEquipCode, contextForm, InceCode);
                }

                if(contextForm=="RTO"){
                    if(isDeployed!=1){
                        getFormId(InceCode, formActivation, idSection, lcdvForm, email, formTypeLabel, isDeployed, contextForm,iPageId, gdoMarketingCode);
                    }
                }

                else{
                    getFormId(InceCode, formActivation, idSection, lcdvForm, email, formTypeLabel, isDeployed, contextForm,iPageId, gdoMarketingCode);
                }

            }
        }

        var idParent = $(this).parent().parent().attr('id');
        var idPage = $('input[name=' + idSection + 'idPage]').val();
        var zoneOrder = $('input[name=' + idSection + 'zoneOrder]').val();
        var areaId = $('input[name=' + idSection + 'areaId]').val();
        var zoneTid = $('input[name=' + idSection + 'zoneTid]').val();

        $('.folder a[href^="#' + idParent + '"]').on('click', function(e) {


            if (formActivation != 'CHOIX' && (!$(this).parent().parent().hasClass('showroom') ||   typeOfForm == 'RTO' )) {

                if(lcdvForm)
                {
                    contextForm = "CAR";
                }
                if(typeOfForm == 'RTO')
                {
                    contextForm =  'RTO' ;
                    InceCode = getINCEForm(formIDType, formUserCode, formEquipCode, contextForm, InceCode);
                }

                if(contextForm=="RTO"){
                    if(isDeployed!=1){
                        getFormId(InceCode, formActivation, idSection, lcdvForm, email, formTypeLabel, isDeployed, contextForm,iPageId, gdoMarketingCode);
                    }
                }

                else{
                    getFormId(InceCode, formActivation, idSection, lcdvForm, email, formTypeLabel, isDeployed, contextForm,iPageId, gdoMarketingCode);
                }

            }

            var url = window.location.href;

            if (url.indexOf('?') != -1) {

                var token = url.substring(url.indexOf('?') + 1, url.indexOf('_')),
                    id = url.substring(url.indexOf('=')+1),
                    selector = token+'_'+id;

                if(token =='deployable') {
                    $('div#' + selector + '').hide();
                }
            }

            if($('input#form_deployed').length ==0){
                $("<input>", {class: "form_deployed",id:"form_deployed",type:"hidden"}).insertBefore('.footerReviewDesk').val(idParent);
            }

            if($('input#form_deployed').length > 0){
                var fdeployed = $('input#form_deployed').val();
                $('div#'+fdeployed+'').hide();
                $('input#form_deployed').val(idParent);
            }

            // if ($('#' + idSection).find('iframe').size() == 0) {
                // if(lcdvForm)
                // {
                    // contextForm = "CAR";
                // }
                // if(typeOfForm == 'RTO')
                // {
                    // contextForm =  'RTO' ;
                    // InceCode =  getINCEForm(formIDType, formUserCode, formEquipCode, contextForm, InceCode);
                // }
                // getFormId(InceCode, formActivation, idSection, lcdvForm, email, formTypeLabel, isDeployed, contextForm,iPageId);
            // } else {
                // if(lcdvForm)
                // {
                    // contextForm = "CAR";
                // }
                // if(typeOfForm == 'RTO')
                // {
                    // contextForm =  'RTO' ;
                    // InceCode =  getINCEForm(formIDType, formUserCode, formEquipCode, contextForm, InceCode);
                // }
                // /**
                 // *CommentÃƒÂ© car bug des onglets outils
                 // *reinitForm(idPage, zoneOrder, areaId, zoneTid, idParent, lcdvForm, email, contextForm);
                 // */
                // getFormId(InceCode, formActivation, idSection, lcdvForm, email, formTypeLabel, isDeployed, contextForm,iPageId);
            // }
        });

        $('.nextStepForm' + idSection).on('click', function(e) {
            if(e.preventDefault){
                e.preventDefault();
            }else{
                e.returnValue = false;
            }
            var section = $(this).attr('rel');
            var typeClient = $('section.' + section).find('input[name=isPro' + idSection + ']:checked').val();
            var isDeployed = $('section.' + section).find('input[name=deployed]').val();
            if (typeof (typeClient) != 'undefined') {
                if(lcdvForm)
                {
                    contextForm = "CAR";
                }
                if(typeOfForm == 'RTO')
                {
                    contextForm =  'RTO' ;
                    InceCode = getINCEForm(formIDType, formUserCode, formEquipCode, contextForm, InceCode);
                }
                $('#'+section+'').hide();
                callFormulaire(typeFormulaire, typeClient, typeDevice, section, lcdvForm, email, formTypeLabel, isDeployed, contextForm,iPageId);
            }
        });
    });
}
function ResizeIframeFromParent(id)
{
    try {
        if (jQuery('#' + id).length > 0) {
            var window = document.getElementById(id).contentWindow;
            var prevheight = jQuery('#' + id).attr('height');
            var newheight = window.document.getElementById('wf_form_content').clientHeight;

            //console.log("Adjusting iframe height for "+id+": " +prevheight+"px => "+newheight+"px");
            if (newheight != prevheight && newheight > 0) {
                jQuery('#' + id).attr('height', newheight + 10);
            }
        }
    }
    catch (e) {
    }
}
function ResizeIframeFromParent2(id)
{
    var isMSIE = /*@cc_on!@*/0; //test pour dÃƒÂ©terminÃƒÂ© si IE
    if (!isMSIE)
    {
        var $myIframe = $('#' + id);
        var myIframe = $myIframe[0];
        var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;

        myIframe.addEventListener('load', function() {
            ResizeIframeFromParent(id);

            var target = myIframe.contentDocument.getElementById('wf_form_content');

            var observer = new MutationObserver(function(mutations) {
                ResizeIframeFromParent(id);
            });

            var config = {
                attributes: true,
                childList: true,
                characterData: true,
                subtree: true
            };
            observer.observe(target, config);
        });
    }
    else
    {
        setInterval(function() {
            ResizeIframeFromParent(id);
        }, 1000);
    }
}

function scrollForm(height)
{
    var arrFrames = document.getElementsByTagName("iframe");
    for(i = 0; i<arrFrames.length; i++){
        try{
            if(arrFrames[i].id == IframeId){
                $('html, body').delay(100).stop().dequeue().animate({
                    scrollTop: $(arrFrames[i]).offset().top+height
                }, 200);
            }
        }
        catch(e){
        }
    }
}


function sendGTM(category, action, label) {
                dataLayer.push({'event': 'uaevent', 'eventCategory': category, 'eventAction': action, 'eventLabel': label});
            }     


function loadFormsParameters() {
				
	var pagetitle = $('input[name=form_page_title]').val();
    var vehicleModelBodystyleLabel = $('input[name=vehicleModelBodystyleLabel]').val();
    var siteTypeLevel2 = $('input[name=siteTypeLevel2]').val();
	var formContext = $('input[name=FORM_CONTEXT_CODE]').val();
    var lcdvForm = $('input[name=lcdv6Form]').val();
    var isGeocodeActive = $('input#isGeocodeActive').val();
    var formtypelabelselected = $('input#formtypelabelselected').val();
	 switch(formContext){
            case "CAR":
                context =  "context-car";
                break;
            case "RTO":
                context =  "context-dealer";
                break;
            default:
                context =  "context-none";
                break;
        }
	if(siteTypeLevel2==''){
		siteTypeLevel2 = 'forms';
	}
	var formTypeGTM = $('input[name=formTypeGTM]').val();

	var country = $('input[name=CODE_PAYS]').val();
	// temporaire en attendant lintegration du GDG
	console.log(vehicleModelBodystyleLabel,'vehicleModelBodystyleLabel');
	var sAutoFill = {'GIT_TRACKING_ID': getGITID(),'PAGE_TITLE':pagetitle,'TESTDRIVE_CAR':vehicleModelBodystyleLabel,'DOC_CAR_NAME':vehicleModelBodystyleLabel,'GTM_SITE_TYPE_LEVEL_2':siteTypeLevel2,'GTM_PAGE_VARIANT':context};
	
	lcdvFormContext = Array();
   if(lcdvForm.length > 0){
		lcdvFormContext = [lcdvForm]; 
		var sAutoFill = {'GIT_TRACKING_ID': getGITID(),'TESTDRIVE_CAR_LCDV': lcdvForm,'PAGE_TITLE':pagetitle,'TESTDRIVE_CAR':vehicleModelBodystyleLabel,'DOC_CAR_NAME':vehicleModelBodystyleLabel,'GTM_SITE_TYPE_LEVEL_2':siteTypeLevel2,'GTM_PAGE_VARIANT':context};
	}

	
	
	new citroen.webforms.WebFormsFacade({
		source: '/dcr/prm/getinstancebyid?instanceid='+formParams.instance+'&culture='+formParams.culture + '&GDO_MARKETING_CODE=' + encodeURIComponent(formParams.gdoMarketingCode),
		returnURL: '',
		dealerLocatorFluxType: 'dealerdirectory2',
		target: 'wf_form_content',
		siteGeo : isGeocodeActive,
		autoFill: sAutoFill,
		carPickerPreselectedVehicles: lcdvFormContext,
		brochurePickerPreselectedVehiclesLcdv: lcdvFormContext,
		brochurePickerPreselectedVehicles: [],
		onPostAjaxSuccess: function(datas) {
			//$('div#wf_form_content').hide();
		   finalStepFunction(datas,formParams.idframe, formParams.instance,formParams.typeLabel);
		},
		onPostAjaxFailure: function() {
			alert("Erreur technique lors de l'enregistrement du formulaire");
		},
		onPostAjaxError: function(datas) {
			console.log(datas,'onPostAjaxError');
			alert("Certaines donness du formulaire sont invalides");
		}					
	});
	
	 form_load_html();
	// Contextualisation des parametres du moteur
	citroen.webforms.parameters.contextualize(formParams);
}


function form_load_html() {
    if (typeof $('li.wf_active').html() === 'undefined')
    {
        window.setTimeout(form_load_html, 100);
    }
    else
    {
        $('li.wf_active a').trigger('click');
        $('div.wf_resume_img img').css("min-width", "0%");
    }
}

function getFormId(InceCode, formActivation, idIframe, lcdvForm, email, formTypeLabel, isDeployed, contextForm,iPageId, gdoMarketingCode) {



    if(navigator.appVersion.indexOf("MSIE 9.")!=-1){

        if (typeof (InceCode) == "string" && InceCode != '') {
            if (typeof formTypeLabel == 'undefined' || formTypeLabel == '') {
                formTypeLabel = '';
            }
            var iFormPageId = $('input[name=form_page_pid]').val();

            var styleForm = $('input[name=' + idIframe + 'styleForm]').val();
            var formClass = $("body").attr('class');
            var iPageId2 = $('input[name=form_page_pid]').val();
            //var url = "/forms/" + formTypeLabel + "/Layout_Citroen_Formulaire/iframe?version=2&idform=" + InceCode + "&typeform=" + formActivation + "&section=" + idIframe + "&lcdv=" + lcdvForm + "&email=" + email + "&formClass=" + formClass + "&styleForm=" + styleForm + "&isDeployed=" + isDeployed+"&contextForm="+contextForm;
            var url = "/forms/" + formTypeLabel + "/Layout_Citroen_Formulaire/iframe?version=2&idform=" + InceCode + "&typeform=" + formActivation + "&section=" + idIframe + "&lcdv=" + lcdvForm + "&email=" + email + "&formClass=" + formClass + "&styleForm=" + styleForm + "&isDeployed=" + isDeployed + "&form_page_id=" +iFormPageId+"&contextForm="+contextForm+"&iframe=1";
            $("#" + idIframe).html('<iframe id="iframe' + idIframe + '" src="' + url + '" style="min-height:100px; width: 100%; margin: 0px; padding: 0px; clear: both; display: block" scrolling="no" frameborder="no"></iframe>');
            var loader = new Loader($('#' + idIframe));
            loader.show(LoadingKey, false);
            IframeId = 'iframe' + idIframe;
            ResizeIframeFromParent2('iframe' + idIframe);
            /*setInterval(function() {
             ResizeIframeFromParent('iframe' + idIframe);
             }, 1000);*/
            $('#' + idIframe).slideDown("slow");
            $('#iframe' + idIframe).load(function() {
                loader.hide();
            });
        }
    }else{

        $('div#wf_form_content').remove();

        if (typeof (InceCode) == "string" && InceCode != '') {
            if (typeof formTypeLabel == 'undefined' || formTypeLabel == '') {
                formTypeLabel = '';
            }
            var country = $('input[name=CODE_PAYS]').val();
            var lang = $('input[name=LANGUE_CODE]').val();
            var typeDevice =  $('input[name=typeDevice]').val();
            var iFormPageId = $('input[name=form_page_pid]').val();

            if(typeDevice == 'WEB'){
                contextdevice = 'desktop';
                brandconnector = 'pc';
            }else{
                contextdevice = 'mobile';
                brandconnector = 'mobile';
            }



            formParams = {
                brand:        'ac',               // Marque [ap, ac, ds] en minuscule
                lang:         lang,               // Code ISO de la Langue (en)
                country:      country,               // Code ISO du Pays (GB)
                culture:      lang+'-'+country,            // Culture (en-GB, nl-BE pour le Neerlandais en Belgique)
                instance:     InceCode, // Numero d'nstance du formulaire (16 caracteres)
                context :     contextdevice,          // desktop ou mobile
                brandidConnector: brandconnector,       // pc ou mobile ou driveds
                otherCss:     [],                 // Liste de CSS additionnels
                GammeSource: 'CPP',                // Source de la Gamme des Vehicules et Brochures (CPP ou GDG)
                environment: '' ,// Environnement (DEV, RECETTE, PREPROD, PROD)
                idframe:        idIframe,
                typeLabel:       formTypeLabel,
                contextFormDeploy:   contextForm,
				  gdoMarketingCode: gdoMarketingCode
            };

            if(isDeployed == '0'){
                $("<div>", {class: "wf_form_content",id:"wf_form_content"}).insertBefore('#'+idIframe+'').css("padding-left", "55px");
            }else{
                $("<div>", {class: "wf_form_content",id:"wf_form_content"}).insertBefore('#'+idIframe+'');
            }
            // Chargement du moteur
            $(window).load(loadFormsResources(formParams.context));

            var iFormPageId = $('input[name=form_page_pid]').val();
            var styleForm = $('input[name=' + idIframe + 'styleForm]').val();
            var formClass = $("body").attr('class');
            var iPageId2 = $('input[name=form_page_pid]').val();
            var url = "/forms/" + formTypeLabel + "/Layout_Citroen_Formulaire/iframe?version=2&idform=" + InceCode + "&typeform=" + formActivation + "&section=" + idIframe + "&lcdv=" + lcdvForm + "&email=" + email + "&formClass=" + formClass + "&styleForm=" + styleForm + "&isDeployed=" + isDeployed + "&form_page_id=" +iFormPageId+"&contextForm="+contextForm;


        }

    }


}
function callFormulaire(typeFormulaire, typeClient, typeDevice, idSection, lcdvForm, email, formTypeLabel, isDeployed, contextForm,iPageId) {
    var iFormPageId = $('input[name=form_page_pid]').val();
    callAjax({
        url: '/_/Layout_Citroen_Formulaire/getContenu',
        async: false,
        data: {
            'typeFormulaire': typeFormulaire,
            'typeClient': typeClient,
            'typeDevice': typeDevice,
            'idSection': idSection,
            'lcdvForm': lcdvForm,
            'email': email,
            'isDeployed': isDeployed,
            'contextForm' : contextForm,
            'form_page_pid' : iFormPageId
        },
        success: function(e) {
            gtmCit.initNewGTM();
        }
    });
}



function reinitForm(idPage, zoneOrder, areaId, zoneTid, idParent, lcdvForm, email, contextForm) {
    $.ajax({
        url: '/_/Layout_Citroen_Formulaire/reinitForm',
        async: false,
        data: {
            'idPage': idPage,
            'areaId': areaId,
            'zoneTid': zoneTid,
            'zoneOrder': zoneOrder,
            'lcdvForm': lcdvForm,
            'email': email,
            'contextForm' : contextForm
        },
        success: function(data) {
            $('#' + idParent).html(data);
            initFormulaire();
            gtmCit.initNewGTM();
        }
    });
}
function getINCEForm(TYPE_ID, USER_TYPE_CODE, EQUIPEMENT_CODE, CONTEXT_CODE, InceCode) {
    $.ajax({
        url: '/_/Layout_Citroen_Formulaire/getINCECode',
        async: false,
        data: {
            'TYPE_ID': TYPE_ID,
            'USER_TYPE_CODE': USER_TYPE_CODE,
            'EQUIPEMENT_CODE': EQUIPEMENT_CODE,
            'CONTEXT_CODE': CONTEXT_CODE
        },
        success: function(data) {
            data = JSON.parse(data);
            if(data && data['FORM_INCE_CODE']){
                InceCode = data['FORM_INCE_CODE'];
            }
        }
    });
    return InceCode;
}
function finalStepFunction(dataForm, idSection, idForm, formType) {
    var idPage = $('input[name=' + idSection + 'idPage]').val();
    var zoneOrder = $('input[name=' + idSection + 'zoneOrder]').val();
    var areaId = $('input[name=' + idSection + 'areaId]').val();
    var zoneTid = $('input[name=' + idSection + 'zoneTid]').val();
    var isDeployed = $('input[name=' + idSection + 'deployed]').val();
    var iFormPageId = $('input[name=' + idSection + 'form_page_pid]').val();
    var typeOfForm =  $('input[name=' + idSection + 'FORM_CONTEXT_CODE]').val();
	var page_title =  $('input[name=form_page_title]').val();

    var params = {};
    var arDatas = dataForm.message.split('&');

    arDatas.forEach(function(part) {

        var pair = part.split('=');
        pair[0] = decodeURIComponent(pair[0]);
        pair[1] = decodeURIComponent(pair[1]);
        params[pair[0]] = (pair[1] !== 'undefined') ?
            pair[1] : true;
    });
    if (typeof formType == 'undefined' || formType == '') {
        formType = '';
    }
    var car = null;
    if (typeof params['car'] != 'undefined' && params['car'] != '') {
        var car = params['car'];
    }
    params['contextForm']=typeOfForm;


    var loader = new Loader($('#' + idSection));
    loader.show(LoadingKey, false);

    // Vérification arrivée depuis un CTA
    var formOrigin = window.location.href.match(/[?&]origin=ctaperso/i) ? "ctaperso" : null;

    $.ajax({
        url: '/forms/' + formType + '/Layout_Citroen_Formulaire/finalStep',
        async: false,
        type: 'POST',
        data: {
            'params': params,
            'idPage': idPage,
            'areaId': areaId,
            'zoneTid': zoneTid,
            'zoneOrder': zoneOrder,
            'isDeployed': isDeployed,
            'idForm': idForm,
            'car': car,
            'formOrigin': formOrigin,
            'form_page_pid' : iFormPageId,
			'page_title':page_title

        },
        success: function(data) {
            loader.hide();
            $('.' + idSection + 'Chapo').hide();

            if(navigator.appVersion.indexOf("MSIE 9.")!=-1){
                $('#' + idSection).html(data);
            }else{
                $('#wf_form_content').html(data);
            }
            buttonForm();
            ReinitializeAddThis();
            $('.tooltip,.texttip').each(tooltip.build);
            if ($('a[name=ESSAYER]').size() > 0) {
                $(document).scrollTop($("a[name=ESSAYER]").offset().top);
            } else {
                $(document).scrollTop($("a[name=" + idSection + "anchor]").offset().top);
            }

            gtmCit.initNewGTM();
        }

    });
}
function ReinitializeAddThis() {
    try {
        addthis.toolbox('.addthis_toolbox');
    }
    catch(e) {
        console.log('erreur call : addthis.toolbox  addthis is not defined !');
    }
}

function buttonForm() {
    $('.addSelectionForm a').on('click', function() {
        var rel = $(this).attr('rel');
        var params = rel.split('_');
        var aParams = {};
        aParams['order'] = params[1];
        aParams['lcdv6_code'] = params[0];
        aParams['lcdv6'] = params[0];
        aParams['isForm'] = true;
        $.ajax({
            url: '/_/Layout_Citroen_MonProjet_SelectionVehicules/addToSelectionAjax',
            data: aParams,
            type: 'post',
            dataType: 'json',
            success: function(data) {
                promptPop(data.message);
            }
        });
    });
    $('.bookmarkForm a').on('click', function(e) {
        var id = $(this).attr('rel');
        var bookmarkTpl = $(document).find('.bookmark').html();
        $.ajax({
            url: '/_/Layout_Citroen_PointsDeVente/ajaxPdvBookmarkGet',
            dataType: 'json',
            success: function(data) {
                // DÃƒÂ©finition de la fonction temporaire qui enregistre le point de vente
                var ajaxCallSavePdvBookmark2 = function() {
                    $.ajax({
                        url: '/_/Layout_Citroen_PointsDeVente/ajaxPdvBookmarkSet',
                        dataType: 'json',
                        cache: false,
                        data: {
                            pdvId: id
                        },
                        success: function(data2) {
                            // console.log('favori enregistrÃƒÂ©');

                            $.fancybox.close();
                            if (typeof data2.bookmark_btn_label) {
                                $placeholder.find('.bookmarks a').html(data2.bookmark_btn_label).removeAttr('href').unbind('click');
                            }
                        }
                    });
                }


                // Si l'utilisateur est connectÃƒÂ© et a dÃƒÂ©jÃƒ  une concession favorite
                // ou utilisateur non connectÃƒÂ© & pdv dÃƒÂ©fini dans un cookie
                // => on affiche la popin "Vous avez dÃƒÂ©jÃƒ  une concession favorite"
                if ((data.loggedin == true && data.favoris_db.favoris_vn != null && typeof data.favoris_db.favoris_vn != 'undefined') || (data.loggedin == false && data.favoris_cookie.favoris_vn != null && typeof data.favoris_cookie.favoris_vn != 'undefined')) {
                    var output = _.template(bookmarkTpl, {id: id, name: data.favoris_vn_name});
                    promptPop(output);

                    // Popin : clic sur bouton confirmer
                    $('.fancybox-inner .actions .green>a').click(function(e) {
                        ajaxCallSavePdvBookmark2(); // Enregistrement du favori
                    });

                    // Popin : clic sur bouton annuler
                    $('.fancybox-inner .actions .grey>a').click(function(e) {
                        $.fancybox.close();
                    });

                    return;
                }
                ajaxCallSavePdvBookmark2(); // Enregistrement du favori
            }
        });
    });
}
function hasATactileScreen(id){
    if(id==1){
        $('.saisie_VIN').show();
        $('.message_ineligibilite').hide();
        $('.retour_ajax').hide();
    }
    else{
        $('.saisie_VIN').hide();
        $('.message_ineligibilite').show();
        $('.retour_ajax').hide();
    }
}
function checkEligibilityLinkMyCitroen(VIN,pid,pversion){


    $.ajax({
        url: '/_/Layout_Citroen_EligibiliteLinkMyCitroen/check',
        async: false,
        dataType: 'json',
        data: {
            'VIN': VIN,
            'pid': pid,
            'pversion':pversion
        },
        success: function(e) {
            if(e['invalide_size']){
                var notice = ($('.edge-notice').html());
                promptPop(notice);
                $('.continued').on("click", function(e){
                    $('.edge-notice').hide();
                    $('.fancybox-skin').hide();
                    $('#vin').focus();
                });
            }
            else{
                $('.retour_ajax').show();
                $('.retour_ajax').html(e['message']);
            }
        }

    } );

    return false;
}
function getParameterByName(name) {
    var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
}
function chargeIframeDeploy(url_web_deploy){



    $('section#'+url_web_deploy).each(function() {

        var formActivation = $(this).find('input[name=formActivation]').val();
        var typeFormulaire = $(this).find('input[name=typeFormulaire]').val();
        var typeDevice = $(this).find('input[name=typeDevice]').val();
        var InceCode = $(this).find('input[name=InceCode]').val();
        var lcdvForm = $(this).find('input[name=lcdv6Form]').val();
        var iPageId = $(this).find('input[name=form_page_pid]').val();
        var idSection = $(this).attr('id');
		var gdoMarketingCode = $(this).find('input[name=gdoMarketingCode]').val();
        var email = $(this).find('input[name=email]').val();
        var formTypeLabel = $(this).find('input[name=formTypeLabel]').val();
        var isDeployed = $(this).find('input[name=deployed]').val();
        var typeOfForm =  $(this).find('input[name=FORM_CONTEXT_CODE]').val();
        var isRTO = $("#isPDV").val();

        var formEquipCode =  $(this).find('input[name=EQUIPEMENT_CODE]').val();
        var formIDType =  $(this).find('input[name=TYPE_ID]').val();
        var formUserCode =  $(this).find('input[name=USER_TYPE_CODE]').val();

        var contextForm = "";

        var deployable= getParameterByName('deployable_id');


        if (formActivation != 'CHOIX' && ( !$(this).parent().hasClass('secret') ||  typeOfForm == 'RTO' || typeOfForm=='CAR')) {

            if(lcdvForm)
            {
                contextForm = "CAR";
            }
            if(typeOfForm == 'RTO')
            {
                contextForm =  'RTO' ;
                InceCode = getINCEForm(formIDType, formUserCode, formEquipCode, contextForm, InceCode);
            }
        }

        getFormId(InceCode, formActivation, idSection, lcdvForm, email, formTypeLabel, isDeployed, contextForm, iPageId, gdoMarketingCode);
    });
}


function resize_iframe(iframe) {
    // var iframeid = iframe.id;
    //find the height of the internal page
    // var the_height= document.getElementById(iframeid).contentWindow.document.body.scrollHeight;
    //change the height of the iframe
    // document.getElementById(iframeid).height=the_height;
    // $('div.loading').remove();
}

$('input#searchSubmit').on("click", function(e){
    $('form#searchHeader').submit();
});

$('div.closer').on("click", function(e){
    $(this).parent().addClass('hidden');
});

(function ($) {
    return $(document).ready(function () {
        var allDate;
        var checkScroll;
        $('.stickyDateNav li a').on('click', function (e) {
            var toGo;
            e.preventDefault();
           toGo = $($(this).attr('href')).offset().top;
            $('html, body').scrollTop(toGo + 1);
            return checkScroll();
       });
        allDate = $('.stickyDateNav .dateColumn a');
        checkScroll = function () {
            var overed;
			if($(window).scrollTop() > $('.stickyDateNav').offset()){
				if ($(window).scrollTop() > $('.stickyDateNav').offset().top) {
					$('.stickyDateNav').addClass('stickyActive');
				} else {
					$('.stickyDateNav').removeClass('stickyActive');
				}
			}
            overed = false;
            allDate.each(function (key, el) {
                if ($(window).scrollTop() > $($(this).attr('href')).offset().top) {
                    overed = true;
                    allDate.removeClass('active');
                    return $(this).addClass('active');
                }
            });
            return overed = false;
        };
        $('.wrapperDate .arrowTop').on('click', function () {
            return $('html, body').animate({ scrollTop: 0 }, 'slow');
        });
        return $(window).on('scroll', function () {
            return checkScroll();
        });
    });
}(jQuery));
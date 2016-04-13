/*---------------------------------------------------------------------------------------------

@author       Constantin Saguin - @brutaldesign
@link            http://bsign.co
@github        http://github.com/brutaldesign/swipebox
@version     1.1.2
@license      MIT License

----------------------------------------------------------------------------------------------*/

;(function (window, document, $, undefined) {

	$.swipebox = function(elem, options) {

		var defaults = {
			useCSS : true,
			hideBarsDelay : 3000, 
			pager: true,												// true / false - display a pager
			pagerSelector: null,								// jQuery selector - element to contain the pager. ex: '#pager'
			pagerType: 'full',									// 'full', 'short' - if 'full' pager displays 1,2,3... if 'short' pager displays 1 / 4
			pagerLocation: 'bottom',						// 'bottom', 'top' - location of pager
			pagerShortSeparator: '/',						// string - ex: 'of' pager would display 1 of 4
			pagerActiveClass: 'pager-active',				// function(currentSlideNumber, totalSlideQty, currentSlideHtmlObject) - advanced use only! see the tutorial here: http://bxslider.com/custom-pager
			
			startingSlide: 0, 	
			moveSlideQty: 1,	
			buildPager: null,	
		},

			plugin = this,
			$elem = $(elem),
			elem = elem,
			selector = elem.selector,
			$selector = $(selector),
			isTouch = document.createTouch !== undefined || ('ontouchstart' in window) || ('onmsgesturechange' in window) || navigator.msMaxTouchPoints,
			supportSVG = !!(window.SVGSVGElement),
			html = '<div id="swipebox-overlay">\
					<a id="swipebox-close"></a>\
					<div id="swipebox-slider"></div>\
					<div id="swipebox-action"></div>\
			</div>';

		// initialize (and localize) all variables= '';	
		var $parent = '';
		var base = this;
		var $pager = '';
		var $outerWrapper ='';
		var isWorking = false;
    
		var firstSlide = 0;
		var lastSlide = $('#swipebox-slider .slide').length - 1;
		
		plugin.settings = {}

		plugin.init = function(){

			plugin.settings = $.extend({}, defaults, options);

			$selector.click(function(e){
				e.preventDefault();
				e.stopPropagation();
				index = $elem.index($(this));
				ui.target = $(e.target);
				ui.init(index);
			});
		}

		var ui = {

			init : function(index){
				$parent = $(this);
				this.target.trigger('swipebox-start');
				this.build();
				this.openSlide(index);
				this.openImg(index);
				this.preloadImg(index+1);
				this.preloadImg(index-1);
			},

			build : function(){
				var $this = this;

				$('body').append(html);

				if($this.doCssTrans()){
					$('#swipebox-slider').css({
						'-webkit-transition' : 'left 0.4s ease',
						'-moz-transition' : 'left 0.4s ease',
						'-o-transition' : 'left 0.4s ease',
						'-khtml-transition' : 'left 0.4s ease',
						'transition' : 'left 0.4s ease'
					});
					$('#swipebox-overlay').css({
						'-webkit-transition' : 'opacity 1s ease',
						'-moz-transition' : 'opacity 1s ease',
						'-o-transition' : 'opacity 1s ease',
						'-khtml-transition' : 'opacity 1s ease',
						'transition' : 'opacity 1s ease'
					});
					$('#swipebox-action, #swipebox-caption').css({
						'-webkit-transition' : '0.5s',
						'-moz-transition' : '0.5s',
						'-o-transition' : '0.5s',
						'-khtml-transition' : '0.5s',
						'transition' : '0.5s'
					});
				}

				$elem.each(function(){
					$('#swipebox-slider').append('<div class="slide"></div>');
				});


				$this.showPager('full');
				$this.setDim();
				$this.actions();
				$this.keyboard();
				$this.gesture();
				$this.animBars();

				$(window).resize(function() {
					$this.setDim();
				}).resize();
			},

			setDim : function(){
				var sliderCss = {
					width : $(window).width(),
					height : window.innerHeight ? window.innerHeight : $(window).height() // fix IOS bug
				}

				$('#swipebox-overlay').css(sliderCss);

			},

			supportTransition : function() {
				var prefixes = 'transition WebkitTransition MozTransition OTransition msTransition KhtmlTransition'.split(' ');
				for(var i = 0; i < prefixes.length; i++) {
					if(document.createElement('div').style[prefixes[i]] !== undefined) {
						return prefixes[i];
					}
				}
				return false;
			},

			doCssTrans : function(){
				if(plugin.settings.useCSS && this.supportTransition() ){
					return true;
				}
			},

			gesture : function(){
				if ( isTouch ){
					var $this = this,
					distance = null,
					swipMinDistance = 10,
					startCoords = {}, 
					endCoords = {};
					var b = $('#swipebox-caption, #swipebox-action');

					b.addClass('visible-bars');
					$this.setTimeout();

					$('body').bind('touchstart', function(e){

						$(this).addClass('touching');

		  				endCoords = e.originalEvent.targetTouches[0];
		    				startCoords.pageX = e.originalEvent.targetTouches[0].pageX;

						$('.touching').bind('touchmove',function(e){
							e.preventDefault();
							e.stopPropagation();
		    					endCoords = e.originalEvent.targetTouches[0];

						});

			           			return false;

	           			}).bind('touchend',function(e){
	           				e.preventDefault();
						e.stopPropagation();

	   					distance = endCoords.pageX - startCoords.pageX;

	       				if( distance >= swipMinDistance ){
	       					// swipeLeft
	       					$this.getPrev();
	       				}

	       				else if( distance <= - swipMinDistance ){
	       					// swipeRight
	       					$this.getNext();

	       				}else{
	       					// tap
	       					if(!b.hasClass('visible-bars')){
							$this.showBars();
							$this.setTimeout();
						}else{
							$this.clearTimeout();
							$this.hideBars();
						}

	       				}	

	       				$('.touching').off('touchmove').removeClass('touching');

					});

           			}
			},

			setTimeout: function(){
				if(plugin.settings.hideBarsDelay > 0){
					var $this = this;
					$this.clearTimeout();
					$this.timeout = window.setTimeout( function(){
						$this.hideBars() },
						plugin.settings.hideBarsDelay
					);
				}
			},

			clearTimeout: function(){	
				window.clearTimeout(this.timeout);
				this.timeout = null;
			},

			showBars : function(){
				var b = $('#swipebox-caption, #swipebox-action');
				if(this.doCssTrans()){
					b.addClass('visible-bars');
				}else{
					$('#swipebox-caption').animate({ top : 0 }, 500);
					$('#swipebox-action').animate({ bottom : 0 }, 500);
					setTimeout(function(){
						b.addClass('visible-bars');
					}, 1000);
				}
			},

			hideBars : function(){
				var b = $('#swipebox-caption, #swipebox-action');
				if(this.doCssTrans()){
					b.removeClass('visible-bars');
				}else{
					$('#swipebox-caption').animate({ top : '-50px' }, 500);
					$('#swipebox-action').animate({ bottom : '-50px' }, 500);
					setTimeout(function(){
						b.removeClass('visible-bars');
					}, 1000);
				}
			},

			animBars : function(){
				var $this = this;
				var b = $('#swipebox-caption, #swipebox-action');

				b.addClass('visible-bars');
				$this.setTimeout();

				$('#swipebox-slider').click(function(e){
					if(!b.hasClass('visible-bars')){
						$this.showBars();
						$this.setTimeout();
					}
				});

				$('#swipebox-action').hover(function() {
				  		$this.showBars();
						b.addClass('force-visible-bars');
						$this.clearTimeout();

					},function() { 
						b.removeClass('force-visible-bars');
						$this.setTimeout();

				});
			},

			keyboard : function(){
				var $this = this;
				$(window).bind('keyup', function(e){
					e.preventDefault();
					e.stopPropagation();
					if (e.keyCode == 37){
						$this.getPrev();
					}
					else if (e.keyCode==39){
						$this.getNext();
					}
					else if (e.keyCode == 27) {
						$this.closeSlide();
					}
				});
			},

			actions : function(){
				var $this = this;

				if( $elem.length < 2 ){
					$('#swipebox-prev, #swipebox-next').hide();
				}else{
					$('#swipebox-prev').bind('click touchend', function(e){
						e.preventDefault();
						e.stopPropagation();
						$this.getPrev();
						$this.setTimeout();
					});

					$('#swipebox-next').bind('click touchend', function(e){
						e.preventDefault();
						e.stopPropagation();
						$this.getNext();
						$this.setTimeout();
					});

					$('.pager-link').each(function(index,el){
						$(el).bind('click touchend', function(e){
							e.preventDefault();
							e.stopPropagation();
							$this.setSlide(index);
							$this.preloadImg(index-1);
							$this.openImg(index);
							$this.preloadImg(index+1);
							$this.setTimeout();
						});
					});
				}
				

				$('#swipebox-slider').bind('tapone', function(e){
					e.preventDefault();
					e.stopPropagation();
					$this.closeSlide();
				});
				

				

				$('#swipebox-close').bind('click touchend', function(e){
					$this.closeSlide();
				});
			},

			setSlide : function (index, isFirst){
				isFirst = isFirst || false;

				var slider = $('#swipebox-slider');

				if(this.doCssTrans()){
					slider.css({ left : (-index*100)+'%' });
				}else{
					slider.animate({ left : (-index*100)+'%' });
				}

				$('#swipebox-slider .slide').removeClass('current');
				$('#swipebox-slider .slide').eq(index).addClass('current');
				this.setTitle(index);

				if( isFirst ){
					slider.fadeIn();
				}

				$('#swipebox-prev, #swipebox-next').removeClass('disabled');
				if(index == 0){
					$('#swipebox-prev').addClass('disabled');
				}else if( index == $elem.length - 1 ){
					$('#swipebox-next').addClass('disabled');
				}
				this.makeSlideActive(index);
			},

			openSlide : function (index){

				$('html').addClass('swipebox');
				$(window).trigger('resize'); // fix scroll bar visibility on desktop
				this.setSlide(index, true);
			},

			preloadImg : function (index){
				var $this = this;
				setTimeout(function(){
					$this.openImg(index);
				}, 1000);
			},

			openImg : function (index){
				var $this = this;
				if(index < 0 || index >= $elem.length){
					return false;
				}

				$this.loadImg($elem.eq(index).attr('href'), function(){
					$('#swipebox-slider .slide').eq(index).html(this);
				});
			},


			setTitle : function(index, isFirst){
				$('#swipebox-caption').empty();

				if($elem.eq(index).attr('title')){
					$('#swipebox-caption').append($elem.eq(index).attr('title'));
				}
			},

			loadImg : function (src, callback){
				var img = $('<img>').on('load', function(){
					callback.call(img);
				});

				img.attr('src',src);
			},

	
			makeSlideActive : function(number){
				if(plugin.settings.pagerType == 'full' && plugin.settings.pager){
					// remove all active classes
					$('a', $pager).removeClass(plugin.settings.pagerActiveClass);
					// assign active class to appropriate slide
					$('a', $pager).eq(number).addClass(plugin.settings.pagerActiveClass);
				}else if(plugin.settings.pagerType == 'short' && plugin.settings.pager){
					$('.bx-pager-current', $pager).html(currentSlide+1);
				}
			}, 

			getNext : function (){
				var $this = this;
				index = $('#swipebox-slider .slide').index($('#swipebox-slider .slide.current'));
				if(index+1 < $elem.length){
					index++;
					$this.setSlide(index);
					$this.preloadImg(index+1);
				}
				else{

					$('#swipebox-slider').addClass('rightSpring');
					setTimeout(function(){
						$('#swipebox-slider').removeClass('rightSpring');
					},500);
				}
			},

			getPrev : function (){
				var $this = this;
				index = $('#swipebox-slider .slide').index($('#swipebox-slider .slide.current'));
				if(index > 0){
					index--;
					$this.setSlide(index);
					$this.preloadImg(index-1);
				}
				else{

					$('#swipebox-slider').addClass('leftSpring');
					setTimeout(function(){
						$('#swipebox-slider').removeClass('leftSpring');
					},500);
				}
			},
			
			/**
			 * Displays the pager
			 *
			 * @param string type 'full', 'short'
			 */		
			showPager : function(type){
				// sets up logic for finite multi slide shows
				var pagerQty = $('#swipebox-slider .slide').length;
				var pagerString = '';
				
				// build the full pager
				for(var i=1; i<=pagerQty; i++){
					pagerString += '<a href="" class="pager-link pager-'+i+'">'+i+'</a>';
				}
				var $pagerContainer = $('<div class="bx-pager"></div>');
				$pagerContainer.append(pagerString);
				$outerWrapper = $('#swipebox-action');
				// attach the pager to the DOM
				if(plugin.settings.pagerLocation == 'top'){
					$outerWrapper.prepend($pagerContainer);
				}else if(plugin.settings.pagerLocation == 'bottom'){
				
					$outerWrapper.append($pagerContainer);
				}
				// cache the pager element
				$pager = $outerWrapper.children('.bx-pager');
					/*
				$pager.children().click(function(e) {
					e.preventDefault();
					var slideIndex = $pager.children().index(this);
					goToSlide(slideIndex);
					return false;
				});
				*/
			}, 
			
			closeSlide : function (){
				var $this = this;
				$(window).trigger('resize');
				$('html').removeClass('swipebox');
				$this.destroy();
			},

			destroy : function(){
				var $this = this;
				$(window).unbind('keyup');
				$('body').unbind('touchstart');
				$('body').unbind('touchmove');
				$('body').unbind('touchend');
				$('#swipebox-slider').unbind();
				$('#swipebox-overlay').remove();
				$elem.removeData('_swipebox');
				$this.target.trigger('swipebox-destroy');
 			}

		}

		plugin.init();

	}

	$.fn.swipebox = function(options){
		if (!$.data(this, "_swipebox")) {
			var swipebox = new $.swipebox(this, options);
			this.data('_swipebox', swipebox);
		}
	}

}(window, document, jQuery));
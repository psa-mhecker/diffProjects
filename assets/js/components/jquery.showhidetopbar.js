;(function($, window, document, undefined) {

	// Create the defaults once
	var pluginName = "showHideTopBar",
		defaults = {
			animating: false,
			previousScroll: 0,
			topToHide: -500,
			topToShow: 0,
			duration: 300,
			style: {
				"position": "fixed",
				"left": "inherit",
				"z-index": 11,
				"width": "100%",
				"max-width": "1280px"
			}
		};


	var ShowHideTopBar = function (element, options) {

		this.element = element;

		this.options = $.extend({}, defaults, options);

		this._defaults = defaults;
		this._name = pluginName;

		this._init();
	};

	ShowHideTopBar.prototype = {

		_init: function() {
			this.throttledScrollCallback = _.throttle(this._scrollCallback.bind(this), 100);
			$(this.options.stickyEl).on('enableShowHideTopBar', this._enableShowHideTopBar.bind(this));
			$(this.options.stickyEl).on('disableShowHideTopBar', this._disableShowHideTopBar.bind(this));
		},

		_enableShowHideTopBar: function(){
			$(window).on('scroll', this.throttledScrollCallback);
		},

		_disableShowHideTopBar: function(){
			$(window).off('scroll', this.throttledScrollCallback);
		},

		_scrollCallback: function() {

			var elOuterHeight = $(this.options.stickyEl).outerHeight();
			var containerTopPosition = $(this.element).position().top;
			var currentScroll = $(window).scrollTop();

			//Defining when to disable sticky behavior
			if ( currentScroll <= containerTopPosition ){
				$(this.options.stickyEl).removeAttr('style');
			}
			/*
			  If the current scroll position is greater than 0 (the top)
			*/
			else if (currentScroll > containerTopPosition + elOuterHeight){

				/*
					If the current scroll is greater than the previous scroll 
					(i.e we're scrolling down the page), hide the nav.
				*/
				if (currentScroll >= this.options.previousScroll){
					this._showOrHideNav(this.options.topToHide, this.options.duration);

				/*
					Else we are scrolling up (i.e the previous scroll is greater than 
					the current scroll), so show the nav.
				*/
				} else {
					var $pt21 = $('.slice-pt21');

					if ($pt21.length && !$pt21.hasClass('nav-light')) {
						var $mainNav = $pt21.find('#principal-nav');
						if ($mainNav.length && window.matchMedia("(min-width: 641px)").matches) {
							this.options.style.width = $('.body').width();
						}
					}
					$(this.options.stickyEl).css(this.options.style);
					this._showOrHideNav(this.options.topToShow, this.options.duration);
				}

			}
			

			/* 
				Set the previous scroll value equal to the current scroll.
			*/
			this.options.previousScroll = currentScroll;

		},

		_showOrHideNav: function(top, duration) {
			var self = this;
			if ( !this.options.animating ){
				var animationConfig = {
					duration: duration,
					start: function(){
						self.options.animating = true;
					},
					complete: function(){
						self.options.animating = false;
					}
				};
				$(this.options.stickyEl).animate({top: top}, animationConfig);
			}
		}

	};

	$.fn[pluginName] = function(options) {
		return this.each(function() {
			if (!$.data(this, "plugin_" + pluginName)) {
				$.data(this, "plugin_" + pluginName,
					new ShowHideTopBar(this, options));
			}
		});
	};


})(jQuery, window, document);

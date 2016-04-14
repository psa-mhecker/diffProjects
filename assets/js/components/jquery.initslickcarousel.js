;(function($, window, document, undefined) {

	// Create the defaults once
	var pluginName = "initSlickCarousel",
		defaults = {
			dots: true,
			speed: 300,
			autoplay: true,
			autoplaySpeed: 5000,
			pauseOnHover: false,
			pauseOnFocus: false,
			lazyLoad: 'ondemand',
			prevArrow: '<div class="js-slick-arrow slick-prev"></div>',
			nextArrow: '<div class="js-slick-arrow slick-next"></div>',
			easing: "easeInOutCubic"
		};


	var InitSlickCarousel = function (element, options) {

		this.element = element;
		this.$element = $(this.element);

		this.options = $.extend({}, defaults, options, $(element).data('options') || {});

		this._defaults = defaults;
		this._name = pluginName;

		this._init();
	};

	InitSlickCarousel.prototype = {

		_init: function() {

			//Init carousel
			this.carousel = this.$element.find('.js-slick-carousel');
			if (this.carousel) {
				this.carousel.slick(this.options);
			}

			//Overridding speed with back-office parameter
			this._setAutoPlaySpeed(this.carousel.data("timer"));

			//If using arrows, stop autoplay
			this.carousel.find('.slick-arrow').one('click', this._stopAutoPlay.bind(this));

			if(this.$element.data('customThumbnail')){
				this._initCustomThumbnail();
			}

			//Init transition logic on objects to sync with the slides
			this._initToSyncTransition();

			//Init tagEvent logic
			this._initGtm();

		},

		_setAutoPlaySpeed: function(autoplaySpeed) {
			if (autoplaySpeed) {
				this.carousel.slick('slickSetOption', 'autoplaySpeed', autoplaySpeed);
			}
		},

		_stopAutoPlay: function() {
			this.carousel.slick('slickPause');
			this.carousel.slick('slickSetOption', 'autoplay', false);
		},

		_initCustomThumbnail: function(){
			var thumbnails = this.$element.parents('.js-custom-thumbnail-container').find('.js-item-thumbnail');
			thumbnails.first().addClass('active');
			var myCarousel = this.carousel;
			thumbnails.on('click',function(){
				thumbnails.removeClass('active');
				$(this).toggleClass('active');
				myCarousel.slick("slickGoTo" , $(this).data('slickLink'));
			});
		},

		_initToSyncTransition: function() {
			//Getting objects to sync
			var toSync = $(this.element).find('.js-slick-carousel-to-sync [data-slick-to-sync]');

			if (toSync.length) {

				//At first, show only the first sync object with the first slide; hide the rest
				var firstToSync = toSync.filter('[data-slick-to-sync=0]');
				if (firstToSync.length) {
					$(_.rest(toSync)).hide();
				}
				else {
					$(toSync).hide();
				}

				//On change, switch sync object
				this.carousel.on('beforeChange', function(event, slick, currentSlide, nextSlide) {

					toSync.hide();

					var nextSync = toSync.filter('[data-slick-to-sync='+nextSlide+']');
					if (nextSync) {
						nextSync.show();
					}
				});
			}
		},

		_initGtm: function() {

			var gtmCategory = $(this.carousel).data('gtmCategory');
			var baseData = {
				event: 'uaevent',
				eventCategory: gtmCategory
			};
			$(this.element).on('click', '.js-slick-arrow', function(e) {
				var data;
				if ($(e.target).hasClass('slick-prev')) {
					data = {
						eventAction: 'Select::Previous',
						eventLabel: 'Previous',
					};
				}
				else {
					data = {
						eventAction: 'Select::Next',
						eventLabel: 'Next',
					};
				}
				window.NDP.TrackEventGTM.pushToDataLayer($.extend(baseData, data));
			}.bind(this));

		}

	};

	$.fn[pluginName] = function(options) {
		return this.each(function() {
			if (!$.data(this, "plugin_" + pluginName)) {
				$.data(this, "plugin_" + pluginName,
					new InitSlickCarousel(this, options));
			}
		});
	};


})(jQuery, window, document);

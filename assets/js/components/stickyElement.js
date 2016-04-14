/**
 * This contains the code for the animation of the sticky element for recap container
 *
 */
;(function ($, window, document, undefined) {

	var pluginName = "stickyElement",
		defaults = {};

	function StickyElement(element, options) {
		this.stickyContainer = $('#stickyContainer');
		this.element = element;
		this.options = $.extend({}, defaults, options);
		this._defaults = defaults;
		this._name = pluginName;
		this._yStartToStick = this.stickyContainer.offset().top;
		this.init();
	}

	StickyElement.prototype = {

		init: function () {

			if (matchMedia('only screen and (min-width: 641px)').matches) {
				$(window).scroll(_.throttle(this.updateStickyContainer.bind(this), 100));
				$(window).resize(_.throttle(this.updateStickyContainer.bind(this), 100));
				this.updateStickyContainer();
			} else {
				$(window).scroll(_.throttle(this.updateStickyContainerForMobile.bind(this), 100));
				$(window).resize(_.throttle(this.updateStickyContainerForMobile.bind(this), 100));
				this.updateStickyContainerForMobile();
			}

		},
		updateStickyContainer: function () {

			var yWindowPosition = $(window).scrollTop();
			var $containerRecap = $(this.element).find('.container-recap');

			if (yWindowPosition > this._yStartToStick) {

				this.stickyContainer.css('width', document.getElementById('stickyContainer').offsetWidth + 'px');
				$containerRecap.css('margin-bottom', '0px');
				this.stickyContainer.addClass('stick');

				var yCurrentFooterPosition = $('.footer').offset().top;
				var scrollBottom = yWindowPosition + $(window).height();

				if (scrollBottom > yCurrentFooterPosition) {
					this.stickyContainer.removeClass('stick');
					this.stickyContainer.css('width', '');
					$containerRecap.css('margin-bottom', '');
				}

			} else {
				this.stickyContainer.removeClass('stick');
				this.stickyContainer.css('width', '');
				$containerRecap.css('margin-bottom', '');
			}

		},
		updateStickyContainerForMobile: function () {

			var yWindowPosition = $(window).scrollTop();
			var $containerRecapMobileTablet = $(this.element).find('.container-recap-mobile, .container-recap');

			if (yWindowPosition > this._yStartToStick) {

				this.stickyContainer.css('width', document.getElementById('stickyContainer').offsetWidth + 'px');
				$containerRecapMobileTablet.css('padding', '0px');
				$containerRecapMobileTablet.css('margin-bottom', '0px');
				this.stickyContainer.addClass('stick-mobile');

				var yCurrentFooterPosition = $('.footer').offset().top;
				var scrollBottom = yWindowPosition + $(window).height();

				if (scrollBottom > yCurrentFooterPosition) {
					this.stickyContainer.removeClass('stick-mobile');
					this.stickyContainer.css('width', '');
					$containerRecapMobileTablet.css('margin-bottom', '');
					$containerRecapMobileTablet.css('padding', '');
				}

			} else {
				this.stickyContainer.removeClass('stick-mobile');
				this.stickyContainer.css('width', '');
				$containerRecapMobileTablet.css('margin-bottom', '');
				$containerRecapMobileTablet.css('padding', '');
			}
		}

	};

	$.fn[pluginName] = function (options) {
		return this.each(function () {
			if (!$.data(this, "plugin_" + pluginName)) {
				$.data(this, "plugin_" + pluginName,
					new StickyElement(this, options));
			}
		});
	};

})(jQuery, window, document);

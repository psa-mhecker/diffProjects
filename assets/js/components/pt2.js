/* PT2 - Footer */
;(function ($, window, document, undefined) {

	// Create the defaults once
	var pluginName = "footer",
		defaults = {};

	// The actual plugin constructor
	function Footer(element, options) {
		this.element = element;

		this.options = $.extend({}, defaults, options);

		this._defaults = defaults;
		this._name = pluginName;

		this.init();
	}

	Footer.prototype = {
		init: function () {
			var $el = $(this.element),
				$siteMapExpandBtn = $el.find('.site-map-expand-btn'),
				$siteMapContainer = $el.find('.site-map-expand'),
				$form             = $el.find('.newsletter-form');

			$siteMapExpandBtn.on('click', function(e) {
				e.preventDefault();
				if ($siteMapContainer.is(':hidden')) {
					NDP.TrackEventGTM.pushToDataLayer({
						event: 'uaevent',
						eventCategory: 'PT2_Footer_cross section::position-last',
						eventAction: 'Display::Sitemap',
						eventLabel: 'Sitemap'
					});
					$siteMapExpandBtn.addClass('open');
					$siteMapContainer.slideDown(200, function() {
						$("html, body").animate({ scrollTop:$(this).offset().top }, 1000);
					});
				} else {
					$siteMapContainer.slideUp(200, function() {
						$siteMapExpandBtn.removeClass('open');
					});
				}
			});
			$(window).on('scroll', _.debounce(this._scrolledToBottom.bind(this), 200));
		},
		_scrolledToBottom: function() {
			var $window = $(window);
			if($(document).height() === $window.innerHeight() + $window.scrollTop()) {
				NDP.TrackEventGTM.pushToDataLayer({
					event: 'uaevent',
					eventCategory: 'UX',
					eventAction: 'Scroll::ReachBottom',
					eventLabel: '100%'
				});
			}
		}
	};

	$.fn[pluginName] = function (options) {
		return this.each(function () {
			if (!$.data(this, "plugin_" + pluginName)) {
				$.data(this, "plugin_" + pluginName,
					new Footer(this, options));
			}
		});
	};

})(jQuery, window, document);

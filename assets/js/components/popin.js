/**
 * This file handles the feature of the popin for the psa ndp website based on the plugin jquery blueimp
 * Gallery
 *
 * https://github.com/blueimp/Gallery
 *
 */
;(function ($, window, document, undefined) {

	var pluginName = "popin",
		defaults = {};

	function Popin(element, options) {
		this.element = element;
		this.options = $.extend({}, defaults, options);
		this._defaults = defaults;
		this._name = pluginName;
		this.init();
	}

	Popin.prototype = {

		init: function () {
			var $links = $(this.element).find('.popin-links');

			$links.on('click', function (event) {
				event.preventDefault();
				event = event || window.event;

				var target = event.target || event.srcElement,
					link = target.src ? target.parentNode : target,
					options = {
						index: link.getAttribute('data-index'),
						event: event,
						transitionSpeed: 400,
						startSlideshow: false,
						closeOnSlideClick: true,
						closeOnEscape: true,
						enableKeyboardNavigation: true,
						continuous: true,
						carousel: true,
						onopened: function (index) {
							// Auto play the video when the gallery opens on that video
							var videoElement = $('#blueimp-gallery [data-index="' + index + '"] video').get(0);
							if (videoElement) {
								videoElement.play();
							}
							$('#blueimp-gallery .video-content').parent().append('<div class="error js-error hide"><div class="notification"><p><span class="information fade">i</span>'+translation.NDP_ERROR_VIDEO_STREAMLIKE+'</p></div></div>');
						}
					},
					links = this.getElementsByTagName('a');

				var gallery = blueimp.Gallery(links, options);
			});
		}

	};

	$.fn[pluginName] = function (options) {
		return this.each(function () {
			if (!$.data(this, "plugin_" + pluginName)) {
				$.data(this, "plugin_" + pluginName,
					new Popin(this, options));
			}
		});
	};

})(jQuery, window, document);

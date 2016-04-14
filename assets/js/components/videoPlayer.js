/*!
 * jQuery lightweight plugin boilerplate
 * Original author: @ajpiano
 * Further changes, comments: @addyosmani
 * Licensed under the MIT license
 */


// the semi-colon before the function invocation is a safety
// net against concatenated scripts and/or other plugins
// that are not closed properly.
;(function ( $, window, document, undefined ) {

	// undefined is used here as the undefined global
	// variable in ECMAScript 3 and is mutable (i.e. it can
	// be changed by someone else). undefined isn't really
	// being passed in so we can ensure that its value is
	// truly undefined. In ES5, undefined can no longer be
	// modified.

	// window and document are passed through as local
	// variables rather than as globals, because this (slightly)
	// quickens the resolution process and can be more
	// efficiently minified (especially when both are
	// regularly referenced in our plugin).

	// Create the defaults once
	var pluginName = "videoPlayer";
	var defaults = {};

	// The actual plugin constructor
	function Plugin( element, options ) {
		this.element = element;

		// jQuery has an extend method that merges the
		// contents of two or more objects, storing the
		// result in the first object. The first object
		// is generally empty because we don't want to alter
		// the default options for future instances of the plugin
		this.options = $.extend( {}, defaults, options) ;

		this._defaults = defaults;
		this._name = pluginName;

		this.init();
	}

	Plugin.prototype.init = function () {
		// Place initialization logic here
		// We already have access to the DOM element and
		// the options via the instance, e.g. this.element
		// and this.options


		//Common video elements found in the common tpl
		var $video = $(this.element).find('.js-video-player'),
			$playVideo = $(this.element).find('.js-play-button'),
			$closeButton = $(this.element).find('.js-close-button'),

			//Encapsulating common elements
			videoElements = {
				video: $video,
				playVideo: $playVideo,
				closeButton: $closeButton
			},

			//for closure
			self = this,
			gtmCategory = $(this.element).data('gtmcategory');

		if (!(window.isMobile || window.isTablet)) { // Hide controls if not in mobile or tablet
			$video.attr('controls', false);
		}

		/*
		* Encapsulating native html5 video events to trigger custom events.
		* Custom events are cought by the DOM element on which the plugin has been initiated.
		* We pass the video elements in the trigger in order to be used in the listener.
		* That way, you can use these elements (e.g., hiding or showing them) to define
		* specific video player logic in a slice.
		*/

		$playVideo.on('click', function() {
			// Show controls back
			$video.attr('controls', true);
			videoElements.video[0].play();
			$(self.element).trigger('clickOnPlay', videoElements);
		});
		$video.on('play', function(){
			$video.attr('controls', true);
			if (!(window.isMobile || window.isTablet)) {
				$(videoElements.playVideo).hide();
			}
			$(self.element).trigger('playVideo', videoElements);
		});
		$video.on('pause', function(){
			$(self.element).trigger('pauseVideo', videoElements);

			if(self.metadata) {
				window.NDP.TrackEventGTM.pushToDataLayer({
					'event': 'uaevent',
					'eventCategory': gtmCategory,
					'eventAction': 'Video::Pause',
					'eventLabel': self.metadata.global.name
				});
			}
		});
		$video.on('fullscreenchange webkitfullscreenchange mozfullscreenchange MSFullscreenChange', function(){
			if(document.fullScreen || document.webkitIsFullScreen || document.mozFullScreen || document.msFullscreenElement) {
				if(self.metadata) {
					window.NDP.TrackEventGTM.pushToDataLayer({
						'event': 'uaevent',
						'eventCategory': gtmCategory,
						'eventAction': 'Video::Fullscreen',
						'eventLabel': self.metadata.global.name
					});
				}
			}
		});
		$video.on('ended', function(){
			$video.removeAttr('autoplay');
			$video.load();
			$(self.element).trigger('endVideo', videoElements);

			if(self.metadata) {
				window.NDP.TrackEventGTM.pushToDataLayer({
					'event': 'uaevent',
					'eventCategory': gtmCategory,
					'eventAction': 'Video::End',
					'eventLabel': self.metadata.global.name
				});
			}
			$video.on('playing', playingCallback);
		});
		$closeButton.on('click', function(){
			$(self.element).trigger('clickOnClose', videoElements);
		});

		var errorFunc = function(e) {
			var $error = $(self.element).find('.js-error');
			$error.show();
			$video.hide();
			$playVideo.hide();
			_.defer(function() { // use defer so that element is shown for fade-in of icon
				$error.find('.information').addClass('fade');
			});
		};

		$video.on('error', errorFunc).find('source').last().on('error', errorFunc);


		var mediaid = $video.data('mediaid');

		var playingCallback = function(e) {
			var res = /(.*)\/html5\/(\w+)\/media_id(.*)/g.exec($video.get(0).currentSrc);
			$.get('//cdn3.streamlike.com/secure/Medias/391/' + mediaid + '/o.k?t=' + Math.floor(Math.random() * 99999999999) + '&s=' + res[2]);
			if(self.metadata) {
				window.NDP.TrackEventGTM.pushToDataLayer({
					'event': 'uaevent',
					'eventCategory': gtmCategory,
					'eventAction': 'Video::Play',
					'eventLabel': self.metadata.global.name
				});
			}
		};

		$video.on('playing', playingCallback);

		$.get('//cdn.streamlike.com/ws/media?lng=all&rate=false&f=json&media_id='+mediaid, function(data) {

			if(data.media.metadata) {
				self.metadata = data.media.metadata;
			}
		});

	};

	// A really lightweight plugin wrapper around the constructor,
	// preventing against multiple instantiations
	$.fn[pluginName] = function ( options ) {
		return this.each(function () {
			if ( !$.data(this, "plugin_" + pluginName )) {
				$.data( this, "plugin_" + pluginName,
				new Plugin( this, options ));
			}
		});
	};

})( jQuery, window, document );

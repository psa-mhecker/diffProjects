(function($, window, document, NDP, undefined) {

	NDP = NDP || {};

	NDP.initPF2 = function() {
		var $pf2 = $(".slice-pf2");
		if ($pf2.length){

		//Specific logic for video player
			var $pf2VideoContainer = $pf2.find('.js-video-container');
			//If the video player exists
			if ($pf2VideoContainer.length){

				/*
				* Listening custom events from the plugin.
				* Each custom event returns the common video elements in videoElements.
				* That way, you can use these elements (e.g., hiding or showing them)
				* to define specific video player logic in that slice.
				*/

				//Specific slice logic when playing the video
				$pf2VideoContainer.on('playVideo pauseVideo endVideo', function(event){
					if (matchMedia('only screen and (min-width: 641px)').matches) {
						var $elementsToHide = $(event.target).parent().find('.js-block-video-to-hide');
						switch (event.type){
							case "playVideo" :
								$elementsToHide.hide();
								break;
							case "pauseVideo" :
							case "endVideo" :
								$elementsToHide.show();
								break;
						}
					}
				});

			}
		}
	};

	//PC19 - Homepage présentation
	NDP.initPC19 = function() {
		var $pc19 = $('.slice-pc19');
		if ($pc19.length) {

			//GTM
			var gtmCategory = $pc19.data('gtmCategory');

			//cta logic
			var $ctas = $pc19.find('.cta');
			if ($ctas.length) {
				$ctas.click(function(e) {
					e.stopPropagation();
				});
			}
			$pc19.on('click', function(e) {
				if (!$(e.target).hasClass('js-slick-arrow') && !$(e.target).hasClass('js-play-button')) {
					/*
					* In case of a slick slideshow, we need to find the current clickable cta
					* In the others cases, there will always be one (and only one) visible cta
					*/
					var $visibleCta = $pc19.find('.homepage-panel:visible').find('.cta');
					if ($visibleCta.length) {

						//Triggers click on the cta
						$visibleCta.get(0).click();

						var slideNum = $pc19.find('[data-slick-to-sync]:visible').data('slickToSync') || 0;
						var data = {
							event: 'uaevent',
							eventCategory: gtmCategory,
							eventAction: 'Redirection::BannerClick::slide-'+slideNum,
							eventLabel: ''
						};
						window.NDP.TrackEventGTM.pushToDataLayer(data);
					}

				}
			});

			//Video logic
			var $pc19VideoContainer = $pc19.find('.js-video-container');
			//If the video player exists
			if ($pc19VideoContainer.length){

				/*
				* Listening custom events from the plugin.
				* Each custom event returns the common video elements in videoElements.
				* That way, you can use these elements (e.g., hiding or showing them)
				* to define specific video player logic in that slice.
				*/

				//Specific slice logic when playing the video
				$pc19VideoContainer.on('playVideo pauseVideo endVideo', function(event){
					if (matchMedia('only screen and (min-width: 641px)').matches) {
						var $elementsToHide = $(event.target).parent().find('.js-block-video-to-hide');
						switch (event.type){
							case "playVideo" :
								$elementsToHide.hide();
								break;
							case "pauseVideo" :
							case "endVideo" :
								$elementsToHide.show();
								break;
						}
					}
				});

			}
		}
	};

	NDP.initRangeBar = function() {
		var $rangeBar = $('.range-bar'),
			slideOptions = {
				easing: 'easeInOutCubic'
			};
		if ($rangeBar.length) {
			var $rangeBarHeader = $rangeBar.find('.range-bar-header'),
				$rangeBarList = $rangeBar.find('.range-bar-list'),
				$icon = $rangeBar.find('.js-toggle-icon'),
				gtmCategory = $('.slice-pf23').data('gtmCategory');
			if ($rangeBarHeader.length && $rangeBarList.length && $icon.length) {
				var localLabel = $rangeBarHeader.data('gtmEventLabel');
				$rangeBarHeader.on('click', _.debounce(function(){
					if ($icon.hasClass('toggle-icon-expand')) {
						var data = {
							event: 'uaevent',
							eventCategory: gtmCategory,
							eventAction: 'Display::Expandbar',
							eventLabel: localLabel
						};
						window.NDP.TrackEventGTM.pushToDataLayer(data);
					}
					$icon.toggleClass('toggle-icon-expand toggle-icon-shrink');
					$rangeBarList.slideToggle(slideOptions);
				}, 300, true));
			}
		}
	};

	NDP.initVignette = function() {
		$(document).on('touchstart', '.vignette', function() {
			$('.vignette').removeClass('touched');
			$(this).addClass('touched');
		});
	};

	NDP.initPC52 = function() {
		var $pc52 = $(".slice-pc52");
		if($pc52.length) {
			$pc52.find('.services-selector .drop-filters').on('change', function(evt) {
				var $vignettes = $pc52.find('.vignette');
				$vignettes.hide();
				var $checkedInputs = $(this).find('input:checked');
				if($checkedInputs.length) {
					$checkedInputs.each(function(key, input) {
					   $vignettes.filter('.filter-'+input.name).show();
					});
				} else {
					$vignettes.show();
				}
			});
		}
	};

	NDP.initPF25 = function() {
		var $pf25 = $(".slice-pf25");
		if($pf25.length){
			var url = $pf25.data('url');
			if(url){
				var $carSelector = $('.car-selector');
				var spinner = new Spinner().spin($carSelector.get(0));
				$.ajax({
					type: "GET",
					url: url,
					dataType: "json"
				}).done(function (response) {
					spinner.stop();
					var vignetteTpl = document.getElementById('vignette-tpl').innerHTML;
					var carSelectorOutput = "";
					response.results.forEach(function(result) {
						result.vehicle.version = result.vehicle.version;
						carSelectorOutput += _.template(vignetteTpl)(result);
					});
					$carSelector.append(carSelectorOutput);

					var baseData = {
						event: 'uaevent',
						eventCategory: $pf25.data('slicename')+'::position-'+$pf25.data('sliceposition'),
						eventAction: '',
						eventLabel: ''
					};
					$carSelector.on('click', '.vignette .content', function(e){
						var $carContent = $(e.currentTarget);
						NDP.TrackEventGTM.pushToDataLayer($.extend({}, baseData, {
							eventLabel: $carContent.find('h3').text(),
							eventAction: 'Display::More::'+$carContent.parent().attr('id')
						}));
					});
					$carSelector.on('click', '.vignette .cta.discover', function(e){
						var $cta = $(e.currentTarget);
						NDP.TrackEventGTM.pushToDataLayer($.extend({}, baseData, {
							eventLabel: $cta.find('span').text(),
							eventAction: 'Redirection::Showroom::'+$cta.parents('.vignette')[0].id
						}));
					});
					$carSelector.on('click', '.vignette .cta.config', function(e){
						var $cta = $(e.currentTarget);
						NDP.TrackEventGTM.pushToDataLayer($.extend({}, baseData, {
							eventLabel: $cta.find('span').text(),
							eventAction: 'Redirection::Configurator::'+$cta.parents('.vignette')[0].id
						}));
					});
				}).fail(function (res, status) {
					spinner.stop();
				});
			}
		}
	};

})(jQuery, window, document, window.NDP);

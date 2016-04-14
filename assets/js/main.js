/**
 * Created on 09/11/15.
 * Script lanceur pour l'application NDP
 */

$(document).ready(function(){

	$(document).foundation();

	picturefill();

	//Définition du namespace NDP
	window.NDP = window.NDP || {};

	//Mobile and tablet detection. isMobile and isTablet are booleans.
	window.isMobile = ($('body.isMobile').length !== 0);
	window.isTablet = ($('body.isTablet').length !== 0);

	//Set default Spinner color
	window.Spinner.defaults.color = '#162133';

	//Change CTA dropdown's chevron
	$('.custom-dropdown-button').on('click', function() {
		$(this).find('span').toggleClass('sprite-up');
	});
	$('.custom-dropdown-button').on('focusout', function() {
		$(this).find('span').removeClass('sprite-up');
	});

	//Accordion smooze effect
	$('#js-accordion-navigation').on('click', function() {
		$(this).next(".content").stop().slideToggle(350);
	});

	//Video player global init
	var $videoContainers = $(document).find('.js-video-container');
	if ($videoContainers.length){
		$videoContainers.videoPlayer();
	}

	window.NDP.initPF2();
	window.NDP.initPC19();
	window.NDP.initPF25();
	window.NDP.initPC52();
	window.NDP.initRangeBar();
	// Disabled for release 1
	// window.NDP.initVignette();

	// Navigation
	var $navigation = $(document.getElementById('principal-nav'));
	if ($navigation.length > 0){
		$navigation.navigation(500);
	}

	// PT2 Footer
	var $pt2 = $(".slice-pt2");
	if ($pt2.length){
		$('.slice-pt2').footer();
	}

	// BacktoTop
	var $toplink = $(".toplink");
	if ($toplink.length){
		$('.toplink').topLink();
	}
	// BacktoTopMobile
	var $toplinkMobile = $(".toplinkMobile");
	if ($toplinkMobile.length){
		$('.toplinkMobile').topLink();
	}

	// PF11 Dealer locator
	var $pf11 = $(".slice-pf11");
	if ($pf11.length){
		// Get options and pass them in plugin options
		var mapOptions = $pf11.data('mapoptions');
		var options = {
			urlRedirection: $pf11.data('urlredirection'),
			regroupment: mapOptions.marker_clustering,
			autocomplete: mapOptions.autocomplete,
			maxResults: mapOptions.max_results,
			nbDVN: mapOptions.max_dvn ,
			mediaServer: mapOptions.media_server,
			coordDefaultString: $pf11.data('coordinit'),
			url: $pf11.data('urljson'),
			mode: $pf11.data('mode'),
			gtmCategory: $pf11.data('slicename')+'::position-'+$pf11.data('sliceposition')
		};
		$pf11.dealerLocator(options);
	}

	var $pn14 = $(".slice-pn14");
	if ($pn14.length){
		$.NDP.navShowroom($pn14);
	}

	var $anchor = $('a[data-anchor]');
	if ($anchor.length){
		$.NDP.anchorScroll($anchor);
	}

	var $slickcarousels = $('.js-slick-carousel-container');
	if(window.matchMedia("(max-width: 640px)").matches) {
		$slickcarousels = $slickcarousels.not('.js-no-slick-mobile');
	}
	if ($slickcarousels.length) {
		$slickcarousels.initSlickCarousel();
	}

	// Tracking des events GTM
	$('[data-gtm]').trackEventGTM();

	// Initialisation du plugin dragndrop (twentytwenty)
	$(".twentytwenty-container").dragndrop();

	$('.wf_form_content').each(function() {
		$(this).formLoader($(this).data('options'));
	});

	// PC79 - Popin feature
	var $pc79 = $(".slice-pc79");
	if ($pc79.length){
		$pc79.popin();
	}

	// PC23 - Popin feature
	var $pc23 = $(".slice-pc23");
	if ($pc23.length){
		$pc23.popin();
	}

	// PC60 - Sticky Element
	var $stickyContainer = $("#stickyContainer");
	if ($stickyContainer.length){
		$stickyContainer.stickyElement();
	}

	var $pn18 = $(".slice-pn18");
	if($pn18.length){
		window.addEventListener('message', function(e) {
			var $iframe = $pn18.find('iframe');
			var eventName = e.data[0];
			var data = e.data[1];
			switch(eventName) {
				case 'setHeight':
					$iframe.height(data);
					break;
			}
		}, false);
	}

	//Mosaic USP
	var $mosaicUSP = $('.mosaic-usp');
	if($mosaicUSP.length) {
		$mosaicUSP.mosaicUSP();
	}

	var $dropFilter = $('.drop-filters');
	if($dropFilter.length) {
		$dropFilter.on("click", ".title", function (evt) {
			$(evt.currentTarget).toggleClass('open');
			$dropFilter.find('.content').slideToggle("slow");
		});
	}

	// Leave this at the end
	var $lazy = $('img[data-lazy]');
	if ($lazy.length){
		$lazy.lazyInterchange();
	}

	$lazy = $('picture.lazy-load');
	if ($lazy.length){
		$lazy.lazysrcset();
	}

	// Init SiteTypeLevel2
	var current = sessionStorage.getItem('curSiteTypeLevel2');
	if(current) {
		sessionStorage.setItem('prevSiteTypeLevel2', current);
	}
	sessionStorage.setItem('curSiteTypeLevel2', window.dataLayer[0].siteTypeLevel2);
});

// Create the defaults once
var pluginName = "dealerLocator";
var markers = {};
var markerClusters = null;
var activeMarker = {};
var translation = window.translation || {};
var defaults = {
	markers: [],
	mapOptions: {},
	map: null,
	dealers: {},
	latLngObject: null,
	noResultBoolean: false,
	enTraitement: false,
	typeSelected: 'city',
	autocomplete: null,
	mediaServer: null,
	picto: "/design/frontend/desktop/img/pin.png",
	pictoOn: "/design/frontend/desktop/img/pin-on.png",
	pictoOff: "/design/frontend/desktop/img/pin-off.png",
	coordDefaultString: null,
	latDefault: 47.1428282,
	lngDefault: 2.7260531,
	infobulle: null,
	infobulles: [],
	urlRedirection: null,
	url: null,
	regroupment: null,
	maxResults: null,
	nbDVN: 0,
	gtmCategory: null
};

// The actual plugin constructor
export default function DealerLocator(element, options) {
	this.element = element;
	this.options = $.extend({}, defaults, options);
	this._defaults = defaults;
	this._name = pluginName;
	this.isMobile = window.matchMedia("(max-width: 40em)").matches;
	this._create();
}

DealerLocator.prototype = {
	/**
	 * Load googlemaps api + marker clusterer then launch init
	 * @private
	 */
	_create: function() {
		var self = this;
		this.addScript(
			"//maps.googleapis.com/maps/api/js?libraries=places&v=3.22",
			function() {
				self.addScript(
					"//google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclustererplus/src/markerclusterer_packed.js",
					self.init.bind(self)
				);
			}
		);
	},
	addScript: function(url, callback) {
		var script = document.createElement('script');
		script.setAttribute('src', url);
		script.onload = callback;
		document.body.insertBefore(script, document.body.firstChild);
	},
	init: function() {
		var self = this;
		var psaGeocoder = new google.maps.Geocoder();

		window.onresize = _.throttle(this.checkIfMobile.bind(this), 100);

		if($('.dealer-locator-head.light').length) {
			this.options.autocomplete = true;
		}

		psaGeocoder.geocode({
			componentRestrictions: {
				country: $('html').data('country')
			}
		}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				self.options.latDefault = results[0].geometry.location.lat();
				self.options.lngDefault = results[0].geometry.location.lng();
				self.startMap();
			} else {
				console.error('Geocode was not successful for the following reason: ' + status);
			}
		});

	},
	startMap: function () {
		var self = this;
		//Filtre Type Search
		var $subCity = $(this.element).find('.sub-city'),
			$typeSearch = $(this.element).find('.type-search'),
			chevronClass = "chevron-up";

		// gestion du chevron a gauche du champ de recherche
		$subCity.on('click', function () {
			$typeSearch.stop().slideToggle(200);
			if ($subCity.hasClass(chevronClass)) {
				$subCity.removeClass(chevronClass);
			} else {
				$subCity.addClass(chevronClass);
			}
		});
		var $a = $(this.element).find('a');
		$a.click(function (event) {
			event.preventDefault();
		});

		$(this.element).on('click', '.infowindow a', function (e) {
			if ($(this.element).data('mode') !== "apv") {
				e.preventDefault();
				var idDealer = $(this).data('dealerid');
				$(document.getElementById('dealer_' + idDealer)).trigger('click');
			}
		});

		$(this.element).on('click', '.btn-return', function(e) {
			$(self.element).find('.js-list-dealer').removeClass('hide');
			$(self.element).find('.dealer-details').hide();
		});

		$('#search-loc-input').on('keyup', this.changeSubmitState);

		// ---- AFFICHER PLUS FILTRE -----
		var $btnMoreFilter = $(this.element).find('.btn-more-filter'),
			$moreFilter = $(this.element).find('.more-filter'),
			$closeMoreFilter = $(this.element).find('.close-more-filter'),
			$openMoreFilter = $(this.element).find('.open-more-filter'),
			$errorLoad = $(this.element).find(".errorOnLoad"),
			open = false;
		$btnMoreFilter.click(function (event) {
			event.preventDefault();
			$moreFilter.slideToggle(200);
			$closeMoreFilter.toggle();
			$openMoreFilter.toggle();
		});

		if (this.options.coordDefaultString) {
			var tabCoord = this.options.coordDefaultString.split(',');
			this.options.latDefault = tabCoord[0] ? parseFloat(tabCoord[0]) : 47.1428282;
			this.options.lngDefault = tabCoord[1] ? parseFloat(tabCoord[1]) : 2.7260531;
		}
		var zoom = 6;
		if($('#map-canvas').css('height') === '200px') {
			zoom = 4;
		}
		this.options.mapOptions = {
			center: {lat: this.options.latDefault, lng: this.options.lngDefault},
			zoom: zoom,
			zoomControl: true,
			zoomControlOptions: {
				style: google.maps.ZoomControlStyle.SMALL,
				position: google.maps.ControlPosition.RIGHT_BOTTOM
			},
			disableDefaultUI: true
		};

		var mapStyles = [
			{
				stylers: [
					{saturation: -30}
				]
			}
		];

		this.options.mcOptions = {
			styles: [
				{
					textColor: 'white',
					url: this.options.mediaServer+this.options.picto,
					height: 52,
					width: 42
				}
			],
			maxZoom: 15
		};

		// Error for googlemaps not loaded
		if (typeof google === "undefined") {
			$errorLoad.removeClass('hide');
			return false;
		}

		var styledMap = new google.maps.StyledMapType(
			mapStyles,
			{ name: "Styled Peugeot Map" }
		);
		this.map = new google.maps.Map(document.getElementById('map-canvas'), this.options.mapOptions);

		this.map.mapTypes.set('map_style', styledMap);
		this.map.setMapTypeId('map_style');
		this.managefilterType();
		this.aroundMe();
		this.searchInput();

		// If we have a query from dealer-locator-light version
		var searchQuery = location.search;
		if(searchQuery) {
			var match = searchQuery.match('departure=([^&]*)');
			if(match[1]) {
				var splittedCoord = match[1].split(',', 2);
				var position = {
					coords: {
						latitude: splittedCoord[0],
						longitude: splittedCoord[1]
					}
				};
				this.getCity(position);
			}
		}

		// s'il y a des valeurs par défault, on affiche les dealers correspondant
		if (this.options.coordDefaultString) {
			this.options.latLngObject = new google.maps.LatLng(this.options.latDefault, this.options.lngDefault);
			this.map.setOptions(this.options.mapOptions);
			this.getDealers();
		}
		if (this.options.autocomplete) {
			this.enableGoogleAutocomplete();
		}
	},
	initMap: function () {
		$(this.element).find('.list-result', $(this.element)).hide();
		for (var i = 0; i < this.options.infobulles.length; i++) {
			this.options.infobulles[i].close();
		}
		this.options.infobulles = [];
		this.options.noResultBoolean = false;
		this.clearOverlays();
	},
	/**
	 * Get dealer by geolocation
	 */
	aroundMe: function() {
		var self = this;
		$(self.element).find('.js-btn-around-me').on('click', function (e) {
			e.preventDefault();
			self.clearMarkers();
			if (navigator.geolocation) {
				$(this.element).find('.search-loc-submit').addClass("active");
				navigator.geolocation.getCurrentPosition(getPosition);// Get the current position
			}
		});

		function getPosition(position) {
			self.options.latLngObject = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
			self.options.mapOptions = {
				center: self.options.latLngObject,
				zoom: 12
			};
			self.map.setOptions(self.options.mapOptions);
			self.getCity(position);
		}
	},
	getCity: function(position) {
		var self = this;
		//CONVERT COORDS TO CITY
		var myLatlng = new google.maps.LatLng( position.coords.latitude, position.coords.longitude );
		var latLngObject = myLatlng ;
		var GeocoderOptions = {
			'latLng' : myLatlng
		};
		var myGeocoder = new google.maps.Geocoder();
		function GeocodingResult( results , status ) {
			if (status !== "ZERO_RESULTS") {
				self.getLatLng(results[1].formatted_address);
			}
			else {
				self.displayDealerResults();
			}
		}
		myGeocoder.geocode( GeocoderOptions, GeocodingResult );
	},
	searchInput: function () {
		var self = this;
		$(this.element).find('#search-loc-input').on('change', function (e) {
			var $input = $(this);
			if ($input.val !== "") {
				$(self.element).find('.search-loc-submit').addClass("active");
			} else {
				$(self.element).find('.search-loc-submit').removeClass("active");
			}
		});

		$(this.element).find("#form_search_dealer").submit(function (event) {
			event.preventDefault();
			// Reset result box and dealer options
			self.clearMarkers();
			// Prevent user to submit several time while treatment is active.
			self.options.enTraitement = true;
			var searchBtn = $(self.element).find('input[type=submit]');
			var searchQuery = $(self.element).find('#search-loc-input').val();
			// TagEvent
			self.addTagManagement({
				eventLabel: searchQuery,
				eventAction: 'Search::Keywords'
			});

			if (self.options.urlRedirection) {
				self.addTagManagement({
					eventAction: 'Search::DealerLocator',
					eventLabel: 'OK'
				});
			}

			if (self.options.typeSelected === "dealer") {
				self.initMap();
				// TRAITEMENT : NOM DU POINT DE VENTE
				self.getDealers({ query: searchQuery });
			}else {
				// TRAITEMENT VILLE & CODE POSTAL (retour googleMap)
				self.initMap();
				self.getLatLng(searchQuery);
			}
		});
	},
	getLatLng: function (city) {
		var self = this;
		var GeocoderOptions = {
			'address': city
		};
		var myGeocoder = new google.maps.Geocoder();

		function GeocodingResult(results, status) {
			if (status !== "ZERO_RESULTS") {
				self.options.mapOptions = {
					center: results[0].geometry.location,
					zoom: 12
				};
				self.options.latLngObject = results[0].geometry.location;
				if (self.options.urlRedirection) {
					document.location.href = self.options.urlRedirection + "?departure=" + parseFloat(results[0].geometry.location.lat()) + "," + parseFloat(results[0].geometry.location.lng());
					return false;
				}
				self.map.setOptions(self.options.mapOptions);
				self.getDealers();
			}
			else {
				// Reset potentials previous dealers result
				self.options.dealers = {};
				self.displayDealerResults();
			}
		}

		myGeocoder.geocode(GeocoderOptions, GeocodingResult);
	},
	managefilterType: function () {
		var self = this;
		$(self.element).find('.type-search label.filters').on("change", "input", function () {
			var $input = $(this);
			// Manage STYLE CSS
			$(self.element).find('.type-search').find('.checkbox, .radio').removeClass("checked");
			$input.parents('label').find('.checkbox, .radio').addClass("checked");

			// Manage FONCTIONNALITE
			self.options.typeSelected = $input.val();
			if (self.options.typeSelected === "dealer") {
				self.disableGoogleAutocomplete() ;
			}else{
				if(self.options.autocomplete) {
					self.enableGoogleAutocomplete();
				}
			}
			$(self.element).find('#search-loc-input').attr('placeholder', $input.data('placeholder'));
		});
		$(self.element).find('.type-search label.filters input[value=city]').trigger("click");
	},
	disableGoogleAutocomplete : function() {
		if (this.autocomplete !== undefined) {
			google.maps.event.removeListener(this.autocompleteListener);
			google.maps.event.clearInstanceListeners(this.autocomplete);
			$(this.element).find(".pac-container").remove();
		}
	},
	enableGoogleAutocomplete: function () {
		var input = document.getElementById('search-loc-input');
		var self = this;
		var autocompleteOptions = {
			types: ['geocode']
		};
		if ($('html').data('country') !== undefined) {
			autocompleteOptions.options = {
				componentRestrictions: {
					country: $('html').data('country')
				}
			};
		}
		this.autocomplete = new google.maps.places.Autocomplete(input, autocompleteOptions);
		this.autocomplete.bindTo('bounds', this.map);
		if(window.isMobile) {
			this.autocompleteListener = google.maps.event.addListener(this.autocomplete, 'place_changed', function () {
				var place = self.autocomplete.getPlace() || {};
				// If the place has a geometry, then present it on a map.
				if (!place.geometry) {
					return;
				}
				var vp = place.geometry.viewport;
				self.map.fitBounds(vp);

				if (place.geometry.location) {
					self.map.setCenter(place.geometry.location);
					self.map.setZoom(12);
				}
				else {
					self.map.setCenter(place);
					self.map.setZoom(12);
				}
				$(self.element).find("#form_search_dealer").trigger('submit');
			});
		}
	},
	/**
	 * AJAX call to dealers webservice
	 */
	getDealers: function (query) {
		var self = this;
		var spinner = new Spinner().spin(document.getElementById('dealer-locator-map'));
		if(!query) {
			query = {
				departure: this.options.latLngObject.lat().toFixed(2) +","+ this.options.latLngObject.lng().toFixed(2)
			};
		}

		$.ajax({
			type: "GET",
			url: this.options.url,
			dataType: "json",
			data: query
		}).done(function (res) {
			if(res.listDealer) {
				self.options.dealers = res.listDealer;
				self.formatDealers();
				if (res.status !== "ZERO_RESULTS") {
					self.addMarkers(self.options.dealers);
				}
			}
			spinner.stop();
			self.displayDealerResults();
		}).fail(function (res, status) {
			spinner.stop();
			self.displayDealerResults();
		});
	},
	clearOverlays: function () {
		for (var i = 0; i < this.options.markers.length; i++) {
			this.options.markers[i].setOptions({'visible': false}); // masque le marker
			this.options.markers[i].setMap(null);
		}
		this.options.markers.length = 0;
	},
	/**
	 * Add markers by webservice response
	 * @param res
	 */
	addMarkers: function(res) {
		var self = this,
			bounds = new google.maps.LatLngBounds();

		if ($.isEmptyObject(this.options.dealers)) {
			return;
		}
		// Add marker for each dealers sent by web service response
		$.each(this.options.dealers, function (i, dealer) {
			// Set an infowindow to the marker
			var infoWindowValues = {
				dealerId: dealer.id,
				dealerName: dealer.name,
				btnDetailedSheet: translation.NDP_VIEW_DETAILED_SHEET || "NDP_VIEW_DETAILED_SHEET"
			};
			var infoWindowTpl = $(document.getElementById('tpl-infowindow')).html();
			var compiledTpl = _.template(infoWindowTpl);
			var infoWindow = new google.maps.InfoWindow({
				content: compiledTpl(infoWindowValues)
			});

			var testMarker = [];
			var marker = new google.maps.Marker({
				position: new google.maps.LatLng(dealer.adress.lat, dealer.adress.lng),
				map: self.map,
				animation: google.maps.Animation.DROP,
				icon: self.options.mediaServer+self.options.pictoOff,
				id: dealer.id,
				active: false,
				infoWindow: infoWindow
			});
			testMarker.push(marker);
			markers[dealer.id] = marker;
			bounds.extend(marker.position);

			// Set events on this marker

			// Higlight marker on rollover
			google.maps.event.addListener(marker, 'mouseover', function () {
				this.setIcon(self.options.mediaServer+self.options.pictoOn);
			});

			// Remove highlight on rollover end
			google.maps.event.addListener(marker, 'mouseout', function () {
				if(!this.active) {
					this.setIcon(self.options.mediaServer+self.options.pictoOff);
				}
			});

			google.maps.event.addListener(marker, 'click', function () {
				if(!window.isMobile) {
					this.infoWindow.open(self.map, this);
					self.addDetailedSheetEvent('infowindow', dealer);
				}
				var marker = this;
				$(self.element).find('.js-list-dealer').removeClass('hide');
				$(self.element).find('.dealer-details').hide();
				self.setMarkerActive(marker);
				self.scrolltoResult(marker, true);

				// Tag event
				self.addTagManagement({
					eventAction: "Select::PinPoint",
					eventLabel: "PinPoint::"+marker.id,
				});
			});

			$(self.element).on('click', "#dealer-"+marker.id,  function(e) {
				var currentMarker = markers[marker.id];
				self.setMarkerActive(currentMarker);
				self.scrolltoResult(currentMarker, false);
				self.map.setCenter(currentMarker.getPosition());
				self.map.setZoom(15);
			});

			self.addDetailedSheetEvent('dealer', dealer);
		}); // End of each

		if(self.options.regroupment) {
			var clusterStyles = [
				{
					textColor: 'white',
					url: this.options.mediaServer + this.options.picto,
					height: 58,
					width: 42,
					anchor: [0, 0],
					textSize: 16
				}
			];
			var mcOptions = {
				gridSize: (this.isMobile) ? 32 : 60,
				styles: clusterStyles,
			};
			markerClusters = new MarkerClusterer(this.map, markers, mcOptions);
			google.maps.event.addListener(markerClusters, "click", function (c) {
				// Tag event
				self.addTagManagement({
					eventAction: "Select::PinPoint",
					eventLabel: "PinPoint",
				});
			});
		}

		google.marker
		this.map.fitBounds(bounds);
		this.map.panToBounds(bounds);
	},
	/**
	 * Remove all markers
	 */
	clearMarkers: function() {
		$(this.element).find('.js-list-dealer-wrapper').empty();
		$(this.element).find('.js-list-dealer').addClass("hide");
		$(this.element).find('.js-list-result').hide();
		$(this.element).find('#result-nbr').text("");
		this.options.dealers = {};
		for(var key in markers) {
			markers[key].setMap(null);
		}
		markers = {};

		// Clear dealer-details-tpl too
		var $details = $(this.element).find('.dealer-details');
		$details.empty();
		$details.hide();
		if(markerClusters) {
			markerClusters.clearMarkers();
		}
	},
	scrolltoResult: function(dest, from) {
		var dealerDest = "#dealer-" + dest.id;
		if(this.isMobile) {
			if(from) {
				$('html, body').animate({
					scrollTop: $(dealerDest).position().top
				}, 300);
			}else{
				$('html, body').animate({
					scrollTop: $("body").position().top
				}, 300);
			}
		}else{
			if(from) {
				$(this.element).find('.scroller').mCustomScrollbar("scrollTo", dealerDest, "top");
			}
		}
	},
	setMarkerActive: function (marker) {
		var self = this;
		// Center map on marker position
		self.map.setCenter(marker.getPosition());

		// Disable highlight and infowindow on previous markers
		if (!jQuery.isEmptyObject(activeMarker) && (activeMarker !== marker)) {
			activeMarker.setIcon(self.options.mediaServer+self.options.pictoOff);
			activeMarker.active = false;
			activeMarker.infoWindow.close();
			$(document.getElementById("dealer-"+activeMarker.id)).removeClass('active');
		}

		// Highlight marker and dealer infos
		marker.active = true;
		marker.setIcon(self.options.mediaServer+self.options.pictoOn);
		$(document.getElementById("dealer-"+marker.id)).addClass('active');

		activeMarker = marker;
	},
	formatDealers: function() {
		var self = this;
		if(this.options.dealers.length) {
			self.limitDealerResults();
			$.each(this.options.dealers, function(key, dealer) {
				self.formatDealerCta(dealer);
			});
		}
	},
	/**
	 * Display result item with dealers results or an error
	 */
	displayDealerResults: function() {
		var self = this;
		$(self.element).find('.scroller').mCustomScrollbar({ mouseWheel:{ scrollAmount: 200 } });
		var msgInfo = $(self.element).find('.js-msg-info');
		var listResult = $(self.element).find('.js-list-result');
		var listDealer = $(self.element).find('.js-list-dealer');
		if (jQuery.isEmptyObject(self.options.dealers)) {
			if ($(listResult).css('display') !== 'block') {
				$(listResult).fadeIn(200);
			}
			$(msgInfo).removeClass('hide');
		}
		else {
			var dealersList = this.options.dealers;
			var nbDealers = dealersList.length || 0;
			$(this.element).find('#result-nbr').text(nbDealers);
			// tag event
			this.addTagManagement({
				eventLabel: nbDealers,
				eventAction: 'Search::Matches'
			});
			var template = $(document.getElementById('tpl-dealer-item')).html();
			$.each(dealersList, function(key, dealer) {
				// Set another dealer type to display in template
				var typeDealer = translation.NDP_DEALER || "NDP_DEALER";
				var typeAgent = translation.NDP_AGENT || "NDP_AGENT";
				var types = [];
				if (dealer.dealer) {
					types.push(typeDealer);
				}
				if(dealer.agent) {
					types.push(typeAgent);
				}
				dealer.types = types.join(', ');
				self.renderDealer(template, dealer);
			});
			$(msgInfo).addClass('hide');
			// Today we don't have any result to display
			if ($(listResult).css('display') !== 'block') {
				$(listDealer).removeClass('hide');
				$(listResult).fadeIn(200);
			}
		}
	},
	/**
	 * Render dealer template
	 * @param template
	 * @param dealer
	 */
	renderDealer: function(template, dealer) {
		var compiled = _.template(template);
		$(this.element).find('.js-list-dealer-wrapper').append(compiled(dealer));
	},
	/**
	 * Limit dealer result number
	 */
	limitDealerResults: function() {
		if(this.options.dealers.length) {
			if(this.options.dealers.length > this.options.maxResults) {
				this.options.dealers.length = this.options.maxResults;
			}
		}
	},
	addDetailedSheetEvent: function(id, dealer) {
		var self = this;
		var selector = '#'+id+'-'+dealer.id+' .detailed-sheet';
		var $dealerDetails = $(self.element).find('.dealer-details');
		$(self.element).on('click', selector, function(e) {
			e.preventDefault();
			// Extend dealer info with translations
			var translations = {
				btnvisitWebsite: translation.NDP_VISIT_WEBSITE || "NDP_VISIT_WEBSITE",
				btnBackToResults: translation.NDP_BACK_TO_RESULTS || "NDP_BACK_TO_RESULTS",
				linkSchedules: translation.NDP_OPENING_HOURS || "NDP_OPENING_HOURS",
				linkServices: translation.NDP_DEALER_SERVICES || "NDP_DEALER_SERVICES",
				linkContact: translation.NDP_PF11_CONTACT || "NDP_PF11_CONTACT",
				linkContactSheet: translation.NDP_PF11_VCF_CONTACT_SHEET || "NDP_PF11_VCF_CONTACT_SHEET",
				labelPhone: translation.NDP_PHONENUMBER || "NDP_PHONENUMBER"
			};
			dealer.gtmCategory = self.options.gtmCategory;
			dealer.translations = translations;
			// Handle website
			if(!dealer.contact.website || !/^(https?:)?\/\//.test(dealer.contact.website)){
				delete dealer.contact.website;
			}
			//Get template
			var template = $(document.getElementById('tpl-dealer-item-details')).html();
			var compiled = _.template(template);
			if (!dealer.hasOwnProperty("schedules")) {
				dealer.schedules = false;
			}
			$dealerDetails.html(compiled(dealer));

			self.displayDealerServices(dealer);
			self.displayDealerCta(dealer);

			$(document).foundation('tab', 'reflow');
			$(document).foundation('accordion', 'reflow');
			$(self.element).find('.js-list-dealer').addClass('hide');
			$dealerDetails.find('[data-gtm]').trackEventGTM();
			$dealerDetails.show();

			//Tag event
			self.addTagManagement({
				eventAction: "Select::DealerDetails::"+dealer.id,
				eventLabel: translation.NDP_VIEW_DETAILED_SHEET || "NDP_VIEW_DETAILED_SHEET"
			});
		});
	},
	displayDealerServices: function(dealer) {
		var self = this;
		var servicesCompiled = "";
		var serviceTpl = _.template(document.getElementById('tpl-dealer-service').innerHTML);
		dealer.services.forEach(function(service) {
			if(!service.icon) {
				service.icon = self.options.mediaServer+'/design/frontend/desktop/img/logo-smallx2.png';
			}
			var serviceDatas = {
				translations: {
					labelPhone: translation.NDP_PHONENUMBER || "NDP_PHONENUMBER"
				},
				dataGtm: {
					eventCategory: self.options.gtmCategory,
					mail: {
						eventAction: "Redirection::Email::"+dealer.id,
						eventLabel: "Icon::Email"
					},
					tel: {
						eventAction: "Redirection::Clicktocall::"+dealer.id,
						eventLabel: "Icon::Phone"
					}
				}
			};
			$.extend(service, serviceDatas);
			servicesCompiled += serviceTpl(service);
		});
		$('.tabs-content .services').append(servicesCompiled);
		$('.accordion .services').append(servicesCompiled);
	},
	formatDealerCta: function(dealer) {
		// add call cta
		if(dealer.contact.tel) {
			dealer.ctaList.unshift({
				target: "self",
				title: translation.NDP_PF11_CALL_US || "NDP_PF11_CALL_US",
				url: "tel:"+dealer.contact.tel,
				version: "cta-call"
			});
		}
		//add website cta
		if(dealer.contact.mail) {
			dealer.ctaList.push({
				target: "self",
				title: translation.NDP_PF11_EMAIL_US || "NDP_PF11_EMAIL_US",
				url: dealer.contact.mail,
				version: "cta-write"
			});
		}
		//Remove itinerary cta
		dealer.ctaList = _.reject(dealer.ctaList, function(cta) {
			return cta.version === "cta-direction";
		});
	},
	displayDealerCta: function(dealer) {
		var self = this;
		var ctaCompiled = "";
		var ctaTpl = _.template(document.getElementById('tpl-dealer-cta').innerHTML);

		dealer.ctaList.forEach(function(cta, key) {
			var dataGtm = {
				eventCategory: self.options.gtmCategory,
				eventLabel: cta.title
			};
			if(!cta.img) {
				switch(cta.version) {
					case 'cta-call':
						cta.img = self.options.mediaServer+'/design/frontend/mobile/img/cta-call.png';
						dataGtm.eventAction = "Redirection::Clicktocall";
					break;
					case 'cta-direction':
						cta.img = self.options.mediaServer+'/design/frontend/mobile/img/cta-itinerary.png';
						dataGtm.eventAction = "";
					break;
					case 'cta-contact':
						cta.img = self.options.mediaServer+'/design/frontend/mobile/img/cta-visit.png';
						dataGtm.eventAction = "Redirection::Edealer::"+dealer.id;
					break;
					case 'cta-write':
						cta.img = self.options.mediaServer+'/design/frontend/mobile/img/cta-write.png';
						dataGtm.eventAction = "Redirection::Email::"+dealer.id;
						dataGtm.eventLabel = "Icon::Email";
					break;
				}
			}
			cta.dataGtm = dataGtm;
			// if cta number is odd then apply full width class to last element
			if (dealer.ctaList.length%2 !== 0 && key === dealer.ctaList.length-1) {
				dealer.ctaList[dealer.ctaList.length -1].version += ' fullwidth';
			}
			ctaCompiled += ctaTpl(cta);
		});
		$('.squared-cta-wrapper').append(ctaCompiled);

	},
	addTagManagement : function(data) {
		var baseData = {
			event: 'uaevent',
			eventCategory: this.options.gtmCategory,
			eventAction: '',
			eventLabel: ''
		};
		NDP.TrackEventGTM.pushToDataLayer($.extend(baseData, data));
	},
	changeSubmitState: function() {
		var $submitBtn = $('.search-loc-submit');
		var disabledClass = 'disabled';
		if(document.getElementById('search-loc-input').value === "") {
			$submitBtn.addClass(disabledClass);
		}
		else {
			$submitBtn.removeClass(disabledClass);
		}
	},
	checkIfMobile: function() {
		this.isMobile = window.matchMedia("(max-width: 40em)").matches;
	}
};

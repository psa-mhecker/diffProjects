/*-------------------- Google maps MOBILE 20/05/2015 --------------------*/
// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
;
(function($, window, document, undefined) {

    // undefined is used here as the undefined global variable in ECMAScript 3 is
    // mutable (ie. it can be changed by someone else). undefined isn't really being
    // passed in so we can ensure the value of it is truly undefined. In ES5, undefined
    // can no longer be modified.

    // window and document are passed through as local variable rather than global
    // as this (slightly) quickens the resolution process and can be more efficiently
    // minified (especially when both are regularly referenced in your plugin).

    // Create the defaults once
    var pluginName = 'gLocator',

        /* Defaults */
        defaults = {

            /* Vars */
            config: {
                timeout: 10000
            },
            markers: [],
            clusterer: null,
            latest: {
                lat: null,
                lng: null,
                filters: null,
                zoom: null,
                type: 'geo'
            },
            state: 0,

            /* Callbacks */
            onLoad: function() {},
            onFilter: function() {},
            onList: function() {},
            onItemClick: function(storeId, storeRRDI) {},
            onDetails: function() {},
            onHashDetails: function() {},
            onGeoloc: function() {},
            onGeolocError: function() {}

        };

    // The actual plugin constructor
    function Plugin(element, options) {

        /* Static */
        this.element = element;
        this.timer = null;
        this.busyState = false;
        this.markers = [];

        // jQuery has an extend method which merges the contents of two or
        // more objects, storing the result in the first object. The first object
        // is generally empty as we don't want to alter the default options for
        // future instances of the plugin
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    };

    Plugin.prototype = {

        /* Initalisation */
        init: function() {

            var me = this,
                el = this.element,
                domid = el.getAttribute('data-dom') || 'map-canvas';

            me.loader = new Loader($('#' + domid));

            /* Vars */
            me.settings.base = domid;
            me.settings.dom = document.getElementById(domid);
            me.settings.wsConf = el.getAttribute('data-config'),
				me.settings.wsList = el.getAttribute('data-list'),
				me.settings.brandactivity = el.getAttribute('data-brand-activity'),
                me.settings.wsDetails = el.getAttribute('data-details');
            me.settings.imgPath = el.getAttribute('data-path') || '';
            me.settings.page = el.getAttribute('data-page');
            me.settings.version = el.getAttribute('data-version');
            me.settings.order = el.getAttribute('data-order');
            me.settings.area = el.getAttribute('data-area');
            me.settings.ztid = el.getAttribute('data-ztid');
            me.settings.mea = el.getAttribute('data-mea');
            me.settings.ds = ($(el).hasClass('ds'))?'true':'false';
            me.settings.meaPdvCount = 0;
            me.settings.meaDvnCount = 0;
			
		  //HACK
		  	//me.settings.ds = 'true';
		  

            if (me.settings.mea == "true") {
                me.settings.meaFile = "mea-";
            } else {
                me.settings.meaFile = "";
            }
            if (me.settings.ds == "true") {
                me.settings.dsFile = "ds-";
            } else {
                me.settings.dsFile = "";
            }
            me.settings.attribut = el.getAttribute('data-attribut') || '';

            me.$element = $(el);
            //			me.$locations = $(me.settings.dom).parent();
            me.currentmarkerid = [];

            me.initGeoPosButton();

            if (me.isBusy() || !me.settings.dom) return;
            me.busy(true);

            /* Service call */
            $.ajax({
                url: me.settings.wsConf,
                type: 'POST',
                dataType: 'json',
                cache: false,
                data: {
                    page: me.settings.page,
                    version: me.settings.version,
                    order: me.settings.order,
                    area: me.settings.area,
                    ztid: me.settings.ztid
                },
                success: function(response) {
                    me.busy(false);

				  //HACK getMapConfiguration ------
//					response = {"lat":48.8566,"lng":2.35222,"zoom":6,"timeout":2000,"country":"fr","autocomplete":true,"clusterer":false,"filter":"dvnpdv","search":{"step":10,"radius":40,"types":[{"label":"pdv","count":"5"},{"label":"dvn","count":"3"}]},"services":[{"code":"E","label":"Citro\u00ebn Select","TYPE_SERVICE":"L","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"65","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/E.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/E.png","big":"\/design\/frontend\/images\/picto\/services\/E_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/E_big.png","index":0,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/E.png"},{"code":"PR","label":"PIECES DE RECHANGE","TYPE_SERVICE":"A","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"62","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/PR.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/PR.png","big":"\/design\/frontend\/images\/picto\/services\/PR_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/PR_big.png","index":1,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/PR.png"},{"code":"VN","label":"VENTES DE VEHICULES NEUFS","TYPE_SERVICE":"A","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"63","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/VN.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/VN.png","big":"\/design\/frontend\/images\/picto\/services\/VN_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/VN_big.png","index":2,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/VN.png"},{"code":"VO","label":"VENTES DE VEHICULES D'OCCASION","TYPE_SERVICE":"A","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"64","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/VO.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/VO.png","big":"\/design\/frontend\/images\/picto\/services\/VO_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/VO_big.png","index":3,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/VO.png"},{"code":"APV","label":"APRES-VENTE","TYPE_SERVICE":"A","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"61","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/APV.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/APV.png","big":"\/design\/frontend\/images\/picto\/services\/APV_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/APV_big.png","index":4,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/APV.png"},{"code":"DS1","label":"DS World","TYPE_SERVICE":"I","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"1000","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/APV.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/APV.png","big":"\/design\/frontend\/images\/picto\/services\/APV_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/APV_big.png","index":5,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/APV.png"},{"code":"DS2","label":"DS Store","TYPE_SERVICE":"I","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"1001","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/APV.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/APV.png","big":"\/design\/frontend\/images\/picto\/services\/APV_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/APV_big.png","index":6,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/APV.png"},{"code":"DS3","label":"DS Salon","TYPE_SERVICE":"I","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"1002","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/APV.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/APV.png","big":"\/design\/frontend\/images\/picto\/services\/APV_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/APV_big.png","index":7,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/APV.png"},{"code":"DS4","label":"DS Distributeur","TYPE_SERVICE":"I","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"1003","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/APV.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/APV.png","big":"\/design\/frontend\/images\/picto\/services\/APV_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/APV_big.png","index":8,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/APV.png"}]};
//					console.log(response);
			  

					
					
                    $.extend(me.settings.config, response);

                    if (me.settings.latest.lat !== null && me.settings.latest.lng !== null && me.settings.latest.zoom !== null) {
                        me.settings.config.lat = me.settings.latest.lat;
                        me.settings.config.lng = me.settings.latest.lng;
                        me.settings.config.zoom = me.settings.latest.zoom;
                    }

                    var options = {
                        center: new google.maps.LatLng(me.settings.config.lat, me.settings.config.lng),
                        zoom: me.settings.config.zoom,
                        disableDefaultUI: true,
                        scaleControl: true,
                        streetViewControl: true,
                        panControl: true,
                        zoomControl: false,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    };

                    /* Map init */

                    me.settings.map = new google.maps.Map(me.settings.dom, options);
                    me.settings.icon = {
                        url: me.settings.imgPath + 'design/frontend/images/mobile/picto/' + me.settings.meaFile + 'marker.png'
                    };
                    me.settings.iconHover = {
                        url: me.settings.imgPath + 'design/frontend/images/mobile/picto/' + me.settings.meaFile + 'markerHover.png'
                    };
                    me.settings.icon2 = {
                        url: me.settings.imgPath + 'design/frontend/images/mobile/picto/' + me.settings.meaFile + 'marker2.png'
                    };
                    me.settings.icon2Hover = {
                        url: me.settings.imgPath + 'design/frontend/images/mobile/picto/' + me.settings.meaFile + 'marker2Hover.png'
                    };

              if(me.settings.ds == "true"){
                  me.settings.icon3 = {
                      url: me.settings.imgPath + 'design/frontend/images/mobile/picto/' + me.settings.meaFile + me.settings.dsFile + 'marker1.png'
                  };
                  me.settings.icon3Hover = {
                      url: me.settings.imgPath + 'design/frontend/images/mobile/picto/' + me.settings.meaFile + me.settings.dsFile + 'marker1Hover.png'
                  };
                  me.settings.icon4 = {
                      url: me.settings.imgPath + 'design/frontend/images/mobile/picto/' + me.settings.meaFile + me.settings.dsFile + 'marker2.png'
                  };
                  me.settings.icon4Hover = {
                      url: me.settings.imgPath + 'design/frontend/images/mobile/picto/' + me.settings.meaFile + me.settings.dsFile + 'marker2Hover.png'
                  };
                  me.settings.icon5 = {
                      url: me.settings.imgPath + 'design/frontend/images/mobile/picto/' + me.settings.meaFile + me.settings.dsFile + 'marker3.png'
                  };
                  me.settings.icon5Hover = {
                      url: me.settings.imgPath + 'design/frontend/images/mobile/picto/' + me.settings.meaFile + me.settings.dsFile + 'marker3Hover.png'
                  };
                  me.settings.icon6 = {
                      url: me.settings.imgPath + 'design/frontend/images/mobile/picto/' + me.settings.meaFile + me.settings.dsFile + 'marker.png'
                  };
                  me.settings.icon6Hover = {
                      url: me.settings.imgPath + 'design/frontend/images/mobile/picto/' + me.settings.meaFile + me.settings.dsFile + 'markerHover.png'
                  };
                  me.settings.icon7 = {
                      url: me.settings.imgPath + 'design/frontend/images/mobile/picto/' + me.settings.meaFile + me.settings.dsFile + 'marker.png'
                  };
                  me.settings.icon7Hover = {
                      url: me.settings.imgPath + 'design/frontend/images/mobile/picto/' + me.settings.meaFile + me.settings.dsFile + 'markerHover.png'
                  };				  
				  
              }
                    me.settings.shadow = {
                        url: me.settings.imgPath + 'design/frontend/images/mobile/picto/marker-shadow.png',
                        anchor: new google.maps.Point(19, 35)
                    };
                    me.settings.shadowHover = {
                        url: me.settings.imgPath + 'design/frontend/images/mobile/picto/marker-shadowHover.png',
                        anchor: new google.maps.Point(19, 41)
                    };


                    /* Has default open item */
                    var hash = document.location.hash.substr(1),
                        open = me.markers[hash];
                    if (open) {
                        me.details(open);
                    };



                    var input = $(el).find('input[name="address"]').get(0);
                    /* Enable locations search with autocomplete module */
                    if (me.settings.config.autocomplete) {
                        var autocomplete = new google.maps.places.Autocomplete(input, {
                            componentRestrictions: {
                                country: me.settings.config.country
                            }
                        });

                        autocomplete.bindTo('bounds', me.settings.map);
                        google.maps.event.addListener(autocomplete, 'place_changed', function() {
                            var place = autocomplete.getPlace();

                            // Maj dataLayer
                            dataLayer[0].internalSearchKeyword = place.formatted_address;
                            dataLayer[0].internalSearchType = "pdv";

                            me.fitSearch(place);

                        });
                    };

                    /* Enable geocoder for custom search when button is clicked */
                    var geocoder = new google.maps.Geocoder();
                    $(el).on('submit', function(e) {
                        e.preventDefault();

                        // Maj dataLayer
                        dataLayer[0].internalSearchKeyword = input.value;
                        dataLayer[0].internalSearchType = "pdv";

                        var string = input.value;
                        geocoder.geocode({
                            'address': string
                        }, function(results, status) {

                            if (status == google.maps.GeocoderStatus.OK) {
                                var result = [];
                                

                                $('.locator fieldset input').addClass('searchdone');
                                if ($('.geoloc').hasClass('geolocdone')) {
                                    $('.geoloc').removeClass('geolocdone');
                                }

                                function getCountry(address) {
                                    for (var i = 0; i < address.length; i++) {
                                        if (address[i].types[0] === 'country') {
                                            return address[i].short_name.toLowerCase();
                                        }
                                    }
                                }

                                for (var i = 0; i < results.length; i++) {
                                    if ((me.settings.config.country === getCountry(results[i].address_components)) || (me.settings.config.country === 'ru')) {
                                        result.push(results[i]);
                                    }
                                }


                                var filledlist = $('.locations').find('.stores');
                                if (filledlist.hasClass('filled')) {
                                    filledlist.removeClass('filled');
                                    $('.locations .items').empty();
                                }

								
								for (var i = 0, len = me.settings.markers.length; i < len; i++) {
                                    me.settings.markers[i].setMap(null);
                                }
								if (result.length)
								{
                                 var currentlat = result[0].geometry.location.lat();
                                 var currentlng = result[0].geometry.location.lng();
								 me.list(currentlat, currentlng);
								}

                               
                                

                            }
                        });

                    });



                    /* Filters */
                    if (me.settings.config.services) {

                        var $filterTpl = $(me.settings.dom).parents('.locations').find('.filtersTpl'),
                            tpl = $filterTpl.html();
                        if (0 != $filterTpl.length) {

                            var compiledTemplate = _.template(tpl, {
                                services: me.settings.config.services,
                                base: me.settings.base
                            });

                            $(me.settings.dom).append(compiledTemplate).find('.mapFilters input').each(function() {
                                var root = $(this).parents('.mapFilters').get(0);
                                if (!root._inputs) root._inputs = [];

                                this._root = root;
                                root._inputs.push(this);

                            }).change(function() {

                                var ids = [];
                                $(this._root._inputs).each(function() {
                                    if (this.checked) ids.push(parseInt(this.value));
                                });
                                me.settings.latest.filters = ids;

                                /* Re do latest search */
                                me.filter(ids);

                            });

                            $(me.settings.dom).find('.mapFilters span').click(function() {
                                $(this).parent().toggleClass('open').find('ul').stop(true, false).slideToggle(250);
                            });

                        };

                    };

                    /* Callback */
                    me.settings.onLoad.call(me);

                }
            });

        },

        initGeoPosButton: function() {
            //redmine #3159
            var me = this,
                el = this.element,
                $geolocButton = $(el).find('.geoloc');
            /* Enable geolocation if enabled */
            if (navigator.geolocation) {
                /* Backup timer because browser doesen't trigger error when prompt is simply closed */
                var geolocTimer = 0,
                    geolocBackup = function() {

                        clearTimeout(geolocTimer);
                        me.busy(false);

                        /* Callback */
                        me.settings.onGeolocError.call(me);

                    },
                    checkGeoloc = function() {
                        // Empty previous list and marker
                        $('.locations').find('.stores').removeClass('filled');
                        $('.locations .items').empty();
                        for (var i = 0, len = me.settings.markers.length; i < len; i++) {
                            me.settings.markers[i].setMap(null);
                        }

                        if (me.isBusy()) return;
                        me.busy(true);
                        if (sessionStorage.getItem("position") === null) {

                            geolocTimer = window.setTimeout(function() {
                                geolocBackup();
                            }, 10000);

                            navigator.geolocation.getCurrentPosition(function(pos) {
                                clearTimeout(geolocTimer);
                                //$.fancybox.close();
                                me.busy(false);

                                var currentlat = pos.coords.latitude;
                                var currentlng = pos.coords.longitude;
                                me.list(currentlat, currentlng, 'geo');

                                posgeo = {
                                    latitude: pos.coords.latitude,
                                    longitude: pos.coords.longitude
                                }
                                sessionStorage.setItem('position', JSON.stringify(posgeo));
                                /* Callback */
                                me.settings.onGeoloc.call(me);

                            }, geolocBackup, {
                                enableHighAccuracy: false,
                                maximumAge: 60000,
                                timeout: 10000
                            });
                        } else {
                            var pos = JSON.parse(sessionStorage.getItem("position"));
                            me.busy(false);
                            me.list(pos.latitude, pos.longitude, 'geo');
                        }
                    }

                $geolocButton.click(checkGeoloc).css({
                    cursor: 'pointer'
                });
            } else {
                $geolocButton.css({
                    opacity: 0.25
                });
                checkGeoloc();
            }
            $('.geoloc').on('click', function() {
                $(this).addClass('geolocdone');
                if ($('.locator fieldset input').hasClass('searchdone')) {
                    $('.locator fieldset input').removeClass('searchdone')
                }
            });
        },
		getCodeFromIndex: function(index) { var code="";
											var me = this;
											var getcode = "";
												for(var ak=0; ak<me.settings.config.services.length; ak++){
													if (me.settings.config.services[ak].index==index) 
													getcode = me.settings.config.services[ak].code;
												}	
												return getcode;
		},
        /* Set markers on the map and activate clustering */
        setMarkers: function(markers) {
            /* Vars */
            var me = this,
                el = this.element;

            me.settings.markers = [];
            me.markers = [];

            /* Has clusterer */
            if (me.settings.config.clusterer) {
                /* Clear existing markers */
                if (me.settings.clusterer) {
                    me.settings.clusterer.clearMarkers();
                } else {
                    /* Initialize cluster manager */
                    var options = {
                        averageCenter: true,
                        gridSize: 25,
                        styles: [{
                            url: me.settings.imgPath + 'design/frontend/images/mobile/picto/cluster.png',
                            height: 30,
                            width: 37
                        }]
                    };
                    me.settings.clusterer = new MarkerClusterer(me.settings.map, me.settings.markers, options);
                };
            };

            if (markers !== null) {
                for (var i = 0, len = markers.length; i < len; i++) {
                    var data = markers[i];
					
					
					// ICI on cr�e une liste des services que l'on pourra tester
				var chaineServices =",";
				for(var zi=0; zi<data.services.length; zi++){
					//console.log('==> '+data.services[i]+']:' + me.getCodeFromIndex(data.services[i]));
					chaineServices=chaineServices+me.getCodeFromIndex(data.services[zi])+',';
				}
				 /*HACK*/
	/*			 
				console.log(data.name);	
				console.log(chaineServices);	
				console.log(data.services);
				*/
				
					

                    /* Has clusterer */
                    if (me.settings.config.clusterer) {
                        if('pdv' == data.type){
                            if('true' != me.settings.ds){
                                var icon = me.settings.icon;
                            } else {

									if(chaineServices.indexOf(',DS1,') !== -1){
										var icon = me.settings.icon3; 
										var iconRoll = me.settings.icon3Hover; 
									} else if(chaineServices.indexOf(',DS2,') !== -1){
										var icon = me.settings.icon4; 
										var iconRoll = me.settings.icon4Hover;
									} else if(chaineServices.indexOf(',DS3,') !== -1){
										var icon = me.settings.icon5; 
										var iconRoll = me.settings.icon5Hover;
									} else if(chaineServices.indexOf(',DS4,') !== -1){
										var icon = me.settings.icon6; 
										var iconRoll = me.settings.icon6Hover;
									} else{
										var icon = me.settings.icon7; 
										var iconRoll = me.settings.icon7Hover;
									}
                            }

                        } else {
                            if('true' != me.settings.ds){
                                var icon = me.settings.icon2;
                            } else {
												if(chaineServices.indexOf(',DS1,') !== -1){
											var icon = me.settings.icon3; 
											var iconRoll = me.settings.icon3Hover; 
										} else if(chaineServices.indexOf(',DS2,') !== -1){
											var icon = me.settings.icon4; 
											var iconRoll = me.settings.icon4Hover;
										} else if(chaineServices.indexOf(',DS3,') !== -1){
											var icon = me.settings.icon5; 
											var iconRoll = me.settings.icon5Hover;
										} else if(chaineServices.indexOf(',DS4,') !== -1){
											var icon = me.settings.icon6; 
											var iconRoll = me.settings.icon6Hover;
										} else{
											var icon = me.settings.icon7; 
											var iconRoll = me.settings.icon7Hover;
										}
                            }
                        }
                        var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(data.lat, data.lng),
                            icon: icon,
                            shadow: me.settings.shadow
                        });
                    } else {
                        /* Create marker */
                        if('pdv' == data.type){
                            if('true' != me.settings.ds){
                                var icon = me.settings.icon;
                            } else {
        
		
										if(chaineServices.indexOf(',DS1,') !== -1){
															var icon = me.settings.icon3; 
															var iconRoll = me.settings.icon3Hover; 
														} else if(chaineServices.indexOf(',DS2,') !== -1){
															var icon = me.settings.icon4; 
															var iconRoll = me.settings.icon4Hover;
														} else if(chaineServices.indexOf(',DS3,') !== -1){
															var icon = me.settings.icon5; 
															var iconRoll = me.settings.icon5Hover;
														} else if(chaineServices.indexOf(',DS4,') !== -1){
															var icon = me.settings.icon6; 
															var iconRoll = me.settings.icon6Hover;
														} else{
															var icon = me.settings.icon7; 
															var iconRoll = me.settings.icon7Hover;
														}
		
                            }

                        } else {
                            if('true' != me.settings.ds){
                                var icon = me.settings.icon2;
                            } else {
										if(chaineServices.indexOf(',DS1,') !== -1){
																	var icon = me.settings.icon3; 
																	var iconRoll = me.settings.icon3Hover; 
																} else if(chaineServices.indexOf(',DS2,') !== -1){
																	var icon = me.settings.icon4; 
																	var iconRoll = me.settings.icon4Hover;
																} else if(chaineServices.indexOf(',DS3,') !== -1){
																	var icon = me.settings.icon5; 
																	var iconRoll = me.settings.icon5Hover;
																} else if(chaineServices.indexOf(',DS4,') !== -1){
																	var icon = me.settings.icon6; 
																	var iconRoll = me.settings.icon6Hover;
																} else{
																	var icon = me.settings.icon7; 
																	var iconRoll = me.settings.icon7Hover;
																}
							
							
                            }
                        }
                        var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(data.lat, data.lng),
                            icon: icon,
                            shadow: me.settings.shadow,
                            map: me.settings.map
                        });
                    };

                    /* Store marker data */
                    marker._storeId = data.id;
					if('true' == me.settings.ds){
							marker._icon = icon;
							marker._iconHover = iconRoll;
						  }					
                    marker._storeRRDI = data.rrdi;
                    marker._storeName = data.name;
                    marker._type = data.type;
                    marker._services = data.services;
                    google.maps.event.addListener(marker, 'click', function() {
                        me.details(this);
                    });

                    google.maps.event.addListener(marker, 'mouseover', function() {
                        me.highlight(this, false, me.settings);
                    });
                    google.maps.event.addListener(marker, 'mouseout', function() {
                        me.downplay(this, false, me.settings);
                    });

                    /* Store marker */
                    me.settings.markers.push(marker);
                    me.markers[data.id] = marker;

                }
            }

            /* Has clusterer */
            if (me.settings.config.clusterer) {
                me.settings.clusterer.addMarkers(me.settings.markers);
            };

            /* Events */
            if (me.settings.latest.lat && me.settings.latest.lng) {
                me.list();
            };

        },

        filter: function(filters) {

            /* Vars */
            var me = this,
                el = this.element,
                output = [],
                markers = me.settings.config.markers;

            for (var i = markers.length; i--;) {
                for (var j = filters.length; j--;) {
                    if (-1 != markers[i].services.indexOf(filters[j])) {
                        output.push(markers[i]);
                        break;
                    };
                };
            };

            /* Reset markers */
            me.setMarkers(output);

            /* Callback */
            me.settings.onFilter.call(me);

            return output;

        },

        highlight: function(marker, markerOnly, instance) {
            var me = this;

		if('true' != me.settings.ds){
              var icon = ('pdv' == marker._type) ? instance.iconHover : instance.icon2Hover;
          } else {
              var icon = ('pdv' == marker._type) ? marker._iconHover : marker._iconHover;
          }
			
            marker.setIcon(icon);
            marker.setShadow(instance.shadowHover);
            if (marker._item && true != markerOnly) $(marker._item).addClass('hover');

        },
        downplay: function(marker, markerOnly, instance) {
            var icon = ('pdv' == marker._type) ? instance.icon : instance.icon2;
            marker.setIcon(icon);
            marker.setShadow(instance.shadow);
            if (marker._item && true != markerOnly) $(marker._item).removeClass('hover');
        },

        busy: function(is) {

            /* Vars */
            var me = this;

            /* Set state */
            me.busyState = is;

            /* Launch wait overlay timer if busy */
            if (is) {
                $(me.element).trigger('busy');
                me.loader.show();

                /* Hide wait overlay */
            } else {
                $(me.element).trigger('notbusy');
                me.loader.hide();

            }

        },
        isBusy: function() {

            /* Vars */
            var me = this;
            return me.busyState;

        },

        /* Clear results */
        clear: function() {

            $(this.settings.dom).parents('.locations').find('.stores .items').html('');

        },

        /* Find */
        find: function(lat, lng, type) {
            /* Vars */
            var me = this,
                el = me.element;

            /* Display if not displayed */
            $(me.settings.dom).parents('.locations').show();

            /* Refresh latest find if lat or lng missing */
            if (!lat || !lng) {
                var lat = me.settings.latest.lat,
                    lng = me.settings.latest.lng;
                type = me.settings.latest.type;
            } else {
                me.settings.latest.lat = lat;
                me.settings.latest.lng = lng;
                me.settings.latest.type = type;
            }

            /* Radian */
            function rad(x) {
                return x * Math.PI / 180;
            }

            var R = 6371,
                /* radius of earth in km */
                distances = [],
                markers = me.settings.markers;


            /* Calculate and store distances */
            for (var i = 0, len = markers.length; i < len; i++) {
                var that = markers[i]

                var mlat = that.position.lat(),
                    mlng = that.position.lng(),
                    dLat = rad(mlat - lat),
                    dLong = rad(mlng - lng),
                    a = Math.sin(dLat / 2) * Math.sin(dLat / 2) + Math.cos(rad(lat)) * Math.cos(rad(lat)) * Math.sin(dLong / 2) * Math.sin(dLong / 2),
                    c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)),
                    d = R * c;

                that._km = d;
                distances.push(that);
            }

            /* Sorting by km and select nearest*/
            distances.sort(function(a, b) {
                return a._km - b._km;
            });
            /* Parse array to get firsts elements of type */
            var getType = function(type, count) {
                var selected = [];
                for (var i = 0, len = distances.length; i < len; i++) {
                    if (type == distances[i]._type) selected.push(i);
                    if (selected.length == count) break;
                }
                return selected;
            }

            /* If has privileged pattern */
            if (me.settings.config.search.types) {

                /* Privileged pattern */
                var privileged = [],
                    searchType;
                for (var i = 0, len = me.settings.config.search.types.length; i < len; i++) {
                    searchType = me.settings.config.search.types[i];
                    privileged = privileged.concat(getType(searchType.label, searchType.count));
                }

                privileged.sort(function(a, b) {
                    return a - b;
                });

                var base = privileged.length,
                    podium = [];
                for (var i = base - 1; i >= 0; i--) {
                    var index = privileged[i],
                        entry = distances.splice(index, 1);
                    podium.unshift(entry[0]);
                }
                distances = podium.concat(distances);
            }

            /* Strip all markers that are beyond the limit */
            var cut = -1,
                limit = me.settings.config.search.radius;
            for (var i = base || 0; i < distances.length; i++) {
                if (distances[i] && limit < distances[i]._km) {
                    cut = i;
                    break;
                }
            }
            distances = distances.slice(0, cut);

            /* Clear current results  */
            me.clear();

            var holder = $(me.settings.dom).parents('.locations').find('*[data-result]'),
                $holder = (holder.length) ? holder : $('#results'),
                string = $holder.attr('data-' + type),
                init = (privileged) ? privileged.length : me.settings.config.search.step;

            string = string.replace('###count###', distances.length);
            if ('search' == type) string = string.replace('###address###', $(el).find('*[name="address"]').val());

            $holder.html(string);

            me.settings.state = 0;
            me.list(distances, me.settings.state, init);

        },

        /* Adjust zoom to englobe results */
        fitSearch: function(place) {

            if (!place.geometry) return;

            var me = this;

            // // If the place has a geometry, then present it on a map.
            // if (place.geometry.viewport) {
            // 	locator.map.fitBounds(place.geometry.viewport);
            // } else {
            // 	locator.map.setCenter(place.geometry.location);
            // 	locator.map.setZoom(17);  // Why 17? Because it looks good.
            // }
            /* Find nearest stores from this place */
            var lat = place.geometry.location.lat(),
                lng = place.geometry.location.lng();

            me.list(lat, lng, 'search');

        },

        /* List and display */
        list: function(currentlat, currentlng, markers) {
            ///* Vars */
            var me = this,
                el = me.element;

            var start = 0;
            var filledlist = $('.locations').find('.stores');

            if (me.isBusy()) return;

            me.busy(true);
            $.ajax({
                url: me.settings.wsList,
                type: 'POST',
                dataType: 'json',
                cache: false,
                data: {
                    lat: Math.round(currentlat * 100) / 100,
                    long: Math.round(currentlng * 100) / 100,
                    page: me.settings.page,
                    version: me.settings.version,
                    order: me.settings.order,
                    area: me.settings.area,
                    ztid: me.settings.ztid,
                    attribut: me.settings.attribut,
					brandactivity: me.settings.brandactivity
                },
                success: function(items) {
                    me.busy(false);
						// Hack
				//		items = [{"id":"0000038241","rrdi":"020842E01F","media":null,"name":"DS DISTRIBUTEUR -CITRO\u00cbN RETAIL PARIS REPUBLIQUE","address":"62 AVENUE DE LA REPUBLIQUE<br \/>75011&nbsp;PARIS","phone":"01 49 29 62 62","distance":2,"services":[4,1,2,8,3,0],"lat":48.8646774,"lng":2.376711,"type":"dvn","isAgent":false},{"id":"0000040609","rrdi":"990131Z01F","media":null,"name":"GARAGE DU FAUBOURG","address":"33 RUE DE REUILLY<br \/>75012&nbsp;PARIS","phone":"01 43 72 70 76","distance":3,"services":[4,1,2,3],"lat":48.8474,"lng":2.3867,"type":"pdv","isAgent":true},{"id":"0000046339","rrdi":"058870P01F","media":null,"name":"CITRO\u00cbN RETAIL PARIS 8","address":"25 RUE DE CONSTANTINOPLE<br \/>75008&nbsp;PARIS","phone":"01 40 08 60 40","distance":3.2,"services":[4,1,2,3,0],"lat":48.8802643,"lng":2.319153,"type":"pdv","isAgent":false},{"id":"0000041370","rrdi":"038901J01F","media":null,"name":"DS Salon - CITRO\u00cbN RETAIL PANTIN ETS JAURES","address":"59 BIS AVENUE JEAN JAURES<br \/>75019&nbsp;PARIS","phone":"01 44 52 79 79","distance":3.3,"services":[4,2,3,0,7],"lat":48.88437,"lng":2.376887,"type":"pdv","isAgent":false},{"id":"0000046330","rrdi":"020910D01F","media":null,"name":"DS WORLD PARIS","address":"33 RUE FRANCOIS 1ER<br \/>75008&nbsp;PARIS","phone":"01 53 57 33 08","distance":3.5,"services":[2,5],"lat":48.8683739,"lng":2.303964,"type":"pdv","isAgent":false},{"id":"0000038619","rrdi":"766108K01F","media":null,"name":"DS STORE - GARAGE CITE LECOURBE","address":"88 RUE LECOURBE<br \/>75015&nbsp;PARIS","phone":"01 47 83 22 18","distance":3.8,"services":[4,1,6,2,3],"lat":48.8432,"lng":2.30466,"type":"pdv","isAgent":true},{"id":"0000040618","rrdi":"990138L01F","media":null,"name":"DS WORLD AUTO SPECIALITES","address":"42 RUE BELGRAND<br \/>75020&nbsp;PARIS","phone":"01 47 97 20 59","distance":4,"services":[4,1,2,3,5],"lat":48.8647,"lng":2.40404,"type":"pdv","isAgent":true}];
						//console.log(items);


					me.settings.config.markers = items;
                    me.setMarkers(items);

                    var currentmarkerid = [];

                    if (filledlist.hasClass('filled')) {
                        var currentmarkerid = [];

                        for (var i = me.settings.markers.length - 1; i >= 0; i--) {
                            currentmarkerid.push(me.settings.markers[i]);
                        };
                        me.markersevent(currentmarkerid);
                    }

                    //if (!(filledlist.hasClass('filled'))) {
                        me.createList(items);
                    //}

                    // google.maps.event.addListener(me.settings.map, 'dragend', function(e) {
                    // 
                    //     var currentlat = this.center.lat();
                    //     var currentlng = this.center.lng();
                    // 
                    //     for (var i = 0, len = me.settings.markers.length; i < len; i++) {
                    //         me.settings.markers[i].setMap(null);
                    //     }
                    //     me.list(currentlat, currentlng);
                    // });
                }

            });

        },

        createList: function(items) {
            ///* Vars */
            var me = this,
                el = me.element,
                start = 0;
            /* Vars */
            var tpl = $(me.settings.dom).parents('.locations').find('.stores > script').html(),
                $placeholder = $(me.settings.dom).parents('.locations').find('.stores .items'),
                compiled = '';

            // Create a table of markers
            var currentmarkerid = [];
            for (var i = me.settings.markers.length - 1; i >= 0; i--) {
                currentmarkerid.push(me.settings.markers[i]);
            };


            /* Appends */
            if (items != null) {
                me.settings.meaPdvCount=0;
                me.settings.meaDvnCount=0;
                for (var i = 0, len = items.length; i < len; i++) {

                    if (me.settings.mea == "true") {
                        if (items[i].type == "pdv") {
                            me.settings.meaPdvCount++;
                        } else if (items[i].type == "dvn") {
                            me.settings.meaDvnCount++;
                        }
                    }

                    var compiledTemplate = _.template(tpl, {
                        data: items[i],
                        services: me.settings.config.services
                    });
                    compiled += compiledTemplate;
                }
            } else {
                items = [];
                items.length = 0;
                if (me.settings.mea == "true") {
                    me.settings.meaPdvCount = 0;
                    me.settings.meaDvnCount = 0;
                }
            }

            /* Events */
            $placeholder.append(compiled)

            me.markersevent(currentmarkerid);

            /* ADDMORE */
            var more = $placeholder.attr('data-more');
            var from = me.settings.config.search.step,
                step = me.settings.config.search.step;

            $placeholder.find('.addmore').remove();

            $placeholder.find('.item:gt(' + step + ')').hide();

            if ($placeholder.find('.item:hidden').length) {
                $placeholder.append(more).find('.addmore a').click(function(e) {
                    e.preventDefault;

                    $placeholder.find('.item:lt(' + (from + step) + '):not(.item:lt(' + from + '))').show();
                    from += step;

                    if ($placeholder.find('.item:hidden').length == 0) {
                        $placeholder.find('.addmore').remove();
                    }
                });
            }

            /* Callback */
            me.settings.onList.call(me);

            /* If is first */
            if (0 < start) return;

            /* Open results sidebar */
            $placeholder.parents('.stores').addClass('filled');

            /* Small delay to prevent bounds misplace */
            setTimeout(function() {
                /* Reset map center */
                var currCenter = me.settings.map.getCenter();
                google.maps.event.trigger(me.settings.map, 'resize');
                me.settings.map.setCenter(currCenter);

                /* Fit selection bounds */
                var bounds = new google.maps.LatLngBounds();
                for (var i = 0; i < me.settings.markers.length; i++) {
                    bounds.extend(me.settings.markers[i].position);
                };
                me.settings.map.fitBounds(bounds);
            }, 100);


            /***** Write number of results ****/
            if ($('.searchdone').length) {
                var holder = $(me.settings.dom).parents('.locations').find('*[data-result]'),
                    $holder = (holder.length) ? holder : $('#results');


                if (me.settings.mea == "true") {
                    if ((me.settings.meaPdvCount > 0) && (me.settings.meaDvnCount > 0)) {
                        string = $holder.attr('data-search-mea-both');
                    }
                    if ((me.settings.meaPdvCount > 0) && (me.settings.meaDvnCount == 0)) {
                        string = $holder.attr('data-search-mea-pdv');
                    }
                    if ((me.settings.meaPdvCount == 0) && (me.settings.meaDvnCount > 0)) {
                        string = $holder.attr('data-search-mea-dvn');
                    }
                } else {
                    string = $holder.attr('data-search');
                }

                string = string.replace('###count###', items.length);
                string = string.replace('###countPdv###', me.settings.meaPdvCount);
                string = string.replace('###countDvn###', me.settings.meaDvnCount);
                string = string.replace('###address###', '"' + $(el).find('*[name="address"]').val() + '"');

                $holder.html(string);

            }

            if ($('.geolocdone').length) {
                var holder = $(me.settings.dom).parents('.locations').find('*[data-result]'),
                    $holder = (holder.length) ? holder : $('#results');


                if (me.settings.mea == "true") {
                    if ((me.settings.meaPdvCount > 0) && (me.settings.meaDvnCount > 0)) {
                        string = $holder.attr('data-geo-mea-both');
                    }
                    if ((me.settings.meaPdvCount > 0) && (me.settings.meaDvnCount == 0)) {
                        string = $holder.attr('data-geo-mea-pdv');
                    }
                    if ((me.settings.meaPdvCount == 0) && (me.settings.meaDvnCount > 0)) {
                        string = $holder.attr('data-geo-mea-dvn');
                    }
                } else {
                    string = $holder.attr('data-geo');
                }

                string = string.replace('###count###', items.length);
                string = string.replace('###countPdv###', me.settings.meaPdvCount);
                string = string.replace('###countDvn###', me.settings.meaDvnCount);
                string = string.replace('###address###', '"' + $(el).find('*[name="address"]').val() + '"');

                $holder.html(string);
            }
        },

        markersevent: function(currentmarkerid) {
            var me = this,
                $items = me.$element.find('.items .item'),
                itemstoreid;

            $('.locations .items .item').on('mouseenter', function() {
                itemstoreid = $(this).data('storeid');
                for (var i = 0, len = currentmarkerid.length; i < len; i++) {
                    if (itemstoreid == currentmarkerid[i]._storeId) {
                        me.highlight(currentmarkerid[i], false, me.settings);
                    }
                }
            });

            $('.locations .items .item').on('mouseleave', function() {
                itemstoreid = $(this).data('storeid');
                for (var i = 0, len = currentmarkerid.length; i < len; i++) {
                    if (itemstoreid == currentmarkerid[i]._storeId) {
                        me.downplay(currentmarkerid[i], false, me.settings);
                    }

                }
            });

            $('.locations .items .item').on('click', function() {
                itemstoreid = $(this).data('storeid');
                for (var i = 0, len = currentmarkerid.length; i < len; i++) {
                    if (itemstoreid == currentmarkerid[i]._storeId) {
                        me.details(currentmarkerid[i]);
                    }
                }
            });
        },


        /* Details */
        details: function(caller) {
            /* Vars */
            var me = this;
            /* Callback */
            //if(!me.settings.onDetails.call(me)) return;
            var id = caller._storeId || caller.getAttribute('data-id'),
                url = me.settings.wsDetails.replace('###id###', id);

            document.location = url;

        }

    };

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function(options) {
        return this.each(function() {
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName, new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);
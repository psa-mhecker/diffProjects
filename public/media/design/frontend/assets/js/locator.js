var lastRelease = '20150526';
// the semi-colon before function invocation is a safety

;
(function($, window, document, undefined) {

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
      this.dragState = false; // true, si la carte vient de subir un drag&drop avec recalcul de la position ne plus faire de fitbound dans ce cas!

      // jQuery has an extend method which merges the contents of two or more objects, storing the result in the first object. The first object
      // is generally empty as we don't want to alter the default options for future instances of the plugin
      this.settings = $.extend({}, defaults, options);
      this._defaults = defaults;
      this._name = pluginName;
      this.init();
    };

    function SetAutocomplete(me, lblCountry) {

      if (me.$element.find('input[name="address"]').length) {
        var autocomplete = new google.maps.places.Autocomplete(me.$element.find('input[name="address"]').get(0), {
          componentRestrictions: {
            'country': lblCountry
          }
        });
        autocomplete.bindTo('bounds', me.settings.map);
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
          var place = autocomplete.getPlace();

          // Maj dataLayer
          dataLayer[0].internalSearchKeyword = place.formatted_address;
          dataLayer[0].internalSearchType = "pdv";

          me.emptyResultList();
          me.fitSearch(place);
          me.switchSearchMethod('search');
        });
      }
    }


    Plugin.prototype = {

        /* Initalisation */
        init: function() {

          var me = this,
            el = this.element,
            domid = el.getAttribute('data-dom') || 'map-canvas';

          /* Vars */
          me.settings.base = domid;
          me.settings.dom = document.getElementById(domid);
          me.settings.wsConf = el.getAttribute('data-config');
          me.settings.wsList = el.getAttribute('data-list');
		  me.settings.brandactivity = el.getAttribute('data-brand-activity');
          me.settings.wsDetails = el.getAttribute('data-details');
          me.settings.imgPath = el.getAttribute('data-path') || '';
          me.settings.page = el.getAttribute('data-page');
          me.settings.version = el.getAttribute('data-version');
          me.settings.order = el.getAttribute('data-order');
          me.settings.area = el.getAttribute('data-area');
          me.settings.ztid = el.getAttribute('data-ztid');
          me.settings.mea = el.getAttribute('data-mea');
          me.settings.filtersStatus = el.getAttribute('data-filter-bar');
          me.settings.ds = ($(el).hasClass('ds'))?'true':'false';
		me.settings.c = ($(el).hasClass('c-skin'))?'true':'false';
          me.settings.advisor = $(".clsDealerDetail").attr('data-advisor');
          me.settings.wsadvisor = $(".clsDealerDetail").attr('data-wsadvisor');

          me.settings.meaPdvCount = 0;
          me.settings.meaDvnCount = 0;
          me.settings.meaAgentCount = 0;
          me.settings.meaConcession = 0;
		  
		  
		  //HACK


//		  me.settings.ds = 'true';


		  

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
          me.$locations = $(me.settings.dom).parent();
          me.currentmarkerid = [];
          me.initGeoPosButton();

          if (me.isBusy() || !me.settings.dom) return;
          me.busy(true);

          // Verif context onglet
          // Verif si carte dans un conteneur secret
          if ($(me.settings.dom).parents('.secret').length > 0) {
            // Verif si ce conteneur est affich� par d�faut
            if ($(me.settings.dom).parents('.secret').css('display') === "block") {
              me.build();
            } else {
              // S'il est ferm� -> je surveille evenement
              $('body').on('gmapBuild', function() {
                me.build();
              });
            }
          } else {
            // Si carte dans un conteneur classique on la g�n�re.
            me.build();
          }

        },
        build: function() {
          var me = this,
            el = this.element;
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
	//		  response = {"lat":48.8566,"lng":2.35222,"zoom":6,"timeout":2000,"country":"fr","autocomplete":true,"clusterer":false,"filter":"dvnpdv","search":{"step":10,"radius":40,"types":[{"label":"pdv","count":"5"},{"label":"dvn","count":"3"}]},"services":[{"code":"E","label":"Citro\u00ebn Select","TYPE_SERVICE":"L","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"65","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/E.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/E.png","big":"\/design\/frontend\/images\/picto\/services\/E_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/E_big.png","index":0,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/E.png"},{"code":"PR","label":"PIECES DE RECHANGE","TYPE_SERVICE":"A","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"62","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/PR.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/PR.png","big":"\/design\/frontend\/images\/picto\/services\/PR_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/PR_big.png","index":1,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/PR.png"},{"code":"VN","label":"VENTES DE VEHICULES NEUFS","TYPE_SERVICE":"A","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"63","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/VN.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/VN.png","big":"\/design\/frontend\/images\/picto\/services\/VN_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/VN_big.png","index":2,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/VN.png"},{"code":"VO","label":"VENTES DE VEHICULES D'OCCASION","TYPE_SERVICE":"A","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"64","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/VO.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/VO.png","big":"\/design\/frontend\/images\/picto\/services\/VO_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/VO_big.png","index":3,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/VO.png"},{"code":"APV","label":"APRES-VENTE","TYPE_SERVICE":"A","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"61","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/APV.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/APV.png","big":"\/design\/frontend\/images\/picto\/services\/APV_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/APV_big.png","index":4,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/APV.png"},{"code":"DS1","label":"DS World","TYPE_SERVICE":"I","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"1000","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/APV.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/APV.png","big":"\/design\/frontend\/images\/picto\/services\/APV_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/APV_big.png","index":5,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/APV.png"},{"code":"DS2","label":"DS Store","TYPE_SERVICE":"I","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"1001","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/APV.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/APV.png","big":"\/design\/frontend\/images\/picto\/services\/APV_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/APV_big.png","index":6,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/APV.png"},{"code":"DS3","label":"DS Salon","TYPE_SERVICE":"I","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"1002","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/APV.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/APV.png","big":"\/design\/frontend\/images\/picto\/services\/APV_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/APV_big.png","index":7,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/APV.png"},{"code":"DS4","label":"DS Distributeur","TYPE_SERVICE":"I","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"1003","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/APV.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/APV.png","big":"\/design\/frontend\/images\/picto\/services\/APV_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/APV_big.png","index":8,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/APV.png"}]};

		//	  console.log(response);
			  
			  
			  
              $.extend(me.settings.config, response);
			  
              setTimeout(function() {
                SetAutocomplete(me, me.settings.config.country);
              }, 100);

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
                zoomControl: true,
                mapTypeId: google.maps.MapTypeId.ROADMAP
              };

              // Map init
              me.settings.map = new google.maps.Map(me.settings.dom, options);

              me.settings.icon = {
                url: me.settings.imgPath + 'design/frontend/images/illustrations/' + me.settings.meaFile + 'marker.png'
              };
              me.settings.iconHover = {
                url: me.settings.imgPath + 'design/frontend/images/illustrations/' + me.settings.meaFile + 'markerHover.png'
              };
              me.settings.icon2 = {
                url: me.settings.imgPath + 'design/frontend/images/illustrations/' + me.settings.meaFile + 'marker2.png'
              };
              me.settings.icon2Hover = {
                url: me.settings.imgPath + 'design/frontend/images/illustrations/' + me.settings.meaFile + 'marker2Hover.png'
              };
			  
              /*if(me.settings.ds == "true"){
                  me.settings.icon3 = {
                      url: me.settings.imgPath + 'design/frontend/images/picto/' + me.settings.meaFile + me.settings.dsFile + 'marker1.png'
                  };
                  me.settings.icon3Hover = {
                      url: me.settings.imgPath + 'design/frontend/images/picto/' + me.settings.meaFile + me.settings.dsFile + 'marker1Hover.png'
                  };
                  me.settings.icon4 = {
                      url: me.settings.imgPath + 'design/frontend/images/picto/' + me.settings.meaFile + me.settings.dsFile + 'marker2.png'
                  };
                  me.settings.icon4Hover = {
                      url: me.settings.imgPath + 'design/frontend/images/picto/' + me.settings.meaFile + me.settings.dsFile + 'marker2Hover.png'
                  };
                  me.settings.icon5 = {
                      url: me.settings.imgPath + 'design/frontend/images/picto/' + me.settings.meaFile + me.settings.dsFile + 'marker3.png'
                  };
                  me.settings.icon5Hover = {
                      url: me.settings.imgPath + 'design/frontend/images/picto/' + me.settings.meaFile + me.settings.dsFile + 'marker3Hover.png'
                  };
                  me.settings.icon6 = {
                      url: me.settings.imgPath + 'design/frontend/images/picto/' + me.settings.meaFile + me.settings.dsFile + 'marker.png'
                  }; 
                  me.settings.icon6Hover = {
                      url: me.settings.imgPath + 'design/frontend/images/picto/' + me.settings.meaFile + me.settings.dsFile + 'markerHover.png'
                  };
                  me.settings.icon7 = {
                      url: me.settings.imgPath + 'design/frontend/images/picto/' + me.settings.meaFile + me.settings.dsFile + 'marker.png'
                  };
                  me.settings.icon7Hover = {
                      url: me.settings.imgPath + 'design/frontend/images/picto/' + me.settings.meaFile + me.settings.dsFile + 'markerHover.png'
                  };				  
				  
              }*/
              me.settings.shadow = {
                url: me.settings.imgPath + 'design/frontend/images/picto/marker-shadow.png',
                anchor: new google.maps.Point(19, 35)
              };
              me.settings.shadowHover = {
                url: me.settings.imgPath + 'design/frontend/images/picto/marker-shadowHover.png',
                anchor: new google.maps.Point(19, 41)
              };

              var zoomopt = {
                minZoom: 5,
                maxZoom: 18
              }; // Limitation du zoom global    5 =  50px - 200km    18 = 50px - 20m
              me.settings.map.setOptions(zoomopt);


              try {
                // Has default open item
                var hash = document.location.hash.substr(1).toLowerCase(),
                  open = me.markers[hash];
                if (open) {
                  me.details(open);
                } else if (hash !== '' && hash.indexOf('lorem') === -1) {
                  me.autoDetails(hash);
                }
              } catch (e) {}

              // Filters
              if (me.settings.config.services.length > 0) {

                var $filterContent = $(me.settings.dom).parents('.locations').prev('.filterLocator'),
                  $filterTpl = $filterContent.find('.filtersTpl'),
                  tpl = $filterTpl.html();
                if (0 != $filterTpl.length) {
                  // V�rification sur la pr�sence de filtres g�n�r�s.
                  if ($filterContent.find('.mapFilters').length > 0) {
                    $filterContent.find('.mapFilters').remove();
                  }


                  var compiledTemplate = _.template(tpl, {
                    services: me.settings.config.services,
                    base: me.settings.base
                  });
                  me.settings.filters = [];
                  $filterContent.append(compiledTemplate).find('.mapFilters input').each(function() {

                    var root = $(this).parents('.mapFilters').get(0);
                    if (!root._inputs) root._inputs = [];
                    this._root = root;
                    root._inputs.push(this);
                    me.settings.filters.push(parseInt(this.value));

                  }).change(function() {

                    var ids = [];
                    $(this._root._inputs).each(function() {
                      if (this.checked) ids.push(parseInt(this.value));
                    });
                    //me.settings.latest.filters = ids;
                    me.settings.filters = ids;
                    // Re do latest search
                    me.filter(me.settings.filters);
					

                  });



                  $filterContent.find('.mapFilters span').click(function() {
                    $(this).parent().toggleClass('open').find('ul').stop(true, false).slideToggle(250);
                  });


                  if(me.settings.filtersStatus=="open"){
                    $(this).parent().toggleClass('open').find('ul').stop(true, false).slideToggle(250);

                  }




                };



              };


              if (me.$element.find('input[name="address"]').length) {
                var input = me.$element.find('input[name="address"]').get(0);
                var dragendTO;
                // Listen for moves on the map
                google.maps.event.addListener(me.settings.map, 'dragend', function(e) {
                  me.dragEndCont = this;
                  clearTimeout(dragendTO);
                  dragendTO = setTimeout(function() {
                    me.clearAllMarkers();
                    me.switchSearchMethod('geoloc');
                    me.geoSearch({
                      location: new google.maps.LatLng(me.dragEndCont.center.lat(), me.dragEndCont.center.lng())
                    }, 'dragend', me.dragEndCont.center.lat(), me.dragEndCont.center.lng());
                  }, 3000);
                });

                // Enable geocoder for custom search when button is clicked
                me.geocoder = new google.maps.Geocoder();
                me.$element.on('submit', function(e) {
                  e.preventDefault();

                  // Maj dataLayer
                  dataLayer[0].internalSearchKeyword = input.value;
                  dataLayer[0].internalSearchType = "pdv";

                  // SEARCH
                  me.geoSearch({
                    'address': (input.value + ', ' + me.settings.config.country)
                  });
                });
              }

              // Callback
              me.settings.onLoad.call(me);

            }
          });

        },

        /**
         * Retrieve the country from a geocoded address
         * @param  {Object} address The geocoded address
         * @return {String}         The found country
         */
        getCountryFromGeoAddress: function(address) {
          for (var i = 0; i < address.length; i++) {
            if (address[i].types[0] === 'country') {
              return address[i].short_name.toLowerCase();
            }
          }
        },

        /**
         * Empty the result list
         */
        emptyResultList: function() {
          var $filledlist = this.$locations.find('.stores');
          this.$locations.find('.items').empty();
          this.updateResultTitle(-1); // lance le mode d'affichage recherche en cours...
          this.clearAllMarkers();
        },

        /**
         * Display an empty list with a title in the result panel
         */
        noResult: function() {
          // Empty result list
          this.emptyResultList();

          // Update the title
          this.updateResultTitle();

          // Open the results sidebar
          $(this.settings.dom).parents('.locations').find('.stores').addClass('filled');
        },

        /**
         * Switch the current search method
         * @param  {String} method Choice between 'geoloc' and 'search'
         */
        switchSearchMethod: function(method) {
          var
            me = this,
            searchButton = me.$element.find('input[type=submit]'),
            geolocButton = me.$element.find('.geoloc');

          switch (method) {
            case 'geoloc':
              geolocButton.addClass('geolocdone');
              searchButton.removeClass('searchdone');
              break;
            default:
              searchButton.addClass('searchdone');
              geolocButton.removeClass('geolocdone');
              break;
          }
        },

        /**
         * Update the title according to the current method
         * @param  {Number}  count         The number of found results
         *   count = -1  (Pour afficher le message de recherceh en cours...)
         */
        updateResultTitle: function(count) {
          if (!count) {
            count = 0;
          }
          var
            me = this,
            $results = this.$locations.find('.stores-results'),
            $holder = $(this.settings.dom).parents('.locations').find('*[data-result]'),
            tpl, isGeolocation = me.$element.find('.geolocdone').length ? true : false;;

          if (!$holder.length) {
            $holder = $results
          }

          if (count > -1) {
            if (!isGeolocation && count > 0) {
              if (me.settings.mea == "true") {
                if ((me.settings.meaPdvCount > 0) && (me.settings.meaDvnCount > 0)) tpl = $holder.attr('data-search-mea-both');
                if ((me.settings.meaPdvCount > 0) && (me.settings.meaDvnCount == 0)) tpl = $holder.attr('data-search-mea-pdv');
                if ((me.settings.meaPdvCount == 0) && (me.settings.meaDvnCount > 0)) tpl = $holder.attr('data-search-mea-dvn');
              } else {
                tpl = $holder.attr('data-search');
              }
            } else if (count > 0) {
              if (me.settings.mea == "true") {
                if ((me.settings.meaPdvCount > 0) && (me.settings.meaDvnCount > 0)) tpl = $holder.attr('data-geo-mea-both');
                if ((me.settings.meaPdvCount > 0) && (me.settings.meaDvnCount == 0)) tpl = $holder.attr('data-geo-mea-pdv');
                if ((me.settings.meaPdvCount == 0) && (me.settings.meaDvnCount > 0)) tpl = $holder.attr('data-geo-mea-dvn');
              } else {
                tpl = $holder.attr('data-geo');
              }

            } else {
              tpl = $holder.attr('data-noresult');
            }

            tpl = tpl.replace('###nb_concession###', me.settings.meaAgentCount);
            tpl = tpl.replace('###nb_agent###', me.settings.meaConcession);
            tpl = tpl.replace('###count###', count);
            tpl = tpl.replace('###countPdv###', me.settings.meaPdvCount);
            tpl = tpl.replace('###countDvn###', me.settings.meaDvnCount);
            tpl = tpl.replace('###address###', '"' + this.$element.find('*[name="address"]').val() + '"');

          } else {
            tpl = $holder.attr('data-searchresult');
          }

          $results.html(tpl);
        },

        /**
         * Make a geocoder search
         * @param  {Object} parameters An object with an 'address' or a 'location' entry
         */
        geoSearch: function(parameters, from, dragLat, dragLong) {
          var me = this;

          if (!parameters) {
            parameters = {};
          }

          me.drag(false);
          if (from == 'dragend') me.drag(true);
          me.geocoder.geocode(parameters, function(results, status) {

            if (status == google.maps.GeocoderStatus.OK && results.length) {

              var result = [];


              for (var i = 0; i < results.length; i++) {
                if ((me.settings.config.country === me.getCountryFromGeoAddress(results[i].address_components)) || (me.settings.config.country === 'ru')) {
                  result.push(results[i]);
                }
              }

              if (result.length) {
                me.emptyResultList();
                me.list(result[0].geometry.location.lat(), result[0].geometry.location.lng());
              } else {
                me.noResult();
              }
            } else {
              // V�rification du p�rim�tre de la Crim�e
              if ((dragLong > 32.50) & (dragLong < 36.71) & (dragLat > 44.20) & (dragLat < 46.12)) {
                me.list(dragLat, dragLong);
              } else me.noResult();
            }
          });
        },

        initGeoPosButton: function() {
          //redmine #3159
          var me = this,
            el = this.element,
            $geolocButton = me.$element.find('.geoloc');

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
                me.emptyResultList();

                if (me.isBusy()) return;
                me.busy(true);
                if (sessionStorage.getItem("position") === null) {

                  geolocTimer = window.setTimeout(function() {
                    geolocBackup();
                  }, 10000);

                  navigator.geolocation.getCurrentPosition(function(pos) {
                    clearTimeout(geolocTimer);
                    $.fancybox.close();
                    me.busy(false);

                    var currentlat = pos.coords.latitude;
                    var currentlng = pos.coords.longitude;
                    me.list(currentlat, currentlng);

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
                  me.list(pos.latitude, pos.longitude);
                }
              };

            $geolocButton.click(checkGeoloc).css({
              cursor: 'pointer'
            });
          } else {
            $geolocButton.css({
              opacity: 0.25
            });
          }

          me.$element.find('.geoloc').on('click', function() {
            me.switchSearchMethod('geoloc');
          });
        },

      
		getCodeFromIndex: function(index) { var code="";
											var getcode = "";
											var me = this;
												for(var ak=0; ak<me.settings.config.services.length; ak++){
													if (me.settings.config.services[ak].index==index) 
													getcode = me.settings.config.services[ak].code;
												}	
												return getcode;
		},

		getLabelFromCodeService: function(code) { 
													var getLabel = "";
													var me = this;
													for(var ak=0; ak<me.settings.config.services.length; ak++){
														if (me.settings.config.services[ak].code==code) 
														getLabel = me.settings.config.services[ak].label;
													}	
													return getLabel;
		},







		  /**
         * Set markers on the map and activate clustering
         */
        setMarkers: function(markers) {


          /* Vars */
          var me = this,
            el = this.element;

          me.settings.markers = [];
          me.markers = [];

          if (me.$locations.css('display') === 'none') {
            me.$locations.show();
          }

          /* Has clusterer */
          if (me.settings.config.clusterer) {
            /* Clear existing markers */
            if (me.settings.clusterer) {
              me.settings.clusterer.clearMarkers();
            } else {
              /* Initialize cluster manager */
              var options = {
                averageCenter: true,
                gridSize: 65,
                styles: [{
                  url: me.settings.imgPath + 'design/frontend/images/picto/cluster.png',
                  height: 49,
                  width: 61
                }]
              };
              me.settings.clusterer = new MarkerClusterer(me.settings.map, me.settings.markers, options);
            };
          };

          if (markers !== null) {

            for (var i = 0, len = markers.length; i < len; i++) {
              var data = markers[i];
				
				// ICI on crée une liste des services que l'on pourra tester
				var chaineServices =",";
				for(var zi=0; zi<data.services.length; zi++){
					//console.log('==> '+data.services[i]+']:' + me.getCodeFromIndex(data.services[i]));
					chaineServices=chaineServices+me.getCodeFromIndex(data.services[zi])+',';
				}

			/* Has clusterer */
              if (me.settings.config.clusterer) {
                /* Create marker */
				data.siteMode = me.settings.siteFile;

                if('pdv' == data.type){
                    if('true' != me.settings.ds){
                        var icon = me.settings.icon;
                    } else {
					

                        if(chaineServices.indexOf(',DS1,') !== -1){
						dataCat = '-ds1'; 
								data.cat = 'DS1';
								data.catName =	me.getLabelFromCodeService('DS1');



                            var icon = me.settings.icon3; 
							var iconRoll = me.settings.icon3Hover; 
                        } else if(chaineServices.indexOf(',DS2,') !== -1){
							dataCat = '-ds2';
								data.cat = 'DS2';
								data.catName =	me.getLabelFromCodeService('DS2');
							

                            var icon = me.settings.icon4; 
							var iconRoll = me.settings.icon4Hover;
                        } else if(chaineServices.indexOf(',DS3,') !== -1){
							dataCat = '-ds3';
								data.cat = 'DS3';
								data.catName =	me.getLabelFromCodeService('DS3');


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
                    } 
					else {
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
				else {
							if('true' != me.settings.ds){
								var  icon = me.settings.icon2;
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
              marker._distance = data.distance;
              marker._type = data.type;
              marker._services = data.services;
              marker._lat = data.lat;
              marker._lng = data.lng;
              google.maps.event.addListener(marker, 'click', function() {
                // bookmark it in the browser history
                window.location.hash = this._storeId;
                // load details
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
            me.list(me.settings.latest.lat, me.settings.latest.lng);
          };

        },

        filter: function(filters) {
					
			/* Vars */
			var me = this,
			el = this.element,
			output = [],
			markers = me.settings.config.markers,
			count = 0;
			
			if (markers!=undefined)  {
		  
				me.emptyResultList();
				var listItems = me.settings.config.markers;
				/*  me.setMarkers(items);
					  me.settings.config.markers = items;
					  me.createList(items);*/
				var newItems = [];
				if (filters) {
					me.settings.meaPdvCount = 0;
					me.settings.meaDvnCount = 0;
					me.settings.meaAgentCount = 0;
					me.settings.meaConcession = 0;
					for (var i = 0, len = markers.length; i < len; i++) {
					  for (var j = filters.length; j--;) {
						if (-1 != markers[i].services.indexOf(filters[j])) {
						  output.push(markers[i]);
						  newItems.push(listItems[i]);

						  if (markers[i].isAgent == true) {
							me.settings.meaConcession++;
						  } else {
							me.settings.meaAgentCount++;
						  }

						  if (me.settings.mea == "true") {
							if (markers[i].type == "pdv") {
							  me.settings.meaPdvCount++;
							} else if (markers[i].type == "dvn") {
							  me.settings.meaDvnCount++;
							}
						  }

						  count++;

						  break;
						};
					  };
					};
				} else {
					output = markers;
					newItems = listItems;

					if (me.settings.mea == "true") {
					  me.settings.meaPdvCount = 0;
					  me.settings.meaDvnCount = 0;
					  me.settings.meaAgentCount = 0;
					  me.settings.meaConcession = 0;

					  for (var i = 0, len = markers.length; i < len; i++) {

						if (markers[i].isAgent == true) {
						  me.settings.meaConcession++;
						} else {
						  me.settings.meaAgentCount++;
						}

						if (markers[i].type == "pdv") {
						  me.settings.meaPdvCount++;
						} else if (markers[i].type == "dvn") {
						  me.settings.meaDvnCount++;
						}
					  }
					}

					count = markers.length;


					}

					me.clearAllMarkers();
					/* Reset markers */
					me.setMarkers(output);
					me.createList(newItems);

					/* Callback */
					me.settings.onFilter.call(me);
					/* ici */
					me.updateResultTitle(count);
					return output;
				}
        },

        highlight: function(marker, markerOnly, instance) {
          var me = this,
            $item = me.$locations.find('.items .item');


          if('true' != me.settings.ds){
              var icon = ('pdv' == marker._type) ? instance.iconHover : instance.icon2Hover;
          } else {
              var icon = ('pdv' == marker._type) ? marker._iconHover : marker._iconHover;
          }


          marker.setIcon(icon);
          marker.setShadow(instance.shadowHover);
          if (marker._storeId && true != markerOnly) {
            $.each($item, function(i, v) {
              if ($(v).data('storeid') === marker._storeId) $(v).addClass('hover');
            })
          }

        },

        downplay: function(marker, markerOnly, instance) {
          var me = this,
            $item = me.$locations.find('.items .item');



          if('true' != me.settings.ds){
              var icon = ('pdv' == marker._type) ? instance.icon : instance.icon2;
          } else {
              var icon = ('pdv' == marker._type) ? marker._icon : marker._icon;
          }

          marker.setIcon(icon);
          marker.setShadow(instance.shadow);
          if (marker._storeId && true != markerOnly) {
            $.each($item, function(i, v) {
              if ($(v).data('storeid') === marker._storeId) $(v).removeClass('hover');
            })
          }

        },

        busy: function(is) {

          /* Vars */
          var me = this;

          /* Set state */
          me.busyState = is;

          /* Launch wait overlay timer if busy */
          if (is) {
            $(me.element).trigger('busy');

            /* Hide wait overlay */
          } else {
            $(me.element).trigger('notbusy');

          }

        },
        isBusy: function() {

          /* Vars */
          var me = this;
          return me.busyState;

        },


        drag: function(is) {

          var me = this;
          me.dragState = is;
        },
        isDrag: function() {

          /* Vars */
          var me = this;
          return me.dragState;

        },



        /* Clear results */
        clear: function() {

          $(this.settings.dom).parents('.locations').find('.stores .items').html('');

        },

        /* Adjust zoom to englobe results */
        fitSearch: function(place) {

          if (!place.geometry) return;

          var me = this;

          var lat = place.geometry.location.lat(),
            lng = place.geometry.location.lng();

          me.list(lat, lng);
        },

        autoDetails: function(id) {
          var me = this;
          me.loadDetails(id, true);
        },

        /* List and display */
        list: function(currentlat, currentlng) {
          // VARS
          var
            me = this,
            el = me.element,
            start = 0,
            $filledlist = me.$locations.find('.stores'),
            $detailStore = me.$locations.find('.store');
			
          //if (me.isBusy()) return; 
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

            // HACK
            //  var items = [{"id":"0000038241","rrdi":"020842E01F","media":null,"name":"CITRO\u00cbN RETAIL PARIS REPUBLIQUE","address":"62 AVENUE DE LA REPUBLIQUE<br \/>75011&nbsp;PARIS","phone":"01 49 29 62 62","distance":1.4,"services":[4,1,2,3,0],"lat":48.8646774,"lng":2.376711,"type":"dvn","isAgent":false},{"id":"0000041370","rrdi":"038901J01F","media":null,"name":"CITRO\u00cbN RETAIL PANTIN ETS JAURES","address":"59 BIS AVENUE JEAN JAURES<br \/>75019&nbsp;PARIS","phone":"01 44 52 79 79","distance":2,"services":[4,2,3,0],"lat":48.88437,"lng":2.376887,"type":"dvn","isAgent":false},{"id":"0000040609","rrdi":"990131Z01F","media":null,"name":"GARAGE DU FAUBOURG","address":"33 RUE DE REUILLY<br \/>75012&nbsp;PARIS","phone":"01 43 72 70 76","distance":3.2,"services":[4,1,2,3],"lat":48.8474,"lng":2.3867,"type":"pdv","isAgent":true},{"id":"0000046339","rrdi":"058870P01F","media":null,"name":"CITRO\u00cbN RETAIL PARIS 8","address":"25 RUE DE CONSTANTINOPLE<br \/>75008&nbsp;PARIS","phone":"01 40 08 60 40","distance":3.2,"services":[4,1,2,3,0],"lat":48.8802643,"lng":2.319153,"type":"dvn","isAgent":false},{"id":"0000040618","rrdi":"990138L01F","media":null,"name":"AUTO SPECIALITES","address":"42 RUE BELGRAND<br \/>75020&nbsp;PARIS","phone":"01 47 97 20 59","distance":3.3,"services":[4,1,2,3],"lat":48.8647,"lng":2.40404,"type":"pdv","isAgent":true},{"id":"0000040632","rrdi":"614429H01F","media":null,"name":"CICOV LEBAR","address":"6 RUE JACQUES CARTIER<br \/>75018&nbsp;PARIS","phone":"01 46 27 98 66","distance":3.5,"services":[4,1,2,3],"lat":48.894,"lng":2.32974,"type":"pdv","isAgent":true},{"id":"0000040617","rrdi":"990096Y01F","media":null,"name":"GARAGE FOUGERE ET CIE","address":"27 RUE ANDRE JOINEAU<br \/>93310&nbsp;LE PRE SAINT GERVAIS","phone":"01 48 45 23 40","distance":3.7,"services":[4,1,2,3],"lat":48.8865,"lng":2.40398,"type":"pdv","isAgent":true},{"id":"0000038179","rrdi":"058835B01F","media":null,"name":"CITRO\u00cbN RETAIL PARIS-EST ETS PARIS NATION","address":"42 COURS DE VINCENNES<br \/>75012&nbsp;PARIS","phone":"01 43 46 27 00","distance":4,"services":[4,2,3,0],"lat":48.84742,"lng":2.402546,"type":"dvn","isAgent":false}];
			//items = [{"id":"0000038241","rrdi":"020842E01F","media":null,"name":"DS DISTRIBUTEUR -CITRO\u00cbN RETAIL PARIS REPUBLIQUE","address":"62 AVENUE DE LA REPUBLIQUE<br \/>75011&nbsp;PARIS","phone":"01 49 29 62 62","distance":2,"services":[4,1,2,8,3,0],"lat":48.8646774,"lng":2.376711,"type":"dvn","isAgent":false},{"id":"0000040609","rrdi":"990131Z01F","media":null,"name":"GARAGE DU FAUBOURG","address":"33 RUE DE REUILLY<br \/>75012&nbsp;PARIS","phone":"01 43 72 70 76","distance":3,"services":[4,1,2,3],"lat":48.8474,"lng":2.3867,"type":"pdv","isAgent":true},{"id":"0000046339","rrdi":"058870P01F","media":null,"name":"CITRO\u00cbN RETAIL PARIS 8","address":"25 RUE DE CONSTANTINOPLE<br \/>75008&nbsp;PARIS","phone":"01 40 08 60 40","distance":3.2,"services":[4,1,2,3,0],"lat":48.8802643,"lng":2.319153,"type":"pdv","isAgent":false},{"id":"0000041370","rrdi":"038901J01F","media":null,"name":"DS Salon - CITRO\u00cbN RETAIL PANTIN ETS JAURES","address":"59 BIS AVENUE JEAN JAURES<br \/>75019&nbsp;PARIS","phone":"01 44 52 79 79","distance":3.3,"services":[4,2,3,0,7],"lat":48.88437,"lng":2.376887,"type":"pdv","isAgent":false},{"id":"0000046330","rrdi":"020910D01F","media":null,"name":"DS WORLD PARIS","address":"33 RUE FRANCOIS 1ER<br \/>75008&nbsp;PARIS","phone":"01 53 57 33 08","distance":3.5,"services":[2,5],"lat":48.8683739,"lng":2.303964,"type":"pdv","isAgent":false},{"id":"0000038619","rrdi":"766108K01F","media":null,"name":"DS STORE - GARAGE CITE LECOURBE","address":"88 RUE LECOURBE<br \/>75015&nbsp;PARIS","phone":"01 47 83 22 18","distance":3.8,"services":[4,1,6,2,3],"lat":48.8432,"lng":2.30466,"type":"pdv","isAgent":true},{"id":"0000040618","rrdi":"990138L01F","media":null,"name":"DS WORLD AUTO SPECIALITES","address":"42 RUE BELGRAND<br \/>75020&nbsp;PARIS","phone":"01 47 97 20 59","distance":4,"services":[4,1,2,3,5],"lat":48.8647,"lng":2.40404,"type":"pdv","isAgent":true}];
			//console.log(items);
			
              // No result, cancel
              if (!items || typeof items !== 'object') {
                return;
              }

			   
              me.settings.config.markers = items;
			  
	            // Trigger Filters cpw-4219
				if (me.settings.config.services.length > 0) {
					var $filterContent = $(me.settings.dom).parents('.locations').prev('.filterLocator');
					if (0 != $filterContent.find('.filtersTpl').length) {
						// il y a des filtres dont il faut utiliser le statut.
						me.filter(me.settings.filters);
					}
				}
				else
				{	
					// J'affiche les résultats sans tenir compte du moindre filtre.
					me.setMarkers(items);
					me.createList(items);
				}
              
              $detailStore.removeClass('open');
            }

          });

        },

        clearAllMarkers: function() {
          var me = this;
          for (var i = 0, len = me.settings.markers.length; i < len; i++) {
            me.settings.markers[i].setMap(null);
          }
        },

        createList: function(items) {
          ///* Vars */
          var me = this,
            el = me.element,
            start = 0;
          /* Vars */
          var tpl = $(me.settings.dom).parents('.locations').find('.stores > script').html(),
            $placeholder = $(me.settings.dom).parents('.locations').find('.stores .items'),
            compiled = '',
            $results = me.$locations.find('.stores-results');

          // Create a table of markers
          me.currentmarkerid = [];
          for (var i = me.settings.markers.length - 1; i >= 0; i--) {
            me.currentmarkerid.push(me.settings.markers[i]);
          }

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
          me.markersevent();

          /* ADDMORE */
          var more = $placeholder.attr('data-more');
          var from = me.settings.config.search.step,
            step = me.settings.config.search.step;

          $placeholder.find('.addmore').remove();

          $placeholder.find('.item:gt(' + (step - 1) + ')').hide();

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
          $placeholder.parents('.stores')
            .one('transitionend', function() {
              google.maps.event.trigger(me.settings.map, 'resize');
            })
            .addClass('filled');

          ;

          if ($('html').hasClass('ie')) {
            google.maps.event.trigger(me.settings.map, 'resize');
          }

          /* The list has been shown, trigger a resize on the map */

          /* Small delay to prevent bounds misplace */
          var fn = function() {
            // Filter the markers
            var
              bounds = new google.maps.LatLngBounds(),
              types = {},
              allTypesCount = 0;

            if (me.settings.config.filter != 'rayon') {
              // Loop the types to locally store which type and how many items we have to keep
              for (var j in me.settings.config.search.types) {
                types[me.settings.config.search.types[j].label] = Number(me.settings.config.search.types[j].count);
                allTypesCount += Number(me.settings.config.search.types[j].count);
              }

              // Loop the markers and keep if has the required criterias
              for (var i = 0; i < me.settings.markers.length; i++) {
                if (types[me.settings.markers[i]._type] > 0) {
                  bounds.extend(me.settings.markers[i].position);
                  types[me.settings.markers[i]._type]--;
                  allTypesCount--;
                }

                if (allTypesCount < 1) {
                  break;
                }
              }
            } else {
              // Si on recherche par rayon, je m'assure que les points du bounds sont compris dans celui-ci !
              for (var i = 0; i < me.settings.markers.length; i++) {
                if (me.settings.markers[i]._distance <= me.settings.config.search.radius) {
                  bounds.extend(me.settings.markers[i].position);
                }
              }
            }


            if (!me.isDrag()) {
              /* � l'initialisation de la carte - On adapte le zoom et on centre la carte sur les markers ! */
              me.settings.map.fitBounds(bounds);
              me.settings.map.setCenter(bounds.getCenter());
            }
            google.maps.event.trigger(me.settings.map, 'resize');

          };

          setTimeout(fn, 200);

          /***** Write number of stores-results ****/
          me.updateResultTitle(items.length);
        },

        markersevent: function() {
          var me = this,
            itemstoreid,
            $item = me.$locations.find('.items .item');

          $item.on('mouseenter', function() {
            itemstoreid = $(this).data('storeid');
            for (var i = 0, len = me.currentmarkerid.length; i < len; i++) {
              if (itemstoreid == me.currentmarkerid[i]._storeId) {
                me.highlight(me.currentmarkerid[i], false, me.settings);
              }
            }
          });

          $item.on('mouseleave', function() {
            itemstoreid = $(this).data('storeid');
            for (var i = 0, len = me.currentmarkerid.length; i < len; i++) {
              if (itemstoreid == me.currentmarkerid[i]._storeId) {
                me.downplay(me.currentmarkerid[i], false, me.settings);
              }
            }
          });

          $item.on('click', function(event) {
            me.getMarkerDetail(this);
          });
        },

        getMarkerDetail: function(el, choiceDone) {
          var
            me = this,
            $target = $(el),
            itemstoreid = ($target.is('a')) ? $target.attr('href').replace('#', '') : $target.data('storeid') || $target.attr('for').replace('dealer', '');

          for (var i = 0, len = me.currentmarkerid.length; i < len; i++) {
            if (itemstoreid == me.currentmarkerid[i]._storeId) {
              // get details
              me.details(me.currentmarkerid[i], choiceDone);
              // bookmark it in the browser history
              window.location.hash = me.currentmarkerid[i]._storeId;
            }
          }
        },

        /* Details */
        details: function(caller, choiceDone) {
          /* Vars */
          var
            me = this,
            el = me.element,
            marker = caller._marker || caller,
            id = marker._storeId,
            rrdi = marker._storeRRDI,
            storeName = marker._storeName,
            lat = marker._lat,
            lng = marker._lng,
            type = marker._type,
            promptTpl = $(me.settings.dom).parents('.locations').find('.prompt').html(),
            bookmarkTpl = $(me.settings.dom).parents('.locations').find('.bookmark').html();

          /* Callback */
          me.settings.onItemClick.call(me, marker._storeId, marker._storeRRDI, lat, lng);

          /* Prompt if has one */
          if (promptTpl !== undefined && promptTpl !== null && choiceDone === undefined) {

            var output = _.template(promptTpl, {
              id: id
            })
            promptPop(output, function() {
              var $root = $(this[0]);
              $root.find('a[id^=show_details]').click(function(e) {
                e.preventDefault();
                $.fancybox.close();
                me.getMarkerDetail(this, true);
              });
            });

            return;
          };

          dataLayer.push({
            'edealerName': marker._storeName,
            'edealerType': marker._type,
            'edealerSiteGeo': marker._storeId,
            'edealerID': marker._storeRRDI,
            'event': 'click'
          });

          /* Center map on marker */
          me.settings.map.setCenter(marker.position);
          me.settings.map.setZoom(15);

          if (me.isBusy()) return;
          me.busy(true);
          me.loadDetails(id);
        },

        loadDetails: function(id, auto) {

          var me = this,
            tpl = $(me.settings.dom).parents('.locations').find('.storeTpl').html(),
            $placeholder = $(me.settings.dom).parents('.locations').find('.store');

          /* If has no store template */
          if ('' == tpl || 0 == $placeholder.length) {
            if (this._item) {
              $(this._item).trigger('click');
            };
            return;
          }

          $.ajax({
            url: me.settings.wsDetails,
            type: 'POST',
            dataType: 'json',
            cache: false,
            data: {
              id: id
            },
            success: function(data) {
              //HACK DETAIL Fiche sur notre VM car wsDetail ne renvois pas de json correct.
              //var data = {"type":"dvn","bAdvisor":true,"nameAdvisor":"\/paris-republique","id":"0000038241","name":"CITRO\u00cbN RETAIL PARIS REPUBLIQUE","address":"62 AVENUE DE LA REPUBLIQUE<br \/>75011&nbsp;PARIS","phone":"01 49 29 62 62","fax":"01 49 29 62 99","web":"http:\/\/www.reseau.citroen.fr\/paris-republique","route":"https:\/\/maps.google.com\/maps?saddr=&daddr=48.8646774,2.376711","services":[4,1,2,3,0],"servicesMob":[{"code":4,"label":"APRES-VENTE"},{"code":1,"label":"PIECES DE RECHANGE"},{"code":2,"label":"VENTES DE VEHICULES NEUFS"},{"code":3,"label":"VENTES DE VEHICULES D'OCCASION"},{"code":0,"label":"Citro\u00ebn Select"}],"serviceList":{"4":{"code":"APV","label":"APRES-VENTE","TYPE_SERVICE":"A","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"61","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/APV.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/APV.png","big":"\/design\/frontend\/images\/picto\/services\/APV_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/APV_big.png","index":4},"1":{"code":"PR","label":"PIECES DE RECHANGE","TYPE_SERVICE":"A","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"62","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/PR.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/PR.png","big":"\/design\/frontend\/images\/picto\/services\/PR_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/PR_big.png","index":1},"2":{"code":"VN","label":"VENTES DE VEHICULES NEUFS","TYPE_SERVICE":"A","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"63","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/VN.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/VN.png","big":"\/design\/frontend\/images\/picto\/services\/VN_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/VN_big.png","index":2},"3":{"code":"VO","label":"VENTES DE VEHICULES D'OCCASION","TYPE_SERVICE":"A","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"64","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/VO.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/VO.png","big":"\/design\/frontend\/images\/picto\/services\/VO_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/VO_big.png","index":3},"0":{"code":"E","label":"Citro\u00ebn Select","TYPE_SERVICE":"L","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"65","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/E.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/E.png","big":"\/design\/frontend\/images\/picto\/services\/E_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/E_big.png","index":0}},"serviceListCode":["APV","PR","VN","VO","E"],"contacts":{"ADM":{"list":[{"name":"M.&nbsp;LUBAT&nbsp;STEPHANE","office":"Responsable de Site","phone":"","fax":"","email":"stephane.lubat@citroen.com"}],"group":""},"APV":{"list":{"1":{"name":"M.&nbsp;BUNODIERE&nbsp;Nicolas","office":"Conseiller Apr\u00e8s-Vente Soci\u00e9t\u00e9s","phone":"01 49 29 62 52","fax":"01 49 29 62 88","email":""},"2":{"name":"M.&nbsp;CHAPUZET&nbsp;Thierry","office":"Responsable APV","phone":"01 49 29 62 91","fax":"","email":""},"4":{"name":"M.&nbsp;FOURNIE&nbsp;Laurent","office":"Conseiller Apr\u00e8s-Vente Soci\u00e9t\u00e9s","phone":"01 49 29 62 84","fax":"01 49 29 62 88","email":""},"6":{"name":"M.&nbsp;MUR\u00c7A&nbsp;Jean","office":"Responsable APV","phone":"01 49 29 62 80","fax":"01 49 29 62 88","email":""},"8":{"name":"M.&nbsp;VILAIN&nbsp;Christian","office":"Conseiller Apr\u00e8s-Vente Soci\u00e9t\u00e9s","phone":"01 49 29 62 85","fax":"01 49 29 62 88","email":""}},"group":"Service APV","timetable":"Lundi:07:30-12:00 13:30-18:30<br \/> Mardi:07:30-12:00 13:30-18:30<br \/> Mercredi:07:30-12:00 13:30-18:30<br \/> Jeudi:07:30-12:00 13:30-18:30<br \/> Vendredi:07:30-12:00 13:30-17:30<br \/> Samedi:Ferm\u00e9<br \/> Dimanche:Ferm\u00e9<br \/>"},"VN":{"list":{"3":{"name":"M.&nbsp;CHARLOUX&nbsp;Pierrick","office":"Vendeur Magasin VN","phone":"01 49 29 62 72","fax":"","email":"A.SCHOEFFTER@republique.citroen.fr"},"5":{"name":"M.&nbsp;GODIN&nbsp;Gilles","office":"Vendeur Magasin VN","phone":"01 49 29 62 69","fax":"","email":""},"7":{"name":"M.&nbsp;STEFANOSKI&nbsp;Dejan","office":"Vendeur Magasin VN","phone":"01 49 29 62 69","fax":"","email":""},"9":{"name":"Mme.&nbsp;BOUBERKA&nbsp;Johana","office":"Vendeur Magasin VN","phone":"01 49 29 62 58","fax":"01 49 29 62 79","email":""},"10":{"name":"Mme.&nbsp;DEBRAY&nbsp;Patricia","office":"Vendeur Magasin VN","phone":"01 49 29 62 62","fax":"","email":""},"11":{"name":"Mme.&nbsp;GUIOT&nbsp;Solange","office":"Vendeur Magasin VN","phone":"01 49 29 62 71","fax":"","email":""}},"group":"Ventes VN","timetable":"Lundi:08:30-19:00<br \/> Mardi:08:30-19:00<br \/> Mercredi:08:30-19:00<br \/> Jeudi:08:30-19:00<br \/> Vendredi:08:30-19:00<br \/> Samedi:09:00-12:30 13:30-19:00<br \/> Dimanche:Ferm\u00e9<br \/>"},"VO":{"timetable":"Lundi:08:30-19:00<br \/> Mardi:08:30-19:00<br \/> Mercredi:08:30-19:00<br \/> Jeudi:08:30-19:00<br \/> Vendredi:08:30-19:00<br \/> Samedi:09:00-12:30 13:30-19:00<br \/> Dimanche:Ferm\u00e9<br \/>"}},"addressDetail":{"City":"PARIS","Country":"","Department":"Paris","Line1":"62 AVENUE DE LA REPUBLIQUE","Line2":"","Line3":"","Region":"Ile-de-France","ZipCode":"75011"},"RRDI":"020842E01F","lat":48.8646774,"lng":2.376711,"bookmarked":false,"bookmark_btn_label":"[cle1: ADD_FAVORITE_BOOKMARKED Lang:FR]","notes_advisor":{"apv":{"note":"2","total":5},"vn":{"note":"4","total":8},"urlHome":"http:\/\/www.citroen-advisor.fr\/paris-republique\/sales\/comments","urlVn":"http:\/\/www.citroen-advisor.fr\/paris-republique\/sales\/comments","urlApv":"http:\/\/www.citroen-advisor.fr\/paris-republique\/atelier\/comments","geo":"0000038241","marketing_name":"CITRO\u00cbN RETAIL PARIS REPUBLIQUE","status":999}};

			var chaineServices =",";
			for(var zi=0; zi<data.services.length; zi++){
				chaineServices=chaineServices+me.getCodeFromIndex(data.services[zi])+',';
			}

			data.siteMode = me.settings.siteFile;
			
			if(chaineServices.indexOf(',DS1,') !== -1){
				dataCat = '-ds1'; 
				data.cat = 'DS1';
				data.catName =	me.getLabelFromCodeService('DS1');
				
			} else if(chaineServices.indexOf(',DS2,') !== -1){
				dataCat = '-ds2';
				data.cat = 'DS2';
				data.catName =	me.getLabelFromCodeService('DS2');
			} else if(chaineServices.indexOf(',DS3,') !== -1){
				dataCat = '-ds3';
				data.cat = 'DS3';
				data.catName =	me.getLabelFromCodeService('DS3');
			} else if(chaineServices.indexOf(',DS4,') !== -1){
				dataCat = '-ds4';
				data.cat = 'DS4';
				data.catName =	me.getLabelFromCodeService('DS4');
			} else if(chaineServices.indexOf(',DS5,') !== -1){
				dataCat = '-ds5';
				data.cat = 'DS5';
				data.catName =	me.getLabelFromCodeService('DS5');
			} else{
				dataCat = '';
				data.cat = '';
				data.catName =	'';
			}

              if (!data.id) return;

              if (auto !== undefined && auto) {
                me.setMarkers([data]);
              }
              me.busy(false);
              name = data.name;
              /* Appends */
              var compiledTemplate = _.template(tpl, {
                data: data,
                services: me.settings.config.services
              });
              $placeholder.html(compiledTemplate).addClass('open');
              setTools();

              /* Events */
              //$placeholder.find('.tabbed').each(tabs.build);
              $placeholder.find('.tabbed').each(function() {
                new Tabbs($(this));
              });


              $placeholder.find('.closer').click(function() {
                me.$locations.find('.stores .item.hover').removeClass('hover');
                $placeholder.removeClass('open');
                // remove the storeid from the url
                window.location.hash = '#_';
              });

              // Masquage des outils appartenant � une typologie qui ne propose aucun des services du pdv
              $placeholder.find('.tools ul>li').each(function() {
                // Si l'outil n'a pas d'attribut data-services, il n'appartient à aucune typologie donc il est toujours affiché
                // var outilServices = $(this).data('services');

                var listservice = $(this).data('services');

                if (listservice) {
                  if (typeof(listservice) == 'number') {
                    var ListServiceOutils = '' + listservice + '';
                    var outilServices = jQuery.makeArray(ListServiceOutils);
                  } else {
                    var outilServices = listservice.split(',');
                  }
                }

                // Parcours des services du pdv (si le pdv propose au moins un service correspondant à l'outil, l'outil reste affiché)
                for (var i in data.serviceList) {
                  if ($.inArray(data.serviceList[i].code, outilServices) != -1) {
                    return;
                  }
                }

                if (listservice == undefined) {
                  return;
                }

                // Suppression de l'outil
                $(this).remove();
              });

              if ($('div.tools ul li').length == 0) {
                $('div.tools').removeAttr('class');
              }
              // Marquage GTM
              try {
                dataLayer.push({
                  edealerName: data.name,
                  edealerType: data.type,
                  edealerSiteGeo: data.id,
                  edealerID: data.RRDI,
                  edealerCity: data.addressDetail.City,
                  edealerAddress: [data.addressDetail.Line1, data.addressDetail.Line2, data.addressDetail.Line3].join(' '),
                  edealerPostalCode: data.addressDetail.ZipCode,
                  edealerRegion: data.addressDetail.Region,
                  edealerCountry: data.addressDetail.Country,
                  event: 'click'
                });
				
				 
              } catch (ex) {}

              if (data.bookmarked == true) {
                // Si concession favorite, le label change
                $placeholder.find('.bookmarks a').text(data.bookmark_btn_label).removeAttr('href');
              } else {
                // Clic sur bouton "Ajouter ? mes favoris"
                $placeholder.find('.bookmarks a').click(function() {
                  // On v?rifie si l'utilisateur a une concession favorite
                  $.ajax({
                    url: '/_/Layout_Citroen_PointsDeVente/ajaxPdvBookmarkGet',
                    dataType: 'json',
                    success: function(data) {
                      // D?finition de la fonction temporaire qui enregistre le point de vente
                      var ajaxCallSavePdvBookmark = function() {
                        $.ajax({
                          url: '/_/Layout_Citroen_PointsDeVente/ajaxPdvBookmarkSet',
                          dataType: 'json',
                          cache: false,
                          data: {
                            pdvId: id
                          },
                          success: function(data2) {

                            $.fancybox.close();
                            if (typeof data2.bookmark_btn_label) {
                              $placeholder.find('.bookmarks a').html(data2.bookmark_btn_label).removeAttr('href').unbind('click');
                            }
                          }
                        });
                      }

                      // Si l'utilisateur est connect? et a d?j? une concession favorite
                      // ou utilisateur non connect? & pdv d?fini dans un cookie
                      // => on affiche la popin "Vous avez d?j? une concession favorite"
                      if ((data.loggedin == true && data.favoris_db.favoris_vn != null && typeof data.favoris_db.favoris_vn != 'undefined') || (data.loggedin == false && data.favoris_cookie.favoris_vn != null && typeof data.favoris_cookie.favoris_vn != 'undefined')) {

                        var output = _.template(bookmarkTpl, {
                          id: id,
                          name: data.favoris_vn_name
                        });
                        promptPop(output);

                        // Popin : clic sur bouton confirmer
                        $('.fancybox-inner .actions .green>a').click(function(e) {
                          ajaxCallSavePdvBookmark(); // Enregistrement du favori
                        });

                        // Popin : clic sur bouton annuler
                        $('.fancybox-inner .actions .grey>a').click(function(e) {
                          $.fancybox.close();
                        });

                        return;
                      }
                      ajaxCallSavePdvBookmark(); // Enregistrement du favori
                    }
                  });
                });
              }
			$('.folder a[href^="#deployable_"]').on('click', function(e) {
				
				
			
				var href = $(this).attr('href');
				var aHref  = href.split('_');
				var idform = aHref[1];
				
				
				var formContext = $($(this).attr('href')).find('input[name=FORM_CONTEXT_CODE]').val();
				 var parcours = $('#parcours').val();
				 
				 switch(formContext){
					case "CAR":
						context =  "context-car";
					break;
					case "RTO":
						context =  "context-dealer";
					break;
					default:
						context =  "context-none";
					break;
				 }
				 
				 var gtm_form_data = $(this).attr('gtm-form-data');
				  var data_gtm = $(this).attr('data-gtm');
				 if(gtm_form_data){
					gtm_form_data = $.parseJSON(gtm_form_data);
				 }
			
				 var aGtm = data_gtm.split('|');
				 
				 
				 dataLayer.push({
     
                  event: 'uaevent',
				  eventCategory : aGtm[1],
				  eventAction: 'Redirection',
				  eventLabel :aGtm[3]+' - position '+aGtm[5]
                });
				 
				
				 if($('#nextstepformdeploy'+idform+'').length > 0){
						dataLayer.push({ 
										event: 'updatevirtualpath', 
										pageName: 'Intro/Request_' + gtm_form_data.form_gtm.FORM_TYPE_GTM_ID,
										virtualPageURL: '/Request_' + gtm_form_data.form_gtm.FORM_TYPE_GTM_ID + '/Intro',
										pageVariant:context
										
								});
				}
				
				 
				 	// $('#nextstepformdeploy').on('click', function(e) {  
						// var parcours = $('#parcours').val();
						// if( parcours != undefined){
									// if (gtm_form_data) {
										// dataLayer.push({ 
												// event: 'updateformsV2',
												// pageName: 'Intro/Request_' + gtm_form_data.form_gtm.FORM_TYPE_GTM_ID,
												// virtualPageURL: '/Request_' + gtm_form_data.form_gtm.FORM_TYPE_GTM_ID + '/Intro',
												// pageVariant:context+'/'+parcours
										// });
									// }
									
						// }
					// });
			});


              //gtmFormPush();
              /* Callback */
              me.settings.onDetails.call(me);


			  if (me.settings.ds == "false") 
				{
					if (data.notes_advisor && (data.notes_advisor!=="")) me.loadAdvisor(data.notes_advisor);
				}		
            }
          });

        },
        loadAdvisor: function(dataAdvisor) {
			
          var me = this;
		  
		  	  
		  
		  
          //if (me.settings.advisor == "on") {
			if (true) {
            var tpl = $(me.settings.dom).parents('.locations').find('.advisorTpl').html();


            /* If has no store template */
            if ('' == tpl) {
              if (this._item) {
                $(this._item).trigger('click');
              };
              return;
            }
           
                   	var data = dataAdvisor;
                    
                  if (data.status == 3) return;

                  var compiledTemplate = _.template(tpl, {
                    data: data,
                    services: me.settings.config.services
                  });


                  $placeholder = $(me.settings.dom).parents('.locations').find('.advisor');
                  $placeholder.html(compiledTemplate);

                  putStarAdvisor('vnstar', data.vn.note);

                  putStarAdvisor('apvstar', data.apv.note);

                  if ((data.apv.note.total + data.vn.note.total) == 0) {
                    $('#advisorvoirtous').hide();
                  } else {
                    $('#advisorlaisseravis').hide();
                  }






                }
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


        function putStarAdvisor(classid, note) {
          if (note > 4.75) {
            $('#' + classid + '1').addClass('etoiles_1_rouge_module');
            $('#' + classid + '2').addClass('etoiles_1_rouge_module');
            $('#' + classid + '3').addClass('etoiles_1_rouge_module');
            $('#' + classid + '4').addClass('etoiles_1_rouge_module');
            $('#' + classid + '5').addClass('etoiles_1_rouge_module');
          }
          if ((note > 4.25) && (note <= 4.75)) {
            $('#' + classid + '1').addClass('etoiles_1_rouge_module');
            $('#' + classid + '2').addClass('etoiles_1_rouge_module');
            $('#' + classid + '3').addClass('etoiles_1_rouge_module');
            $('#' + classid + '4').addClass('etoiles_1_rouge_module');
            $('#' + classid + '5').addClass('etoiles_1_middle_module');
          }

          if ((note > 3.75) && (note <= 4.25)) {
            $('#' + classid + '1').addClass('etoiles_1_rouge_module');
            $('#' + classid + '2').addClass('etoiles_1_rouge_module');
            $('#' + classid + '3').addClass('etoiles_1_rouge_module');
            $('#' + classid + '4').addClass('etoiles_1_rouge_module');
          }

          if ((note > 3.25) && (note <= 3.75)) {
            $('#' + classid + '1').addClass('etoiles_1_rouge_module');
            $('#' + classid + '2').addClass('etoiles_1_rouge_module');
            $('#' + classid + '3').addClass('etoiles_1_rouge_module');
            $('#' + classid + '4').addClass('etoiles_1_middle_module');
          }
          if ((note > 2.75) && (note <= 3.25)) {
            $('#' + classid + '1').addClass('etoiles_1_rouge_module');
            $('#' + classid + '2').addClass('etoiles_1_rouge_module');
            $('#' + classid + '3').addClass('etoiles_1_rouge_module');
          }
          if ((note > 2.25) && (note <= 2.75)) {
            $('#' + classid + '1').addClass('etoiles_1_rouge_module');
            $('#' + classid + '2').addClass('etoiles_1_rouge_module');
            $('#' + classid + '3').addClass('etoiles_1_middle_module');
          }

          if ((note > 1.75) && (note <= 2.25)) {
            $('#' + classid + '1').addClass('etoiles_1_rouge_module');
            $('#' + classid + '2').addClass('etoiles_1_rouge_module');
          }

          if ((note > 1.25) && (note <= 1.75)) {
            $('#' + classid + '1').addClass('etoiles_1_rouge_module');
            $('#' + classid + '2').addClass('etoiles_1_middle_module');
          }
          if ((note > 1) && (note < 1.24)) {
            $('#' + classid + '1').addClass('etoiles_1_rouge_module');
          }

        }



        //charger les donn?es du formulaire depuis l'onglet pour faire le dataLayer.push au clic
        function gtmFormPush() {
          $('.folder a[href^="#deployable_"]').on('click', function(e) {
            e.preventDefault();
            var gtm_form_data = $(this).attr('gtm-form-data');
            if (gtm_form_data) {
              gtm_form_data = $.parseJSON(gtm_form_data);
              dataLayer.push({
                event: 'updateformsV2',
                pageName: 'Intro/Request_' + gtm_form_data.form_gtm.FORM_TYPE_GTM_ID,
                virtualPageURL: '/Request_' + gtm_form_data.form_gtm.FORM_TYPE_GTM_ID + '/Intro'
              });
            }



          });
        }

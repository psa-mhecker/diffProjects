
var indiceConfigurateur;
var selectionConfigurateur;
var loader = new Loader();
var lastRelease = '20141203'; //claire
var IframeId;

/*
 * Init carte Point de vente 
 * 
 */

//var googlemapAPI = 'https://maps.googleapis.com/maps/api/js?client=&sensor=true&libraries=places';
 
;(function($,win,doc,Cit){

	var $doc = $(document);
	Cit.$globalLocators;

	Cit.SetPDV = function ($root){ this.init($root); };
	Cit.SetPDV.prototype = {
		init:function($root){
			this.$root = $root;
			this.$mapLayer = $root.next('.locations'); 
			this.loader = new Loader($root);
			this.loaderMap = new Loader($root.parent().find('.locations'));
			this.pos = null;

			this.setHandlers();
			this.setLocator();
		 },
		setHandlers:function(){
			var oThis = this;
			this.$root.on('busy',function(){
				oThis.loader.show();
			});
			this.$root.on('notbusy',function(){
				oThis.loader.hide();
			});
		},
		showVnResult:function(xhr){
			if(xhr.responseJSON !== undefined){
				
				var $content = $('#'+xhr.responseJSON[0].id);
				
				$('html, body').animate({
					scrollTop:$content.offset().top - 100
				}, 'slow');				
			}
			this.loaderMap.hide();
			this.loader.hide();
		},
		setLocator:function(){
			var oThis = this;
			this.$root.gLocator({
				latest:{
					lat:(oThis.pos) ? oThis.pos.latitude : null,
					lng:(oThis.pos) ? oThis.pos.longitude : null,
					zoom:12,
					filters:null,
					type:'geo'
				},
				onLoad:function(){
					var that = this;

					if(oThis.$root.hasClass('locatorPDV')){					
						oThis.$mapLayer.show();
					}
					//oThis.$root.addClass('initDone');
					/* if has bookmark button */
					$(that.element).find('.bookmarks a').click(function(e){
						e.preventDefault();
						that._booked = this;
						
						// RÃƒÂ©cupÃƒÂ©ration des points de vente favoris
						$.ajax({
							url: '/_/Layout_Citroen_PointsDeVente/ajaxPdvBookmarkGet',
							dataType: 'json',
							success: function(data){
								var source = null;
								
								// User connectÃƒÂ© => on utilise les favoris de son compte
								if( data.loggedin == true  && data.favoris_db.favoris_vn != null && typeof data.favoris_db.favoris_vn != 'undefined' ){
									source = data.favoris_db;
								}
								
								// User non connectÃƒÂ© => on utilise les favoris dÃƒÂ©fini en cookie
								else if( data.loggedin == false && data.favoris_cookie.favoris_vn != null && typeof data.favoris_cookie.favoris_vn != 'undefined' ){
									source = data.favoris_cookie;
								}
								
								// Parcours
								var pdv_favori = [];
								for(var i in source){
									if( source[i] != '' && source[i] != NaN && source[i] != undefined && source[i] != null ){
										pdv_favori.push(source[i]);
									}
								}
								that.list(pdv_favori);
							}
						});
					});

				},
				onFilter:function(){},
				onList:function(){

					/* If has custom origin */
					if(this._booked){
						this.$locations.find('.stores-results').html(t('VOS_FAVORIS'));
					}
					this._booked = null;

				},
				onItemClick:function(storeId, storeRRDI, lat, lng){
					if(oThis.$root.hasClass('locatorVN')){
						try{
							$(document).on('ajaxComplete ',function(event, xhr, settings){ 
								oThis.showVnResult(xhr);
								$(document).off('ajaxComplete');
							});
							oThis.loader.show();
							oThis.loaderMap.show();
							getCarStock(storeId, storeRRDI, lat, lng);
						} catch(e){ }
					}
				},
				onDetails:function(){ 
					$(this.settings.dom).parent().find('.store .folder').each(folder.build);
				},
				onGeoloc:function(){},
				onGeolocError:function(){
					/* Display prompt, should use an HTML template */
					if(promptPop){
						promptPop(t('GEOLOCALISATION_IMPOSSIBLE_VEUILLEZ_VERIFIER_QUE_VOTRE_NAVIGATEUR_ACCEPTE_CETTE_FONCTIONNALITE_ET_SI_ELLE_EST_ACTIVEE'));
					}

				}
			});
		}
	}

	Cit.initLocator = function($target){
	
		var $locators;
		if($target !== undefined) {
			$locators = $target;	
		} else {
			$locators = $('.locatorPDV, .locatorVN, .locatorMesCS');
		}

		Cit.$globalLocators = $locators;
 
		if($locators !== undefined){
			// Charge l'API que si il y a un contenu de carte dans le DOM qui n'est (ni en classe secret / locator).
			if ($locators.length>0)
			{
				try
				{
					if (google) { Cit.initLocatorPDV(); }
				}
				catch(e)
				{	
						// injecte le script que si celui-ci n'est dÃƒÂ©jÃƒ  pas chargÃƒÂ©.
						var script = document.createElement('script');
						  script.type = 'text/javascript';
						  script.src = googlemapAPI + '&callback=Cit.initLocatorPDV';
						  document.body.appendChild(script);
				}
			}
		}
	}
 
	Cit.initLocatorPDV = function() {
		if(Cit.$globalLocators!== undefined){
			Cit.$globalLocators.each(
				function(){ 
							new Cit.SetPDV($(this)); 
				}
			); 
		}
	}

	Cit._checkLocatorMap = function($content){
		Cit.initLocator($content.find('.locator'));
	}



	/* Google map dealers details*/
	if(typeof $('#map_dealers_details').gLocator === 'function'){
		$('#map_dealers_details').gLocator({
			onLoad:function(instance){},
			onFilter:function(){},
			onList:function(){},
			onItemClick:function(storeId){},
			onDetails:function(){}
		});
	}
        
})( jQuery, window, document, window.Cit = window.Cit || {} );

 
/**
 * PATCH PELICAN AJAX
 */
Pelican.ajax.prototype.call = function() {
    // PATCH START
    var self = this,
        elem = $('#caracteristiques-equipements'),
		obj ={ '>Standard<': '>'+elem.data('localise-standard')+'<', '>Option<' : '>'+elem.data('localise-option')+'<', '>Non disponible<': '>' + elem.data('localise-nondispo') + '<' };
    // PATCH END
    $.ajax({
        data: this.getData(this.data) ? this.getData(this.data) : "&values[]=null",
        url: "/library/Pelican/Ajax/Adapter/Jquery/public/?route=" + this.url,
        type: this.type ? this.type : "GET",
        dataType: this.dataType ? this.dataType : 'json',
        timeout: this.timeout ? this.timeout : 60000,
        error: function(xhr, ajaxOptions, thrownError) {
            if (this.debug == true) {
                alert('Error processing request: '
                        + '/library/Pelican/Ajax/Adapter/Jquery/public/?route=' + func,
                        10000);
                alert(xhr.responseText);
            }
            self.error(xhr, ajaxOptions, thrownError);
        },
        success: function(data, textStatus, jqXHR) {
            self.beforeAction(data, textStatus, jqXHR);
            if (data) {
                $.each(data, function() {
                    if(typeof this.id != "undefined" && this.id === 'caracteristiques-equipements') {
                        var str = this.value;
                        if( obj[x] !== "" )
                            for (var x in obj) { str = str.replace(new RegExp(x, 'g'), obj[x])}
                    }else{
						var str = this.value;
					}
                    switch (this.cmd) {
                        case 'assign':
                            {
                                if (this.attr.toLowerCase() == 'innerhtml') {
                                    $('#' + this.id).html(str);
                                } else {
                                    $('#' + this.id).attr(this.attr, this.value);
                                }
                                break;
                            }
                        case 'append':
                            {
                                $('#' + this.id).append(this.value);
                                break;
                            }
                        case 'prepend':
                            {
                                $('#' + this.id).prepend(this.value);
                                break;
                            }
                        case 'replace':
                            {
                                var ori = $('#' + this.id).attr(this.attr);
                                $('#' + this.id).attr(this.attr,
                                        ori.replace(this.search, this.value));
                                break;
                            }
                        case 'clear':
                            {
                                $('#' + this.id).removeAttr(this.attr);
                                break;
                            }
                        case 'remove':
                            {
                                $('#' + this.id).remove();
                                break;
                            }
                        case 'redirect':
                            {
                                document.location.href = this.url;
                                /*
                                 * this.'delay';
                                 */
                                break;
                            }
                        case 'reload':
                            {
                                document.location.reload();
                                break;
                            }
                        case 'alert':
                            {
                                alert(this.value);
                                break;
                            }
                        case 'script':
                            {
                                delegate(this.value);
                                break;
                            }
                    }
                });
            }
            self.afterAction(data, textStatus, jqXHR);
            self.success(data, textStatus, jqXHR);

   			gtmCit.initNewGTM();
        }
         
    });
};

var loadGoogle_API = false;


$(document).ready(function() 
{
	// Chargement Points de vente.
	var  checklocator= $('.locatorPDV, .locatorVN, .locatorMesCS');
	if (checklocator !== undefined) {  if (checklocator.length>0)  loadGoogle_API = true; }

	// Chargement si StockBE
	if ($("form[name=newCarBelgium]").find('input[name="address"]').length ){ loadGoogle_API = true; };
	
	
	if (loadGoogle_API)
	{
		if(typeof google === 'undefined'){
			var script = document.createElement( 'script' );
			script.type = 'text/javascript';
			script.src = googlemapAPI+"&callback=DomReadyLoad";
			document.body.appendChild(script);	
		}
	}		
	else	
	{
		DomReadyLoad();
	}
        
        if($('#iframeContainer').attr('data-iframe') != ""){
            var loaderIframe = new Loader('.iframeClear');
            //on masque les iframes
            $('#iframeContainer').hide('0', function(){	
                    loaderIframe.show(LoadingKey, false);
            });

            //on affiche l'iframe avec filtre sur class et id
            $('#iframeContainer').load(function() {
                    var hideData = '';
                    if($('#iframeContainer').attr('data-iframe')){
                            var hideData = $('#iframeContainer').attr('data-iframe');			 
                    }
                    $('#iframeContainer').contents().find(hideData).hide("0", function(){
                            $('#iframeContainer').show();
                            loaderIframe.hide();
                    });

            });
	}
});



function DomReadyLoad()
{
    // Affichage de la bulle de scroll (si elle est présente dans le DOM)
    scrollIncite();
    
    //ActualitÃƒÂ©
    seeMoreNews();
    filterNews();
    //Car selector
    addToCompare();
    maskBtnComparateur();
    //Vehicules neufs
    seeMoreCars();
	
	if($.fn.gLocator){ 	Cit.initLocator(); }

  	getCarStockBE();
    //RÃƒÂ©sultats de recherche
    autoCompleteSearch();
    seeMoreResults();
    //Accessoires
    seeMoreAccessories();
    //Home
    launchInstagram();
    //Comparateur
    reinitComparateur();
    replaceToCompare();
    onMyProjectPage();
    //Iframe
    loadIframe();
    gtmFormPush();    
    // Gestion des onglets    
    $('.data-onglet').each(function() {
        onglet = $(this).data('onglet');
        valeur = $(this).children().detach();
        valeur.appendTo('.onglet-' + onglet);
        $(this).remove();
    });
    // Gestion des Accordeon Web et Mobile
    $('.tog').each(function() {
        valeur = $(this).children().detach();
        toggle = $(this).data('group');
        if (toggle) {
            $('#toggle' + toggle).append(valeur);
        }
        $(this).remove();
    });
    //Languette pro - client
    if ($('.languettePro').length > 0) {
        var languettePro = $('.languettePro').children().detach();
        languettePro.prependTo('.body');
        $('.stickyplaceholder,.sticker,.listickholder,.stripholder').each(sticky.build);
        $('.languettePro').remove();
    }
    if ($('.languetteClient').length > 0) {
        var languetteClient = $('.languetteClient').children().detach();
        languetteClient.prependTo('.body');
        $('.stickyplaceholder,.sticker,.listickholder,.stripholder').each(sticky.build);
        $('.languetteClient').remove();
    }
    languettePerso();
    // Gestion des tranches Parentes/Enfants
    // on rÃƒÂ©cupÃƒÂ©re toutes les tranches actives c'est Ãƒ  dire toutes les tranches parentes
    $('.parentActif').each(function() {
        var idParent = this.id;
        var idTrancheParent = '#' + this.id;
        var idEnfant = idParent.split('_');
        classeTrancheEnfant = '.trancheEnfant' + idEnfant[1];
        // on rÃƒÂ©cupÃƒÂ©re toutes les tranches enfants associÃƒÂ©es Ãƒ  une tranche parente
        $(classeTrancheEnfant).each(function() {
            var idTrancheEnfant = '#' + this.id;
            valeur = $(idTrancheEnfant).children().detach();
            valeur.appendTo(idTrancheParent);
            $(this).remove();
            //On remplit la div parent avec tous ses enfants
            //$(idTrancheParent).html($(idTrancheParent).html() + $(idTrancheEnfant).html());
        });
    });
    $('footer .site-version').bind('click', function(e) {
        e.preventDefault();
        _version = $(this).data('version');
        if (_version == 'mobile') {
            callAjax({
                url: "Layout_Citroen_Global_Footer/versionMobile"
            });

        } else {
            callAjax({
                url: "Layout_Citroen_Global_Footer/versionDesktop"
            });
        }
    });
    //Formulaires
    initFormulaire();
    
    // include fichier demo.js
    
    /* Sortable */
	if($.fn.sortable){
		$('.listeVehicules').sortable({
			items:'.selectedCar',
			toleranceType:'pointer',
			revert:true,
			update:function(e,ui){
				var $root = ui.item.parents('.listeVehicules'),
					$field = $root.prev(),
					values = [];

				$root.find('input.vid').each(function(){
					values.push(this.value);
				});

				//$field.val(JSON.stringify(values)).trigger('change');

				/* Update wordings */
                                var original = null;
                                var target = null;
                                var selection =new Array();
				$(this).find('.selectedCar').each(function(i,item){

                    item = $(item);
                    selection.push($(item).find('input.vid').val());

					var index = i,
						$label = $(this).find('.sortLabel'),
						current = $label.html(),
						rplc = current.replace(/^(.*)([0-9]{2})(.*)$/gi,'$10'+index+'$3');

					$label.html(rplc);
				});



                callAjax({
                    url:'Layout_Citroen_MonProjet_SelectionVehicules/changeOrderAjax',
                    data:{
                        items:selection,
                    },
                    success: function(){
                        location.reload();
                    }
                });

			}
		});
	};
        
        var search = $('input[name="search"]'),
		placeholder = search.val();


	search.focus(function(){
		search.removeClass('placeholder');
	});
	search.blur(function(){
		if(search.val() !== placeholder){
			search.removeClass('placeholder');
		} else {
			search.addClass('placeholder');
		}
	});

	search.on('typeahead:initialized',function(){
		search.addClass('placeholder');
	});
        
        /* Add to selection effect */
	$('.add2selection').click(function(){

		if(0 < $('.projectAdd').length) return;

		var $o = $(this),
			$d = $('header .projects');

		/* Get buttons positions */
		var origin = $o.offset(),
			dest = $d.offset(),
			time = (origin.top - dest.top)*1.5;

		/* Update to center */
		origin.left += $o.width()/2;
		dest.top += $d.height()/2;
		dest.left += $d.width()/2;

		/* Append */
		$('body').append('<div class="projectAdd"></div>').find('.projectAdd').fadeIn(125,function(){
			var $t = $(this);
			$t.css(origin).animate(dest,time,'linear',function(){
				$t.fadeOut(125,function(){
					$t.remove();
				});
			});
		});

	});dropdownstack
}

/**
 * ACTUALITES
 **/
//MÃƒÂ©thode servant au binding le clic sur le bouton permettant d'afficher plus de news
function seeMoreNews() {
    $("#seeMoreNews a").bind('click', function(e) {
        e.preventDefault();
        displayMoreNews('more');
    });
}

function displayMoreNews(typeAff) {
    var iMin = parseInt($('#iCount').val());
    var iPid = parseInt($('#pid').val());
    callAjax({
        url: "Layout_Citroen_Actualites_Galerie/moreNews",
        async: false,
        data: {
            'iMin': iMin,
            'typeAff': typeAff,
            'iPid': iPid
        },
        success: function(e) {
            ReinitializeAddThis();

            lazy.set($('#allActu img.lazy'));
            gtmCit.initNewGTM();
        }
    });
}

//MÃƒÂ©thode permettant de submitter le formulaire de filtre au changement d'un des filtres
function filterNews() {
    $('input[name="themeId"]').click(function() {
        $('#allActu').html('<div class="row of2 item zoner"></div>');
        $('#seeMoreNews').hide();
        var loader = new Loader($('#allActu'));
        loader.show(LoadingKey, false);
        var sFormName = $(this).parents("form").attr('id');
        var pid = $('input[name="pid"]').val();
        var iTheme = $(this).val();
        if (iTheme == '0') {
            /*var urlPage = document.URL;
             var urlNoParams = urlPage.split('?');
             window.location.href = urlNoParams[0];*/
            callAjax({
                url: "Layout_Citroen_Actualites_Galerie/filterNews",
                async: false,
                data: {
                    'iPid': pid,
                    'iTheme': iTheme,
                    'iMin': 1
                }
            });
        } else {
            //$('#'+sFormName).submit();

            callAjax({
                url: "Layout_Citroen_Actualites_Galerie/filterNews",
                async: false,
                data: {
                    'iPid': pid,
                    'iTheme': iTheme,
                    'iMin': 1
                }
            });
        }
        lazy.set($('#allActu img.lazy'));
        gtmCit.initNewGTM();
    });
}
/**
 * VEHICULES NEUFS
 **/
//MÃƒÂ©thode servant au binding le clic sur le bouton permettant d'afficher plus de vehicules neufs
function seeMoreCars() {
    $("#seeMoreCars a").on('click', function(e) {
        e.preventDefault();
        displayMoreCars('more');
    });
}
//MÃƒÂ©thode affichant les vehicules supplÃƒÂ©mentaires via un appel ajax, on rÃƒÂ©cupÃƒÂ¨re un compteur dans un champs cachÃƒÂ© sur la page
function displayMoreCars(typeAff) {
    var iMin = parseInt($('#iCount').val());
    var iZid = parseInt($('#zidVN').val());
    var iZorder = parseInt($('#zorderVN').val());
    var iAreaId = parseInt($('#zareaVN').val());
    var zType = String($('#zType').val());
    var lng = ($('#lng').length > 0) ? $('#lng').val() : 0;
    var lat = ($('#lng').length > 0) ? $('#lat').val() : 0;
    var storeId = ($('#storeId').length > 0) ? $('#storeId').val() : 0;
    var storeRRDI = ($('#storeRRDI').length > 0) ? $('#storeRRDI').val() : '';

    callAjax({
        url: "Layout_Citroen_VehiculesNeufs/moreCars",
        async: true,
        data: {
            'iMin': iMin,
            'typeAff': typeAff,
            'iZid': iZid,
            'iZorder': iZorder,
            'iAreaId': iAreaId,
            'zType': zType,
            'iLng': lng,
            'iLat': lat,
            'storeId': storeId,
            'storeRRDI': storeRRDI


        },
        success: function(){
			loader.hide();
			gtmCit.initNewGTM();
		}
    });
    
}
//MÃƒÂ©thode affichant les vehicules liÃƒÂ© Ãƒ  un point de vente (FRANCE)
function getCarStock(id, rrdi, lat, lng) {
    var iZid = parseInt($('#zidVN').val());
    var iZorder = parseInt($('#zorderVN').val());
    var iAreaId = parseInt($('#zareaVN').val());
    var sLcdv = $('#lcdvVN').val();
    var sSkin = $('#ZONE_SKIN').val();

    $('#storeId').val(id);
    $('#storeRRDI').val(rrdi);
    $('#lat').val(lat);
    $('#lng').val(lng);
    var loader = new Loader($('#resultVN')); // Instantiation de lÃ¢â‚¬â„¢objet
    loader.show(LoadingKey, false);
    callAjax({
        url: "Layout_Citroen_VehiculesNeufs/france",
        async: true,
        data: {
            'storeId': id,
            'storeRRDI': rrdi,
            'iZid': iZid,
            'iZorder': iZorder,
            'iAreaId': iAreaId,
            'lcdv': sLcdv,
            'lat': Math.round(lat * 100) / 100,
            'long': Math.round(lng * 100) / 100,
            'ZONE_SKIN': sSkin

        },
		success: function(){
			loader.hide();
			gtmCit.initNewGTM();
		}
    });
}
//MÃƒÂ©thode affichant les vehicules liÃƒÂ© Ãƒ  une ville/dÃƒÂ©partement (BELGIQUE)
function getCarStockBE() {
	//console.log('getCarStockBe');
    var iZid = parseInt($('#zidVN').val());
    var iZorder = parseInt($('#zorderVN').val());
    var iAreaId = parseInt($('#zareaVN').val());
    var sCountryCode = $('#countryCode').val();
    var iMaxDistance = $('#maxDistance').val();
    var sLcdv = $('#lcdvVN').val();
		var sSkin = $('#ZONE_SKIN').val();
    var input = $("form[name=newCarBelgium]").find('input[name="address"]').get(0);
    var loader = new Loader($('#resultVN')); // Instantiation de lÃ¢â‚¬â„¢objet
		if ($("form[name=newCarBelgium]").find('input[name="address"]').length ){
	    var autocomplete = new google.maps.places.Autocomplete(input, {
	        componentRestrictions: {
	            country: sCountryCode
	        }
	    });
	    google.maps.event.addListener(autocomplete, 'place_changed', function() {
	
	        var place = autocomplete.getPlace();
	        //me.fitSearch(place);
			
	    });
  	}
    $("form[name=newCarBelgium]").submit(function(e) {
        e.preventDefault();
        loader.show(LoadingKey, false);
        $("form[name=newCarBelgium]").trigger('busy');
        var geocoder = new google.maps.Geocoder();
        var string = $(this).find("input[name=address]").val();
		var sSkin = $('#ZONE_SKIN').val();
        geocoder.geocode({'address': string}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                var location = results[0].geometry.location
                var lat = location.lat();
                var lng = location.lng();
                $('#lat').val(lat);
                $('#lng').val(lng);
                callAjax({
                    url: "Layout_Citroen_VehiculesNeufs/belgique",
                    async: false,
                    data: {
                        'lat': Math.round(lat * 100) / 100,
                        'long': Math.round(lng * 100) / 100,
                        'iZid': iZid,
                        'iZorder': iZorder,
                        'iAreaId': iAreaId,
                        'lcdv': sLcdv,
						'ZONE_SKIN': sSkin,
						'iMaxDistance': iMaxDistance						
                    },
                    success: function(){
	                    loader.hide();
                    	gtmCit.initNewGTM();
                    }
                });
            }
            ;
        });
    });
    /* Enable geolocation if enabled */
    if (navigator.geolocation) {

        /* Backup timer because browser doesen't trigger error when prompt is simply closed */
        var geolocTimerVn = 0,
                geolocBackupVn = function() {

                    clearTimeout(geolocTimerVn);
                    $("form[name=newCarBelgium]").trigger('notbusy');
                    if (promptPop) {
                        promptPop(t('GEOLOCALISATION_IMPOSSIBLE_VEUILLEZ_VERIFIER_QUE_VOTRE_NAVIGATEUR_ACCEPTE_CETTE_FONCTIONNALITE_ET_SI_ELLE_EST_ACTIVEE'));
                    }
                };
        $("form[name=newCarBelgium]").find('.geoloc').unbind('click');
        $("form[name=newCarBelgium]").find('.geoloc').click(function(e) {
            $("form[name=newCarBelgium]").trigger('busy');

            geolocTimerVn = window.setTimeout(function() {
                geolocBackupVn();
            }, 10000);

            navigator.geolocation.getCurrentPosition(function(pos) {
                loader.show(LoadingKey, false);
                $("form[name=newCarBelgium]").trigger('busy');
                clearTimeout(geolocTimerVn);
                $('#lat').val(pos.coords.latitude);
                $('#lng').val(pos.coords.longitude);
				var sSkin = $('#ZONE_SKIN').val();
                callAjax({
                    url: "Layout_Citroen_VehiculesNeufs/belgique",
                    async: false,
                    data: {
                        'lat': Math.round(pos.coords.latitude * 100) / 100,
                        'long': Math.round(pos.coords.longitude * 100) / 100,
                        'iZid': iZid,
                        'iZorder': iZorder,
                        'iAreaId': iAreaId,
						'ZONE_SKIN': sSkin
                    },
                    success: function(e) {
                        $("form[name=newCarBelgium]").trigger('notbusy');
                        clearTimeout(geolocTimerVn);
                    	gtmCit.initNewGTM();
                    }
                });

            }, geolocBackupVn);

        }).css({cursor: 'pointer'});
    } else {
        $("form[name=newCarBelgium]").find('.geoloc').css({opacity: 0.25});
    }
}
function timeOutCars() {
}

//MÃƒÂ©thode permettant d'ajouter des vehicules en session pour le comparateur
function addToCompare() {
    $('a.addtoCompare').on('click', function(e) {

        e.preventDefault();
        var invoker = $(this).attr('data-source')
        var vehiculeId = $(this).attr('id');
        var finitionId = $(this).attr('rel');
        var nomFinition = $(this).attr('data-value');
        var urlAjax = "";
        var _vehicule = null;


        if (invoker != '' && invoker != null) {

            switch (invoker) {
                case 'CARSELECTOR':
                    urlAjax = "Layout_Citroen_CarSelector_Resultats/addToCompare";
                    callAjax({
                        url: urlAjax,
                        async: false,
                        data: {
                            'invoker': invoker,
                            'vehiculeId': vehiculeId,
                        },
                    });
                    break;
            }


        } else {

            if (showRoomComparateur.length >= 3) {
                promptPop(_addToComparator_KO);
            }
            else if (typeof (finitionId) != 'undefined') {
                for (i = 0; i < 3; i++) {
                    if ($('#select' + i + 'b').val() == 0) {
                        var ulSelect = $('#select' + i + 'b').next().find('ul.select');
                        var t = false;
                        $('#select' + i + 'b').next().find('ul.select li a').each(function(e) {

                            if ($(this).attr('data-value') == finitionId + '#' + vehiculeId) {
                                t = true;
                                $('#select' + i + 'b').val('1');

                                if (showRoomComparateur.length < 3) {
                                    $(this).setActivateGTM(false);
                                    $(this).click();
                                    $(this).click();
                                    promptPop(_addToComparator_OK);
                                    $(this).setActivateGTM(true);
                                }
                            }
                        });
                        if (t == false)
                        {

                            $('#select' + i + 'b').val('1');
                            ulSelect.append('<li><a data-value="' + finitionId + '#' + vehiculeId + '" href="#0">' + nomFinition + '</a></li>');
                            $('#select' + i + 'b').next().find('ul.select li a').each(function(e) {
                                if ($(this).attr('data-value') == finitionId + '#' + vehiculeId) {
                                    t = true;
                                    $('#select' + i + 'b').val('1');
                                    //selection
                                    $(this).setActivateGTM(false);
                                    $(this).click();
                                    //validation
                                    $(this).click();
                                    if (showRoomComparateur.length < 3) {
                                        $(this).click();
                                        $(this).click();
                                        promptPop(_addToComparator_OK);
                                    }
                                    $(this).setActivateGTM(true);
                                }
                            });
                        }
                        break;
                    }
                }

            }

        }

    });
}

//MÃƒÂ©thode servant au binding le clic sur le bouton permettant d'afficher plus de vehicules neufs
function maskBtnComparateur() {
    $(".compareBtn").each(function(e) {
        if ($("input[name=trancheComparateur]").length == 0) {
            $(this).css('display', 'none');
        }
    });
}

/**
 * RESULTATS DE RECHERCHE
 **/
//MÃƒÂ©thode gÃƒÂ©rant la fonction d'autocomplÃƒÂ©tion de la recherche
function autoCompleteSearch() {

    $("input[name=search]").not('.autocomplete-off').typeahead({
        remote: '/_/Layout_Citroen_ResultatsRecherche/suggest?term=%QUERY',
        minLength: 3,
        name: 'rechercher'
    });

    $("input[name=search]").not('.autocomplete-off').on("typeahead:selected typeahead:autocompleted",
            function(e, datum) {
                var sFormName = $(this).parents("form").attr('id');
                $('#' + sFormName).submit();
            });
}
//MÃƒÂ©thode servant au binding le clic sur le bouton permettant d'afficher plus de rÃƒÂ©sultats de recherche
function seeMoreResults() {
    $("#seeMoreResults a").bind('click', function(e) {
        e.preventDefault();
        displayMoreResults('more');
    });
}
//MÃƒÂ©thode affichant les rÃƒÂ©sultats supplÃƒÂ©mentaires via un appel ajax, on rÃƒÂ©cupÃƒÂ¨re un compteur dans un champs cachÃƒÂ© sur la page
function displayMoreResults(typeAff) {
    var iStart = parseInt($('#iCount').val());
    var sSearch = $('#searchField').val();
    callAjax({
        url: "Layout_Citroen_ResultatsRecherche/moreResults",
        async: false,
        data: {
            'iStart': iStart,
            'search': sSearch,
            'typeAff': typeAff
        },
         success: function() {
         
            	gtmCit.initNewGTM();
        }
    });
}

/**
 * ACCESSOIRES
 **/
//MÃƒÂ©thode servant au binding le clic sur le bouton permettant d'afficher plus d'accessoires
function seeMoreAccessories() {
    $(".seeMoreAccessories a").bind('click', function(e) {
        e.preventDefault();
        var sContext = $(this).attr('rel');
        displayMoreAccessories(sContext);
    });
}
//MÃƒÂ©thode affichant les accessoires supplÃƒÂ©mentaires via un appel ajax, on rÃƒÂ©cupÃƒÂ¨re un compteur dans un champs cachÃƒÂ© sur la page
function displayMoreAccessories(sContext) {
    var elem = sContext.split('_');
    var iStart = parseInt($('#iCount_' + elem[0] + '_' + elem[1] + '_' + elem[2]).val());
    callAjax({
        url: "Layout_Citroen_Accessoires/moreAccessories",
        async: false,
        data: {
            'iPosition': elem[0],
            'univ': elem[1],
            'ssUniv': elem[2],
            'lcdv6': elem[3],
            'iStart': iStart
        },
        success: function() {
            lazy.load($('img.lazy'));
            	gtmCit.initNewGTM();
        }
    });
}
//Fonction permettant d'accepter les cookies sur le site, l'Ajax modifie des
//donnÃƒÂ©es de session indiquant que l'utilisateur Ãƒ  accepter les cookies et qu'il
//n'est plus nÃƒÂ©cessaires d'afficher le bandeau d'information
function acceptCookies(redirectUrl) {
    $.ajax({
        url: '/_/Layout_Citroen_Global_Header/acceptcookies',
        async: true,
        data: {
        },
        success: function(data) {
            if (typeof redirectUrl !== 'undefined') {
                document.location.href = redirectUrl;
            }

        }
    });
    return false;
}

function loadIframe() { 
    $('section.clsiframe').each(function() {
    var loader = new Loader($(this).find('.iframeClear')),
    $iframe = $(this).find('iframe#iframeContainer');
    loader.show(LoadingKey, true);
        $iframe.load();
        loader.hide();
    });
}

/** RESEAUX SOCIAUX HOME**/
//MÃƒÂ©thode affichant les feed instagram sur la home
function launchInstagram() {
    var instaFeedId = $('input[name=instaFeedId]').val();
    if (typeof (instaFeedId) != "undefined" && instaFeedId != '') {
        callAjax({
            url: "Layout_Citroen_Home_RemonteesReseauxSociaux/instagram",
            async: true,
            data: {
                "instaFeedId": instaFeedId
            },
        });
    }
}

var selectMotorisation = {
    manage: function() {
        var me = this,
                nextSelector = me.getAttribute('data-value');
        if (0 != me.value) {
            if ('motorisation' == me.getAttribute('data-value')) {
            }
        }
    }
};


/*Simulateur de Finacement*/
/**
 * Pour le simulateur de financement, ajoute la class "off" pour le data-step = 1
 **/
function step1Off()
{
    $("#step1").attr('class', 'parttitle off');
}

var simulateurFinancement = {
    nextStep: function() {
        var aParams = {};
        $($('form#sim-fin').serializeArray()).each(function(index, item) {
            aParams[item.name] = item.value;
        });
        //var iframe = $('#sim_fin_step2_iframe');
        callAjax({
            url: "Layout_Citroen_SimulateurFinancement/step2Ajax",
            data: aParams,
            type: 'post',
            success: function(data) {
                $('#sim_fin_step2_iframe').attr('src', data);

            }
        });

        return; //$('form#sim-fin').submit();
    }
};
/*Outil choix financement*/

var outilChoixFinancement = {
    manage: function() {
        //e.preventDefault();
        var me = $(this);
        data = jQuery.parseJSON(me.attr('data'));
        var loader = new Loader($('#choix_financement'));
        loader.show(LoadingKey, false);
        if (data.p != null) {
            callAjax({
                url: "Layout_Citroen_OutilChoixFinancement/getProduitFinancierAjax",
                data: {
                    "qpid": data.p,
                    "zo": data.zo
                },
                success: function() {
                    loader.hide();
                    gtmCit.initNewGTM();
                }
            });
        } else {
            if (data.q != null) {
                callAjax({
                    url: "Layout_Citroen_OutilChoixFinancement/getQuestionAjax",
                    data: {
                        "id": data.id,
                        "qpid": data.pid
                    },
                    success: function() {
                        $('div#choix_financement div.reponse input').on('click', outilChoixFinancement.manage);
                        gtmCit.initNewGTM();
                        loader.hide();
                    }

                });
            }
        }


    },
    reload: function() {
        var loader = new Loader($('#choix_financement'));
        loader.show(LoadingKey, false);
        callAjax({
            url: "Layout_Citroen_OutilChoixFinancement/getQuestionAjax",
            success: function() {
                $('div#choix_financement div.reponse input').on('click', outilChoixFinancement.manage);
                loader.hide();
            }
        });
    }
};

var onMyProjectPage = function() {

    var elements = $('.vehiculesProjets');
    //verifie si on est bien sur la page mon projet

    if (Boolean($('.vehiculesProjets').length)) {
        var selectCar = jQuery('.selectedCar.active input');
        if (typeof selectCar.val() != 'undefined' && selectCar.val() != 0) {
            var lcdv6 = selectCar.val().split('|')[0];
            if (lcdv6 != '') {
                aParams = {'lcdv6': lcdv6}
                callAjax({
                    url: 'Layout_Citroen_MonProjet_SelectionVehicules/onMyProjectAjax',
                    data: aParams,
                    type: 'post',
                    success: function() {
                    }
                });
            }
        }
    }
}


/*Ma sÃƒÂ©lÃƒÂ©ction de vÃƒÂ©hicules*/
var selectionVehicule = {
    manage: function() {
        var me = $(this);
    },
    save: function(invoker, sr_order, fromShowRoom) {
        var aParams = {};
        if (typeof fromShowRoom != 'undefined' && fromShowRoom != null) {
            aParams['order'] = sr_order;
            aParams['lcdv6_code'] = invoker;
            aParams['lcdv6'] = invoker;

        }
        else {
            var order = $(invoker).attr('id').split('_')[3];
            aParams['order'] = order;
            $($('form#car_selection_' + order).serializeArray()).each(function(index, item) {
                //fetch premier parametre sur le formulaire de selecteur de vehicule
                // doit forcement etre le code lcdv6
                //on passe ce parametre pour generer cet ajax dans la perso

                if (index == 0) {
                    aParams['lcdv6'] = item.value;
                }
                if (item.value == 0) {
                    item.value = null;
                }
                aParams[item.name] = item.value;
            });
        }


        callAjax({
            url: 'Layout_Citroen_MonProjet_SelectionVehicules/addToSelectionAjax',
            data: aParams,
            type: 'post',
            success: function() {
                old_url = location.href;
                var new_url = old_url.substring(0, old_url.indexOf('?'));
                location.href = new_url;
            }
        });
        /*$.event.trigger({
         type: "selection_vehicule.save",
         time: new Date(),
         invoker: invoker
         });*/
    },
    remove: function() {
        var invoker = $(this);
        var order = invoker.parent().parent().attr('id').split('_')[2];
        callAjax({
            url: "Layout_Citroen_MonProjet_SelectionVehicules/removeFromSelectionAjax",
            data: {order: order},
            success: function() {
                old_url = location.href;
                var new_url = old_url.substring(0, old_url.indexOf('?'));
                location.href = new_url;
            }
        });
    }
};

$('div#choix_financement div.reponse input').on('click', outilChoixFinancement.manage);
//$('form#sim-fin div.actions a#next-step').bind('click',simulateurFinancement.nextStep);
$('div.selectedCar div.closer').bind('click', selectionVehicule.remove);



$('.selectZone').prev('.fakehidden').bind('change', selectMotorisation.manage);
/* Inter-dependent lists */
var targetDropDown0b = 'first';
var targetDropDown1b = 'first';
var targetDropDown2b = 'first';
var targetDropDown0c = 'first';
var targetDropDown1c = 'first';
var targetDropDown2c = 'first';

var dropdownstack = {
    manage: function(event) {
       var me = this,
                nextSelector = me.getAttribute('data-next'),
                module = me.getAttribute('data-module'),
                a = me.getAttribute('data-ws');
        var params = null;

		var state = $(me).parent().find('.selectZone .hover').length;
     
        if (module == 'select_vehicule' || module == 'sim_fin') {
            params = me.value;
            params_0 = me.value;
        } else {
            params = me.value.split("#");
            params_0 = params[0];
        }
        var _complement = '';
        if (typeof params != 'string' && params.length > 1) {
            _complement = '&lcdv6=' + params[1];
        }
        var $nextfield = $(nextSelector),
                $button = $(me).parent().find('.button'),
                $figure = $(me).prev('figure');


        /*Comparateur*/
        var unique_finition = Array();

        if (module == 'comparator') {
            var finition_ids = Array('select0b', 'select1b', 'select2b');

            if ($.inArray($(me).attr('id'), finition_ids) != -1) {
                var finition_values = Array();
                $.each(finition_ids, function(index, value) {
                    field_id = '#' + value;
                    field_val = $(field_id).val();
                    if (typeof field_val != 'undefined' && field_val) {
                        fin = field_val.split('#')[0];
                        finition_values.push(fin);
                    }
                });

                if (finition_values.length > 0) {
                    unique_finition = Array();
                    $.each(finition_values, function(index, value) {
                        if ($.inArray(value, unique_finition) == -1) {
                            unique_finition.push(value);
                        }
                    });

                    if (unique_finition.length > 0) {
                        showRoomComparateur = [];
                        var aParams = {};
                        $($('form#form_comparateur').serializeArray()).each(function(index, item) {
                            aParams[item.name] = item.value;
                        });



                        /*callAjax({
                         url:  "Layout_Citroen_Comparateur/updateComparateurSessionAjax",
                         async: false,
                         type: 'post',
                         data: aParams
                         });*/


                        //alert(aParams.length);


                        $('ul.actions.compareBtn li a').show();
                        var _vehicule = null;
                        $.each(unique_finition, function(index, value) {
                            if (parseInt(value) != 0 && showRoomComparateur.length < 3) {
                                _vehicule = {'finition_code': value};
                                showRoomComparateur.push(_vehicule);
                                $(this).trigger("added_to_showroom");
                            }

                            $('li a[rel="' + value + '"]').hide();
                        });
                    }

                }

            }

        }
        //end if(module=='comparator')


        /* Selecteur de VÃƒÂ©hicules */
        if (module == 'select_vehicule' && me.value != 0) {
            var order = $(me).attr('id').split('_')[2];
            var lvl = $(me).attr('id').split('_')[3];
            if (
                    $(me).attr('id') == 'sv_select_0_a' ||
                    $(me).attr('id') == 'sv_select_1_a' ||
                    $(me).attr('id') == 'sv_select_2_a' ||
                    $(me).attr('id') == 'sv_select_0_b' ||
                    $(me).attr('id') == 'sv_select_1_b' ||
                    $(me).attr('id') == 'sv_select_2_b' ||
                    $(me).attr('id') == 'sv_select_0_c' ||
                    $(me).attr('id') == 'sv_select_1_c' ||
                    $(me).attr('id') == 'sv_select_2_c'
                    ) {
                $('#add_to_selection_' + order).removeClass('hidden');
                finition = $('#sv_select_' + order + '_b').data('save');
                engine = $('#sv_select_' + order + '_c').data('save');
                if ((lvl == 'a' && finition == '' && engine == '') || (lvl == 'b' && engine == '') || lvl == 'c') {
                    callAjax({
                        url: "Layout_Citroen_MonProjet_SelectionVehicules/getVehiculeImagePrixAjax",
                        async: true,
                        data: {
                            v: me.value,
                            order: order
                        },
                        success: function() {
                        	gtmCit.initNewGTM();
                        }
                    });

                    $('#sv_select_' + order + '_a').data('save', '');
                    $('#sv_select_' + order + '_b').data('save', '');
                    $('#sv_select_' + order + '_c').data('save', '');
                }
            }
            if (a) {
                _url = a + '?v=' + params_0;
                if (module == 'select_vehicule') {
                    if (finition != undefined && finition != '') {
                        _url += '&f=' + finition;
                    }
                    if (engine != undefined && engine != '') {
                        _url += '&e=' + engine;
                    }
                }
                $.ajax({
                    url: _url,
                    success: function(response) {
                        //fillout next select field
                        $nextfield.next().find('.select').html(response);
                        //reable next select field
                        $nextfield.removeAttr('disabled').unbind('change', dropdownstack.manage).bind('change', dropdownstack.manage);
                        //
                        $nextfield.trigger('change');
                        gtmCit.initNewGTM();
                    }
                });
            }
            //no need to do more for vehicule selection module
            return;
        }



        if (module == 'sim_fin' && me.value != 0) {

            if ($(me).attr('id') == 'sim_fin_select0') {
                $('#car_figure').html('');
            }

            if ($(me).attr('id') == 'sim_fin_select1' || $(me).attr('id') == 'sim_fin_select2') {
                callAjax({
                    url: "Layout_Citroen_SimulateurFinancement/getVehiculeImagePrixAjax",
                    async: true,
                    data: {
                        v: me.value
                    },
                     success: function() {
                        	gtmCit.initNewGTM();
                        }
                });
            }
            if ($(me).attr('id') == 'sim_fin_select1' || $(me).attr('id') == 'sim_fin_select2') {

                $('#next-step').removeClass('disabled').bind('click', simulateurFinancement.manage);

            }

            if (a) {
                $.ajax({
                    url: a + '?v=' + params_0,
                    success: function(response) {
                        //fillout next select field
                        $nextfield.next().find('.select').html(response);
                        //reable next select field
                        $nextfield.removeAttr('disabled').unbind('change', dropdownstack.manage).bind('change', dropdownstack.manage);
                        gtmCit.initNewGTM();
                    }
                });
            }

            return;
        }

        /*Simulateur de Financemenr*/



        //if(!nextSelector) return;
        if (0 != me.value) {
            /* Enable next */
            $nextfield.removeAttr('disabled').unbind('change', dropdownstack.manage).bind('change', dropdownstack.manage);

	
            /* Show media if has */
            if ($figure.length) {

                var $img = $figure.find('img'),
                        src = $img.attr('src');

                /* Backup if hasn't */
                if (!$img.data('backup'))
                    $img.data('backup', src);

                /* Static example */
                $img.attr('src', 'design/frontend/images/car/monprojet-selection01-visuel.png');

                /* Show button if has */
                $button.removeClass('hidden');
            }
            ;

            /* Has ajax */
            var _equipement = me.getAttribute('data-equipement');
            var _tpid = $('input[name=tpid]').val();
            var _zid = $('input[name=zid]').val();
            var _gamme = $('#form_comparateur input[name=filterComparator]').val();
            if ((a || _equipement)) {
                var id = me.getAttribute('id');
                var comparateurMonProjet = $('input.comparateurMonProjet').length > 0 ? 1 : 0;
                if (me.getAttribute('data-save') != '' && me.getAttribute('data-save') != null) {
                    a = null;
                }

                //Select an Item in The Dropdown, not jst click on Item selected for open DropDown 
				if (state == 0 ){ 

					

	                /** set des targets **/
	                if (a && (getTargetDropdown(id) == "" || (getTargetDropdown(id) != $(event.target).text()))) {

	                	if(getTargetDropdown(id) != "first") { 
	                		setTargetDropdown(id, $(event.target).text()); 
	                	}
	                	else
	                	{
	                   		setTargetDropdown(id, $(this).next('div').children().find('a.on').text());
	                	}
	                	 _complement  = _complement +  "&TEMPLATE_PAGE_ID="+_tpid+"&ZONE_ID="+ _zid+'&gamme='+_gamme;

                
	                    $.ajax({
	                        url: a + '?v=' + params[0] + _complement,
	                        success: function(response) {

	                            $nextfield.next().find('.select').html(response);

	                            var  first_engine = $nextfield.next().find('.select li:nth-child(2) a').attr('data-value').split('#')[0];

								if(   (a == '/_/Layout_Citroen_Comparateur/getEngineByFinitionAjax' || a == '/_/Layout_Citroen_MonProjet_Comparateur/getEngineByFinitionAjax') ){

	                                callEquipement(false, true);
	                                callAjax({
	                                    url: "Layout_Citroen_Comparateur/getOutilsAjax",
	                                    async: true,
	                                    data: {
	                                        "LCDV6": params[1], "ID": id, "FINITION": params[0], "TEMPLATE_PAGE_ID": _tpid,"ZONE_ID": _zid, 'ENGINE': first_engine ,'GAMME':_gamme
	                                    }
	                                   
	                                });
	                                callAjax({
	                                    url: "Layout_Citroen_Comparateur/getImageEtPrixVehiculeByVersionAjax",
	                                    async: true,
	                                    data: {
	                                        "LCDV6": params[1], "ID": id, "FINITION": params[0], "TEMPLATE_PAGE_ID": _tpid,"ZONE_ID": _zid, 'ENGINE': first_engine,'GAMME':_gamme
	                                    },
	                                    success: function() {
	                                       
	                                        $('.tooltip,.texttip').each(tooltip.build);
	                                        lazy.load($('.datas img.lazy'));
	                                    }
	                                });
	                            }
	                            gtmCit.initNewGTM();
	                        }
	                    });
					}
                }
	
                if (_equipement && (getTargetDropdown(id) == "" || (getTargetDropdown(id) != $(event.target).text()))) {
          		
          		if(getTargetDropdown(id) != "first") { 
                		setTargetDropdown(id, $(event.target).text()); 
                	}
                	else
                	{
                   	setTargetDropdown(id, $(this).next('div').children().find('a.on').text());
                	}

                    callEquipement(false, false);
                    callAjax({
                        url: "Layout_Citroen_Comparateur/getOutilsAjax",
                        async: true,
                        data: {
                                    "LCDV6": params[1], "ID": id, "FINITION": params[2], "TEMPLATE_PAGE_ID": _tpid,"ZONE_ID": _zid, "ENGINE": params[0]   ,'GAMME':_gamme
                        },
                           success: function() {
                           	  gtmCit.initNewGTM();
                        }
                    });
                    callAjax({
                        url: "Layout_Citroen_Comparateur/getImageEtPrixVehiculeByVersionAjax",
                        async: true,
                        data: {
                            "LCDV6": params[1], "ID": id, "FINITION": params[2], "TEMPLATE_PAGE_ID": _tpid,"ZONE_ID": _zid, "ENGINE": params[0],'GAMME':_gamme
                        },
                        success: function() {
                            $('.tooltip,.texttip').each(tooltip.build);
                            lazy.load($('.datas img.lazy'));
                            gtmCit.initNewGTM();
                        }
                    });


                }
            } else {
                if (
                        $(me).attr('id') == 'select0a'
                        ||
                        $(me).attr('id') == 'select1a' ||
                        $(me).attr('id') == 'select2a'
                        )
                {

                    $(me).attr('data-ws', '/_/Layout_Citroen_Comparateur/getFinitionsByModelAjax');
                } else {
                    var _params = me.value.split("#");
                    //limiter ces appels aux controlleurs adequats

                    callAjax({
                        url: "Layout_Citroen_Comparateur/getImageEtPrixVehiculeByVersionAjax",
                        async: true,
                        data: {
                            "ENGINE": _params[0], "LCDV6": _params[1], "ID": $(me).attr('id'), "FINITION": _params[2],'GAMME':_gamme
                        },
                        success: function() {
                           	  gtmCit.initNewGTM();
                        }
                    });

                }
            }
            //



        } else {


            /* Disabled field */
            $nextfield.attr('disabled', 'disabled');

            /* Reset media if has */
            if ($figure.length) {

                var $img = $figure.find('img'),
                        src = $img.data('backup');

                /* Static example */
                $img.attr('src', src);

                /* Hide button if has */
                $button.addClass('hidden');

            }
            ;

            /* Reset field value */
            $nextfield.val(0).trigger('change');

        }
        ;
        me.setAttribute('data-save', '');
        /* If content is added/updated, keep synchronized height on concerned elements */
        if (sync)
            sync.set();

	  
        return;
    }
};



/* funding page to manage Ajax */
dropdownGroup.getResultField = function(root){
	var $form = root.find('form');
	$.ajax({
		data:$form.serialize(),
        type: 'post',
		url:$form.attr('action'),
		dataType:'html',
		success:function(response){
			dropdownGroup.htmlRender($form,response);
			 gtmCit.initNewGTM();
		}
	});
}

function setTargetDropdown(id, value)
{
	switch(id)
	{
		case "select0b":
		targetDropDown0b = value;
		 break;
		case "select1b":
		targetDropDown1b = value;
		 break;
		case "select2b":
		targetDropDown2b = value;
		 break;
		case "select0c":
		targetDropDown0c = value;
		 break;
		case "select1c":
		targetDropDown1c = value;
		 break;
		case "select2c":
		targetDropDown2c = value;
		 break;
	}
}
function getTargetDropdown(id)
{
	var toReturn = "";
	switch(id)
	{
		case "select0b":
			toReturn = targetDropDown0b;
		 break;
		case "select1b":
			toReturn = targetDropDown1b ;
		 break;
		case "select2b":
			toReturn = targetDropDown2b ;
		break;
		case "select0c":
			toReturn = targetDropDown0c ;
		 break;
		case "select1c":
			toReturn = targetDropDown1c ;
	 	break;
		case "select2c":
			toReturn = targetDropDown2c ;
		 break;
	}

	return toReturn;
}
function reinitComparateur() {
    $('.reinitComparateur0, .reinitComparateur1, .reinitComparateur2').on('click', function(e) {

        var selectToReinit = $(this).attr('data-values');
        var select_val = $('#select' + selectToReinit + 'b').val();
        var _finition = select_val.split('#')[0];
        if ($('a[rel=\"' + _finition + '"]').length) {
            $('a[rel=\"' + _finition + '"]').show();
        }
        var _figure = $('#car' + selectToReinit).find('figure');
        var _img = _figure.find('img');
        var _src = _img.attr('data-backup');
        _img.attr('src', _src);
        $('#outils' + selectToReinit).html('');
        $('#car' + selectToReinit + ' .prices').html('');
        if ($(this).attr('data-info') == 'comparateur') {
            $('#select' + selectToReinit + 'a').val(0).trigger('change');
        }
        $('#select' + selectToReinit + 'b').val(0).trigger('change');
        $('#select' + selectToReinit + 'c').val(0).trigger('change');
        $('.disclaimer').css('display', 'none');
        callEquipement(true, true);
    });


    var lcdv6Preset = $('#lcdv6Preset').val();

    try {
        if(lcdv6Preset.length){
            $('#select0a').next().find('.on').removeClass('on');
            $('#select0a').val(lcdv6Preset).trigger('change');
        }
    } catch(ex) {}
}
function callEquipement(isReinit, forceLoad) {
    //var target = $( event.target );
    if (($('#select0a').length > 0 || $('#select1a').length > 0 || $('#select2a').length > 0)) {
        //target.parents('ul').removeClass('open');
        var model_1 = 0;
        var model_2 = 0;
        var model_3 = 0;
        var finition_1 = 0;
        var finition_2 = 0;
        var finition_3 = 0;
        var engine_1 = 0;
        var engine_2 = 0;
        var engine_3 = 0;
        if ($('#select0a').length > 0) {
            var selectedmodel1 = $('#select0a').val();
            model_1 = selectedmodel1.split("_");
        }
        if ($('#select1a').length > 0) {
            var selectedmodel2 = $('#select1a').val();
            model_2 = selectedmodel2.split("_");
        }
        if ($('#select2a').length > 0) {
            var selectedmodel3 = $('#select2a').val();
            model_3 = selectedmodel3.split("_");
        }
        if ($('#select0b').length > 0) {

            var selectedfinition1 = $('#select0b').val();
            finition_1 = selectedfinition1.split("#");

        }
        if ($('#select1b').length > 0) {
            var selectedfinition2 = $('#select1b').val();
            finition_2 = selectedfinition2.split("#");
        }
        if ($('#select2b').length > 0) {
            var selectedfinition3 = $('#select2b').val();
            finition_3 = selectedfinition3.split("#");
        }

        if ($('#select0c').length > 0) {
            var selectedengine1 = $('#select0c').val();
            engine_1 = selectedengine1.split("#");
        }
        if ($('#select1c').length > 0) {
            var selectedengine2 = $('#select1c').val();
            engine_2 = selectedengine2.split("#");
        }
        if ($('#select2c').length > 0) {
            var selectedegine3 = $('#select2c').val();
            engine_3 = selectedegine3.split("#");
        }

        if ((model_1 != 0 && finition_1[0] != 0) || (model_2 != 0 && finition_2[0] != 0) || (model_3 != 0 && finition_3[0] != 0) || isReinit == true) {
            var _tpid = $('input[name=tpid]').val();
            var _zid = $('input[name=zid]').val();
            var loader = new Loader($('#form_comparateur'));
            loader.show(LoadingKey, false);
            callAjax({
                url: "Layout_Citroen_Comparateur/getEquipementsCaracteristiques",
                async: true,
                data: {
                    "model_1": model_1[0],
                    "model_2": model_2[0],
                    "model_3": model_3[0],
                    "finition_1": finition_1[0],
                    "finition_2": finition_2[0],
                    "finition_3": finition_3[0],
                    "engine_1": engine_1[0],
                    "engine_2": engine_2[0],
                    "engine_3": engine_3[0],
                    "TEMPLATE_PAGE_ID": _tpid,
                    "ZONE_ID": _zid
                },
                success: function(e) {
                    if (finition_1[0] == 0 && finition_2[0] == 0 && finition_3[0] == 0) {
                        $('.disclaimer').css('display', 'none');
                    }

                    if (typeof window.updateComparisonTable === 'function') {
                        updateComparisonTable();
                    }
 					
                    loader.hide();
                    gtmCit.initNewGTM();
                }
            });
        }
    } else {
        if (target.parents('ul').attr('class') != "undefined") {
            target.parents('ul').addClass('open');
        }

    }
}
//MÃƒÂ©thode permettant d'ajouter des vehicules en session pour le comparateur
function replaceToCompare() {
    $('a.replaceToCompare').bind('click', function(e) {
        e.preventDefault();
        var lcdv6 = $(this).attr('data-value');
        callAjax({
            url: "Layout_Citroen_Comparateur/addToCompare",
            async: false,
            data: {
                'lcdv6': lcdv6
            },
        });
    });
}
/* Bind "fakehidden" to create inter-dependent lists */
$('.selectZone').prev('.fakehidden').bind('change', dropdownstack.manage);



/* Bind "fakehidden" to listen sortable change event */
$('.fakehidden[name="listorder"]').bind('change', function() {

});

/*ConcessionVNAV*/

var concession = {
    addToFavs: function(invoker) {
        var invoker = $(invoker);
        var invoker_args = invoker.attr('href').split('#');
        if (invoker_args != '') {
            callAjax({
                url: 'Layout_Citroen_MonProjet_ConcessionVNAV/addToFavorisAjax',
                async: false,
                data: {
                    'sid': invoker_args[1],
                    'type': invoker_args[2]
                },
            });

        }

    },
    showDetails: function(invoker) {
        var invoker = $(invoker);
        var invoker_args = invoker.attr('href').split('#');

        if (invoker_args.length > 0) {
            callAjax({
                url: 'Layout_Citroen_PointsDeVente/getDealer',
                async: false,
                data: {
                    'id': invoker_args[1]
                },  
  				success: function(e) {
  					gtmCit.initNewGTM();
  				}
            });
        }
        return;
    },
    deleteFromFavs: function(invoker) {
        var invoker = $(invoker);
        var invoker_args = invoker.attr('href').split('#');
        if (invoker_args.length > 0) {
            callAjax({
                url: 'Layout_Citroen_MonProjet_ConcessionVNAV/deleteFromFavsAjax',
                async: false,
                data: {
                    sid: invoker_args[1],
                    type: invoker_args[2]
                }
            });
        }
        return;
    }

};
$('.alert .closer').bind('click', function() {
    callAjax({
        url: 'Layout_Citroen_MonProjet_MessageInformatif/closeMessage',
        async: false
    });
});

function languettePerso() {
    $('form[name=fFormLanguettePro] input[name=isPro],form[name=fFormLanguetteClient] input[name=isClient]').on('click', function(e) {
        $(this).parent('form').submit();
    });
}
//charger les donnÃƒÂ©es du formulaire depuis l'onglet pour faire le dataLayer.push au clic
function gtmFormPush(element){
    $('.folder a[href^="#deployable_"]').on('click', function(e) {
        if(e.preventDefault){
            e.preventDefault();
        }else{
            e.returnValue = false;
        }
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
/**Formulaires*/
function initFormulaire() {
    $('section.formulaireCitroen').each(function() {
		
		
        var formActivation = $(this).find('input[name=formActivation]').val();
        var typeFormulaire = $(this).find('input[name=typeFormulaire]').val();
        var typeDevice = $(this).find('input[name=typeDevice]').val();
        var InceCode = $(this).find('input[name=InceCode]').val();
        var lcdvForm = $(this).find('input[name=lcdv6Form]').val();
		var iPageId = $(this).find('input[name=form_page_pid]').val();
        var idSection = $(this).attr('id');
        var email = $(this).find('input[name=email]').val();
        var formTypeLabel = $(this).find('input[name=formTypeLabel]').val();
        var isDeployed = $(this).find('input[name=deployed]').val();
        var typeOfForm =  $(this).find('input[name=FORM_CONTEXT_CODE]').val();
        var isRTO = $("#isPDV").val();

        var formEquipCode =  $(this).find('input[name=EQUIPEMENT_CODE]').val();
        var formIDType =  $(this).find('input[name=TYPE_ID]').val();
        var formUserCode =  $(this).find('input[name=USER_TYPE_CODE]').val();

        var contextForm = "";
        if (formActivation != 'CHOIX' && ( !$(this).parent().hasClass('secret') ||  typeOfForm == 'RTO')) {

        if(lcdvForm)
        { 
            contextForm = "CAR";
        }
        if(typeOfForm == 'RTO')
        {   
            contextForm =  'RTO' ;
            InceCode = getINCEForm(formIDType, formUserCode, formEquipCode, contextForm, InceCode);
        }
		
		if(contextForm=="RTO"){
			if(isDeployed!=1){
        		getFormId(InceCode, formActivation, idSection, lcdvForm, email, formTypeLabel, isDeployed, contextForm,iPageId);
			}	
		}
		
		else{
			getFormId(InceCode, formActivation, idSection, lcdvForm, email, formTypeLabel, isDeployed, contextForm,iPageId);
 		}
		
    }

    var idParent = $(this).parent().attr('id');
    var idPage = $('input[name=' + idSection + 'idPage]').val();
    var zoneOrder = $('input[name=' + idSection + 'zoneOrder]').val();
    var areaId = $('input[name=' + idSection + 'areaId]').val();
    var zoneTid = $('input[name=' + idSection + 'zoneTid]').val();
    $('.folder a[href^="#' + idParent + '"]').on('click', function(e) {
            if ($('#' + idSection).find('iframe').size() == 0) {
                if(lcdvForm)
                {
                    contextForm = "CAR";
                }
                if(typeOfForm == 'RTO')
                {
                    contextForm =  'RTO' ;
                    InceCode =  getINCEForm(formIDType, formUserCode, formEquipCode, contextForm, InceCode);
                }
                getFormId(InceCode, formActivation, idSection, lcdvForm, email, formTypeLabel, isDeployed, contextForm,iPageId);
            } else {
                if(lcdvForm)
                {
                    contextForm = "CAR";
                }
                if(typeOfForm == 'RTO')
                {
                    contextForm =  'RTO' ;
                    InceCode =  getINCEForm(formIDType, formUserCode, formEquipCode, contextForm, InceCode);
                }
                /**
                *CommentÃƒÂ© car bug des onglets outils
                *reinitForm(idPage, zoneOrder, areaId, zoneTid, idParent, lcdvForm, email, contextForm);
                */
                getFormId(InceCode, formActivation, idSection, lcdvForm, email, formTypeLabel, isDeployed, contextForm,iPageId);
            }
        });

$('.nextStepForm' + idSection).on('click', function(e) {
    if(e.preventDefault){
        e.preventDefault();
    }else{
        e.returnValue = false;
    }
    var section = $(this).attr('rel');
    var typeClient = $('section.' + section).find('input[name=isPro' + idSection + ']:checked').val();
    var isDeployed = $('section.' + section).find('input[name=deployed]').val();
    if (typeof (typeClient) != 'undefined') {
        if(lcdvForm)
        {
            contextForm = "CAR";
        }
        if(typeOfForm == 'RTO')
        {
            contextForm =  'RTO' ;
            InceCode = getINCEForm(formIDType, formUserCode, formEquipCode, contextForm, InceCode);
        }

        callFormulaire(typeFormulaire, typeClient, typeDevice, section, lcdvForm, email, formTypeLabel, isDeployed, contextForm,iPageId);
    }
});
});
}
function ResizeIframeFromParent(id)
{
    try {
        if (jQuery('#' + id).length > 0) {
            var window = document.getElementById(id).contentWindow;
            var prevheight = jQuery('#' + id).attr('height');
            var newheight = window.document.getElementById('wf_form_content').clientHeight;

            //console.log("Adjusting iframe height for "+id+": " +prevheight+"px => "+newheight+"px");
            if (newheight != prevheight && newheight > 0) {
                jQuery('#' + id).attr('height', newheight + 10);
            }
        }
    }
    catch (e) {
    }
}
function ResizeIframeFromParent2(id)
{
    var isMSIE = /*@cc_on!@*/0; //test pour dÃƒÂ©terminÃƒÂ© si IE
    if (!isMSIE)
    {
        var $myIframe = $('#' + id);
        var myIframe = $myIframe[0];
        var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;

        myIframe.addEventListener('load', function() {
            ResizeIframeFromParent(id);

            var target = myIframe.contentDocument.getElementById('wf_form_content');

            var observer = new MutationObserver(function(mutations) {
                ResizeIframeFromParent(id);
            });

            var config = {
                attributes: true,
                childList: true,
                characterData: true,
                subtree: true
            };
            observer.observe(target, config);
        });
    }
    else
    {
        setInterval(function() {
            ResizeIframeFromParent(id);
        }, 1000);
    }
}

function scrollForm(height) 
{   
        var arrFrames = document.getElementsByTagName("iframe");
        for(i = 0; i<arrFrames.length; i++){
            try{
                if(arrFrames[i].id == IframeId){
                        $('html, body').delay(100).stop().dequeue().animate({
                        scrollTop: $(arrFrames[i]).offset().top+height
                    }, 200);
                 }
              }
              catch(e){
              } 
        }
}

function getFormId(InceCode, formActivation, idIframe, lcdvForm, email, formTypeLabel, isDeployed, contextForm,iPageId) {
    if (typeof (InceCode) == "string" && InceCode != '') {
        if (typeof formTypeLabel == 'undefined' || formTypeLabel == '') {
            formTypeLabel = '';
        }
		var iFormPageId = $('input[name=form_page_pid]').val();
        var styleForm = $('input[name=' + idIframe + 'styleForm]').val();
        var formClass = $("body").attr('class');
		var iPageId2 = $('input[name=form_page_pid]').val();
        //var url = "/forms/" + formTypeLabel + "/Layout_Citroen_Formulaire/iframe?version=2&idform=" + InceCode + "&typeform=" + formActivation + "&section=" + idIframe + "&lcdv=" + lcdvForm + "&email=" + email + "&formClass=" + formClass + "&styleForm=" + styleForm + "&isDeployed=" + isDeployed+"&contextForm="+contextForm;
		var url = "/forms/" + formTypeLabel + "/Layout_Citroen_Formulaire/iframe?version=2&idform=" + InceCode + "&typeform=" + formActivation + "&section=" + idIframe + "&lcdv=" + lcdvForm + "&email=" + email + "&formClass=" + formClass + "&styleForm=" + styleForm + "&isDeployed=" + isDeployed + "&form_page_id=" +iFormPageId+"&contextForm="+contextForm;
        $("#" + idIframe).html('<iframe id="iframe' + idIframe + '" src="' + url + '" style="min-height:100px; width: 100%; margin: 0px; padding: 0px; clear: both; display: block" scrolling="no" frameborder="no"></iframe>');
        var loader = new Loader($('#' + idIframe));
        loader.show(LoadingKey, false);
        IframeId = 'iframe' + idIframe;
        ResizeIframeFromParent2('iframe' + idIframe);
        /*setInterval(function() {
         ResizeIframeFromParent('iframe' + idIframe);
         }, 1000);*/
        $('#' + idIframe).slideDown("slow");
        $('#iframe' + idIframe).load(function() {
            loader.hide();
        });
    }
}
function callFormulaire(typeFormulaire, typeClient, typeDevice, idSection, lcdvForm, email, formTypeLabel, isDeployed, contextForm,iPageId) {
	var iFormPageId = $('input[name=form_page_pid]').val();
    callAjax({
        url: '/_/Layout_Citroen_Formulaire/getContenu',
        async: false,
        data: {
            'typeFormulaire': typeFormulaire,
            'typeClient': typeClient,
            'typeDevice': typeDevice,
            'idSection': idSection,
            'lcdvForm': lcdvForm,
            'email': email,
            'isDeployed': isDeployed,
            'contextForm' : contextForm,
			'form_page_pid' : iFormPageId
        },
        success: function(e) {
  					gtmCit.initNewGTM();
  				}
    });
}
function reinitForm(idPage, zoneOrder, areaId, zoneTid, idParent, lcdvForm, email, contextForm) {
    $.ajax({
        url: '/_/Layout_Citroen_Formulaire/reinitForm',
        async: false,
        data: {
            'idPage': idPage,
            'areaId': areaId,
            'zoneTid': zoneTid,
            'zoneOrder': zoneOrder,
            'lcdvForm': lcdvForm,
            'email': email,
            'contextForm' : contextForm
        },
        success: function(data) {
            $('#' + idParent).html(data);
            initFormulaire();
            gtmCit.initNewGTM();
        }
    });
}
function getINCEForm(TYPE_ID, USER_TYPE_CODE, EQUIPEMENT_CODE, CONTEXT_CODE, InceCode) {
    $.ajax({
        url: '/_/Layout_Citroen_Formulaire/getINCECode',
        async: false,
        data: {
            'TYPE_ID': TYPE_ID,
            'USER_TYPE_CODE': USER_TYPE_CODE,
            'EQUIPEMENT_CODE': EQUIPEMENT_CODE,
            'CONTEXT_CODE': CONTEXT_CODE
        },
        success: function(data) {
	        data = JSON.parse(data);
	        if(data && data['FORM_INCE_CODE']){
	        InceCode = data['FORM_INCE_CODE'];
	        }
        }
    });
    return InceCode;
}
function finalStepFunction(dataForm, idSection, idForm, formType) {
     var idPage = $('input[name=' + idSection + 'idPage]').val();
    var zoneOrder = $('input[name=' + idSection + 'zoneOrder]').val();
    var areaId = $('input[name=' + idSection + 'areaId]').val();
    var zoneTid = $('input[name=' + idSection + 'zoneTid]').val();
    var isDeployed = $('input[name=' + idSection + 'deployed]').val();
	var iFormPageId = $('input[name=' + idSection + 'form_page_pid]').val();
    var params = {};
    var arDatas = dataForm.message.split('&');
   
    arDatas.forEach(function(part) {
        
        var pair = part.split('=');
        pair[0] = decodeURIComponent(pair[0]);
        pair[1] = decodeURIComponent(pair[1]);
        params[pair[0]] = (pair[1] !== 'undefined') ?
                pair[1] : true;
    });
    if (typeof formType == 'undefined' || formType == '') {
        formType = '';
    }
    var car = null;
    if (typeof params['car'] != 'undefined' && params['car'] != '') {
        var car = params['car'];
    }
    

    var loader = new Loader($('#' + idSection));
    loader.show(LoadingKey, false);
    
    // Vérification arrivée depuis un CTA
    var formOrigin = window.location.href.match(/[?&]origin=ctaperso/i) ? "ctaperso" : null;
    
    $.ajax({
        url: '/forms/' + formType + '/Layout_Citroen_Formulaire/finalStep',
        async: false,
        type: 'POST',
        data: {
            'params': params,
            'idPage': idPage,
            'areaId': areaId,
            'zoneTid': zoneTid,
            'zoneOrder': zoneOrder,
            'isDeployed': isDeployed,
            'idForm': idForm,
            'car': car,
            'formOrigin': formOrigin,
			'form_page_pid' : iFormPageId

        },
        success: function(data) {
            loader.hide();
            $('.' + idSection + 'Chapo').hide();
            $('#' + idSection).html(data);
            buttonForm();
            ReinitializeAddThis();
            $('.tooltip,.texttip').each(tooltip.build);
            if ($('a[name=ESSAYER]').size() > 0) {
                $(document).scrollTop($("a[name=ESSAYER]").offset().top);
            } else {
                $(document).scrollTop($("a[name=" + idSection + "anchor]").offset().top);
            }

   		gtmCit.initNewGTM();
        }

    });
}
function ReinitializeAddThis() {
    addthis.toolbox('.addthis_toolbox');
}

function buttonForm() {
    $('.addSelectionForm a').on('click', function() {
        var rel = $(this).attr('rel');
        var params = rel.split('_');
        var aParams = {};
        aParams['order'] = params[1];
        aParams['lcdv6_code'] = params[0];
        aParams['lcdv6'] = params[0];
        aParams['isForm'] = true;
        $.ajax({
            url: '/_/Layout_Citroen_MonProjet_SelectionVehicules/addToSelectionAjax',
            data: aParams,
            type: 'post',
            dataType: 'json',
            success: function(data) {
                promptPop(data.message);
            }
        });
    });
    $('.bookmarkForm a').on('click', function(e) {
        var id = $(this).attr('rel');
        var bookmarkTpl = $(document).find('.bookmark').html();
        $.ajax({
            url: '/_/Layout_Citroen_PointsDeVente/ajaxPdvBookmarkGet',
            dataType: 'json',
            success: function(data) {
                // DÃƒÂ©finition de la fonction temporaire qui enregistre le point de vente
                var ajaxCallSavePdvBookmark2 = function() {
                    $.ajax({
                        url: '/_/Layout_Citroen_PointsDeVente/ajaxPdvBookmarkSet',
                        dataType: 'json',
                        cache: false,
                        data: {
                            pdvId: id
                        },
                        success: function(data2) {
                            // console.log('favori enregistrÃƒÂ©');

                            $.fancybox.close();
                            if (typeof data2.bookmark_btn_label) {
                                $placeholder.find('.bookmarks a').html(data2.bookmark_btn_label).removeAttr('href').unbind('click');
                            }
                        }
                    });
                }


                // Si l'utilisateur est connectÃƒÂ© et a dÃƒÂ©jÃƒ  une concession favorite
                // ou utilisateur non connectÃƒÂ© & pdv dÃƒÂ©fini dans un cookie
                // => on affiche la popin "Vous avez dÃƒÂ©jÃƒ  une concession favorite"
                if ((data.loggedin == true && data.favoris_db.favoris_vn != null && typeof data.favoris_db.favoris_vn != 'undefined') || (data.loggedin == false && data.favoris_cookie.favoris_vn != null && typeof data.favoris_cookie.favoris_vn != 'undefined')) {
                    var output = _.template(bookmarkTpl, {id: id, name: data.favoris_vn_name});
                    promptPop(output);

                    // Popin : clic sur bouton confirmer
                    $('.fancybox-inner .actions .green>a').click(function(e) {
                        ajaxCallSavePdvBookmark2(); // Enregistrement du favori
                    });

                    // Popin : clic sur bouton annuler
                    $('.fancybox-inner .actions .grey>a').click(function(e) {
                        $.fancybox.close();
                    });

                    return;
                }
                ajaxCallSavePdvBookmark2(); // Enregistrement du favori
            }
        });
    });
}
function hasATactileScreen(id){
	if(id==1){
		 $('.saisie_VIN').show();
		 $('.message_ineligibilite').hide();
		 $('.retour_ajax').hide();
	}
	else{
		$('.saisie_VIN').hide();
		$('.message_ineligibilite').show();
		$('.retour_ajax').hide();
	}
}
function checkEligibilityLinkMyCitroen(VIN,pid,pversion){
		
		
	$.ajax({
        url: '/_/Layout_Citroen_EligibiliteLinkMyCitroen/check',
        async: false,
   		dataType: 'json',
        data: {
            'VIN': VIN,
            'pid': pid,
            'pversion':pversion
        },
        success: function(e) {
        	if(e['invalide_size']){
        		var notice = ($('.edge-notice').html());
        		promptPop(notice);
        		$('.continued').on("click", function(e){
				$('.edge-notice').hide();
				$('.fancybox-skin').hide(); 
				$('#vin').focus();
				});
        	}
        	else{
        		$('.retour_ajax').show();
        		$('.retour_ajax').html(e['message']);
        	}
        }
       
    } );

	return false;
}

function chargeIframeDeploy(url_web_deploy){
	
	
	$('section#'+url_web_deploy).each(function() {
		
        var formActivation = $(this).find('input[name=formActivation]').val();
        var typeFormulaire = $(this).find('input[name=typeFormulaire]').val();
        var typeDevice = $(this).find('input[name=typeDevice]').val();
        var InceCode = $(this).find('input[name=InceCode]').val();
        var lcdvForm = $(this).find('input[name=lcdv6Form]').val();
        var iPageId = $(this).find('input[name=form_page_pid]').val();
        var idSection = $(this).attr('id');
        var email = $(this).find('input[name=email]').val();
        var formTypeLabel = $(this).find('input[name=formTypeLabel]').val();
        var isDeployed = $(this).find('input[name=deployed]').val();
        var typeOfForm =  $(this).find('input[name=FORM_CONTEXT_CODE]').val();
        var isRTO = $("#isPDV").val();

        var formEquipCode =  $(this).find('input[name=EQUIPEMENT_CODE]').val();
        var formIDType =  $(this).find('input[name=TYPE_ID]').val();
        var formUserCode =  $(this).find('input[name=USER_TYPE_CODE]').val();

        var contextForm = "";
        if (formActivation != 'CHOIX' && ( !$(this).parent().hasClass('secret') ||  typeOfForm == 'RTO')) {
	
	        if(lcdvForm)
	        { 
	            contextForm = "CAR";
	        }
	        if(typeOfForm == 'RTO')
	        {   
	            contextForm =  'RTO' ;
	            InceCode = getINCEForm(formIDType, formUserCode, formEquipCode, contextForm, InceCode);
	        }
        }
   
	getFormId(InceCode, formActivation, idSection, lcdvForm, email, formTypeLabel, isDeployed, contextForm, iPageId);
	});
}

$(document).ready(function(){

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

	});

});

/* Inter-dependent lists */
var dropdownstack = {
	manage:function(){

		var me = this,
			nextSelector = me.getAttribute('data-next');
		if(!nextSelector) return;

		var $nextfield = $(nextSelector),
			$button = $(me).parent().find('.button'),
			$figure = $(me).prev('figure');

		if(0 != me.value){

			/* Enable next */
			$nextfield.removeAttr('disabled').unbind('change',dropdownstack.manage).bind('change',dropdownstack.manage);

			/* Show media if has */
			if($figure.length){

				var $img = $figure.find('img'),
					src = $img.attr('src');

				/* Backup if hasn't */
				if(!$img.data('backup')) $img.data('backup',src);

				/* Static example */
				$img.attr('src','design/frontend/images/car/monprojet-selection01-visuel.png');

				/* Show button if has */
				$button.removeClass('hidden');

			};

			/* Has ajax */
			var a = me.getAttribute('data-ws');
			if(a){
				$.ajax({
					url:a+'?v='+me.value,
					success:function(response){
						$nextfield.next().find('.select').html(response);
					}
				});
			};


		} else {

			/* Disabled field */
			$nextfield.attr('disabled','disabled');

			/* Reset media if has */
			if($figure.length){

				var $img = $figure.find('img'),
					src = $img.data('backup');

				/* Static example */
				$img.attr('src',src);

				/* Hide button if has */
				$button.addClass('hidden');

			};

			/* Reset field value */
			$nextfield.val(0).trigger('change');

		};

		/* If content is added/updated, keep synchronized height on concerned elements */
		if(sync) sync.set();

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
		}
	});
}

// /* Set default on all locators except those which are hidden and custom initialisation */

/* Locator custom initialisation */
/*

$('.locator').gLocator({
	onLoad:function(){},				// Initialisation
	onFilter:function(){},				// When filters are applied
	onList:function(){},				// When results displayed / refreshed
	onItemClick:function(storeId){},	// When item (marker or result) is cliked, receives storeId as parameter
	onDetails:function(){},				// When details is displayed
	onGeoloc:function(){},				// When geolocation is completed
	onGeolocError:function(){}			// When geolocation has failed
});


*/
;(function($){

	var $doc = $(document);

	var SetPDV = function ($root){ this.init($root); };
		SetPDV.prototype = {
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
					if($content.find('.item').length){
						$('html, body').animate({
							scrollTop:$content.offset().top - 100
						}, 'slow');
					}
				}
				this.loaderMap.hide();
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
						oThis.$root.addClass('initDone');
						/* if has bookmark button */
						$(that.element).find('.bookmarks a').click(function(e){
							e.preventDefault();
							that._booked = this;
							
							// Récupération des points de vente favoris
							$.ajax({
								url: '/_/Layout_Citroen_PointsDeVente/ajaxPdvBookmarkGet',
								dataType: 'json',
								success: function(data){
									var source = null;
									
									// User connecté => on utilise les favoris de son compte
									if( data.loggedin == true  && data.favoris_db.favoris_vn != null && typeof data.favoris_db.favoris_vn != 'undefined' ){
										source = data.favoris_db;
									}
									
									// User non connecté => on utilise les favoris défini en cookie
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
							this.$locations.find('.stores-results').html('Vos favoris');
						}
						this._booked = null;

					},
					onItemClick:function(storeId, storeRRDI){
						if(oThis.$root.hasClass('locatorVN')){
							try{
								$(document).on('ajaxComplete ',function(event, xhr, settings){ 
									oThis.showVnResult(xhr);
									$(document).off('ajaxComplete');
								});
								oThis.loaderMap.show();
								getCarStock(storeId, storeRRDI);
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
		};



	function initLocator($target){
		var $locators;
		if($target !== undefined) {
			$locators = $target;	
		} else {
			$locators = $('.locatorPDV, .locatorVN, .locatorMesCS').not('.secret .locator');
		}

		if($locators !== undefined){
			$locators.each(function(){
				new SetPDV($(this));
			});
		}
	}

	Cit._checkLocatorMap = function($content){
		initLocator($content.find('.locator'));
	}


	if($.fn.gLocator){ initLocator(); }


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


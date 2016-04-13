$(document).ready(function(){
	$('.stickyplaceholder.monprojet .sticky ul li a').each(function() {
		$(this).bind('click', function(e) {
			$('.stickyplaceholder.monprojet .sticky ul li').removeClass('on');
			$(this).parent().addClass('on');
		});
	});
	$('.connect-citroen-id').unbind('click');
    $('.connect-citroen-id').bind('click', function(e) {
		e.preventDefault();
		var _url = "/_/User/openid";
		window.open(_url,'login','width=600,height=470');
        //var html = $('#layerconnexion').html();
        //promptPopConnexion(html);
    });
    $('.disconnect a').click(function(e) {
        e.preventDefault();
        callAjax({url: "/_/User/deconnexion"});
    });
    $('.connectList ul.socials li a, section.mesPreferences ul.socials li a, section.forminscription .connects li a').each(function() {
        $(this).bind('click', function(e) {
            e.preventDefault();
            var _url = $(this).attr('href');
            window.open(_url,'login','width=600,height=470');
        });
    });
    $('section.forminscription a.valid, section.forminscription .register a, section.mesPreferences .register a.button, section.forminscription > a.button').click(function(e){
        e.preventDefault();
		if (!$(this).hasClass('disabled')) {
			validationInscription();
		}
    });
	$('section.mesPreferences input[type=\'checkbox\']').click(function(e){
		callAjax({
			type: 'GET',
			url: "/_/User/majNewsletters",
			dataType: "json",
			data: {
				type: $(this).attr('id'),
				value: $(this).is(':checked')
			}
		});
	});
	$('section.forminscription input[name=\'email\']').on('input', function() {
		if ($('section.forminscription input[name=\'confirmEmail\']').attr('disabled')=='disabled') {
			$('section.forminscription input').removeAttr('disabled');
			$('a.valid').removeClass('disabled');
			$('.register a').removeClass('disabled');
			$('span.error').hide();
			$('.alredayaccount').hide();
			validationLock = false;
		}
    });
	$('section.forminscription input[name=\'email\']').on('blur', function() {
		email = $('section.forminscription input[name=\'email\']').val();
		callAjax({
			type: 'POST',
			url: "/_/User/emailValide",
			dataType: "json",
			data: {
				USR_EMAIL: email
			}
		});
	});
	$('section.mesPreferences .field input').on('input', function() {
		if ($('section.mesPreferences .register a').hasClass('disabled')) {
			$('section.mesPreferences .register a').removeClass('disabled');
			validationLock = false;
		}
	});
	$('section.mesPreferences .field ul.select li a').not('*[class=\'on\']').on('click', function() {
		if ($('section.mesPreferences .register a').hasClass('disabled')) {
			$('section.mesPreferences .register a').removeClass('disabled');
			validationLock = false;
		}
	});
    promptPopConnexion = function(html,callback){
		$.fancybox({
			content:html,
			fitToView:false,
			minWidth:200,
			maxWidth:600,
			padding:20,
			wrapCSS:'prompt-wrap',
			helpers:{
				overlay:{
					css:{
						'background' : 'rgba(255,255,255,0.1)'
					}
				}
			},
			beforeShow:function(){
				var _url = "/_/User/openid";
				$('.fancybox-inner iframe').attr('src', _url);
				/* Tooltip */
				this.inner.click(function(){
					tooltip.close();
				}).find('.tooltip,.texttip').each(tooltip.build);
				/* jScrollPane */
				this.inner.find('.scroll').jScrollPane({ autoReinitialise:true, autoReinitialiseDelay:10, verticalGutter:20 }).bind('mousewheel',function(e){ e.preventDefault(); });
				if(callback) callback.call(this.inner);
			}
		});
	}
	$('.selectedCar .content').click(function(e){
		_target = $(e.target);
		if(!_target.hasClass('closer') && !_target.hasClass('pictoModifier')) {
			idx = $(this).parent().attr('id').replace('selected_car_','');
			if (idx) {
				callAjax({
					url: 'Layout_Citroen_MonProjet_SelectionVehicules/setVehiculeActif',
					data: {
						'idx': idx
					}
				});
			}
		}
	});
});
function confirmConfigurateur(){
	$.ajax({
		url: '/_/Layout_Citroen_MonProjet_SelectionVehicules/majSelection',
		data: {
			'indice': indiceConfigurateur,
			'selection': selectionConfigurateur
		}
	}).done(function(html) {
		$('.listeVehicules > div:nth-child('+(indiceConfigurateur+1)+')').remove();
		if (indiceConfigurateur == 0) {
			$('.listeVehicules > div:nth-child('+(indiceConfigurateur+1)+')').before(html);
		} else {
			$('.listeVehicules > div:nth-child('+indiceConfigurateur+')').after(html);
		}
		$(document).ready(function(){
			lazy.set($('img.lazy').not('.slider img.lazy'));
			$('.fancybox-close').click();
			$('div.selectedCar div.closer').unbind('click').bind('click', selectionVehicule.remove);
		});
	});
}
function confirmAjoutConfigurateur(){
	$.ajax({
		url: '/_/Layout_Citroen_MonProjet_SelectionVehicules/addSelection',
		data: {
			'selection': selectionConfigurateur
		}
	}).done(function(html) {
		if (html) {
			var idx = parseInt($(html).attr('id').replace('selected_car_', ''));
			if (idx < 3) {
				$('.listeVehicules > div:nth-child('+(idx+1)+')').remove();
				if (idx == 0) {
					$('.listeVehicules > div:nth-child('+(idx+1)+')').before(html);
				} else {
					$('.listeVehicules > div:nth-child('+idx+')').after(html);
				}
				$(document).ready(function(){
					lazy.set($('img.lazy').not('.slider img.lazy'));
					$('.fancybox-close').click();
					$('div.selectedCar div.closer').unbind('click').bind('click', selectionVehicule.remove);
				});
			}
		} else {
			$('.fancybox-close').click();
		}
	});
}
function setVehiculeEdit(idx) {
	$.ajax({
		url: '/_/Layout_Citroen_MonProjet_SelectionVehicules/setVehiculeEdit',
		data: {
			'idx': idx
		}
	}).done(function(retour) {
		$('.listeVehicules > div:nth-child('+(idx+1)+')').remove();
		if (idx == 0) {
			$('.listeVehicules > div:nth-child('+(idx+1)+')').before(retour);
		} else {
			$('.listeVehicules > div:nth-child('+idx+')').after(retour);
		}
		$(document).ready(function(){
			lazy.set($('img.lazy').not('.slider img.lazy'));
			$('.selectZone .select').cSelector();
			$('.selectZone').prev('.fakehidden').unbind('change', dropdownstack.manage).bind('change', dropdownstack.manage);
			$('.selectZone').prev('.fakehidden').each(function(){
				if ($(this).data('save') !='') {
					$(this).trigger('change');
				}
			})
		})
	});
}
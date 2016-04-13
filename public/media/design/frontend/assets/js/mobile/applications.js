var IframeId; 
$(document).ready(function(){
	//Actualit�
	seeMoreNews();
	filterNews();
	//R�sultats de recherche
	seeMoreResults();
	autoCompleteSearch();
    // Gestion des onglets
    $('.data-onglet').each(function(){
        onglet = $(this).data('onglet');
        valeur = $(this).children().detach();
        valeur.appendTo('.onglet-'+onglet);
        $(this).remove();
    });
	//Finitions
	maskBtnComparateur();
	ajaxToggleFinitions();
	fctConnexion();
	//Iframe
	//loadIframe();
	$('footer .site-version').bind('click', function(e){
		e.preventDefault();
		_version = $(this).data('version');
		if (_version == 'mobile'){
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
	focusVehicule();
	focusFinition();
	// Maj dataLayer
	$('form.search-lvl').submit(function(e){
		dataLayer[0].internalSearchKeyword = $(this).find("input[type='search']").val();
	});
	
	// Choix langue dans le footer
	$('footer .languageSelect select').change(function(e){
		var selectedOption = $(this).find('option:selected');
		var urlTo = selectedOption.data('href');
		window.location.href = urlTo;
	});		
	
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
/**
* ACTUALITES
**/
//M�thode servant au binding le clic sur le bouton permettant d'afficher plus de news
function seeMoreNews(){
	$("#seeMoreNews a").on('click',function(e){
		e.preventDefault();
		displayMoreNews('more');
	});
}
//M�thode affichant les news suppl�mentaires via un appel ajax, on r�cup�re un compteur dans un champs cach� sur la page
function displayMoreNews(typeAff){
	var iMin = parseInt($('#iCount').val());
	var iPid = parseInt($('#pid').val());
    var loader = new Loader($('#seeMoreNews'));
    loader.show();
	callAjax({
		url: "Layout_Citroen_Actualites_Galerie/moreNews",
		async: false,
		data:	{
		   'iMin' : iMin,
		   'typeAff' : typeAff,
		   'iPid' : iPid
		},
        afterAction: function( jqXHR, textStatus){
            lazy.set($('#allActu img.lazy'));
            loader.hide();
        }
	});
}
//M�thode permettant de submitter le formulaire de filtre au changement d'un des filtres
function filterNews(){
	$('select#themeId').on('change',function(){
		var sFormName = $(this).parents("form").attr('id');
        var pid = $('input[name="pid"]').val();
        var iTheme = $(this).val();
        var loader = new Loader($('#allActu'));
        loader.show(LoadingKey,false);
		if(iTheme == '0'){
			/*var urlPage = document.URL;
			var urlNoParams = urlPage.split('?');
			window.location.href = urlNoParams[0];*/
                        callAjax({
                        url: "Layout_Citroen_Actualites_Galerie/filterNews",
                        async: false,
                        data:{
                           'iPid' : pid,
                           'iTheme' : iTheme,
                           'iMin' : 1
                        }
                    });
		}else{
                    //$('#'+sFormName).submit();

                    callAjax({
                        url: "Layout_Citroen_Actualites_Galerie/filterNews",
                        async: false,
                        data:{
                           'iPid' : pid,
                           'iTheme' : iTheme,
                           'iMin' : 1
                        }
                    });
		}
	});
}
/**
* IFRAME
**/

function loadIframe(){
    var loader = new Loader($('.iframeClear'));
    loader.show(LoadingKey,false);
    $('iframe.loadingIframe').load(function() {
        loader.hide();
    });
	 $('iframe#iframeContainer').load(function() {
        //verification de l'iframe
        try {
            document.getElementById('iframeContainer').contentWindow.document.body.innerHTML;
            if($("#iframeContainer").contents().find('body').html() == "PAGE NOT FOUND"){
                $('#iframeContainer').css('display', 'none');
                $('#alterFrame').css('display', 'block');
            }
            adjustMyFrameHeight(document.getElementById("iframeContainer"));
       } catch (ex) {
            $('#iframeContainer').css('display', 'none');
            $('#alterFrame').css('display', 'block');
       }

    });
}
/**
* RESULTATS DE RECHERCHE
**/
//Méthode gérant la fonction d'autocomplétion de la recherche
function autoCompleteSearch(){
	$("input[type=search]").not('.autocomplete-off').typeahead( {
		remote: '/_/Layout_Citroen_ResultatsRecherche/suggest',
		minLength: 3,
		name: 'rechercher'
	});
	$("input[type=search]").not('.autocomplete-off').on("typeahead:selected typeahead:autocompleted", function(e,datum) {
		var sFormName = $(this).parents("form").attr('id');
		$('#'+sFormName).submit();
	});
}
//M�thode servant au binding le clic sur le bouton permettant d'afficher plus de r�sultats de recherche
function seeMoreResults(){
	$("#seeMoreResults a").bind('click',function(e){
		e.preventDefault();
		displayMoreResults('more');
	});
}
//M�thode affichant les r�sultats suppl�mentaires via un appel ajax, on r�cup�re un compteur dans un champs cach� sur la page
function displayMoreResults(typeAff){
	var iStart = parseInt($('#iCount').val());
	var sSearch = $('#searchField').val();
	callAjax({
		url: "Layout_Citroen_ResultatsRecherche/moreResults",
		async: false,
		data:	{
		   'iStart' : iStart,
		   'search' : sSearch,
		   'typeAff' : typeAff
		},
	});
}
//Fonction permettant d'accepter les cookies sur le site, l'Ajax modifie des
//données de session indiquant que l'utilisateur à accepter les cookies et qu'il
//n'est plus nécessaires d'afficher le bandeau d'information
function acceptCookies(redirectUrl){
    $.ajax({
		url: '/_/Layout_Citroen_Global_Header/acceptcookies',
		async: true,
		data:	{
		},
               success: function(data) {

                    if(typeof redirectUrl !== 'undefined'){
                        document.location.href =  redirectUrl;
                    }

                }
	});
}

function ajaxToggleFinitions(){
	$('.btn.dynscript').each(function(index, el){
		$(el).bind('click', function(e){
			var infoRel = $(this).attr('rel');
			var params = infoRel.split('_');
			var finitionLabel = $(this).attr('data-finitionlabel');
            var loader = new Loader($('#car-details'));
            loader.show(LoadingKey,false);
			callAjax({
				url: "Layout_Citroen_Finitions/toggleFinitions",
				async: false,
				data: {
					'lcvd6' : params[0],
					'gamme' : params[1],
					'finition' : params[2],
					'skin' : params[3],
					'form_page_pid':params[4]
				},
				beforeAction: function( jqXHR, textStatus ) {
					scrollTopV = $(window).scrollTop();

					$('.content').css({
						top: - scrollTopV,
						left:0
					});

					$('.container').addClass('popopen');
					$('.content').addClass('popopen');

					$('.container').css({
						height:$(window).height()
					});

                    //$('<div class="loading"><div class="circ"></div></div>').appendTo($('body'));
                    $('.loading').css({
                        width:$(window).width(),
                        height:$(window).height()
                    });

				},
				afterAction: function( jqXHR, textStatus){
					$('.container').removeClass('popopen');
					$('.content').removeClass('popopen');
					$('.content').css({
						top: 0,
						left:0
					});
					$('.container').css({
						height:'auto'
					});
					$(window).scrollTo(scrollTopV, 0);
                    //$('.loading').remove();
                    loader.hide();

					// Marquage GTM
					dataLayer.push({
						vehicleFinition : params[2],
						vehicleFinitionLabel : finitionLabel,
						event : 'click'
					});
				},
				success: function(data){
					var	tpl = $('#itemTpl').html(),
					$placeholder = $('#car-details');
					//$placeholder.html(tpl).find('.accordion').each(accordion.build);
					//$placeholder.find('.car-details'+index).html(tpl).find('.accordion').each(accordion.build);
								
					$placeholder.find('.car-details'+index).html(tpl).find('.accordion').each(function(){
						new Accordion($(this));
					});
					ajaxCaracteristiquesFinitions();
					var $box_lvl2 = $placeholder.find('.box-lvl2');
					$box_lvl2.find('.accordion').show();
					var position = $("#sticky").offset().top;
					$('html, body').animate({scrollTop:position}, 0);
				}
			});
		});
	});
}

function ajaxCaracteristiquesFinitions(){
	$('select[name=equipe]').on('change',function(){
		var valueCarac = $(this).val();
		var params = valueCarac.split('_');
		var motorLabel = $(this).find('option:selected').text();
		displayCaracteristiquesFinitions(params[0], params[1], params[2], params[3]);

		// Marquage GTM
		dataLayer.push({
			vehicleMotor : params[0],
			vehicleMotorLabel : motorLabel,
			event : 'click'
		});
	});
}

function displayCaracteristiquesFinitions(engine_code, finition, lcvd6, gamme){
	$box_lvl2 = $('.box-lvl2');
    var loader = new Loader($('#caracteristiques'));
    loader.show(LoadingKey,false);
	callAjax({
		url: "Layout_Citroen_Finitions/caracteristiquesFinitions",
		async: false,
		data:	{
		   'engine_code' : engine_code,
		   'finition' : finition,
		   'lcvd6' : lcvd6,
		   'gamme' : gamme
		},
		 beforeAction : function( jqXHR, textStatus ) {

                    scrollTopV = $(window).scrollTop();
                    $('.content').css({
                        top: - scrollTopV,
                        left:0
                    });

                    $('.container').addClass('popopen');
                    $('.content').addClass('popopen');

                    $('.container').css({
                        height:$(window).height()
                    });

                    //$('<div class="loading"><div class="circ"></div></div>').appendTo($('body'));
                    $('.loading').css({
                        width:$(window).width(),
                        height:$(window).height()
                    });
                },
                afterAction : function( jqXHR, textStatus)
                {

                $('.container').removeClass('popopen');
                $('.content').removeClass('popopen');
                $('.content').css({
                    top: 0,
                    left:0
                });

                $('.container').css({
                    height:'auto'
                });
                 $(window).scrollTo(scrollTopV, 0);

                //$('.loading').remove();
                loader.hide();

                },
		success: function(){
			$box_lvl2.find('.accordion').show();
			$('.accordion', $box_lvl2).each(function(){
				new Accordion($(this));
			});
		}
	});
}
//Méthode servant au binding le clic sur le bouton permettant d'afficher plus de vehicules neufs
function maskBtnComparateur(){
	$(".compareBtn").each(function(e){
		if($("input[name=trancheComparateur]").length == 0){
			$(this).css('display','none');
		}
	});
}
function fctConnexion() {
	// Connexion reseaux sociaux
	$('.step .share-btns ul li a, .social-connect .btns span, .social-connect .btns-text span').bind('tapone', function(e) {
		e.preventDefault();
		var _url = $(this).data('url');
		window.open(_url,'login');
	});
}

$('a.connection-btn').bind('click, tapone', function(e){
	var _url = "/_/User/openid";
	window.open(_url,'login');
	//$('.citroenid-block-popin iframe').attr('src', _url);
});


/**Formulaires*/

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


function initFormulaire()
{
    $('div.request-form').each(function(){
        var formActivation = $(this).find('input[name=formActivation]').val();
        var typeFormulaire = $(this).find('input[name=typeFormulaire]').val();
        var typeDevice = $(this).find('input[name=typeDevice]').val();
        var idDiv =  $(this).attr('id');
        var InceCode = $(this).find('input[name=InceCode]').val();
        var lcdvForm = $(this).find('input[name=lcdv6Form]').val();
        isDeployed = $(this).find('input[name=deployed]').val();
		var ippId = $(this).find('input[name=ppid]').val();
		var formTypeLabel = $(this).find('input[name=formTypeLabel]').val();

        var formEquipCode =  $(this).find('input[name=EQUIPEMENT_CODE]').val();
        var formIDType =  $(this).find('input[name=TYPE_ID]').val();
        var formUserCode =  $(this).find('input[name=USER_TYPE_CODE]').val();
        var contextForm = "";
        if(formActivation != 'CHOIX'){
          if(lcdvForm)
          {
            contextForm = "CAR";
        }
        if ($("#isPDV") && $("#isPDV").val() == 'RTO')
        {

            contextForm = "RTO";
            InceCode = getINCEForm(formIDType, formUserCode, formEquipCode, contextForm, InceCode);

        }

        getFormId(InceCode, formActivation,idDiv,lcdvForm, null, formTypeLabel, isDeployed, contextForm,ippId);
    }

    $('.nextStepForm'+idDiv).on('click', function(e){
        e.preventDefault();
        var div = $(this).attr('rel');
        var typeClient = $('div.'+div).find('input[name=typeClient]:checked').val();
        if(typeof(typeClient) != 'undefined'){
          if(lcdvForm)
          {
            contextForm = "CAR";
        }

        if ($("#isPDV") && $("#isPDV").val() == 'RTO')
        {
            contextForm = "RTO";
            InceCode = getINCEForm(formIDType, formUserCode, formEquipCode, contextForm, InceCode);
        }
		$('#'+idDiv+'').hide();
        callFormulaire(typeFormulaire,typeClient,typeDevice,idDiv,lcdvForm, isDeployed, contextForm,ippId);
    }
});
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
       InceCode = data['FORM_INCE_CODE'];
        }
    });
    return InceCode;
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
    var isMSIE = /*@cc_on!@*/0; //test pour déterminé si IE
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





function loadFormsParameters() {
				
	var lcdvForm = $('input[name=lcdv6Form]').val();
	var formTypeGTM = $('input[name=formTypeGTM]').val();
	var isGeocodeActive = $('input#isGeocodeActive').val();
	var country = $('input[name=CODE_PAYS]').val();
          	
	if(country == 'FR' || country == 'BE' || formTypeGTM == 'pre-lead' ){
		var sAutoFill = {'GIT_TRACKING_ID': getGITID(),'TESTDRIVE_CAR_LCDV': lcdvForm};
	}else{
		var sAutoFill = {'GIT_TRACKING_ID': getGITID()};
	}
	
	

	lcdvFormContext = Array();
	if(lcdvForm.length > 0){
		lcdvFormContext = [lcdvForm]; 
	}

	
	
	new citroen.webforms.WebFormsFacade({
		source: '/dcr/prm/getinstancebyid?instanceid='+formParams.instance+'&culture='+formParams.culture,
		returnURL: '',
		dealerLocatorFluxType: 'dealerdirectory2',
		target: 'wf_form_content',
		siteGeo : isGeocodeActive,
		autoFill: sAutoFill,
		carPickerPreselectedVehicles: lcdvFormContext,
		brochurePickerPreselectedVehiclesLcdv: lcdvFormContext,
		brochurePickerPreselectedVehicles: [],
		onPostAjaxSuccess: function(datas) {
			//$('div#wf_form_content').hide();
		   finalStepFunction(datas,formParams.idframe, formParams.instance,formParams.typeLabel);
		},
		onPostAjaxFailure: function() {
			alert("Erreur technique lors de l'enregistrement du formulaire");
		},
		onPostAjaxError: function(datas) {
			console.log(datas,'onPostAjaxError');
			alert("Certaines donness du formulaire sont invalides");
		}					
	});
	
	 form_load_html();
	// Contextualisation des parametres du moteur
	citroen.webforms.parameters.contextualize(formParams);
}	

 function form_load_html() {
         if (typeof $('li.wf_active').html() === 'undefined')
        {
            window.setTimeout(form_load_html, 100);	
        }
        else
        {
			$('li.wf_active a').trigger('click');
			$('div.wf_resume_img img').css("min-width", "0%");
        }
    }

function getFormId(InceCode, formActivation, idIframe, lcdvForm, email, formTypeLabel, isDeployed, contextForm,ippId){

  
	$('div#wf_form_content').remove();
	
    if (typeof (InceCode) == "string" && InceCode != '') {
        if (typeof formTypeLabel == 'undefined' || formTypeLabel == '') {
            formTypeLabel = '';
        }
		var country = $('input[name=CODE_PAYS]').val();
		var lang = $('input[name=LANGUE_CODE]').val();
		var typeDevice =  $('input[name=typeDevice]').val();
		 var iFormPageId = $('input[name=form_page_pid]').val();
		
		if(typeDevice == 'WEB'){
			contextdevice = 'desktop';
			brandconnector = 'pc';
		}else{
			contextdevice = 'mobile';
			brandconnector = 'mobile';
		}
		
		
		formParams = {
				brand:        'ac',               // Marque [ap, ac, ds] en minuscule
				lang:         lang,               // Code ISO de la Langue (en)
				country:      country,               // Code ISO du Pays (GB) 
				culture:      lang+'-'+country,            // Culture (en-GB, nl-BE pour le Neerlandais en Belgique)
				instance:     InceCode, // Numero d'nstance du formulaire (16 caracteres)
				context :     contextdevice,          // desktop ou mobile
				brandidConnector: brandconnector,       // pc ou mobile ou driveds
				otherCss:     [],                 // Liste de CSS additionnels
				GammeSource: 'CPP',                // Source de la Gamme des Vehicules et Brochures (CPP ou GDG)
				environment: '' ,// Environnement (DEV, RECETTE, PREPROD, PROD)
				idframe:        idIframe,  
				typeLabel:       formTypeLabel,  
				contextFormDeploy:   contextForm
			};
			
		  
			  $("<div>", {class: "wf_form_content",id:"wf_form_content"}).insertBefore('#'+idIframe+''); 
		  
            // Chargement du moteur
            $(window).load(loadFormsResources(formParams.context));
		 
        // var iFormPageId = $('input[name=form_page_pid]').val();
        // var styleForm = $('input[name=' + idIframe + 'styleForm]').val();
        // var formClass = $("body").attr('class');
        // var iPageId2 = $('input[name=form_page_pid]').val();
        // var url = "/forms/" + formTypeLabel + "/Layout_Citroen_Formulaire/iframe?version=2&idform=" + InceCode + "&typeform=" + formActivation + "&section=" + idIframe + "&lcdv=" + lcdvForm + "&email=" + email + "&formClass=" + formClass + "&styleForm=" + styleForm + "&isDeployed=" + isDeployed + "&form_page_id=" +iFormPageId+"&contextForm="+contextForm;
      

    }
}

// function getFormId(InceCode, formActivation, idIframe, lcdvForm, email, formTypeLabel, isDeployed, contextForm,ippId)
// {
    // var styleForm = $('input[name=' + idIframe + 'styleForm]').val();
    // var formClass = $("body").attr('class');
    // formClass = formClass.replace("script","");
    // formClass = $.trim(formClass);
	// var ippId = $('input[name=ppid]').val();
    // var url = "/_/Layout_Citroen_Formulaire/iframe?version=2&idform=" + InceCode+ "&typeform=" + formActivation+"&section="+idIframe+"&lcdv="+lcdvForm + "&formClass="+ formClass + "&styleForm=" + styleForm + "&isDeployed=" + isDeployed + "&ppid=" + ippId + "&contextForm="+contextForm;

   // $("#"+idIframe).html('<iframe id="iframe'+idIframe+'" src="'+url+'" style="min-height:100px; width: 100%; margin: 0px; padding: 0px; clear: both; display: block" scrolling="no" frameborder="no"></iframe>');
    // setInterval(function() {ResizeIframeFromParent('iframe'+idIframe);}, 1000);
    // var loader = new Loader($('#'+idIframe));
    // loader.show(LoadingKey,false);
    // IframeId = 'iframe' + idIframe;
    // ResizeIframeFromParent2('iframe' + idIframe);
    // $('#'+idIframe).slideDown( "slow" );
    // $('#iframe' + idIframe).load(function(){
        // loader.hide();
    // });
// }
function callFormulaire(typeFormulaire,typeClient,typeDevice,idSection,lcdvForm, isDeployed, contextForm,ippId)
{

    callAjax({
        url: '/_/Layout_Citroen_Formulaire/getContenu',
        async: false,
        data:	{
            'typeFormulaire' : typeFormulaire,
            'typeClient' : typeClient,
            'typeDevice' : typeDevice,
            'idSection' : idSection,
            'lcdvForm' : lcdvForm,
            'isDeployed' : isDeployed,
            'contextForm' : contextForm,
			'ppid' : ippId
        }
    });
}
function finalStepFunction(dataForm,idSection,idForm)
{
    var idPage = $('input[name='+idSection+'idPage]').val();
    var zoneOrder = $('input[name='+idSection+'zoneOrder]').val();
    var areaId = $('input[name='+idSection+'areaId]').val();
    var zoneTid = $('input[name='+idSection+'zoneTid]').val();
    var isDeployed = $('input[name='+idSection+'deployed]').val();
	var ippId = $('input[name='+idSection+'ppid]').val();
	
    var params = {};
    var arDatas = dataForm.message.split('&');
    var loader = new Loader($('#'+idSection));
    loader.show(LoadingKey,false);
    arDatas.forEach(function (part) {
        var pair = part.split('=');
        pair[0] = decodeURIComponent(pair[0]);
        pair[1] = decodeURIComponent(pair[1]);
        params[pair[0]] = (pair[1] !== 'undefined') ?
            pair[1] : true;
    });
	
    $.ajax({
        url: '/_/Layout_Citroen_Formulaire/finalStep',
        async: false,
        type : 'POST',
        data:	{
            'params' : params,
            'idPage' : idPage,
            'areaId' : areaId,
            'zoneTid' : zoneTid,
            'zoneOrder' : zoneOrder,
            'isDeployed' : isDeployed,
            'idForm' : idForm,
			'ppid':ippId
        },
        success: function(data){
            $('.'+idSection+'Chapo').hide();
            // $('#'+idSection).html(data);
			$('#wf_form_content').html(data);
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
		item = $('.selection-vehicules article:not(.bx-clone):nth-child('+(idx+1)+')');
		item.html(retour);
		new SelectFeeder(item.find('.selectfeeder'));
	});
}

function setVehiculeActif(idx) {
	callAjax({
		url: 'Layout_Citroen_MonProjet_SelectionVehicules/setVehiculeActif',
		data: {
			'idx': idx
		}
	});
}

function saveVehicule(idx) {
	order = idx-1;
	vehicule = '';
	for (i=1; i<=3; i++) {
		elem = $('article:not(.bx-clone) #mp-v-form_' + idx + ' select.select' + i + ' option:selected').val();
		if (elem) {
			if (vehicule != '') { vehicule += '|'; }
			vehicule += elem;
		}
	}
	callAjax({
		url: 'Layout_Citroen_MonProjet_SelectionVehicules/addToSelectionAjax',
		data: {
			'order': order,
			'vehicule': vehicule
		},
		type: 'post',
		success: function() {
			document.location.reload();
		}
	});
}
function focusFinition() {
	if ($('section.finitions').length >0) {
		idxFinition = null;
		$('section.finitions article:not(.bx-clone)').each(function(index){
			if ($(this).find('span.check-label').hasClass('checked')) {
				idxFinition = index;
			}
		});
		if (idxFinition) {
			$('section.finitions .bx-pager .bx-pager-item a[data-slide-index='+idxFinition+']').trigger('click');
		}
	}
}

function focusVehicule() {
	if ($('section.selection-vehicules').length >0) {
		idxVehicule = null;
		$('section.selection-vehicules article:not(.bx-clone)').each(function(index){
			if ($(this).data('actif') != '') {
				idxVehicule = index;
			}
		});

		if (idxVehicule) {
			$('section.selection-vehicules .bx-pager .bx-pager-item a[data-slide-index='+idxVehicule+']').trigger('click');
		}
	}
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
        		
        		
        		$('#edge-modal').css({"position": "absolute", "top": 45, "right": 55});
				$('#edge-modal').fadeIn(300, function(){$(this).focus();});
				$closeModal	= $('#edge-modal').find(".close");
				
				if ($closeModal.length){
					$closeModal.on("click", function(e){
						var _this = $(this);
							_this.closest("#edge-modal").fadeOut("slow");
					});
				};
        	}
        	else{
        		$('.retour_ajax').show();
        		$('.retour_ajax').html(e['message']);
        	}
        }
       
    } );

	return false;
}

function resize_iframe(iframe) {
	var iframeid = iframe.id;
	//find the height of the internal page
	var the_height= document.getElementById(iframeid).contentWindow.document.body.scrollHeight;
	//change the height of the iframe
	document.getElementById(iframeid).height=the_height;
	$('div.loading').remove();
} 
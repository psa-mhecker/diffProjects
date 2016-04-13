var form_changed = false;
var titleDialog = 'Gestion de la personalisation';
var idToScroll;
var dialogWidth = 900;
var dialogHeight = 570;
var dialogX;
var dialogY;


var maxTabInit;
var maxTab;

$(document).ready(function(){

    bindingPersoDialog();
    
});

function bindingPersoDialog(){
    dialogX = (window.innerWidth - dialogWidth)/2;
    dialogY = (window.innerHeight - dialogHeight)/2;
    var heightIframe = window.parent.$("iframe#iframeRight").height();
    var widthIframe = window.parent.$("iframe#iframeRight").width();


    if(heightIframe  < dialogHeight){
        dialogHeight = heightIframe - 100;
    }
     if(widthIframe  < dialogWidth){
        dialogWidth = widthIframe - 100;
    }

    dialogX = (window.innerWidth - dialogWidth)/2;
    dialogY = (window.innerHeight - dialogHeight)/2;
    
    var dialogExtendOptions = {
            "close" : true,
            "maximize" : true,
            "minimize" : true,
            "maximizedHeight" : heightIframe
        };

 
    $("#dialog").dialog({
        autoOpen: false,
        modal: true,
        height: dialogHeight,
        width: dialogWidth,
        minWidth: 200,
        minHeight: 200,
        resizable: true,
        draggable: true,
        dialogClass:'dialog_perso',
        title : titleDialog,
         resize: function() { setPositionTabs(); },
         open: function(){form_changed = false;},
         beforeClose: function(event, ui) {
            if ($(this).data('propagationStopped')) {
        
                    $(this).data('propagationStopped', false);
                    $(this).html('');
                     callback(true);
                    return true;
                } else {
             if( form_changed){
                 
                    event.stopImmediatePropagation();
                    event.preventDefault()
                    $("#dialog-confirm").html($('.closePerso').attr('data-confirmText'));
                    var confirmDialogWidth =400;
                    var confirmDialogHeight = 200;
                    var confirmDialogX = (window.innerWidth - confirmDialogWidth)/2;
                    var confirmDialogY = (window.innerHeight - confirmDialogHeight)/2;
       
                   
                    // Define the Dialog and its properties.
                    $("#dialog-confirm").dialog({
                        resizable: false,
                        modal: true,
                        title: "Confirmation de fermerture",
                        height: confirmDialogHeight,
                        width: confirmDialogWidth,
                        position: [confirmDialogX,confirmDialogY],
                        open: function(event, ui) {  $(this).parent().focus(); },
                        buttons: {

                             "Annuler" : function () {
                                $(this).dialog('close');
								$('#IS_PERSO').val('1');
                              return false;
                             },
                           "OK":  function () {
									$('#IS_PERSO').val('0');
                                    $(this).dialog('close');

                                    if(idToScroll){
                                       $('body').animate({scrollTop: $("#togglezone"+idToScroll).offset().top}, 'slow');
                                    }

                                    $('#dialog').data('propagationStopped', true);
                                    $('#dialog').dialog('close');
                                   return true;
				}
                         }
                    });
                   return false
                }

                $(this).html('');
                  callback(true);
            }
          
             function callback(value){
                if(value == true){
                	 
                    if(idToScroll){
                           $('body').animate({scrollTop: $("#togglezone"+idToScroll).offset().top}, 'slow');
                        }
                    }
                }
            

         },
         position: [dialogX,dialogY]
    }).dialogExtend(dialogExtendOptions);;

    $( ".persoDialog").unbind( "click" );
    $('.persoDialog').on('click',function(e){
        e.preventDefault();
        var _rel = $(this).attr("rel");
		$('#IS_PERSO').val('1');
        ajaxPerso(_rel);
        var args = _rel.split('&');
        var ztid;
        for(i=0;i<args.length;i++){
            splitArgs = args[i].split('=');
            if(splitArgs[0] == 'ztid'){
                idToScroll = splitArgs[1];
            }
       }
       

    });
    
 
    
}


function initTabsIndexation(){
    maxTab =  $('.dialog_perso .ui-tabs-nav li').length;
        
    if($('.dialog_perso .ui-tabs-nav li.tabAddPerso').length ){
        maxTabInit -= 1 ;
    }
    maxTab = maxTabInit ;
}
function tabDrop(){
    var bGeneral = $('#bGeneral').val();
    var sMultiName = $('#multiId').val();
    $('.dialog_perso #tabs').find( ".ui-tabs-nav" ).sortable({
        axis: "y",
        cancel: '.tabAddPerso',
        stop: function() {
            $(".dialog_perso #tabs ul li").each(function(index) {
                var iteration = index + 1;
                var tab = $(this).children('a').attr('href');
                if(tab){
                    var aTab = tab.split('-');
                    if(bGeneral == false){
                        var prefixe =   '#perso_'+aTab[1]+'_'+sMultiName;
                    }else{
                        var prefixe =   '#perso_'+aTab[1]+'_';
                    }
                    //$(this).children('a').html($('#profilTrad').val()+ ' ' +iteration);
                    $(prefixe+'ORDER').val(iteration);
                }
            });
            form_changed = true;
        }
    });
}

function setPositionTabs(){
    var offset = 3;
    
    // Pas de positionnement lorsqu'on utilise les onglets verticaux
    if ($('.dialog_perso').find('.ui-tabs').hasClass('ui-tabs-vertical')) {
        return;
    }
    
    if($('.dialog_perso .ui-tabs-nav') && $('.dialog_perso .ui-tabs-nav').position()){
        var positionTabs = parseInt($('.dialog_perso .ui-tabs-nav').position().top + $('.dialog_perso .ui-tabs-nav').height() + offset);
        $('.dialog_perso .ui-tabs-panel').each(function(){
            $(this).attr('style','top: '+positionTabs+'px !important;' );
        });
    }

}

function initPositionAddBtn(force_init){
    
    if($('.dialog_perso .ui-tabs-nav li').length  || force_init == true){
        var originButton = $('.dialog_perso .addPerso');
        var addBtn = originButton.clone();
        
        
        originButton.addClass('originButton').removeClass('addPerso').hide();
        addBtn.addClass('addPersoButton').removeClass('addPerso')

        $('.dialog_perso .tabAddPerso').remove();
        $('.dialog_perso .ui-tabs-nav').append(addBtn);


        addBtn.attr('value','');
        addBtn.html('');
       
        addBtn.wrap('<li  class="tabAddPerso ui-state-default "></li>');
        addBtn.unbind('click');
        addBtn.bind( "click",function(e) {
            e.preventDefault();
            addPerso();
        });

       
    }
   
}

function initButton(){
       
    $("button.addPerso").unbind('click');    
    $("button.addPerso").on( "click",function(e) {
        e.preventDefault();
        addPerso();
    });

     initPositionAddBtn();
    $("button.savePerso").on('click',function(e) {
        e.preventDefault();
		
        submitProfile();
    });
    $("button.closePerso").on('click',function(e) {
           e.preventDefault();
		   $('#IS_PERSO').val('0');
            closeDialog();
       
    });
}
function showDialog() {
    dialogX = (window.innerWidth - dialogWidth)/2;
    dialogY = (window.innerHeight - dialogHeight)/2;
    //Initialisation du titre, tailles et position de la pop-in
    $("#dialog").dialog({
        
        height: dialogHeight,
        width: dialogWidth,
        title : titleDialog,
        
         position: [dialogX,dialogY]
    });
    $("#dialog").dialog("open");
    
   
}

function closeDialog() {
	$('#IS_PERSO').val('0');	
    $("#dialog").dialog("close");
}

function addLoader(){
    $("#dialog").append('<div id="loading"><span class="loaderAjax"></span></div>');
}
function delLoader(){
    $("#loading").remove();
}
function ajaxPerso(sRel) {
    showDialog();
    addLoader();
	
    var args = new Array();
    var splitArgs = new Array();
    var jsonObj = { };
    if(sRel != ''){
        args = sRel.split('&');
        for(i=0;i<args.length;i++){
            splitArgs = args[i].split('=');
            jsonObj[ splitArgs[0]] = splitArgs[1];
        }
        jsonObj['listMulti'] = $("#TRACK_MULTINAMES").val();
        if(jsonObj['general'] == true){
            jsonObj['perso'] = $('#PAGE_PERSO').val();
            $('select[name^="PUSH"] option, select[name^="PUSH_OUTILS_MAJEURS"] option, select[name^="PUSH_OUTILS_MINEURS"] option, select[name^="PUSH_CONTENU_ANNEXE"] option').not('select[name^="srcPUSH"] option, select[name^="srcPUSH_OUTILS_MAJEURS"] option, select[name^="srcPUSH_OUTILS_MINEURS"] option, select[name^="srcPUSH_CONTENU_ANNEXE"] option').each(function(){
                if($(this).parent().attr('multiple') == 'multiple'){
                    $(this).attr('selected', true);
                }
            });
            jsonObj['form'] = $('*[name^="count_PUSH"],*[name^="PUSH"], *[name^="count_PUSH_OUTILS_MAJEURS"],*[name^="PUSH_OUTILS_MAJEURS"],*[name^="count_PUSH_OUTILS_MINEURS"], *[name^="PUSH_OUTILS_MINEURS"],*[name^="count_PUSH_CONTENU_ANNEXE"], *[name^="PUSH_CONTENU_ANNEXE"]').not('*[name^="srcPUSH"], *[name^="srcPUSH_OUTILS_MAJEURS"], *[name^="srcPUSH_OUTILS_MINEURS"], *[name^="srcPUSH_CONTENU_ANNEXE"], *[name^=PUSH_subFormJS]').serialize();
        }else{
            jsonObj['perso'] = $('#'+jsonObj['multi']+'ZONE_PERSO').val();
            $('select[name^="'+jsonObj['multi']+'"] option').not('select[name^="src'+jsonObj['multi']+'"] option').each(function(){
                if($(this).parent().attr('multiple') == 'multiple'){
                    $(this).attr('selected', true);
                }
            });
            jsonObj['form'] = $('*[name^="'+jsonObj['multi']+'"], *[name*="count_'+jsonObj['multi']+'"]').not('*[name^="src'+jsonObj['multi']+'"]').serialize();
        }
        
        // Ajout index nouveaux multi en paramètre
        var tempForm = $('<form>');
        $(MultiMetadataManager.formElement).find("input[type='hidden'][name^='added_multi_index']").each(function(index, el){
            var inputClone = $(this).clone();
            inputClone.appendTo(tempForm);
        });
        jsonObj['multiMetadata'] = tempForm.serialize();
    }
    callAjax({
        url: "Cms_Page/perso",
        async: false,
        data: jsonObj,
        type: "POST",
        success: function(e){
			
            $('.fwForm').on('keyup change', 'input, select, textarea', function(){
                form_changed = true;
        
            });
             $( ".dialog_perso #tabs" ).tabs( {
               select: function(event, ui) {     
	   
                    $('#ui-dialog-title-dialog').html(titleDialog +' - '+ $(ui.tab).text())},
                });
            
            $(".dialog_perso #tabs").addClass("ui-tabs-vertical ui-helper-clearfix");
            $(".dialog_perso #tabs li").removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
			 
             initButton();
              setPositionTabs();
              //titre de la fenetre sur le premier onglet
              $('#ui-dialog-title-dialog').html(titleDialog +' - '+ $('.dialog_perso #tabs .ui-tabs-selected').text());
             delLoader();
             tabDrop();
             initTabsIndexation();
             getNewIndexTab();
        }
    });
    
}

function searchMaxIndexTabs(){
    var elt =  $("#dialog  #tabs .tabAddPerso").prev();
    var maxId = 0;
    
    while(elt.length){
        var anchor = elt.find("a"); 
        if(anchor && anchor.attr('href')){
            var index = parseInt(anchor.attr('href').split('-')[1]);
            if( index > maxId ){
                maxId = index;
            }
        }
        elt = elt.prev();
   
    }
    return maxId;
}

function getNewIndexTab(){
    /*se base sur le dernier onglet de #tabs et des variables max selon les données de la BD et les données des actions JS*/
    maxTab = searchMaxIndexTabs();

    if(maxTab < maxTabInit){
        maxTab = maxTabInit;
    }
    return maxTab+1;
}

function addPerso(){
    addLoader();
    var _zoneId = $('#zoneId').val();
    var _ztId = $('#ztid').val();
    var _multiId = $('#multiId').val();
    var _bGeneral = $('#bGeneral').val();
    var _iPid = $('#iPid').val();
    var _iTpid = $('#iTpid').val();
    var _iTypeExpand = $('#PAGE_TYPE_EXPAND').val();
    var _defaultSerialize = $('#defaultSerialize').val();
    
	
	  var _index = getNewIndexTab();
    if($("#dialog #tabs .ui-tabs-nav").html() == undefined){
        $('#dialog  #tabs').append('<ul class="ui-tabs-nav"></ul>');
        initPositionAddBtn(true);
       
    }
  
 
      
    var _elmt = _index;
    var initAddBtn = false;
    
    var _divTab = 'tabs-'+_elmt;
	
    $('<li ><a href="#'+_divTab+'">'+ $('#profilTrad').val() +'&nbsp;'+_elmt+'</a></span></li>').insertBefore('.tabAddPerso');

    $('.dialog_perso #tabs').append('<div id="'+_divTab+'"></div>');
    
    // Ajout index nouveaux multi en paramètre
    var tempForm = $('<form>');
    $(MultiMetadataManager.formElement).find("input[type='hidden'][name^='added_multi_index']").each(function(index, el){
        var inputClone = $(this).clone();
        inputClone.appendTo(tempForm);
    });
    var multiMetadata = tempForm.serialize();

    callAjax({
        url: "Cms_Page_Perso/addFormPerso",
        async: false,
        type: "POST",
        data:    {
           'zoneId' : _zoneId,
           'ztid' : _ztId,
           'multiId' : _multiId,
           'bGeneral' : _bGeneral,
           'iPid' : _iPid,
           'iTpid' : _iTpid,
           'target' : _divTab,
           'indexTab' : _elmt,
           'iTypeExpand' : _iTypeExpand,
           'defaultSerialize' : _defaultSerialize,
           'multiMetadata' : multiMetadata
        },
        success: function(e){
             form_changed = true;
			
            $(".dialog_perso #tabs").tabs( "destroy" );
            $( ".dialog_perso #tabs" ).tabs( {
               select: function(event, ui) {                               
                    $('#ui-dialog-title-dialog').html(titleDialog +' - '+ $(ui.tab).text())
                },
            });
            $('.dialog_perso .ui-tabs-panel').each(function(){
                if( $(this).hasClass("ui-tabs-hide") == false ){
                    $(this).hide();
                }
            });

             setPositionTabs();

            var indexElmt = $('.dialog_perso #tabs a[href="#'+_divTab+'"]').parent().index();
            $( ".dialog_perso #tabs" ).tabs( "select",indexElmt );
            
            delLoader();
            tabDrop();
        }
    });
    
}



function deleteTab(obj,idTab){


        $("#dialog-confirm").html(obj.getAttribute('data-title'));
    var confirmDialogWidth = 450;
var confirmDialogHeight = 200;
var confirmDialogX = (window.innerWidth - confirmDialogWidth)/2;
var confirmDialogY = (window.innerHeight - confirmDialogHeight)/2;
   
    // Define the Dialog and its properties.
    $("#dialog-confirm").dialog({
        resizable: false,
        modal: true,
        title: "Demande confirmation",
        height: confirmDialogHeight,
        width: confirmDialogWidth,
        position: [confirmDialogX,confirmDialogY],
          open: function(event, ui) {  $(this).parent().focus(); },
                  
        buttons: {

                "Annuler": function () {
                $(this).dialog('close');
                return false;
            },
            "OK": function () {
                $(this).dialog('close');
                 $( ".dialog_perso #tabs" ).tabs( "remove",idTab );
                setPositionTabs();
                form_changed = true;

                if($('.dialog_perso #tabs .ui-tabs-nav li').length == 1){
                         //retire le ul
                        $('.dialog_perso #tabs ul').remove();
                        initTabsIndexation();
                       $('.dialog_perso .originButton').removeClass('originButton').addClass('addPerso').show();
                        $('#ui-dialog-title-dialog').html(titleDialog )
                    }
                return true;
            }
        }
    });
}
function submitProfile(){
    addLoader();
    var sMultiName = $('#multiId').val();
    var bGeneral = $('#bGeneral').val();
    var zoneId = $('#zoneId').val();
    var _obj = new Array();
    var _elmt;
    var _keyTab;
    var _serializeForm;
    var sListMultiName = $("#TRACK_MULTINAMES").val();
	
    var complement = "";
    //var order = "";
    var launchAjax = true;
	
    $(".dialog_perso #tabs ul li.ui-state-default").each(function(index){
	
	
        var idTab = $(this).find('a').attr('href');
		
        if(idTab){
            var idForm = idTab.replace('#tabs-','');
            _elmt = index + 1;
            if(bGeneral == false){
                complement = sMultiName+idForm;
            }else{
                complement = idForm;
            }
            if($('#fForm'+idForm).length > 0){
                if(eval('CheckFormPerso_'+complement+'(document.getElementById("fForm'+idForm+'"))')){
                    // Sélection des éléments gauche
                    $('#fForm'+idForm).find('table.formval[summary*="Associative"] td.formval>select:eq(0)>option').prop("selected", true);
                    
                    // récuperation de l'ordre pour le tri sur les tabs
                    order = $('#perso_' + idForm + '_' + sMultiName + 'ORDER').val();
                    
                    // Récupération des champs du formulaire (pour ensuite les poster en AJAX à savePerso, pour sérialisation JSON)
                    _serializeForm = $('#fForm'+idForm).serialize();
                    _obj[order] = _serializeForm;
                    //_obj[idForm] = _serializeForm;
				
                }else{
                    launchAjax = false;
                    delLoader();
                }
            }
        }
		
			
    });
	
    if(launchAjax == true){
        callAjax({
            url: "Cms_Page_Perso/savePerso",
            async: true,
            type: "POST",
            data:    {
                'ListMultiName' : sListMultiName,
                'multiName' : sMultiName,
                'bGeneral' : bGeneral,
                'zoneId' : zoneId,
                'obj' : _obj
            },
            success: function(){
				$('#IS_PERSO').val('0');
                form_changed=false;
                closeDialog();
                initButton();
            }
        });
    }else{
		$('#IS_PERSO').val('1');
        delLoader();
    }
}
function hideProduct(choix, multi){
    switch (choix) {
        case '7':
        case '11':
        case '12':
        case '13':
        case '14':
            $('input[name='+multi+'PRODUCT_ID]').parent().show();
            $('#'+multi+'PRODUCT_ID').show();
        break; 
        default:
            $('input[name='+multi+'PRODUCT_ID]').parent().hide();
            $('#'+multi+'PRODUCT_ID').hide();
        break;
    }
 }
 


function print_this(where){
	var is_mac=(navigator.platform.indexOf("ac") != -1);
	(document.all && is_mac)? alert("Select the \"Print...\" command of the File menu") : where? where.window.print() :window.print();
}

function OpenPopup(strPageURL, i_width, i_height, str_name) {
	window.open(strPageURL, str_name, 'width='+i_width+',height='+i_height+',resizable=yes,scrollbars=yes,status=no');
}

function verife_id() {
	if (event.keyCode == 13) {
		filtre_index(document.formBouton.recherche_id);
	}
}

var lastMenu = new Object;
function menu(tid, tc, id, pid, reado, lang) {
	lastMenu["tid"] = tid;
	lastMenu["tc"] = tc;
	if (id != -2) {
		lastMenu["id"] = id;
	}

	iframe = document.getElementById("iframeRight");
	if (iframe && tid) {
		iframe.src = vIndexIframePath + "?tid=" + tid + "&tc=" + tc + (id?"&id="+id:"") + (pid?"&pid="+pid:"") + (lang?"&langue="+lang:"") + (reado?"&readO="+reado:"") + "&view=" + vView;
	}
}

function orderFolder(direction) {
	if (lastMenu["id"]) {
		document.location.href = vTransactionPath + "?form_name=page&form_path=/layout&form_action=MOVE&id=" + lastMenu["id"] + "&direction=" + direction + "&view=" + vView;
	}
}

function orderFolderHmvc(direction) {
	if (lastMenu["id"]) {
		callAjax("/Cms_Page/move", lastMenu["id"] , direction);
	}
}

function movePage(data){
        var loader =$('<span id="loader" class="jstree-loading"><ins class="jstree-icon">&nbsp;</ins>Loading ...</span>');
	$.ajax({
		type: 'post',
		data: data,
		url:"/_/Cms_Page/movePage",
                beforeSend: function(){
                    $('div#frame_left_middle').block({
                        css: {
                            border: 'none',
                            padding: '25px',
                            backgroundColor: '#000',
                            width:'100%',
                            height:'100%',
                            opacity: '.7',
                            color: '#fff',
                            cursor:'wait'
                        },
                        overlayCSS:  {
                            backgroundColor:'#fff',
                            opacity:        '0',
                            width:'100%',
                            height:'100%'
                        },
                        message: '<img src=\"/images/ajax-loader.gif\" alt=\"\"/><h1>Traitement en cours...</h1>',
                        fadeIn:  200,
                        fadeOut:  200
                        
                    });
                },
                success:function(response){
                    jQuery.jstree._focused ().refresh();
                }
                
	});
}

function DragnDropFolder(pid, from, to, position) {
	if (from && to) {
		document.location.href = vTransactionPath + "?form_name=page&form_path=/layout&form_action=DRAG&dragFrom=" + from + "&dragTo=" + to;
        }
}

function ajaxDragnDropFolder(pid, from, to, order) {
    if (from && to) {
    	callAjax("/index/ajaxDragnDropFolder", pid, from, to, order);
    }
}

function menu_gauche(viewid) {
	if (viewid) {
		document.location.href = vIndexPath + "?view=" + viewid;
	}
}

function putonline(id, status, type) {
	iframe = document.getElementById("iframeRight");
	vTransactionPath = '/_/Cms_'+ucFirst(type);
	if (id) {
		iframe.src = vTransactionPath + "?form_action=" + vOnline + "&" + type + "_STATUS=" + status + "&" + type + "_ID=" + id + "&form_workflow=" + type + "&retour=" + escape(getIFrameDocument('iframeRight').location.href);
	}

}

function ucFirst(str) {
	  if (str.length > 0) {
	    return str[0].toUpperCase() + str.substring(1).toLowerCase();
	  } else {
	    return str;
	  }
	}

function content_type(uid) {
	iframe = document.getElementById("iframeRight");
	if (uid) {
		iframe.src = vIndexIframePath + "?uid=" + uid + "&view=" + vView;
	}
}

function setRightTitle(text) {
	try{document.getElementById("frame_right_top").innerHTML = (text);}catch(e){}
}

function state(tid, sid, lib) {
	iframe = document.getElementById("iframeRight");
	if (sid) {
		iframe.src = vIndexIframePath + "?tid=" + tid + "&sid=" + sid + "&titre_workflow=" + escape(lib) + "&view=" + vView;
	}
}

function change_site(obj) {
	if (obj.value) {
		if (document.fLogin) {
			document.fLogin.submit();
		} else {
			document.fSite.SITE_ID.value = obj.value;
			document.fSite.submit();
		}
	}
}

function getIFrameDocument(aID) {
	/* if contentDocument exists, W3C compliant (Mozilla) */
	if (document.getElementById(aID).contentDocument) {
		return document.getElementById(aID).contentDocument;
	} else {
		/* IE */
		return document.frames[aID].document;
	}
}

function getElementById(obj) {
	return document.getElementById(obj);
}

function getResolution() {
	var oldwidth = getCookie('screen_width');
	var oldheight = getCookie('screen_height');
	var width = $(window).width(); if (!width) { width = $(document).width(); } // PLA20130128 : pour IE
	var height = $(window).height(); if (!height) { height = $(document).height(); } //  PLA20130128 : pour IE
	width = (width >= 830 ? width : 830);
	setCookie('screen_width', width, 30);
	setCookie('screen_height', height, 30);
	if (document.location.href.indexOf('/login') == -1) {
		if ((oldheight && oldwidth)) {
			if ((Math.abs(oldheight - height) > 10)
					|| (Math.abs(oldwidth - width) > 30)) {
				document.location.href = document.location.href;
			}
		}
		if (!oldheight) {
			document.location.href = document.location.href;
		}
	}

}

function openNewBo(view, tid, tc, id) {
	var newBoId = Number(new Date());
	window.open("http://phpfactory.dev.backend?idbo="+newBoId+"&newBo=1"+(view?'&view='+view:'')+(id?'&idItem='+id:'')+(tc?'&tc='+tc:'')+(tid?'&tid='+tid:''));
}

/**
* Fonction d'initialisation des champs "Code couleur" (on/off) dans la tranche outils.
* @param tranche Élément du DOM contenant le formulaire de la tranche (ex: #tableClassForm1). Attention, tranche doit être un objet DOM natif, pas un objet jQuery.
*/
function initTrancheCodeCouleur(tranche) {
    // Check arg
    if (typeof tranche != "object") {
        return false;
    }
    
    // Check si la tranche contient les champs code couleur
    try {
        if ($(tranche).find('.outil-code-couleur').length == 0) {
            return false;
        }
    } catch(ex) {
        return false;
    }
    
    // Sélection du champ mode d'affichage de la tranche
    var mode = $(tranche).find("select[name*='ZONE_TITRE19']");
    
    // Mise à jour de la visibilité des champs code couleur en fonction de la valeur de "Mode d'affichage"
    mode.change(function(e){
        // Les champs code couleur ne s'affichent que pour le mode d'affichage ligne C
        if (mode.val() != "C") {
            $(tranche).find(".outil-code-couleur").hide();
            return;
        } else {
            $(tranche).find(".outil-code-couleur").show();
        }
    });
    
    // Trigger change event pour actualiser la visibilité
    mode.change();
    return true;
}

jQuery(document).ready(function($){
	$('div#divMedia1').show();
    // Initialisation de toutes les tranches outil au chargement de la page
    $(".outil-code-couleur-on").each(function(index, el){
        var tranche = $(this).closest("table").get();
        initTrancheCodeCouleur(tranche);
    });
    
	
	var url       = window.location.href; 
	var arrUrl    =  url.split('?');
	var arrUrl2   = url.split('/');
	
	if(arrUrl[1]=='view=O_28'|| arrUrl2[5] == 'popup'){
	
		var site = $('#SITE_ID_NAME').val();
		
		
		jQuery('.dTreeNode').each(function( index ) {
		$( this ).hide();
		
		var name = $( this ).find('a').text();			
		var parts = site.split('-');
		
		var aFolderAllowed = $('#FOLDER_ALLOWED_ALL').val().split(',');
	
		for (var i=0;i<parts.length;i++){
			if (name.indexOf(parts[i]) !== -1 || jQuery.inArray($( this ).attr('rel'), aFolderAllowed)!==-1) {
				$( this ).show();
			}
		  }	
		});
		if(arrUrl2[5] != 'popup'){
			$('div#divMedia1').hide();
		}
		$('div.dtree').show();
		
		jQuery('.dTreeNode').click(function( index ) {
		var iDiv = $( this ).find('img').attr('id');
			if(iDiv){
				var arr = iDiv.split('_');
				$('div#ddtreeO_'+arr[1]+' div.dTreeNode').show();
			}
		});
    }else{
		$('div.dtree').show();
		$('div#divMedia1').show();
	}
	
    // Définition du champ order pour les multi, lorsqu'il n'est pas défini (au chargement de la page)
    var orderAutoFill = function(index, el){
        // Récupération de l'ordre max du groupe de multi
        var orderFieldCollection = $(el).find("input[name*='PAGE_ZONE_MULTI_ORDER']");
        var maxOrder = 0;
        orderFieldCollection.each(function(index, el){
            try {
                var valOrder = parseInt($(this).val());
                if (valOrder > maxOrder) {
                    maxOrder = valOrder;
                }
            } catch (ex) {}
        });
        
        // Remplissage des champs ordre multi qui sont vides
        $(el).find("input[name*='PAGE_ZONE_MULTI_ORDER']").each(function(orderIndex, orderEl){
            // Si le champ order est rempli, on ne change rien
            if ($(this).val() != "") {
                return;
            }
            // Ignore le multi "modèle"
            if ($(this).attr('id').match(/__CPT__/)) {
                return;
            }
            // Sinon, on remplit l'ordre
            maxOrder++;
            $(this).val(maxOrder);
        });
    };
    try {
        $("td[id*='SLIDESHOW_GENERIC_td']").each(orderAutoFill);
        $("td[id*='SLIDEOFFREADDFORM_td']").each(orderAutoFill);
        //$("td[id*='PUSH_OUTILS_MAJEUR_td']").each(orderAutoFill);
        //$("td[id*='PUSH_OUTILS_MINEUR_td']").each(orderAutoFill);
        //$("td[id*='PUSH_CONTENU_ANNEXE_td']").each(orderAutoFill);
        $("td[id*='PUSH_td']").each(orderAutoFill);
    } catch (ex) {
        console.log("Erreur lors de l'initialisation des champs order multi.");
    }
    
    // Initialisation MultiMetadataManager (utilisé pour la synchro des données perso)
    try {
        var formEl = $('form#fForm').get();
        MultiMetadataManager.init(formEl);
    } catch (ex) {
        console.log("Unable to load MultiMetadataManager (" + ex + ")");
    }
    
    // Génération des MULTI_HASH (identifiant élément multi) non défini
    try {
        $('form#fForm').find("input[type='hidden'][name$='MULTI_HASH']").each(function(index, el){
            // Ignore les multi modèle (__CPT__)
            if ($(this).attr('name').match(/__CPT__/)) {
                return;
            }
            
            // Si le champ est déjà rempli, on ne change rien
            if ($(this).val() != '') {
                return;
            }
            
            // Génération du hash & enregistrement dans le champ hidden
            var hash = generateMultiHash();
            $(this).val(hash);
            
            // Mise à jour du label du multi correspondant au champ hidden
            var multi = $(this).attr('name').replace(/_MULTI_HASH$/, '');
            var subForm = $("#" + multi + "_subForm");
            subForm.find("a.multi-hash-display").attr("title", hash).text(hash.substr(0, 7));
        });
    } catch (ex) {
        console.log("MULTI_HASH generation failed (" + ex + ")");
    }
});
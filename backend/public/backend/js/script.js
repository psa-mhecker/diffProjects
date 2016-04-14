function print_this(where) {
    var is_mac = (navigator.platform.indexOf("ac") != -1);
    (document.all && is_mac) ? alert("Select the \"Print...\" command of the File menu") : where ? where.window.print() : window.print();
}

function OpenPopup(strPageURL, i_width, i_height, str_name) {
    window.open(strPageURL, str_name, 'width=' + i_width + ',height=' + i_height + ',resizable=yes,scrollbars=yes,status=no');
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
        iframe.src = vIndexIframePath + "?tid=" + tid + "&tc=" + tc + (id ? "&id=" + id : "") + (pid ? "&pid=" + pid : "") + (lang ? "&langue=" + lang : "") + (reado ? "&readO=" + reado : "") + "&view=" + vView;
    }
}

function orderFolder(direction) {
    if (lastMenu["id"]) {
        document.location.href = vTransactionPath + "?form_name=page&form_path=/layout&form_action=MOVE&id=" + lastMenu["id"] + "&direction=" + direction + "&view=" + vView;
    }
}

function orderFolderHmvc(direction) {
    if (lastMenu["id"]) {
        callAjax("/Cms_Page/move", lastMenu["id"], direction);
    }
}

function updateSiteDefaultLangueList() {
    var list = $('#SITE_DEFAULT_LANGUAGE');
    var value = $('#SITE_DEFAULT_LANGUAGE option:selected').val();
    list.empty();
    $('#assoc_langue_id').find('option').clone().appendTo('#SITE_DEFAULT_LANGUAGE');
    $('#SITE_DEFAULT_LANGUAGE').val(value);
}

function movePage(data) {
    var loader = $('<span id="loader" class="jstree-loading"><ins class="jstree-icon">&nbsp;</ins>Loading ...</span>');
    $.ajax({
        type: 'post',
        data: data,
        url: "/_/Cms_Page/movePage",
        beforeSend: function () {
            $('div#frame_left_middle').block({
                css: {
                    border: 'none',
                    padding: '25px',
                    backgroundColor: '#000',
                    width: '100%',
                    height: '100%',
                    opacity: '.7',
                    color: '#fff',
                    cursor: 'wait'
                },
                overlayCSS: {
                    backgroundColor: '#fff',
                    opacity: '0',
                    width: '100%',
                    height: '100%'
                },
                message: '<img src=\"/images/ajax-loader.gif\" alt=\"\"/><h1>Traitement en cours...</h1>',
                fadeIn: 200,
                fadeOut: 200

            });
        },
        success: function (response) {
            jQuery.jstree._focused().refresh();
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
    vTransactionPath = '/_/Cms_' + ucFirst(type);
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
    try {
        document.getElementById("frame_right_top").innerHTML = (text);
    } catch (e) {
    }
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
    var width = $(window).width();
    if (!width) {
        width = $(document).width();
    } // PLA20130128 : pour IE
    var height = $(window).height();
    if (!height) {
        height = $(document).height();
    } //  PLA20130128 : pour IE
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
    window.open("http://phpfactory.dev.backend?idbo=" + newBoId + "&newBo=1" + (view ? '&view=' + view : '') + (id ? '&idItem=' + id : '') + (tc ? '&tc=' + tc : '') + (tid ? '&tid=' + tid : ''));
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
    } catch (ex) {
        return false;
    }

    // Sélection du champ mode d'affichage de la tranche
    var mode = $(tranche).find("select[name*='ZONE_TITRE19']");

    // Mise à jour de la visibilité des champs code couleur en fonction de la valeur de "Mode d'affichage"
    mode.change(function (e) {
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

// Initialisation de toutes les tranches outil au chargement de la page
jQuery(document).ready(function ($) {
    $(".outil-code-couleur-on").each(function (index, el) {
        var tranche = $(this).closest("table").get();
        initTrancheCodeCouleur(tranche);
    });

    function escapeId(str) {
       return str.replace(/(:|\.|\[|\])/g,'\\$1');
    }
    zoneCit.initNewZone();
    zoneCit.refreshZone();
    // bouton d'ouverture du media editor
    $('.js-media-editor').live('click', function (e) {
        // on désactive le click normal
        e.preventDefault();
        var $link = $(this);
        var $caller =  $('#'+escapeId($link.data('caller-id')));
        var img = new Image();
        var width = 300;
        var height = 400;
        var path = $caller.attr('href');
        if( $caller.data('original')) {
            path=  $caller.data('original');
        }
        var parser = document.createElement('a');
        parser.href= path;
        window.mediaeditor = new Object();
        $(img).on('load', function(){
            width = (img.width < width?width:img.width);
            height = (img.height < height?height:img.height);
            //ouverture de la popin de l'editeur
            window.open($link.data('path') + '/media_editor.php?path=' + parser.pathname + '&format=' + $link.data('format') , 'editor', 'width='+parseInt(width+50)+',height='+parseInt(height+200)+',top=0,left=0,menubar=0,status=1,titlebar=0,toolbar=0,resizable=1,scrollbars=1');
            // enregistrement d'un callback a executé a la fermeture de la fenetre
        });
        img.src = path;

        window.mediaeditor.callback = function(path, original) {
            var now = new Date().getTime();
            $caller.attr('href', path);
            $caller.data('original', original);
           $caller.find('img').attr('src', path+'?t='+now);

        }
    })

});

(function ($, win, doc, zoneCit) {
    /* Zone Module Functions */
    var zoneModule = function (data) {
        this.init(data)
        jQuery.extends(this, new zoneModule[data['zone_code']](data));
    };


    zoneModule.prototype = {
        init: function (data) {
            this.zone_id = data['zone_id'];
            if (this.zone_id == undefined) {
                return false
            }
            this.$root = data.root;

            if (this.$root.attr('data-uniq-uid') == undefined) {
                this.$root.attr('data-uniq-uid', this.$root.find('[data-uniq-uid]').attr('data-uniq-uid'));
            }
            this.$root.attr('data-zone-code', data['zone_code']);

        }

    }
    zoneModule.RefreshPC78 = function (data) {
        ;

    };

    zoneModule.PC78 = function (data) {
        
        this.initialize(data)
    };
    zoneModule.PC78.prototype = {
        initialize: function (data) {
            var oThis = this;

            this.$root = data.root;
            this.$root.on('refreshZonePC78', function () {
                oThis.refresh();
            });
            this.$root.find('.buttonmulti').on('click', function () {
                oThis.refresh();
            });
        },
        refresh: function () {
            var oThis = this;

            var obj = oThis.$root;
            var select = $(oThis.$root).find('.NDP_ASSOCIATED_SLICE78 select');

            var compteur = 1;
            var newOptions = {'': ''};
            while (obj = oThis.getNext(obj)) {

                var position = obj.index() + 1;
                newOptions[ $('#PAGE_ID').val() + '.' + $('#LANGUE_ID').val() + '.' + $('select#TEMPLATE_PAGE_ID').val() + '.' + obj.attr('data-area_id') + '.' + obj.attr('data-zone_id') + '.' + obj.attr('data-uniq-uid')] = 'N°' + position + ' ' + obj.find(".zonetype1").text();
                compteur += 1;
            }
            select.each(function () {

                $(this).attr('data-prev-value', $(this).val());

            });

            $('option', select).remove();
            $.each(newOptions, function (val, text) {
                select.append($("<option/>", {
                    value: val,
                    text: text
                }));
            });

            if (oThis.$root.attr('data-zone_id') === '819') {
                /***
                 * Selection unique dans toute la tranche
                 */
                select.focus(function () {
                    var sf = $(this);
                    sf.data('prev-value', sf.val());
                }).change(function () {
                    if(typeof window.parent.showLoading == "function"){
                        window.parent.showLoading('div#frame_right_middle', true);
                    }
                    var value = $(this).val();
                    var prev_value = $(this).data('prev-value') || '';
                    var id = $(this).attr('id');
                    var current_select_id;
                    var zonesInfo = value.split('.');
                    var displayZone = $('#MULTI_ZONE_' +zonesInfo[zonesInfo.length - 3]+'_'+ zonesInfo[zonesInfo.length - 1] + '_DISPLAY_ON_FO');
                    displayZone.val(0);
                    $(oThis.$root).find('.NDP_ASSOCIATED_SLICE78 select').each(function () {
                         current_select_id = $(this).attr('id');
                        $(this).children('option').each(function () {
                            if (($(this).attr('value') === value) && $(this).attr('value') !== '' && id != current_select_id) {
                                $(this).attr('disabled', true);
                            } else if ( $(this).attr('value') == prev_value) {
                                $(this).removeAttr('disabled');
                            }
                        });
                         if(  id == current_select_id && value != prev_value){
                                var zonesInfoToDisplay = prev_value.split('.');
                                var zoneToDisplay = $('#MULTI_ZONE_' +zonesInfoToDisplay[zonesInfoToDisplay.length - 3]+'_'+ zonesInfoToDisplay[zonesInfoToDisplay.length - 1] + '_DISPLAY_ON_FO');
                                 zoneToDisplay.val(1);
                            }
                    });
                    if(typeof window.parent.showLoading == "function"){
                        window.parent.showLoading('div#frame_right_middle', false);
                    }
                });
            }

            select.each(function () {
                $(this).val($(this).attr('data-prev-value')).change();
            });

        },
        getNext: function (obj) {
            var oThis = this;

            var next = obj.next();
            if (next.length == 0 || next.attr('data-zone-code') == oThis.$root.attr('data-zone-code')) {
                return null;
            }
            if (next.attr('data-zone-code') == undefined) {
                return oThis.getNext(next);
            }

            return next;
        }

    };

    zoneCit.initNewZone = function () {
        $('[data-zone_id]:not(.draggable)').each(function () {
            $(this).trigger('initZone');
        });
    };

    zoneCit.refreshZone = function () {
        $('[data-zone-code=PC78]:not(.draggable)').each(function () {
            $(this).trigger('refreshZonePC78');
        });

    }
    $(document).on('initZone', '[data-zone_id]:not(.draggable)', function (e) {
        var $this = $(this);
        if ($this.attr('data-zone-code') == undefined && $this.attr('data-zone_id') != undefined) {
            try {
                var zone_id = $this.attr('data-zone_id');


                var oData = {
                    'zone_id': zone_id,
                    'zone_code': null,
                    'root': $this
                };

                switch (zone_id) {
                    case '819' :
                        oData['zone_code'] = 'PC78';
                        break;
                    case '788' :
                        //oData['zone_code'] = 'PN13';
                        oData['zone_code'] = 'PC78';
                        break;
                    default:
                        oData['zone_code'] = 'RefreshPC78';
                        break;
                }

                if (oData['zone_code'] != null) {

                    new zoneModule(oData);
                }


            } catch (err) {
            }

        }
    });


})(jQuery, window, document, window.zoneCit = window.zoneCit || {});
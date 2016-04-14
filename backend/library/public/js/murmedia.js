function updateStructure()
{

    $('.structure').each( function(idx, elm) {
        // supression des structure vide
        var $s = $(elm);
        var nbImageFound = 0;
        $s.find('.image-value').each(function(num, img) {
            if($(img).val() > 0) {
                nbImageFound++;
            }
        });
        if (nbImageFound == 0)   {
            $s.remove();
            return
        }
        var basename = $s.data('name');
        $s.find('.image-value').each( function(num,img) {
            $(img).attr('name', basename+'[ID]['+num+']');
        });
        $s.find('.structure-order').val(idx);
    });

}

function mediatPathWithFormat(path, format) {

    var lastDotpos = path.lastIndexOf('.');

    return path.substring(0,lastDotpos)+'.'+format+path.substring(lastDotpos)+'?autocrop=1'
}

function refrehMurMediaJs(newconfig) {
    var multi = newconfig.multi;
    $('#mur-media-'+multi).removeClass('mur-media-loaded');
    // on vide les strutures
    $('#structure-'+multi +' ol').html('').droppable('destroy');
    // on vide la liste des images;
    $('#image-'+multi+' .list-images').html('');
    $('#catalogue-'+multi+' li').draggable( 'destroy' );
    $('#structure-'+multi+' ol').sortable('destroy');
    $('.structure .delete').die('click');
    $('.image-container .add-image').off('click');
    var config = $('#mur-media-'+multi).data('config');
    $.extend(config, newconfig );
    initMurMedia(config);
}

function initMurMedia(config) {
    var multi = config.multi;
    // je ne sais pas pourquoi mais le JS est appelé 2 fois donc on met un check pour ne pas faire le taff 2 fois
    if($('#mur-media-'+multi).hasClass('mur-media-loaded')) {
        return void(0);
    }
    // sauvegarde de la config
    $('#mur-media-'+multi).data('config', config);
    $('#mur-media-'+multi).addClass('mur-media-loaded');
    // fin check
   var  content,
        nbImage             = 0,
        nbStructure         = 0,
        $tplMedia           = $('#tpl-media-'+multi),
        $tplVideo           = $('#tpl-video-'+multi),
        $tplMediaEmpty      = $('#tpl-empty-media-'+multi),
        $image              = $('#image-'+multi+' .list-images'),
        $structureContainer = $('#structure-'+multi+' ol')
       ;
    // catalogue de structure
    $('#catalogue-'+multi+' li').draggable({
        revert: false,
        helper: "clone",
        connectToSortable: $structureContainer
    });

    // permet l'ajout d'image a la liste des images
    $('#image-'+multi+' .list-images').droppable({
        accept: ".media-model",
        drop: dropMedia

    });

    // remplir le tableau d'image
    if(config.medias.length == 0) {
        $image.html($('#error-no-medias').text());
    }
    for(var idx in config.medias) {
        var media = config.medias[idx];
        if(media.MEDIA_TYPE_ID =='image') {
            content = $tplMedia.html().replace(/\{url\}/g, media.MEDIA_PATH)
                .replace(/\{name\}/g, media.MEDIA_TITLE)
                .replace(/\{id\}/g, media.MEDIA_ID)
                .replace(/\{width\}/g, media.MEDIA_WIDTH)
                .replace(/\{height\}/g, media.MEDIA_HEIGHT)
                .replace(/\{multi\}/g, multi)
                .replace(/\{compteur\}/g, nbImage++);
        }
        if(media.MEDIA_TYPE_ID =='streamlike') {
            content = $tplVideo.html().replace(/\{url\}/g, media.MEDIA_URL)
                .replace(/\{cover\}/g, media.MEDIA_PATH)
                .replace(/\{name\}/g, media.MEDIA_TITLE)
                .replace(/\{id\}/g, media.MEDIA_ID)
                .replace(/\{multi\}/g, multi)
                .replace(/\{msgAlertVideo\}/g, config.msgAlertVideo)
                .replace(/\{compteur\}/g, nbImage++);
        }
        $(content).appendTo($image);
    }
    $('#image-'+multi+' .media-model').draggable({
        helper: 'clone',
        appendTo: 'body',
        start: function( event, ui ) {
            var $draggable = $(ui.helper[0]);
            if($draggable.hasClass('video-model')) {
                $structureContainer.find('.ndp_square').addClass('drop-disabled').append('<div class="drop-disabled-overlay" />');
            }
        },
        stop: function( event, ui ) {
            $structureContainer.find('.ndp_square').removeClass('drop-disabled');
            $structureContainer.find('.drop-disabled-overlay').remove();
        }
    });
    // pemettre aux structure d'etre trié
    $structureContainer.sortable({
        placeholder:"structure-placeholder",
        stop: function(ev, ui) {
            var $draggable = $(ui.item[0]);
            if($draggable.hasClass('structure-model')) {

                var $structure  = createStructure($draggable.data('id'));
                // on remplace l'objet dropper par la structure créer
                $draggable.replaceWith($structure);
            }
        }
    });

    // permet de supprimer une structure
    $('.structure .delete').live('click',function(e){
        e.preventDefault();
        var $structure = $(this).parents('.structure');
        //on deplace toute les image de la structure dans la liste d'image
        $structure.find('.media-model').each(function(idx,elm){
            var $im = $(elm);
            var ui = {};
            ui.draggable = [$im.get(0)];
            dropMedia.call($image ,{}, ui);
        });
        //on supprime la structure
        $structure.remove();
    });

    // permet d'ajouter une image a la liste
    $('.image-container .add-image').on('click',function(e){
        e.preventDefault();
        addImage();

    });


    // reremplissage des structures
    var $struc, struc, $im, idx, key;
    for(idx in config.structuresValues) {

        struc = config.structuresValues[idx];
        $struc = addStructure.call( $structureContainer, 'tpl-'+struc['PAGE_ZONE_MULTI_VALUE']+'-'+multi);
        var keys = ['MEDIA_ID','MEDIA_ID2','MEDIA_ID3','MEDIA_ID4','MEDIA_ID5','MEDIA_ID6'];
        for(key in keys) {
            if (struc[keys[key]]) {
                $im = $('#image-'+multi+'-'+struc[keys[key]]);
                var ui = {};
                ui.draggable = [$im.get(0)];
                dropMedia.call($struc.find('li:eq('+key+')') ,{}, ui);
            }
        }


    }
    hookCheckForm(multi);

    function cropImage($slot, $draggable) {
        var img = new Image(),
            $original = $($draggable).find('.original-img a'),
            compteur = $original.data('compteur'),
            // chemin image original
            original = $original.data('original'),
            // on recherche le template des bouton de crop pour le slot courant de la structure
            $cropTpl = $('#'+$slot.data('container-id'));

        // si on le trouve pas on sort (pas dans une structure)
        if($cropTpl.length < 1) {
            console.log('tpl not found');
            return ;
        }
        // on récupere le receptacle des boutons de crops
        var $cropContainer = $draggable.find('.container-block');
        // on supprime les boutons de crop existant
        $cropContainer.html('');
        // on remplace le variable dans le template
        var html = $cropTpl.html().replace(/\{compteur\}/g,compteur).replace(/\{size\}/g,$original.data('size'));
        // on insere les outils de crop dans le slot en cours
        $cropContainer.html(html);
        // on va mettre les images dans chacun des outils de crop
        $cropContainer.find('.crop-container').each(function(idx,elm){
            var $crop = $(elm);
            // on cherche les informations sur le crop pour cette outils
            var formatInfo = config.formats[$crop.data('crop')];
            // calcul du chemin de l'image cropper
            var cropedPath = mediatPathWithFormat(original,formatInfo['MEDIA_FORMAT_ID']);
            var $link= $crop.find('a:first');
            $link.data('original', original)
                .attr('href',cropedPath);
            $link.find('img').attr('src',cropedPath);
            var img = new Image();
            // on charge l'image pour connaitre ses dimensions
            $(img).on('load',function(){
                // si elle est trop petite pour le crop on higlight le slot et on affiche un messag e
                if (formatInfo['MEDIA_FORMAT_WIDTH'] > img.width || formatInfo['MEDIA_FORMAT_HEIGHT'] > img.height) {
                    $slot.addClass('alert-size');
                    $slot.find('.media-error').html(config.msgAlertFormat.replace(/\{width\}/g, formatInfo['MEDIA_FORMAT_WIDTH']).replace(/\{height\}/g, formatInfo['MEDIA_FORMAT_HEIGHT']));
                }
            });
            img.src = original;
        }).last().addClass('last');

    }

    function dropMedia( event, ui ) {
        // slot = block container destination
        var $slot= $(this),
            // l'object qui est deplacer
            $draggable =  $(ui.draggable[0]);
            // le lien de l'image deplacer
          //  $btEditor = $draggable.find('.js-media-editor')
            var $oldSlot =$draggable.parent();
        $oldSlot.removeClass('alert-size');
        $slot.removeClass('alert-size');

        // si on a déja quelque chose dedans donc on remet le contenu dans la liste des images
        // si on n'est pas entrein de deplacer dans la liste d'image
        if ($slot.html() && ! $slot.hasClass('list-images')) {
           var     $oldDraggrable =  $slot.children();
           // remise a zero du format et ajout a la liste d'image
           $oldDraggrable.data('format','').appendTo($('#image-'+multi+' .list-images'));
        }

        if(!$draggable.hasClass('video-model')) {
            cropImage($slot, $draggable);
        }

        $draggable.appendTo($slot);
    }
    function addImage() {
        var content = $tplMediaEmpty.html().replace(/\{compteur\}/g,nbImage++).replace(/\{multi\}/g,multi);
        $(content).appendTo($image);
        $('#image-'+multi+' .media-model').draggable({
            helper: 'clone'
        });
    }

    function createStructure(id) {
        var $tpl = $('#'+id);
        var $structure = $($tpl.html().replace(/\{idx\}/g,nbStructure++)
            .replace(/\{multi\}/g,multi));
        // permet d'ajouter des images et video a la structure
        if($structure.hasClass('ndp_widescreen')) {
            $structure.find('li').droppable({
                accept: '.media-model',
                drop: dropMedia

            });
        }
        // permet d'ajouter des images uniquement a la structure
        if($structure.hasClass('ndp_square')) {
            $structure.find('li').droppable({
                accept: '.media-model:not(.video-model)',
                drop: dropMedia

            });
        }

        return  $structure
    }
    function addStructure(id) {
        var $structure =  createStructure(id)
        $structure.appendTo(this);
        $structureContainer.sortable('refresh');

        return $structure;
    }

    function hookCheckForm(multi) {

        // gestion de la validation du formulaire
        var $form = $('#mur-media-'+multi).parents('form:first');

        if($form.hasClass('murmedia-initialised'))  {
            return void(0);
        }
        $form.addClass('murmedia-initialised');
        $form.get(0).onClickDelete = updateStructure;
        $form.get(0).onsubmit = function() {
            var nbRequireImage, nbImageFound;
            //suppression du loader
            var loading =  top.showLoading;
            top.showLoading = false;
            // backup CheckForm
            var oldCheckForm =  CheckForm;
            var checkform = CheckForm(this);
            var incomplete = false


            // rechecrche si les structure sont valide
            $('.structure').each( function(idx, elm) {
                nbRequireImage = $(elm).find(' ul > li').length;
                nbImageFound = 0;
                $(elm).find('.image-value').each(function(num, img) {
                    if($(img).val() > 0) {
                        nbImageFound++;
                    }
                });

                if(nbImageFound > 0 && nbRequireImage != nbImageFound) {
                    checkform =false;
                    incomplete = true;

                }

            });
            if (checkform) {
                updateStructure();
                //restauration du loader et activation avant de submit le formulaire
                top.showLoading = loading;
                top.showLoading('#frame_right_middle',true);

                return true;
            }
            CheckForm = oldCheckForm;
            // restauration du loader
            top.showLoading = loading
            if(incomplete) {
                alert($('#error-structure').text());
            }

            return false;
        }

    }

}

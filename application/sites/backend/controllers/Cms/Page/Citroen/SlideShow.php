<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . "/Cms/Page/Module.php");
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');

use Citroen\Perso\Synchronizer;

class Cms_Page_Citroen_SlideShow extends Cms_Page_Citroen
{
    public static $decacheBack = array(
        array('Frontend/Citroen/ZoneMulti')
    );
    public static $decachePublication = array(
        array('Frontend/Citroen/ZoneMulti')
    );

    public static function render(Pelican_Controller $controller, $aAdditionalData = null)
    {
        // Champs communs
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
		$return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 255, "", false, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 75);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TEXTE2", t('SOUS_TITRE'), 255, "", false, $controller->zoneValues['ZONE_TEXTE2'], $controller->readO, 75);
        $return .= $controller->oForm->createEditor($controller->multi."ZONE_TEXTE", t('CHAPO'), false, $controller->zoneValues['ZONE_TEXTE'], $controller->readO, true, "", 500, 200);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_CRITERIA_ID", t('TIMING'), 2, "", true, (empty($controller->zoneValues["ZONE_CRITERIA_ID"]) ? 05 : $controller->zoneValues["ZONE_CRITERIA_ID"]), $controller->readO, 2);
        $return .= $controller->oForm->createJS("
            var iTiming = $('#" . $controller->multi . "ZONE_TITRE2').val();
            if(iTiming < 5){
                alert('" . t('ALERT_TIMING_SLIDESHOW', 'js') . "');
                $('#FIELD_BLANKS').val(1);
            }
        ");
        
        // Détection du contexte (onglet existant ou nouvel onglet perso)
        $context = $controller->getContext();
        
        // Récupération du préfixe multi (qui change en fonction du contexte)
        $multiPrefix = $context == 'newprofile' ? $controller->getParam('multiId') : $controller->getParam('multi');
        
        // Récupération des données génériques
        $result = $controller->getMultisGenericData();
        $resultPrefix = $context == 'newprofile' ? '' : $multiPrefix;
        $genericData = isset($result[$resultPrefix.'SLIDESHOW_GENERIC']) ? $result[$resultPrefix.'SLIDESHOW_GENERIC'] : null;
        $isPerso = empty($genericData) ? false : true;
        
        // Lecture des métadonnées multi
        $multiMetadataForm = $controller->getParam('multiMetadata');
        parse_str($multiMetadataForm, $multiMetadata);
        $addedMultiIndex = isset($multiMetadata['added_multi_index'][$multiPrefix.'SLIDESHOW_GENERIC']) ? $multiMetadata['added_multi_index'][$multiPrefix.'SLIDESHOW_GENERIC'] : null;
        
        // Multi slide
        $return .= $controller->oForm->createMultiHmvc(
            $controller->multi . "SLIDESHOW_GENERIC", // $strName
            t('ADD_SLIDESHOW'),                       // $strLib
            array('path' => __FILE__, 'class' => __CLASS__, 'method' => 'addSlideGeneric'),
            Backoffice_Form_Helper::getPageZoneMultiValues($controller), // $tabValues
            $controller->multi . "SLIDESHOW_GENERIC", // $incrementField
            $controller->readO,                       // $bReadOnly = false
            5,                                        // $intMinMaxIterations = ""
            true,                                     // $bAllowDeletion = true
            true,                                     // $bAllowAdd = true
            $controller->multi . "SLIDESHOW_GENERIC", // $strPrefixe = "multi"
            "values",                                 // $line = "values"
            "multi",                                  // $strCss = "multi"
            "2",                                      // $sColspan = "2"
            "",                                       // $sButtonAddMulti = ""
            "",                                       // $complement = ""
            $isPerso,                                 // $perso = false
            array(
                'generic_data' => $genericData,
                'added_multi_index' => $addedMultiIndex,
                'context' => $context,
            )
        );

        ob_start();
        ?>
        <script type="text/javascript">
            $(document).ready(function() {
                // Initialisation des formulaires au chargement de la page
                $('.slideshow_slide_wrap').each(function(index) {
                    slideshow_update_pane(this);
                });
            });

            /**
             * Fonction de mise à jour du multi contrôlé par le bouton radio type
             */
            window.slideshow_radio_type_click = function(element) {
                var wrapEl = $(element).closest('table').find('.slideshow_slide_wrap');
                slideshow_update_pane(wrapEl);
            }

            /**
             * Fonction de gestion de l'affichage des pane (image/vidéo/flash/html5)
             * Elle met à jour le multi (élément .slideshow_slide_wrap) passé en paramètre
             */
            window.slideshow_update_pane = function(element) {
                // Lecture du type défini dans le bouton radio
                var type = $(element).closest('table').find('input:radio.slideshow_slide_type:checked').val();

                // Masquage de tous les pane
                $(element).find('.slideshow_slide_pane').hide();

                // Affichage du pane correspondant au type sélectionné
                switch (type) {
                    case 'IMAGE':
                        $(element).find('.slideshow_slide_pane_img').show();
                        $(element).find('.media_img').show();
                        $(element).find('.media_video').hide();
                        break;
                    case 'VIDEO':
                        $(element).find('.slideshow_slide_pane_img').show();
                        $(element).find('.media_img').hide();
                        $(element).find('.media_video').show();
                        break;
                    case 'FLASH':
                        $(element).find('.slideshow_slide_pane_flash').show();
                        break;
                    case 'HTML5':
                        $(element).find('.slideshow_slide_pane_html').show();
                        break;
                }
            }
        </script>
        <?php
        $return .= ob_get_clean();
        $return .= Backoffice_Form_Helper::getLanguette($controller);		

        return $return;
    }

    /**
     * Le multi générique affiche un groupe de bouton radio permettant de choisir le type de slide,
     * ainsi que le formulaire de tous les types, qui sont ensuite affichés ou masqués en fonction
     * du type de slide demandé (radio sélectionné)
     */
    public static function addSlideGeneric($oForm, $values, $readO, $multi, $perso, $extendedArgs)
    {
        // Debug
        $debugMode = isset($_COOKIE['debug']) && preg_match('#perso_sync#', $_COOKIE['debug']) ? true : false;
        if ($debugMode) {
            ob_start();
            echo '<div>$multi : '; var_dump($multi); echo '</div>';
            echo '<div>$perso : '; var_dump($perso); echo '</div>';
            Synchronizer::debugUI($extendedArgs, array('title' => '$extendedArgs'));
            $return .= '<tr><td colspan="50"><div style="padding:10px; background:rgba(0,0,0,.15); margin:10px;">'.ob_get_clean().'</div></td></tr>';
        }
        
        // Synchronisation (perso)
        $genericData = isset($extendedArgs['generic_data']) ? $extendedArgs['generic_data'] : null;
        if (isset($genericData) && is_array($genericData)) {
            // Liste des hash des multi ajoutés
            $addedMulti = isset($extendedArgs['added_multi_index']) ? $extendedArgs['added_multi_index'] : array();
            
            // Liste synchronisation
            $synchroValues = array('-2' => t('ACTIVER_LA_PERSO_POUR_CE_SLIDE'));
            $numerotation = 1;
            foreach ($genericData as $key => $val) {
                if (empty($val['MULTI_HASH'])) {
                    continue;
                }
                
                // Exclusion des nouveaux éléments multi génériques (pour ne pas créer de double ajout avec la synchro add/del)
                if (in_array($val['MULTI_HASH'], $addedMulti)) {
                    continue;
                }
                
                $titre = !empty($val['PAGE_ZONE_MULTI_TITRE']) ? $val['PAGE_ZONE_MULTI_TITRE'] : t('PAS_DE_TITRE_POUR_CE_SLIDE');
                $label = t('SYNCHRONISATION_AVEC_LE_SLIDE')
                    .' '.substr($val['MULTI_HASH'], 0, 7)
                    .', '.t('OFFRE').' n°'.$numerotation
                    .' "'.$titre.'"';
                $numerotation++;
                $synchroValues[$val['MULTI_HASH']] = htmlspecialchars($label);
            }
            
            // Définition de la valeur du champ synchronisation : valeur enregistrée | multi générique | activer la perso
            $synchroValue = -2;
            if (isset($values['_sync'])) {
                $synchroValue = $values['_sync'];
            } elseif (!empty($values['MULTI_HASH'])) {
                $synchroValue = $values['MULTI_HASH'];
            }
            
            // Avertissement rétro-compatibilité + forçage synchro à "Activer la perso"
            $usesOldIdentifier = function ($values, &$id = null) {
                $hashPattern = '#^[0-9a-z]{40}$#i';
                $oldPattern = '#^\d+$#';
                if (isset($values['_sync'])) {
                    $id = $values['_sync'];
                    if ($values['_sync'] == -2 || preg_match($hashPattern, $values['_sync'])) {
                        return true;
                    } else {
                        return false;
                    }
                }
                $id = isset($values['PAGE_ZONE_MULTI_ID']) ? $values['PAGE_ZONE_MULTI_ID'] : null;
                if ($id == -2) {
                    return true;
                }
                return false;
            };
            $isMultiTemplate = preg_match('#__CPT__.{0,5}$#', $multi);
            if (!$usesOldIdentifier($values, $id) && $extendedArgs['context'] != 'newprofile' && !$isMultiTemplate) {
                $synchroValue = -2;
                $return .= '<tr><td colspan="50"><div style="padding:10px; background:rgba(255,0,0,.15); color:#a00; margin:10px 0 3px;">'
                    ."Cet élément multi utilise <b style=\"font-weight:bold;\">un ancien identifiant de synchronisation</b> (".$id.") à la place du hash. "
                    ."Veuillez sélectionner le multi générique ci-dessous :"
                    .'</div></td></tr>';
            }
            
            $return .= $oForm->createComboFromList($multi."_sync", t('SLIDE_PERSO_ACTIVE'), $synchroValues, $synchroValue, true, $readO);
        } else {
            // Affichage de l'identifiant de l'élément mutli
            if ($debugMode) {
                $return .= $oForm->createLabel(t('MULTI_SLIDE_ID').' (old)', $values["PAGE_ZONE_MULTI_ID"]);
            }
            $return .= sprintf(
                '<tr><td class="formlib">%s</td><td class="formval"><a class="multi-hash-display" title="%s">%s</a></td></tr>',
                htmlspecialchars(t('MULTI_SLIDE_ID')),
                $values["MULTI_HASH"],
                substr($values["MULTI_HASH"], 0, 7)
            );

            if (!empty($values["PAGE_ZONE_MULTI_ID"])) {
                $return .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_ID", $values["PAGE_ZONE_MULTI_ID"]);
            }
        }
        
        // Identifiant de multi
        $return .= $oForm->createHidden($multi."MULTI_HASH", $values["MULTI_HASH"]);
        
        // Affichage du groupe de radio de sélection du type de slide (image/vidéo/flash/html5)
        $return .= $oForm->createRadioFromList(
            $multi . "PAGE_ZONE_MULTI_VALUE", t('TYPE_AFFICHAGE'), Pelican::$config['SLIDESHOW']["MODE_AFF"], $values['PAGE_ID'] == -2 ? 0 : $values['PAGE_ZONE_MULTI_VALUE'], true, $readO, "h", false, 'class="slideshow_slide_type" onclick="slideshow_radio_type_click(this);"'
        );

        // Affichage des formulaires des différents types de bouton radio
        $return .= '<tr><td colspan="2" class="slideshow_slide_wrap" data-multi="' . $multi . '">';
        $return .= '<table class="multi slideshow_slide_pane slideshow_slide_pane_img">' . self::addSlideImgForm($oForm, $values, $readO, $multi, $perso) . '</table>';
        $return .= '<table class="multi slideshow_slide_pane slideshow_slide_pane_flash">' . self::addSlideFlashForm($oForm, $values, $readO, $multi, $perso) . '</table>';
        $return .= '<table class="multi slideshow_slide_pane slideshow_slide_pane_html">' . self::addSlideHtmlForm($oForm, $values, $readO, $multi, $perso) . '</table>';
        $return .= '</td></tr>';

        return $return;
    }

    public static function addSlideImgForm($oForm, $values, $readO, $multi, $perso) {
    	// Page globale
    	$pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
    			$_SESSION[APP]['SITE_ID'],
    			$_SESSION[APP]['LANGUE_ID'],
    			Pelican::getPreviewVersion(),
    			Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
    	));
    	
    	// Zone Configuration de la page globale
    	$aConfiguration = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
    			$pageGlobal['PAGE_ID'],
    			Pelican::$config['ZONE_TEMPLATE_ID']['CONFIGURATION'],
    			$pageGlobal['PAGE_VERSION'],
    			$_SESSION[APP]['LANGUE_ID']
    	));
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_TITRE", t('TITRE'), 255, "", false, $values["PAGE_ZONE_MULTI_TITRE"], $readO, 100);
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_TITRE2", t('SOUS_TITRE'), 255, "", false, $values["PAGE_ZONE_MULTI_TITRE2"], $readO, 100);
        //$return .= $oForm->createMedia($multi."MEDIA_ID", t ('VISUEL'), false, "image", "", $values['MEDIA_ID'], $readO);
        //$return .= $oForm->createMedia($multi."MEDIA_ID2", t ('VISUEL_MOBILE'), false, "image", "", $values['MEDIA_ID2'], $readO);
		
        $slideshowFormat = 'new_chart_showroom';
		$bShowGenerique = false;
		if (strpos($multi,'perso') !== false) {
			$bShowGenerique = true;
		}
        $return .= '<tr class="media_img"><td class="formlib">' . t ( 'VISUEL' ).'</td><td class="formval">' . $oForm->createMedia($multi . "MEDIA_ID", t('VISUEL'), false, "image", "", $values['MEDIA_ID'], $readO, true, true, $slideshowFormat, null, $bShowGenerique, $values['MEDIA_ID_GENERIQUE']).'</td></tr>';
        //$return .= '<tr class="media_img"><td class="formlib">' . t ( 'VISUEL_MOBILE' ).'</td><td class="formval">' . $oForm->createMedia($multi . "MEDIA_ID2", t('VISUEL_MOBILE'), false, "image", "", $values['MEDIA_ID2'], $readO, true, true, $slideshowFormat, null, $bShowGenerique, $values['MEDIA_ID2_GENERIQUE']).'</td></tr>';
        
        $return .= '<tr class="media_video"><td class="formlib">' . t ( 'VIDEO' ).'</td><td class="formval">' . $oForm->createMedia($multi . "YOUTUBE_ID", t('VIDEO'), false, "video", "", $values['YOUTUBE_ID'], $readO, true, true).'</td></tr>';

        $ChooseColor = '<div style="width:128px;">
                           <input readonly style="width:100px;" id="'.$multi.'PAGE_ZONE_MULTI_LABEL3" name="'.$multi.'PAGE_ZONE_MULTI_LABEL3" class="colorPicker evo-cp0" value="'.$values["PAGE_ZONE_MULTI_LABEL3"].'"/>
                        </div>';
        $return .= '<tr><td class="formlib">' . t('COULEUR_TYPO') . ' *</td><td class="formval">' . $ChooseColor . '</td>';

        ($values["PAGE_ZONE_MULTI_LABEL3"])?$values["PAGE_ZONE_MULTI_LABEL3"]:$values["PAGE_ZONE_MULTI_LABEL3"]='#0000ffff';
        $return .= "<script type=\"text/javascript\">
                var tradColors = \"Couleurs principales,Couleurs secondaires,Plus de couleurs,Moins de couleurs,Palette,Historique,Pas encore d'historique.,Couleur édito\";
                $(document).ready(function(){

                    if($('.ui-dialog').length >0){
                        var strinput = $('#".$multi."PAGE_ZONE_MULTI_LABEL3').attr('id');
                        if (strinput.indexOf(\"perso\") !=-1) {
                          $('.evo-pointer,#".$multi."PAGE_ZONE_MULTI_LABEL3').click(function() {
                            $('.evo-pop').removeAttr('style');
                           });
                        }
                    }
                    // No color indicator
                    $('#".$multi."PAGE_ZONE_MULTI_LABEL3').colorpicker({
                        strings: tradColors,
                        color:'".$values["PAGE_ZONE_MULTI_LABEL3"]."',//transparent par defaut
                        displayIndicator: false,
                        history:false,
                        webColor:false,
                        transparentColor:true,
                        color1:[".Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS"] ."],
                        color2:[".Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS2"] ."],
                        color3:[".Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS3"] ."]
                    });
                    // No color indicator
                    $('body').on('click','input[name =".$multi."PAGE_ZONE_MULTI_VALUE]',function(){
                        $('#".$multi."PAGE_ZONE_MULTI_LABEL3').next('.evo-pointer').remove();
                        var htmlColor = $('#".$multi."PAGE_ZONE_MULTI_LABEL3').get();
                        $('#".$multi."PAGE_ZONE_MULTI_LABEL3').closest('td').html(htmlColor);
                          if($('.ui-dialog').length >0){
                            var strinput = $('#".$multi."PAGE_ZONE_MULTI_LABEL3').attr('id');
                            if (strinput.indexOf(\"perso\") !=-1) {
                              $('.evo-pointer,#".$multi."PAGE_ZONE_MULTI_LABEL3').click(function() {
                                $('.evo-pop').removeAttr('style');
                               });
                            }
                        }
                        $('#".$multi."PAGE_ZONE_MULTI_LABEL3').colorpicker({
                            strings: tradColors,
                            color:'".$values["PAGE_ZONE_MULTI_LABEL3"]."',//transparent par defaut
                            displayIndicator: false,
                            history:false,
                            webColor:false,
                            transparentColor:true,
                            color1:[".Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS"] ."],
                            color2:[".Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS2"] ."],
                            color3:[".Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS3"] ."]
                        });
                    });
                });
        </script>";

        $htmlComboColorCta = '<div style="width:128px;">
                           <input readonly style="width:100px;" id="'.$multi.'PAGE_ZONE_MULTI_LABEL4" name="'.$multi.'PAGE_ZONE_MULTI_LABEL4" class="colorPicker evo-cp0" value="'.$values["PAGE_ZONE_MULTI_LABEL4"].'"/>
                        </div>';
        $return .= '<tr><td class="formlib">' . t('COULEUR_CTA') . ' *</td><td class="formval">' . $htmlComboColorCta . '</td>';
        ($values["PAGE_ZONE_MULTI_LABEL4"])?$values["PAGE_ZONE_MULTI_LABEL4"]:$values["PAGE_ZONE_MULTI_LABEL4"]='#0000ffff';
        $return .= "<script type=\"text/javascript\">
                var tradColors = \"Couleurs principales,Couleurs secondaires,Plus de couleurs,Moins de couleurs,Palette,Historique,Pas encore d'historique.,Couleur édito\";
                $(document).ready(function(){

              if($('.ui-dialog').length >0){
                    var strinput = $('#".$multi."PAGE_ZONE_MULTI_LABEL4').attr('id');
                    if (strinput.indexOf(\"perso\") !=-1) {
                      $('.evo-pointer,#".$multi."PAGE_ZONE_MULTI_LABEL4').click(function() {
                        $('.evo-pop').removeAttr('style');
                       });
                    }
                }
                $('#".$multi."PAGE_ZONE_MULTI_LABEL4').colorpicker({
                            strings: tradColors,
                            color:'".$values["PAGE_ZONE_MULTI_LABEL4"]."',//transparent par defaut
                            displayIndicator: false,
                            history:false,
                            webColor:false,
                            transparentColor:true,
                            color1:[".Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS"] ."],
                            color2:[".Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS2"] ."],
                            color3:[".Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS3"] ."]
                        });
                    // No color indicator
                    $('body').on('click','input[name =".$multi."PAGE_ZONE_MULTI_VALUE]',function(){
                        $('#".$multi."PAGE_ZONE_MULTI_LABEL4').next('.evo-pointer').remove();
                        var htmlColor = $('#".$multi."PAGE_ZONE_MULTI_LABEL4').get();
                        $('#".$multi."PAGE_ZONE_MULTI_LABEL4').closest('td').html(htmlColor);
                        if($('.ui-dialog').length >0){
                            var strinput = $('#".$multi."PAGE_ZONE_MULTI_LABEL4').attr('id');
                            if (strinput.indexOf(\"perso\") !=-1) {
                              $('.evo-pointer,#".$multi."PAGE_ZONE_MULTI_LABEL4').click(function() {
                                $('.evo-pop').removeAttr('style');
                               });
                            }
                        }
                        $('#".$multi."PAGE_ZONE_MULTI_LABEL4').colorpicker({
                            strings: tradColors,
                            color:'".$values["PAGE_ZONE_MULTI_LABEL4"]."',//transparent par defaut
                            displayIndicator: false,
                            history:false,
                            webColor:false,
                            transparentColor:true,
                            color1:[".Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS"] ."],
                            color2:[".Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS2"] ."],
                            color3:[".Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS3"] ."]
                        });
                    });
                });
        </script>";


        $return .= $oForm->createComboFromList($multi."PAGE_ZONE_MULTI_ATTRIBUT", t('POSITION_CTA'), Pelican::$config['POSITION_CTA'], $values['PAGE_ZONE_MULTI_ATTRIBUT'], true, $readO);

        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_LABEL", t('LIB_CTA'), 40, "", false, $values["PAGE_ZONE_MULTI_LABEL"], $readO, 100);
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_URL", t('URL_CTA'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL"], $readO, 50, false);
        $return .= $oForm->createRadioFromList($multi . "PAGE_ZONE_MULTI_MODE", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $values['PAGE_ZONE_MULTI_MODE'], false, $readO);
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_LABEL2", t('LIB_CTA') . ' 2', 40, "", false, $values["PAGE_ZONE_MULTI_LABEL2"], $readO, 100);
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_URL2", t('URL_CTA') . ' 2', 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL2"], $readO, 50, false);
        $return .= $oForm->createRadioFromList($multi . "PAGE_ZONE_MULTI_MODE2", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $values['PAGE_ZONE_MULTI_MODE2'], false, $readO);
        $return .= $oForm->createLabel('', t('DESC_USING_LINK'));
        $return .= $oForm->createCheckBoxFromList($multi . "PAGE_ZONE_MULTI_MODE3", t('VISUEL_CLIQUABLE'), array(1 => ""), $values['PAGE_ZONE_MULTI_MODE3'], false, $readO);
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_URL3", t('URL_VISUEL'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL3"], $readO, 50, false);
        $return .= $oForm->createRadioFromList($multi . "PAGE_ZONE_MULTI_MODE4", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $values['PAGE_ZONE_MULTI_MODE4'], false, $readO);
        $return .= $oForm->showSeparator();
        $return .= $oForm->createLabel(t('CTA').' '.t('MOBILE'), '');
        $return .= $oForm->showSeparator();
        $return .= $oForm->createLabel('', '');
        $slideshowFormatMobile = 'new_chart_showroom_mobile';
        $return .= '<tr class="media_img"><td class="formlib">' . t ( 'VISUEL_MOBILE' ).'</td><td class="formval">' . $oForm->createMedia($multi . "MEDIA_ID2", t('VISUEL_MOBILE'), false, "image", "", $values['MEDIA_ID2'], $readO, true, true, $slideshowFormatMobile, null, $bShowGenerique, $values['MEDIA_ID2_GENERIQUE']).'</td></tr>';
        $ChooseColor = '<div style="width:128px;">
                           <input readonly style="width:100px;" id="'.$multi.'PAGE_ZONE_MULTI_LABEL7" name="'.$multi.'PAGE_ZONE_MULTI_LABEL7" class="colorPicker evo-cp0"/>
                        </div>';
        $return .= '<tr><td class="formlib">' . t('COULEUR_TYPO_MOBILE') . ' *</td><td class="formval">' . $ChooseColor . '</td>';

        ($values["PAGE_ZONE_MULTI_LABEL7"])?$values["PAGE_ZONE_MULTI_LABEL7"]:$values["PAGE_ZONE_MULTI_LABEL7"]='#0000ffff';
        $return .= "<script type=\"text/javascript\">
                var tradColors = \"Couleurs principales,Couleurs secondaires,Plus de couleurs,Moins de couleurs,Palette,Historique,Pas encore d'historique.,Couleur édito\";
                $(document).ready(function(){

                if($('.ui-dialog').length >0){
                    var strinput = $('#".$multi."PAGE_ZONE_MULTI_LABEL7').attr('id');
                    if (strinput.indexOf(\"perso\") !=-1) {
                      $('.evo-pointer,#".$multi."PAGE_ZONE_MULTI_LABEL7').click(function() {
                        $('.evo-pop').removeAttr('style');
                       });
                    }
                }
                    // No color indicator
                    $('#".$multi."PAGE_ZONE_MULTI_LABEL7').colorpicker({
                        strings: tradColors,
                        color:'".$values["PAGE_ZONE_MULTI_LABEL7"]."',//transparent par defaut
                        displayIndicator: false,
                        history:false,
                        webColor:false,
                        transparentColor:true,
                        color1:[".Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS"] ."],
                        color2:[".Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS2"] ."],
                        color3:[".Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS3"] ."]
                    });
                    // No color indicator
                    $('body').on('click','input[name =".$multi."PAGE_ZONE_MULTI_VALUE]',function(){
                        $('#".$multi."PAGE_ZONE_MULTI_LABEL7').next('.evo-pointer').remove();
                        var htmlColor = $('#".$multi."PAGE_ZONE_MULTI_LABEL7').get();
                        $('#".$multi."PAGE_ZONE_MULTI_LABEL7').closest('td').html(htmlColor);
                         if($('.ui-dialog').length >0){
                            var strinput = $('#".$multi."PAGE_ZONE_MULTI_LABEL7').attr('id');
                            if (strinput.indexOf(\"perso\") !=-1) {
                              $('.evo-pointer,#".$multi."PAGE_ZONE_MULTI_LABEL7').click(function() {
                                $('.evo-pop').removeAttr('style');
                               });
                            }
                        }
                        $('#".$multi."PAGE_ZONE_MULTI_LABEL7').colorpicker({
                            strings: tradColors,
                            color:'".$values["PAGE_ZONE_MULTI_LABEL7"]."',//transparent par defaut
                            displayIndicator: false,
                            history:false,
                            webColor:false,
                            transparentColor:true,
                            color1:[".Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS"] ."],
                            color2:[".Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS2"] ."],
                            color3:[".Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS3"] ."]
                        });
                    });
                });
        </script>";

        $htmlComboColorCta = '<div style="width:128px;">
                           <input readonly style="width:100px;" id="'.$multi.'PAGE_ZONE_MULTI_LABEL8" name="'.$multi.'PAGE_ZONE_MULTI_LABEL8" class="colorPicker evo-cp0" />
                        </div>';
        $return .= '<tr><td class="formlib">' . t('COULEUR_CTA_MOBILE') . ' *</td><td class="formval">' . $htmlComboColorCta . '</td>';
        ($values["PAGE_ZONE_MULTI_LABEL8"])?$values["PAGE_ZONE_MULTI_LABEL8"]:$values["PAGE_ZONE_MULTI_LABEL8"]='#0000ffff';
        $return .= "<script type=\"text/javascript\">
                var tradColors = \"Couleurs principales,Couleurs secondaires,Plus de couleurs,Moins de couleurs,Palette,Historique,Pas encore d'historique.,Couleur édito\";
                $(document).ready(function(){
                 if($('.ui-dialog').length >0){
                    var strinput = $('#".$multi."PAGE_ZONE_MULTI_LABEL8').attr('id');
                    if (strinput.indexOf(\"perso\") !=-1) {
                      $('.evo-pointer,#".$multi."PAGE_ZONE_MULTI_LABEL8').click(function() {
                        $('.evo-pop').removeAttr('style');
                       });
                    }
                }
                    // No color indicator
                    $('#".$multi."PAGE_ZONE_MULTI_LABEL8').colorpicker({
                        strings: tradColors,
                        color:'".$values["PAGE_ZONE_MULTI_LABEL8"]."',//transparent par defaut
                        displayIndicator: false,
                        history:false,
                        webColor:false,
                        transparentColor:true,
                        color1:[".Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS"] ."],
                        color2:[".Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS2"] ."],
                        color3:[".Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS3"] ."]
                    });
                    // No color indicator
                    $('body').on('click','input[name =".$multi."PAGE_ZONE_MULTI_VALUE]',function(){
                        $('#".$multi."PAGE_ZONE_MULTI_LABEL8').next('.evo-pointer').remove();
                        var htmlColor = $('#".$multi."PAGE_ZONE_MULTI_LABEL8').get();
                        $('#".$multi."PAGE_ZONE_MULTI_LABEL8').closest('td').html(htmlColor);
                        if($('.ui-dialog').length >0){
                            var strinput = $('#".$multi."PAGE_ZONE_MULTI_LABEL8').attr('id');
                            if (strinput.indexOf(\"perso\") !=-1) {
                              $('.evo-pointer,#".$multi."PAGE_ZONE_MULTI_LABEL8').click(function() {
                                $('.evo-pop').removeAttr('style');
                               });
                            }
                        }
                        $('#".$multi."PAGE_ZONE_MULTI_LABEL8').colorpicker({
                            strings: tradColors,
                            color:'".$values["PAGE_ZONE_MULTI_LABEL8"]."',//transparent par defaut
                            displayIndicator: false,
                            history:false,
                            webColor:false,
                            transparentColor:true,
                            color1:[".Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS"] ."],
                            color2:[".Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS2"] ."],
                            color3:[".Pelican::$config['COULEUR_TYPO_TAB']["THEME_ADVANCED_TEXT_COLORS3"] ."]
                        });
                    });
                });
        </script>";
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_LABEL5", t('LIB_CTA'), 40, "", false, $values["PAGE_ZONE_MULTI_LABEL5"], $readO, 100);
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_URL5", t('URL_CTA'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL5"], $readO, 50, false);
        $return .= $oForm->createRadioFromList($multi . "PAGE_ZONE_MULTI_MODE5", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $values['PAGE_ZONE_MULTI_MODE5'], false, $readO);
        //$return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_LABEL6", t('LIB_CTA') . ' 2', 40, "", false, $values["PAGE_ZONE_MULTI_LABEL6"], $readO, 100);
        /*$return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_URL6", t('URL_CTA') . ' 2', 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL6"], $readO, 50, false);
        $return .= $oForm->createRadioFromList($multi . "PAGE_ZONE_MULTI_MODE6", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $values['PAGE_ZONE_MULTI_MODE6'], false, $readO);*/


        /*
          $return .= $oForm->createJS("
          var typoColor = $('#".$multi."PAGE_ZONE_MULTI_LABEL3').val();
          var ctaColor = $('#".$multi."PAGE_ZONE_MULTI_LABEL4').val();
          var multiDisplay = $('#".$multi."multi_display').val();
          if(typoColor == '0' && multiDisplay == 1){
          alert('".t('ALERT_TYPO_MULTI_SLIDESHOW', 'js')."');
          return false;
          }
          if(ctaColor == '0' && multiDisplay == 1){
          alert('".t('ALERT_CTA_MULTI_SLIDESHOW', 'js')."');
          return false;
          }
          ");
         */

        return $return;
    }

    public static function addSlideFlashForm($oForm, $values, $readO, $multi) {
        $return .= $oForm->createMedia($multi . "MEDIA_ID3", t('FICHIER_SWF'), false, "flash", "", $values['MEDIA_ID3'], $readO);
        $return .= $oForm->createMedia($multi . "MEDIA_ID4", t('FICHIER_XML'), false, "file", "", $values['MEDIA_ID4'], $readO);
        $return .= $oForm->createLabel("", t('VARIABLE_XML') . Pelican::$config['VARIABLE_XML_SLIDESHOW']);
        $return .= $oForm->createMedia($multi . "MEDIA_ID5", t('ALTERNATIVE_IMAGE'), false, "image", "", $values['MEDIA_ID5'], $readO);
        $return .= $oForm->createTextArea($multi . "PAGE_ZONE_MULTI_TEXT2", t('ALTERNATIVE_TEXT'), false, $values["PAGE_ZONE_MULTI_TEXT2"], 1000, $readO, 2, 100, false, "", false);
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_URL4", t('ALTERNATIVE_URL'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL4"], $readO, 50, false);

        return $return;
    }

    public static function addSlideHtmlForm($oForm, $values, $readO, $multi) {
        $return .= $oForm->createTextArea($multi . "PAGE_ZONE_MULTI_TEXT", t('CODE_HTML'), false, $values["PAGE_ZONE_MULTI_TEXT"], "", $readO, 4, 100, false, "", false);

        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        // Synchronisation (perso)
        try {
            $verbose = isset($_COOKIE['debug']) && preg_match('#perso_sync#', $_COOKIE['debug']) ? true : false;
            $persoData = Synchronizer::unserialize(Pelican_Db::$values['ZONE_PERSO']);
            $sync = new Synchronizer($persoData, Pelican_Db::$values, $_POST, $verbose);
            $sync->sync('SLIDESHOW_GENERIC', 5);
            Pelican_Db::$values['ZONE_PERSO'] = Synchronizer::serialize($sync->persoData);
        } catch (Exception $ex) {
            switch ($ex->getCode()) {
                case Synchronizer::EX_UNREADABLE_PERSODATA:
                    break;
                default:
                    trigger_error($ex->getMessage(), E_USER_WARNING);
                    break;
            }
        }
        
        Backoffice_Form_Helper::saveFormAffichage(); 
        parent::save();
        Backoffice_Form_Helper::savePageZoneMultiValues('SLIDESHOW_GENERIC', 'SLIDESHOW_GENERIC');
        Pelican_Cache::clean("Frontend/Citroen/SlideShow");
    }  
}

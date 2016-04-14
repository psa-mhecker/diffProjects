<?php

include_once Pelican::$config['CONTROLLERS_ROOT']."/Cms/Page/Module.php";
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';

class Cms_Page_Citroen_SlideShow extends Cms_Page_Citroen
{
    public static $decacheBack = array(
        array('Frontend/Citroen/ZoneMulti'),
    );
    public static $decachePublication = array(
        array('Frontend/Citroen/ZoneMulti'),
    );

    public static function render(Pelican_Controller $controller, $aAdditionalData = null)
    {
        if (is_array($aAdditionalData) && !empty($aAdditionalData)) {
            $controller->zoneValues = array_merge($controller->zoneValues, $aAdditionalData);
        }
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_CRITERIA_ID", t('TIMING'), 2, "", true, (empty($controller->zoneValues["ZONE_CRITERIA_ID"]) ? 05 : $controller->zoneValues["ZONE_CRITERIA_ID"]), $controller->readO, 2);
        // Création du multi générique
        $return .= $controller->oForm->createMultiHmvc(
                $controller->multi."SLIDESHOW_GENERIC", // $strName
                t('ADD_SLIDESHOW'), array(
                    'path' => __FILE__,
                    'class' => __CLASS__,
                    'method' => 'addSlideGeneric',
                ),
                Backoffice_Form_Helper::getPageZoneMultiValues($controller), $controller->multi."SLIDESHOW_GENERIC", // $incrementField
                $controller->readO, 5, true, true, $controller->multi."SLIDESHOW_GENERIC", // $strPrefixe
                "values", "multi", "2", "", "", $controller->zoneValues
        );

        $return .= $controller->oForm->createJS("
		    var iTiming = $('#".$controller->multi."ZONE_TITRE2').val();
			if(iTiming < 5){
				alert('".t('ALERT_TIMING_SLIDESHOW', 'js')."');
				return false;
			}
        ");

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

        return $return;
    }

    /**
     * Le multi générique affiche un groupe de bouton radio permettant de choisir le type de slide,
     * ainsi que le formulaire de tous les types, qui sont ensuite affichés ou masqués en fonction
     * du type de slide demandé (radio sélectionné).
     */
    public static function addSlideGeneric($oForm, $values, $readO, $multi, $aData = null)
    {
        $return .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_TYPE", 'SLIDESHOW_GENERIC');
        $return .= $oForm->createLabel(t('MULTI_SLIDE_ID'), $values["PAGE_ZONE_MULTI_ID"]);
        if (!empty($values["PAGE_ZONE_MULTI_ID"])) {
            $return .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_ID", $values["PAGE_ZONE_MULTI_ID"]);
        }
        // Affichage du groupe de radio de sélection du type de slide (image/vidéo/flash/html5)
        $return .= $oForm->createRadioFromList(
                $multi."PAGE_ZONE_MULTI_VALUE", t('TYPE_AFFICHAGE'), Pelican::$config['SLIDESHOW']["MODE_AFF"], $values['PAGE_ID'] == -2 ? 0 : $values['PAGE_ZONE_MULTI_VALUE'], true, $readO, "h", false, 'class="slideshow_slide_type" onclick="slideshow_radio_type_click(this);"'
        );

        // Affichage des formulaires des différents types de bouton radio
        $return .= '<tr><td colspan="2" class="slideshow_slide_wrap" data-multi="'.$multi.'">';
        $return .= '<table class="multi slideshow_slide_pane slideshow_slide_pane_img">'.self::addSlideImgForm($oForm, $values, $readO, $multi).'</table>';
        $return .= '<table class="multi slideshow_slide_pane slideshow_slide_pane_flash">'.self::addSlideFlashForm($oForm, $values, $readO, $multi).'</table>';
        $return .= '<table class="multi slideshow_slide_pane slideshow_slide_pane_html">'.self::addSlideHtmlForm($oForm, $values, $readO, $multi).'</table>';
        $return .= '</td></tr>';

        return $return;
    }

    public static function addSlideImgForm($oForm, $values, $readO, $multi)
    {
        $return .= $oForm->createInput($multi."PAGE_ZONE_MULTI_TITRE", t('TITRE'), 255, "", false, $values["PAGE_ZONE_MULTI_TITRE"], $readO, 100);
        $return .= $oForm->createInput($multi."PAGE_ZONE_MULTI_TITRE2", t('SOUS_TITRE'), 255, "", false, $values["PAGE_ZONE_MULTI_TITRE2"], $readO, 100);
        $return .= '<tr class="media_img"><td class="formlib">'.t('VISUEL').'</td><td class="formval">'.$oForm->createMedia($multi."MEDIA_ID", t('VISUEL'), false, "image", "", $values['MEDIA_ID'], $readO, true, true, 'slideshow', null, false, $values['MEDIA_ID_GENERIQUE']).'</td></tr>';
        $return .= '<tr class="media_img"><td class="formlib">'.t('VISUEL_MOBILE').'</td><td class="formval">'.$oForm->createMedia($multi."MEDIA_ID2", t('VISUEL_MOBILE'), false, "image", "", $values['MEDIA_ID2'], $readO, true, true, 'slideshow', null, false, $values['MEDIA_ID2_GENERIQUE']).'</td></tr>';

        $return .= '<tr class="media_video"><td class="formlib">'.t('VIDEO').'</td><td class="formval">'.$oForm->createMedia($multi."YOUTUBE_ID", t('VIDEO'), false, "video", "", $values['YOUTUBE_ID'], $readO, true, true).'</td></tr>';

        $aCouleurTypo = Pelican::$config['COULEUR_TYPO_TAB'];
        $comboTypo = array();
        $aComboTypo = array();
        $htmlComboColorTypo = '';
        if ($aCouleurTypo) {
            $aComboTypo[][] = Pelican_Html::option(array(
                        'value' => "",
                        "class" => "PAGE_ZONE_MULTI_ATTRIBUT",
                            ), "&nbsp;");
            foreach ($aCouleurTypo as $optgroup => $aColor) {
                foreach ($aColor as $key => $color) {
                    $selectedTypo = '';
                    if ($key == $values['PAGE_ZONE_MULTI_ATTRIBUT']) {
                        $selectedTypo = 'selected';
                    }
                    $aComboTypo[$optgroup][] = Pelican_Html::option(array(
                                "value" => $key,
                                "class" => "PAGE_ZONE_MULTI_ATTRIBUT",
                                "selected" => $selectedTypo,
                                    ), Pelican_Text::htmlentities($color));
                }
                $comboTypo[] = Pelican_Html::optgroup(array(
                            "label" => Pelican_Text::htmlentities($optgroup),
                            "class" => "PAGE_ZONE_MULTI_ATTRIBUT",
                                ), implode("", $aComboTypo[$optgroup]));
            }
            $htmlComboColorTypo = Pelican_Html::select(array(
                        'id' => $multi."PAGE_ZONE_MULTI_ATTRIBUT",
                        'name' => $multi."PAGE_ZONE_MULTI_ATTRIBUT",
                        'class' => "text",
                            ), implode("", $comboTypo));
        }
        $return .= '<tr><td class="formlib">'.t('COULEUR_TYPO').' *</td><td class="formval">'.$htmlComboColorTypo.'</td>';

        $aCouleurCta = Pelican::$config['COULEUR_CTA_TAB'];
        $comboCta = array();
        $aComboCta = array();
        $htmlComboColorCta = '';

        if ($aCouleurCta) {
            $aComboCta[][] = Pelican_Html::option(array(
                        'value' => "",
                        "class" => "PAGE_ZONE_MULTI_ATTRIBUT2",
                            ), "&nbsp;");
            foreach ($aCouleurCta as $optgroup => $aColor) {
                foreach ($aColor as $key => $color) {
                    $selectedCta = '';
                    if ($key == $values['PAGE_ZONE_MULTI_ATTRIBUT2']) {
                        $selectedCta = 'selected';
                    }
                    $aComboCta[$optgroup][] = Pelican_Html::option(array(
                                "value" => $key,
                                "class" => "PAGE_ZONE_MULTI_ATTRIBUT2",
                                "selected" => $selectedCta,
                                    ), Pelican_Text::htmlentities($color));
                }
                $comboCta[] = Pelican_Html::optgroup(array(
                            "label" => Pelican_Text::htmlentities($optgroup),
                            "class" => "PAGE_ZONE_MULTI_ATTRIBUT2",
                                ), implode("", $aComboCta[$optgroup]));
            }
            $htmlComboColorCta = Pelican_Html::select(array(
                        'id' => $multi."PAGE_ZONE_MULTI_ATTRIBUT2",
                        'name' => $multi."PAGE_ZONE_MULTI_ATTRIBUT2",
                        'class' => "text",
                            ), implode("", $comboCta));
        }
        $return .= '<tr><td class="formlib">'.t('COULEUR_CTA').' *</td><td class="formval">'.$htmlComboColorCta.'</td>';

        $return .= $oForm->createInput($multi."PAGE_ZONE_MULTI_LABEL", t('LIB_CTA'), 40, "", false, $values["PAGE_ZONE_MULTI_LABEL"], $readO, 100);
        $return .= $oForm->createInput($multi."PAGE_ZONE_MULTI_URL", t('URL_CTA'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL"], $readO, 50, false);
        $return .= $oForm->createRadioFromList($multi."PAGE_ZONE_MULTI_MODE", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $values['PAGE_ZONE_MULTI_MODE'], false, $readO);
        $return .= $oForm->createInput($multi."PAGE_ZONE_MULTI_LABEL2", t('LIB_CTA').' 2', 40, "", false, $values["PAGE_ZONE_MULTI_LABEL2"], $readO, 100);
        $return .= $oForm->createInput($multi."PAGE_ZONE_MULTI_URL2", t('URL_CTA').' 2', 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL2"], $readO, 50, false);
        $return .= $oForm->createRadioFromList($multi."PAGE_ZONE_MULTI_MODE2", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $values['PAGE_ZONE_MULTI_MODE2'], false, $readO);
        $return .= $oForm->createLabel('', t('DESC_USING_LINK'));
        $return .= $oForm->createCheckBoxFromList($multi."PAGE_ZONE_MULTI_MODE3", t('VISUEL_CLIQUABLE'), array(1 => ""), $values['PAGE_ZONE_MULTI_MODE3'], false, $readO);
        $return .= $oForm->createInput($multi."PAGE_ZONE_MULTI_URL3", t('URL_VISUEL'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL3"], $readO, 50, false);
        $return .= $oForm->createRadioFromList($multi."PAGE_ZONE_MULTI_MODE4", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $values['PAGE_ZONE_MULTI_MODE4'], false, $readO);

        return $return;
    }

    public static function addSlideFlashForm($oForm, $values, $readO, $multi)
    {
        $return .= $oForm->createMedia($multi."MEDIA_ID3", t('FICHIER_SWF'), false, "flash", "", $values['MEDIA_ID3'], $readO);
        $return .= $oForm->createMedia($multi."MEDIA_ID4", t('FICHIER_XML'), false, "file", "", $values['MEDIA_ID4'], $readO);
        $return .= $oForm->createLabel("", t('VARIABLE_XML').Pelican::$config['VARIABLE_XML_SLIDESHOW']);
        $return .= $oForm->createMedia($multi."MEDIA_ID5", t('ALTERNATIVE_IMAGE'), false, "image", "", $values['MEDIA_ID5'], $readO);
        $return .= $oForm->createTextArea($multi."PAGE_ZONE_MULTI_TEXT2", t('ALTERNATIVE_TEXT'), false, $values["PAGE_ZONE_MULTI_TEXT2"], 1000, $readO, 2, 100, false, "", false);
        $return .= $oForm->createInput($multi."PAGE_ZONE_MULTI_URL4", t('ALTERNATIVE_URL'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL4"], $readO, 50, false);

        return $return;
    }

    public static function addSlideHtmlForm($oForm, $values, $readO, $multi)
    {
        $return .= $oForm->createTextArea($multi."PAGE_ZONE_MULTI_TEXT", t('CODE_HTML'), false, $values["PAGE_ZONE_MULTI_TEXT"], "", $readO, 4, 100, false, "", false);

        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
        $sMultiName = 'SLIDESHOW_GENERIC';
        Backoffice_Form_Helper::savePageZoneMultiValues($sMultiName, $sMultiName);
        Pelican_Cache::clean("Frontend/Citroen/SlideShow");
    }
}

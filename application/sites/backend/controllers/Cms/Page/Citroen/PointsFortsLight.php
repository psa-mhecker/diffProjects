<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . "/Cms/Page/Module.php");
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');

use Citroen\Perso\Synchronizer;

class Cms_Page_Citroen_PointsFortsLight extends Cms_Page_Citroen
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
		$return .= $controller->oForm->createCheckBoxFromList($controller->multi . "MEDIA_AUTOLOAD", t('POINT_FORT_AUTOLOAD'), array(1 => ""), ($controller->zoneValues['MEDIA_AUTOLOAD'] == -2) ? 1 : $controller->zoneValues['MEDIA_AUTOLOAD'], '', $controller->readO);
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
        $genericData = isset($result[$resultPrefix.'POINTS_FORT_LIGHT']) ? $result[$resultPrefix.'POINTS_FORT_LIGHT'] : null;
        $isPerso = empty($genericData) ? false : true;
        
        // Lecture des métadonnées multi
        $multiMetadataForm = $controller->getParam('multiMetadata');
        parse_str($multiMetadataForm, $multiMetadata);
        $addedMultiIndex = isset($multiMetadata['added_multi_index'][$multiPrefix.'POINTS_FORT_LIGHT']) ? $multiMetadata['added_multi_index'][$multiPrefix.'POINTS_FORT_LIGHT'] : null;
        
        // Multi slide
        $return .= $controller->oForm->createMultiHmvc(
            $controller->multi . "POINTS_FORT_LIGHT", // $strName
            t('ADD_SLIDESHOW'),                       // $strLib
            array('path' => __FILE__, 'class' => __CLASS__, 'method' => 'addSlideGeneric'),
            Backoffice_Form_Helper::getPageZoneMultiValues($controller), // $tabValues
            $controller->multi . "POINTS_FORT_LIGHT", // $incrementField
            $controller->readO,                       // $bReadOnly = false
            6,                                        // $intMinMaxIterations = ""
            true,                                     // $bAllowDeletion = true
            true,                                     // $bAllowAdd = true
            $controller->multi . "POINTS_FORT_LIGHT", // $strPrefixe = "multi"
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
                $('.point_fort_light_slide_wrap').each(function(index) {
                    point_fort_light_update_pane(this);
                });
            });

            /**
             * Fonction de mise à jour du multi contrôlé par le bouton radio type
             */
            window.point_fort_light_radio_type_click = function(element) {
                var wrapEl = $(element).closest('table').find('.point_fort_light_slide_wrap');
                point_fort_light_update_pane(wrapEl);
            }

            /**
             * Fonction de gestion de l'affichage des pane (image/vidéo/flash/html5)
             * Elle met à jour le multi (élément .point_fort_light_slide_wrap) passé en paramètre
             */
            window.point_fort_light_update_pane = function(element) {
                // Lecture du type défini dans le bouton radio
                var type = $(element).closest('table').find('input:radio.slideshow_slide_type:checked').val();

                // Masquage de tous les pane
                $(element).find('.slideshow_slide_pane').hide();

                // Affichage du pane correspondant au type sélectionné
                switch (type) {
                    case 'GRAND_VISUEL':
                        $(element).find('.slideshow_slide_pane_img').show();
                        $(element).find('.media_img').show();
                        $(element).find('.grand_visuel').show();
                        $(element).find('.media_video').hide();
						$(element).find('.2_colonne').hide();
						$(element).find('.general').show();
						$(element).find('.superposition_visuel').hide();
						$(element).find('.3_colonne').hide();
						$(element).find('.all').show();
                        break;
                    case '2_COLONNE_MIXTE':
                        $(element).find('.slideshow_slide_pane_img').show();
                        $(element).find('.media_img').hide();
                        $(element).find('.media_video').show();
						$(element).find('.2_colonne').show();
						$(element).find('.grand_visuel').hide();
						$(element).find('.3_colonne').hide();
						$(element).find('.general').show();
						$(element).find('.superposition_visuel').hide();
						$(element).find('.all').show();
						$(element).find('.general').hide();
                        break;
                    case 'SUPERPOSITION_VISUELS':
                        $(element).find('.slideshow_slide_pane_img').show();
						$(element).find('.superposition_visuel').show();
						$(element).find('.grand_visuel').hide();
						$(element).find('.2_colonne').hide();
						$(element).find('.3_colonne').hide();
						$(element).find('.general').hide();
						$(element).find('.all').show();
                        break;
                    case '3_COLONNE_MIXTE':
                        $(element).find('.slideshow_slide_pane_img').show();
						$(element).find('.3_colonne').show();
						$(element).find('.grand_visuel').show();
						$(element).find('.2_colonne').hide();
						$(element).find('.superposition_visuel').hide();
						$(element).find('.without3col').hide();
						$(element).find('.all').hide();
						$(element).find('.general').hide();
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
     * du type de slide demandé (radio sélectionné)
     */
    public static function addSlideGeneric($oForm, $values, $readO, $multi, $perso, $extendedArgs)
    {
        

		if (!empty($values["PAGE_ZONE_MULTI_ID"])) {
			$return .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_ID", $values["PAGE_ZONE_MULTI_ID"]);
		}
        // Identifiant de multi
        $return .= $oForm->createHidden($multi."MULTI_HASH", $values["MULTI_HASH"]);
        
        // Affichage du groupe de radio de sélection du type de slide (image/vidéo/flash/html5)
        $return .= $oForm->createRadioFromList(
            $multi . "PAGE_ZONE_MULTI_VALUE", t('TYPE_AFFICHAGE'), Pelican::$config['POINTS_FORTS_LIGHT']["MODE_AFF"], $values['PAGE_ID'] == -2 ? 0 : $values['PAGE_ZONE_MULTI_VALUE'], true, $readO, "h", false, 'class="slideshow_slide_type" onclick="point_fort_light_radio_type_click(this);"'
        );
		
        // Affichage des formulaires des différents types de bouton radio
        $return .= '<tr><td colspan="2" class="point_fort_light_slide_wrap" data-multi="' . $multi . '">';
        $return .= '<table class="multi slideshow_slide_pane slideshow_slide_pane_img" style="display:none;">' . self::addSlidePoinstForts($oForm, $values, $readO, $multi, $perso) . '</table>';
        $return .= '</td></tr>';

        return $return;
    }
	

    public static function addSlidePoinstForts($oForm, $values, $readO, $multi, $perso) {
		
		
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_TITRE", t('TITRE'), 255, "", false, $values["PAGE_ZONE_MULTI_TITRE"], $readO, 100);
        $return .= '<tr class="without3col"><td >' . t ( 'VISUEL_WEB' ).'</td><td class="formval">' . $oForm->createMedia($multi . "MEDIA_ID", t('VISUEL_WEB'), false, "image", "", $values['MEDIA_ID'], $readO, true, true, '', null, false, $values['MEDIA_ID_GENERIQUE']).'</td></tr>';
		$return .= '<tr class="3_colonne" ><td >' . t ( 'VISUEL_WEB_GAUCHE' ).'</td><td class="formval">' . $oForm->createMedia($multi . "MEDIA_ID5", t('VISUEL_WEB_GAUCHE'), false, "image", "", $values['MEDIA_ID5'], $readO, true, true, '', null, false, $values['MEDIA_ID5']).'</td></tr>';
		$return .= '<tr class="3_colonne"><td >' . t ( 'VISUEL_WEB_DROIT' ).'</td><td class="formval">' . $oForm->createMedia($multi . "MEDIA_ID6", t('VISUEL_WEB_DROIT'), false, "image", "", $values['MEDIA_ID6'], $readO, true, true, '', null, false, $values['MEDIA_ID6']).'</td></tr>';
		$return .= '<tr class="superposition_visuel"><td class="formlib">' . t ( 'VISUEL_WEB_SUPERPOSE' ).'</td><td class="formval">' . $oForm->createMedia($multi . "MEDIA_ID3", t('VISUEL_WEB_SUPERPOSE'), false, "image", "", $values['MEDIA_ID3'], $readO, true, true, '', null, false, $values['MEDIA_ID3']).'</td></tr>';
		$return .= self::addClass($oForm->createComboFromList($multi . "PAGE_ZONE_MULTI_LABEL5", t("POSITION_VISUEL_WEB"), Pelican::$config['TRANCHE_COL']["POSITION_TEXTE"]['GENERAL'],$values['PAGE_ZONE_MULTI_LABEL5'], false, $controller->readO),'2_colonne');
		$return .= '<tr class="media_video"><td class="formlib"></td><td class="formval">' . $oForm->createEditor($multi. "PAGE_ZONE_MULTI_TEXT3", t('TEXTE_WEB'), false, $values["PAGE_ZONE_MULTI_TEXT3"], $controller->readO, true, "", 650, 150).'</td></tr>';
		$return .= '<tr class="3_colonne"><td>' . t ( 'VISUEL_MOBILE_HAUT' ).'</td><td class="formval">' . $oForm->createMedia($multi . "MEDIA_ID7", t('VISUEL_MOBILE_BAS'), false, "image", "", $values['MEDIA_ID7'], $readO, true, true, '', null, false, $values['MEDIA_ID7']).'</td></tr>';
		//$return .= '<tr class="3_colonne" ><td>' . t ( 'VISUEL_MOBILE_BAS' ).'</td><td class="formval">' . $oForm->createMedia($multi . "MEDIA_ID8", t('VISUEL_WEB_DROIT'), false, "image", "", $values['MEDIA_ID8'], $readO, true, true, '', null, false, $values['MEDIA_ID8']).'</td></tr>';
		$return .= self::addClass($oForm->createComboFromList($multi . "PAGE_ZONE_MULTI_LABEL3", t("PSITION_TEXTE_WEB"), Pelican::$config['TRANCHE_COL']["POSITION_TEXTE"]['WEB'],$values['PAGE_ZONE_MULTI_LABEL3'], false, $controller->readO),'general');
        $return .= '<tr class="all"><td class="formlib">' . t ( 'VISUEL_MOBILE' ).'</td><td class="formval">' . $oForm->createMedia($multi . "MEDIA_ID2", t('VISUEL_MOBILE'), false, "image", "", $values['MEDIA_ID2'], $readO, true, true, '', null, false, $values['MEDIA_ID2_GENERIQUE']).'</td></tr>';
		$return .= '<tr class="superposition_visuel"><td class="formlib">' . t ( 'VISUEL_MOBILE_SUPERPOSE' ).'</td><td class="formval">' . $oForm->createMedia($multi . "MEDIA_ID4", t('VISUEL_MOBILE_SUPERPOSE'), false, "image", "", $values['MEDIA_ID4'], $readO, true, true, '', null, false, $values['MEDIA_ID4']).'</td></tr>';
        $return .= self::addClass($oForm->createComboFromList($multi . "PAGE_ZONE_MULTI_LABEL6", t("POSITION_VISUEL_MOBILE"), Pelican::$config['TRANCHE_COL']["POSITION_TEXTE"]['MOBILE'],$values['PAGE_ZONE_MULTI_LABEL6'], false, $controller->readO),'2_colonne');
		$return .= '<tr class=""><td class="formlib"></td><td class="formval">' . $oForm->createEditor($multi. "PAGE_ZONE_MULTI_TEXT4", t('TEXTE_MOBILE'), false, $values["PAGE_ZONE_MULTI_TEXT4"], $controller->readO, true, "", 650, 150).'</td></tr>';
		$return .= self::addClass($oForm->createComboFromList($multi . "PAGE_ZONE_MULTI_LABEL4", t("POSITION_VISUEL_MOBILE"), Pelican::$config['TRANCHE_COL']["POSITION_TEXTE"]['MOBILE'],$values['PAGE_ZONE_MULTI_LABEL4'], false, $controller->readO),'grand_visuel');
		 
		$aDataValues = Pelican::$config['TRANCHE_COL']["BLANK_SELF"];
        $oConnection = Pelican_Db::getInstance();
        $aBind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];

        $sql = 'SELECT
                    BARRE_OUTILS_ID ID,
                    BARRE_OUTILS_LABEL LIB
                FROM 
                    #pref#_barre_outils
                WHERE
                    BARRE_OUTILS_MODE_OUVERTURE IN (1,2)
                GROUP BY SITE_ID, LANGUE_ID, ID
                HAVING 
                    SITE_ID = :SITE_ID 
                AND LANGUE_ID = :LANGUE_ID';
        $val = $oConnection->queryTab($sql, $aBind);

        foreach ($val as $outil) {
            $aDataOutilWeb[$outil['ID']] = $outil['LIB'];
        }

		$return .= $oForm->showSeparator("formSep");
        $return .= $oForm->createComboFromList($multi . "PAGE_ZONE_MULTI_ATTRIBUT", t("CTA_OUTIL"), $aDataOutilWeb, $values["PAGE_ZONE_MULTI_ATTRIBUT"], false, $readO);
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_LABEL", t('LIBELLE'), 40, "", false, $values["PAGE_ZONE_MULTI_LABEL"], $readO, 100);
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_URL", t('URL_WEB'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL"], $readO, 100);
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_URL2", t('URL_MOB'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL2"], $readO, 100);
        $return .= $oForm->createRadioFromList($multi . "PAGE_ZONE_MULTI_MODE2", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $values['PAGE_ZONE_MULTI_MODE2'], false, $readO);
		
		$return .= $oForm->showSeparator("formSep");
		$return .= $oForm->createComboFromList($multi . "PAGE_ZONE_MULTI_ATTRIBUT2", t("CTA_OUTIL"), $aDataOutilWeb, $values["PAGE_ZONE_MULTI_ATTRIBUT2"], false, $readO);
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_LABEL2", t('LIBELLE'), 40, "", false, $values["PAGE_ZONE_MULTI_LABEL2"], $readO, 100);
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_URL4", t('URL_WEB'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL4"], $readO, 100);
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_URL3", t('URL_MOB'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL3"], $readO, 100);
		$return .= $oForm->createRadioFromList($multi . "PAGE_ZONE_MULTI_MODE4", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $values['PAGE_ZONE_MULTI_MODE4'], false, $readO);


        return $return;
    }

 


    public static function save(Pelican_Controller $controller)
    {
        
        Backoffice_Form_Helper::saveFormAffichage(); 
        parent::save();
        Backoffice_Form_Helper::savePageZoneMultiValues('POINTS_FORT_LIGHT', 'POINTS_FORT_LIGHT');
        Pelican_Cache::clean("Frontend/Cta");
    }

	/**
	**/
	public static function addClass($strTmp,$sClass){
		
		if(strpos($strTmp, '<tr>')!==false && !empty($sClass)){
			$strTmp= str_replace('<tr>','<tr class='.$sClass.'>',$strTmp);
		}
		
		return $strTmp;
		
	}
}

<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_PointsDeVente extends Cms_Page_Citroen
{

    public static function render(Pelican_Controller $controller)
    {
		$oConnection = Pelican_Db::getInstance ();
		$return .= Backoffice_Form_Helper::getFormAffichage($controller);
		$return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        

        // Code couleur
        $input = $controller->oForm->createInput($controller->multi."ZONE_TITRE3", t('OUTIL_CODE_COULEUR_ON'), 255, "", false, $controller->zoneValues["ZONE_TITRE3"], $controller->readO, 10, true);
        $return .= '<tr style="display:none;" class="outil-code-couleur outil-code-couleur-on"><td class="formlib">'.t('OUTIL_CODE_COULEUR_ON').'</td><td class="formval">'.$input.' (ex: A6A6A6)</td></tr>';
        $input = $controller->oForm->createInput($controller->multi."ZONE_TITRE4", t('OUTIL_CODE_COULEUR_OFF'), 255, "", false, $controller->zoneValues["ZONE_TITRE4"], $controller->readO, 10, true);
        $return .= '<tr style="display:none;" class="outil-code-couleur outil-code-couleur-off"><td class="formlib">'.t('OUTIL_CODE_COULEUR_OFF').'</td><td class="formval">'.$input.' (ex: A6A6A6)</td></tr>';

		$aServices =  Pelican_Cache::fetch('Frontend/Citroen/Annuaire/ServicesOrder', array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            "1"
                ));		

		if(is_array($aServices)){
		foreach ($aServices as $keyPDV => $valuePDV) {
			$filtresPDV[$valuePDV['code']] = $valuePDV['label'];
		}
                }

        // Filtre résultat point de vente (paramètre BrandActivity du webservice)
        $filterList = array(
            ''   => t("FILTRE_BRANDACTIVITY_ALL"),
            'AC' => t("FILTRE_BRANDACTIVITY_AC"),
            'DS' => t("FILTRE_BRANDACTIVITY_DS")
        );
        $filterValue = ($controller->zoneValues['PAGE_ID'] == -2 || !isset($filterList[$controller->zoneValues['ZONE_TITRE8']])) ? '' : $controller->zoneValues['ZONE_TITRE8'];
        $return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_TITRE8", t('FILTRE_PDV_BRANDACTIVITY'), $filterList, $filterValue, true, $controller->readO);

		$return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_CRITERIA_ID", t('MODE_AFFICHAGE_CARTE'), array('1' => t("FILTRE_RAYON"), '2' => t("FILTRE_PDV_VDN")), ($controller->zoneValues['PAGE_ID'] == -2) ? '1' : $controller->zoneValues['ZONE_CRITERIA_ID'], true, $controller->readO);
		if( $controller->zoneValues['TEMPLATE_PAGE_ID'] ==  Pelican::$config['TEMPLATE_PAGE']['GLOBAL']){
			$aCodePays=Pelican_Cache::fetch("Citroen/CodePaysWithSiteId", array($_SESSION[APP]['SITE_ID']));
 			$return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE14", t('WS_ADVISOR'), 200, "", true,$controller->zoneValues['ZONE_TITRE14']?$controller->zoneValues['ZONE_TITRE14']:Pelican::$config['URL_CITROEN_ADVISER'].".".strtolower($aCodePays).'/api', $controller->readO, 50);
        	$return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_TITRE15", t('CITROEN_ADVISOR_ALL'), array('1' => t("OUI"), '2' => t("NON")), ($controller->zoneValues['PAGE_ID'] == -2 || empty($controller->zoneValues['ZONE_TITRE15'])) ? '1' : $controller->zoneValues['ZONE_TITRE15'], true, $controller->readO);
		}
		$return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_TITRE7", t('BARRE_FILTRE'), array('close' => t("FERME"), 'open' => t("OUVERT")), ($controller->zoneValues['PAGE_ID'] == -2 || empty($controller->zoneValues['ZONE_TITRE7'])) ? 'close' : $controller->zoneValues['ZONE_TITRE7'], true, $controller->readO);
		$return .= $controller->oForm->createCheckBoxFromList($controller->multi . "ZONE_PARAMETERS", t('SHARER'), $filtresPDV, explode('|', $controller->zoneValues['ZONE_PARAMETERS']), false, $controller->readO);
        
		if( $controller->zoneValues['TEMPLATE_PAGE_ID'] ==  Pelican::$config['TEMPLATE_PAGE']['GLOBAL']){
			
			$return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_TITRE5", t('SHOW_EMAILS'), array('1' => t("OUI"), '2' => t("NON")), ($controller->zoneValues['PAGE_ID'] == -2 || empty($controller->zoneValues['ZONE_TITRE5'])) ? '1' : $controller->zoneValues['ZONE_TITRE5'], false, $controller->readO);
			$return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_TITRE9", t('SHOW_PHONE'), array('1' => t("OUI"), '2' => t("NON")), ($controller->zoneValues['PAGE_ID'] == -2 || empty($controller->zoneValues['ZONE_TITRE9'])) ? '2' : $controller->zoneValues['ZONE_TITRE9'], false, $controller->readO);
			$return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_TITRE10", t('SHOW_FAX'), array('1' => t("OUI"), '2' => t("NON")), ($controller->zoneValues['PAGE_ID'] == -2 || empty($controller->zoneValues['ZONE_TITRE10'])) ? '2' : $controller->zoneValues['ZONE_TITRE10'], false, $controller->readO);
			$return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_TITRE6", t('HIGHLIGHT_CONCESSIONS_CITROEN'), array('1' => t("OUI"), '2' => t("NON")), ($controller->zoneValues['PAGE_ID'] == -2 || empty($controller->zoneValues['ZONE_TITRE6'])) ? '1' : $controller->zoneValues['ZONE_TITRE6'], false, $controller->readO);
		}
		$return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_CRITERIA_ID2", t('REGROUPEMENT'), array('1' => t("OUI"), '2' => t("NON")), ($controller->zoneValues['PAGE_ID'] == -2) ? '1' : $controller->zoneValues['ZONE_CRITERIA_ID2'], false, $controller->readO);
		$return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_CRITERIA_ID3", t('AUTOCOMPLETION'), array('1' => t("OUI"), '2' => t("NON")), ($controller->zoneValues['PAGE_ID'] == -2) ? '1' : $controller->zoneValues['ZONE_CRITERIA_ID3'], false, $controller->readO);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE13", t('NB_MAX_PDV'), 4, "", true, ($controller->zoneValues['PAGE_ID'] == -2 || $controller->zoneValues['ZONE_TITRE13'] == '') ? 300 : $controller->zoneValues['ZONE_TITRE13'], $controller->readO, 3);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_ATTRIBUT", t('RAYON'), 3, "", true, ($controller->zoneValues['PAGE_ID'] == -2 || $controller->zoneValues['ZONE_ATTRIBUT'] == '') ? 20 : $controller->zoneValues['ZONE_ATTRIBUT'], $controller->readO, 3);
		$return .= $controller->oForm->createInput($controller->multi."ZONE_ATTRIBUT2", t('NB_PDV'), 2, "", false, ($controller->zoneValues['PAGE_ID'] == -2) ? 5 : $controller->zoneValues['ZONE_ATTRIBUT2'], $controller->readO, 2);
		$return .= $controller->oForm->createInput($controller->multi."ZONE_ATTRIBUT3", t('NB_DVN'), 2, "", false, ($controller->zoneValues['PAGE_ID'] == -2) ? 2 : $controller->zoneValues['ZONE_ATTRIBUT3'], $controller->readO, 2);
		$return .= Backoffice_Form_Helper::getOutils($controller, true, true, 0, 5, true);
		//$return .= Backoffice_Form_Helper::getCta($controller, 3);
        
        // Définition des métiers pour chaque typologie d'outils
        $stmt = "SELECT * FROM #pref#_referentiel_outils WHERE SITE_ID = :SITE_ID AND LANGUE_ID = :LANGUE_ID;";
        $bind = array();
        $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $bind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $result = $oConnection->queryTab($stmt, $bind);
        $checkedServices = !empty($controller->zoneValues['ZONE_TEXTE']) ? json_decode($controller->zoneValues['ZONE_TEXTE']) : array();
        foreach ($result as $key => $val) {
            $typoLabel = isset(Pelican::$config['REFOUTIL_TYPES'][$val['TYPE']]) ? t(Pelican::$config['REFOUTIL_TYPES'][$val['TYPE']]) : '#'.$val['TYPE'];
            $groupLabel = sprintf(t('REFOUTIL_CHOIX_SERVICE_PDV'), $typoLabel);
            $checked = isset($checkedServices->$val['TYPE']) ? $checkedServices->$val['TYPE'] : array();
            $return .= $controller->oForm->createCheckBoxFromList($controller->multi."_typologieServicePdv[".$val['TYPE']."]", $groupLabel, $filtresPDV, $checked, false, $controller->readO);
        }
		
		$return .= $controller->oForm->createJS("
			var selectModeAffichage = $('input[type=radio][name=".$controller->multi."ZONE_CRITERIA_ID]:checked').attr('value');
			var zoneAttr = $('#".$controller->multi."ZONE_ATTRIBUT').val();
			var zoneAttr2 = $('#".$controller->multi."ZONE_ATTRIBUT2').val();
			var zoneAttr3 = $('#".$controller->multi."ZONE_ATTRIBUT3').val();

			if( selectModeAffichage == 1 ){
				if(zoneAttr == ''){
					alert('".t('VOUS_DEVEZ_SAISIR_UN_RAYON', 'js')."');
					$('#FIELD_BLANKS').val(1);
				}	
			if(!isNumeric(zoneAttr)){
				alert('".t('ALERT_NUMERIC_RAYON', 'js')."');
				$('#FIELD_BLANKS').val(1);
			}
			}
			
			if( selectModeAffichage == 2 ){
				if(zoneAttr2 == ''){
					alert('".t('VOUS_DEVEZ_SAISIR_UN_PDV', 'js')."');
					$('#FIELD_BLANKS').val(1);
				}
				if(zoneAttr3 == ''){
					alert('".t('VOUS_DEVEZ_SAISIR_UN_DVN', 'js')."');
					$('#FIELD_BLANKS').val(1);
				}
			if(!isNumeric(zoneAttr2)){
				alert('".t('ALERT_NUMERIC_NB_PDV', 'js')."');
				$('#FIELD_BLANKS').val(1);
			}
			if(!isNumeric(zoneAttr3)){
				alert('".t('ALERT_NUMERIC_NB_DVN', 'js')."');
				$('#FIELD_BLANKS').val(1);
			}
			}			
        ");
        return $return;
    }
		
    public static function save(Pelican_Controller $controller)
    {
        // Serialisation des métiers / typologie outil
        Pelican_Db::$values['ZONE_TEXTE'] = isset(Pelican_Db::$values['_typologieServicePdv']) ? json_encode(Pelican_Db::$values['_typologieServicePdv']) : null;
        
        Backoffice_Form_Helper::saveOutils(); 
        Backoffice_Form_Helper::saveFormAffichage();
           if (Pelican_Db::$values['ZONE_PARAMETERS']) {
                    Pelican_Db::$values['ZONE_PARAMETERS'] = implode('|', Pelican_Db::$values['ZONE_PARAMETERS']);
            }
			
        parent::save();
        Backoffice_Form_Helper::saveCta();
        Pelican_Cache::clean('Frontend/Citroen/Annuaire/DealerList');
        Pelican_Cache::clean('Frontend/Citroen/Annuaire/ServicesOrder');
        Pelican_Cache::clean('Frontend/Citroen/Annuaire/MapConf');
		
    }
	
	

}
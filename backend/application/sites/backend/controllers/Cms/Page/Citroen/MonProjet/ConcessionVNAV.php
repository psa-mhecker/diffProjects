<?php

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';

/**
 * Classe d'administration de la tranche Concession VN ou AV de Mon projet.
 */
class Cms_Page_Citroen_MonProjet_ConcessionVNAV extends Cms_Page_Citroen
{
    /**
     * Affichage du formulaire.
     *
     * @param Pelican_Controller $oController
     */
    public static function render(Pelican_Controller $oController)
    {
        $return .= $oController->oForm->createInput($oController->multi."ZONE_TITRE", t('TITRE'), 255, "", true, $oController->zoneValues['ZONE_TITRE'], $oController->readO, 75);
        $return .= $oController->oForm->createEditor($oController->multi."ZONE_TEXTE", t('TEXTE'), true, $oController->zoneValues['ZONE_TEXTE'], $oController->readO, true, "", 465, 200);

        $return .= $oController->oForm->createRadioFromList($oController->multi."ZONE_CRITERIA_ID", t('MODE_AFFICHAGE_CARTE'), array('1' => t("FILTRE_RAYON"), '2' => t("FILTRE_PDV_VDN")), ($oController->zoneValues['PAGE_ID'] == -2) ? '1' : $oController->zoneValues['ZONE_CRITERIA_ID'], true, $oController->readO);
        $return .= $oController->oForm->createRadioFromList($oController->multi."ZONE_CRITERIA_ID2", t('REGROUPEMENT'), array('1' => t("OUI"), '2' => t("NON")), ($oController->zoneValues['PAGE_ID'] == -2) ? '1' : $oController->zoneValues['ZONE_CRITERIA_ID2'], false, $oController->readO);
        $return .= $oController->oForm->createRadioFromList($oController->multi."ZONE_CRITERIA_ID3", t('AUTOCOMPLETION'), array('1' => t("OUI"), '2' => t("NON")), ($oController->zoneValues['PAGE_ID'] == -2) ? '1' : $oController->zoneValues['ZONE_CRITERIA_ID3'], false, $oController->readO);
        $return .= $oController->oForm->createRadioFromList($oController->multi."ZONE_PARAMETERS", t('VN_APV'), array('favoris_vn' => t("VN"), 'favoris_av' => t("AV")), ($oController->zoneValues['PAGE_ID'] == -2) ? '1' : $oController->zoneValues['ZONE_PARAMETERS'], true, $oController->readO);
        $return .= $oController->oForm->createInput($oController->multi."ZONE_ATTRIBUT", t('RAYON'), 3, "", true, ($oController->zoneValues['PAGE_ID'] == -2) ? 15 : $oController->zoneValues['ZONE_ATTRIBUT'], $oController->readO, 3);
        $return .= $oController->oForm->createInput($oController->multi."ZONE_ATTRIBUT2", t('NB_PDV'), 2, "", true, ($oController->zoneValues['PAGE_ID'] == -2) ? 5 : $oController->zoneValues['ZONE_ATTRIBUT2'], $oController->readO, 2);
        $return .= $oController->oForm->createInput($oController->multi."ZONE_ATTRIBUT3", t('NB_DVN'), 2, "", true, ($oController->zoneValues['PAGE_ID'] == -2) ? 2 : $oController->zoneValues['ZONE_ATTRIBUT3'], $oController->readO, 2);
        $return .= Backoffice_Form_Helper::getOutils($oController, true, true, 0, 2, true);
        $return .= $oController->oForm->createJS("
			var zoneAttr = $('#".$oController->multi."ZONE_ATTRIBUT').val();
			var zoneAttr2 = $('#".$oController->multi."ZONE_ATTRIBUT2').val();
			var zoneAttr3 = $('#".$oController->multi."ZONE_ATTRIBUT3').val();
			if(!isNumeric(zoneAttr)){
				alert('".t('ALERT_NUMERIC_RAYON', 'js')."');
				return false;
			}
			if(!isNumeric(zoneAttr2)){
				alert('".t('ALERT_NUMERIC_NB_PDV', 'js')."');
				return false;
			}
			if(!isNumeric(zoneAttr3)){
				alert('".t('ALERT_NUMERIC_NB_DVN', 'js')."');
				return false;
			}
        ");

        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        Backoffice_Form_Helper::saveOutils();
        parent::save();
    }
}

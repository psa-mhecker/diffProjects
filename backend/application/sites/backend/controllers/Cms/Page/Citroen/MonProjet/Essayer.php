<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
/**
 * Classe d'administration de la tranche Essayer de Mon projet.
 */
class Cms_Page_Citroen_MonProjet_Essayer extends Cms_Page_Citroen
{
    /**
     * Affichage du formulaire.
     *
     * @param Pelican_Controller $oController
     */
    public static function render(Pelican_Controller $oController)
    {
        $return .= Backoffice_Form_Helper::getFormAffichage($oController, true, false);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($oController);
        // @TODO : Formulaire
        $return .= $oController->oForm->createHidden($oController->multi."ZONE_ATTRIBUT2", 2);
        $oConnection = Pelican_Db::getInstance();
        $aBind[':ZONE_ID'] = Pelican::$config['ZONE']['FORMULAIRE'];
        $sSQL = "
            SELECT
                ZONE_TITRE2,
                pzt.ZONE_TEMPLATE_ID,
                p.LANGUE_ID,
                p.PAGE_ID
            FROM
                #pref#_page_zone pz
                INNER JOIN #pref#_zone_template pzt ON (pz.ZONE_TEMPLATE_ID = pzt.ZONE_TEMPLATE_ID)
                INNER JOIN #pref#_page_version pv ON (pz.PAGE_ID = pv.PAGE_ID and pz.PAGE_VERSION = pv.PAGE_VERSION and pz.LANGUE_ID = pv.LANGUE_ID)
                INNER JOIN #pref#_page p ON (p.PAGE_ID = pv.PAGE_ID and p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION and p.LANGUE_ID = pv.LANGUE_ID)
            where
                pzt.ZONE_ID = :ZONE_ID
        ";
        $pagezone = $oConnection->queryTab($sSQL, $aBind);
        $aTempPageZone = array();
        if (is_array($pagezone) && count($pagezone)>0) {
            foreach ($pagezone as $zone) {
                $aTempPageZone[$zone['PAGE_ID'].'_'.$zone['LANGUE_ID'].'_'.$zone['ZONE_TEMPLATE_ID']] = $zone['ZONE_TITRE2'];
            }
        }
        $sSQL = "
            SELECT
                ZONE_TITRE2,
                ZONE_ID,
                AREA_ID,
                ZONE_ORDER,
                p.LANGUE_ID,
                p.PAGE_ID
            FROM
                #pref#_page_multi_zone pmz
                INNER JOIN #pref#_page_version pv ON (pmz.PAGE_ID = pv.PAGE_ID and pmz.PAGE_VERSION = pv.PAGE_VERSION and pmz.LANGUE_ID = pv.LANGUE_ID)
                INNER JOIN #pref#_page p ON (p.PAGE_ID = pv.PAGE_ID and p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION and p.LANGUE_ID = pv.LANGUE_ID)
            where
                pmz.ZONE_ID = :ZONE_ID
        ";
        $pagemultizone = $oConnection->queryTab($sSQL, $aBind);

        $aTempPageMultiZone = array();
        if (is_array($pagemultizone) && count($pagemultizone)>0) {
            foreach ($pagemultizone as $zone) {
                $aTempPageMultiZone[$zone['PAGE_ID'].'_'.$zone['LANGUE_ID'].'__'.$zone['ZONE_ID'].'_'.$zone['AREA_ID'].'_'.$zone['ZONE_ORDER']] = $zone['ZONE_TITRE2'];
            }
        }
        $aPageValues = array();
        if (count($aTempPageZone)>0 && count($aTempPageMultiZone)>0) {
            $aPageValues = array_replace($aTempPageZone, $aTempPageMultiZone);
        } elseif (count($aTempPageZone)>0 && empty($aTempPageMultiZone)) {
            $aPageValues = $aTempPageZone;
        } elseif (count($aTempPageMultiZone)>0 && empty($aTempPageZone)) {
            $aPageValues = $aTempPageMultiZone;
        }
        $return .= $oController->oForm->createComboFromList($oController->multi."ZONE_TITRE13", t("LISTE_FORM"), $aPageValues, $oController->zoneValues['ZONE_TITRE13'], true, $oController->readO);

        return $return;
    }

    public static function save(Pelican_Controller $oController)
    {
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
    }
}

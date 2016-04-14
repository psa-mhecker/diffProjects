<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_PromotionListShowroom extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $aBind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $sqlData = "select
            VEHICULE_ID, VEHICULE_LABEL
            from #pref#_vehicule
            where SITE_ID=:SITE_ID
            and LANGUE_ID=:LANGUE_ID
            order by VEHICULE_LABEL";
        $values = $oConnection->queryTab($sqlData, $aBind);
        $aCombo = array();
        foreach ($values as $OneValue) {
            $aCombo[$OneValue['VEHICULE_ID']] = $OneValue['VEHICULE_LABEL'];
        }
        unset($values);
        $return .= $controller->oForm->createComboFromList($controller->multi."ZONE_TITRE", t('SELECTION_VEHICULES'), $aCombo, $controller->zoneValues["ZONE_TITRE"], true, $controller->readO);

        return $return;
    }

    public static function save()
    {
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
    }
}

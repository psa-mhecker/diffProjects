<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_Global_Footer_Abonnements extends Cms_Page_Citroen
{

    public static function render(Pelican_Controller $controller)
    {
        $oConnection = Pelican_Db::getInstance();
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi."ZONE_TITRE10", t('AFFICHER'), array(1 => ""), $controller->zoneValues['ZONE_TITRE10'], false, $controller->readO);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE_WEB'), 40, "", false, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 40);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE4", t('TITRE_MOBILE'), 40, "", false, $controller->zoneValues['ZONE_TITRE4'], $controller->readO, 40);
        $sSQL = "
            select
                RESEAU_SOCIAL_ID as id,
                RESEAU_SOCIAL_LABEL as lib
            from #pref#_reseau_social
            where SITE_ID = " . $_SESSION[APP]['SITE_ID'] . "
            and LANGUE_ID = " . $controller->zoneValues['LANGUE_ID'] . "
            order by RESEAU_SOCIAL_ORDER asc";
        
        $aSelectedValues  = explode('|', $controller->zoneValues['ZONE_PARAMETERS']);
        $return .= $controller->oForm->createComboFromSql($oConnection, $controller->multi."ZONE_PARAMETERS", t('RESEAUX_SOCIAUX'), $sSQL, $aSelectedValues, true, $controller->readO, 5, true, 120, false);
        $return .= $controller->oForm->showSeparator();
        $return .= $controller->oForm->createLabel(t('ABONNEMENT_NEWSLETTER'), "");
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi."ZONE_TITRE11", t('AFFICHER_ZONE_ABONNEMENT'), array(1 => ""), $controller->zoneValues['ZONE_TITRE11'], false, $controller->readO);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('LIBELLE'), 50, "", false, $controller->zoneValues['ZONE_TITRE2'], $controller->readO, 50);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_URL2", t('URL_PAGE_ABONNEMENT_NEWSLETTER'), 255, "internallink", false, $controller->zoneValues['ZONE_URL2'], $controller->readO, 75);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE3", t('LIBELLE_BOUTON_VALIDATION'), 20, "", false, $controller->zoneValues['ZONE_TITRE3'], $controller->readO, 20);
        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        if (Pelican_Db::$values['ZONE_PARAMETERS']) {
            Pelican_Db::$values['ZONE_PARAMETERS'] = implode('|', Pelican_Db::$values['ZONE_PARAMETERS']);
        }
        parent::save();
    }

}
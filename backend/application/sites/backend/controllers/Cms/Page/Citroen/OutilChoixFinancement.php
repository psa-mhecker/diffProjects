<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_OutilChoixFinancement extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $return .= Backoffice_Form_Helper::getFormAffichage($controller, true, false);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);

        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 255, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 50);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('SOUS_TITRE'), 255, "", false, $controller->zoneValues["ZONE_TITRE2"], $controller->readO, 50);
        $return .= $controller->oForm->createEditor($controller->multi."ZONE_TEXTE", t('Content'), false, $controller->zoneValues["ZONE_TEXTE"], $controller->readO, true, "", 500, 150);

        $return .= Backoffice_Form_Helper::getMentionsLegales($controller, false, 'cinemascope');

        return $return;
    }

    public static function save()
    {
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
    }
}

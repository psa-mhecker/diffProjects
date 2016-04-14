<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_TexteRiche extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        $return .= $controller->oForm->createEditor($controller->multi."ZONE_TEXTE4", t('TEXTE'), true, $controller->zoneValues["ZONE_TEXTE4"], $controller->readO, true, "", 650, 150);

        return $return;
    }

    public static function save()
    {
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
    }
}

<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_Cookies extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $return = Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= $controller->oForm->createEditor($controller->multi."ZONE_TEXTE", t('Content'), true, $controller->zoneValues["ZONE_TEXTE"], $controller->readO, true, "", 500, 150);

        return $return;
    }

    public static function save()
    {
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
    }
}

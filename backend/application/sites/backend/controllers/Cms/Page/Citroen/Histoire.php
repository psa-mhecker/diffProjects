<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_Histoire extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $return .= $controller->oForm->createComboFromList($controller->multi."ZONE_ATTRIBUT", t('VERSION_DATE'), array(1 => 'UK (MM/JJ/AAAA)', 2 => 'FR (JJ/MM/AAAA)'), $controller->values['ZONE_ATTRIBUT'], true, $controller->readO);

        return $return;
    }

    /*Enregistrement complémentaires multi
     */
    public static function save()
    {
        parent::save();
        Pelican_Cache::clean("Frontend_Citroen_ListeHistoire", array($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']));
    }
}

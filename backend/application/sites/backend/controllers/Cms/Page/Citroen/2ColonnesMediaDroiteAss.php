<?php

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';

class Cms_Page_Citroen_2ColonnesMediaDroiteAss extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $return .= Backoffice_Form_Helper::getForm($controller->zoneValues["ZONE_BO_PATH"], $controller);

        return $return;
    }

    /*Enregistrement complémentaires multi
     */
    public static function save()
    {
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
        if (Pelican_Db::$values["form_action"] != Pelican_Db::DATABASE_DELETE) {
            Backoffice_Form_Helper::saveCta();
            Backoffice_Form_Helper::savePushGallery();
        }
    }
}

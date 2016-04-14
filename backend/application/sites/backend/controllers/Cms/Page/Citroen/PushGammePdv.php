<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_PushGammePdv extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $return .= Backoffice_Form_Helper::getFormAffichage($controller, false, true);

        // CTA Gamme
        $return .= $controller->oForm->createLabel(t('CTA_GAMME'), "");
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('LIBELLE'), 40, "", true, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 50);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE5", t('LIBELLE_BOUTON'), 40, "", true, $controller->zoneValues["ZONE_TITRE5"], $controller->readO, 50);
        $return .= $controller->oForm->createMedia($controller->multi."MEDIA_ID", t('IMAGE_GAMME'), true, "image", "", $controller->zoneValues["MEDIA_ID"], $controller->readO, true, false, 'portrait');
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('LINK'), 255, "internallink", true, $controller->zoneValues["ZONE_TITRE2"], $controller->readO, 100);
        $return .= $controller->oForm->createRadioFromList($controller->multi.'ZONE_ATTRIBUT', t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $controller->zoneValues['ZONE_ATTRIBUT'], false, $controller->readO);

        // CTA PDV
        $return .= $controller->oForm->createLabel(t('CTA_PDV'), "");
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE3", t('LIBELLE'), 40, "", true, $controller->zoneValues["ZONE_TITRE3"], $controller->readO, 50);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE6", t('LIBELLE_BOUTON'), 40, "", true, $controller->zoneValues["ZONE_TITRE6"], $controller->readO, 50);
        $return .= $controller->oForm->createMedia($controller->multi."MEDIA_ID2", t('IMAGE_PDV'), true, "image", "", $controller->zoneValues["MEDIA_ID2"], $controller->readO, true, false, 'portrait');
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE4", t('LINK'), 255, "internallink", true, $controller->zoneValues["ZONE_TITRE4"], $controller->readO, 100);
        $return .= $controller->oForm->createRadioFromList($controller->multi.'ZONE_ATTRIBUT2', t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $controller->zoneValues['ZONE_ATTRIBUT2'], false, $controller->readO);

        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        Backoffice_Form_Helper::saveFormAffichage();
        $oConnection = Pelican_Db::getInstance();

         // {CODE}

         parent::save();
    }
}

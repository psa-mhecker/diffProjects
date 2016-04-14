<?php
include_once Pelican::$config['CONTROLLERS_ROOT']."/Cms/Page/Module.php";
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_SlideShowOffre extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $return  = $controller->oForm->createMultiHmvc($controller->multi."SLIDEOFFREADDFORM", t('SLIDE_OFFRE_ADD_FORM'), array(
         "path" => __FILE__,
         "class" => __CLASS__,
         "method" => "slideOffreAddForm",
        ), Backoffice_Form_Helper::getPageZoneMultiValues($controller, 'SLIDEOFFREADDFORM'), $controller->multi."SLIDEOFFREADDFORM", $controller->readO, "12", true, true, $controller->multi."SLIDEOFFREADDFORM", "values", "multi", "2", "", "", $controller->zoneValues);

        return $return;
    }

    public static function slideOffreAddForm($oForm, $values, $readO, $multi, $aData)
    {
        if (!empty($values["PAGE_ZONE_MULTI_ID"])) {
            $offre .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_ID", $values["PAGE_ZONE_MULTI_ID"]);
        }
        $offre .= $oForm->createInput($multi."PAGE_ZONE_MULTI_TITRE", t('TITRE'), 255, "", false, $values["PAGE_ZONE_MULTI_TITRE"], $readO, 50);
        $offre .= $oForm->createMedia($multi."MEDIA_ID", t('IMAGE_WEB'), false,  "image", "", $values['MEDIA_ID'], $readO, true, false, 'offre', null, false, $values['MEDIA_ID_GENERIQUE']);
        $offre .= $oForm->createMedia($multi."MEDIA_ID2", t('IMAGE_MOBILE'), false,  "image", "", $values['MEDIA_ID2'], $readO, true, false, 'offre', null, false, $values['MEDIA_ID2_GENERIQUE']);
        $offre .= $oForm->createInput($multi."PAGE_ZONE_MULTI_URL", t('URL_WEB'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL"], $readO, 50);
        $offre .= $oForm->createInput($multi."PAGE_ZONE_MULTI_URL2", t('URL_MOBILE'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL2"], $readO, 50);
        $offre .= $oForm->createRadioFromList($multi.'PAGE_ZONE_MULTI_ATTRIBUT', t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $values['PAGE_ZONE_MULTI_ATTRIBUT'], false, $readO);

        return $offre;
    }

    public static function save(Pelican_Controller $controller)
    {
        $sMultiName = 'SLIDEOFFREADDFORM';
        parent::save();
        Backoffice_Form_Helper::savePageZoneMultiValues($sMultiName, $sMultiName);
        Pelican_Cache::clean("Frontend/Citroen/ZoneMulti");
    }
}

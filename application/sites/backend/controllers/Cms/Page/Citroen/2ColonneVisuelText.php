<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Module.php');
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');

class Cms_Page_Citroen_2ColonneVisuelText extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE", t ( 'TITRE' ), 255, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 50);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE2", t ( 'SOUS_TITRE' ), 255, "", false, $controller->zoneValues["ZONE_TITRE2"], $controller->readO, 50);
        $return .= $controller->oForm->createEditor($controller->multi . "ZONE_TEXTE", t('CHAPEAU'), false, $controller->zoneValues["ZONE_TEXTE"], $controller->readO, true, "", 500, 150);


        $sMultiName = $controller->multi .'ADD_VISUEL_TEXTE';
        $return .= $controller->oForm->createMultiHmvc(
                $sMultiName,
                t('VISUEL_TEXTE_FORM'),
                array(
                    'path' => __FILE__,
                    'class' => __CLASS__,
                    'method' => 'addVisuelTexteForm'
                 ),
                Backoffice_Form_Helper::getPageZoneMultiValues($controller,'VISUEL_TEXTE'),
                $sMultiName, $controller->readO, 12, true, true, $sMultiName
                );

        $return .= Backoffice_Form_Helper::getMentionsLegales($controller, false, 'cinemascope');
        $return .= Backoffice_Form_Helper::getPushMediaCommun($controller);
        $return .= Backoffice_Form_Helper::getCta($controller, 3);

        return $return;
    }

    public static function addVisuelTexteForm ($oForm, $aValues, $mReadO, $sMultiLabel)
    {
        $sMultiForm .= $oForm->createInput($sMultiLabel . 'PAGE_ZONE_MULTI_TITRE', t('TITLE'), 255, '', false, $aValues['PAGE_ZONE_MULTI_TITRE'], $mReadO, 75);
        $sMultiForm .= $oForm->createMedia($sMultiLabel . 'MEDIA_ID', t ('VISUEL'), true, 'image', '', $aValues['MEDIA_ID'], $mReadO, true, false, "16_9");
        $sMultiForm .= $oForm->createEditor($sMultiLabel . "PAGE_ZONE_MULTI_TEXT", t('TEXTE'), true, $aValues["PAGE_ZONE_MULTI_TEXT"], $mReadO, true, "", 500, 150);

        return $sMultiForm;
    }

    public static function save()
    {
		Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
        Backoffice_Form_Helper::savePageZoneMultiValues('ADD_VISUEL_TEXTE', 'VISUEL_TEXTE');
        Backoffice_Form_Helper::saveCta();
        Backoffice_Form_Helper::savePushGallery();

        Pelican_Cache::clean("Frontend/Citroen/ZoneMulti");
    }
}
?>

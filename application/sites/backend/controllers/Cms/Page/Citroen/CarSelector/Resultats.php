<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
include_once(Pelican::$config['CONTROLLERS_ROOT']."/Cms/Page/Module.php");

class Cms_Page_Citroen_CarSelector_Resultats extends Cms_Page_Citroen
{
	public static $decacheBack = array(
        array('Frontend/Citroen/CarSelector/AutresVehicules', 
            array('ZONE_TEMPLATE_ID','SITE_ID', 'LANGUE_ID') 
        )
    );
	public static $decachePublication = array(
        array('Frontend/Citroen/CarSelector/AutresVehicules', 
            array('ZONE_TEMPLATE_ID','SITE_ID', 'LANGUE_ID') 
        )
    );
	
    public static function render(Pelican_Controller $controller)
    {	
		$oConnection = Pelican_Db::getInstance ();
		$return .= $controller->oForm->createEditor($controller->multi . "ZONE_TEXTE", t('TEXTE_NO_RESULTS'), true, $controller->zoneValues["ZONE_TEXTE"], $controller->readO, true, "", 500, 150);
		$return .= $controller->oForm->showSeparator("formsep", false);
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi."ZONE_TITRE11", t('COMPARER_AVEC_UN_AUTRE_MODELE_CITROEN'), array('1' => ""), ($controller->zoneValues)?$controller->zoneValues['ZONE_TITRE11']:1, false, $controller->readO);

        $aBind[':PAGE_ID'] = $controller->zoneValues['PAGE_ID'];
        $aBind[':LANGUE_ID'] = $controller->zoneValues['LANGUE_ID'];
        $aBind[':PAGE_VERSION'] = $controller->zoneValues['PAGE_VERSION'];
        $aBind[':ZONE_TEMPLATE_ID'] = $controller->zoneValues['ZONE_TEMPLATE_ID'];
		$sSQL = "SELECT 
					* 
				FROM 
					#pref#_page_zone_multi 
				WHERE 
					PAGE_ID=:PAGE_ID 
				AND LANGUE_ID=:LANGUE_ID 
				AND PAGE_VERSION=:PAGE_VERSION
				AND ZONE_TEMPLATE_ID=:ZONE_TEMPLATE_ID";
        $multiValues = $oConnection->queryTab($sSQL, $aBind);
		$return .= $controller->oForm->createMultiHmvc($controller->multi."CARFORM", t('ADD_OTHER_CARS'), array(
            'path' => __FILE__,
            'class' => __CLASS__,
            'method' => 'addVehiculeForm'
        ), Backoffice_Form_Helper::getPageZoneMultiValues($controller), $controller->multi."CARFORM", $controller->readO, "", true, true, $controller->multi."CARFORM");
		$return .= Backoffice_Form_Helper::getMentionsLegales($controller, false, 'cinemascope');
        return $return;
    }
	
	public static function addVehiculeForm($oForm, $values, $readO, $multi)
    {
        $return .= $oForm->createInput($multi."PAGE_ZONE_MULTI_TITRE", t ('TITRE'), 255, "", true, $values["PAGE_ZONE_MULTI_TITRE"], $readO, 100);
		$return .= $oForm->createMedia($multi."MEDIA_ID", t ('VISUEL'), true, "image", "", $values['MEDIA_ID'], $readO, true, false, '16_9');
		$return .= $oForm->createInput($multi. "PAGE_ZONE_MULTI_URL", t('URL'), 255, "internallink", true, $values["PAGE_ZONE_MULTI_URL"], $readO, 50, false);
        return $return;
    }
	
    public static function save(Pelican_Controller $controller)
    {
        parent::save();
		Backoffice_Form_Helper::savePageZoneMultiValues('CARFORM');
    }

}
<?php
include_once(Pelican::$config['CONTROLLERS_ROOT']."/Cms/Page/Module.php");
class Citroen_Page_Module_lbo extends Cms_Page_Module
{

    public static function render(Pelican_Controller $controller)
    {	
		$oConnection = Pelican_Db::getInstance ();
		$return .= $controller->oForm->createEditor($controller->multi . "ZONE_TEXTE", t('TEXTE_NO_RESULTS'), true, $controller->zoneValues["ZONE_TEXTE"], $controller->readO, true, "", 500, 150);
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
            "path" => Pelican::$config["APPLICATION_CONTROLLERS"] . "/Citroen/Page/Module/lbo.php",
            "class" => "Citroen_Page_Module_lbo",
            "method" => "addVehiculeForm"
        ), $multiValues, $controller->multi."CARFORM", $controller->readO, array(4, 7), true, true, $controller->multi."CARFORM");
		$return .= Backoffice_Form_Helper::getMentionsLegales($controller);
        return $return;
    }
	
	public static function addVehiculeForm($oForm, $values, $readO, $multi)
    {
        $return .= $oForm->createInput($multi."PAGE_ZONE_MULTI_TITRE", t ('TITRE'), 255, "", true, $values["PAGE_ZONE_MULTI_TITRE"], $readO, 100);
		//$return .= $oForm->createMedia($multi."MEDIA_ID", t ('VISUEL'), true, "image", "", $values['MEDIA_ID'], $readO);
		$return .= $oForm->createInput($multi. "PAGE_ZONE_MULTI_URL", t('URL'), 255, "internallink", true, $values["PAGE_ZONE_MULTI_URL"], $readO, 50, false);
        return $return;
    }
	
    public static function save(Pelican_Controller $controller)
    {
        parent::save();
		readMulti("CARFORM","CARFORM");
        
		$oConnection = Pelican_Db::getInstance ();
		$aBind[':PAGE_ID'] = Pelican_Db::$values['PAGE_ID'];
        $aBind[':LANGUE_ID'] = Pelican_Db::$values['LANGUE_ID'];
        $aBind[':PAGE_VERSION'] = Pelican_Db::$values['PAGE_VERSION'];
        $aBind[':ZONE_TEMPLATE_ID'] = Pelican_Db::$values['ZONE_TEMPLATE_ID'];
        $sSQL = "delete from #pref#_page_zone_multi where PAGE_ID=:PAGE_ID and LANGUE_ID=:LANGUE_ID and PAGE_VERSION=:PAGE_VERSION and ZONE_TEMPLATE_ID=:ZONE_TEMPLATE_ID";
        $oConnection->query($sSQL, $aBind);
        if (Pelican_Db::$values['CARFORM']) {
            foreach (Pelican_Db::$values['CARFORM'] as $i => $item) {
                if ($item['multi_display'] == 1) {
                    $id++;
                    $DBVALUES_SAVE = Pelican_Db::$values;
                    Pelican_Db::$values['PAGE_ZONE_MULTI_ID'] = $id;
                    Pelican_Db::$values['PAGE_ZONE_MULTI_ORDER'] = $id;
                    Pelican_Db::$values['PAGE_ZONE_MULTI_TITRE'] = $item['PAGE_ZONE_MULTI_TITRE'];
                    Pelican_Db::$values['MEDIA_ID'] = $item['MEDIA_ID'];
                    Pelican_Db::$values['PAGE_ZONE_MULTI_URL'] = $item['PAGE_ZONE_MULTI_URL'];
                    $oConnection->updateTable(Pelican::$config['DATABASE_INSERT'], "#pref#_page_zone_multi");
                    Pelican_Db::$values = $DBVALUES_SAVE;
                }
            }
        }
		if(Pelican_Db::$values['STATE_ID'] == 4){
			Pelican_Cache::clean("Frontend/Citroen/CarSelector/AutresVehicules");
		}
    }
}
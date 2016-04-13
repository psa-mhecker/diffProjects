<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
include_once(Pelican::$config['CONTROLLERS_ROOT']."/Cms/Page/Module.php");

class Cms_Page_Citroen_ResultatsRecherche extends Cms_Page_Citroen
{

    public static function render(Pelican_Controller $controller)
    {
		$oConnection = Pelican_Db::getInstance ();
		$return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 255, "", true, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 75);

		/* Sélection d'outils pour la zone */
        $return .= Backoffice_Form_Helper::getOutils($controller, true, false, 3, 7, false);
		
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
		$return .= $controller->oForm->createMultiHmvc($controller->multi."TERME", t('ADD_TERME'), array(
            'path' => __FILE__,
            'class' => __CLASS__,
            'method' => 'addTermeForm'
        ), Backoffice_Form_Helper::getPageZoneMultiValues($controller), $controller->multi . "TERME", $controller->readO, 6, true, true, $controller->multi . "TERME");
                /*$return .= $controller->oForm->createJs(" 
            var i = 0;
            var j = 0;
            
            var flag = 0;

            while(flag == 0)
            {
                 if(document.getElementById('" . $controller->multi . "' + i + '_PAGE_ZONE_MULTI_LABEL'))
                 {
                 
                  if(document.getElementById('" . $controller->multi . "' + i + '_multi_display').value != 0)
                    {
                    j++;
                    }
                 }
                 else
                 {
                    flag = 1;
                 }
                 i++;
            }
            if( j < 3){
             alert('".t('FONCTIONNEMENT_OUTILS_RECHERCHE', 'js')."');
            return false;
            
            }

");*/

        return $return;
    }
	
	public static function addTermeForm($oForm, $values, $readO, $multi)
    {
        $return = $oForm->createInput ($multi . "PAGE_ZONE_MULTI_LABEL", t ( 'TERME' ), 255, "", true, $values["PAGE_ZONE_MULTI_LABEL"], $readO, 100);
        return $return;
    }
	
    public static function save(Pelican_Controller $controller)
    {

		Backoffice_Form_Helper::saveOutils();
		Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
		Backoffice_Form_Helper::savePageZoneMultiValues('TERME');
    }
}
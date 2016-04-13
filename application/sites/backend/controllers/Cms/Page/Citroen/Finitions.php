<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_Finitions extends Cms_Page_Citroen
{
	public static $decacheBack = array(
        array('Frontend/Citroen/Finitions'),
        array('Frontend/Citroen/Finitions/Caracteristiques'),
        array('Frontend/Citroen/Finitions/EngineList'),
        array('Frontend/Citroen/Finitions/Equipement')
    );
	public static $decachePublication = array(
        array('Frontend/Citroen/Finitions'),
        array('Frontend/Citroen/Finitions/Caracteristiques'),
        array('Frontend/Citroen/Finitions/EngineList'),
        array('Frontend/Citroen/Finitions/Equipement')
    );
    public static function render(Pelican_Controller $controller)
    {	
		$oConnection = Pelican_Db::getInstance ();
		$return .= Backoffice_Form_Helper::getFormAffichage($controller);
		$return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
		$return .= $controller->oForm->createInput ($controller->multi."ZONE_TITRE", t('TITRE'), 255, "", true, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 100);
		$return .= Backoffice_Form_Helper::getVehicule($controller);
		$return .= Backoffice_Form_Helper::getMentionsLegales($controller);
                
                
        return $return;
    }
		
    public static function save(Pelican_Controller $controller)
    {
        Backoffice_Form_Helper::saveFormAffichage();
		parent::save();	
		if(Pelican_Db::$values['STATE_ID'] == 4){
			Pelican_Cache::clean("Frontend/Citroen/Finitions");
		}
    }

}
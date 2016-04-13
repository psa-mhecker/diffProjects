<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php');

/**
 * Classe d'administration de la tranche Expand Gamme C, DS
 *
 * @package Page
 * @subpackage Citroen
 */
class Cms_Page_Citroen_ExpandGamme extends Cms_Page_Citroen
{

    /**
     * Affichage du formulaire
     * @param Pelican_Controller $oController
     */
    public static function render(Pelican_Controller $controller)
    {
        $return = $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 255, "", true, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 75);
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi."ZONE_PARAMETERS", t('AFFICHER_PRIX'), array(1 => ""), $controller->zoneValues['ZONE_PARAMETERS'], false, $controller->readO);
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi."ZONE_TITRE9", t('CACHER_DS'), array(1 => ""), $controller->zoneValues['ZONE_TITRE9'], false, $controller->readO);
      
        return $return;
    }
    
    public static function save(Pelican_Controller $controller)
    {        
        parent::save();        
        Pelican_Cache::clean("Frontend/Citroen/ExpandGamme");
        Pelican_Cache::clean("Frontend/Citroen/VehiculeExpandCTA");
        Pelican_Cache::clean("Frontend/Citroen/Navigation");
    }
}

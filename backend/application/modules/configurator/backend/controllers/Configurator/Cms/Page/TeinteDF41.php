<?php

include_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Cms/Page/Module.php';

class Configurator_Cms_Page_TeinteDF41 extends Cms_Page_Module
{
    /**
     * Affichage des controles de saisie du bloc.
     *
     * @param Pelican_Controller $controller
     */
    public static function render(Pelican_Controller $controller)
    {
        $return = $controller->oForm->createHidden($controller->multi . 'PLUGIN_ID', 'configurator');        
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi . "ZONE_WEB", t('AFFICHAGE_WEB'), array(1 => ""), $controller->zoneValues['ZONE_WEB'], $required, $controller->readO);
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi . "ZONE_MOBILE", t('AFFICHAGE_MOB'), array(1 => ""), $controller->zoneValues['ZONE_MOBILE'], $required, $controller->readO);
      
        return $return;
    }

    /**
     * Enregistrement des parametres du bloc.
     *
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        parent::save($controller);
    }
}

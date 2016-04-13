<?php
include_once (Pelican::$config['APPLICATION_CONTROLLERS'] . '/Cms/Page/Module.php');

/**
 * Module Backend de parametrage du plugin iGoogle
 */
class Cms_Page_Module_Bloc1 extends Cms_Page_Module
{

    /**
     *
     *
     *
     * Enregistrement des parametres du bloc
     *
     * @param Pelican_Controller $controller            
     */
    public static function save (Pelican_Controller $controller)
    {
        parent::save($controller);
    }

    /**
     *
     *
     *
     * Affichage des controles de saisie du bloc
     *
     * @param Pelican_Controller $controller            
     */
    public static function render (Pelican_Controller $controller)
    {
        return $return;
    }
}

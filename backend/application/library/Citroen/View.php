<?php
/**
 * Classe de gestion des vues de Pelican.
 */
// Cette surcharge de la vue ne fonctionne pas
require_once 'Pelican/View.php';

class Citroen_View extends Pelican_View
{
    public function __construct()
    {
        parent::__construct();
        /* Rajout du chemin de plugins de l'application au contructeur de la view */
        $this->plugins_dir[] = Pelican::$config['DOCUMENT_ROOT'].'/views/plugins';
    }
}

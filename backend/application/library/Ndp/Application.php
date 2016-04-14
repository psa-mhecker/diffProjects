<?php

/**
 * Gestion de l'application
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */
require_once 'Pelican/Application.php';

class Ndp_Application extends Pelican_Application
{

    public static function init()
    {
        if ($_SERVER['DOCUMENT_ROOT'] === Pelican::$config['DOCUMENT_INIT'].'/public/media') {
            Pelican::$config['BYPASS_SESSION'] = true;
        }

        parent::init();
    }
}

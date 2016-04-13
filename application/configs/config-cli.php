<?php
/**
 * Fichier de configuration global en mode client
 *
 * A inclure dans tous les fichiers par : include_once('config.php');
 *
 * Paramétrage au niveau Apache à effectuer
 * - php.ini : include_path = ".;/home/projet/application/configs"
 * - httpd.conf : php_value include_path ".:/home/projet/application/configs"
 * - .htaccess : php_value include_path ".:/home/projet/application/configs"
 * (ATTENTION, il faut <Directory "/home/projet">AllowOverride all</Directory> dans le httpd.conf pour prendre ne compte le .htaccess)
 *
 * @package Pelican
 * @subpackage config
 */

/**
 * @global Tableau de configuration
 */
define('LOADER_AUTOLOADER', true);

$dir = str_replace('/application/configs', '', dirname(__FILE__));

if (phpversion() >= 5.3) {
    require_once $dir . '/vendor/autoload.php';
} else {
    set_include_path(get_include_path() . ':' . $dir . '/vendor');
    require_once 'Zend/Loader/Autoloader.php';
    $loader = Zend_Loader_Autoloader::getInstance();
    require_once $dir . '/vendor/Smarty/Smarty.class.php';
}
include_once 'Pelican.php';
include_once 'Pelican/Profiler.php';
include_once 'Pelican/Application.php';

Pelican::$config["BACK_OFFICE"] = false;
if (! empty($backend)) {
    Pelican::$config["BACK_OFFICE"] = true;
}

Pelican_Profiler::start('conf', 'page');

/** constantes locales */
$source = __FILE__;
include_once ('local/console.ini.php');
include_once ('app.ini.php');
include_once ('local.ini.php');

Pelican_Application::init();

Pelican::$config['HMVC'] = true;

/** librairie globale */
include_once ('Pelican/Helper/Global.lib.php');

/** pour pouvoir gérer les compatibilités de version */
include_once ('legacy.ini.php');

/** déclaration des chemins applicatifs */
include_once ('path.ini.php');
Pelican::$config['CACHE_FW_ROOT'] = Pelican::$config['CACHE_FW_ROOT'].'/cli/';

/** pour pouvoir gérer les compatibilités de version */
include_once ('loader.ini.php');
include_once ('Pelican/Loader.php');
include_once ('Pelican/Factory.php');
/** paramétrage du framework */
include_once ('framework.ini.php');

/** paramétrage applicatif : sauf dans le cas des medias */
include_once ('global.ini.php');
if ($_SERVER['DOCUMENT_ROOT'] != Pelican::$config['DOCUMENT_ROOT']) {
    if (file_exists(Pelican::$config['DOCUMENT_ROOT'] . Pelican::$config['LIB_CONF'] . '/configs.php')) {
        include_once (Pelican::$config['DOCUMENT_ROOT'] . Pelican::$config['LIB_CONF'] . '/configs.php');
    }
}
require_once(Pelican::$config['VENDOR_ROOT'] . '/autoload.php');

include_once (pelican_path('Cache'));
pelican_import('Db');
pelican_import('Plugin');

pelican_import('Html');
include_once (Pelican::$config['LIB_ROOT'] . Pelican::$config['LIB_DEBUG'] . '/Debug.php');
pelican_import('Log');

//include('local/services.ini.'.$_ENV['TYPE_ENVIRONNEMENT'].'.php');
include('services.ini.php');
pelican_import('Text');
pelican_import ('Http.UserAgent');

/** gestion des langues */
include_once pelican_path('Translate');

/** Request */
pelican_import('Request');

/* Modif Pelican_Log Pierre */
//include_once(pelican_path('Log'));
//include_once(dirname(__FILE__) . "/log.ini.php");
/* Fin modif Pelican_Log Pierre */

/** paramétrage de la mediathèque */
include_once ('mediatheque.ini.php');

/** Contrôles de sécurité */
include_once ('security.ini.php');

/** a faire avant les langues */
Pelican_Application::getSiteInfos();

/** paramétrage propre au site BnppLs */
include_once ('citroen.ini.php');

Pelican::$config['PORTAL_AUTH_TABLE'] = 'portal_user';

/** initialisation HMVC */
pelican_import('Route');
pelican_import('Request');


Pelican_Profiler::stop('conf', 'page');

?>
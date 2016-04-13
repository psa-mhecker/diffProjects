<?php
/**
 * Fichier de configuration global
 *
 * A inclure dans tous les fichiers par : include_once('config.php');
 *
 * Param�trage au niveau Apache a effectuer
 * - php.ini : include_path = '.;/home/projet/application/configs'
 * - httpd.conf : php_value include_path '.:/home/projet/application/configs'
 * - .htaccess : php_value include_path '.:/home/projet/application/configs'
 * (ATTENTION, il faut <Directory '/home/projet'>AllowOverride all</Directory> dans le httpd.conf pour prendre ne compte le .htaccess)
 *
 * @package Pelican
 * @subpackage config
 */

/**
 *
 * @global Loaders
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

/**
 * constantes locales
 */
include 'app.ini.php';
include 'local.ini.php';

Pelican_Application::init();

Pelican::$config['HMVC'] = true;

/**
 * librairie globale
 */
include 'Pelican/Helper/Global.lib.php';

/**
 * pour pouvoir g�rer les compatibilit�s de version
 */
include 'legacy.ini.php';

/**
 * d�claration des chemins applicatifs
 */
include 'path.ini.php';

/**
 * pour pouvoir g�rer les compatibilit�s de version
 */
include 'loader.ini.php';
include 'Pelican/Loader.php';
include 'Pelican/Factory.php';

/**
 * param�trage du framework
 */
include 'framework.ini.php';

/**
 * param�trage applicatif : sauf dans le cas des medias
 */
include 'global.ini.php';
if ($_SERVER['DOCUMENT_ROOT'] != Pelican::$config['DOCUMENT_ROOT']) {
    if (file_exists(Pelican::$config['DOCUMENT_ROOT'] . Pelican::$config['LIB_CONF'] . '/configs.php')) {
        include (Pelican::$config['DOCUMENT_ROOT'] . Pelican::$config['LIB_CONF'] . '/configs.php');
    }
}

include_once pelican_path('Cache');
pelican_import('Db');
pelican_import('Plugin');

pelican_import('Html');
include Pelican::$config['LIB_ROOT'] . Pelican::$config['LIB_DEBUG'] . '/Debug.php';
pelican_import('Log');

pelican_import('Text');
pelican_import('Http.UserAgent');

/**
 * gestion des langues
 */
include_once pelican_path('Translate');

/**
 * Request
 */
pelican_import('Request');

/**
 * param�trage de la mediatheque
 */
include 'mediatheque.ini.php';

/**
 * Controles de securite
 */
include 'security.ini.php';

/**
 * a faire avant les langues
 */
Pelican_Application::getSiteInfos();

/**
 * param�trage propre au site CPPV2
 */
include 'citroen.ini.php';

Pelican::$config['PORTAL_AUTH_TABLE'] = 'portal_user';
// include 'local/services.ini.' . $_ENV["TYPE_ENVIRONNEMENT"] . '.php';
include 'services.ini.php';
switch ($_ENV["TYPE_ENVIRONNEMENT"]) {
    case "VM":
    case "DEV":
    case "PREPROD":
    case "RECETTE":
    case "INTEGRATION":
    case "PSA_INTEGRATION":
    case "PSA_PREPRODUCTION":
    case "PSA_PRODUCTION":
    case "PSA_RECETTE":
    case "dev":
    case "preprod":
    case "recette":
    case "integration":
    case "psa_integration":
    case "psa_preprod":
    case "psa_prod":
    case "psa_recette":
    case "psa_recette_projet":
    case "PSA_RECETTE_PROJET":
    case "psa_integration_vc":    
    case "psa_integration_prj":    
    case "PSA_INTEGRATION_VC":
    case "PSA_INTEGRATION_PRJ":
        {
            $include = $_ENV["TYPE_ENVIRONNEMENT"];
            break;
        }
    default:
        {
            $include = 'default';
            break;
        }
}
include_once dirname(__FILE__) . '/local/apis.ini.' . $include . '.php';
/**
 * initialisation HMVC
 */
pelican_import('Route');
pelican_import('Request');

Pelican_Profiler::stop('conf', 'page');
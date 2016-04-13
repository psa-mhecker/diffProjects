<?php
/**
 * Fichier de configuration des chemins d'accès
 *
 * @package Pelican
 * @subpackage config
 */

/**
 * Définition du répertoire racine
 * NE PAS METTRE DE / A LA FIN DE DOCUMENT_ROOT
 * DOCUMENT_ROOT personnalisé (prenant en compte un répertoire virtuel si nécessaire)
 */
Pelican::$config["SERVER_PROTOCOL"] = "http";
/* prb de chargement des js en acces externe */
if (isset($_SERVER["HTTPS"])) {
    if ($_SERVER["HTTPS"]) {
        Pelican::$config["SERVER_PROTOCOL"] = "https";
    }
} else {
    if (! empty($_SERVER['HTTP_CLIENT_HOST'])) {
        if (strpos($_SERVER['HTTP_CLIENT_HOST'], '.citroen.com') !== false && ! strpos($_SERVER['HTTP_CLIENT_HOST'], '.citroen.com.')) {
            Pelican::$config["SERVER_PROTOCOL"] = "https";
        }
    }
}

Pelican::$config["CONFIG_ROOT"] = dirname(__FILE__);

Pelican::$config['DOCUMENT_ROOT'] = str_replace("/public", "/application/sites", $_SERVER["DOCUMENT_ROOT"]);
$aFront = explode("/", trim(Pelican::$config['DOCUMENT_ROOT'], '/'));
$frontend = $aFront[count($aFront) - 1];
Pelican::$config["DOCUMENT_HTTP"] = Pelican::$config["SERVER_PROTOCOL"] . "://" . Pelican::$config["HTTP_HOST"];

/**
 * Pages d'index
 */
Pelican::$config["PAGE_INDEX_PATH"] = "/";
Pelican::$config["PAGE_INDEX_IFRAME_PATH"] = "/_/Index/child";
Pelican::$config["INDEX_PATH"] = "";

/**
 * Chemin absolu du répertoire contenant les classes et librairies du framework
 */
Pelican::$config["LIB_PATH"] = "/library";
Pelican::$config['LIB_ROOT'] = Pelican::$config["DOCUMENT_INIT"] . "/library";

/**
 * Chemin absolu du répertoire contenant les fichiers générés par l'application : leur suppression n'empêche pas le fonctionnement
 */
Pelican::$config["VAR_ROOT"] = Pelican::$config["DOCUMENT_INIT"] . "/var";
Pelican::$config["LOG_ROOT"] = Pelican::$config["VAR_ROOT"] . '/logs';

/**
 * Chemin absolu du répertoire de Pelican_Plugin
 */
Pelican::$config["PLUGIN_PATH"] = "/application/modules";
Pelican::$config["PLUGIN_ROOT"] = Pelican::$config["DOCUMENT_INIT"] . "/application/modules";

/**
 * Gestion du Pelican_Cache
 */
Pelican::$config["TEMPLATE_CACHE_ROOT"] = Pelican::$config["DOCUMENT_INIT"] . "/application/caches";
Pelican::$config["CACHE_FW_ROOT"] = Pelican::$config["DOCUMENT_INIT"] . "/var/cache/application";

/**
 * Chemin absolu du répertoire contenant les templates de saisie
 */
Pelican::$config["CONTROLLERS_ROOT"] = Pelican::$config['DOCUMENT_ROOT'] . "/controllers";
Pelican::$config['APPLICATION_LIBRARY'] = Pelican::$config['DOCUMENT_INIT'] . "/application/library";
Pelican::$config['APPLICATION_CONTROLLERS'] = Pelican::$config['DOCUMENT_ROOT'] . "/controllers";
Pelican::$config['APPLICATION_VIEWS'] = Pelican::$config['DOCUMENT_ROOT'] . "/views/scripts";
Pelican::$config['APPLICATION_VIEW_HELPERS'] = Pelican::$config['DOCUMENT_ROOT'] . "/views/helpers";

/**
 * gestion de la langue
 */
Pelican::$config['TRANSLATION_ROOT'] = Pelican::$config['DOCUMENT_ROOT'] . '/i18n/';

/**
 * Gestion du chemin vers le répertoire vendor
 */
Pelican::$config['VENDOR_ROOT'] = Pelican::$config['DOCUMENT_INIT'] . '/vendor';

// Chemin absolu et http du répertoire contenant les Pelican_Media
Pelican::$config["MEDIA_LIB_PATH"] = Pelican::$config["LIB_PATH"] . "/Pelican/Media/public";
Pelican::$config["MEDIA_LIB_ROOT"] = Pelican::$config['LIB_ROOT'] . "/Pelican/Media";
Pelican::$config["MEDIA_HTTP"] = Pelican::$config["SERVER_PROTOCOL"] . "://" . Pelican::$config["HTTP_MEDIA"];
Pelican::$config["MEDIA_ROOT"] = Pelican::$config["DOCUMENT_INIT"] . "/public/media";
Pelican::$config["MEDIA_VAR"] = "#MEDIA_HTTP#";

// CSS
if (! empty($_GET['css'])) {
    $_SESSION[APP]["css"] = $_GET["css"];
} elseif (empty($_SESSION[APP]['css'])) {
    $_SESSION[APP]["css"] = APP_DEFAULT_SKIN;
}
// $_SESSION[APP]["css"] = $_COOKIE["css"];
Pelican::$config["SKIN"] = $_SESSION[APP]["css"];
Pelican::$config["SKIN_PATH"] = Pelican::$config["LIB_PATH"] . Pelican::$config['LIB_INDEX'] . "/Backoffice/public/skins/" . Pelican::$config["SKIN"];
Pelican::$config["CSS_BACK_PATH"] = Pelican::$config["SKIN_PATH"] . "/css/style.css.php";
Pelican::$config["CSS_FRONT_PATH"] = "/css/style.css";
Pelican::$config["FONT_FRONT_PATH"] = "/css/font";

/**
 * Chemins du répertoire contenant les images
 */
Pelican::$config["IMAGE_PATH"] = "/images";
Pelican::$config["IMAGE_HTTP"] = Pelican::$config["SERVER_PROTOCOL"] . "://" . $_SERVER["HTTP_HOST"];

/**
 * Chemin absolu du répertoire contenant les fichiers de transaction
 */
Pelican::$config["DB_PATH"] = "/_/Index/child";
Pelican::$config["TRANSACTION_ROOT"] = Pelican::$config['DOCUMENT_ROOT'] . "/actions";

// DESIGN ET JS
Pelican::$config['DESIGN_NAME'] = 'design';
Pelican::$config['DESIGN_PACK_NAME'] = 'design_pack';
Pelican::$config["DESIGN_ROOT"] = Pelican::$config["MEDIA_ROOT"] . "/" . Pelican::$config['DESIGN_NAME'] . "/" . $frontend;
Pelican::$config["DESIGN_PACK_ROOT"] = Pelican::$config["MEDIA_ROOT"] . "/" . Pelican::$config['DESIGN_PACK_NAME'] . "/" . $frontend;
Pelican::$config["DESIGN_HTTP"] = Pelican::$config["MEDIA_HTTP"] . "/" . Pelican::$config['DESIGN_NAME'] . "/" . $frontend;
Pelican::$config["DESIGN_PACK_HTTP"] = Pelican::$config["MEDIA_HTTP"] . "/" . Pelican::$config['DESIGN_PACK_NAME'] . "/" . $frontend;
Pelican::$config["ARCHIVE_ROOT"] = Pelican::$config["DESIGN_ROOT"] . "/archive";
Pelican::$config["ARCHIVE_HTTP"] = Pelican::$config["DESIGN_HTTP"] . "/archive";
Pelican::$config["IMAGE_FRONT_HTTP"] = Pelican::$config["DESIGN_HTTP"] . "/images";
Pelican::$config["CSS_FRONT_HTTP"] = Pelican::$config["DESIGN_HTTP"] . "/css";
Pelican::$config["JS_FRONT_HTTP"] = Pelican::$config["DESIGN_HTTP"] . "/js";

/**
 * portal
 */
Pelican::$config["PORTAL_DESIGN_HTTP"] = Pelican::$config["MEDIA_HTTP"] . "/" . Pelican::$config['DESIGN_NAME'] . "/portal";
Pelican::$config["PORTAL_CSS_FRONT_HTTP"] = Pelican::$config["PORTAL_DESIGN_HTTP"] . "/css";
Pelican::$config["PORTAL_JS_FRONT_HTTP"] = Pelican::$config["PORTAL_DESIGN_HTTP"] . "/js";
Pelican::$config["PORTAL_VIEWS_ROOT"] = Pelican::$config["DOCUMENT_INIT"] . "/portal/views/scripts";
Pelican::$config["PORTAL_CONTROLLERS_ROOT"] = Pelican::$config["DOCUMENT_INIT"] . "/portal/controllers";

/**
 * mobile
 */
Pelican::$config["MOBILE_DESIGN_HTTP"] = Pelican::$config["MEDIA_HTTP"] . "/" . Pelican::$config['DESIGN_NAME'] . "/mobile";
Pelican::$config["MOBILE_CSS_FRONT_HTTP"] = Pelican::$config["MOBILE_DESIGN_HTTP"] . "/css";
Pelican::$config["MOBILEL_JS_FRONT_HTTP"] = Pelican::$config["MOBILE_DESIGN_HTTP"] . "/js";

/**
 * Paramétrage de SMARTY
 */
Pelican::$config["VIEWS_ROOT"] = Pelican::$config['DOCUMENT_ROOT'] . "/views/scripts";
Pelican::$config["VAR_VIEW_COMPILES_ROOT"] = str_replace('public', 'var/cache/view_compiles', $_SERVER['DOCUMENT_ROOT']);
Pelican::$config["VAR_CACHE_VIEWS"] = str_replace('public', 'var/cache/views', $_SERVER['DOCUMENT_ROOT']);

/**
 * WURFL
 */
Pelican::$config['wurflapi']['wurfl_lib_dir'] = Pelican::$config['LIB_ROOT'] . Pelican::$config['LIB_MOBILE'] . '/Wurfl/1.5/';
Pelican::$config['wurflapi']['wurfl_config_file'] = Pelican::$config["CONFIG_ROOT"] . '/Wurfl/1.5/wurfl-config.php';
Pelican::$config['wurflapi']['DATABASE_HASH'] = '51eeefadd7b165f709f675131e719fb2';


/**
*PATH TO FILE
*/
Pelican::$config["TEMPLATE_OUTILS_WEB"] = Pelican::$config['APPLICATION_VIEWS'].'/Layout/Citroen/Outil/index_outils.tpl';  
Pelican::$config["TEMPLATE_OUTILS_MOBILE"] = Pelican::$config['APPLICATION_VIEWS'].'/Layout/Citroen/Outil/index.mobi';

// Toutes les variables _PATH sont automatiquement transformées en chemin physiques _ROOT
$const_path = array(
    "INDEX_PATH",
    "PAGE_INDEX_PATH",
    "LIB_PATH",
    "MEDIA_LIB_PATH",
    "THUMBNAIL_ORIGINAL_PATH",
    "THUMBNAIL_PATH",
    "CSS_FRONT_PATH",
    "FONT_FRONT_PATH",
    "IMAGE_PATH",
    "SKIN_PATH",
    "CSS_BACK_PATH",
    "CSS_PRINT_PATH",
    "CSS_MOBILE_PATH"
);
pathToRoot($const_path);

?>
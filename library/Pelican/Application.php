<?php

/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Application
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */

/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Application
 * @author __AUTHOR__
 */
class Pelican_Application
{

    /**
     * Tableau des configurations de l'application
     *
     * @access public
     * @var Array
     */
    static $config = array();

    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    static $frontController;

    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    static $trace = array();

    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    static $lang = array();

    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    static $logger;

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public static function init ()
    {
        if (empty($_SERVER['REDIRECT_STATUS'])) {
            $_SERVER['REDIRECT_STATUS'] = '';
        }
        
        /**
         * gestion de la session
         */
        if (ini_get('session.save_handler') != 'redis') {
            include_once 'Pelican/Session/FileSystem.php';
            $handler = new Pelican_Session_FileSystem();
            session_set_save_handler(array(
                $handler,
                'open'
            ), array(
                $handler,
                'close'
            ), array(
                $handler,
                'read'
            ), array(
                $handler,
                'write'
            ), array(
                $handler,
                'destroy'
            ), array(
                $handler,
                'gc'
            ));
            // Ceci permet de prévenir des effets non désirés lors de l'utilisation d'objets comme gestionnaires de session
            register_shutdown_function('session_write_close');
        }
        
        if (empty(Pelican::$config['BYPASS_SESSION']) && $_SERVER['DOCUMENT_ROOT'] != Pelican::$config['DOCUMENT_INIT'] . '/media' && $_SERVER['REDIRECT_STATUS'] != 404) {
            include_once 'Pelican/Session.php';
            Pelican_Session::start();
            // session_start();
        }
        if (isset($_GET['useragent'])) {
            $_SESSION['HTTP_USER_AGENT'] = rawurldecode($_GET['useragent']);
        }
        
        /**
         * nom de l'application
         */
        if (! isset($_SESSION[APP])) {
            $_SESSION[APP] = array();
        }
        
        // utilisation de Graphics_Magick
        Pelican::$config["GRAPHICS_MAGICK"] = (strpos(Pelican::$config["IM_ROOT"], 'gm convert') ? true : false);
    }

    /**
     *
     * @param string $cmd
     *            Command line
     * @param bool $win
     *            run under Windows
     */
    public static function runCommand ($cmd, $win = '')
    {
        if (isset($_SERVER['WINDIR'])) {
            if ($win) {
                $list = glob($win);
                foreach ($list as $file) {
                    unlink($file);
                }
            } else {
                $fp = fopen("c:\\temp.bat", "w");
                fwrite($fp, str_replace("/", "\\", $cmd));
                fclose($fp);
                exec("c:\\temp.bat");
                @unlink("c:\\temp.bat");
            }
        } else {
            pclose(popen($cmd, "r"));
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public static function getAjaxEngine ()
    {
        /*
         * Jquery by default
         */
        if (empty(Pelican::$config['AJAX_ADAPTER'])) {
            Pelican::$config['AJAX_ADAPTER'] = 'Jquery';
        }
        /*
         * load the Ajax adapter
         */
        if (Pelican::$config['AJAX_ADAPTER']) {
            $adapter = 'Ajax.Adapter.' . ucfirst(Pelican::$config['AJAX_ADAPTER']);
            include_once (pelican_path($adapter));
            return $adapter;
        }
    }

    /**
     * Identifie la version � utiliser pour l'affichage (Publi� ou draft)
     *
     * @access public
     * @return string
     */
    public static function getPreviewVersion ()
    {
        
        /**
         * * Pour la prévisu en mode draft ou schedule
         */
        if (! empty($_GET['schedule'])) {
            $type_version = "SCHEDULE";
        }elseif(! empty($_GET['preview'])){
            $type_version = "DRAFT";
        } else {
            $type_version = "CURRENT";
        }
        
        return $type_version;
    }

    /**
     * R�cup du chemin du Pelican_Media pass� en param�tre
     *
     * @access public
     * @param __TYPE__ $media_id
     *            Int __DESC__
     * @return string
     */
    function getPathMedia ($media_id)
    {
        $file = Pelican_Cache::fetch("media_path_php", $media_id);
        return $file;
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public static function getSiteInfos ()
    {
        $site = array();
        Pelican::$config["SITE"]["ID"] = '';
        Pelican::$config["SITE"]["INFOS"] = array();
        Pelican::$config["SITE"]["URL"] = '';
        if ($_SERVER["SCRIPT_NAME"] != "/500.php") {
            if (! isset($_SESSION[APP]['SITE_ID'])) {
                // URL infos
                $site = Pelican_Cache::fetch("Frontend/Site/Url", strtoLower($_SERVER["HTTP_HOST"]));
                // Try to identify Backend
                // $_SESSION[APP]["BACKOFFICE"] = ($site['SITE_ID'] == 1 || empty($site['SITE_ID']));
                if (! isset($site["SITE_URL"])) {
                    $site["SITE_URL"] = "";
                }
                if (! isset($site['SITE_ID'])) {
                    $site['SITE_ID'] = "";
                }
                Pelican::$config["SITE"]["URL"] = $site["SITE_URL"];
                if (empty(Pelican::$config["SITE"]["ID"]) && ! Pelican::$config["BACK_OFFICE"]) {
                    Pelican::$config["SITE"]["ID"] = $site['SITE_ID'];
                }
                if (! empty(Pelican::$config["SITE"]["ID"])) {
                    Pelican::$config["SITE"]["INFOS"] = Pelican_Cache::fetch("Frontend/Site", Pelican::$config["SITE"]["ID"]);
                    $_SESSION[APP]['SITE_ID'] = Pelican::$config["SITE"]["ID"];
                    $aCodePays = Pelican_Cache::fetch("Citroen/CodePaysById");
                    $_SESSION[APP]['CODE_PAYS'] = $aCodePays[Pelican::$config["SITE"]["ID"]];
                    Pelican::setLang();
                }
            } else {
                Pelican::$config["SITE"]["INFOS"] = Pelican_Cache::fetch("Frontend/Site", $_SESSION[APP]['SITE_ID']);
                
                // if backend, $_SESSION[APP]['CODE_PAYS'] must be empty
                if (! empty(Pelican::$config["BACK_OFFICE"])) {
                    $_SESSION[APP]['CODE_PAYS'] = null;
                }
                if (isset(Pelican::$config["SITE"]["INFOS"]["SITE_URL"])) {
                    Pelican::$config["SITE"]["URL"] = Pelican::$config["SITE"]["INFOS"]["SITE_URL"];
                }
                if (isset(Pelican::$config["SITE"]["INFOS"]['SITE_ID']) && ! Pelican::$config["BACK_OFFICE"]) {
                    Pelican::$config["SITE"]["ID"] = Pelican::$config["SITE"]["INFOS"]['SITE_ID'];
                }
                if (isset(Pelican::$config["SITE"]["INFOS"]["SITE_MINISITE"])) {
                    Pelican::$config["SITE"]["MINISITE"] = Pelican::$config["SITE"]["INFOS"]["SITE_MINISITE"];
                }
            }
            if (! empty(Pelican::$config["SITE"]["INFOS"]['DNS'][Pelican::$config["SITE"]["URL"]])) {
                Pelican::$config["SITE"]["PARAMETERS"] = Pelican::$config["SITE"]["INFOS"]['DNS'][Pelican::$config["SITE"]["URL"]];
            }
            if (! empty(Pelican::$config["SITE"]["INFOS"]['MAP_PROVIDER_CODE'])) {
                if (! empty(Pelican::$config["SITE"]["PARAMETERS"]['map_' . Pelican::$config["SITE"]["INFOS"]['MAP_PROVIDER_CODE']])) {
                    Pelican::$config["SITE"]["INFOS"]['MAP_PROVIDER_KEY'] = Pelican::$config["SITE"]["PARAMETERS"]['map_' . Pelican::$config["SITE"]["INFOS"]['MAP_PROVIDER_CODE']];
                }
            }
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public static function setLang ()
    {
        $oldLang = $_SESSION[APP]['LANG'];
        $ibBackend = 1;
        
        /**
         * UI language
         */
        if (! empty($_REQUEST['lang']) && ($_SESSION[APP]['LANG'] != (int) $_REQUEST['lang'])) {
            $_SESSION[APP]['LANG'] = (int) $_REQUEST['lang'];
        }
        
        /**
         * data language
         */
        if (! empty($_REQUEST['langue']) && ($_SESSION[APP]['LANGUE_ID'] != (int) $_REQUEST['langue'])) {
            $_SESSION[APP]['LANGUE_ID'] = (int) $_REQUEST['langue'];
        }
        
        /**
         * both
         */
        if (! empty($_REQUEST['lng']) && ($_SESSION[APP]['LANG'] != (int) $_REQUEST['lng']) && ! Pelican::$config["BACK_OFFICE"]) {
            $_SESSION[APP]['LANG'] = (int) $_REQUEST['lng'];
            $_SESSION[APP]['LANGUE_ID'] = (int) $_REQUEST['lng'];
        }
        
        /*
         * if (! empty(Pelican::$config["SITE"]["INFOS"]["LANGUE"])) { if (! in_array($_SESSION[APP]['LANGUE_ID'], Pelican::$config["SITE"]["INFOS"]["LANGUE"])) { // If no default language, the first one is selected $_SESSION[APP]['LANGUE_ID'] = Pelican::$config["SITE"]["INFOS"]["LANGUE"][0]['LANGUE_ID']; } }
         */
        
        /*
         * search the default backend language
         */
        if (Pelican::$config["BACK_OFFICE"]) {
            $BO['INFOS'] = Pelican_Cache::fetch("Frontend/Site", $ibBackend);
            // UI language
            if (! empty($BO['INFOS']["LANG"])) {
                if (! in_array($_SESSION[APP]['LANG'], $BO['INFOS']["LANG"])) {
                    // If no default language, the first one is selected
                    $_SESSION[APP]['LANG'] = $BO['INFOS']["LANG"][0];
                }
            }
            // data language : not for backend
            if ($_SESSION[APP]['SITE_ID'] != $ibBackend) {
                $FO['INFOS'] = Pelican_Cache::fetch("Frontend/Site", $_SESSION[APP]['SITE_ID']);
                if (! empty($FO['INFOS']["LANG"])) {
                    if (! in_array($_SESSION[APP]['LANGUE_ID'], $FO['INFOS']["LANG"])) {
                        // If no default language, the first one is selected
                        $_SESSION[APP]['LANGUE_ID'] = $FO['INFOS']["LANG"][0];
                    }
                }
            }
        } else {
            if ($_SESSION[APP]['SITE_ID'] != $ibBackend) {
                if (empty($_SESSION[APP]['LANGUE_ID'])) {
                    if (count(Pelican::$config['SITE']['INFOS']['LANG']) == 1) {
                        $_SESSION[APP]['LANGUE_ID'] = Pelican::$config['SITE']['INFOS']["LANG"][0];
                    }
                }
                // in frontend, UI and data have the same language
                $_SESSION[APP]['LANG'] = $_SESSION[APP]['LANGUE_ID'];
            }
        }
        
        if ($oldLang != $_SESSION[APP]['LANG']) {
            $aLangue = Pelican_Cache::fetch('Language');
            $_SESSION[APP]['LANGUE_CODE'] = strtoupper($aLangue[$_SESSION[APP]['LANG']]['LANGUE_CODE']);
            Pelican::$config['LANG'] = $_SESSION[APP]['LANGUE_CODE'];
            Pelican_Translate::getLang();
        }
        
        // var_dump(debug_backtrace());
        /* var_dump('interface : ' . $_SESSION[APP]['LANG']); var_dump('data : ' . $_SESSION[APP]['LANGUE_ID']); var_dump('code interface : ' . $_SESSION[APP]['LANGUE_CODE']); */
    }

    public static function resetLang ($id = '')
    {
        if ($id != '' && ! (Pelican::$config["BACK_OFFICE"])) {
            if (empty($_SESSION[APP]['LANGUE_ID'])) {
                $_SESSION[APP]['LANGUE_ID'] = '';
            }
            $_REQUEST['lng'] = $id;
            Pelican_Application::setLang();
            Pelican_Translate::getLang();
        }
    }
}
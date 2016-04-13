<?php

/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Translate
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */

/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Translate
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 *
 */
/**
* 
* Permet la surcharge de la traduction
*
*/
require_once('Pelican/Translate.php');

class Citroen_Translate extends Pelican_Translate {

    public static $dictionary;

    /**
     *
     * @access public
     * @var __TYPE__ __DESC__
     */
    public static $translations = array();

    public static $controlInit = array();

    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public static $translationType = array();

    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public static $path = array();

    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public static $cachepath = array();

    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public static $translationSite = '';

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $site
     *            __DESC__
     * @param __TYPE__ $type
     *            __DESC__
     * @return __TYPE__
     */
    public static function setTranslationType ($site, $type)
    {
        self::$translationType[$site] = $type;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $value
     *            __DESC__
     * @return __TYPE__
     */
    public static function setTranslations ($value)
    {
        self::$translations = $value;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $site
     *            __DESC__
     * @return string
     */
    public static function getTranslationType ($site)
    {
        if (isset(self::$translationType[$site])) {
            return self::$translationType[$site];
        } else {
            return '';
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @return the
     */
    public static function getTranslations ()
    {
        return self::$translations;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $text
     *            __DESC__
     * @return the
     */
    public static function getTranslation ($text)
    {
        $text = strtr(strtoupper(dropaccent($text)), " ", "_");
        if (isset(self::$translations[$text])) {
            // self::isUsed($text);
            if ($_SESSION['translate'] == 'on') {
                return '...';
            } else {
                if (! empty($_GET['DEBUG_LANG'])) {
                    return "XXXXX_" . self::getLang() . "_XXXXX";
                } else {
                    return self::$translations[$text];
                }
            }
        } else {
            /*
             * if (! empty(Pelican::$config["TRANSLATE_TRACE"]) && $text != 'DATE_FORMAT_DB' && $text != 'DATE_FORMAT_PHP') { if (strtoupper($_ENV["TYPE_ENVIRONNEMENT"]) == 'DEV') { self::createDatabaseKey($text, '??_' . $text); } }
             */
            return "[cle1: " . $text . " Lang:" . self::getLang() . "]";
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param string $site_id
     *            (option) __DESC__
     * @return __TYPE__
     */
    static public function getTranslationSite ($site_id = '')
    {
        if (empty(Pelican::$config["HTTP_BACK"])) {
            Pelican::$config["HTTP_BACK"] = false;
        }
        if (! empty($site_id) && $_SERVER["HTTP_HOST"] != Pelican::$config["HTTP_BACK"]) {
            return 'site_' . $site_id;
        } else {
            return 'common';
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param string $site_id
     *            (option) __DESC__
     * @return __TYPE__
     */
    static public function getTranslationSite2 ($site_id = '')
    {
        if (empty(Pelican::$config["HTTP_BACK"])) {
            Pelican::$config["HTTP_BACK"] = false;
        }
        if (! empty($site_id) && $_SERVER["HTTP_HOST"] != Pelican::$config["HTTP_BACK"]) {
            return 'site_' . $site_id;
        } else {
            return 'common';
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $site
     *            __DESC__
     * @return __TYPE__
     */
    static public function getIniPath ($site)
    {
        if (empty(self::$path[$site])) {
            if ($site == 'common' && self::getLang()) {
                self::$path[$site] = Pelican::$config['DOCUMENT_INIT'] . '/application/i18n/';
            } else {
                self::$path[$site] = str_replace('/public', '/application/sites', Pelican::$config['DOCUMENT_ROOT'] . '/i18n/');
            }
        }
        return self::$path[$site];
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $site
     *            __DESC__
     * @return __TYPE__
     */
    static public function getCachePath ($site)
    {
        if (empty(self::$cachepath[$site])) {
            if ($site == 'common') {
                self::$cachepath[$site] = Pelican::$config["VAR_ROOT"] . "/i18n/common/";
            } else {
                self::$cachepath[$site] = Pelican::$config["VAR_ROOT"] . "/i18n/" . $site . "/";
            }
            if (! is_dir(self::$cachepath[$site])) {
                mkdir(self::$cachepath[$site], 0777, true);
            }
        }
        return self::$cachepath[$site];
    }

    public static function getLanguageCode ()
    {
        return Pelican_Cache::fetch("LanguageCode");
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    static public function getLang ()
    {
        return $_SESSION[APP]['LANGUE_CODE'];
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    static public function getCodePays ()
    {
        return $_SESSION[APP]['CODE_PAYS'];
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $text
     *            __DESC__
     * @param bool $debug
     *            (option) __DESC__
     * @return __TYPE__
     */
    public static function t($text, $debug = false, $forceBO = false)
    {
        if (isset($_GET['translate'])) {
            if ($_GET['translate'] == 'on') {
                $_SESSION['translate'] = 'on';
            } elseif ($_GET['translate'] == 'off') {
                $_SESSION['translate'] = 'off';
            }
        }
        if (! isset($_SESSION['translate'])) {
            $_SESSION['translate'] = 'off';
        }
        $return = '';
        if (! isset(Pelican::$config["SITE"]["ID"])) {
            Pelican::$config["SITE"]["ID"] = '';
        }
        // $translationSite = self::getTranslationSite(Pelican::$config['SITE_ID']);
        $translationSite = 'frontend';
        if (Pelican::$config["BACK_OFFICE"] || $forceBO) {
            $translationSite = 'backend';
        }
        
        $codeLangue = strtolower(self::getLang());
        $codePays = self::getCodePays();
        if (! empty($codeLangue)) {
            if (! isset(self::$controlInit[$translationSite . '_' . $codeLangue . '_' . $codePays])) {
                self::init($translationSite);
            }
        }
        if (isset(self::$controlInit[$translationSite . '_' . $codeLangue . '_' . $codePays])) {
            $return = self::getTranslation($text);
        }
        return $return;
    }

    /**
     *
     * @param string $key            
     * @param string $value            
     */
    public static function createDatabaseKey ($key, $value = '')
    {
        if (! empty($_SESSION[APP]['LANGUE_ID'])) {
            $oConnection = Pelican_Db::getInstance();
            Pelican_Db::$values['LABEL_ID'] = $key;
            Pelican_Db::$values['LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
            Pelican_Db::$values['LABEL_TRANSLATE'] = (! empty($value) ? $value : $key);
            
            $count = $oConnection->queryItem("select count(*)  from #pref#_label where LABEL_ID = '" . str_replace("'", "''", $key) . "'");
            if (! $count) {
                $oConnection->insertQuery('#pref#_label');
                $oConnection->insertQuery('#pref#_label_langue');
            }
        }
    }

    public static function isUsed ($key)
    {
        ini_set('display_errors', 1);
        $execption = array(
            'DATE_FORMAT_DB',
            'DATE_FORMAT_PHP'
        );
        if (! in_array($key, $execption)) {
            if (Pelican::$config["SHOW_DEBUG"]) {
                $oConnection = Pelican_Db::getInstance();
                if ($_SESSION[APP]['SITE_ID'] == Pelican::$config['SITE_BO']) {
                    $oConnection->query("UPDATE #pref#_label SET LABEL_INFO = 'used', LABEL_BACK = '1' WHERE LABEL_ID = :LABEL_ID", array(
                        ':LABEL_ID' => $oConnection->strToBind($key)
                    ));
                } else {
                    $oConnection->query("UPDATE #pref#_label SET LABEL_INFO = 'used' WHERE LABEL_ID = :LABEL_ID", array(
                        ':LABEL_ID' => $oConnection->strToBind($key)
                    ));
                }
            }
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $site
     *            (option) __DESC__
     * @return __TYPE__
     */
    public static function init ($site = "common")
    {
        $codeLangue = strtolower(self::getLang());
        $codePays = self::getCodePays();
        $fichier = $codePays . '-' . $codeLangue;
        $fichierCommon = $codeLangue;
        if (file_exists(self::getCachePath($site) . $fichier . '.php')) {
            $file = self::getCachePath($site) . $fichier . '.php';
            include $file;
            self::$translations = Pelican::$lang;
        } elseif (file_exists(self::getCachePath('common') . $fichierCommon . '.php')) {
            $file = self::getCachePath('common') . $fichierCommon . '.php';
            include $file;
            self::$translations = Pelican::$lang;
        }
        if ($file) {
            self::$controlInit[$site . '_' . $codeLangue . '_' . $codePays] = $codeLangue;
        }
    }


/**
 * __DESC__
 *
 * @param __TYPE__ $text
 *            __DESC__
 * @param bool $debug
 *            (option) __DESC__
 * @return __TYPE__
 */
      public static function tForceBo ($text, $alter = '', $aDynamisationParams = array())
    {
        if (! $text)
            return '';
        /** force l'utilisation des traductions BO **/
        $forceBO = false;
        if($alter == "forceBO") {
            $forceBO = true;
        }

        $return = Citroen_Translate::t($text, $debug, $forceBO);
        
        if (is_array($aDynamisationParams) && sizeof($aDynamisationParams) > 0) {
            foreach ($aDynamisationParams as $key => $replacementsParam) {
                $patternsParam = '/##param' . $key . '(.*?)##/';
                $return = preg_replace($patternsParam, $replacementsParam, $return);
            }
        }
        
        switch ($alter) {
            case 'escape':
            case 'js2':
                {
                    $return = str_replace(array(
                        "'",
                        "\""
                    ), array(
                        "\\'",
                        "\\\""
                    ), $return);
                    break;
                }
            case 'js':
                {
                    $return = str_replace(array(
                        "'",
                        "/"
                    ), array(
                        "\'",
                        "\/"
                    ), $return);
                    break;
                }
        }
        
        return $return;
    }

}

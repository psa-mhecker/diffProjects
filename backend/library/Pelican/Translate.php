<?php

/**
 *
 *
 * @package Pelican
 * @subpackage Translate
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */

/**
 *
 *
 * @package Pelican
 * @subpackage Translate
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Translate
{

    /**
     * @access public
     *
     * @var array
     */
    public static $translations = array();

    /**
     * @var array
     */
    public static $controlInit = array();

    /**
     *
     *
     * @access public
     * @var array
     */
    public static $translationType = array();

    /**
     *
     *
     * @access public
     * @var array
     */
    public static $path = array();

    /**
     *
     *
     * @access public
     * @var string
     */
    public static $cachepath = '';

    /**
     *
     *
     * @access public
     * @param array $value
     *
     */
    public static function setTranslations($value)
    {
        self::$translations = $value;
    }

    /**
     *
     *
     * @access public
     * @param mixed $site
     * @return string
     */
    public static function getTranslationType($site)
    {
        if (isset(self::$translationType[$site])) {
            return self::$translationType[$site];
        } else {
            return '';
        }
    }

    /**
     *
     *
     * @access public
     *
     * @return string
     */
    public static function getTranslations()
    {
        return self::$translations;
    }

    /**
     *
     *
     * @access public
     * @param string $text
     * @return string
     */
    public static function getTranslation($text)
    {
        $text0 = $text;
        $text = strtr(strtoupper(dropaccent($text)), " ", "_");

        if (isset(self::$translations[$text])) {

            if ($_SESSION['translate'] == 'on') {
                return '...';
            } else {
                if (!empty($_GET['DEBUG_LANG'])) {
                    return "XXXXX_".self::getLang()."_XXXXX";
                } else {
                    return self::$translations[$text];
                }
            }
        } else {
            return "[cle1: ".$text0." Lang:".self::getLang()."]";
        }
    }


    /**
     *
     *
     * @access public
     * @return string
     */
    public static function getCachePath()
    {
        if (empty(self::$cachepath)) {
            self::$cachepath = Pelican::$config["VAR_ROOT"]."/i18n/";
            if (!is_dir(self::$cachepath)) {
                mkdir(self::$cachepath, 0777, true);
            }
        }

        return self::$cachepath;
    }

    /**
     * @return array
     */
    public static function getLanguageCode()
    {
        return Pelican_Cache::fetch("LanguageCode");
    }

    /**
     *
     *
     * @access public
     *
     * @return null|string
     */
    public static function getLang()
    {
        $return = null;
        if (!empty($_SESSION[APP]['LANGUE_CODE'])) {
            $return = $_SESSION[APP]['LANGUE_CODE'];
        }

        return $return;
    }

    /**
     *
     *
     * @access public
     *
     * @return string
     */
    static public function getCodePays()
    {
        if (empty($_SESSION[APP]['CODE_PAYS']) && Pelican::$config["BACK_OFFICE"]) {
            $codePays = 'S1'; // TODO recuperer valeur de SITE_CODE_PAYS du BO
        } else {
            $codePays = $_SESSION[APP]['CODE_PAYS'];
        }

        return $codePays;
    }

    /**
     *
     *
     * @access public
     * @param mixed $text
     *
     * @return mixed
     */
    public static function t($text)
    {
        if (isset($_GET['translate'])) {
            if ($_GET['translate'] == 'on') {
                $_SESSION['translate'] = 'on';
            } elseif ($_GET['translate'] == 'off') {
                $_SESSION['translate'] = 'off';
            }
        }
        if (!isset($_SESSION['translate'])) {
            $_SESSION['translate'] = 'off';
        }
        $return = '';

        $codeLangue = strtolower(self::getLang());
        $codePays = self::getCodePays();


        if (!empty($codeLangue) && !empty($codePays)) {
            if (!isset(self::$controlInit[$codeLangue.'_'.$codePays])) {
                self::init();
            }
        }
        if (isset(self::$controlInit[$codeLangue.'_'.$codePays])) {
            $return = self::getTranslation($text);
        }

        return $return;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public static function createDatabaseKey($key, $value = '')
    {
        if (!empty($_SESSION[APP]['LANGUE_ID'])) {
            $oConnection = Pelican_Db::getInstance();
            Pelican_Db::$values['LABEL_ID'] = $key;
            Pelican_Db::$values['LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
            Pelican_Db::$values['LABEL_TRANSLATE'] = (!empty($value) ? $value : $key);

            $count = $oConnection->queryItem("select count(*)  from #pref#_label where LABEL_ID = '".str_replace("'", "''", $key)."'");
            if (!$count) {
                $oConnection->insertQuery('#pref#_label');
                $oConnection->insertQuery('#pref#_label_langue');
            }
        }
    }

    public static function isUsed($key)
    {
        ini_set('display_errors', 1);
        $execption = array(
            'DATE_FORMAT_DB',
            'DATE_FORMAT_PHP',
        );
        if (!in_array($key, $execption)) {
            if (Pelican::$config["SHOW_DEBUG"]) {
                $oConnection = Pelican_Db::getInstance();
                if ($_SESSION[APP]['SITE_ID'] == Pelican::$config['SITE_BO']) {
                    $oConnection->query(
                        "UPDATE #pref#_label SET LABEL_INFO = 'used', LABEL_BACK = '1' WHERE LABEL_ID = :LABEL_ID",
                        array(
                            ':LABEL_ID' => $oConnection->strToBind($key),
                        )
                    );
                } else {
                    $oConnection->query(
                        "UPDATE #pref#_label SET LABEL_INFO = 'used' WHERE LABEL_ID = :LABEL_ID",
                        array(
                            ':LABEL_ID' => $oConnection->strToBind($key),
                        )
                    );
                }
            }
        }
    }

    /**
     *
     *
     * @access public
     * @return mixed
     */
    public static function init()
    {
        $codeLangue = strtolower(self::getLang());
        $codePays = self::getCodePays();
        $fileName = $codePays.'-'.$codeLangue;
        $filePath = self::getCachePath().$fileName.'.php';

        if (file_exists($filePath)) {

            include $filePath;
            self::$translations = Pelican::$lang;
            self::$controlInit[$codeLangue.'_'.$codePays] = $codeLangue;
        }

    }
}

/**
 * appelle la fonction de traduction et formate le retour
 *
 * @param string $text
 * @param string $alter 'escape', 'js', 'js2' (formatage du texte retour selon l'usage)
 * @param array $aDynamisationParams

  * @return mixed $return variable traduite
 */
function t($text, $alter = '', $aDynamisationParams = array())
{
    if (!$text) {
        return '';
    }

    $return = Pelican_Translate::t($text);

    if (is_array($aDynamisationParams) && sizeof($aDynamisationParams) > 0) {
        foreach ($aDynamisationParams as $key => $replacementsParam) {
            $patternsParam = '/##param'.$key.'(.*?)##/';
            $return = preg_replace($patternsParam, $replacementsParam, $return);
        }
    }

    switch ($alter) {
        case 'escape':
        case 'js2': {
            $return = str_replace(
                array(
                    "'",
                    "\"",
                ),
                array(
                    "\\'",
                    "\\\"",
                ),
                $return
            );
            break;
        }
        case 'js': {
            $return = str_replace(
                array(
                    "'",
                    "/",
                ),
                array(
                    "\'",
                    "\/",
                ),
                $return
            );
            break;
        }
    }

    return $return;
}

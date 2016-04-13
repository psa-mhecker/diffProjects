<?php
/**
 * Gestion des formulaires de saisie avec contrôles de saisie centralisée
 *
 * @package Pelican
 * @subpackage Html
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */
define("REQUIRED", "*");
$STYLE = "accessible";
$STYLE = "normal";

/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Html
 * @author __AUTHOR__
 */
class Pelican_Html_Form extends Pelican_Html
{

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $lib (option) __DESC__
     * @param bool $required (option) __DESC__
     * @param bool $readOnly (option) __DESC__
     * @param string $class (option) __DESC__
     * @param string $valign (option) __DESC__
     * @param string $align (option) __DESC__
     * @return __TYPE__
     */
    public static function lib(
        $lib = "&nbsp;",
        $required = false,
        $readOnly = false,
        $class = "",
        $valign = "",
        $align = ""
    ) {
        $addon = "";
        if ($required && !$readOnly) {
            $addon = " " . REQUIRED;
        }
        $return = self::td(array("class" => $class, valign => $valign, align => $align), $lib . $addon);

        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $val (option) __DESC__
     * @param string $class (option) __DESC__
     * @return __TYPE__
     */
    public static function val($val = "&nbsp;", $class = "")
    {
        $return = self::td(array("class" => $class), $val);

        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $value __DESC__
     * @return __TYPE__
     */
    public static function Pelican_Index_Comment($value)
    {
        $return = self::nbsp() . self::nbsp() . self::span(array("class" => "formcomment"), $value);

        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $src __DESC__
     * @param string $url (option) __DESC__
     * @param string $onclick (option) __DESC__
     * @param string $alt (option) __DESC__
     * @return __TYPE__
     */
    public static function imgComment($src, $url = "", $onclick = "", $alt = "")
    {
        $return = self::img(
            array(
                src => $src,
                alt => $alt,
                border => "0",
                align => "middle",
                onclick => $onclick,
                style => "cursor:pointer"
            )
        );
        if ($url) {
            $return = self::a(array(href => $url), $return);
        }
        $return = self::nbsp() . self::nbsp() . $return;

        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $lib (option) __DESC__
     * @param __TYPE__ $val (option) __DESC__
     * @param bool $required (option) __DESC__
     * @param bool $readOnly (option) __DESC__
     * @param string $classLib (option) __DESC__
     * @param string $classVal (option) __DESC__
     * @param string $valign (option) __DESC__
     * @param string $align (option) __DESC__
     * @param string $disposition (option) __DESC__
     * @param __TYPE__ $trParams (option) __DESC__
     * @return __TYPE__
     */
    public static function get(
        $lib = "&nbsp;",
        $val = "&nbsp;",
        $required = false,
        $readOnly = false,
        $classLib = "",
        $classVal = "",
        $valign = "",
        $align = "",
        $disposition = "",
        $trParams = array()
    ) {
        $td[] = self::lib($lib, $required, $readOnly, $classLib);
        $td[] = self::val($val, $classVal);
        $return = "";
        switch ($disposition) {
            case "vertical": {
                $return = self::tr($trParams, $td[0]) . self::tr($trParams, $td[1]);
                break;
            }
            default: {
            $return = self::tr($trParams, $td[0] . $td[1]);
            break;
            }
        }

        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $lib (option) __DESC__
     * @param __TYPE__ $val (option) __DESC__
     * @param bool $required (option) __DESC__
     * @param bool $readOnly (option) __DESC__
     * @param string $classLib (option) __DESC__
     * @param string $classVal (option) __DESC__
     * @param string $valign (option) __DESC__
     * @param string $align (option) __DESC__
     * @param string $disposition (option) __DESC__
     * @param __TYPE__ $trParams (option) __DESC__
     * @return __TYPE__
     */
    public static function get0(
        $lib = "&nbsp;",
        $val = "&nbsp;",
        $required = false,
        $readOnly = false,
        $classLib = "",
        $classVal = "",
        $valign = "",
        $align = "",
        $disposition = "",
        $trParams = array()
    ) {
        $td[] = $lib;
        $td[] = $val;
        $return = "";
        switch ($disposition) {
            case "vertical": {
                $return = self::label($td[0]) . self::br() . $td[1];
                break;
            }
            default: {
            $return = self::label($td[0]) . $td[1];
            break;
            }
        }

        return self::p($return);
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $input __DESC__
     * @param string $strEvent (option) __DESC__
     * @param __TYPE__ $type (option) __DESC__
     * @return __TYPE__
     */
    public static function addInputEvent($input, $strEvent = "", $type = "input")
    {
        $return = $input;
        if ($strEvent) {
            $return = str_replace("<" . $type, "<" . $type . " " . $strEvent, $input);
        }

        return $return;
    }
}

/**
 * ---------------------------------------
 * Fonctions pour créer un tableau encadrant le formulaire
 *
 * ---------------------------------------
 *
 * *
 * Création du début d'un tag Table adapté à la classe Pelican_Form
 *
 * @param string $cellpadding (option) Marg interne des cellules : "0" par défaut
 *
 * @param string $cellspacing (option) Espacement entre les cellules :"0" par
 * défaut
 * @param string $class (option) Classe css pour la table : "form" par défaut
 * @param bool $bDirectOutput (option) True pour un affichage direct, false pour
 * que les méthodes retournent le code Pelican_Html sous forme de texte
 * @param string $id (option) Identifiant du tag TABLE
 * @return string
 */
function beginFormTable0(
    $cellpadding = "0",
    $cellspacing = "0",
    $class = "form",
    $bDirectOutput = true,
    $id = ""
) {
    $strTemp = "<fieldset><legend></legend>";
    if ($bDirectOutput) {
        echo($strTemp);
    } else {
        return $strTemp;
    }
}

/**
 * __DESC__
 *
 * @param __TYPE__ $cellpadding (option) __DESC__
 * @param __TYPE__ $cellspacing (option) __DESC__
 * @param __TYPE__ $class (option) __DESC__
 * @param bool $bDirectOutput (option) __DESC__
 * @param string $id (option) __DESC__
 * @return __TYPE__
 */
function beginFormTable(
    $cellpadding = "0",
    $cellspacing = "0",
    $class = "form",
    $bDirectOutput = true,
    $id = ""
) {
    $strTemp = "<table border=\"0\" cellspacing=\"" . $cellspacing . "\" cellpadding=\"" . $cellpadding . "\" class=\"" . $class . "\" id=\"tableClassForm" . $id . "\" summary=\"Formulaire\">";
    if ($bDirectOutput) {
        echo($strTemp);
    } else {
        return $strTemp;
    }
}

/**
 * Création d'une ligne de tableau avec des images d'1 pixel de hauteur pour figer
 * les dimensions du tableau d'affichage du formulaire
 *
 * @param string $Width1 (option) Largeur pour les libellés : "120" par défaut
 * @param string $Width2 (option) Largeur pour les valeurs : "520" par défaut
 * @param bool $bDirectOutput (option) True pour un affichage direct, false pour
 * que les méthodes retournent le code Pelican_Html sous forme de texte
 * @return string
 */
function limitFormTable($Width1 = "120", $Width2 = "520", $bDirectOutput = true)
{
    $strTmp = "<tr><td height=\"1\"><img src=\"" . Pelican::$config["LIB_PATH"] . "/public/images/pixel.gif\" width=\"" . $Width1 . "\" height=\"1\" alt=\"\" border=\"0\" /></td><td height=\"1\"><img src=\"" . Pelican::$config["LIB_PATH"] . "/public/images/pixel.gif\" width=\"" . $Width2 . "\" height=\"1\" alt=\"\" border=\"0\" /></td></tr>\n";
    if ($bDirectOutput) {
        echo($strTmp);
    } else {
        return $strTmp;
    }
}

/**
 * Tag TABLE de fin de formulaire
 *
 * @param bool $bDirectOutput (option) True pour un affichage direct, false pour
 * que les méthodes retournent le code Pelican_Html sous forme de texte
 * @return string
 */
function endFormTable0($bDirectOutput = true)
{
    $strTemp = "</fieldset>\n";
    if ($bDirectOutput) {
        echo($strTemp);
    } else {
        return $strTemp;
    }
}

/**
 * __DESC__
 *
 * @param bool $bDirectOutput (option) __DESC__
 * @return __TYPE__
 */
function endFormTable($bDirectOutput = true)
{
    $strTemp = "</table>\n";
    if ($bDirectOutput) {
        echo($strTemp);
    } else {
        return $strTemp;
    }
}
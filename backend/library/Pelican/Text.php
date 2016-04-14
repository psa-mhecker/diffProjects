<?php
/**
 * Classe de traitement des chaines de texte.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */
define('CHARSET_ASCII', 'ascii');
define('CHARSET_UTF8', 'utf8');

/**
 * Classe de traitement des chaines de texte.
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Text
{
    /**
     * Enter description here...
     */
    const test = 5;

    /**
     * Retourne le Charset.
     *
     * @static
     * @access public
     *
     * @return string
     */
    public static function getAppCharset()
    {
        /*static $test = '5';
        static $temp;*/
        $return = str_replace('-', '', strtolower(Pelican::$config["CHARSET"]));
        if ($return == CHARSET_UTF8) {
            include_once dirname(__FILE__).'/Text/Utf8.php';
        }

        return $return;
    }

    /**
     * Retourne le md5 d'une chaîne.
     *
     * @static
     * @access public
     *
     * @param string $str Chaîne à encrypter
     *
     * @return string
     */
    public static function md5($str)
    {
        //$return = md5($str);
        if (is_array($str)) {
            $str = serialize($str);
        }
        $return = bin2hex(crc32($str));

        return $return;
    }

    /**
     * Encodage d'une chaîne de caractère.
     *
     * @static
     * @access public
     *
     * @param string $text Chaîne à encoder
     *
     * @return string
     */
    public static function rawurlencode($text)
    {

        /*
         Correction des encodages des caractères
         €,‚,ƒ,„,…,†,‡,ˆ,‰,Š,‹,Œ,Ž,‘,',',“,᾿,•,–,—,˜,™,š,›,œ,ž,Ÿ
         */
        $replace = array(
        "%80" => "%u20AC",
        "%82" => "%u201A",
        "%83" => "%u0192",
        "%84" => "%u201E",
        "%85" => "%u2026",
        "%86" => "%u2020",
        "%87" => "%u2021",
        "%88" => "%u02C6",
        "%89" => "%u2030",
        "%8A" => "%u0160",
        "%8B" => "%u2039",
        "%8C" => "%u0152",
        "%8E" => "%u017D",
        "%91" => "%u2018",
        "%92" => "%u2019",
        "%92" => "%u2019",
        "%93" => "%u201C",
        "%94" => "%u201D",
        "%95" => "%u2022",
        "%96" => "%u2013",
        "%97" => "%u2014",
        "%98" => "%u02DC",
        "%99" => "%u2122",
        "%9A" => "%u0161",
        "%9B" => "%u203A",
        "%9C" => "%u0153",
        "%9E" => "%u017E",
        "%9F" => "%u0178", );

        return strtr(rawurlencode($text), $replace);
    }

    /**
     * Decodage d'une chaîne de caractère.
     *
     * @static
     * @access public
     *
     * @param string $text Chaîne à decoder
     *
     * @return __TYPE__
     */
    public static function rawurldecode($text)
    {

        /*
         Correction des encodages des caractères
         €,‚,ƒ,„,…,†,‡,ˆ,‰,Š,‹,Œ,Ž,‘,',',“,᾿,•,–,—,˜,™,š,›,œ,ž,Ÿ
         */
        $replace = array(
        "%u20AC" => "€",
        "%u201A" => "‚",
        "%u0192" => "ƒ",
        "%u201E" => "„",
        "%u2026" => "…",
        "%u2020" => "†",
        "%u2021" => "‡",
        "%u02C6" => "ˆ",
        "%u2030" => "‰",
        "%u0160" => "Š",
        "%u2039" => "‹",
        "%u0152" => "Œ",
        "%u017D" => "Ž",
        "%u2018" => "‘",
        "%u2019" => "'",
        "%u2019" => "'",
        "%u201C" => "“",
        "%u201D" => "᾿",
        "%u2022" => "•",
        "%u2013" => "–",
        "%u2014" => "—",
        "%u02DC" => "˜",
        "%u2122" => "™",
        "%u0161" => "š",
        "%u203A" => "›",
        "%u0153" => "œ",
        "%u017E" => "ž",
        "%u0178" => "Ÿ", );

        return strtr(rawurldecode($text), $replace);
    }

    /**
     * Echappement d'une chaîne de caractère.
     *
     * @static
     * @access public
     *
     * @param string $text Chaîne de caractère
     *
     * @return __TYPE__
     */
    public static function htmlencode($text)
    {

        /*
         Correction des encodages des caractères
         €,‚,ƒ,„,…,†,‡,ˆ,‰,Š,‹,Œ,Ž,‘,',',“,᾿,•,–,—,˜,™,š,›,œ,ž,Ÿ
         */
        $replace = array(
        "€" => "&euro;",
        "‘" => "&lsquo;",
        "'" => "&rsquo;",
        "'" => "&rsquo;",
        "“" => "&ldquo;",
        "᾿" => "&rdquo;",
        "–" => "&ndash;",
        "—" => "&mdash;",
        "¡" => "&iexcl;",
        "¢" => "&cent;",
        "£" => "&pound;",
        "£" => "&pound;",
        "¤" => "&curren;",
        "¥" => "&yen;",
        "¦" => "&brvbar;",
        "§" => "&sect;",
        "¨" => "&uml;",
        "©" => "&copy;",
        "ª" => "&ordf;",
        "«" => "&laquo;",
        "¬" => "&not;",
        "®" => "&reg;",
        "¯" => "&macr;",
        "±" => "&plusmn;",
        "²" => "&sup2;",
        "³" => "&sup3;",
        "´" => "&acute;",
        "µ" => "&micro;",
        "¶" => "&para;",
        "·" => "&middot;",
        "¸" => "&cedil;",
        "¹" => "&sup1;",
        "º" => "&ordm;",
        "»" => "&raquo;",
        "¼" => "&frac14;",
        "½" => "&frac12;",
        "¾" => "&frac34;",
        "¿" => "&iquest;",
        "×" => "&times;",
        "Ø" => "&Oslash;",
        "Þ" => "&THORN;",
        "ß" => "&szlig;",
        "ð" => "&eth;",
        "÷" => "&divide;",
        "ø" => "&oslash;",
        "þ" => "&thorn;",
        "æ" => "&aelig;",
        "œ" => "&#156;",
        "…" => "...", );

        $replaceext = array(
        "ñ" => "&ntilde;",
        "ò" => "&ograve;",
        "ó" => "&oacute;",
        "ô" => "&ocirc;",
        "õ" => "&otilde;",
        "ö" => "&ouml;",
        "à" => "&agrave;",
        "á" => "&aacute;",
        "â" => "&acirc;",
        "ã" => "&atilde;",
        "ä" => "&auml;",
        "å" => "&aring;",
        "æ" => "&aelig;",
        "ç" => "&ccedil;",
        "è" => "&egrave;",
        "é" => "&eacute;",
        "ê" => "&ecirc;",
        "ë" => "&euml;",
        "ì" => "&igrave;",
        "í" => "&iacute;",
        "î" => "&icirc;",
        "ï" => "&iuml;",
        "À" => "&Agrave;",
        "�?" => "&Aacute;",
        "Â" => "&Acirc;",
        "Ã" => "&Atilde;",
        "Ä" => "&Auml;",
        "Å" => "&Aring;",
        "Æ" => "&AElig;",
        "Ç" => "&Ccedil;",
        "È" => "&Egrave;",
        "É" => "&Eacute;",
        "Ê" => "&Ecirc;",
        "Ë" => "&Euml;",
        "Ì" => "&Igrave;",
        "�?" => "&Iacute;",
        "Î" => "&Icirc;",
        "�?" => "&Iuml;",
        "�?" => "&ETH;",
        "Ñ" => "&Ntilde;",
        "Ù" => "&Ugrave;",
        "Ú" => "&Uacute;",
        "Û" => "&Ucirc;",
        "Ü" => "&Uuml;",
        "�?" => "&Yacute;",
        "Ò" => "&Ograve;",
        "Ó" => "&Oacute;",
        "Ô" => "&Ocirc;",
        "Õ" => "&Otilde;",
        "Ö" => "&Ouml;",
        "ù" => "&ugrave;",
        "ú" => "&uacute;",
        "û" => "&ucirc;",
        "ü" => "&uuml;",
        "ü" => "&uuml;",
        "ý" => "&yacute;",
        "ÿ" => "&yuml;", );

        $replaceext2 = array(
        "&" => "&amp;",
        "/" => "&frasl;",
        "<" => "&lt;",
        ">" => "&gt;",
        "" => "&sbquo;",
        "" => "&bdquo;",
        "" => "&dagger;",
        "" => "&Dagger;",
        "" => "&permil;",
        "" => "&lsaquo;",
        "" => "&trade;",
        "" => "&rsaquo;",
        " " => "&nbsp;",
        "­" => "&shy;",
        "°" => "&deg;", );

        return strtr(rawurldecode($text), $replace);
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param string $string __DESC__
     *
     * @return __TYPE__
     */
    public static function unhtmlentities($string)
    {
        $trans_tbl = get_html_translation_table(HTML_ENTITIES);
        $trans_tbl = array_flip($trans_tbl);
        $ret = strtr($string, $trans_tbl);
        $return = preg_replace('/&#(\d+);/m', "chr('\\1')", $ret);
        $return = str_replace("&nbsp;", " ", $return);
        $return = str_replace("&rsquo;", "'", $return);
        $return = str_replace("&lsquo;", "'", $return);
        $return = str_replace("&ndash;", "-", $return);
        $return = str_replace("&oelig;", "oe", $return);
        $return = str_replace("&tau;", "τ", $return);

        return $return;
    }
    /*	public static function htmlencode($text) {

    $replace = array(
    "ñ" => "&ntilde;",
    "ò" => "&ograve;",
    "ó" => "&oacute;",
    "ô" => "&ocirc;",
    "õ" => "&otilde;",
    "ö" => "&ouml;",
    "à" => "&agrave;",
    "á" => "&aacute;",
    "â" => "&acirc;",
    "ã" => "&atilde;",
    "ä" => "&auml;",
    "å" => "&aring;",
    "æ" => "&aelig;",
    "ç" => "&ccedil;",
    "è" => "&egrave;",
    "é" => "&eacute;",
    "ê" => "&ecirc;",
    "ë" => "&euml;",
    "ì" => "&igrave;",
    "í" => "&iacute;",
    "î" => "&icirc;",
    "ï" => "&iuml;",
    "À" => "&Agrave;",
    "" => "&Aacute;",
    "Â" => "&Acirc;",
    "Ã" => "&Atilde;",
    "Ä" => "&Auml;",
    "Å" => "&Aring;",
    "Æ" => "&AElig;",
    "Ç" => "&Ccedil;",
    "È" => "&Egrave;",
    "É" => "&Eacute;",
    "Ê" => "&Ecirc;",
    "Ë" => "&Euml;",
    "Ì" => "&Igrave;",
    "" => "&Iacute;",
    "Î" => "&Icirc;",
    "" => "&Iuml;",
    "" => "&ETH;",
    "Ñ" => "&Ntilde;",
    "Ù" => "&Ugrave;",
    "Ú" => "&Uacute;",
    "Û" => "&Ucirc;",
    "Ü" => "&Uuml;",
    "" => "&Yacute;",
    "Ò" => "&Ograve;",
    "Ó" => "&Oacute;",
    "Ô" => "&Ocirc;",
    "Õ" => "&Otilde;",
    "Ö" => "&Ouml;",
    "ù" => "&ugrave;",
    "ú" => "&uacute;",
    "û" => "&ucirc;",
    "ü" => "&uuml;",
    "ü" => "&uuml;",
    "ý" => "&yacute;",
    "ÿ" => "&yuml;");


    $replace = array(
    "ò" => "\\0F2",
    "ó" => "\\0F3",
    "ô" => "\\0F4",
    "õ" => "\\0F5",
    "ö" => "\\0F6",
    "à" => "\\0E0",
    "á" => "\\0E1",
    "â" => "\\0E2",
    "ã" => "\\0E3",
    "ä" => "\\0E4",
    "å" => "\\0E5",
    "æ" => "\\0E6",
    "ç" => "\\0E7",
    "è" => "\\0E8",
    "é" => "\\0E9",
    "ê" => "\\0EA",
    "ë" => "\\0EB",
    "ì" => "\\0EC",
    "í" => "\\0ED",
    "î" => "\\0EE",
    "ï" => "\\0EF",
    "ù" => "\\0F9",
    "ú" => "\\0FA",
    "û" => "\\0FB",
    "ü" => "\\0FC",
    "ý" => "\\0FD"
    );

    return strtr($text, $replace);
    }
    */

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param string $test __DESC__
     *
     * @return __TYPE__
     */
    public static function strToNumber($test)
    {
        $test = str_replace(":", "", $test);
        $test = str_replace("?", "", $test);
        $test = str_replace("~", "", $test);
        $test = str_replace(" ", "", $test);
        $test = str_replace("\\", "", $test);
        $test = str_replace("\"", "", $test);
        $test = str_replace("'", "", $test);
        $pattern = "/[\_\(\)\.\-\/A-Za-z]/";
        $replacement = "";

        return preg_replace($pattern, $replacement, $test);
    }

    /**
     * Fonction Remplacant HTMLentities pour prendre en compte le charset défini dans
     * Pelican::$config["CHARSET"].
     *
     * @static
     * @access public
     *
     * @param string $str Chaine de caractère à traiter
     *
     * @return string
     */
    public static function htmlentities($str)
    {
        $return = "";
        if (Pelican_Text::getAppCharset() == CHARSET_UTF8) {
            $return = Pelican_Text_Utf8::htmlentities($str);
        } else {
            $return = htmlentities($str);
        }

        return $return;
    }

    /**
     * DateToString, retourne un date de format jj/mm/yyyy en chaine de la forme
     * yyyymmjj utile pour le trie.
     *
     * @static
     * @access public
     *
     * @author fairouz Bihler <fbihler@businessdecision.com>
     *
     * @since 13/10/2004
     *
     * @param string $sDate __DESC__
     *
     * @return __TYPE__
     */
    public static function dateToString($sDate)
    {
        $dateTemp = explode("/", $sDate);
        $pJour = $dateTemp[0];
        $pMois = $dateTemp[1];
        $pAnnee = $dateTemp[2];

        return $pAnnee.$pMois.$pJour;
    }

    /**
     * StringToDate, retourne un date de format yyyymmjj en chaine de la forme
     * jj/mm/yyyy util pour le trie.
     *
     * @static
     * @access public
     *
     * @author fairouz Bihler <fbihler@businessdecision.com>
     *
     * @since 13/10/2004
     *
     * @param string $sDate __DESC__
     *
     * @return __TYPE__
     */
    public static function stringToDate($sDate)
    {
        if ($sDate) {
            $pJour = substr($sDate, 6, 2);
            $pMois = substr($sDate, 4, 2);
            $pAnnee = substr($sDate, 0, 4);
            $return = $pJour."/".$pMois."/".$pAnnee;
        } else {
            $return = "";
        }

        return $$return;
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param string $text     __DESC__
     * @param string $charlist (option) (optionnel) __DESC__
     *
     * @return __TYPE__
     */
    public static function trim($text, $charlist = '')
    {
        $return = '';
        if (Pelican_Text::getAppCharset() == CHARSET_UTF8) {
            $return = Pelican_Text_Utf8::trim($text, $charlist);
        } else {
            $return = trim($text, $charlist);
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param string $text __DESC__
     *
     * @return __TYPE__
     */
    public static function dropAccent($text)
    {
        $return = "";
        if (Pelican_Text::getAppCharset() == CHARSET_UTF8) {
            $return = Pelican_Text_Utf8::dropAccent($text);
        } else {
            $return = (strtr(trim($text), 'À�?ÂÃÄÅĄàáâãäåąĆćÒÓÔÕÖØÓòóôõöøÈÉÊËĘèéêëęÇçÌ�?Î�?ìíîïÙÚÛÜùúûüÿÑŃñń�?łŚśŹŻ«»Č�?Ď�?ĚěŇňŘřŠšŤťŮů�?ýŽž', 'AAAAAAAaaaaaaaCcOOOOOOÓooooooEEEEEeeeeeCcIIIIiiiiUUUUuuuuyNNnnLlSsZZ""CcDdEeNnRrSsTtUuYyZz'));
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param string $text __DESC__
     *
     * @return __TYPE__
     */
    public static function ansi2asci($text)
    {
        return (strtr(trim($text), Chr(228).chr(246).chr(252).chr(223).chr(196).chr(214).chr(220).chr(199).chr(233).chr(226).chr(224).chr(229).chr(231).chr(234).chr(235).chr(232).chr(239).chr(238).chr(197).chr(201).chr(244).chr(251).chr(249).chr(182).chr(230).chr(198).chr(162).chr(163).chr(170).chr(186).chr(189).chr(188).chr(216).chr(177).chr(247).chr(178).chr(167).chr(165), Chr(132).chr(148).chr(129).chr(225).chr(142).chr(153).chr(154).chr(128).chr(130).chr(131).chr(133).chr(134).chr(135).chr(136).chr(137).chr(138).chr(139).chr(140).chr(143).chr(144).chr(147).chr(150).chr(151).chr(20).chr(145).chr(146).chr(155).chr(156).chr(166).chr(167).chr(171).chr(172).chr(237).chr(241).chr(246).chr(253).chr(21).chr(157)));
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param string $value __DESC__
     *
     * @return __TYPE__
     */
    public static function trimCote($value)
    {
        return trim($value, '"');
    }

    /**
     * Retourne <kbd>true</kbd> si la string ou l'array de string est encodé en UTF8.
     *
     * Exemple d'utilisation. Vous voulez afficher un fichier texte sans vous soucier
     * de son encodage (UTF-8 ou 8-bit).
     * $array = file('fichier.txt');
     * $isUTF8 = isUTF8($array);
     * foreach($array as $val)
     * {
     *     echo $isUTF8?utf8entities($val)
     *                 :htmlentities($val);
     * }
     *
     * @static
     * @access public
     *
     * @author iubito
     *
     * @param string $string __DESC__
     *
     * @return bool
     */
    public static function isUTF8($string)
    {
        if (is_array($string)) {
            $enc = implode('', $string);

            return @!((ord($enc[0]) != 239) && (ord($enc[1]) != 187) && (ord($enc[2]) != 191));
        } else {
            return (utf8_encode(utf8_decode($string)) == $string);
        }
    }

    /**
     * Utf8entities.
     *
     * Pour améliorer l'htmlentities() pour les chaînes en UTF-8 !
     *
     * Transforme une chaîne UTF8 en entitées Pelican_Html & # nnn; n={0..9} visible
     * dans tous les navigateurs.
     *
     * @static
     * @access public
     *
     * @see http://www.php.net/utf8_decode
     * @see http://www.randomchaos.com/document.php?source=php_and_unicode
     *
     * @param string $str __DESC__
     *
     * @return string
     */
    public static function utf8entities($str)
    {
        if (!is_string($str)) {
            die('<b>Warning:</b><br/>'.'<tt>utf8entities(string $source)</tt> : $source should be a string.');
        }
        //utf8 to unicode
        $unicode = array();
        $values = array();
        $lookingFor = 1;
        $len = strlen($str);
        for ($i = 0;$i < $len;$i++) {
            $thisValue = ord($str[$i]);
            if ($thisValue < 128) {
                $unicode[] = $thisValue;
            } else {
                if (count($values) == 0) {
                    $lookingFor = (($thisValue < 224) ? 2 : 3);
                }
                $values[] = $thisValue;
                if (count($values) == $lookingFor) {
                    $number = ($lookingFor == 3) ? (($values[0] % 16) * 4096) + (($values[1] % 64) * 64) + ($values[2] % 64) : (($values[0] % 32) * 64) + ($values[1] % 64);
                    $unicode[] = $number;
                    $values = array();
                    $lookingFor = 1;
                } // if
            } // if
        } // for
        $entities = '';
        foreach ($unicode as $value) {
            $entities .= ($value < 128 ? chr($value) : ('&#'.$value.';'));
        }

        return $entities;
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param string $text     __DESC__
     * @param string $escape   (option) (optionnel) __DESC__
     * @param bool   $tag      (option) (optionnel) __DESC__
     * @param bool   $unchange (option) (optionnel) __DESC__
     *
     * @return __TYPE__
     */
    public static function cleanText($text, $escape = "-", $tag = false, $unchange = true)
    {
        $return = $text;
        if ($tag) {
            $escape = "_";
        }
        $return = Pelican_Text::trim($return);
        $return = Pelican_Text::unhtmlentities($return);
        if (!$unchange) {
            $return = Pelican_Text::romanize($return);
            $return = Pelican_Text::strtolower($return);
            $return = stripslashes($return);
        }
        if ($return) {
            //$return = preg_replace("/\n(\s+)?(.+)(\s+)?/", $escape."$2", $return);
            $return = preg_replace('/[\»\«\^\·\'\;\-\:\?\,\"\&\_\=\°\/\%\!\(\)\`\+\'\*\[\]\#\$]/i', $escape, $return);
            $return = preg_replace('/[\>\<]/i', '', $return);
            $return = preg_replace('/[\s]{1,10}/i', $escape, $return);
            $return = preg_replace('/_{1,10}/i', $escape, $return);
            $return = preg_replace('/\.{2,4}/i', $escape, $return);
            $return = preg_replace('/'.$escape.'{1,10}/i', $escape, $return);
            if ($tag) {
                $return = preg_replace('/'.$escape.'[a-z0-9]{1,2}'.$escape.'/i', $escape, $return);
                $return = preg_replace('/'.$escape.'[a-z0-9]{1,2}'.$escape.'/i', $escape, $return);
            }
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param string $email __DESC__
     *
     * @return __TYPE__
     */
    public static function cleanEmail($email)
    {
        if ($email) {
            $email = Pelican_Text::dropAccent(trim(strtolower(str_replace(" ", "", $email))));
        } else {
            unset($email);
        }
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param string $text __DESC__
     *
     * @return __TYPE__
     */
    public static function cleanReturns($text)
    {
        $return = "";
        $return = str_replace(array("\r\n", "\r", "\n"), '', $text);

        return $return;
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param string $text __DESC__
     *
     * @return __TYPE__
     */
    public static function cleanRepeatedPonctuation($text)
    {
        $return = "";
        $return = preg_replace("/\.+/i", ".", $text);

        return $return;
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param string $text __DESC__
     *
     * @return __TYPE__
     */
    public static function cleanRepeatedWords($text)
    {
        $return = "";
        $return = preg_replace("/\s(\w+\s)\1/i", "$1", $text);

        return $return;
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param string $text __DESC__
     *
     * @return __TYPE__
     */
    public static function cleanNonPrintableCharacters($text)
    {
        $return = "";
        $return = preg_replace("/[^[:print:]]+/", "", $text);

        return $return;
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param string $text __DESC__
     *
     * @return __TYPE__
     */
    public static function strip_tags($text)
    {
        $return = "";
        $text = preg_replace(array(
        // Remove invisible content
    '@<head[^>]*?>.*?</head>@siu',
    '@<style[^>]*?>.*?</style>@siu',
    '@<script[^>]*?.*?</script>@siu',
    '@<object[^>]*?.*?</object>@siu',
    '@<embed[^>]*?.*?</embed>@siu',
    '@<applet[^>]*?.*?</applet>@siu',
    '@<noframes[^>]*?.*?</noframes>@siu',
    '@<noscript[^>]*?.*?</noscript>@siu',
    '@<noembed[^>]*?.*?</noembed>@siu',    // Add line breaks before & after blocks
    '@<((br)|(hr))@iu',
    '@</?((address)|(blockquote)|(center)|(del))@iu',
    '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
    '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
    '@</?((table)|(th)|(td)|(caption))@iu',
    '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
    '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
    '@</?((frameset)|(frame)|(iframe))@iu', ),    array(
    ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
    "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
    "\n\$0", "\n\$0", ), $text
    );

        // Remove all remaining tags and comments and return.
        $return = strip_tags($text);

        return $return;
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param string $text __DESC__
     * @param bool   $up   (option) (optionnel) __DESC__
     *
     * @return __TYPE__
     */
    public static function stringUpDown($text, $up = true)
    {
        // Convert values from Lower to Upper
        $arrayLower = array('ć', 'ç', 'â', 'ã', 'à', 'á', 'ä', 'ą', 'é', 'è', 'ê', 'ë', 'ę', 'í', 'ì', 'î', 'ï', 'ł', 'ń', 'ó', 'ò', 'ô', 'õ', 'ö', 'ś', 'ú', 'ù', 'û', 'ü', 'ź', 'ż');
        $arrayUpper = array('Ć', 'Ç', 'Â', 'Ã', 'À', '�?', 'Ä', 'Ą', 'É', 'È', 'Ê', 'Ë', 'Ę', '�?', 'Ì', 'Î', '�?', '�?', 'Ń', 'Ó', 'Ò', 'Õ', 'Ô', 'Ö', 'Ś', 'Ú', 'Ù', 'Û', 'Ü', 'Ź', 'Ż');
        if ($text == '') {
            return $text;
        }
        if ($up != true) {
            $text = strtolower($text);
            $text = str_replace($arrayUpper, $arrayLower, $text);
        } else {
            $text = strtoupper($text);
            $text = str_replace($arrayLower, $arrayUpper, $text);
        }

        return ($text);
    }

    /**
     * Checks if a string contains 7bit ASCII only.
     *
     * @static
     * @access public
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     *
     * @param string $str __DESC__
     *
     * @return __TYPE__
     */
    public static function isASCII($str)
    {
        for ($i = 0;$i < strlen($str);$i++) {
            if (ord($str{$i}) > 127) {
                return false;
            }
        }

        return true;
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param string $text __DESC__
     *
     * @return __TYPE__
     */
    public static function strtolower($text)
    {
        $return = '';
        if (Pelican_Text::getAppCharset() == CHARSET_UTF8) {
            $return = Pelican_Text_Utf8::strtolower($text);
        } else {
            $return = strtolower($text);
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param string $text __DESC__
     *
     * @return __TYPE__
     */
    public static function strtoupper($text)
    {
        $return = '';
        if (Pelican_Text::getAppCharset() == CHARSET_UTF8) {
            $return = Pelican_Text_Utf8::strtoupper($text);
        } else {
            $return = strtoupper($text);
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param string $text __DESC__
     *
     * @return __TYPE__
     */
    public static function ucfirst($text)
    {
        $return = '';
        if (Pelican_Text::getAppCharset() == CHARSET_UTF8) {
            $return = Pelican_Text_Utf8::ucfirst($text);
        } else {
            $return = ucfirst($text);
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param string $value __DESC__
     *
     * @return __TYPE__
     */
    public function ucwordfirst($value)
    {
        $tmp = explode(' ', str_replace('_', ' ', strtolower($value)));
        $tmp = array_map("ucfirst", $tmp);
        $return = implode(" ", $tmp);

        return $return;
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param string $text __DESC__
     *
     * @return __TYPE__
     */
    public static function ucwords($text)
    {
        $return = '';
        if (Pelican_Text::getAppCharset() == CHARSET_UTF8) {
            $return = Pelican_Text_Utf8::ucwords($text);
        } else {
            $return = ucwords($text);
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param string $haystack __DESC__
     * @param string $needle   __DESC__
     * @param string $offset   (option) (optionnel) __DESC__
     *
     * @return __TYPE__
     */
    public function strpos($haystack, $needle, $offset = 0)
    {
        $return = '';
        if (Pelican_Text::getAppCharset() == CHARSET_UTF8) {
            $return = Pelican_Text_Utf8::strpos($haystack, $needle, $offset);
        } else {
            $return = strpos($haystack, $needle, $offset);
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param string $text __DESC__
     *
     * @return __TYPE__
     */
    public static function romanize($text)
    {
        $return = '';
        if (Pelican_Text::getAppCharset() == CHARSET_UTF8) {

            /* translit est utilisé s'il est installé */
            if (function_exists('transliterate')) {
                //debug(transliterate_filters_get());
            $options = array('cyrillic_transliterate',
            'cyrillic_transliterate_bulgarian',
            'diacritical_remove',
            'greek_transliterate',
            'han_transliterate',
            'hebrew_transliterate',
            'jamo_transliterate',
            'lowercase_cyrillic',
            'cyrillic_lowercase',
            'lowercase_greek',
            'greek_lowercase',
            'lowercase_latin',
            'latin_lowercase',
            'normalize_ligature',
            'normalize_punctuation',
            'remove_punctuation',
            'spaces_to_underscore',
            'normalize_superscript_numbers',
            'normalize_subscript_numbers',
            'normalize_numbers',
            'normalize_superscript',
            'normalize_subscript',
            'decompose_special',
            'decompose_currency_signs',
            'decompose',
            'hangul_to_jamo', );
                $return = transliterate($text, $options, 'utf-8', 'utf-8');
            } else {
                $return = $text;
            }

            /* dans le doute ou si translit n'est pas installé */
            $return = Pelican_Text_Utf8::romanize($return);
        } else {
            $return = $text;
        }

        /* nettoyage des accents */
        $return = Pelican_Text::dropAccent($return);

        return $return;
    }
}

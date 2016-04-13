<?php
/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Html
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */
class Pelican_Html_Util {
    // Written by Fredrik Kristiansen (russlndr at online.no)
    // and Albrecht Guenther (ag at phprojekt.de).
    
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $html __DESC__
     * @return __TYPE__
     */
    function text2Link($html) {
        $html = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_+.~#?&//=]+)', '<a href="\1" target="_blank">\1</a>', $html);
        $html = eregi_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_+.~#?&//=]+)', '\1<a href="http://\2" target="_blank">\2</a>', $html);
        $html = eregi_replace('([_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3})', '<a href="mailto:\1">\1</a>', $html);
        return $html;
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $html __DESC__
     * @return __TYPE__
     */
    function noSpam($html) {
        //    $return = eregi_replace('([_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3})','<a href="mailto:\1">\1</a>', $html);
        return $return;
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $html __DESC__
     * @return __TYPE__
     */
    function getBody($html) {
        return eregi_replace('(^.*<html[^>]*>.*<body[^>]*>)|(</body[^>]*>.*</html[^>]*>.*$)', '', $html);
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $html __DESC__
     * @return __TYPE__
     */
    function dropComments($html) {
        $return = preg_replace('/<!\-\-.[^(\-\-\>)]*?\-\->/i', '', $html);
        return $return;
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $html __DESC__
     * @param __TYPE__ $tag __DESC__
     * @return __TYPE__
     */
    function droptag($html, $tag) {
        return preg_replace('@<(' . strtolower($tag) . '|' . strtoupper($tag) . ')[^>]*?>.*?</(' . strtolower($tag) . '|' . strtoupper($tag) . ')>@si', 'XXXXXXXXXXX', $html);
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $html __DESC__
     * @param __TYPE__ $aExpressions __DESC__
     * @return __TYPE__
     */
    function dropExpressions($html, $aExpressions) {
        $blurb = "@!#$";
        
        /**
         * Enter description here...
         *
         * @param unknown_type $html
         * @return unknown
         */
        return preg_replace("/(^|[^a-zA-Z])(" . implode("|", $aExpressions) . ")([^a-zA-Z]|$)/si", '\\1' . $blurb . '\\3', $html);
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $html __DESC__
     * @return __TYPE__
     */
    function dropUnprintable($html) {
        return preg_replace("/\\x0|[\x01-\x1f]/U", "", $html);
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $html __DESC__
     * @param string $host (option) __DESC__
     * @return __TYPE__
     */
    function extractLinks($html, $host = "") {
        //Pattern building across multiple lines to avoid page distortion.
        $pattern = "/((@import\s+[\"'`]([\w:?=@&\/#._;-]+)[\"'`];)|";
        $pattern.= "(:\s*url\s*\([\s\"'`]*([\w:?=@&\/#._;-]+)";
        $pattern.= "([\s\"'`]*\))|<[^>]*\s+(src|href|url)\=[\s\"'`]*";
        $pattern.= "([\w:?=@&\/#._;-]+)[\s\"'`]*[^>]*>))/i";
        //End pattern building.
        preg_match_all($pattern, $html, $matches);
        if ($matches) {
            foreach($matches[7] as $key => $type) {
                $tmp = parse_url($matches[8][$key]);
                if ($host) {
                    if ($tmp["host"] == $host) {
                        $tmp = "";
                    }
                }
                if ($tmp) {
                    $return[$type][($tmp["scheme"] ? $tmp["scheme"] : "http") ][($tmp["host"] ? $tmp["host"] : $_SERVER["HTTP_HOST"]) ][$tmp["path"]][$tmp["query"]][] = $matches[8][$key];
                }
                //    $return2[$type][] = $matches[8][$key];
                
            }
        }
        return $return;
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $html __DESC__
     * @return __TYPE__
     */
    function compress($html) {
        $html = str_replace("\t", " ", $html);
        $html = preg_replace('/(\s)\/\/(.*)(\s)/', '\\1/* \\2 */\\3', $html);
        $search = array('/(\s+)?(\<.+\>)(\s+)?/', '/(\s)+/s');
        $replace = array('\\2', '\\1');
        $html = preg_replace($search, $replace, $html);
        $html = str_replace("\n", "", $html);
        return $html;
    }
    
    /**
     * Encodage d'un email en codes numériques pour éviter la collecte d'email
     * servant au spam
     *
     * @access public
     * @param string $text Contenu d'une page
     * @return string
     */
    function encodeAllEmail($text) {
        $return = $text;
        $match = array();
        $regexp = "/[a-zA-Z0-9\\.\\-\\_]*[a-zA-Z0-9\\.\\-\\_]\\@(\\[?)[a-zA-Z0-9\\-\\.]+\\.([a-zA-Z]{2,3}|[0-9]{1,3})(\\]?)/s";
        preg_match_all($regexp, $return, $match);
        $email = array_unique($match[0]);
        if ($email) {
            foreach($email as $key) {
                $replace[$key] = Pelican_Html_Util::encodeEmail($key);
            }
            $return = strtr($return, $replace);
        }
        return $return;
    }
    
    /**
     * Encodage d'un email en codes numériques
     *
     * @access public
     * @param string $email __DESC__
     * @return string
     */
    function encodeEmail($email) {
        $aChar = str_split($email);
        $email = '';
        foreach($aChar as $char) {
            $email.= '&#' . ord($char) . ';';
        }
        return $email;
        
        /**
         * ereg_replace deprecated depuis php5.3
         */
        /*
        $cars = array(
        'A' ,
        'B' ,
        'C' ,
        'D' ,
        'E' ,
        'F' ,
        'G' ,
        'H' ,
        'I' ,
        'J' ,
        'K' ,
        'L' ,
        'M' ,
        'N' ,
        'O' ,
        'P' ,
        'Q' ,
        'R' ,
        'S' ,
        'T' ,
        'U' ,
        'V' ,
        'W' ,
        'X' ,
        'Y' ,
        'Z' ,
        'a' ,
        'b' ,
        'c' ,
        'd' ,
        'e' ,
        'f' ,
        'g' ,
        'h' ,
        'i' ,
        'j' ,
        'k' ,
        'l' ,
        'm' ,
        'n' ,
        'o' ,
        'p' ,
        'q' ,
        'r' ,
        's' ,
        't' ,
        'u' ,
        'v' ,
        'w' ,
        'x' ,
        'y' ,
        'z' ,
        '@');
        $htmls = array(
        '65' ,
        '66' ,
        '67' ,
        '68' ,
        '69' ,
        '70' ,
        '71' ,
        '72' ,
        '73' ,
        '74' ,
        '75' ,
        '76' ,
        '77' ,
        '78' ,
        '79' ,
        '80' ,
        '81' ,
        '82' ,
        '83' ,
        '84' ,
        '85' ,
        '86' ,
        '87' ,
        '88' ,
        '89' ,
        '90' ,
        '97' ,
        '98' ,
        '99' ,
        '100' ,
        '101' ,
        '102' ,
        '103' ,
        '104' ,
        '105' ,
        '106' ,
        '107' ,
        '108' ,
        '109' ,
        '110' ,
        '111' ,
        '112' ,
        '113' ,
        '114' ,
        '115' ,
        '116' ,
        '117' ,
        '118' ,
        '119' ,
        '120' ,
        '121' ,
        '122' ,
        '64');
        
        $nbcars = count($cars);
        for ($i = 0; $i < $nbcars; $i ++) {
        $email = ereg_replace($cars[$i], '&#' . $htmls[$i] . ';', $email);
        }
        return $email;
        */
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $html __DESC__
     * @param __TYPE__ $expression __DESC__
     * @param __TYPE__ $color (option) __DESC__
     * @return __TYPE__
     */
    function highlightWord($html, $expression, $color = "yellow") {
        $pattern = '(>[^<]*)(' . quotemeta($expression) . ')';
        $replacement = '\\1<span style="background-color:' . $color . ';">\\2</span>';
        return eregi_replace($pattern, $replacement, $html);
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $html __DESC__
     * @param __TYPE__ $warp (option) __DESC__
     * @return __TYPE__
     */
    function wrap($html, $warp = 72) {
        $text = explode(" ", $html);
        $i = 0;
        $length = 0;
        while ($i <= count($text)) {
            $length+= strlen($text[$i]);
            if ($length <= $warp) {
                $output.= $text[$i] . " ";
                $i++;
            } else {
                $output.= "\n";
                $length = 0;
            }
        }
        return $output;
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $query_string __DESC__
     * @param __TYPE__ $aAllow __DESC__
     * @return __TYPE__
     */
    function cleanUrlParameters($query_string, $aAllow) {
        $allow = implode("|", $aAllow);
        $return = preg_replace('#([^\=&\?]+)(?<!' . $allow . ')\=([^\=&]+)(&)?#', '', $query_string);
        return $return;
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $html __DESC__
     * @param __TYPE__ $supDns (option) __DESC__
     * @return __TYPE__
     */
    function dnsSharding($html, $supDns = array()) {
        global $aDns;
        if (!empty($supDns)) {
            $step = 10;
            // le premier doit être le media standard
            if (!in_array(str_replace('http://', '', Pelican::$config['MEDIA_HTTP']), $supDns)) {
                $aDns[] = str_replace('http://', '', Pelican::$config['MEDIA_HTTP']);
            }
            foreach($supDns as $dns) {
                $aDns[] = $dns;
            }
            // extract image link from media/image
            $pattern = '/src\=[\s\"\'`](http|https):\/\/(' . implode('|', str_replace('.', '\.', $aDns)) . ')\/image\/([\w:?=@&\/#._;-]+)([\s\"`\'`]*[^>]*>)/i';
            // die();
            $html = preg_replace_callback($pattern, array('Pelican_Html_Util', 'rollDns'), $html);
        }
        return $html;
    }
    
    /**
     * __DESC__
     *
     * @static
     * @access public
     * @param __TYPE__ $matches __DESC__
     * @return __TYPE__
     */
    static function rollDns($matches) {
        global $aDns;
        $indice = self::extract_numbers($matches[3]);
        $return = 'src="' . $matches[1] . '://' . $aDns[$indice] . '/image/' . $matches[3] . $matches[4] . '"';
        $return = str_replace(' />"', ' />', $return);
        return $return;
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param string $string __DESC__
     * @return __TYPE__
     */
    function extract_numbers($string) {
        preg_match_all('/([\d]+)/', $string, $match);
        $return = intVal($match[0][1] / 3);
        return $return;
    }
    
    /**
     /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $html __DESC__
     * @return __TYPE__
     */
    function numericEntities($html) {
        return preg_replace('/[^!-%\x27-;=?-~ ]/e', '"&#".ord("$0").chr(59)', $html);
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $input __DESC__
     * @return __TYPE__
     */
    function checkOutput($input) {
        include_once (Pelican::$config['LIB_ROOT'] . Pelican::$config['LIB_OTHER'] . "/Htmltidy/tidy_check.php");
    }
    
    /**
     * Nettoyage des code Embed (Flash) issus du miniword : incompatibles avec Safari
     * => tout code
     *
     * <embed * />
     *
     * trouvé est remplacé par le code
     *
     * <script>
     * APPEL à SWFOBJECT pour s'adapter au navigateur
     * </script>
     * <noscript>
     * <embed * />
     * </noscript>
     *
     * équivalent
     *
     * @access public
     * @param string $flash __DESC__
     * @return string
     */
    function cleanEmbed($flash) {
        $param[1] = "name";
        $param[2] = "src";
        $param[3] = "width";
        $param[4] = "height";
        $regexp = "/\<embed(.*)";
        foreach($param as $par) {
            $regexp.= "(\\s){0,1}" . $par . "\=\"(.[^\"]*)\"(.[^>]*)";
        }
        $regexp.= "\\s\/\>/ui";
        $tmp = str_replace("&gt;", ">", str_replace("&lt;", "<", str_replace("&quot;", "\"", htmlentities($flash))));
        preg_match_all($regexp, $tmp, $out);
        //debug($out);
        if ($out) {
            for ($i = 0;$i < count($out[0]);$i++) {
                $replace = "<script>
					var so = new SWFObject(\"" . $out[6][$i] . "\", \"" . $out[3][$i] . "\", \"" . $out[9][$i] . "\", \"" . $out[12][$i] . "\", \"7\", \"#ffffff\");
					so.addParam(\"scale\", \"noscale\");
					so.addParam(\"quality\", \"high\");
					so.addParam(\"wmode\", \"transparent\");
					document.write(so.getSWFHTML());
					</script>
					<noscript>
					" . $out[0][$i] . "
					</noscript>";
                $flash = str_replace($out[0][$i], $replace, $flash);
            }
        }
        return $flash;
    }
    
    /**
     * Strips extra whitespace from output
     *
     * @static __DESC__
     * @access public
     * @param string $str String to sanitize
     * @return string
     */
    function stripWhitespace($str) {
        $r = preg_replace('/[\n\r\t]+/', '', $str);
        return preg_replace('/\s{2,}/', ' ', $r);
    }
    
    /**
     * Strips image tags from output
     *
     * @static __DESC__
     * @access public
     * @param string $str String to sanitize
     * @return string
     */
    function stripImages($str) {
        $str = preg_replace('/(<a[^>]*>)(<img[^>]+alt=")([^"]*)("[^>]*>)(<\/a>)/i', '$1$3$5<br />', $str);
        $str = preg_replace('/(<img[^>]+alt=")([^"]*)("[^>]*>)/i', '$2<br />', $str);
        $str = preg_replace('/<img[^>]*>/i', '', $str);
        return $str;
    }
    
    /**
     * Strips scripts and stylesheets from output
     *
     * @static __DESC__
     * @access public
     * @param string $str String to sanitize
     * @return string
     */
    function stripScripts($str) {
        return preg_replace('/(<link[^>]+rel="[^"]*stylesheet"[^>]*>|<img[^>]*>|style="[^"]*")|<script[^>]*>.*?<\/script>|<style[^>]*>.*?<\/style>|<!--.*?-->/i', '', $str);
    }
    
    /**
     * Strips extra whitespace, images, scripts and stylesheets from output
     *
     * @access public
     * @param string $str String to sanitize
     * @return string
     */
    function stripAll($str) {
        $str = Sanitize::stripWhitespace($str);
        $str = Sanitize::stripImages($str);
        $str = Sanitize::stripScripts($str);
        return $str;
    }
    
    /**
     * Strips the specified tags from output. First parameter is string from
     * where to remove tags. All subsequent parameters are tags.
     *
     * @static __DESC__
     * @access public
     * @return string
     */
    function stripTags() {
        $params = params(func_get_args());
        $str = $params[0];
        for ($i = 1;$i < count($params);$i++) {
            $str = preg_replace('/<' . $params[$i] . '\b[^>]*>/i', '', $str);
            $str = preg_replace('/<\/' . $params[$i] . '[^>]*>/i', '', $str);
        }
        return $str;
    }
}

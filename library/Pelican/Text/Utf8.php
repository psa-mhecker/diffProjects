<?php
/**
 * UTF8 helper functions
 *
 * @license    LGPL (http://www.gnu.org/copyleft/lesser.html)
 * @author     Andreas Gohr <andi@splitbrain.org>
 */

/**
 * check for mb_string support
 */
if (! defined('UTF8_MBSTRING')) {
    if (function_exists('mb_substr') && ! defined('UTF8_NOMBSTRING')) {
        define('UTF8_MBSTRING', 1);
    } else {
        define('UTF8_MBSTRING', 0);
    }
}

if (UTF8_MBSTRING) {
    mb_internal_encoding('UTF-8');
}

if (! class_exists('utf8_entity_decoder')) {
    class utf8_entity_decoder
    {

        var $table;

        public static function entity_decoder ()
        {
            $table = get_html_translation_table(HTML_ENTITIES);
            $table = array_flip($table);
            $table = array_map(array(&$this , 'makeutf8'), $table);
        }

        public static function makeutf8 ($c)
        {
            return unicode_to_utf8(array(ord($c)));
        }

        public static function decode ($ent)
        {
            if ($ent[1] == '#') {
                return Pelican_Text_Utf8::decode_numeric($ent);
            } elseif (array_key_exists($ent[0], $table)) {
                return $table[$ent[0]];
            } else {
                return $ent[0];
            }
        }
    }
}

class Pelican_Text_Utf8
{

    //'Pelican_Text_Utf8::filenameEncode
    /**
     * URL-Encode a filename to allow unicodecharacters
     *
     * Slashes are not encoded
     *
     * When the second parameter is true the string will
     * be encoded only if non ASCII characters are detected -
     * This makes it safe to run it multiple times on the
     * same string (default is true)
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     * @see    urlencode
     */
    public static function filenameEncode ($file, $safe = true)
    {
        if ($safe && preg_match('#^[a-zA-Z0-9/_\-.%]+$#', $file)) {
            return $file;
        }
        $file = urlencode($file);
        $file = str_replace('%2F', '/', $file);
        return $file;
    }

    /**
     * URL-Decode a filename
     *
     * This is just a wrapper around urldecode
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     * @see    urldecode
     */
    public static function filenameDecode ($file)
    {
        $file = urldecode($file);
        return $file;
    }

    /**
     * Checks if a string contains 7bit ASCII only
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     */
    public static function isASCII ($str)
    {
        for ($i = 0; $i < strlen($str); $i ++) {
            if (ord($str{$i}) > 127)
                return false;
        }
        return true;
    }

    /**
     * Strips all highbyte chars
     *
     * Returns a pure ASCII7 string
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     */
    public static function strip ($str)
    {
        $ascii = '';
        for ($i = 0; $i < strlen($str); $i ++) {
            if (ord($str{$i}) < 128) {
                $ascii .= $str{$i};
            }
        }
        return $ascii;
    }

    /**
     * Tries to detect if a string is in Unicode encoding
     *
     * @author <bmorel@ssi.fr>
     * @link   http://www.php.net/manual/en/function.utf8-encode.php
     */
    public static function check ($Str)
    {
        for ($i = 0; $i < strlen($Str); $i ++) {
            $b = ord($Str[$i]);
            if ($b < 0x80)
                continue; # 0bbbbbbb
            elseif (($b & 0xE0) == 0xC0)
                $n = 1; # 110bbbbb
            elseif (($b & 0xF0) == 0xE0)
                $n = 2; # 1110bbbb
            elseif (($b & 0xF8) == 0xF0)
                $n = 3; # 11110bbb
            elseif (($b & 0xFC) == 0xF8)
                $n = 4; # 111110bb
            elseif (($b & 0xFE) == 0xFC)
                $n = 5; # 1111110b
            else
                return false; # Does not match any model
            

            for ($j = 0; $j < $n; $j ++) { # n bytes matching 10bbbbbb follow ?
                if ((++ $i == strlen($Str)) || ((ord($Str[$i]) & 0xC0) != 0x80))
                    return false;
            }
        }
        return true;
    }

    /**
     * Unicode aware replacement for strlen()
     *
     * utf8_entity_decoder::decode() converts characters that are not in ISO-8859-1
     * to '?', which, for the purpose of counting, is alright - It's
     * even faster than mb_strlen.
     *
     * @author <chernyshevsky at hotmail dot com>
     * @see    strlen()
     * @see    Pelican_Text_Utf8::decode()
     */
    public static function strlen ($string)
    {
        return strlen(utf8_entity_decoder::decode($string));
    }

    /**
     * UTF-8 aware alternative to substr
     *
     * Return part of a string given character offset (and optionally length)
     *
     * @author Harry Fuecks <hfuecks@gmail.com>
     * @author Chris Smith <chris@jalakai.co.uk>
     * @param string
     * @param integer number of UTF-8 characters offset (from left)
     * @param integer (optional) length in UTF-8 characters from offset
     * @return mixed string or false if failure
     */
    public static function substr ($str, $offset, $length = null)
    {
        if (UTF8_MBSTRING) {
            if ($length === null) {
                return mb_substr($str, $offset);
            } else {
                return mb_substr($str, $offset, $length);
            }
        }
        
        /*
		* Notes:
		*
		* no mb string support, so we'll use pcre regex's with 'u' flag
		* pcre only supports repetitions of less than 65536, in order to accept up to MAXINT values for
		* offset and length, we'll repeat a group of 65535 characters when needed (ok, up to MAXINT-65536)
		*
		* substr documentation states false can be returned in some cases (e.g. offset > string length)
		* mb_substr never returns false, it will return an empty string instead.
		*
		* calculating the number of characters in the string is a relatively expensive operation, so
		* we only carry it out when necessary. It isn't necessary for +ve offsets and no specified length
		*/
        
        // cast parameters to appropriate types to avoid multiple notices/warnings
        $str = (string) $str; // generates E_NOTICE for PHP4 objects, but not PHP5 objects
        $offset = (int) $offset;
        if (! is_null($length))
            $length = (int) $length;
            
        // handle trivial cases
        if ($length === 0)
            return '';
        if ($offset < 0 && $length < 0 && $length < $offset)
            return '';
        
        $offset_pattern = '';
        $length_pattern = '';
        
        // normalise -ve offsets (we could use a tail anchored pattern, but they are horribly slow!)
        if ($offset < 0) {
            $strlen = strlen(utf8_entity_decoder::decode($str)); // see notes
            $offset = $strlen + $offset;
            if ($offset < 0)
                $offset = 0;
        }
        
        // establish a pattern for offset, a non-captured group equal in length to offset
        if ($offset > 0) {
            $Ox = (int) ($offset / 65535);
            $Oy = $offset % 65535;
            
            if ($Ox)
                $offset_pattern = '(?:.{65535}){' . $Ox . '}';
            $offset_pattern = '^(?:' . $offset_pattern . '.{' . $Oy . '})';
        } else {
            $offset_pattern = '^'; // offset == 0; just anchor the pattern
        }
        
        // establish a pattern for length
        if (is_null($length)) {
            $length_pattern = '(.*)$'; // the rest of the string
        } else {
            
            if (! isset($strlen))
                $strlen = strlen(utf8_entity_decoder::decode($str)); // see notes
            if ($offset > $strlen)
                return ''; // another trivial case
            

            if ($length > 0) {
                
                $length = min($strlen - $offset, $length); // reduce any length that would go passed the end of the string
                

                $Lx = (int) ($length / 65535);
                $Ly = $length % 65535;
                
                // +ve length requires ... a captured group of length characters
                if ($Lx)
                    $length_pattern = '(?:.{65535}){' . $Lx . '}';
                $length_pattern = '(' . $length_pattern . '.{' . $Ly . '})';
            
            } else 
                if ($length < 0) {
                    
                    if ($length < ($offset - $strlen))
                        return '';
                    
                    $Lx = (int) ((- $length) / 65535);
                    $Ly = (- $length) % 65535;
                    
                    // -ve length requires ... capture everything except a group of -length characters
                    //                         anchored at the tail-end of the string
                    if ($Lx)
                        $length_pattern = '(?:.{65535}){' . $Lx . '}';
                    $length_pattern = '(.*)(?:' . $length_pattern . '.{' . $Ly . '})$';
                }
        }
        
        if (! preg_match('#' . $offset_pattern . $length_pattern . '#us', $str, $match))
            return '';
        return $match[1];
    }

    /**
     * Unicode aware replacement for substr_replace()
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     * @see    substr_replace()
     */
    public static function substr_replace ($string, $replacement, $start, $length = 0)
    {
        $ret = '';
        if ($start > 0)
            $ret .= Pelican_Text_Utf8::substr($string, 0, $start);
        $ret .= $replacement;
        $ret .= Pelican_Text_Utf8::substr($string, $start + $length);
        return $ret;
    }

    /**
     * Unicode aware replacement for ltrim()
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     * @see    ltrim()
     * @return string
     */
    public static function ltrim ($str, $charlist = '')
    {
        if ($charlist == '')
            return ltrim($str);
            
        //quote charlist for use in a characterclass
        $charlist = preg_replace('!([\\\\\\-\\]\\[/])!', '\\\${1}', $charlist);
        
        return preg_replace_callback('/^[' . $charlist . ']+/u', '', $str);
    }

    /**
     * Unicode aware replacement for rtrim()
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     * @see    rtrim()
     * @return string
     */
    public static function rtrim ($str, $charlist = '')
    {
        if ($charlist == '')
            return rtrim($str);
            
        //quote charlist for use in a characterclass
        $charlist = preg_replace('!([\\\\\\-\\]\\[/])!', '\\\${1}', $charlist);
        
        return preg_replace_callback('/[' . $charlist . ']+$/u', '', $str);
    }

    /**
     * Unicode aware replacement for trim()
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     * @see    trim()
     * @return string
     */
    public static function trim ($str, $charlist = '')
    {
        if ($charlist == '')
            return trim($str);
        
        return Pelican_Text_Utf8::ltrim(Pelican_Text_Utf8::rtrim($str, $charlist), $charlist);
    }

    /**
     * This is a unicode aware replacement for strtolower()
     *
     * Uses mb_string extension if available
     *
     * @author Leo Feyer <leo@typolight.org>
     * @see    strtolower()
     * @see    Pelican_Text_Utf8::strtoupper()
     */
    public static function strtolower ($string)
    {
        if (UTF8_MBSTRING)
            return mb_strtolower($string, 'utf-8');
        
        return strtr($string, getUtf8UpperToLower());
    }

    /**
     * This is a unicode aware replacement for strtoupper()
     *
     * Uses mb_string extension if available
     *
     * @author Leo Feyer <leo@typolight.org>
     * @see    strtoupper()
     * @see    Pelican_Text_Utf8::strtoupper()
     */
    public static function strtoupper ($string)
    {
        if (UTF8_MBSTRING)
            return mb_strtoupper($string, 'utf-8');
        
        return strtr($string, getUtf8LowerToUpper());
    }

    /**
     * UTF-8 aware alternative to ucfirst
     * Make a string's first character uppercase
     *
     * @author Harry Fuecks
     * @param string
     * @return string with first character as upper case (if applicable)
     */
    public static function ucfirst ($str)
    {
        switch (Pelican_Text_Utf8::strlen($str)) {
            case 0:
                return '';
            case 1:
                return Pelican_Text_Utf8::strtoupper($str);
            default:
                preg_match('/^(.{1})(.*)$/us', $str, $matches);
                return Pelican_Text_Utf8::strtoupper($matches[1]) . $matches[2];
        }
    }

    /**
     * UTF-8 aware alternative to ucwords
     * Uppercase the first character of each word in a string
     *
     * @author Harry Fuecks
     * @param string
     * @return string with first char of each word uppercase
     * @see http://www.php.net/ucwords
     */
    public static function ucwords ($str)
    {
        // Note: [\x0c\x09\x0b\x0a\x0d\x20] matches;
        // Pelican_Form feeds, horizontal tabs, vertical tabs, linefeeds and carriage returns
        // This corresponds to the definition of a "word" defined at http://www.php.net/ucwords
        $pattern = '/(^|([\x0c\x09\x0b\x0a\x0d\x20]+))([^\x0c\x09\x0b\x0a\x0d\x20]{1})[^\x0c\x09\x0b\x0a\x0d\x20]*/u';
        
        return preg_replace_callback($pattern, 'Pelican_Text_Utf8::ucwords_callback', $str);
    }

    /**
     * Callback public static function for preg_replace_callback call in Pelican_Text_Utf8::ucwords
     * You don't need to call this yourself
     *
     * @author Harry Fuecks
     * @param array of matches corresponding to a single word
     * @return string with first char of the word in uppercase
     * @see Pelican_Text_Utf8::ucwords
     * @see Pelican_Text_Utf8::strtoupper
     */
    public static function ucwords_callback ($matches)
    {
        $leadingws = $matches[2];
        $ucfirst = Pelican_Text_Utf8::strtoupper($matches[3]);
        $ucword = Pelican_Text_Utf8::substr_replace(ltrim($matches[0]), $ucfirst, 0, 1);
        return $leadingws . $ucword;
    }

    /**
     * Replace accented UTF-8 characters by unaccented ASCII-7 equivalents
     *
     * Use the optional parameter to just deaccent lower ($case = -1) or upper ($case = 1)
     * letters. Default is to deaccent both cases ($case = 0)
     * alternative : $return = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     */
    public static function dropAccent ($string, $case = 0)
    {
    	
    	$string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
      /*
        if ($case <= 0) {
            $string = strtr($string, getUtf8LowerAccents());
        }
        if ($case >= 0) {
            $string = strtr($string, getUtf8UpperAccents());
        }
      */
        return $string;
    }

    /**
     * Romanize a non-latin string
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     */
    public static function romanize ($string)
    {
        if (Pelican_Text_Utf8::isASCII($string))
            return $string; //nothing to do
        

        $return = strtr($string, getUtf8Romanization());
        /** patch ? */
        $return = iconv('UTF-8', 'ASCII//TRANSLIT', $return);
        return $return;
    }

    /**
     * Removes special characters (nonalphanumeric) from a UTF-8 string
     *
     * This public static function adds the controlchars 0x00 to 0x19 to the array of
     * stripped chars (they are not included in $UTF8_SPECIAL_CHARS)
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     * @param  string $string     The UTF8 string to strip of special chars
     * @param  string $repl       Replace special with this string
     * @param  string $additional Additional chars to strip (used in regexp char class)
     */
    public static function stripspecials ($string, $repl = '', $additional = '')
    {
        static $specials = null;
        if (is_null($specials)) {
            #$specials = preg_quote(unicode_to_utf8(getUtf8SpecialChars()), '/');
            $specials = preg_quote(getUtf8SpecialChars(), '/');
        }
        
        return preg_replace('/[' . $additional . '\x00-\x19' . $specials . ']/u', $repl, $string);
    }

    /**
     * This is an Unicode aware replacement for strpos
     *
     * @author Leo Feyer <leo@typolight.org>
     * @see    strpos()
     * @param  string
     * @param  string
     * @param  integer
     * @return integer
     */
    public static function strpos ($haystack, $needle, $offset = 0)
    {
        $comp = 0;
        $length = null;
        
        while (is_null($length) || $length < $offset) {
            $pos = strpos($haystack, $needle, $offset + $comp);
            
            if ($pos === false)
                return false;
            
            $length = Pelican_Text_Utf8::strlen(substr($haystack, 0, $pos));
            
            if ($length < $offset)
                $comp = $pos - $length;
        }
        
        return $length;
    }

    /**
     * Encodes UTF-8 characters to Pelican_Html entities
     *
     * @author Tom N Harris <tnharris@whoopdedo.org>
     * @author <vpribish at shopping dot com>
     * @link   http://www.php.net/manual/en/function.utf8-decode.php
     */
    public static function tohtml ($str)
    {
        $ret = '';
        foreach (Pelican_Text_Utf8::to_unicode($str) as $cp) {
            if ($cp < 0x80)
                $ret .= chr($cp);
            elseif ($cp < 0x100)
                $ret .= "&#$cp;";
            else
                $ret .= '&#x' . dechex($cp) . ';';
        }
        return $ret;
    }

    /**
     * Decodes Pelican_Html entities to UTF-8 characters
     *
     * Convert any &#..; entity to a codepoint,
     * The entities flag defaults to only decoding numeric entities.
     * Pass HTML_ENTITIES and named entities, including &amp; &lt; etc.
     * are handled as well. Avoids the problem that would occur if you
     * had to decode "&amp;#38;&#38;amp;#38;"
     *
     * unhtmlspecialchars(Pelican_Text_Utf8::unhtml($s)) -> "&#38;&#38;"
     * Pelican_Text_Utf8::unhtml(unhtmlspecialchars($s)) -> "&&amp#38;"
     * what it should be                   -> "&#38;&amp#38;"
     *
     * @author Tom N Harris <tnharris@whoopdedo.org>
     * @param  string  $str      UTF-8 encoded string
     * @param  boolean $entities Flag controlling decoding of named entities.
     * @return UTF-8 encoded string with numeric (and named) entities replaced.
     */
    public static function unhtml ($str, $entities = null)
    {
        static $decoder = null;
        if (is_null($decoder))
            $decoder = new utf8_entity_decoder();
        if (is_null($entities))
            return preg_replace_callback('/(&#([Xx])?([0-9A-Za-z]+);)/m', 'Pelican_Text_Utf8::decode_numeric', $str);
        else
            return preg_replace_callback('/&(#)?([Xx])?([0-9A-Za-z]+);/m', array(&$decoder , 'decode'), $str);
    }

    public static function decode_numeric ($ent)
    {
        switch ($ent[2]) {
            case 'X':
            case 'x':
                $cp = hexdec($ent[3]);
                break;
            default:
                $cp = intval($ent[3]);
                break;
        }
        return unicode_to_utf8(array($cp));
    }

    /**
     * Takes an UTF-8 string and returns an array of ints representing the
     * Unicode characters. Astral planes are supported ie. the ints in the
     * output can be > 0xFFFF. Occurrances of the BOM are ignored. Surrogates
     * are not allowed.
     *
     * If $strict is set to true the public static function returns false if the input
     * string isn't a valid UTF-8 octet sequence and raises a PHP error at
     * level E_USER_WARNING
     *
     * Note: this public static function has been modified slightly in this library to
     * trigger errors on encountering bad bytes
     *
     * @author <hsivonen@iki.fi>
     * @author Harry Fuecks <hfuecks@gmail.com>
     * @param  string  UTF-8 encoded string
     * @param  boolean Check for invalid sequences?
     * @return mixed array of unicode code points or false if UTF-8 invalid
     * @see    unicode_to_utf8
     * @link   http://hsivonen.iki.fi/php-utf8/
     * @link   http://sourceforge.net/projects/phputf8/
     */
    public static function to_unicode ($str, $strict = false)
    {
        $mState = 0; // cached expected number of octets after the current octet
        // until the beginning of the next UTF8 character sequence
        $mUcs4 = 0; // cached Unicode character
        $mBytes = 1; // cached expected number of octets in the current sequence
        

        $out = array();
        
        $len = strlen($str);
        
        for ($i = 0; $i < $len; $i ++) {
            
            $in = ord($str{$i});
            
            if ($mState == 0) {
                
                // When mState is zero we expect either a US-ASCII character or a
                // multi-octet sequence.
                if (0 == (0x80 & ($in))) {
                    // US-ASCII, pass straight through.
                    $out[] = $in;
                    $mBytes = 1;
                
                } else 
                    if (0xC0 == (0xE0 & ($in))) {
                        // First octet of 2 octet sequence
                        $mUcs4 = ($in);
                        $mUcs4 = ($mUcs4 & 0x1F) << 6;
                        $mState = 1;
                        $mBytes = 2;
                    
                    } else 
                        if (0xE0 == (0xF0 & ($in))) {
                            // First octet of 3 octet sequence
                            $mUcs4 = ($in);
                            $mUcs4 = ($mUcs4 & 0x0F) << 12;
                            $mState = 2;
                            $mBytes = 3;
                        
                        } else 
                            if (0xF0 == (0xF8 & ($in))) {
                                // First octet of 4 octet sequence
                                $mUcs4 = ($in);
                                $mUcs4 = ($mUcs4 & 0x07) << 18;
                                $mState = 3;
                                $mBytes = 4;
                            
                            } else 
                                if (0xF8 == (0xFC & ($in))) {
                                    /* First octet of 5 octet sequence.
					*
					* This is illegal because the encoded codepoint must be either
					* (a) not the shortest Pelican_Form or
					* (b) outside the Unicode range of 0-0x10FFFF.
					* Rather than trying to resynchronize, we will carry on until the end
					* of the sequence and let the later error handling code catch it.
					*/
                                    $mUcs4 = ($in);
                                    $mUcs4 = ($mUcs4 & 0x03) << 24;
                                    $mState = 4;
                                    $mBytes = 5;
                                
                                } else 
                                    if (0xFC == (0xFE & ($in))) {
                                        // First octet of 6 octet sequence, see comments for 5 octet sequence.
                                        $mUcs4 = ($in);
                                        $mUcs4 = ($mUcs4 & 1) << 30;
                                        $mState = 5;
                                        $mBytes = 6;
                                    
                                    } elseif ($strict) {
                                        /* Current octet is neither in the US-ASCII range nor a legal first
					* octet of a multi-octet sequence.
					*/
                                        trigger_error('Pelican_Text_Utf8::to_unicode: Illegal sequence identifier ' . 'in UTF-8 at byte ' . $i, E_USER_WARNING);
                                        return false;
                                    
                                    }
            
            } else {
                
                // When mState is non-zero, we expect a continuation of the multi-octet
                // sequence
                if (0x80 == (0xC0 & ($in))) {
                    
                    // Legal continuation.
                    $shift = ($mState - 1) * 6;
                    $tmp = $in;
                    $tmp = ($tmp & 0x0000003F) << $shift;
                    $mUcs4 |= $tmp;
                    
                    /**
                     * End of the multi-octet sequence. mUcs4 now contains the final
                     * Unicode codepoint to be output
                     */
                    if (0 == -- $mState) {
                        
                        /*
						* Check for illegal sequences and codepoints.
						*/
                        // From Unicode 3.1, non-shortest Pelican_Form is illegal
                        if (((2 == $mBytes) && ($mUcs4 < 0x0080)) || ((3 == $mBytes) && ($mUcs4 < 0x0800)) || ((4 == $mBytes) && ($mUcs4 < 0x10000)) || (4 < $mBytes) || // From Unicode 3.2, surrogate characters are illegal
                        (($mUcs4 & 0xFFFFF800) == 0xD800) || // Codepoints outside the Unicode range are illegal
                        ($mUcs4 > 0x10FFFF)) {
                            
                            if ($strict) {
                                trigger_error('Pelican_Text_Utf8::to_unicode: Illegal sequence or codepoint ' . 'in UTF-8 at byte ' . $i, E_USER_WARNING);
                                
                                return false;
                            }
                        
                        }
                        
                        if (0xFEFF != $mUcs4) {
                            // BOM is legal but we don't want to output it
                            $out[] = $mUcs4;
                        }
                        
                        //initialize UTF8 cache
                        $mState = 0;
                        $mUcs4 = 0;
                        $mBytes = 1;
                    }
                
                } elseif ($strict) {
                    /**
                     *((0xC0 & (*in) != 0x80) && (mState != 0))
                     * Incomplete multi-octet sequence.
                     */
                    trigger_error('Pelican_Text_Utf8::to_unicode: Incomplete multi-octet ' . '   sequence in UTF-8 at byte ' . $i, E_USER_WARNING);
                    
                    return false;
                }
            }
        }
        return $out;
    }

    /**
     * Takes an array of ints representing the Unicode characters and returns
     * a UTF-8 string. Astral planes are supported ie. the ints in the
     * input can be > 0xFFFF. Occurrances of the BOM are ignored. Surrogates
     * are not allowed.
     *
     * If $strict is set to true the public static function returns false if the input
     * array contains ints that represent surrogates or are outside the
     * Unicode range and raises a PHP error at level E_USER_WARNING
     *
     * Note: this public static function has been modified slightly in this library to use
     * output buffering to concatenate the UTF-8 string (faster) as well as
     * reference the array by it's keys
     *
     * @param  array of unicode code points representing a string
     * @param  boolean Check for invalid sequences?
     * @return mixed UTF-8 string or false if array contains invalid code points
     * @author <hsivonen@iki.fi>
     * @author Harry Fuecks <hfuecks@gmail.com>
     * @see    Pelican_Text_Utf8::to_unicode
     * @link   http://hsivonen.iki.fi/php-utf8/
     * @link   http://sourceforge.net/projects/phputf8/
     */
    public static function unicode_to_utf8 ($arr, $strict = false)
    {
        if (! is_array($arr))
            return '';
        ob_start();
        
        foreach (array_keys($arr) as $k) {
            
            # ASCII range (including control chars)
            if (($arr[$k] >= 0) && ($arr[$k] <= 0x007f)) {
                
                echo chr($arr[$k]);
                
            # 2 byte sequence
            } else 
                if ($arr[$k] <= 0x07ff) {
                    
                    echo chr(0xc0 | ($arr[$k] >> 6));
                    echo chr(0x80 | ($arr[$k] & 0x003f));
                    
                # Byte order mark (skip)
                } else 
                    if ($arr[$k] == 0xFEFF) {    

                    // nop -- zap the BOM
                    

                    # Test for illegal surrogates
                    } else 
                        if ($arr[$k] >= 0xD800 && $arr[$k] <= 0xDFFF) {
                            
                            // found a surrogate
                            if ($strict) {
                                trigger_error('unicode_to_utf8: Illegal surrogate ' . 'at index: ' . $k . ', value: ' . $arr[$k], E_USER_WARNING);
                                return false;
                            }
                            
                        # 3 byte sequence
                        } else 
                            if ($arr[$k] <= 0xffff) {
                                
                                echo chr(0xe0 | ($arr[$k] >> 12));
                                echo chr(0x80 | (($arr[$k] >> 6) & 0x003f));
                                echo chr(0x80 | ($arr[$k] & 0x003f));
                                
                            # 4 byte sequence
                            } else 
                                if ($arr[$k] <= 0x10ffff) {
                                    
                                    echo chr(0xf0 | ($arr[$k] >> 18));
                                    echo chr(0x80 | (($arr[$k] >> 12) & 0x3f));
                                    echo chr(0x80 | (($arr[$k] >> 6) & 0x3f));
                                    echo chr(0x80 | ($arr[$k] & 0x3f));
                                
                                } elseif ($strict) {
                                    
                                    trigger_error('unicode_to_utf8: Codepoint out of Unicode range ' . 'at index: ' . $k . ', value: ' . $arr[$k], E_USER_WARNING);
                                    
                                    // out of range
                                    return false;
                                }
        }
        
        $result = ob_get_contents();
        ob_end_clean();
        return $result;
    }

    /**
     * UTF-8 to UTF-16BE conversion.
     *
     * Maybe really UCS-2 without mb_string due to Pelican_Text_Utf8::to_unicode limits
     */
    public static function to_utf16be (&$str, $bom = false)
    {
        $out = $bom ? "\xFE\xFF" : '';
        if (UTF8_MBSTRING)
            return $out . mb_convert_encoding($str, 'UTF-16BE', 'UTF-8');
        
        $uni = Pelican_Text_Utf8::to_unicode($str);
        foreach ($uni as $cp) {
            $out .= Pelican_Index_Pack('n', $cp);
        }
        return $out;
    }

    /**
     * UTF-8 to UTF-16BE conversion.
     *
     * Maybe really UCS-2 without mb_string due to Pelican_Text_Utf8::to_unicode limits
     */
    public static function utf16be_to_utf8 (&$str)
    {
        $uni = unpack('n*', $str);
        return unicode_to_utf8($uni);
    }

    /**
     * Conversion des caractères UTF8 en unicode
     *
     * @return  string
     * @param  string $source  Texte à convertir
     */
    public static function utf8_to_unicode ($source)
    {
        // array used to figure what number to decrement from character order value
        // according to number of characters used to map unicode to ascii by utf-8
        $decrement[4] = 240;
        $decrement[3] = 224;
        $decrement[2] = 192;
        $decrement[1] = 0;
        
        // the number of bits to shift each charNum by
        $shift[1][0] = 0;
        $shift[2][0] = 6;
        $shift[2][1] = 0;
        $shift[3][0] = 12;
        $shift[3][1] = 6;
        $shift[3][2] = 0;
        $shift[4][0] = 18;
        $shift[4][1] = 12;
        $shift[4][2] = 6;
        $shift[4][3] = 0;
        
        $pos = 0;
        $len = strlen($source);
        $encodedString = '';
        while ($pos < $len) {
            $asciiPos = ord(substr($source, $pos, 1));
            if (($asciiPos >= 240) && ($asciiPos <= 255)) {
                // 4 chars representing one unicode character
                $thisLetter = substr($source, $pos, 4);
                $pos += 4;
            } else 
                if (($asciiPos >= 224) && ($asciiPos <= 239)) {
                    // 3 chars representing one unicode character
                    $thisLetter = substr($source, $pos, 3);
                    $pos += 3;
                } else 
                    if (($asciiPos >= 192) && ($asciiPos <= 223)) {
                        // 2 chars representing one unicode character
                        $thisLetter = substr($source, $pos, 2);
                        $pos += 2;
                    } else {
                        // 1 char (lower ascii)
                        $thisLetter = substr($source, $pos, 1);
                        $pos += 1;
                    }
            
            $thisLen = strlen($thisLetter);
            if ($thisLen > 1) {
                // process the string representing the letter to a unicode entity
                $thisPos = 0;
                $decimalCode = 0;
                while ($thisPos < $thisLen) {
                    $thisCharOrd = ord(substr($thisLetter, $thisPos, 1));
                    if ($thisPos == 0) {
                        $charNum = intval($thisCharOrd - $decrement[$thisLen]);
                        $decimalCode += ($charNum << $shift[$thisLen][$thisPos]);
                    } else {
                        $charNum = intval($thisCharOrd - 128);
                        $decimalCode += ($charNum << $shift[$thisLen][$thisPos]);
                    }
                    
                    $thisPos ++;
                }
                
                if ($thisLen == 1)
                    $encodedLetter = "&#" . str_pad($decimalCode, 3, "0", STR_PAD_LEFT) . ';';
                else
                    $encodedLetter = "&#" . str_pad($decimalCode, 5, "0", STR_PAD_LEFT) . ';';
                
                $encodedString .= $encodedLetter;
            } else {
                $encodedString .= $thisLetter;
            }
        }
        return $encodedString;
    }

    /**
     * Replace bad bytes with an alternative character
     *
     * ASCII character is recommended for replacement char
     *
     * PCRE Pattern to locate bad bytes in a UTF-8 string
     * Comes from W3 FAQ: Multilingual Forms
     * Note: modified to include full ASCII range including control chars
     *
     * @author Harry Fuecks <hfuecks@gmail.com>
     * @see http://www.w3.org/International/questions/qa-forms-utf-8
     * @param string to search
     * @param string to replace bad bytes with (defaults to '?') - use ASCII
     * @return string
     */
    public static function bad_replace ($str, $replace = '')
    {
        $UTF8_BAD = '([\x00-\x7F]' . # ASCII (including control chars)
'|[\xC2-\xDF][\x80-\xBF]' . # non-overlong 2-byte
'|\xE0[\xA0-\xBF][\x80-\xBF]' . # excluding overlongs
'|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}' . # straight 3-byte
'|\xED[\x80-\x9F][\x80-\xBF]' . # excluding surrogates
'|\xF0[\x90-\xBF][\x80-\xBF]{2}' . # planes 1-3
'|[\xF1-\xF3][\x80-\xBF]{3}' . # planes 4-15
'|\xF4[\x80-\x8F][\x80-\xBF]{2}' . # plane 16
'|(.{1}))'; # invalid byte
        ob_start();
        while (preg_match('/' . $UTF8_BAD . '/S', $str, $matches)) {
            if (! isset($matches[2])) {
                echo $matches[0];
            } else {
                echo $replace;
            }
            $str = substr($str, strlen($matches[0]));
        }
        $result = ob_get_contents();
        ob_end_clean();
        return $result;
    }

    /**
     * adjust a byte Pelican_Index into a utf8 string to a utf8 character boundary
     *
     * @param $str   string   utf8 character string
     * @param $i     int      byte Pelican_Index into $str
     * @param $next  bool     direction to search for boundary,
     *                           false = up (current character)
     *                           true = down (next character)
     *
     * @return int            byte Pelican_Index into $str now pointing to a utf8 character boundary
     *
     * @author       chris smith <chris@jalakai.co.uk>
     */
    public static function correctIdx (&$str, $i, $next = false)
    {
        
        if ($i <= 0)
            return 0;
        
        $limit = strlen($str);
        if ($i >= $limit)
            return $limit;
        
        if ($next) {
            while (($i < $limit) && ((ord($str[$i]) & 0xC0) == 0x80))
                $i ++;
        } else {
            while ($i && ((ord($str[$i]) & 0xC0) == 0x80))
                $i --;
        }
        
        return $i;
    }

    public static function htmlentities ($str)
    {
        return htmlentities($str, ENT_NOQUOTES, "UTF-8");
    }
}

include(dirname(__FILE__) . '/utf8_chars.php');

//Setup VIM: ex: et ts=2 enc=utf-8 :


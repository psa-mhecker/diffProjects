<?php
/**
 * Response Adapter : the standard structure of the adaptative model.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */

/**
 * Response Adapter Factory : the standard structure of the adaptative model.
 *
 /**
 * Response Adapter Factory : the standard structure of the adaptative model
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 *
 * @todo http://ready.mobi/
 * @todo accesskey
 * @todo http://www.w3.org/TR/mobileOK-basic10-tests/#AUTO_REFRESH
 */
class Pelican_Response_Adapter
{
    /**
     * @static
     * @access public
     *
     * @var __TYPE__ __DESC__
     */
    public static $simulation = false;

    /**
     * @static
     * @access public
     *
     * @var __TYPE__ __DESC__
     */
    public static $cleanParams = array();

    /**
     * Options : allow to define adapter configs and browser capabilities.
     *
     * @access protected
     *
     * @var array
     */
    protected $_config = null;

    /**
     * HEAD tag.
     *
     * @access protected
     *
     * @var string
     */
    protected $_head = '';

    /**
     * BODY tag.
     *
     * @access protected
     *
     * @var string
     */
    protected $_body = '';

    /**
     * Charset : UTF8 by default.
     *
     * @access protected
     *
     * @var string
     */
    protected $_charset = 'utf-8';

    /**
     * Content type : text/html by default.
     *
     * @access protected
     *
     * @var string
     */
    protected $_contentType = 'text/html';

    /**
     * Root tag : <html> by default.
     *
     * @access protected
     *
     * @var string
     */
    protected $_root = 'html';

    /**
     * XML header.
     *
     * @access protected
     *
     * @var string
     */
    protected $_xmlHead = '';

    /**
     * Xmlns string.
     *
     * @access protected
     *
     * @var string
     */
    protected $_xmlnsString = 'http://www.w3.org/1999/xhtml';

    /**
     * DOCTYPE : xhtml transitional by default.
     *
     * @access protected
     *
     * @var string
     */
    protected $_docType = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';

    /**
     * @static
     * @access private
     *
     * @var __TYPE__ __DESC__
     */
    private static $HTML_ENTITIES_CONVERSION_TABLE = array(
        '&nbsp;' => '&#160;',
        '&iexcl;' => '&#161;',
        '&cent;' => '&#162;',
        '&pound;' => '&#163;',
        '&curren;' => '&#164;',
        '&yen;' => '&#165;',
        '&brvbar;' => '&#166;',
        '&sect;' => '&#167;',
        '&uml;' => '&#168;',
        '&copy;' => '&#169;',
        '&ordf;' => '&#170;',
        '&laquo;' => '&#171;',
        '&not;' => '&#172;',
        '&shy;' => '&#173;',
        '&reg;' => '&#174;',
        '&macr;' => '&#175;',
        '&deg;' => '&#176;',
        '&plusmn;' => '&#177;',
        '&sup2;' => '&#178;',
        '&sup3;' => '&#179;',
        '&acute;' => '&#180;',
        '&micro;' => '&#181;',
        '&para;' => '&#182;',
        '&middot;' => '&#183;',
        '&cedil;' => '&#184;',
        '&sup1;' => '&#185;',
        '&ordm;' => '&#186;',
        '&raquo;' => '&#187;',
        '&frac14;' => '&#188;',
        '&frac12;' => '&#189;',
        '&frac34;' => '&#190;',
        '&iquest;' => '&#191;',
        '&Agrave;' => '&#192;',
        '&Aacute;' => '&#193;',
        '&Acirc;' => '&#194;',
        '&Atilde;' => '&#195;',
        '&Auml;' => '&#196;',
        '&Aring;' => '&#197;',
        '&AElig;' => '&#198;',
        '&Ccedil;' => '&#199;',
        '&Egrave;' => '&#200;',
        '&Eacute;' => '&#201;',
        '&Ecirc;' => '&#202;',
        '&Euml;' => '&#203;',
        '&Igrave;' => '&#204;',
        '&Iacute;' => '&#205;',
        '&Icirc;' => '&#206;',
        '&Iuml;' => '&#207;',
        '&ETH;' => '&#208;',
        '&Ntilde;' => '&#209;',
        '&Ograve;' => '&#210;',
        '&Oacute;' => '&#211;',
        '&Ocirc;' => '&#212;',
        '&Otilde;' => '&#213;',
        '&Ouml;' => '&#214;',
        '&times;' => '&#215;',
        '&Oslash;' => '&#216;',
        '&Ugrave;' => '&#217;',
        '&Uacute;' => '&#218;',
        '&Ucirc;' => '&#219;',
        '&Uuml;' => '&#220;',
        '&Yacute;' => '&#221;',
        '&THORN;' => '&#222;',
        '&szlig;' => '&#223;',
        '&agrave;' => '&#224;',
        '&aacute;' => '&#225;',
        '&acirc;' => '&#226;',
        '&atilde;' => '&#227;',
        '&auml;' => '&#228;',
        '&aring;' => '&#229;',
        '&aelig;' => '&#230;',
        '&ccedil;' => '&#231;',
        '&egrave;' => '&#232;',
        '&eacute;' => '&#233;',
        '&ecirc;' => '&#234;',
        '&euml;' => '&#235;',
        '&igrave;' => '&#236;',
        '&iacute;' => '&#237;',
        '&icirc;' => '&#238;',
        '&iuml;' => '&#239;',
        '&eth;' => '&#240;',
        '&ntilde;' => '&#241;',
        '&ograve;' => '&#242;',
        '&oacute;' => '&#243;',
        '&ocirc;' => '&#244;',
        '&otilde;' => '&#245;',
        '&ouml;' => '&#246;',
        '&divide;' => '&#247;',
        '&oslash;' => '&#248;',
        '&ugrave;' => '&#249;',
        '&uacute;' => '&#250;',
        '&ucirc;' => '&#251;',
        '&uuml;' => '&#252;',
        '&yacute;' => '&#253;',
        '&thorn;' => '&#254;',
        '&yuml;' => '&#255;',
        '&OElig;' => '&#338;',
        '&oelig;' => '&#339;',
        '&Scaron;' => '&#352;',
        '&scaron;' => '&#353;',
        '&Yuml;' => '&#376;',
        '&circ;' => '&#710;',
        '&tilde;' => '&#732;',
        '&ensp;' => '&#8194;',
        '&emsp;' => '&#8195;',
        '&thinsp;' => '&#8201;',
        '&zwnj;' => '&#8204;',
        '&zwj;' => '&#8205;',
        '&lrm;' => '&#8206;',
        '&rlm;' => '&#8207;',
        '&ndash;' => '&#8211;',
        '&mdash;' => '&#8212;',
        '&lsquo;' => '&#8216;',
        '&rsquo;' => '&#8217;',
        '&sbquo;' => '&#8218;',
        '&ldquo;' => '&#8220;',
        '&rdquo;' => '&#8221;',
        '&bdquo;' => '&#8222;',
        '&dagger;' => '&#8224;',
        '&Dagger;' => '&#8225;',
        '&permil;' => '&#8240;',
        '&lsaquo;' => '&#8249;',
        '&rsaquo;' => '&#8250;',
        '&euro;' => '&#8364;',
        '&Alpha;' => '&#913;',
        '&Beta;' => '&#914;',
        '&Gamma;' => '&#915;',
        '&Delta;' => '&#916;',
        '&Epsilon;' => '&#917;',
        '&Zeta;' => '&#918;',
        '&Eta;' => '&#919;',
        '&Theta;' => '&#920;',
        '&Iota;' => '&#921;',
        '&Kappa;' => '&#922;',
        '&Lambda;' => '&#923;',
        '&Mu;' => '&#924;',
        '&Nu;' => '&#925;',
        '&Xi;' => '&#926;',
        '&Omicron;' => '&#927;',
        '&Pi;' => '&#928;',
        '&Rho;' => '&#929;',
        '&Sigma;' => '&#931;',
        '&Tau;' => '&#932;',
        '&Upsilon;' => '&#933;',
        '&Phi;' => '&#934;',
        '&Chi;' => '&#935;',
        '&Psi;' => '&#936;',
        '&Omega;' => '&#937;',
        '&alpha;' => '&#945;',
        '&beta;' => '&#946;',
        '&gamma;' => '&#947;',
        '&delta;' => '&#948;',
        '&epsilon;' => '&#949;',
        '&zeta;' => '&#950;',
        '&eta;' => '&#951;',
        '&theta;' => '&#952;',
        '&iota;' => '&#953;',
        '&kappa;' => '&#954;',
        '&lambda;' => '&#955;',
        '&mu;' => '&#956;',
        '&nu;' => '&#957;',
        '&xi;' => '&#958;',
        '&omicron;' => '&#959;',
        '&pi;' => '&#960;',
        '&rho;' => '&#961;',
        '&sigmaf;' => '&#962;',
        '&sigma;' => '&#963;',
        '&tau;' => '&#964;',
        '&upsilon;' => '&#965;',
        '&phi;' => '&#966;',
        '&chi;' => '&#967;',
        '&psi;' => '&#968;',
        '&omega;' => '&#969;',
        '&thetasym;' => '&#977;',
        '&upsih;' => '&#978;',
        '&piv;' => '&#982;',
        '&bull;' => '&#8226;',
        '&hellip;' => '&#8230;',
        '&prime;' => '&#8242;',
        '&Prime;' => '&#8243;',
        '&oline;' => '&#8254;',
        '&frasl;' => '&#8260;',
        '&weierp;' => '&#8472;',
        '&image;' => '&#8465;',
        '&real;' => '&#8476;',
        '&trade;' => '&#8482;',
        '&alefsym;' => '&#8501;',
        '&larr;' => '&#8592;',
        '&uarr;' => '&#8593;',
        '&darr;' => '&#8595;',
        '&harr;' => '&#8596;',
        '&crarr;' => '&#8629;',
        '&lArr;' => '&#8656;',
        '&uArr;' => '&#8657;',
        '&rArr;' => '&#8658;',
        '&dArr;' => '&#8659;',
        '&hArr;' => '&#8660;',
        '&forall;' => '&#8704;',
        '&part;' => '&#8706;',
        '&exist;' => '&#8707;',
        '&empty;' => '&#8709;',
        '&nabla;' => '&#8711;',
        '&isin;' => '&#8712;',
        '&notin;' => '&#8713;',
        '&ni;' => '&#8715;',
        '&prod;' => '&#8719;',
        '&sum;' => '&#8721;',
        '&minus;' => '&#8722;',
        '&lowast;' => '&#8727;',
        '&radic;' => '&#8730;',
        '&prop;' => '&#8733;',
        '&infin;' => '&#8734;',
        '&ang;' => '&#8736;',
        '&and;' => '&#8743;',
        '&or;' => '&#8744;',
        '&cap;' => '&#8745;',
        '&cup;' => '&#8746;',
        '&int;' => '&#8747;',
        '&there4;' => '&#8756;',
        '&sim;' => '&#8764;',
        '&cong;' => '&#8773;',
        '&asymp;' => '&#8776;',
        '&ne;' => '&#8800;',
        '&equiv;' => '&#8801;',
        '&le;' => '&#8804;',
        '&ge;' => '&#8805;',
        '&sub;' => '&#8834;',
        '&sup;' => '&#8835;',
        '&nsub;' => '&#8836;',
        '&sube;' => '&#8838;',
        '&supe;' => '&#8839;',
        '&oplus;' => '&#8853;',
        '&otimes;' => '&#8855;',
        '&perp;' => '&#8869;',
        '&sdot;' => '&#8901;',
        '&lceil;' => '&#8968;',
        '&rceil;' => '&#8969;',
        '&lfloor;' => '&#8970;',
        '&rfloor;' => '&#8971;',
        '&lang;' => '&#9001;',
        '&rang;' => '&#9002;',
        '&loz;' => '&#9674;',
        '&spades;' => '&#9824;',
        '&clubs;' => '&#9827;',
        '&hearts;' => '&#9829;',
        '&diams;' => '&#9830;',
    );

    /**
     * Factory to instantiate the right adapter defined by markup param.
     *
     * @access public
     * @staticvar $instance
     *
     * @param string $markup Markup defines the adapter to use
     * @param string $config (option) Config parameters and Capabilities
     *
     * @return Pelican_Response_Adapter_Factory
     */
    public function &getInstance($markup, $config = '')
    {
        static $instance;
        Pelican::$config["SHOW_DEBUG"] = false;
        if (!is_object($instance)) {
            $class = __CLASS__.'_'.ucFirst($markup);
            @include_once pelican_path(str_replace('Pelican_', '', $class));
            if (class_exists($class)) {
                $instance = new $class();
                $instance->setConfig($config);
            }
        }

        return $instance;
    }

    /**
     * Original content.
     *
     * @access public
     *
     * @param string $text Original content
     */
    public function setText($text)
    {
        $this->_text = $text;
    }

    /**
     * Returns the current Markup.
     *
     * @access public
     *
     * @return string
     */
    public function getMarkup()
    {
        return '';
    }

    /**
     * Returns the current Charset.
     *
     * @access public
     *
     * @return string
     */
    public function getCharset()
    {
        return $this->_charset;
    }

    /**
     * Returns the current Content-Type.
     *
     * @access public
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->_contentType;
    }

    /**
     * Returns the current Content string with charset.
     *
     * @access public
     *
     * @return string
     */
    public function getContentString()
    {
        $return = '';
        if ($this->getContentType()) {
            $return .= $this->getContentType();
        }
        if ($this->getCharset()) {
            $return .= '; charset='.$this->getCharset();
        }

        return $return;
    }

    /**
     * Returns the Content-Type header.
     *
     * @access public
     *
     * @return string
     */
    public function setHttpHeader()
    {
        if (self::$simulation) { // && strpos($this->_contentType, 'wap') !== false) {
            $this->_contentType = 'text/html';
            header("Pragma: no-cache", true); // leave blank to avoid IE errors
        } else {
            if ($this->getContentString()) {
                header("Cache-Control: no-transform", true); // leave blank to avoid IE errors
                header("Pragma: ", true); // leave blank to avoid IE errors
                header("Cache-Control: max-age=315360000", true);
                header("Expires: Fri, 01 May 2020 03:47:24 GMT", true);
                header('Content-type: '.$this->getContentString(), true);
            }
        }
    }

    /**
     * Returns the Head Content.
     *
     * @access public
     *
     * @return string
     */
    public function getHead()
    {
        return $this->_head;
    }

    /**
     * Add a string to the Head content.
     *
     * @access public
     *
     * @param string $item Content to add
     *
     * @return string
     */
    public function addHead($item)
    {
        return $this->_head .= $item;
    }

    /**
     * Returns the current Body content.
     *
     * @access public
     *
     * @return string
     */
    public function getBody()
    {
        return $this->_body;
    }

    /**
     * Returns the current Xml head.
     *
     * @access public
     *
     * @return string
     */
    public function getXmlHead()
    {
        echo $this->_xmlHead;
    }

    /**
     * Returns the current DocType.
     *
     * @access public
     *
     * @return string
     */
    public function getDocType()
    {
        return $this->_docType;
    }

    /**
     * Returns the current Xmlns string.
     *
     * @access public
     *
     * @return string
     */
    public function getXmlnsString()
    {
        return $this->_xmlnsString;
    }

    /**
     * Returns the open or close Root Tag.
     *
     * @access public
     *
     * @param bool $start (option) Open tag by default
     *
     * @return string
     */
    public function getRootTag($start = true)
    {
        if ($start) {
            if ($this->getXmlnsString()) {
                $return = '<'.$this->getRoot().' xmlns="'.$this->getXmlnsString().'">';
            } else {
                $return = '<'.$this->getRoot().'>';
            }
        } else {
            $return = '</'.$this->getRoot().'>';
        }

        return $return;
    }

    /**
     * Apply the adaptative transformation to the content.
     *
     * @access public
     *
     * @param string $text Content
     *
     * @return bool
     */
    public function process($text)
    {
        if (!$this->detectUTF8($text)) {
            $text = utf8_encode($text);
        }
        $this->setText($text);
        $this->extractHtmlPart($text);

        return true;
    }

    /**
     * Extract the Head and Body contents from the content.
     *
     * @access public
     *
     * @param string $text Content
     */
    public function extractHtmlPart($text)
    {
        $temp = array();
        $pattern = '#<head[^>]*>(.*?)<\/head>.*?<body[^>]*>(.*)<\/body>#si';
        preg_match($pattern, $text, $temp);
        if (count($temp) == 3) {
            $this->_setResponse($temp[1], $temp[2]);
        }

        /*$html = str_get_html($text);
        $head = $html->find('head', 0)->innertext;
        $body = $html->find('body', 0)->innertext;
        $this->_setResponse($head, $body);*/
    }

    /**
     * Set the Head and Body contents.
     *
     * @access protected
     *
     * @param string $head Head content
     * @param string $body Body content
     */
    protected function _setResponse($head, $body)
    {
        $this->setHead($head);
        $this->setBody($body);
    }

    /**
     * Returns the HTTP response after adaptation.
     *
     * @access public
     *
     * @return string
     */
    public function getOutput()
    {
        $this->setHttpHeader();
        $tmp[] = $this->getXmlHead();
        $tmp[] = $this->getDocType();
        $tmp[] = $this->getRootTag(true);
        $tmp[] = Pelican_Html::head($this->getHead());
        $tmp[] = Pelican_Html::body($this->getBody());
        $tmp[] = $this->getRootTag(false);
        $return = implode("\n", $tmp);
        $return = self::reduce($return);

        return $return;
    }

    /**
     * Returns the value of a config parameter or the entire config array.
     *
     * @access public
     *
     * @param string $item (option) Config entry
     *
     * @return mixed
     */
    public function getConfig($item = null)
    {
        if ($item != null) {
            $return = $this->_config[$item];
        } else {
            $return = $this->_config;
        }

        return $return;
    }

    /**
     * Set the config array.
     *
     * @access public
     *
     * @param array $config Config parameters
     */
    public function setConfig($config)
    {
        $this->_config = $config;
    }

    /**
     * Set a config parameter.
     *
     * @access public
     *
     * @param string $item  Parameter ID
     * @param string $value (option) Parameter value
     */
    public function setConfigValue($item, $value = null)
    {
        $this->_config[$item] = $value;
    }

    /**
     * Set the Head content.
     *
     * @access public
     *
     * @param __TYPE__ $head __DESC__
     */
    public function setHead($head)
    {
        $this->_head = $head;
    }

    /**
     * Set the Body content.
     *
     * @access public
     *
     * @param string $body Body content
     */
    public function setBody($body)
    {
        $this->_body = $body;
    }

    /**
     * Sets the charset value.
     *
     * @access public
     *
     * @param string $charset Charset value
     */
    public function setCharset($charset)
    {
        $this->_charset = $charset;
    }

    /**
     * Sets the ContentType value.
     *
     * @access public
     *
     * @param string $contentType ContentType value
     */
    public function setContentType($contentType)
    {
        $this->_contentType = $contentType;
    }

    /**
     * Sets the XmlHead value.
     *
     * @access public
     *
     * @param string $xmlHeader XmlHead value
     */
    public function setXmlHead($xmlHeader)
    {
        $this->_xmlHead = $xmlHeader;
    }

    /**
     * Sets the XmlnsString value.
     *
     * @access public
     *
     * @param string $xmlnsString XmlnsString value
     */
    public function setXmlnsString($xmlnsString)
    {
        $this->_xmlnsString = $xmlnsString;
    }

    /**
     * Sets the DocType value.
     *
     * @access public
     *
     * @param string $docType DocType value
     */
    public function setDocType($docType)
    {
        $this->_docType = $docType;
    }

    /**
     * Returns the Root tag.
     *
     * @access public
     *
     * @return string
     */
    public function getRoot()
    {
        return $this->_root;
    }

    /**
     * Sets the Root Tag value.
     *
     * @access public
     *
     * @param string $root Root Tag value
     */
    public function setRoot($root)
    {
        $this->_root = $root;
    }

    /**
     * __DESC__.
     *
     * @access protected
     *
     * @param string $text __DESC__
     *
     * @return string
     */
    protected function _processCleanMeta($text)
    {
        $return = preg_replace('#<meta http-equiv[^>]+?>#is', '', $text);

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access protected
     *
     * @param string $text __DESC__
     *
     * @return string
     */
    protected function _processBr($text)
    {
        $return = str_replace('<br>', '<br />', $text); // xml-compatibility

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access protected
     *
     * @param string $text __DESC__
     *
     * @return string
     */
    protected function _processLink($text)
    {
        $return = self::processLink($text);

        return $return;
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param __TYPE__ $text __DESC__
     *
     * @return __TYPE__
     */
    public static function processLink($text)
    {
        $return = preg_replace("#<a.+?href=\"([^\"]*?)\"[^>]*?>([^<]*?)<[^>]*?>#si", "\\2", $text);

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access protected
     *
     * @param string $text __DESC__
     *
     * @return string
     */
    protected function _processRemoveTargetBlank($text)
    {
        $return = preg_replace('#(<a [^>]*?)target="_blank"([^>]*?>)#is', '\1\2', $text);

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access private
     *
     * @param string $text __DESC__
     * @param string $tag  __DESC__
     *
     * @return string
     */
    public function _processRemoveTag($text, $tag)
    {
        /* preg_match_all('#<' . $tag . '\s[^>]+?/>#is',  $text, $match);
        var_dump($match);
        preg_match_all('#<' . $tag . '.+?</' . $tag . '>#is',  $return, $match);
        preg_match_all('#<' . $tag . '\s[^>]+?>#is',  $return, $match);
        */
        $return = preg_replace('#<'.$tag.'\s[^>]+?/>#is', '', $text);
        $return = preg_replace('#<'.$tag.'.+?</'.$tag.'>#is', '', $return);
        $return = preg_replace('#<'.$tag.'\s[^>]+?>#is', '', $return);

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access protected
     *
     * @param string $text __DESC__
     *
     * @return string
     */
    protected function _processResizeImage($text)
    {
        $return = $text;
        // image_format
        $format = "/gif";
        /* if (self::isTrue($this->getConfig('gif'))) {
        $extension = '/gif';
        } else*/
        if (self::isTrue($this->getConfig('jpg'))) {
            $format = '/jpg';
        } elseif (self::isTrue($this->getConfig('png'))) {
            $format = '/png';
        } elseif (self::isTrue($this->getConfig('tiff'))) {
            $format = '/tif';
        } elseif (self::isTrue($this->getConfig('bmp'))) {
            $format = '/bmp';
        }
        if ($this->getConfig('max_image_width') && $this->getConfig('max_image_height')) {
            $return = preg_replace('#<img(.*?)height="([^">]*)?"([^>]*?)>#', '<img$1$3>', $return);
            $return = preg_replace('#<img(.*?)width="([^">]*)?"([^>]*?)>#', '<img$1$3>', $return);
            if ($this->getConfig('image_host')) {
                $regex = '#<img(.*?)src="([^">]*/)?(([^"/]*)\.[^"]*)"([^>]*?)>#';
                self::$cleanParams = array('host' => $this->getConfig('image_host'), 'width' => $this->getConfig('max_image_width'), 'height' => $this->getConfig('max_image_height'), 'format' => $format);
                //create_function('$matches', 'return ' . '<img' . $matches[1] . 'src="' . $this->getConfig('image_host') . $format . (abs($this->getConfig('max_image_width')) ? '/' . abs($this->getConfig('max_image_width')) : '') . (abs($this->getConfig('max_image_height')) ? '/' . abs($this->getConfig('max_image_height')) : '') . '/' . str_replace($this->getConfig('image_host'), '', $matches[2]) . $matches[2] . '"' . $matches[5] . '>;')
                $return = preg_replace_callback($regex, __CLASS__."::cleanMediaUri", $return);
            }
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @static
     * @access private
     *
     * @param __TYPE__ $matches __DESC__
     *
     * @return __TYPE__
     */
    private static function cleanMediaUri($matches)
    {
        //return self::$cleanParams['host'] . $matches[2];
        return '<img'.$matches[1].'src="'.self::$cleanParams['host'].self::$cleanParams['format'].(abs(self::$cleanParams['width']) ? '/'.abs(self::$cleanParams['height']) : '').(abs(self::$cleanParams['height']) ? '/'.abs(self::$cleanParams['height']) : '').'/'.str_replace(self::$cleanParams['host'].'/', '', $matches[2]).$matches[3].'"'.$matches[5].'>';
    }

    /**
     * __DESC__.
     *
     * @access protected
     *
     * @param __TYPE__ $content __DESC__
     *
     * @return __TYPE__
     */
    protected function _imageTmp($content)
    {
        // Parse the list of images in the page, and compute the adapted content
        $adaptedContent = '';
        $offset = 0;
        preg_match_all("/\<img(\s[^>]*)?src=((?:\'[^\']*\')|(?:\"[^\"]*\"))[^>]*\>/Usi", $content, $images, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
        foreach ($images as $image) {
            $src = trim($image[2][0], '\'\"');
            if ($convertedImage) {
                // Update the source of the adapted image, if necessary.
                // Converted image is an array:
                //  0: the source of the adapted image
                //  1: the width of the adapted image
                //  2: the height of the adapted image
                // Make sure the width and height attributes are correctly set
                // (and remove invalid border attribute if needed)
                $imgMarkup = preg_replace('/\s(src|width|height|border)=((?:\'[^\']*\')|(?:\"[^\"]*\"))/Usi', '', $image[0][0]);
                $imgMarkup = preg_replace('|/?>$|Usi', '', $imgMarkup);
                $imgMarkup = '<img src="'.$convertedImage[0].'" width="'.$convertedImage[1].'" height="'.$convertedImage[2].'"'.substr($imgMarkup, strlen('<img')).'/>';
            } else {
                // Image conversion could not be done, replace the image by its
                // alt attribute when defined.
                preg_match('/ alt=((?:\'[^\']*\')|(?:\"[^\"]*\"))/Usi', $image[0][0], $alt);
                if ($alt) {
                    $imgMarkup = '<span>'.trim($alt[1], '\'\"').'</span>';
                } else {
                    $imgMarkup = '';
                }
            }
            // Complete adapted content and move offset in $content to right
            // after the image that has just been processed.
            $adaptedContent .= substr($content, $offset, $image[0][1] - $offset).$imgMarkup;
            $offset = $image[0][1] + strlen($image[0][0]);
        }
    }

    /**
     * __DESC__.
     *
     * @access protected
     *
     * @param string $text __DESC__
     *
     * @return string
     */
    protected function _processColumns($text)
    {
        $return = $text;
        if ($this->getConfig('columns')) {
            $return = preg_replace('#<textarea(.*?)cols="([^"]*)?"([^>]*?)>#', '<textarea$1cols="'.$this->getConfig('columns').'"$3>', $return);
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access protected
     *
     * @param string $text __DESC__
     *
     * @return string
     */
    protected function _processIframe($text)
    {
        $return = $text;
        //xhtml_supports_iframe : none,partial,full
        if (!self::isTrue($this->getConfig('xhtml_supports_iframe'))) {
            $return = $this->_processRemoveTag($return, 'iframe');
        } elseif ($this->getConfig('resolution_width')) {
            $return = preg_replace('#<iframe(.*?)width="([^"]*)?"([^>]*?)>#', '<iframe$1width="'.$this->getConfig('resolution_width').'"$3>', $return);
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access protected
     *
     * @param string $text __DESC__
     *
     * @return string
     */
    protected function _processFlash($text)
    {
        $return = $text;
        //! self::isTrue($this->getConfig('full_flash_support']) ||
        if (!self::isTrue($this->getConfig('fl_browser'))) {
            $return = $this->_processRemoveTag($return, 'object');
            $return = $this->_processRemoveTag($return, 'embed');
            //TODO suppression de swfobject.js + appels swfobjects
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access protected
     *
     * @param string $text __DESC__
     *
     * @return string
     */
    protected function _processEntityDecode($text)
    {
        $return = $text;
        $return = strtr($return, array('&lt;' => '&amp;lt;', '&gt;' => '&amp;gt;', '&amp;' => '&amp;amp;'));
        $return = html_entity_decode($return, ENT_NOQUOTES, 'UTF-8');

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access protected
     *
     * @param string $text __DESC__
     *
     * @return string
     */
    protected function _processTableToText($text)
    {
        // table
        $return = $text;
        $return = preg_replace('#<(td|tr|th)(.*?)>#is', '', $return);
        $return = preg_replace('#</(tr|dd)>#i', '<br/>', $return);
        $return = str_ireplace('</td>', ' | ', $return);
        $return = str_ireplace('</th>', ' | ', $return);
        $return = preg_replace('#<(table|tbody|thead|tfoot)([^>]*?)>#is', '', $return);
        $return = preg_replace('#</(table|tbody|thead|tfoot)>#is', '', $return);
        $return = str_ireplace('<>', '', $return);
        // | <br/> |
        return $return;
    }

    /**
     * __DESC__.
     *
     * @access protected
     *
     * @param __TYPE__ $text __DESC__
     *
     * @return __TYPE__
     */
    protected function _processTableToBasic($text)
    {
        $return = preg_replace('/\<(thead|tbody|tfoot).*>(.*)<\/(thead|tbody|tfoot)>/Usi', '\2', $text);
        $return = preg_replace('/\<colgroup.*>(.*)<\/colgroup.*>/Usi', '', $return);

        return $return;
    }

    /**
     * Translates Pelican_Html entities when the device supports the
     * application/xhtml+xml Pelican_Media type.
     *
     * @access private
     * @exception Array
     *
     * @param __TYPE__ $text __DESC__
     *
     * @return string
     */
    public function _processTranscoding($text)
    {
        $accept = $_SERVER['HTTP_ACCEPT'];
        if ($accept != '') {
            if (strpos($accept, 'application/xhtml+xml') !== false) {
                // TODO: handle the case where the XHTML Pelican_Media type is
                // explicitly set with a qvalue of 0
                $return = strtr($text, self::$HTML_ENTITIES_CONVERSION_TABLE);

                return $return;
            }
        } else {
            return $text;
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
     * @return string
     */
    public static function reduce($text)
    {
        $return = $text;
        $return = str_replace('<!--[if IE 5]><![endif]-->', '', $return);
        $return = str_replace('<!--[if IE 6]><![endif]-->', '', $return);
        $return = str_replace('<!--[if IE 7]><![endif]-->', '', $return);
        $return = str_replace("\t", " ", $return);
        $return = preg_replace('/(\s)\/\/(.*)(\s)/', '\\1/* \\2 */\\3', $return);
        $search = array('/(\s)+/s');
        $replace = array('\\1');
        $return = preg_replace($search, $replace, $return);
        // reducing spaces
        $return = preg_replace('~ +~s', ' ', $return);
        $return = preg_replace('~^\s+~m', '', $return);
        $return = preg_replace('~\s+$~m', '', $return);
        // reducing newlines
        $return = preg_replace('~\n+~s', "\n", $return);
        $return = preg_replace('#<!\-\- [\/\ a-zA-Z]* \-\->#', '', $return);

        return $return;
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param string $option __DESC__
     *
     * @return bool
     */
    public static function isTrue($option)
    {
        if ($option === 'true' || $option === true || $option === 1 || $option === 'supported' || $option === 'full' || $option === 'partial') {
            $return = true;
        } else {
            $return = false;
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param string $string __DESC__
     *
     * @return string
     */
    public function detectUTF8($string)
    {
        $return = (bool) preg_match('/(?:
        [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
        |\xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
        |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
        |\xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
        |\xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
        |[\xF1-\xF3][\x80-\xBF]{3}         # planes 4-15
        |\xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
        )+/xs', $string);

        return $return;
    }
}

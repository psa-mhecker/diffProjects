<?php
/**
 * Zend Framework.
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
require_once 'Pelican/Http/UserAgent/AbstractUserAgent.php';

/**
 * Mobile browser type matcher.
 *
 * @category Zend
 *
 * @copyright Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */
class Pelican_Http_UserAgent_Mobile extends Pelican_Http_UserAgent_AbstractUserAgent
{
    const DEFAULT_FEATURES_ADAPTER_CLASSNAME = 'Pelican_Http_UserAgent_Features_Adapter_WurflApi';

    const DEFAULT_FEATURES_ADAPTER_PATH = 'Pelican/Http/UserAgent/Features/Adapter/WurflApi.php';

    /**
     * User Agent Signatures.
     *
     * @static
     * @access public
     *
     * @var array
     */
    public static $uaSignatures = array(
        'iphone',
        'ipod',
        'ipad',
        'android',
        'blackberry',
        'opera mini',
        'opera mobi',
        'palm',
        'palmos',
        'elaine',
        'windows ce',
        'icab',
        ' ppc',
        '_mms',
        'ahong',
        'archos',
        'armv',
        'astel',
        'avantgo',
        'benq',
        'blazer',
        'brew',
        'com2',
        'compal',
        'danger',
        'pocket',
        'docomo',
        'epoc',
        'ericsson',
        'eudoraweb',
        'hiptop',
        'htc-',
        'htc_',
        'htc ',
        'iemobile',
        'iris',
        'j-phone',
        'kddi',
        'kindle',
        'lg ',
        'lg-',
        'lg/',
        'lg;lx',
        'lge vx',
        'lge',
        'lge-',
        'lge-cx',
        'lge-lx',
        'lge-mx',
        'linux armv',
        'maemo',
        'midp',
        'mini 9.5',
        'minimo',
        'mob-x',
        'mobi',
        'mobile',
        'mobilephone',
        'mot 24',
        'mot-',
        'motorola',
        'n410',
        'netfront',
        'nintendo wii',
        'nintendo',
        'nitro',
        'nokia',
        'novarra-vision',
        'nuvifone',
        'openweb',
        'opwv',
        'palmsource',
        'pdxgw',
        'phone',
        'playstation',
        'polaris',
        'portalmmm',
        'qt embedded',
        'reqwirelessweb',
        'sagem',
        'sam-r',
        'samsu',
        'samsung',
        'sec-',
        'sec-sgh',
        'semc-browser',
        'series60',
        'series70',
        'series80',
        'series90',
        'sharp',
        'sie-m',
        'sie-s',
        'smartphone',
        'sony cmd',
        'sonyericsson',
        'sprint',
        'spv',
        'symbian os',
        'symbian',
        'symbianos',
        'telco',
        'teleca',
        'treo',
        'up.browser',
        'up.link',
        'vodafone',
        'vodaphone',
        'webos',
        'webpro',
        'windows phone',
        'wireless',
        'wm5 pie',
        'wms pie',
        'xiino',
        'mowser',
        'wap',
        'utstar',
        'utec',
        'imode',
        'psion',
        'jbrowser',
        'm3gate',
        'dbtel',
        'ikomo',
        'etouch',
        'wap-browser',
        'au.browser',
        'foma;',
        'becker',
        'nf-browser',
        'o2-',
        'up/',
        'j2me',
        'klondike',
        'kbrowser',
    );

    /**
     * @static
     * @access public
     *
     * @var __TYPE__ __DESC__
     */
    public static $haTerms = array(
        'midp',
        'wml',
        'vnd.rim',
        'vnd.wap',
        'j2me',
    );

    /**
     * first 4 letters of mobile User Agent chains.
     *
     * @var unknown_type
     */
    public static $uaBegin = array(
        'w3c ',
        'acs-',
        'alav',
        'alca',
        'amoi',
        'audi',
        'avan',
        'benq',
        'bird',
        'blac',
        'blaz',
        'brew',
        'cell',
        'cldc',
        'cmd-',
        'dang',
        'doco',
        'eric',
        'hipt',
        'inno',
        'ipaq',
        'java',
        'jigs',
        'kddi',
        'keji',
        'leno',
        'lg-c',
        'lg-d',
        'lg-g',
        'lge-',
        'maui',
        'maxo',
        'midp',
        'mits',
        'mmef',
        'mobi',
        'mot-',
        'moto',
        'mwbp',
        'nec-',
        'newt',
        'noki',
        'palm',
        'pana',
        'pant',
        'phil',
        'play',
        'port',
        'prox',
        'qwap',
        'qtek',
        'sage',
        'sams',
        'sany',
        'sch-',
        'sec-',
        'send',
        'seri',
        'sgh-',
        'shar',
        'sie-',
        'siem',
        'smal',
        'smar',
        'sony',
        'sph-',
        'symb',
        't-mo',
        'teli',
        'tim-',
        'tosh',
        'tsm-',
        'upg1',
        'upsi',
        'vk-v',
        'voda',
        'wap-',
        'wapa',
        'wapi',
        'wapp',
        'wapr',
        'webc',
        'winw',
        'winw',
        'xda',
        'xda-',
        'hs-c',
        'ac83',
        'asus',
        'acer',
        'aiko',
        'airn',
        'sama',
        'samr',
        'samu',
        'pt-g',
        'soft',
        'spic',
        'zte-',
        'nok6',
        'hedy',
        'grad',
        'gpfm',
        'fly_',
        'fly ',
        'fly-',
        'haie',
        'huaw',
        'hutc',
        'lct_',
        'rim8',
        'rim9',
        'xda_',
        'au-m',
        'anny',
        'kwc-',
        'kgt/',
        'rove',
        'htil',
        'do_n',
        'cdm-',
    );

    /**
     * Comparison of the UserAgent chain and User Agent signatures.
     *
     * @static
     * @access public
     *
     * @param string $userAgent User Agent chain
     *
     * @return bool
     */
    public static function match($userAgent)
    {

        /*  to have a quick identification, make lighten tests first */
        if (isset($_SERVER['ALL_HTTP'])) {
            if (strpos(strtolower(str_replace(' ', '', $_SERVER['ALL_HTTP'])), 'operam') !== false) {
                /* Opera Mini or Opera Mobi */
                return true;
            }
        }
        if (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])) {
            return true;
        }
        if (Pelican_Http_UserAgent::match($userAgent, self::$haTerms)) {
            return true;
        }
        if (self::userAgentStart($userAgent)) {
            return true;
        }
        if (Pelican_Http_UserAgent::match($userAgent, self::$uaSignatures)) {
            return true;
        }

        return false;
    }

    /**
     * @return unknown_type
     */
    public static function userAgentStart($userAgent)
    {
        $mobile_ua = strtolower(substr($userAgent, 0, 4));

        return (in_array($mobile_ua, self::$uaBegin));
    }

    /**
     * Constructor.
     *
     * @access public
     */
    public function __construct()
    {

        /* for mobile detection, an adapter must be defined */
        if (empty(Pelican_Http_UserAgent::$config['mobile']['features'])) {
            Pelican_Http_UserAgent::$config['mobile']['features']['path'] = self::DEFAULT_FEATURES_ADAPTER_PATH;
            Pelican_Http_UserAgent::$config['mobile']['features']['classname'] = self::DEFAULT_FEATURES_ADAPTER_CLASSNAME;
        }
        parent::__construct();
    }

    /**
     * Gives the current browser type.
     *
     * @access public
     *
     * @return string
     */
    public function getType()
    {
        return 'mobile';
    }

    /**
     * Look for features.
     *
     * @access public
     *
     * @return string
     */
    public function defineFeatures()
    {
        $this->setFeature('is_wireless_device', false, 'product_info');

        parent::defineFeatures();

        if (! empty($this->_aFeatures["mobile_browser"])) {
            $this->setFeature("browser_name", $this->_aFeatures["mobile_browser"]);
        }
        if (! empty($this->_aFeatures["mobile_browser_version"])) {
            $this->setFeature("browser_version", $this->_aFeatures["mobile_browser_version"]);
        }
        /* valeurs boolennes */
        $this->testBoolean("is_mobile");
        $this->testBoolean("is_tablet");
        $this->testBoolean("is_wireless_device");
        $this->testBoolean("is_bot");

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'ipad') !== false) {
            if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'opera') === false) {
                $this->_aFeatures["is_tablet"] = true;
            }
        }

        /*
         * markup
         */
        if ($this->getFeature('device_os') == 'iPhone OS') {
            $this->setFeature('markup', 'iphone');
        } else {
            $this->setFeature('markup', self::getMarkupLanguage($this->getFeature('preferred_markup')));
        }

        /* image format */
        $this->_images = array();

        if ($this->getFeature('png')) {
            $this->_images[] = 'png';
        }
        if ($this->getFeature('jpg')) {
            $this->_images[] = 'jpg';
        }
        if ($this->getFeature('gif')) {
            $this->_images[] = 'gif';
        }
        if ($this->getFeature('wbmp')) {
            $this->_images[] = 'wbmp';
        }

        return $this->_aFeatures;
    }

    public function testBoolean($item)
    {
        if (! empty($this->_aFeatures[$item])) {
            $this->_aFeatures[$item] = str_replace('false', '', $this->_aFeatures[$item]);
            if ($this->_aFeatures[$item]) {
                $this->_aFeatures[$item] = true;
            }
        } else {
            $this->_aFeatures[$item] = false;
        }
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public static function getMarkupLanguage($preferred_markup = null)
    {
        switch (trim($preferred_markup)) {
            case 'wml_1_1':
            case 'wml_1_2':
            case 'wml_1_3':
                {
                    $return = 'wml'; // text/vnd.wap.wml encoding="ISO-8859-15"
                    break;
                }
            case 'html_wi_imode_compact_generic':
            case 'html_wi_imode_html_1':
            case 'html_wi_imode_html_2':
            case 'html_wi_imode_html_3':
            case 'html_wi_imode_html_4':
            case 'html_wi_imode_html_5':
                {
                    $return = 'chtml'; // text/html
                    break;
                }
            case 'html_wi_oma_xhtmlmp_1_0': // application/vnd.wap.xhtml+xml
            case 'html_wi_w3_xhtmlbasic':
                { // application/xhtml+xml DTD XHTML Basic 1.0
                    $return = 'xhtmlmp';
                    break;
                }
            case 'html_web_3_2': // text/html DTD Html 3.2 Final
            case 'html_web_4_0':
                { // text/html DTD Html 4.01 Transitional
                    $return = '';
                }
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return array
     */
    public function getImageFormatSupport()
    {
        return $this->_images;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public function getMaxImageHeight()
    {
        return $this->getFeature('max_image_height');
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public function getMaxImageWidth()
    {
        return $this->getFeature('max_image_width');
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public function getPhysicalScreenHeight()
    {
        return $this->getFeature('physical_screen_height');
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public function getPhysicalScreenWidth()
    {
        return $this->getFeature('physical_screen_width');
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public function getPreferredMarkup()
    {
        return $this->getFeature("markup");
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public function getXhtmlSupportLevel()
    {
        return $this->getFeature('xhtml_support_level');
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return bool
     */
    public function hasFlashSupport()
    {
        return $this->getFeature('fl_browser');
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return bool
     */
    public function hasPdfSupport()
    {
        return $this->getFeature('pdf_support');
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return bool
     */
    public function hasPhoneNumber()
    {
        return $this->getFeature('can_assign_phone_number');
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return bool
     */
    public function httpsSupport()
    {
        return ($this->getFeature('https_support') == 'supported');
    }
}

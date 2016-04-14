<?php
/**
 * Zend Framework
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
 * @package    Zend_Browser
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
require_once 'Pelican/Http/UserAgent/AbstractUserAgent.php';

/**
 * Mobile browser type matcher
 *
 * @category   Zend
 * @package    Zend_Browser
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

class Pelican_Http_UserAgent_Tv extends Pelican_Http_UserAgent_AbstractUserAgent
{

    /**
     * User Agent Signatures
     *
     * @static
     * @access public
     * @var array
     */
    //Samsung LE46C750 : USER-AGENT: SEC_HHP_TV-46C750/1.0
    // dlnadoc/1.50
    //http://en.wikipedia.org/wiki/10-foot_user_interface
    public static $uaSignatures = array(
        'ce-html' ,
        'large screen' ,
        'googletv' ,
        'webtv' ,
        'appletv' ,
        'escape' ,
        'digitaltv' ,
        'smarttv' ,
        'connectedtv' ,
        'hybridtv' ,
        'google tv' ,
        'digital tv' ,
        'smart tv' ,
        'connected tv' ,
        'hybrid tv' ,
        'sec_hhp_tv' ,
        'sec_hhp_' ,
        'samsungwiselinkpro' ,
        'behoo' ,
        'boxee' ,
        'kaleidescape' ,
        'nsplayer' ,
        'philips_ols' ,  // Philipd NetTV
        'playstation' ,  //playsation
        'wii' ,  //wii
        'xbmc' ,  //xbox
        'tivo' ,
        'tvhttpclient' ,
    );

    /**
     * Comparison of the UserAgent chain and User Agent signatures
     *
     * @static
     * @access public
     * @param  string $userAgent User Agent chain
     * @return bool
     */
    static public

    public function match ($userAgent)
    {
        return Pelican_Http_UserAgent::match($userAgent, self::$uaSignatures);
    }

    /**
     * Gives the current browser type
     *
     * @access public
     * @return string
     */
    public function getType ()
    {
        return 'tv';
    }

    /**
     * Look for features
     *
     * @access public
     * @return string
     */
    public function defineFeatures ()
    {
        $this->setFeature('images', true, 'product_capability');
        $this->setFeature('iframes', false, 'product_capability');
        $this->setFeature('frames', false, 'product_capability');
        $this->setFeature('javascript', true, 'product_capability');

        return parent::defineFeatures();
    }
}

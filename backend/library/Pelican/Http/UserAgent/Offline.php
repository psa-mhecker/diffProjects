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
require_once 'Pelican/Http/UserAgent/Desktop.php';

/**
 * Offline browser type matcher.
 *
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Pelican_Http_UserAgent_Offline extends Pelican_Http_UserAgent_Desktop
{
    /**
     * User Agent Signatures.
     *
     * @static
     * @access public
     *
     * @var array
     */
    public static $uaSignatures = array(
        'wget' ,
        'webzip' ,
        'webcopier' ,
        'downloader' ,
        'superbot' ,
        'offline',
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
        return Pelican_Http_UserAgent::match($userAgent, self::$uaSignatures);
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
        return 'offline';
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
        return parent::defineFeatures();
    }
}

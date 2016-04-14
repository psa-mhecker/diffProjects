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

/**
 * @see Pelican_Http_UserAgent_Storage
 */
require_once 'Pelican/Http/UserAgent/Storage.php';

/**
 * @see Zend_Session
 */
//require_once 'Zend/Session.php';

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Pelican_Http_UserAgent_Storage_Session implements Pelican_Http_UserAgent_Storage
{
    /**
     * Default session namespace.
     */
    const NAMESPACE_DEFAULT = 'Pelican_Http_UserAgent';

    /**
     * Default session object member name.
     */
    const MEMBER_DEFAULT = 'storage';

    /**
     * Object to proxy $_SESSION storage.
     *
     * @var Zend_Session_Namespace
     */
    protected $_session;

    /**
     * Session namespace.
     *
     * @var mixed
     */
    protected $_namespace;

    /**
     * Session object member.
     *
     * @var mixed
     */
    protected $_member;

    /**
     * Sets session storage options and initializes session namespace object.
     *
     * @param mixed $namespace
     * @param mixed $member
     */
    public function __construct($namespace = self::NAMESPACE_DEFAULT, $member = self::MEMBER_DEFAULT)
    {
        /* add '.' to prevent the message ''Session namespace must not start with a number' */
        $this->_namespace = '.'.$namespace;
        $this->_member = $member;
        $this->_session = new Zend_Session_Namespace($this->_namespace);
    }

    /**
     * Returns the session namespace.
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->_namespace;
    }

    /**
     * Returns the name of the session object member.
     *
     * @return string
     */
    public function getMember()
    {
        return $this->_member;
    }

    /**
     * Defined by Pelican_Http_UserAgent_Storage.
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->_session->{$this->_member});
    }

    /**
     * Defined by Pelican_Http_UserAgent_Storage.
     *
     * @return mixed
     */
    public function read()
    {
        return $this->_session->{$this->_member};
    }

    /**
     * Defined by Pelican_Http_UserAgent_Storage.
     *
     * @param mixed $contents
     */
    public function write($content)
    {
        $this->_session->{$this->_member} = $content;
    }

    /**
     * Defined by Pelican_Http_UserAgent_Storage.
     */
    public function clear()
    {
        unset($this->_session->{$this->_member});
    }
}

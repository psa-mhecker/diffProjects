<?php
/**
 * Classe de résolution d'authentification HTTP dédiée à la plateforme de services.
 *
 * @category   Pelican
 */

/**
 * @see Zend_Auth_Adapter_Http_Resolver_Interface
 */
require_once 'Zend/Auth/Adapter/Http/Resolver/Interface.php';

/**
 * HTTP Authentication Pelican Service Resolver.
 *
 * @category   Pelican
 *
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Pelican_Auth_Adapter_Http_Resolver_WebService implements Zend_Auth_Adapter_Http_Resolver_Interface
{
    protected $_functionPrefix = 'service_';

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * Resolve credentials.
     *
     * Only the first matching username/realm combination in the file is
     * returned. If the file contains credentials for Digest authentication,
     * the returned string is the Pelican_Security_Password hash, or h(a1) from RFC 2617. The
     * returned string is the plain-text Pelican_Security_Password for Basic authentication.
     *
     * The expected format of the file is:
     *   username:realm:sharedSecret
     *
     * That is, each line consists of the user's username, the applicable
     * authentication realm, and the Pelican_Security_Password or hash, each delimited by
     * colons.
     *
     * @param string $username Username
     * @param string $realm    WEBSERVICE_ACTION_NAME
     *
     * @throws Zend_Auth_Adapter_Http_Resolver_Exception
     *
     * @return string|false User's shared secret, if the user is found in the
     *                      realm, false otherwise.
     */
    public function resolve($username, $realm)
    {
        error_log('resolving...');

        if (empty($username)) {
            /**
             * @see Zend_Auth_Adapter_Http_Resolver_Exception
             */
            require_once 'Zend/Auth/Adapter/Http/Resolver/Exception.php';
            throw new Zend_Auth_Adapter_Http_Resolver_Exception('Username is required');
        } elseif (!ctype_print($username) || strpos($username, ':') !== false) {
            /**
             * @see Zend_Auth_Adapter_Http_Resolver_Exception
             */
            require_once 'Zend/Auth/Adapter/Http/Resolver/Exception.php';
            throw new Zend_Auth_Adapter_Http_Resolver_Exception('Username must consist only of printable characters, '
                                                              .'excluding the colon');
        }
        if (empty($realm)) {
            /**
             * @see Zend_Auth_Adapter_Http_Resolver_Exception
             */
            require_once 'Zend/Auth/Adapter/Http/Resolver/Exception.php';
            throw new Zend_Auth_Adapter_Http_Resolver_Exception('Realm is required');
        } elseif (!ctype_print($realm) || strpos($realm, ':') !== false) {
            /**
             * @see Zend_Auth_Adapter_Http_Resolver_Exception
             */
            require_once 'Zend/Auth/Adapter/Http/Resolver/Exception.php';
            throw new Zend_Auth_Adapter_Http_Resolver_Exception('Realm must consist only of printable characters, '
                                                              .'excluding the colon.');
        }

    // Récupération du Pelican_Cache
    $availableRealms = Pelican_Cache::fetch('Webservice/User/Action', array($username));

        error_log(count($availableRealms).' - realms found');
    //die;
        // No real validation is done on the contents of the Pelican_Security_Password file. The
        // assumption is that we trust the administrators to keep it secure.
    $i = 0;
        while ($i<(count($availableRealms)-1) && $availableRealms[$i]['WEBSERVICE_ACTION_NAME'] !== $this->_functionPrefix.$this->_realm) {
            $i++;
        }
        if (is_array($availableRealms) && $i<count($availableRealms)) {
            error_log(' - realm found, checking password...');

            return $availableRealms[$i]['PASSWORD'];
        }

        return false;
    }
}

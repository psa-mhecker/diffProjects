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
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @version    $Id: File.php 8862 2008-03-16 15:36:00Z thomas $
 */

/**
 * @see Zend_Auth_Adapter_Http_Resolver_Interface
 */
require_once 'Zend/Auth/Adapter/Http/Resolver/Interface.php';

/**
 * HTTP Authentication File Resolver.
 *
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Pelican_Auth_Adapter_Http_Resolver implements Zend_Auth_Adapter_Http_Resolver_Interface
{
    /**
     * Pelican DB connection.
     *
     * @var object
     */
    protected $oConnection;

    /**
     * Constructor.
     *
     * @param object $oConnection
     */
    public function __construct($oConnection)
    {
        if (isset($oConnection)) {
            $this->oConnection = $oConnection;
        }
    }

    /**
     * Set the path to the credentials file.
     *
     * @param string $oConnection
     *
     * @throws Zend_Auth_Adapter_Http_Resolver_Exception
     *
     * @return Pelican_Auth_Adapter_Http_Resolver Provides a fluent interface
     */
    public function setConnection($oConnection)
    {
        if (!isset($oConnection)) {
            /**
             * @see Zend_Auth_Adapter_Http_Resolver_Exception
             */
            require_once 'Zend/Auth/Adapter/Http/Resolver/Exception.php';
            throw new Zend_Auth_Adapter_Http_Resolver_Exception('Connexion base de données non définie');
        }
        $this->oConnection = $oConnection;

        return $this;
    }

    /**
     * Resolve credentials.
     *
     * Only the first matching username in the Pelican_Db is
     * returned. If the Pelican_Db contains credentials for Digest authentication,
     * the returned string is the Pelican_Security_Password hash, or h(a1) from RFC 2617. The
     * returned string is the plain-text Pelican_Security_Password for Basic authentication.
     *
     * That is, each line consists of the user's username and the Pelican_Security_Password or hash, each delimited by
     * colons.
     *
     * @param string $username Username
     * @param string $realm    Authentication Realm
     *
     * @throws Zend_Auth_Adapter_Http_Resolver_Exception
     *
     * @return string|false User's shared secret, if the user is found in the
     *                      realm, false otherwise.
     */
    public function resolve($username, $realm)
    {
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

        // Open file, read through looking for matching credentials
        $fp = @fopen($this->_file, 'r');
        if (!$fp) {
            /**
             * @see Zend_Auth_Adapter_Http_Resolver_Exception
             */
            require_once 'Zend/Auth/Adapter/Http/Resolver/Exception.php';
            throw new Zend_Auth_Adapter_Http_Resolver_Exception('Unable to open Pelican_Security_Password file: '.$this->_file);
        }

        // No real validation is done on the contents of the Pelican_Security_Password file. The
        // assumption is that we trust the administrators to keep it secure.
        while (($line = fgetcsv($fp, 512, ':')) !== false) {
            if ($line[0] == $username && $line[1] == $realm) {
                $password = $line[2];
                fclose($fp);

                return $password;
            }
        }

        fclose($fp);

        return false;
    }

    protected function getUserInfos()
    {
        $aBind[":LOGIN_VALUE"] = $this->oConnection->strToBind($this->_identity);
        $aBind[":PASS_VALUE"] = $this->oConnection->strToBind($this->_credential);

        $aBind[":TABLE_NAME"] = $this->_tableName;
        $aBind[":LOGIN"] = $this->_identityField;
        $aBind[":PASS"] = $this->_credentialField;

        if (!isset($this->_credentialTreatment)) {
            $aBind[":PASS_VALUE_T"] = $aBind[":PASS_VALUE"];
        } else {
            $aBind[":PASS_VALUE_T"] = preg_replace('/\?/', $aBind[":PASS_VALUE"], $this->_credentialTreatment);
        }

        // Récupération des informations de l'utilisateur prétendu
        $query = "select distinct
				".$aBind[":LOGIN"]." as \"id\",
				".$aBind[":PASS"]." as \"pwd\"
			FROM
				".$aBind[":TABLE_NAME"]."
			WHERE
				".$aBind[":LOGIN"]."=:LOGIN_VALUE
				AND ".$aBind[":PASS"]."=".$aBind[":PASS_VALUE_T"]."
			";
        $return = $this->oConnection->queryRow($query, $aBind);

        return $return;
    }
}

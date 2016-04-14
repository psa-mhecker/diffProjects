<?php
/**
 * Auth
 *
 * @package Pelican
 * @subpackage Auth
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */

/**
 * @see Zend_Auth
 */
require_once 'Zend/Auth.php';

/**
 * __DESC__
 *
 * @category Pelican
 * @package Auth
 * @subpackage Auth
 * @author __AUTHOR__
 */
class Pelican_Auth extends Zend_Auth
{

    /**
     * Constante d�finissant le nombre max de tentatives de connection autoris�es
     * Si null, le test est d�sactiv�
     */
    const MAX_ATTEMPT = 4;

    /**
     * Constante d�finissant la durée de blocage de compte en minutes
     */
    const DURATION_BLOCKING_ATTEMPT = 30;

    /**
     * Echec
     */
    const FAILURE = -1;

    /**
     * Echec et nombre de tentative trop grande
     */
    const FAILURE_ATTEMPT = -2;

    /**
     * Captcha incorrect
     */
    const FAILURE_CAPTCHA = -3;

    /**
     * succ�s
     */
    const SUCCES = 1;

    /**
     * Returns an instance of Pelican_Auth
     *
     * Singleton pattern implementation
     *
     * @static
     * @access public
     * @return Pelican_Auth
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        /**
         * Singleton instance
         *
         * @static
         * @access protected
         * @var Zend_Auth
         */

        /**
         * Singleton instance
         *
         * @static
         * @access protected
         * @var Zend_Auth
         */
        return self::$_instance;
    }

    /**
     * Authenticates against the supplied adapter
     *
     * @access public
     * @param Zend_Auth_Adapter_Interface $adapter __DESC__
     * @param string $captcha (option) __DESC__
     * @return int
     */
    public function authenticate($adapter, $captcha = null)
    {
        $id = $adapter->getIdentity();

        $isLoginAllowed = self::controlAttempt($id);

        // nb tentatives max dépassé
        if (!$isLoginAllowed) {
            $result = self::FAILURE_ATTEMPT;
        } else {
            // si captcha et captcha incorrect
            if (!is_null($captcha) && $captcha != $_SESSION['captcha']) {
                $result = self::FAILURE_CAPTCHA;
                // si pas de captcha ou captcha correct
            } else {
                $resultAuthenticate = parent::authenticate($adapter);
                // succès
                if ($resultAuthenticate->isValid()) {
                    self::cleanAttemptTable($id);
                    $result = self::SUCCES;
                    // échec
                } else {
                    // échec
                    self::insertAttemptTable($id);
                    $result = self::FAILURE;
                    // échec + nb tentatives max
                }
            }
        }
        return $result;
    }

    /**
     * Vérifie le nombre de tentatives de connexion
     *
     * @access public
     * @return bool
     */
    public static function controlAttempt($id)
    {
        $return = true;

        $oConnection = Pelican_Db::getInstance();

        /** attempt */
        self::createAttemptTable();

        $aBind[':USER_LOGIN'] = $oConnection->strTobind($id);
        $aBind[':ATTEMPT_IP'] = $oConnection->strTobind($id);

        // If MAX_ATTEMPT is defined, the number of attempts is tested
        if (!is_null(self::MAX_ATTEMPT)) {
            $nbAttemptLogin = (int)$oConnection->queryItem('select sum(1) from #pref#_attempt where USER_LOGIN=:USER_LOGIN and (ATTEMPT_DATE > (now() - interval '.self::DURATION_BLOCKING_ATTEMPT.' minute))', $aBind);
            $nbAttemptIp = (int)$oConnection->queryItem('select sum(1) from #pref#_attempt where ATTEMPT_IP=:ATTEMPT_IP and (ATTEMPT_DATE > (now() - interval '.self::DURATION_BLOCKING_ATTEMPT.' minute))', $aBind);
            if ($nbAttemptLogin >= self::MAX_ATTEMPT || $nbAttemptIp >= self::MAX_ATTEMPT) {
                $return = false;
            }
        }
        return $return;
    }

    public static function insertAttemptTable($id)
    {
        $oConnection = Pelican_Db::getInstance();
        Pelican_Db::$values['USER_LOGIN'] = $id;
        Pelican_Db::$values['ATTEMPT_IP'] = $id;
        Pelican_Db::$values['ATTEMPT_DATE'] = ':DATE_COURANTE';
        $oConnection->insertQuery('#pref#_attempt');
    }

    public static function createAttemptTable()
    {
        $oConnection = Pelican_Db::getInstance();
        $exists = $oConnection->getDbInfo('tables');
        if (!in_array(Pelican_Db::replacePrefix('#pref#_attempt'), $exists)) {
            $oConnection->query('CREATE TABLE IF NOT EXISTS `#pref#_attempt` (
            `USER_LOGIN` varchar(50) NOT NULL,
            `ATTEMPT_IP` varchar(30) NOT NULL,
            `ATTEMPT_DATE` datetime NOT NULL,
            PRIMARY KEY  (`USER_LOGIN`,`ATTEMPT_IP`,`ATTEMPT_DATE`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;');
            Pelican_Cache::clean("Database/Describe/Table", array("fields", Pelican_Db::replacePrefix('#pref#_attempt')));
        }
    }

    public static function cleanAttemptTable($id)
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':USER_LOGIN'] = $oConnection->strToBind($id);
        $oConnection->query('delete from #pref#_attempt where USER_LOGIN=:USER_LOGIN', $aBind);
    }
}

?>
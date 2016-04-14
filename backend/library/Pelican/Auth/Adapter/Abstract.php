<?php
require_once Pelican::$config['LIB_ROOT'].'/Zend/Auth/Adapter/Interface.php';
require_once Pelican::$config['LIB_ROOT'].'/Zend/Auth/Result.php';

/**
 * Classe d'authentification utilisateur via la connexion bdd Pélican.
 *
 * @author Pierre Moiré <pierre.moire@businessdecision.com>
 */
abstract class Pelican_Auth_Adapter_Abstract
{
    /**
     * Connexion.
     *
     * @var Dbfw
     */
    protected $oConnection;

    protected $_tableName;
    protected $_identityField;
    protected $_credentialField;
    protected $_credentialTreatment;

    /**
     * $_identity - Identity value.
     *
     * @var string
     */
    protected $_identity = null;

    /**
     * $_credential - Credential values.
     *
     * @var mixed
     */
    protected $_credential = null;

    public function __construct()
    {
        global $oConnection;
        if ($oConnection) {
            $this->oConnection = $oConnection;
        } else {
            $this->oConnection = Pelican_Db::getInstance();
        }
    }

    public function setIdentity($identity)
    {
        $this->_identity = $identity;
    }

    public function setCredential($credential)
    {
        $this->_credential = $credential;
    }

    public function setConfig($conf)
    {
        $this->_tableName = $conf[0];
        $this->_identityField = $conf[1];
        $this->_credentialField = $conf[2];
        // Cas où le mot de passe doit subir un traitement avant utilisation (exemple : MD5)
        if (isset($conf[3])) {
            $this->_credentialTreatment = $conf[3];
        }
    }

    /**
     * Récupère les données de l'utilisateur prétendu.
     *
     * @return mixed
     */
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

		//error_log($query);
        $return = $this->oConnection->queryRow($query, $aBind);

        return $return;
    }
}

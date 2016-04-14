<?php
require_once Pelican::$config['LIB_ROOT'].'/Zend/Auth/Adapter/Interface.php';
require_once Pelican::$config['LIB_ROOT'].'/Zend/Auth/Result.php';
class Pelican_Auth_Adapter implements Zend_Auth_Adapter_Interface
{
    /**
     * Connexion.
     *
     * @var Dbfw
     */
    protected $oConnection;

    /**
     * $_identity - Identity value.
     *
     * @var string
     */
    protected $_identity = null;

    /**
     * $_credential - Credential values.
     *
     * @var string
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

    /**
     * Performs an authentication attempt.
     *
     * @throws Zend_Auth_Adapter_Exception If authentication cannot be performed
     *
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        if (isset($this->_identity) && isset($this->_credential)) {
            $oConnection = $this->oConnection;

            $query = "select distinct
						u.PORTAL_USER_ID as \"id\",
						u.PORTAL_USER_PASSWORD as \"pwd\"
					FROM
						#pref#_portal_user u
					WHERE
						u.PORTAL_USER_ID=:1
					";
            $query2 = "select distinct
						u.USER_LOGIN as \"id\",
						u.USER_PASSWORD as \"pwd\"
					FROM
						#pref#_user u
					WHERE
						u.USER_LOGIN=:1
					";
            $aBind[":1"] = $oConnection->strToBind($this->_identity);
                //$aBind[":2"] = $oConnection->strToBind(md5($this->_credential));
                //$aBind[":SITE_ID"]=$_SESSION[APP]['SITE_ID'];
                $result = $oConnection->getRow($query, $aBind);

            $resultMessage = array();
            if (!empty($result)
                    && ($result["pwd"] == md5($this->_credential) || ($result["pwd"] == "" && $this->_credential == ""))) {
                //réussite
                    $resultCode = Zend_Auth_Result::SUCCESS;
                unset($result['pwd']);
                $identity = $result;
            } else {
                //erreurs
                    $resultMessage[] = "échec authent.";
                if (array_key_exists("pwd", $result) && $result["pwd"] != md5($this->_credential)) {
                    //erreur pwd
                        $resultCode = Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
                    $identity = "";
                    $resultMessage[] = "mauvais pwd";
                } elseif (empty($result)) {
                    //erreur user
                        $result2 = $oConnection->getRow($query2, $aBind);
                    if (!empty($result2)) {
                        //si le user existe dans la table d'administration
                            //on l'ajoute dans la table des utilisateurs du portal

                            $DBVALUES_SAVE = Pelican_Db::$values;
                        Pelican_Db::$values = $result2;
                        Pelican_Db::$values["PORTAL_USER_ID"] = Pelican_Db::$values["id"];
                        Pelican_Db::$values["PORTAL_USER_PASSWORD"] = Pelican_Db::$values["pwd"];
                        $oConnection->updateTable(Pelican::$config["DATABASE_INSERT"], "#pref#_portal_user");
                        Pelican_Db::$values = $DBVALUES_SAVE;
                        $this->authenticate();
                    } else {
                        $resultCode = Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND;
                        $identity = "";
                        $resultMessage[] = "user inexistant";
                    }
                } else {
                    $resultCode = Zend_Auth_Result::FAILURE_UNCATEGORIZED;
                    $identity = "";
                    $resultMessage[] = "autre erreur authent";
                }
            }
        } else {
            $resultCode = Zend_Auth_Result::FAILURE_UNCATEGORIZED;
            $identity = "";
            $resultMessage[] = "échec authent : login ou pwd vide";
        }

        return new Zend_Auth_Result($resultCode, $identity, $resultMessage);
    }

    public function setIdentity($identity)
    {
        $this->_identity = $identity;
    }

    public function setCredential($credential)
    {
        $this->_credential = $credential;
    }
}

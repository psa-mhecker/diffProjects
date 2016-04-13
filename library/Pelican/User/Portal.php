<?php
pelican_import('User');
/**
 * Classe de description de l'utilisateur courant
 *
 */
class Pelican_User_Portal extends Pelican_User
{

    public $session_label = 'portal';

    public static function getInstance()
    {
        static $_instance;
        
        if (!is_object($_instance)) {
            $_instance = new self();
        }
        
        return $_instance;
    }

    /**
     * Méthode d'authentification
     *
     * @param string $password
     * @return Pelican_Auth_Result
     */
    public function login($id, $password)
    {
        
        
        $this->controlAttempt();
        
        $auth = Pelican_Auth::getInstance();
        
        $authAdapter = new Pelican_Auth_Adapter_Db_Basic();
        $authAdapter->setIdentity($id);
        $authAdapter->setCredential($password);
        $authAdapter->setConfig(array('#pref#_portal_user' , 'PORTAL_USER_ID' , 'PORTAL_USER_PASSWORD' , 'MD5(?)'));
        
        //Pour gestion du stockage des données d'authentification en session
        $authStorage = new Pelican_Auth_Storage_Session(APP, $this->session_label);
        $auth->setStorage($authStorage);
        
        $result = $auth->authenticate($authAdapter);
        
        $this->setData($_SESSION[APP][$this->session_label]);
        
        $this->saveInfos();
        
        $this->endAttempt();
        
        return ($result);
    }

    public function saveInfos()
    {
        
        
        if ($this->isLoggedIn()) {
            //si le user existe dans la table d'administration
            //on l'ajoute dans la table des utilisateurs du portal
            $oConnection = Pelican_Db::getInstance();
            $DBVALUES_SAVE = Pelican_Db::$values;
            Pelican_Db::$values['PORTAL_USER_ID'] = $this->get('id');
            Pelican_Db::$values['PORTAL_USER_PASSWORD'] = md5($this->get('password'));
            $aBind[':PORTAL_USER_ID'] = $oConnection->strToBind($this->get('id'));
            $oConnection->replaceQuery("#pref#_portal_user", "PORTAL_USER_ID=:PORTAL_USER_ID", "", array('PORTAL_USER_ID'), $aBind);
            Pelican_Db::$values = $DBVALUES_SAVE;
        }
    }
}
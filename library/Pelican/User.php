<?php
/**
 * Classe de gestion d'un utilisateur de l'a plateforme
 *
 * @package Pelican
 * @subpackage User
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * (http://www.businessdecision.com)
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */
pelican_import('Auth');
require_once (pelican_path('Auth.Storage.Session'));
require_once (pelican_path('Auth.Adapter.Db.Basic'));
pelican_import('Session.Data');
define('MAX_ATTEMPT', 4);

/**
 * Classe de gestion d'un utilisateur de l'a plateforme
 *
 * @package Pelican
 * @subpackage User
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_User extends Pelican_Session_Data {
    
    /**
     * Id de session
     *
     * @access public
     * @var string
     */
    public $session_label = 'user';
    
    /**
     * Constructeur
     *
     * @access public
     * @return void
     */
    public function __construct() {
        parent::__construct(APP, $this->session_label);
        if (!empty($_SESSION[APP][$this->session_label])) {
            $this->setData($_SESSION[APP][$this->session_label]);
        }
    }
    
    /**
     * Authentification
     *
     * @access public
     * @param string $id Login
     * @param string $password Mot de passe
     * @return Pelican_Auth_Result
     */
    public function login($id, $password) {
  
        $auth = Pelican_Auth::getInstance();
        $authAdapter = new Pelican_Auth_Adapter_Db_Basic();
        $authAdapter->setIdentity($id);
        $authAdapter->setCredential($password);
        $authAdapter->setConfig(array('#pref#_user', 'USER_LOGIN', 'USER_PASSWORD', 'MD5(?)'));
        //Pour gestion du stockage des données d'authentification en session
        $authStorage = new Pelican_Auth_Storage_Session(APP, $this->session_label);
        $auth->setStorage($authStorage);
        $result = $auth->authenticate($authAdapter);
        $this->setData($_SESSION[APP][$this->session_label]);
      
        $this->getRights();
        return ($result);
    }
    
    /**
     * Informe si l'utilisateur est authentifié
     *
     * @access public
     * @return bool
     */
    public function isLoggedIn() {
        $return = !$this->isEmpty();
        return $return;
    }
    
    /**
     * Déconnexion
     *
     * @access public
     * @return void
     */
    public function logout() {
        $data = array();
        $this->setData($data);
        Zend_Session::destroy(true);
    }
    
    /**
     * Sauvegarde des infos de connexion
     *
     * @access public
     * @return void
     */
    public function saveInfos() { //todo
        
    }
    
    /**
     * Récupération des informations applicatives associé à l'utilisateur
     *
     * @access public
     * @return array
     */
    public function getFullInfos() { //todo
        
    }
    
    /**
     * Sauvegarde
     *
     * @access public
     * @return void
     */
    public function save() { //todo
        
    }
    
    /**
     * Chargement
     *
     * @access public
     * @return void
     */
    public function load() { //todo
        
    }
    
    
    
    /**
     * Retourne les droits de l'utilisateur
     *
     * @access public
     * @return array
     */
    public function getRights() {
    }
}
?>
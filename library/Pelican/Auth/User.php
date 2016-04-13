<?php
require_once(Pelican::$config['LIB_ROOT'].'/Zend/Auth.php');
require_once(Pelican::$config['LIB_ROOT'].'/Zend/Auth/Storage/Session.php');
require_once(Pelican::$config['LIB_ROOT'].Pelican::$config['LIB_AUTH'].'/Adapter.php');
require_once(Pelican::$config['LIB_ROOT'].'/Pelican/Session/Data.php');
/**
 * Classe de description de l'utilisateur courant
 *
 */
class Pelican_Auth_User extends Pelican_Session_Data {
	/**
	 * Constructeur
	 *
	 * @param string $login
	 * @param string $name
	 */
	public function __construct(){
		
		parent::__construct(APP,Pelican::$config['USER_SESSION_MEMBER']);
		if(!empty($_SESSION[APP][Pelican::$config['USER_SESSION_MEMBER']]))
		{
			$this->setData($_SESSION[APP][Pelican::$config['USER_SESSION_MEMBER']]);				
		}
	}
	
	/**
	 * M�thode d'authentification
	 *
	 * @param string $password
	 * @return Zend_Auth_Result
	 */
	public function login($id,$password,$bypass=false){
		
		$auth=Zend_Pelican_Auth::getInstance();
		
		//Pour gestion de la mani�re dont on identifie l'utilisateur 
		//et des donn�es utilisateur � r�cup�rer
		$authAdapter=new Pelican_Auth_Adapter();
		$authAdapter->setIdentity($id);
		$authAdapter->setCredential($password);
		
		//Pour gestion du stockage des donn�es d'authentification en session
		$authStorage=new Zend_Auth_Storage_Session(APP,Pelican::$config['USER_SESSION_MEMBER']);
		$auth->setStorage($authStorage);
		$result=$auth->authenticate($authAdapter);
		$this->setData($_SESSION[APP][Pelican::$config['USER_SESSION_MEMBER']]);
		
		return($result);
	}
	
	public function isLoggedIn(){
		return !$this->isEmpty();
	}
	
	public function logout(){
		$data=array();
		$this->setData($data);
		Zend_Session::destroy(true);
	}
	
}
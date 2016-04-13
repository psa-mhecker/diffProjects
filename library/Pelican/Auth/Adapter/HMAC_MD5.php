<?php
require_once('Abstract.php');

/**
* Classe d'authentification utilisateur basée sur la signature de requête avec l'algorithme HMAC MD5
* Ref : www.faqs.org/rfcs/rfc2104.html
*
* @package Pelican
* @subpackage Pelican_Auth
* @author Pierre Moiré <pierre.moire@businessdecision.com>
*
* ----------------------------------------------------------------------
* Procédure de signature (exemple PHP) : 
*	
*	$message = array(
*		'uid'		=>	$user_login,
*		'<clef>'	=>	<donnees>,
*		...
*		'date'		=>	gmdate("YMdHis")
*		'password'	=> 	$pass
*	);
*
*	$h = hash_hmac( 'md5', implode("\n", $message), $password_utilisateur );
*
*	$url = $url.'&h='.$h
* ----------------------------------------------------------------------
*/

class Pelican_Auth_Adapter_HMAC_MD5 extends Pelican_Auth_Adapter_Abstract implements Zend_Auth_Adapter_Interface{

	
	/**
	* Performs an authentication attempt
	*
	* @throws Zend_Auth_Adapter_Exception If authentication cannot be performed
	* @return Zend_Auth_Result
	*/
	public function authenticate(){
		
		$resultCode = '';
		
		// Cas 1 : Echec - Les données sont mal remplies
		if (!isset($this->_identity) || !isset($this->_credential) ) {
			
			$resultCode = Zend_Auth_Result::FAILURE;
			$resultMessage[]= "Il manque des données d'authentification";
			
			
		// Cas 2 : Les données sont bien remplies
		}else{
			$result = $this->getUserInfos();
			
			// Cas 2.1 : L'utilisateur prétendu existe
			if( !empty($result) ){
			
				$key = $result['pwd'];
				$this->transformKey($key);
				
				$integrity = $this->checkIntegrity($key);
				
				// Cas 2.1.1 : Succes - L'utilisateur est celui qu'il prétend être
				if( $integrity===true ){
				
					$resultCode = Zend_Auth_Result::SUCCESS;
					unset($result);
					$resultMessage[]= "Authentification passée avec succès";
				
				// Cas 2.1.2 : Echec - L'utilisateur n'est pas celui qu'il prétend être
				}else{
				
					$resultCode = Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
					$resultMessage[]= "La signature du message est invalide";
				
				}
				
				
			// Cas 2.2 : L'utilisateur n'existe pas
			}else{

				$resultMessage[]= "L'utilisateur n'existe pas";
				$resultCode=Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND;

			}
		}
	
		return new Zend_Auth_Result($resultCode,$identity,$resultMessage);
	}


	public function transformKey($key){
		if(!isset($this->_credentialTreatment)){
			return $key;
		}else{
			$t = strtolower($this->_credentialTreatment);
			if( $t=='md5(?)' ){
				$r = md5($key);
			}
		}
		return $r;
	}

	/**
	* Vérifie l'intégrité des données
	* @return bool
	*/
	private function checkIntegrity($key){
		if( !isset($this->_credential['message']) ){
			return false;
		}
		
		if(!is_array($this->_credential['message'])){
			$msg = $this->_credential['message'];
		}else{
			$msg = implode( "\n", $this->_credential['message'] );
		}
		/*echo 'message : '.$msg.'<br />';
		echo 'clef de calcul : '.$key.'<br />';
		echo 'reel : '.hash_hmac( "md5", $msg, $key ).'<br />';
		echo 'fourni : '.$this->_credential['hash'].'<br />';*/
		return hash_hmac( "md5", $msg, $key )==$this->_credential['hash'];
	}
	
	/**
	* Récupère les données de l'utilisateur prétendu
	* @return mixed
	*/
	protected function getUserInfos(){
		
		
		$aBind[":LOGIN_VALUE"] = $this->oConnection->strToBind($this->_identity);
		
		$aBind[":TABLE_NAME"] = $this->_tableName;
		$aBind[":LOGIN"] = $this->_identityField;
		$aBind[":PASS"] = $this->_credentialField;
		
		// Récupération des informations de l'utilisateur prétendu
		$query = "select distinct
				".$aBind[":LOGIN"]." as \"id\",
				".$aBind[":PASS"]." as \"pwd\"
			FROM
				".$aBind[":TABLE_NAME"]."
			WHERE
				".$aBind[":LOGIN"]."=:LOGIN_VALUE
			";
				
		$return = $this->oConnection->queryRow($query, $aBind);
		return $return;
	
	}
}
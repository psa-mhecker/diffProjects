<?php
require_once('Basic.php');

/**
* Classe d'authentification utilisateur via bdd Pélican
*
* @package Pelican
* @subpackage Pelican_Auth
* @author Pierre Moiré <pierre.moire@businessdecision.com>
*
*/

class Pelican_Auth_Adapter_Digest extends Pelican_Auth_Adapter_Basic implements Zend_Auth_Adapter_Interface {

	protected $_nonce;
	protected $_created;
	
	public function setCredential( $infos ){
		$this->_nonce = $infos['Nonce'];
		$this->_created = $infos['Created'];
		$this->_credential = base64_decode($infos['Password']);
	}

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
			
			
		// Cas 2 : Les données sont a priori bien remplies
		}else{
			$result = $this->getUserInfos();
			
			// Cas 2.1 : L'utilisateur prétendu existe
			if( !empty($result) ){
				
				$h = sha1($this->_nonce.':'.$this->_created.':'.$result['pwd']);
		
				if($h==$this->_credential){
					$resultCode = Zend_Auth_Result::SUCCESS;
					unset($result);
					$resultMessage[]= "Authentification passée avec succès";
				}else{
					$resultMessage[]= "L'utilisateur n'existe pas";
					$resultCode=Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND;
				}
			
			// Cas 2.2 : L'utilisateur n'existe pas
			}else{

				$resultMessage[]= "L'utilisateur n'existe pas";
				$resultCode=Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND;

			}
		}
	
		return new Zend_Auth_Result($resultCode,$identity,$resultMessage);
	}

	protected function getUserInfos(){
		
		
		$aBind[":LOGIN_VALUE"] = $this->oConnection->strToBind($this->_identity);
		$aBind[":PASS_VALUE"] = $this->oConnection->strToBind($this->_credential);
		
		$aBind[":TABLE_NAME"] = $this->_tableName;
		$aBind[":LOGIN"] = $this->_identityField;
		$aBind[":PASS"] = $this->_credentialField;
		
		/*if( !isset($this->_credentialTreatment) ){
			$aBind[":PASS_VALUE_T"] = $aBind[":PASS_VALUE"];
		}else{
			$aBind[":PASS_VALUE_T"] = preg_replace( '/\?/', $aBind[":PASS_VALUE"], $this->_credentialTreatment );
		}*/
		
		// Récupération des informations de l'utilisateur prétendu
		$query = "select distinct
				".$aBind[":LOGIN"]." as \"id\",
				".$aBind[":PASS"]." as \"pwd\"
			FROM
				".$aBind[":TABLE_NAME"]."
			WHERE
				".$aBind[":LOGIN"]."=:LOGIN_VALUE"; 
			//	AND ".$aBind[":PASS"]."=".$aBind[":PASS_VALUE_T"]."
			
				
		$return = $this->oConnection->queryRow($query, $aBind);
		return $return;
	
	}
}
<?php
	require_once('Abstract.php');
	 
	/**
	* Classe d'authentification utilisateur via bdd Pélican
	*
	* @package Pelican
	* @subpackage Auth
	* @author Pierre Moiré <pierre.moire@businessdecision.com>
	*
	*/
	 
	class Pelican_Auth_Adapter_Db_Basic extends Pelican_Auth_Adapter_Db_Abstract implements Zend_Auth_Adapter_Interface {
		 
		 
		/**
		* Performs an authentication attempt
		*
		* @throws Zend_Auth_Adapter_Exception If authentication cannot be performed
		* @return Zend_Auth_Result
		*/
		public function authenticate() {
			$resultCode = '';
			 
			// Cas 1 : Echec - Les données sont mal remplies
			if (!isset($this->_identity) || !isset($this->_credential) ) {
				 
				$resultCode = Zend_Auth_Result::FAILURE;
				$resultMessage[] = "Il manque des données d'authentification";
				 
				 
				// Cas 2 : Les données sont bien remplies
			} else {
				$result = $this->getUserInfos();
				$identity = $result;
				 
				// Cas 2.1 : L'utilisateur prétendu existe
				if (!empty($result) ) {
					 
					$resultCode = Zend_Auth_Result::SUCCESS;
					unset($result);
					$resultMessage[] = "Authentification passée avec succès";
					 
					// Cas 2.2 : L'utilisateur n'existe pas
				} else {
					 
					$resultMessage[] = "L'utilisateur n'existe pas";
					$resultCode = Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND;
					 
				}
			}
			 
			return new Zend_Auth_Result($resultCode, $identity, $resultMessage);
		}
		 
	}
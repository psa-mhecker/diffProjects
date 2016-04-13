<?php
/**
 * View Date
 * 
 * @version 1.0
 * @since 18/07/2013
 */

Class Frontoffice_Devise_Helper {
	
	/**
	 * Méthode permettant de formater la date en toute lettre selon la langue
	 * 
	 * @param $iLangueId int : Langue sélectionnée
	 * @param $sDevise string : Devise
	 * @param $iPrix int : Prix
	 * @return $sReturn string : Chaine prix + devise formatée
	 */
	
	public static function formatDevise($iLangueId = 2, $sDevise = "€", $iPrix = 0) {
		$sReturn = "";
		$sDevise = empty($sDevise) ? '€' : $sDevise;
		if($iPrix != 0){
			switch($iLangueId){
				case Pelican:: $config['ANGLAIS'] :
					$sReturn = $sDevise."&nbsp;".$iPrix;
				break;
				default :
					$sReturn = $iPrix."&nbsp;".$sDevise;
				break;
			}
		}		
		return $sReturn;
	}
	
	/**
	 * Méthode permettant de retourner la position de la devise par rapport au prix
	 * 
	 * @param $iLangueId int : Langue sélectionnée
	 * @return $aReturn array
	 */
	
	public static function orderDevise($iLangueId = 2) {
		$aReturn = array();
		$bBefore = false;
		$bAfter = false;
		switch($iLangueId){
			case Pelican:: $config['ANGLAIS'] :
				$bBefore = true;
			break;
			default :
				$bAfter = true;
			break;
		}
		$aReturn['before'] = $bBefore;
		$aReturn['after'] = $bAfter;
		return $aReturn;
	}
	
}
?>
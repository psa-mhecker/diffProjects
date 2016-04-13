<?php
/**
 * View Date
 * 
 * @version 1.0
 * @since 18/07/2013
 */

Class Frontoffice_Date_Helper {
	
	/**
	 * Méthode permettant de formater la date en toute lettre selon la langue
	 * 
	 * @param $iLangueId int : Langue sélectionnée
	 * @param $sDate string : Date à formater
	 * @return $sReturn string : Date formatée
	 */
	
	public static function formatDate($iLangueId = 2, $sDate = "") {
		$sReturn = "";
		if($sDate != ""){
			$aDate = explode('-',$sDate);
			switch($iLangueId){
				case Pelican:: $config['ANGLAIS'] :
					$sReturn = t($aDate[0]).',&nbsp;'.t($aDate[2]).'&nbsp;'.$aDate[1].',&nbsp;'.$aDate[3];
				break;
				default :
					$sReturn = t($aDate[0]).'&nbsp;'.$aDate[1].'&nbsp;'.t($aDate[2]).'&nbsp;'.$aDate[3];
				break;
			}
		}		
		return $sReturn;
	}
}
?>
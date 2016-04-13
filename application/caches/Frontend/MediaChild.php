<?php
	/**
	* @package Cache
	* @subpackage Media
	*/
	 
	/**
	* Fichier de Pelican_Cache : Requête récupérant tous les médias enfant d'un media référent
	*
	* retour : *
	*
	* @package Cache
	* @subpackage Media
	* @author Raphaël Carles <rcarles@businessdecision.com>
	* @since 20/06/2004
	*/
	class Frontend_MediaChild extends Pelican_Cache {
		 
		
		var $duration = DAY;
		 
		/** Valeur ou objet à mettre en Pelican_Cache */
		function getValue() {
			
			$oConnection = Pelican_Db::getInstance();
			$aBind[":MEDIA_ID_REFERENT"] = $this->params[0]; 
			
			$sSQL = "
				SELECT 
					MEDIA_PATH
				FROM 
					#pref#_media
				WHERE 
					MEDIA_ID = :MEDIA_ID_REFERENT";
			$aMediaChild['MEDIA_REFERENT']['MEDIA_PATH'] = $oConnection->queryItem($sSQL, $aBind);
			if($aMediaChild['MEDIA_REFERENT']['MEDIA_PATH']){
				$aMediaChild['MEDIA_REFERENT']['EXTENSION'] = pathinfo($aMediaChild['MEDIA_REFERENT']['MEDIA_PATH'], PATHINFO_EXTENSION);
			}
			$sSQL = "
				SELECT 
					MEDIA_PATH
				FROM 
					#pref#_media
				WHERE 
					MEDIA_ID_REFERENT = :MEDIA_ID_REFERENT";
			$aMediaChild['MEDIA_ENFANTS'] = $oConnection->queryTab($sSQL, $aBind);
			if(is_array($aMediaChild['MEDIA_ENFANTS']) && count($aMediaChild['MEDIA_ENFANTS'])>0){
				foreach($aMediaChild['MEDIA_ENFANTS'] as $key=>$child){
					$aMediaChild['MEDIA_ENFANTS'][$key]['EXTENSION'] = pathinfo($child['MEDIA_PATH'], PATHINFO_EXTENSION);
				}
			}
			
			$this->value = $aMediaChild;
		}
	}
	 
?>
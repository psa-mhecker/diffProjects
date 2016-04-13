<?php
/**
 * Fichier de Pelican_Cache : CategoryCaracteristiques
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_Finitions_CategoryCaracteristiques extends Pelican_Cache {

    var $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':GAMME'] = $oConnection->strToBind($this->params[0]);
        $aBind[':SITE_ID'] = $this->params[1];
        $aBind[':LANGUE_ID'] = $this->params[2];
        if( empty($this->params[0])){
			$sSQL = "
				SELECT
					distinct CATEGORY_NAME
				FROM
					#pref#_ws_caracteristique_technique
				WHERE
					SITE_ID = :SITE_ID
				AND LANGUE_ID = :LANGUE_ID			
			";		
		}else{
			$sSQL = "
				SELECT
					distinct CATEGORY_NAME
				FROM
					#pref#_ws_caracteristique_technique
				WHERE
					SITE_ID = :SITE_ID
				AND LANGUE_ID = :LANGUE_ID
				AND GAMME = :GAMME
				
			";
		}
        $aResult = $oConnection->queryTab($sSQL, $aBind);
        $this->value = $aResult;
    }
}
<?php
/**
 * Fichier de Pelican_Cache : Criteres
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_Criteres extends Pelican_Cache {

    var $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
		$aBind[':SITE_ID'] = $this->params[0];
		$aBind[':LANGUE_ID'] = $this->params[1];
        $sSQL = "
            SELECT
				*
			FROM
				#pref#_critere
			WHERE 
				SITE_ID = :SITE_ID
			AND LANGUE_ID = :LANGUE_ID	
			ORDER BY CRITERE_ID
        ";
        $aRes = $oConnection->queryTab($sSQL, $aBind);
		$aCriteres = array();
		if(is_array($aRes) && count($aRes)>0){
			foreach($aRes as $res){
				$aCriteres[$res['CRITERE_TYPE']][] = $res;
			}
		}
		$this->value = $aCriteres;
    }
    
    
}
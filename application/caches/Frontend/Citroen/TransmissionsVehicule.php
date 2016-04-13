<?php
/**
 * Fichier de Pelican_Cache : Liste des boite de vitesse vehicule
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_TransmissionsVehicule extends Pelican_Cache {

    var $duration = DAY;
    
    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
		$oConnection = Pelican_Db::getInstance ();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        $aBind[':GAMME'] = $this->params[2];
        $sSQL = "
            SELECT 
				DISTINCT CRIT_TR_CODE,
				CRIT_TR_LABEL
            FROM 
				#pref#_ws_critere_selection
            WHERE SITE_ID = :SITE_ID
            AND LANGUE_ID = :LANGUE_ID
           
        ";
            if($aBind[':GAMME'])
        {
             $sSQL .= "AND GAMME = ':GAMME' ";
        }
         $sSQL .= "order by CRIT_TR_CODE ";

        $aResult = $oConnection->queryTab($sSQL, $aBind);
        $this->value = $aResult;
    }
}
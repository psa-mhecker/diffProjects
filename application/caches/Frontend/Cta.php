<?php
/**
 * Fichier de Pelican_Cache : CTA
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Cta extends Pelican_Cache {

    var $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
		
		$oConnection = Pelican_Db::getInstance ();
		
		$aBind[':BARRE_OUTILS_ID'] = $this->params[0];
        $aBind[':SITE_ID'] = $this->params[1];
        $aBind[':LANGUE_ID'] = $this->params[2];
			
			$sql = 'SELECT
						BARRE_OUTILS_ID ID,
						BARRE_OUTILS_LABEL LIB,
						BARRE_OUTILS_URL_WEB,
						BARRE_OUTILS_URL_MOBILE,
						BARRE_OUTILS_MODE_OUVERTURE
					FROM 
						#pref#_barre_outils
					WHERE
						BARRE_OUTILS_ID = :BARRE_OUTILS_ID
						AND SITE_ID = :SITE_ID 
						AND LANGUE_ID = :LANGUE_ID';
					

		$aCta = $oConnection->queryRow($sql, $aBind);
        $this->value = $aCta;
    }
	

}
<?php
/**
 * Fichier de Pelican_Cache : Résultat de requête sur site_id pour avoir le code pays
 *
 * retour : id, lib
 *
 * @package Cache
 * @subpackage General
 * @author Sébastien Maillot <sebastien.maillot@businessdecision.com>
 * @since 06/02/2015
 * 
 */
class Citroen_CodePaysWithSiteId extends Pelican_Cache {
	
    public $duration = UNLIMITED;

    /**
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue() 
    {

        $oConnection = Pelican_Db::getInstance ();
        /* Initialisation des variables */
        /* Tableau de résultats */
        $aValue = array();
        /* Tableau des paramètres bind*/
        $aBind = array();
                
		 if ( !is_null($this->params[0]) ){
            $aBind[':SITE_ID'] = (int)$this->params[0];
        }
		 
        $sSqlSite = <<<SQL
            SELECT
                SITE_CODE_PAYS
            FROM #pref#_site_code 
			WHERE 
			SITE_ID = :SITE_ID
SQL;
        
        $aValue = $oConnection->queryItem ( $sSqlSite, $aBind);
        
        $this->value = $aValue;
    }
}
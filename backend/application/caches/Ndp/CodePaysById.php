<?php

/**
 * Fichier de Pelican_Cache : Résultat de requête sur site
 *
 * 
 */
class Ndp_CodePaysById extends Pelican_Cache
{

    public $duration = UNLIMITED;

    /**
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {

        $oConnection = Pelican_Db::getInstance();
        /* Initialisation des variables */
        /* Tableau de résultats */
        $aValue = array();
        /* Tableau des paramètres bind */
        $aBind = array();
        /* Définit si la requête ne doit envoyée qu'une seule ligne */
        $bSendOneLine = false;
        /* Chaîne de caractères contenant les contraintes */
        $sSqlSiteWhere = '';

        $sSqlSite = <<<SQL
            SELECT 
                SITE_ID,
                SITE_CODE_PAYS
            FROM #pref#_site_code 
SQL;

        $aSites = $oConnection->queryTab($sSqlSite);

        foreach ($aSites as $aOneSite) {
            $aValue[$aOneSite['SITE_ID']] = $aOneSite['SITE_CODE_PAYS'];
        }

        $this->value = $aValue;
    }
}

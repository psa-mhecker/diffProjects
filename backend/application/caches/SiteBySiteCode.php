<?php

/**
 * Fichier de Pelican_Cache : Résultat de requête sur site.
 *
 * retour : id, lib
 *
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 *
 * @since  12/07/2013
 *
 * @param  0 SITE_CODE Code pays du site à remonter
 */
class SiteBySiteCode extends Pelican_Cache
{
    public $duration = UNLIMITED;

    /**
     * Valeur ou objet à mettre en Pelican_Cache.
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        /* Initialisation des variables */
        /* Tableau de résultats */
        $aValue = array();
        /* Tableau des paramètres bind*/
        $aBind = array();
        /* Définit si la requête ne doit envoyée qu'une seule ligne*/
        $bSendOneLine = false;
        /* Chaîne de caractères contenant les contraintes */
        $sSqlSiteWhere = '';

        if (!is_null($this->params[0])) {
            $aBind[':SITE_CODE_PAYS'] = (string)$oConnection->strToBind($this->params[0]);
            $bSendOneLine = true;
        }

        $sSqlSite = <<<SQL
            SELECT
                s.SITE_ID,
                sc.SITE_CODE_PAYS,
                s.SITE_LABEL,
                s.SITE_URL
            FROM
                #pref#_site as s
                INNER JOIN #pref#_site_code as sc ON (s.SITE_ID = sc.SITE_ID)
SQL;
        if (array_key_exists(':SITE_CODE_PAYS', $aBind)) {
            $sSqlSiteWhere = <<<SQL
                    sc.SITE_CODE_PAYS = :SITE_CODE_PAYS
SQL;
        }

        /* Ajout des contraintes à la requete principale */
        if (!empty($sSqlSiteWhere)) {
            $sSqlSite = "{$sSqlSite} WHERE {$sSqlSiteWhere}";
        }

        /* Envoi d'un tableau d'une seule dimension si $bSendOneLine vaut true */
        if ($bSendOneLine === true) {
            $aValue = $oConnection->queryRow($sSqlSite, $aBind);
        } else {
            $aSites = $oConnection->queryTab($sSqlSite, $aBind);
            /* Changement des clés du tableau de retour pour qu'elles correspondent
             * au code pays du site et non à une numéro de ligne
             */
            foreach ($aSites as $aOneSite) {
                $aValue[$aOneSite['SITE_CODE_PAYS']] = $aOneSite;
            }
        }
        $this->value = $aValue;
    }
}

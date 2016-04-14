<?php
/**
 * Fichier de Pelican_Cache : Termes/Produits Perso.
 */
class Frontend_Citroen_Perso_SearchTermTrack extends Pelican_Cache
{
    public $duration = DAY;
    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':TERM'] = $oConnection->strToBind($this->params[1]);
        $aTerms = array();

        $sSQL = "
            SELECT
                *
            FROM
              #pref#_perso_product_term
            WHERE
              SITE_ID = :SITE_ID
            and
              PRODUCT_TERM_LABEL = :TERM
        ";
        $results = $oConnection->queryTab($sSQL, $aBind);
        if (is_array($results) && count($results)>0) {
            $aTerms    = $results;
        }
        $this->value = $aTerms;
    }
}

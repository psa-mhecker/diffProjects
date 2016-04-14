<?php
/**
 * Fichier de Pelican_Cache.
 */
class Frontend_Citroen_Perso_ProduitPrefereLigne extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':PRODUCT_ID'] = $oConnection->strToBind($this->params[1]);

        $sSQL = "
            SELECT
                VEHICULE_GAMME_LABEL
            FROM
              #pref#_perso_product pp
            INNER JOIN #pref#_vehicule v ON (v.VEHICULE_ID = pp.VEHICULE_ID and v.SITE_ID = :SITE_ID)
            WHERE
              pp.SITE_ID = :SITE_ID
            and pp.PRODUCT_ID = :PRODUCT_ID

        ";
        $productLigneLabel = $oConnection->queryItem($sSQL, $aBind);

        $this->value = $productLigneLabel;
    }
}

<?php
/**
 * Fichier de Pelican_Cache
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_Perso_ProduitPrefereGamme extends Pelican_Cache {

    var $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance ();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':PRODUCT_ID'] = $oConnection->strToBind($this->params[1]);

        $sSQL = "
            SELECT
                IFNULL(v.VEHICULE_GAMME_CONFIG,v.VEHICULE_GAMME_MANUAL) GAMME
            FROM
              #pref#_perso_product pp
            INNER JOIN #pref#_vehicule v ON (v.VEHICULE_ID = pp.VEHICULE_ID and v.SITE_ID = :SITE_ID)
            WHERE
              pp.SITE_ID = :SITE_ID
            and pp.PRODUCT_ID = :PRODUCT_ID

        ";
        $productGammeLabel = $oConnection->queryItem($sSQL, $aBind);

        $this->value = $productGammeLabel;
    }
}
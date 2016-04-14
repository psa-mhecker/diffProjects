<?php
/**
 * Fichier de Pelican_Cache : Perso Pages Produit.
 */
class Frontend_Citroen_Perso_ProductPageTrack extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':PRODUCT_PAGE_URL'] = $oConnection->strToBind($this->params[1]);
        $pages = array();

        $sSQL = "
            SELECT
                ppp.PRODUCT_PAGE_ID,
                ppp.SITE_ID,
                ppp.PRODUCT_PAGE_URL,
                ppp.PRODUCT_PAGE_SCORE,
                ppp.PRODUCT_ID,
                ppp.PRODUCT_PAGE_AJAX,
                IFNULL(v.VEHICULE_LCDV6_CONFIG,v.VEHICULE_LCDV6_MANUAL) as lcdv6
            FROM
              #pref#_perso_product_page ppp
              LEFT JOIN #pref#_perso_product pp ON (ppp.PRODUCT_ID=pp.PRODUCT_ID  AND pp.SITE_ID=:SITE_ID)
              LEFT JOIN #pref#_vehicule v ON (pp.VEHICULE_ID = v.VEHICULE_ID AND v.SITE_ID=:SITE_ID)
            where ppp.SITE_ID = :SITE_ID and PRODUCT_PAGE_URL = :PRODUCT_PAGE_URL";

        $results = $oConnection->queryTab($sSQL, $aBind);
        if (is_array($results) && count($results)) {
            $pages = $results;
        }
        $this->value = $pages;
    }
}

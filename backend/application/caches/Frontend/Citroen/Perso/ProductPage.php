<?php
/**
 * Fichier de Pelican_Cache : Perso Pages Produit.
 */
class Frontend_Citroen_Perso_ProductPage extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $this->params[0];
        $pages = array();

        $sSQL = "
            SELECT
                *
            FROM
              #pref#_perso_product_page
            where SITE_ID = :SITE_ID";

        if ($this->params[1] != '') {
            $aBind[':PRODUCT_PAGE_URL'] = $oConnection->strToBind($this->params[1]);
            $sSQL .= " and PRODUCT_PAGE_URL = :PRODUCT_PAGE_URL";

            $results = $oConnection->queryTab($sSQL, $aBind);
            if (is_array($results) && count($results)>0) {
                $pages = $results;
            }
        } else {
            $results = $oConnection->queryTab($sSQL, $aBind);
            if (is_array($results) && count($results)>0) {
                $pages = $results;
                foreach ($results as $result) {
                    $pages['URL'][] = $result['PRODUCT_PAGE_URL'];
                    $pages['URL_PRODUCT_ID'][$result['PRODUCT_PAGE_URL']] = $result['PRODUCT_ID'];
                }
            }
        }
        $this->value = $pages;
    }
}

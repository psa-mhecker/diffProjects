<?php
/**
 * View Vehicule.
 *
 * @version 1.0
 *
 * @since 12/12/2013
 */
class Citroen_View_Helper_Vehicule
{
    /*
     * Récupère la gamme du produit préféré
     *
     * @return string
     */
    public static function getProductGamme($siteId, $productId)
    {
        $productTypeLabel = \Pelican_Cache::fetch("Frontend/Citroen/Perso/ProduitPrefereGamme",
            array($siteId, $productId));

        return $productTypeLabel;
    }

    /*
     * Récupère la ligne du produit préféré
     *
     * @return string
     */
    public static function getProductLigne($siteId, $productId)
    {
        $productTypeLabel = \Pelican_Cache::fetch("Frontend/Citroen/Perso/ProduitPrefereLigne",
            array($siteId, $productId));

        return $productTypeLabel;
    }

    /*
     * Récupère la gamme (VP/VU) du produit correspondant au code LCDV6 passé en paramètre
     *
     * @return string
     */
    public static function getProductGammeByLcdv6($siteId, $lcdv6)
    {
        // Récupération du PRODUCT_ID à partir du code LCDV6
        $products = Pelican_Cache::fetch("Frontend/Citroen/Perso/Lcdv6ByProduct", array($siteId));
        $products = array_filter($products);
        $products = array_flip($products);
        $productId = isset($products[$lcdv6]) ? $products[$lcdv6] : null;

        // Si aucun produit ne correspond au code LCDV6 (car non saisi en BO : Personnalisation/Produits), on sort
        if ($productId === null) {
            return false;
        }

        // Récupération de la gamme à partir du PRODUCT_ID
        return self::getProductGamme($siteId, $productId);
    }
}

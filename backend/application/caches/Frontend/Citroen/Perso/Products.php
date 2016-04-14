<?php
/**
 * Récupération de la liste des produits de personnalisation.
 */
class Frontend_Citroen_Perso_Products extends Pelican_Cache
{
    public $duration = DAY;

    public $isPersistent = true;

    public function getValue()
    {
        // Collecte paramètres
        $siteId = isset($this->params[0]) ? $this->params[0] : null;

        // Construction du statement
        $sqlCond = array('1=1');
        if ($siteId) {
            $bind[':SITE_ID'] = $this->params[0];
            $sqlCond[] = 'pp.SITE_ID = :SITE_ID';
        }
        $stmt = "SELECT pp.* FROM #pref#_perso_product pp WHERE ".implode(' AND ', $sqlCond);

        // Exécution de la requête
        $oConnection = Pelican_Db::getInstance();
        $result = $oConnection->queryTab($stmt, $bind);

        // Indexation des résultats par PRODUCT_ID
        $products = array();
        if (count($result)) {
            foreach ($result as $val) {
                $products[$val['PRODUCT_ID']] = $val;
            }
        }

        $this->value = $products;
    }
}

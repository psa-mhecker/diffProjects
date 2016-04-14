<?php
/**
 * Fichier de Pelican_Cache : Barre Outils.
 */
class Frontend_Citroen_Perso_Score extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $this->params[0];

        $sSQL = "
            SELECT
                *
            FROM
              #pref#_perso_product_page
            WHERE SITE_ID = :SITE_ID

        ";
        $scores = $oConnection->queryTab($sSQL, $aBind);

        $this->value = $scores;
    }
}

<?php
/**
 * Fichier de Pelican_Cache : Barre Outils
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_Perso_Indicateurs extends Pelican_Cache {

    var $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance ();
        $sSQL = "
            SELECT
                *
            FROM
              #pref#_perso_indicateur

        ";
        $aIndicateurs = $oConnection->queryTab($sSQL);

        $this->value = $aIndicateurs;
    }
}
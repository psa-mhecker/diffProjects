<?php
/**
 * Fichier de Pelican_Cache : Barre Outils.
 */
class Frontend_Citroen_Perso_Profils extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $sSQL = "
            SELECT
                *
            FROM
              #pref#_perso_profile

        ";
        $aProfils = $oConnection->queryTab($sSQL);

        $this->value = $aProfils;
    }
}

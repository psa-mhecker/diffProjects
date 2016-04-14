<?php
/**
 */

/**
 * Fichier de Pelican_Cache : Liste des plugins installés.
 *
 * retour : id, lib
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 14/07/2007
 */
class Plugins extends Pelican_Cache
{
    public $duration = UNLIMITED;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $query = "SELECT PLUGIN_ID FROM #pref#_plugin";
        $oConnection->query($query);
        $this->value = $oConnection->data["PLUGIN_ID"];
    }
}

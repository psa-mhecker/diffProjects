<?php

/**
 * Fichier de Pelican_Cache :Selection Vehicules Mon projet.
 */
class Frontend_Citroen_MonProjet_SelectionVehicules extends Pelican_Cache
{
    public $duration = DAY;

    public function getValue()
    {
        $aBind[':ID_USER'] = $this->params[0];
        $sSql = 'SELECT * FROM #pref#_selection_vehicules WHERE citroen_user_id=:ID_USER';
        $oConnection = Pelican_Db::getInstance();
        $aSelection = $oConnection->queryTab($sSql, $aBind);
        $this->value = $aSelection;
    }
}

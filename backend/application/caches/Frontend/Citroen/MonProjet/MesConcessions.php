<?php

/**
 * Fichier de Pelican_Cache : Mes Concessions Mon projet.
 */
class Frontend_Citroen_MonProjet_MesConcessions extends Pelican_Cache
{
    public $duration = DAY;

    public function getValue()
    {
        $aBind[':ID_USER'] = $this->params[0];
        $sSQL = 'select favoris_vn,favoris_sav  from cpp_users as cu where users_pk_id=:ID_USER';

        $oConnection = Pelican_Db::getInstance();
        $aFavs = $oConnection->queryRow($sSQL, $aBind);
        //debug($aFavs);
        $this->value = $aFavs;
    }
}

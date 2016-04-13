<?php

/**
 * @package Cache
 * @subpackage Pelican
 */

/**
 * Fichier de cache : Groupe des reseaux sociaux
 *
 * @package Cache
 * @subpackage Pelican
 * @author Raphael Carles <raphael.carles@businessdecision.com>
 * @since 18/05/2014
 */
class Citroen_GroupeReseauxSociaux extends Pelican_Cache
{

    var $duration = DAY;

    public $isPersistent = true;

    /**
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue ()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        $sSQL = "
                select *
                from #pref#_groupe_reseaux_sociaux
                where SITE_ID = :SITE_ID
                and LANGUE_ID = :LANGUE_ID
                order by GROUPE_RESEAUX_SOCIAUX_LABEL asc";
        $value = $oConnection->queryTab($sSQL, $aBind);
        
        $this->value = $value;
    }
}

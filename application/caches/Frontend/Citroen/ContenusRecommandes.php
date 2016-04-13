<?php
/**
 * Fichier de Pelican_Cache : Contenus Recommandés
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_ContenusRecommandes extends Pelican_Cache {

    var $duration = DAY;
    
    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance ();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        $sContenus = implode(',', $this->params[2]);
        $sSQL = "
            select
                cr.*,
                m.MEDIA_PATH,
                m.MEDIA_ALT
            from #pref#_contenu_recommande cr
            inner join #pref#_media m
            on (cr.MEDIA_ID = m.MEDIA_ID)
            where cr.SITE_ID = :SITE_ID
            and cr.LANGUE_ID = :LANGUE_ID
            and cr.CONTENU_RECOMMANDE_ID in (".$sContenus.")
            order by field(cr.CONTENU_RECOMMANDE_ID,".$sContenus.")
        ";
        $aResults = $oConnection->queryTab($sSQL, $aBind);
        $this->value = $aResults;
    }
}
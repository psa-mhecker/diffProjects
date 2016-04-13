<?php

/**
 * @package Cache
 * @subpackage Config
 */

/**
 * Fichier de Pelican_Cache : Résultat de requête sur PAGE_ZONE_MULTI
 *
 * retour : id, lib
 *
 * @package Cache
 * @subpackage Config
 * @author Joseph Franclin <Joseph.Franclin@businessdecision.com>
 * @since 22/11/2013
 */
class Frontend_Citroen_ZonesContentMulti extends Pelican_Cache {

    var $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue() {
        $oConnection = Pelican_Db::getInstance();

        $aBind = array();
        $aBind[":LANGUE_ID"] = $this->params[0];
        $aBind[":PAGE_ID"] = $this->params[1];        
        $aBind[":CONTENT_ID"] = $this->params[2];
        $aBind[":CONTENT_ZONE_MULTI_TYPE"] = $oConnection->strToBind($this->params[3]);
        // Pour Modification de la requette


        $query = "
             SELECT 
                 *,
                 cv.MEDIA_ID2 as MLP
             FROM #pref#_content_version cv, #pref#_content_zone_multi cz
             WHERE
             cv.CONTENT_ID = :CONTENT_ID 
             AND 
             CONTENT_ZONE_MULTI_TYPE = :CONTENT_ZONE_MULTI_TYPE
             AND
             cv.LANGUE_ID = :LANGUE_ID
             AND
             cz.LANGUE_ID = :LANGUE_ID
             AND
             cz.CONTENT_ZONE_ID = :PAGE_ID
             AND
             cv.PAGE_ID = :PAGE_ID
             AND
             cv.CONTENT_VERSION = (SELECT MAX(CONTENT_VERSION) FROM #pref#_content_version WHERE CONTENT_ID = :CONTENT_ID)
             AND
             cz.CONTENT_ID = cv.CONTENT_ID
             AND
             cz.CONTENT_VERSION = (SELECT MAX(CONTENT_VERSION) FROM #pref#_content_version WHERE CONTENT_ID = :CONTENT_ID) 
             ";
        $this->value = $oConnection->queryTab($query, $aBind);
    }

}

?>
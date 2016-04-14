<?php
/**
 * Fichier de Pelican_Cache : récupération des valeurs des multis d'une Pelican_Index_Frontoffice_Zone spécifique dans un gabarit.
 *
 * retour : id, lib
 *
 * @author Raphaël Carles <rcarles@businessdecision.fr>
 *
 * @since 25/02/2006
 */
class Frontend_Page_ZoneMulti extends Pelican_Cache
{
    public $duration = WEEK;
    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[':PAGE_ID'] = $this->params[0];
        $aBind[':ZONE_TEMPLATE_ID'] = $this->params[1];
        $aBind[':SITE_ID'] = $this->params[2];
        $aBind[':LANGUE_ID'] = $this->params[3];
        if ($this->params [4]) {
            $type_version = $this->params [4];
        } else {
            $type_version = "CURRENT";
        }
        $sSQL = "
			SELECT
				pzm.*
            FROM
				#pref#_page_zone_multi pzm
            INNER JOIN #pref#_page p
            ON (p.PAGE_ID = pzm.PAGE_ID
                AND p.PAGE_".$type_version."_VERSION = pzm.PAGE_VERSION
                AND p.LANGUE_ID = pzm.LANGUE_ID
            )
            WHERE p.SITE_ID = :SITE_ID
            AND p.LANGUE_ID = :LANGUE_ID
            AND pzm.ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
            ORDER BY PAGE_ZONE_MULTI_ID asc";
        $this->value = $oConnection->queryTab($sSQL, $aBind);
    }
}
